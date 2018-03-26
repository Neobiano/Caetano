﻿<?php
$nome_relatorio = "percentual_de_retencao_ura"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Percentual de Retenção na URA"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";
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
	
	$texto = "<td><b>TOTAL RECEBIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL RETIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PERCENTUAL DE RETENÇÃO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL RECEBIDAS (LÍQ)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL RETIDAS (LÍQ)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PERCENTUAL DE RETENÇÃO (LÍQ)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
    /*se check box (considerar eventos sem interação estiver desmarcado, então exclui-se da consultas estas ligação*/
	$sAND = '';
	if ($ruchk_1 <> '1') 
	{
       $sAND =   "   and cod_evento <> '001;004;084'
                     and cod_evento <> '001;004;084;010;086;057'
                     and cod_evento <> '001;004;083'
                     and cod_evento <> '001;004;084;005'
                     and cod_evento <> '001;004;003'
                     and cod_evento <> '001;004;003'
                ";	
	}    
	
	$data_inicial = new DateTime( $data_inicial );
	$data_final = new DateTime($data_final);
	date_add($data_final,date_interval_create_from_date_string("1 days"));
	//$data_final =  date('d/m/Y', strtotime("+1 days",strtotime($data_final)));
	
	$daterange = new DatePeriod($data_inicial, new DateInterval('P1D'), $data_final);
	
	foreach($daterange as $data)
	{	
	    //verificando se a data em questão está entre os dias da semana selecionados
	    $dayofweek = date('w', strtotime(date("Y-m-d H:i:s")));
	    $dayofweek++;//compatibilizando o dayofweek do php (0 a 6) com o do sqlserver (1 a 7) 
	    if (strpos($in_semana,"$dayofweek"))
	    {    
	    
    	    $sdata = $data->format('Y-m-d');    
    	    
    	    $qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA	 
    	    $var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
    	    
    	    //----------------------------------BRUTO----------------------------------//
    	    //Total recebidas da URA 
    	    $sql = "select datepart(dw,data_hora) dia_semana, count(distinct t.callid) qtde from tb_eventos_ura t
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
    	    $sql = " select count(distinct t2.callid) qtde from tb_eventos_dac t2
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
    	    $sql = "    select count(distinct t.callid) qtde from tb_eventos_ura t 
                        where t.data_hora between '$sdata' and '$sdata 23:59:59.999'$sAND
                        and datepart(dw,t.data_hora) in $in_semana
                        and t.callid not in 
                        (   --lista de callids que iniciaram os atendimentos pelas filas que não possuem funcionalidade na ura 
                        	select distinct t2.callid 
                            from tb_eventos_dac t2 
                        	where t2.data_hora between '$sdata' and '$sdata 23:59:59.999'
                        	and t2.cod_fila in (63,64,99,100,110,130) 
                            and t2.tempo_atend > 0 
                            and datepart(dw,t2.data_hora) in $in_semana
                        	and t2.data_hora = ( 
                        							select min(data_hora) 
                        							from tb_eventos_dac t3 
                        							where t3.data_hora between '$sdata' and '$sdata 23:59:59.999'	
                                                    and datepart(dw,t.data_hora) in $in_semana							  
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
    				select count(distinct t2.callid) qtde from tb_eventos_dac t2 
    				where t2.data_hora between '$sdata' and '$sdata 23:59:59.999'
    				and t2.cod_fila not in (63,64,99,100,110,130) 
                    and t2.tempo_atend > 0
                    and datepart(dw,t2.data_hora) in $in_semana 
    				and t2.data_hora = ( 
    										select min(data_hora) 
    										from tb_eventos_dac t3 
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
    	    $pct_retencao_bruto = number_format(utf8_encode($pct_retencao_bruto), 2, ',', '.') ;
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
	
	$pct_retencao_liquido = number_format($pct_retencao_liquido, 2, ',', '.');
	$texto = "<td><b>$pct_retencao_liquido%</b></td>";
	echo incrementa_tabela($texto);	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php";// IMPRIME O GRÁFICO
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