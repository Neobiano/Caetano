<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

</head>
<body>

<?php   
    $nome_relatorio = "pesquisa_de_satisfacao"; // NOME DO RELATÃâ€œRIO (UTILIZAR UNDERLINE, POIS Ãâ€° PARTE DO NOME DO ARQUIVO EXCEL)
    $titulo = "Pesquisa de Satisfação"; // MESMO NOME DO INDEX
    $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃÆ’O IMPRIMIR BOTÃÆ’O EXCEL
    include "inicia_variaveis_grafico.php";
    $inicio = defineTime();
    
    //IMPRIME TÍTULO DA CONSULTA
    echo '<div id="divtitulo" class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
    echo "<b>$titulo</b>";
    echo "<br><br><b>Obs:</b> À partir de 01/03/2017 a pesquisa de satisfação considera somente as perguntas e respostas 3 e 4 para realização dos calculos";
    echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à  $data_final_texto";
    echo '</div>';
    
    include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
    include "inicia_tabela_organizada.php"; // INICIA A TABELA
    
    // IMPRIME COLUNAS DA TABELA - INÍCIO
    $texto = "<td><b>VDN &nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>CÓD. FILA&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>FILA &nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>QTDE LIGAÇÕES* &nbsp</b>
            	         <span class='tooltiptext'>Quantidade de ligações transferidas à pesquisa de satisfação</span>
            	      </td>";
    
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>PESQ. REALIZADAS* &nbsp</b>
            	         <span class='tooltiptext'>Quantidade de ligações em que houve resposta à pesquisa de satisfação</span>
            	      </td>";    
    echo incrementa_tabela($texto);   
   
    $texto = "<td><b>REALIZ. (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>SATISFEITO*</b>
            	         <span class='tooltiptext'>TOTALMENTE satisfeito</span>
            	      </td>";      
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>SATISFEITO (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>INSATISFEITO*</b>
            	         <span class='tooltiptext'>TOTALMENTE insatisfeito</span>
            	      </td>";    
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>INSATISFEITO (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>INDIFERENTE*</b>
            	         <span class='tooltiptext'>TOTALMENTE indiferente</span>
            	      </td>";    
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>INDIFERENTE (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "</tr></thead><tbody>";
    echo incrementa_tabela($texto);
    // IMPRIME COLUNAS DA TABELA - FIM
    
    echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
    
    $sOR = '';
    $sANDi = '';
    $sANDs = '';
    $sANDind = '';
    
    //criterio para as pesquisas respondidas antes de 01/03 com 4 perguntas na pesquisa
    if ($data_inicial < '03/01/2018') 
    {
    
        //pelo menos uma pergunta respondida
        $sOR = ' or b.perg1 > 0 or b.perg2 > 0 ';
    
        //totalmente insatisfeito
        $sANDi = ' and b.perg1 = 3 and b.perg2 = 3 ';
    
        //totalmente satisfeito
        $sANDs =  ' and b.perg1 = 1 and b.perg2 = 1 ';
    
        //totalmente indiferente
        $sANDind = ' and b.perg1 = 2 and b.perg2 = 2 ';
    }
	
	$sql =  "select cod_fila, count(callid) qtde_ligacoes,
                	/*Pequisas realizadas*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
                				and t.cod_fila = a.cod_fila
                			) as b
                		where (b.perg3 > 0 or b.perg4 >0 $sOR )	
                	) qtde_pesq,
                    
                    /*Pequisas realizadas TOTAL*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999'  				
                			) as b
                		where (b.perg3 > 0 or b.perg4 >0 $sOR)	
                	) qtde_pesq_geral,
                	
                    /*Qtde Insatisfeito*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
                				and t.cod_fila = a.cod_fila
                			) as b
                		where (   b.perg3 = 3 and b.perg4 =3 $sANDi )	
                	) qtde_insatisfeito,
                	/*Qtde satisfeito*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
                				and t.cod_fila = a.cod_fila
                			) as b
                		where (b.perg3 = 1 and b.perg4 =1 $sANDs)	
                	) qtde_satisfeito,
	
                	/*Qtde Indiferente*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
                				and t.cod_fila = a.cod_fila
                			) as b
                		where (b.perg3 = 2 and b.perg4 = 2 $sANDind)	
                	) qtde_indiferente
                	
                from 
                		(
                			select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao 
                			where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                			
                		) as  a
            group by cod_fila
            order by cod_fila";
	
	//echo $sql;
	$query = $pdo->prepare($sql);
	$query->execute(); // EXECUTA A CONSULTA
	
	$soma_qtde_insatisfeito = 0;
	$soma_qtde_ligacoes = 0;
	$soma_qtde_indiferente = 0;
	$soma_qtde_satisfeito = 0;
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $qtde_pesq_geral = utf8_encode($row['qtde_pesq_geral']);
	    $qtde_pesq = utf8_encode($row['qtde_pesq']);
	    $qtde_ligacoes = utf8_encode($row['qtde_ligacoes']);
	    $qtde_satisfeito = utf8_encode($row['qtde_satisfeito']);
	    $qtde_insatisfeito = utf8_encode($row['qtde_insatisfeito']);
	    $qtde_indiferente = utf8_encode($row['qtde_indiferente']);
	    
	    if ($qtde_pesq > 0)
	    { 	        
    	    $texto = '<tr>';
    	    echo incrementa_tabela($texto);
    	    
    	    $vdn_atual = utf8_encode($row['cod_fila']);    
    	    $vdn_nome = "vdn_$vdn_atual";
    	    $desc_fila = $$vdn_nome;
    	    if(isset($desc_cod[$desc_fila]))
    	       $cod_fila = $desc_cod[$desc_fila];
    	    else
    	       $cod_fila = $vdn_atual;
    	        
    	    $texto = "<td>$vdn_atual</td>";
    	    echo incrementa_tabela($texto);
    	       
    	    $texto = "<td>$cod_fila</td>";
    	    echo incrementa_tabela($texto);
    	       	       	    
    	    $texto = "<td>$desc_fila</td>";
    	    echo incrementa_tabela($texto);
    	        	    
    	    $texto = "<td>$qtde_ligacoes</td>";
    	    $soma_qtde_ligacoes += $qtde_ligacoes;
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$qtde_pesq</td>";
    	    echo incrementa_tabela($texto);
    	       
    	    $pct_pesq_realizada = (($qtde_pesq / $qtde_ligacoes) * 100.00);
    	    $imprime = number_format($pct_pesq_realizada, 2, ',', '.');
    	    $texto = "<td><b>$imprime%</b></td>";
    	    echo incrementa_tabela($texto);
    	                	       	           	        	        	       	        	        	    
    	    $texto = "<td>$qtde_satisfeito</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $pct_satisfeito = (($qtde_satisfeito/$qtde_pesq) * 100.00);
    	    $imprime = number_format($pct_satisfeito, 2, ',', '.');
    	    $texto = "<td><b>$imprime%</b></td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$qtde_insatisfeito</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $pct_isatisfeito = (($qtde_insatisfeito/$qtde_pesq) * 100.00);
    	    $imprime = number_format($pct_isatisfeito, 2, ',', '.');
    	    $texto = "<td><b>$imprime%</b></td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$qtde_indiferente</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $pct_indiferente = (($qtde_indiferente/$qtde_pesq) * 100.00);
    	    $imprime = number_format($pct_indiferente, 2, ',', '.');
    	    $texto = "<td><b>$imprime%</b></td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = '</tr>';
    	    echo incrementa_tabela($texto);
    	    
    	    $soma_qtde_insatisfeito += $qtde_insatisfeito;
    	    $soma_qtde_satisfeito += $qtde_satisfeito;
    	    $soma_qtde_indiferente += $qtde_indiferente;
    	    $qtd_linhas_consulta++;
	    }
	}
	
	echo "</tbody><tr class='w3-indigo'>";
	$tabela = $tabela."<tr>";

	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b></b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b></b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE LIGAÇÕES-------------//
	$imprime = number_format($soma_qtde_ligacoes, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE PESQUISADA-------------//
	$imprime = number_format($qtde_pesq_geral, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------PCT(%) PESQUISADA-------------//
	$pct_realizado_geral = (($qtde_pesq_geral/$soma_qtde_ligacoes) * 100.00);	
	$imprime = number_format($pct_realizado_geral, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE SATISFEITO-------------//
	$imprime = number_format($soma_qtde_satisfeito, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------PCT(%) SATISFEITO-------------//
	$pct_satisfeito_geral = (($soma_qtde_satisfeito/$qtde_pesq_geral) * 100.00);	
	$imprime = number_format($pct_satisfeito_geral, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE  INSATISFEITO-------------//
	$imprime = number_format($soma_qtde_insatisfeito, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------PCT  INSATISFEITO-------------//
	$pct_isatisfeito_geral = (($soma_qtde_insatisfeito/$qtde_pesq_geral) * 100.00);	
	$imprime = number_format($pct_isatisfeito_geral, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE  INDIFERENTE-------------//
	$imprime = number_format($soma_qtde_indiferente, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------PCT  INDIFERENTE-------------//
	$pct_indiferente_geral = (($soma_qtde_indiferente/$qtde_pesq_geral) * 100.00);
	$imprime = number_format($pct_indiferente_geral, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);

    $texto = '</tr>';
    echo incrementa_tabela($texto);
       
    include "finaliza_tabela.php"; // FINALIZA A TABELA
            
    //ILHAS DE ATENDIMENTO   
    $soma_qtde_insatisfeito = 0;
    $soma_qtde_ligacoes = 0;
    $soma_qtde_indiferente = 0;
    $soma_qtde_satisfeito = 0;
    $soma_qtde_pesq = 0;
    
    $ilhas = array
    (
        array("CXA_APP_CARTAO","92522"),
        array("AVISO DE VIAGEM","92532"),
        array("COBRANÇA","92051,92052,92503,92057,92511"),
        array("CONTESTAÇÃO","92012,92037,92045,92504,92513"),
        array("GERAL","92018,92021,92033,92019,92020,92032,92063,92061"),
        array("ILHA PJ","92049,92048,92541,92542,92056"),
        array("PARCELAMENTO","92016,92022,92034,92523,92528"),
        array("PROGRAMA DE PONTOS","92062,92529,92502"),
        array("RETENÇÃO","92014,92023,92035,92507,92530"),
        array("TRIAGEM PREVENTIVA","92531,92508"),
        array("PERDA & ROUBO","92008")
       
    );
    
    echo "<div class='w3-container w3-tiny w3-margin w3-padding w3-card-4 w3-border'>";    
    echo "<table class='w3-table w3-hoverable w3-striped w3-padding w3-tiny w3-margin-top'>";
    echo "<tr class='w3-indigo'>";
    $texto = "<td><b>ILHA &nbsp</b></td>";
    echo incrementa_tabela($texto);
           
    $texto = "<td><b>VDNs&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>QTDE LIGAÇÕES* &nbsp</b>
            	         <span class='tooltiptext'>Quantidade de ligações transferidas à pesquisa de satisfação</span>
            	      </td>";
    
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>PESQ. REALIZADAS* &nbsp</b>
            	         <span class='tooltiptext'>Quantidade de ligações em que houve resposta à pesquisa de satisfação</span>
            	      </td>";
    echo incrementa_tabela($texto);   
    
    $texto = "<td><b>REALIZ. (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>SATISFEITO*</b>
            	         <span class='tooltiptext'>TOTALMENTE satisfeito</span>
            	      </td>";    
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>SATISFEITO (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>INSATISFEITO*</b>
            	         <span class='tooltiptext'>TOTALMENTE insatisfeito</span>
            	      </td>";    
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>INSATISFEITO (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td class='tooltip'><b>INDIFERENTE*</b>
            	         <span class='tooltiptext'>TOTALMENTE indiferente</span>
            	      </td>";

    echo incrementa_tabela($texto);
    
    $texto = "<td><b>INDIFERENTE (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "</tr></thead><tbody>";
    echo incrementa_tabela($texto);
    
    foreach($ilhas as $ilha){
        $sql =  "
                select sum(qtde_ligacoes) qtde_ligacoes,
                	   sum(qtde_pesq) qtde_pesq,
                	   sum(qtde_insatisfeito) qtde_insatisfeito,
                	   sum(qtde_satisfeito) qtde_satisfeito,
                	   sum(qtde_indiferente) qtde_indiferente
                from
                		(
                            select cod_fila, count(callid) qtde_ligacoes,
                        	/*Pequisas realizadas*/
                        	(
                        		select count(distinct callid)
                        		from
                        			(
                        				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                        				where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                        				and t.cod_fila = a.cod_fila
                        			) as b
                        		where (b.perg3 > 0 or b.perg4 >0 $sOR )
                        	) qtde_pesq,
                        	
                            /*Pequisas realizadas TOTAL*/
                        	(
                        		select count(distinct callid)
                        		from
                        			(
                        				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                        				where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                        			) as b
                        		where (b.perg3 > 0 or b.perg4 >0 $sOR)
                        	) qtde_pesq_geral,
                        	
                            /*Qtde Insatisfeito*/
                        	(
                        		select count(distinct callid)
                        		from
                        			(
                        				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                        				where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                        				and t.cod_fila = a.cod_fila
                        			) as b
                        		where (   b.perg3 = 3 and b.perg4 =3 $sANDi )
                        	) qtde_insatisfeito,
                        	/*Qtde satisfeito*/
                        	(
                        		select count(distinct callid)
                        		from
                        			(
                        				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                        				where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                        				and t.cod_fila = a.cod_fila
                        			) as b
                        		where (b.perg3 = 1 and b.perg4 =1 $sANDs)
                        	) qtde_satisfeito,
                        	
                        	/*Qtde Indiferente*/
                        	(
                        		select count(distinct callid)
                        		from
                        			(
                        				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                        				where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                        				and t.cod_fila = a.cod_fila
                        			) as b
                        		where (b.perg3 = 2 and b.perg4 = 2 $sANDind)
                        	) qtde_indiferente
                        	
                        from
                        		(
                        			select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao
                        			where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                        			and cod_fila in ($ilha[1])
                        		) as  a
                       group by cod_fila
		          ) as x";
        
        //echo $sql;
        $query = $pdo->prepare($sql);
        $query->execute(); // EXECUTA A CONSULTA
        for($i=0; $row = $query->fetch(); $i++)
        {
            $qtde_pesq = utf8_encode($row['qtde_pesq']);        
            $qtde_ligacoes = utf8_encode($row['qtde_ligacoes']);
            $qtde_satisfeito = utf8_encode($row['qtde_satisfeito']);
            $qtde_insatisfeito = utf8_encode($row['qtde_insatisfeito']);
            $qtde_indiferente = utf8_encode($row['qtde_indiferente']);
        }
        
        if ($qtde_pesq > 0)
        { 
        
            echo "<tr>";
            echo "<td>$ilha[0]</td>";
            echo "<td>$ilha[1]</td>";
            
            echo "<td>$qtde_ligacoes</td>";
            $soma_qtde_ligacoes += $qtde_ligacoes;
                        
            echo  "<td>$qtde_pesq</td>";                       
            $pct_pesq_realizada = (($qtde_pesq / $qtde_ligacoes) * 100.00);
            $imprime = number_format($pct_pesq_realizada, 2, ',', '.');
            echo  "<td><b>$imprime%</b></td>";
                        
            echo  "<td>$qtde_satisfeito</td>";                       
            $pct_satisfeito = (($qtde_satisfeito/$qtde_pesq) * 100.00);
            $imprime = number_format($pct_satisfeito, 2, ',', '.');
            echo  "<td><b>$imprime%</b></td>";
                        
            echo  "<td>$qtde_insatisfeito</td>";                     
            $pct_isatisfeito = (($qtde_insatisfeito/$qtde_pesq) * 100.00);
            $imprime = number_format($pct_isatisfeito, 2, ',', '.');
            echo "<td><b>$imprime%</b></td>";
            
            
            echo  "<td>$qtde_indiferente</td>";                        
            $pct_indiferente = (($qtde_indiferente/$qtde_pesq) * 100.00);
            $imprime = number_format($pct_indiferente, 2, ',', '.');
            echo "<td><b>$imprime%</b></td>";                                                                                            
            echo "</tr>";
            
            $soma_qtde_pesq += $qtde_pesq;
            $soma_qtde_satisfeito += $qtde_satisfeito;
            $soma_qtde_indiferente += $qtde_indiferente;
            $soma_qtde_insatisfeito += $qtde_insatisfeito;
        }
        
    }
    
    echo "</tbody><tr class='w3-indigo'>";
    $tabela = $tabela."<tr>";
       
    $texto = "<td><b>TOTALIZADOR</b></td>";
    echo incrementa_tabela($texto);
   
    //-----------VDNs-------------//    
    echo  "<td><b></b></td>";
    
    //-----------QTDE LIGAÇÕES-------------//
    $imprime = number_format($soma_qtde_ligacoes, 0, ',', '.');
    echo  "<td><b>$imprime</b></td>";
        
    //-----------QTDE PESQUISADA-------------//
    $imprime = number_format($soma_qtde_pesq, 0, ',', '.');
    echo  "<td><b>$imprime</b></td>";
        
    //-----------PCT(%) PESQUISADA-------------//
    $pct_realizado_geral = (($qtde_pesq_geral/$soma_qtde_ligacoes) * 100.00);
    $imprime = number_format($pct_realizado_geral, 2, ',', '.');
    echo  "<td><b>$imprime%</b></td>";
        
    //-----------QTDE SATISFEITO-------------//
    $imprime = number_format($soma_qtde_satisfeito, 0, ',', '.');
    echo  "<td><b>$imprime</b></td>";
       
    //-----------PCT(%) SATISFEITO-------------//
    $pct_satisfeito_geral = (($soma_qtde_satisfeito/$soma_qtde_pesq) * 100.00);
    $imprime = number_format($pct_satisfeito_geral, 2, ',', '.');
    echo  "<td><b>$imprime%</b></td>";
    
    //-----------QTDE  INSATISFEITO-------------//
    $imprime = number_format($soma_qtde_insatisfeito, 0, ',', '.');
    echo  "<td><b>$imprime</b></td>";
       
    //-----------PCT  INSATISFEITO-------------//
    $pct_isatisfeito_geral = (($soma_qtde_insatisfeito/$soma_qtde_pesq) * 100.00);
    $imprime = number_format($pct_isatisfeito_geral, 2, ',', '.');
    echo  "<td><b>$imprime%</b></td>";
       
    //-----------QTDE  INDIFERENTE-------------//
    $imprime = number_format($soma_qtde_indiferente, 0, ',', '.');
    echo  "<td><b>$imprime</b></td>";
        
    //-----------PCT  INDIFERENTE-------------//
    $pct_indiferente_geral = (($soma_qtde_indiferente/$soma_qtde_pesq) * 100.00);
    $imprime = number_format($pct_indiferente_geral, 2, ',', '.');
    echo  "<td><b>$imprime%</b></td>";        
    echo  '</tr>';     
   
    echo "</table></div>";
    
    for($j=(($data_inicial >='03/01/2018') ? 3 : 1);$j<=4;$j++)
    {
        $perg = "perg$j";
        echo "<div class='w3-container w3-tiny w3-margin w3-padding w3-card-4 w3-border'>";
        
        echo "<table class='w3-table w3-hoverable w3-striped w3-padding w3-tiny w3-margin-top'>";
        echo "<tr class='w3-indigo'>";
        
        if($perg == 'perg1') echo "<td><b>No Geral, qual seu grau de satisfação£o?</b></td>";
        if($perg == 'perg2') echo "<td><b>Quanto ao tempo de espera, você se considera:</b></td>";
        if($perg == 'perg3') echo "<td><b>Quanto a  cordialidade do atendente, voce se considera:</b></td>";
        if($perg == 'perg4') echo "<td><b>A solicitação foi atendida ao final do atendimento?</b></td>";
        
        echo "<td class='w3-right'><b>QUANTIDADE</b></td>";
        echo "</tr>";
        
        $query = $pdo->prepare("select $perg resposta, count(*) total from tb_pesq_satisfacao
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
							group by $perg");
        $query->execute();
        
        $array_respostas = array();
        
        $resultado = array();
        $resultado[1] = 0;
        $resultado[2] = 0;
        $resultado[3] = 0;
        $resultado[-1] = 0;
        $resultado[-2] = 0;
        $resultado[0] = 0;
        
        $resultado['TOTAL_DE_RESPOSTAS'] = 0;
        
        for($i=0; $row = $query->fetch(); $i++){
            $resposta = utf8_encode($row['resposta']);
            $total = utf8_encode($row['total']);
            
            array_push($array_respostas, $resposta);
            
            if($resposta == '1') $resultado[1] = $total;
            if($resposta == '2') $resultado[2] = $total;
            if($resposta == '3') $resultado[3] = $total;
            if($resposta == '-1') $resultado[-1] = $total;
            if($resposta == '-2') $resultado[-2] = $total;
            if($resposta == '0') $resultado[O] = $total;
            
            $resultado['TOTAL_DE_RESPOSTAS'] = $resultado['TOTAL_DE_RESPOSTAS'] + $total;
            
        }
        
        foreach($array_respostas as $resposta){
            $total = $resultado[$resposta];
            $porcentagem = $total / $resultado['TOTAL_DE_RESPOSTAS'] * 100;
            $porcentagem = number_format($porcentagem, 2, ',', '.');
            echo "<tr>";
            if($resposta == '1') echo "<td>Satisfeito</td>";
            if($resposta == '2') echo "<td>Indiferente</td>";
            if($resposta == '3') echo "<td>Insatisfeito</td>";
            if($resposta == '-1') echo "<td>Erro</td>";
            if($resposta == '-2') echo "<td>Sem Interação</td>";
            if($resposta == '0') echo "<td>Opção Inválida</td>";
            echo "<td class='w3-right'>$total ($porcentagem%)</td>";
            echo "</tr>";
        }
        
        $TOTAL_DE_RESPOSTAS = $resultado['TOTAL_DE_RESPOSTAS'];
        echo "<tr>";
        echo "<td><b>TOTAL</b></td>";
        echo "<td class='w3-right'><b>$TOTAL_DE_RESPOSTAS</b></td>";
        echo "</tr>";
        echo "</table></div>";
    }
    
    //include"imprime_grafico.php"; // IMPRIME O GRÁFICO
    $fim = defineTime();
    echo tempoDecorrido($inicio,$fim);
?>

</body>
</html>

<script>  
document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 
$('#tabela').DataTable( {
	 "columnDefs": [ {
     "targets": [ 0 ],
     "orderable": false
   } ]
} );
</script>