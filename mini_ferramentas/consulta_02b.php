<?php
$nome_relatorio = "percentual_de_retencao_ura"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Percentual de Retenção na URA"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";
$dados_grafico = "['Data','PCT(%) Retidas','PCT(%) Retidas LIQ','Benchmark']";
$pointSize = 2;
$inicio = defineTime();


//VARIÁVEIS TOTALIZADORAS
$soma_recebidas = 0;
$soma_retidas = 0;
$soma_recebidas_liquido = 0;
$soma_retidas_liquido = 0;

//IMPRIME TÍTULO DA CONSULTA

echo '<div id="divtitulo" class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo "<br><br><b style='color: red'>Observação:</b> Os campos que contém \"LIQ\" não contabilizam as filas que não possuem funcionalidades na URA.";
echo "</div>";

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DATA</b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td><b>DIA DA SEMANA</b></td>";
	echo incrementa_tabela($texto);	
	
	$texto = "<td class='tooltip'><b>TOTAL RECEBIDAS *</b>
    <span class='tooltiptext'>Total de ligações recebidas na URA</span>
    </td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td class='tooltip'><b>TOTAL RETIDAS *</b>
    <span class='tooltiptext'>Total de ligações recebidas e NÃO transferidas para o ATC (atendimento humano)</span>
    </td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PCT. RETENÇÃO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td class='tooltip'><b>TOTAL RECEBIDAS (LÍQ) *</b>
    <span class='tooltiptext'>Total de ligações recebidas, desconsiderando as filas que NÃO possuem serviço na URA (63,64,99,100,110,130)</span>
    </td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td class='tooltip'><b>TOTAL RETIDAS (LÍQ) *</b>
     <span class='tooltiptext'>Total de ligações recebidas e NÃO transferidas para o ATC (atendimento humano), desconsiderando as filas que NÃO possuem serviço na URA (63,64,99,100,110,130)</span>
    </td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PCT. RETENÇÃO (LÍQ)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
    /*se check box (considerar eventos sem interação estiver desmarcado, então exclui-se da consultas estas ligação*/
	$sAND = '';
	if ($ruchk_1 <> '1') 
	{
	   /*
	     '%001' - 'Morreu' a ligação logo na entrada da ura 
         '%004' - 'Morreu' a ligação logo na entrada na 'porta'        
         '%082' - Canal amazonia
         '%083' - Canal Premium                      
         '%084' - Canal NIG
         '%086' - Solicitação de CPF
         '%086;057' - Solicitação de CPF e não preencheu nada
                     
	    */ 
       $sAND =   "   and cod_evento not like '%001' 
                     and cod_evento not like '%004'                  
                     and cod_evento not like '%082'
                     and cod_evento not like '%083'                       
                     and cod_evento not like '%084'
                     and cod_evento not like '%086'
                     and cod_evento not like '%086;057'                   
                ";	
	}    
	//totalização
	$soma_recebidas_bruto = 0;
	$soma_retidas_bruto  = 0;
	
	$soma_recebidas_liquido = 0;
	$soma_retidas_liquido = 0;	
	
	
	$data_inicial = new DateTime( $data_inicial );
	$data_final = new DateTime($data_final);
	date_add($data_final,date_interval_create_from_date_string("1 days"));
	//$data_final =  date('d/m/Y', strtotime("+1 days",strtotime($data_final)));
	
	$daterange = new DatePeriod($data_inicial, new DateInterval('P1D'), $data_final);
	
	foreach($daterange as $data)
	{	
	    //verificando se a data em questão está entre os dias da semana selecionados
	    //$dayofweek = date('w', strtotime(date("Y-m-d H:i:s")));
	    $dayofweek = date('w', strtotime($data->format('Y-m-d H:i:s')));
	    $dayofweek++;//compatibilizando o dayofweek do php (0 a 6) com o do sqlserver (1 a 7) 
	    if (strpos($in_semana,"$dayofweek"))
	    {    
	        $var_graf = 0;
    	    $sdata = $data->format('Y-m-d');    
    	    
    	    $qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA	 
    	    $var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
    	    
    	    //----------------------------------BRUTO----------------------------------//
    	    //Total recebidas da URA 
    	    $sql = "select datepart(dw,data_hora) dia_semana, count(distinct t.callid) qtde from tb_eventos_ura t (nolock)
    	            where data_hora between '$sdata' and '$sdata 23:59:59.999'$sAND
    	            and datepart(dw,t.data_hora) in $in_semana
                    group by datepart(dw,data_hora)";
    	    //echo $sql;
    	    $query = $pdo->prepare($sql);
    	    $query->execute();
    	    
    	    for($i=0; $row = $query->fetch(); $i++){
    	        $dia_semana = utf8_encode($row['dia_semana']);
    	        $total_recebidas_bruto = utf8_encode($row['qtde']);
    	     	        
    	    }
    	    
    	    //Total encaminhadas para o Humano, NÃO RETIDAS 
    	    $sql = " select count(distinct t2.callid) qtde from tb_eventos_dac t2 (nolock)
    	             where t2.data_hora  between '$sdata' and '$sdata 23:59:59.999'
                     and datepart(dw,t2.data_hora) in $in_semana 
    	             and t2.tempo_atend > 0 ";
    	    
    	    //echo $sql;
    	    $query = $pdo->prepare($sql);
    	    $query->execute();
    	    
    	    for($i=0; $row = $query->fetch(); $i++){	       
    	        $total_humano_bruto = utf8_encode($row['qtde']);	        
    	    }
            
    	   
    	    $total_retidas_bruto = $total_recebidas_bruto - $total_humano_bruto;	    	    	    
    	    $pct_retencao_bruto = ($total_retidas_bruto /$total_recebidas_bruto) * 100;
    	    
    	    //--------------------------------Liquido--------------------------------------------
    	    //Total recebidas liquidas na ura sem contabilizar as chamadas das filas que NÃO POSSUEM serviço válido na URA 
    	    $sql = "    select count(distinct t.callid) qtde from tb_eventos_ura t (nolock) 
                        where t.data_hora between '$sdata' and '$sdata 23:59:59.999'$sAND
                        and datepart(dw,t.data_hora) in $in_semana
                        and t.callid not in 
                        (   --lista de callids que iniciaram os atendimentos pelas filas que não possuem funcionalidade na ura 
                        	select distinct t2.callid 
                            from tb_eventos_dac t2 (nolock) 
                        	where t2.data_hora between '$sdata' and '$sdata 23:59:59.999'
                        	and t2.cod_fila in (63,64,99,100,110,130) 
                            and t2.tempo_atend > 0 
                            and datepart(dw,t2.data_hora) in $in_semana
                        	and t2.data_hora = ( 
                        							select min(data_hora) 
                        							from tb_eventos_dac t3 (nolock) 
                        							where t3.data_hora between '$sdata' and '$sdata 23:59:59.999'	
                                                    and datepart(dw,t3.data_hora) in $in_semana							  
                        							and t3.callid = t2.callid
                        						)           							                							
                        )";
    	    //echo $sql;
    	    $query = $pdo->prepare($sql);
    	    $query->execute();
    	    
    	    for($i=0; $row = $query->fetch(); $i++){
    	        $total_recebidas_liquido = utf8_encode($row['qtde']);	            
    	    }
    	    
    	    //Atendimentos NÃO retidos na URA, ou seja, originados nas filas que possuem serviço na ura (NOT IN (63,64,99,100,110,130))	    
    	    $sql = "
    				select count(distinct t2.callid) qtde from tb_eventos_dac t2 (nolock) 
    				where t2.data_hora between '$sdata' and '$sdata 23:59:59.999'
    				and t2.cod_fila not in (63,64,99,100,110,130) 
                    and t2.tempo_atend > 0
                    and datepart(dw,t2.data_hora) in $in_semana 
    				and t2.data_hora = ( 
    										select min(data_hora) 
    										from tb_eventos_dac t3 (nolock) 
    										where t3.data_hora between '$sdata' and '$sdata 23:59:59.999'
                                            and datepart(dw,t3.data_hora) in $in_semana						  
    										and t3.callid = t2.callid														
    									)    ";
    	    //echo $sql;
    	    $query = $pdo->prepare($sql);
    	    $query->execute();
    	    
    	    for($i=0; $row = $query->fetch(); $i++){
    	        $total_humano_liquido = utf8_encode($row['qtde']);
    	    }
    	    
    	    $total_retidas_liquido = ($total_recebidas_liquido - $total_humano_liquido);	    
    	    $pct_retencao_liquido = ($total_retidas_liquido /$total_recebidas_liquido) * 100;
    	    
    	    //totalização
    	    $soma_recebidas_bruto += $total_recebidas_bruto;
    	    $soma_retidas_bruto  += $total_retidas_bruto;
    	    
    	    $soma_recebidas_liquido += $total_recebidas_liquido;
    	    $soma_retidas_liquido += $total_retidas_liquido;	
    	    
    	    //------------------impressões--------------
    	    $dia = date('w', strtotime($sdata));
    	    $dia_semana = diaSemana($dia+1);
    	   
    	    $total_recebidas_bruto = number_format(utf8_encode($total_recebidas_bruto), 0, ',', '.');
    	    $total_retidas_bruto = number_format(utf8_encode($total_retidas_bruto), 0, ',', '.'); 
    	    $total_recebidas_liquido = number_format(utf8_encode($total_recebidas_liquido), 0, ',', '.'); 
    	    $total_retidas_liquido = number_format(utf8_encode($total_retidas_liquido), 0, ',', '.'); 
    	    $pct_retencao_bruto_grafico = number_format($pct_retencao_bruto, 2, '.', '');
    	    $pct_retencao_bruto = number_format(utf8_encode($pct_retencao_bruto), 2, ',', '.') ;
    	    $pct_retencao_liquido_grafico = number_format($pct_retencao_liquido, 2, '.', '');
    	    $pct_retencao_liquido = number_format(utf8_encode($pct_retencao_liquido), 2, ',', '.');
    	    
    	    
    	        
    	    // IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
    	    $texto = '<tr>';
    	    echo incrementa_tabela($texto);   
    	    $sdata = date("d-m-Y", strtotime($sdata));
    	    $texto = "<td>$sdata</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$dia_semana</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$total_recebidas_bruto</td>";
    	    echo incrementa_tabela($texto);
    	    	    
    	    $texto = "<td>$total_retidas_bruto</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$pct_retencao_bruto%</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$total_recebidas_liquido</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$total_retidas_liquido</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$pct_retencao_liquido%</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = '</tr>';
    	    echo incrementa_tabela($texto);
    	    
    	    //dados do grafico
    	    $incrementa_grafico = $incrementa_grafico.",['$sdata ($dia_semana)'"; // INCREMENTA OS DADOS DO GRÁFICO
    	    $benchmark = number_format('55.85', 2, '.', '');
    	    if($pct_retencao_bruto_grafico > $max)
    	        $max = $pct_retencao_bruto_grafico; // ALTERA O VALOR MÁXIMO DE 'Y' DO GRÁFICO
    	     if($pct_retencao_bruto_grafico < $min)
    	        $min = $pct_retencao_bruto_grafico; // ALTERA O VALOR MÍNIMO DE 'Y' DO GRÁFICO
    	     
    	    $incrementa_grafico = $incrementa_grafico.",$pct_retencao_bruto_grafico,$pct_retencao_liquido_grafico,$benchmark]"; // INCREMENTA OS DADOS DO GRÁFICO
	    } 	  
	}
	
	$media_pct_retidas_bruto =  $soma_retidas_bruto / $soma_recebidas_bruto * 100;
	$media_pct_retidas_liq =  $soma_retidas_liquido / $soma_recebidas_liquido * 100;
	
    
$texto = "</tbody><tr class='w3-indigo'>";
echo incrementa_tabela($texto);

	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$soma_recebidas_bruto = number_format($soma_recebidas_bruto, 0, ',', '.');
	$texto = "<td><b>$soma_recebidas_bruto</b></td>";
	echo incrementa_tabela($texto);
	
	$soma_retidas_bruto = number_format($soma_retidas_bruto, 0, ',', '.');
	$texto = "<td><b>$soma_retidas_bruto</b></td>";
	echo incrementa_tabela($texto);
	
	$media_pct_retidas_bruto = number_format($media_pct_retidas_bruto, 2, ',', '.');
	$texto = "<td><b>$media_pct_retidas_bruto%</b></td>";
	echo incrementa_tabela($texto);
	
	$soma_recebidas_liquido = number_format($soma_recebidas_liquido, 0, ',', '.');
	$texto = "<td><b>$soma_recebidas_liquido</b></td>";
	echo incrementa_tabela($texto);
	
	$soma_retidas_liquido = number_format($soma_retidas_liquido, 0,',', '.');
	$texto = "<td><b>$soma_retidas_liquido</b></td>";
	echo incrementa_tabela($texto);
	
	$pct_retencao_liquido = number_format($media_pct_retidas_liq, 2, ',', '.');
	$texto = "<td><b>$pct_retencao_liquido%</b></td>";
	echo incrementa_tabela($texto);	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
$parametros_adicionais = " pointSize: 2,";
include "imprime_grafico.php"; // IMPRIME O GRÁFICO
$fim = defineTime();
echo tempoDecorrido($inicio,$fim);
?>

<script>  
document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 

$('#tabela').DataTable( {
	 "columnDefs": [ {
      "targets": [ 0 ],
      "orderable": false
    } ]
} );
</script>