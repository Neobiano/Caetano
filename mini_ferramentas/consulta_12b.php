<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

</head>
<body>

<?php
    //$data_atual = date("d-m-Y");
   
    $nome_relatorio = "dns"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
    $titulo = "Dispersão do Nível de Serviço por Faixa de Horário"; // MESMO NOME DO INDEX
    $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
    include "inicia_variaveis_grafico.php";

    $dmm_imprime = $dmm;
    $dmm = explode(",", $dmm);

    
    

    //DEFINE QUANTIDADE DE DIAS DE CADA MÊS
    
    if($qual_mes=='02') 
    {
    	if ($qual_ano%4 != 0) 
    	   $qtd_dias = 28;
    	else 
    	   $qtd_dias = 29;
    }
    
    if($qual_mes=='01') $qtd_dias = 31;
    if($qual_mes=='03') $qtd_dias = 31;
    if($qual_mes=='04') $qtd_dias = 30;
    if($qual_mes=='05') $qtd_dias = 31;
    if($qual_mes=='06') $qtd_dias = 30;
    if($qual_mes=='07') $qtd_dias = 31;
    if($qual_mes=='08') $qtd_dias = 31;
    if($qual_mes=='09') $qtd_dias = 30;
    if($qual_mes=='10') $qtd_dias = 31;
    if($qual_mes=='11') $qtd_dias = 30;
    if($qual_mes=='12') $qtd_dias = 31;

    $dia_atual = $today = date("d");
    $mes_atual = $today = date("m");

    // DEFINE VARIÁVEL $MES POR EXTENSO
    if ($qual_mes == '01') $mes = 'Janeiro';
    if ($qual_mes == '02') $mes = 'Fevereiro';
    if ($qual_mes == '03') $mes = 'Março';
    if ($qual_mes == '04') $mes = 'Abril';
    if ($qual_mes == '05') $mes = 'Maio';
    if ($qual_mes == '06') $mes = 'Junho';
    if ($qual_mes == '07') $mes = 'Julho';
    if ($qual_mes == '08') $mes = 'Agosto';
    if ($qual_mes == '09') $mes = 'Setembro';
    if ($qual_mes == '10') $mes = 'Outubro';
    if ($qual_mes == '11') $mes = 'Novembro';
    if ($qual_mes == '12') $mes = 'Dezembro';


    $SOMA_MULT = 0;
    $SOMA_TOTAL_ATEND = 0;
    $SOMA_NSH = 0;
    $SOMA_A = 0;
    $SOMA_B = 0;
    $SOMA_C = 0;
    $SOMA_TOTAL_ATEND = 0;

    if ($qual_mes == $mes_atual) 
       $qtd_dias = $dia_atual - 1;

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
		echo "<b>$titulo</b>";
		echo "<br><br><b>Período de Consulta:</b> $mes/$qual_ano";
		echo "<br><b>DMM:</b> $dmm_imprime";
		echo "<br><b>Filas:</b> $in_filas";		
	echo '</div>';
	
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-margin-top w3-tiny w3-left w3-padding">';
		echo "<br><b class='w3-text-black'>Legenda:</b>";
		echo "<br><br><b class='w3-text-black'>DNS:</b> Dispersão de Nível de Serviço por Faixa de Horário;";
		echo "<br><b class='w3-text-black'>NSA:</b> Nível de Serviço Ponderado Acumulado Mensal;";
		echo "<br><b class='w3-text-black'>NSH:</b> Média Simples dos Níveis de Serviço Apurados por Faixa de Horário Acumulados Mensais;";
		echo "<br><b class='w3-text-black'>A:</b> Número de atendimentos onde o cliente esperou menos do que o tempo em segundos definido e/ou dentro dos prazos estipulados pela CAIXA;";
		echo "<br><b class='w3-text-black'>B:</b> Soma de todos os atendimentos;";
		echo "<br><b class='w3-text-black'>C:</b> Chamadas abandonadas com espera superior ao tempo em segundos definido e/ou superior aos prazos estipulados pela CAIXA, ou não atendidos.";
		echo "<br><b class='w3-text-black'>Fórmula DNS:</b> DNS = 1 – (│NSA – NSH│– 5%)";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DIA &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL DE ATENDIMENTOS &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>A &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>B &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>C &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TEMPO DE ESPERA PADRÃO &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
		
	$soma_nsh_dia = 0;
	$soma_atendimentos_dia = 0;
	$soma_mult_dia = 0;
	
	$qtd_dias_div = $qtd_dias;
	
	//----------limpando tabela de consolidação do NSH------------------- 
	$query = $pdo->prepare("   IF (OBJECT_ID('tempdb..##temp_consolidado') IS NOT NULL) 			 						
			                     drop table ##temp_consolidado
		
		                      CREATE TABLE ##temp_consolidado (dia INT, hora INT, minuto INT, A INT, B INT, C INT, NSA FLOAT ) 
                           ");
	$query->execute();
	//$pos_dia<=25/*
	for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++)
	{
	    //$pos_dia= 9;
	    $revisao_NS = false;
	    
		$qtd_linhas_consulta++;				
	
	    //validando revisão de nivel
	    $sql = "select t.*,
                    datepart(dd,data_1) d_1,
                    datepart(mm,data_1) m_1,
                    datepart(yyyy,data_1) a_1,
                    datepart(dd,data_2) d_2,
                    datepart(mm,data_2) m_2,
                    datepart(yyyy,data_2) a_2,
                    datepart(dd,data_3) d_3,
                    datepart(mm,data_3) m_3,
                    datepart(yyyy,data_3) a_3,
                    TME_1,
                    TME_2,
                    TME_3
                from tb_fat_revisao_nivel_DNS t
                where t.data_revista = '$qual_ano-$qual_mes-$pos_dia'";
	    
	    $query = $pdo->prepare($sql);
	    $query->execute();
	    for($x=0; $row = $query->fetch(); $x++)
	    {	   
	        $tme_1 =  $row['TME_1'];
	        $tme_2 =  $row['TME_2'];
	        $tme_3 =  $row['TME_3'];
	        
	        $a_1 =  $row['a_1'];
	        $a_2 =  $row['a_2'];
	        $a_3 =  $row['a_3'];
	        
	        $d_1 =  $row['d_1'];
	        $d_2 =  $row['d_2'];
	        $d_3 =  $row['d_3'];
	        
	        $m_1 =  $row['m_1'];
	        $m_2 =  $row['m_2'];
	        $m_3 =  $row['m_3'];
	        
	        
	        $d_1 = ($d_1 <= 9) ? ('0'.$d_1) : $d_1;
	        $d_2 = ($d_2 <= 9) ? ('0'.$d_2) : $d_2;
	        $d_3 = ($d_3 <= 9) ? ('0'.$d_3) : $d_3;
	        
	        $m_1 = ($m_1 <= 9) ? ('0'.$m_1) : $m_1;
	        $m_2 = ($m_2 <= 9) ? ('0'.$m_2) : $m_2;
	        $m_3 = ($m_3 <= 9) ? ('0'.$m_3) : $m_3;    
	        
	        $revisao_NS = true;	        	        
	    } 
	    
	    if(in_array($pos_dia,$dmm))
	       $ns = 90;
	    else
	        $ns = 45;
       
        
	    
	    $sql = "select A, B, C,
                                        ISNULL(
                                                cast(ISNULL(A, 0) as float)
                                                /
                                                nullif(
                                                        cast(ISNULL(B, 0) as float)
                                                        +
                                                        cast(ISNULL(C, 0) as float)
                                                      ,0)
                                               ,1) NSA,
                                         ISNULL(B, 0) TOTAL_ATEND,
                                         ISNULL(
                                                  cast(ISNULL(A, 0) as float)
                                                  /
                                                  nullif(
                                                          cast(ISNULL(B, 0) as float)
                                                          +
                                                          cast(ISNULL(C, 0) as float)
                                                         ,0)
                                                ,1) * ISNULL(B, 0) MULT
                                from
            				    (
            				        select
                            				(
                                				select coalesce(count(*),0) A from tb_eventos_dac
                                				where data_hora between 'ano-mes-dia' and 'ano-mes-dia 23:59:59' and cod_fila in ($in_filas)
                                				and tempo_espera <= ns_dia and tempo_atend > 0
                                                and id_operador <> 'NULL'
                            				) as A,
                            				(
                                				select coalesce(count(*),0) B from tb_eventos_dac
                                				where data_hora between 'ano-mes-dia' and 'ano-mes-dia 23:59:59' and cod_fila in ($in_filas)
                                				and tempo_atend > 0
                                                and id_operador <> 'NULL'
                            				) as B,
                            				(
                                				select coalesce(count(*),0) C from tb_eventos_dac
                                				where data_hora between 'ano-mes-dia' and 'ano-mes-dia 23:59:59' and cod_fila in ($in_filas)
                                				and tempo_espera > ns_dia and tempo_atend = 0

                            				) as C
            				    ) as NSA";
	    
	    //com revisão de nivel de serviço para a data
	    if ($revisao_NS)
	    {	  
	        
	        $cor = " bgcolor='yellow' ";
	        $A = 0;
	        $B = 0;
	        $C = 0;
	        $TOTAL_ATEND = 0;
	        $MULT = 0;
	        
	        /*-------data 1-------------*/ 
	        $sqlaux = str_replace('ano-mes-dia', $a_1.'-'.$m_1.'-'.$d_1,$sql);  
	        $sqlaux = str_replace('ns_dia', $tme_1,$sqlaux);
	        $query = $pdo->prepare($sqlaux);
	        $query->execute();
	        for($i=0; $row = $query->fetch(); $i++)
	        {	            	            
	            $TOTAL_ATEND = $TOTAL_ATEND + $row['TOTAL_ATEND'];
	            $MULT = $MULT + $row['MULT'];	    
	            $A = $A + $row['A'];
	            $B = $B + $row['B'];
	            $C = $C + $row['C'];
	        }
	        
	        /*-------data 2-------------*/
	        $sqlaux = str_replace('ano-mes-dia', $a_2.'-'.$m_2.'-'.$d_2,$sql);
	        $sqlaux = str_replace('ns_dia', $tme_2,$sqlaux);
	        $query = $pdo->prepare($sqlaux);
	        $query->execute();
	        for($i=0; $row = $query->fetch(); $i++)
	        {
	            $TOTAL_ATEND = $TOTAL_ATEND + $row['TOTAL_ATEND'];
	            $MULT = $MULT + $row['MULT'];
	            $A = $A + $row['A'];
	            $B = $B + $row['B'];
	            $C = $C + $row['C'];
	        }
	        
	        /*-------data 3-------------*/
	        $sqlaux = str_replace('ano-mes-dia', $a_3.'-'.$m_3.'-'.$d_3,$sql);
	        $sqlaux = str_replace('ns_dia', $tme_3,$sqlaux);
	        //echo $sqlaux;
	        $query = $pdo->prepare($sqlaux);
	        $query->execute();
	        for($i=0; $row = $query->fetch(); $i++)
	        {
	            $TOTAL_ATEND = $TOTAL_ATEND + $row['TOTAL_ATEND'];
	            $MULT = $MULT + $row['MULT'];
	            $A = $A + $row['A'];
	            $B = $B + $row['B'];
	            $C = $C + $row['C'];
	        }
	        
	        $TOTAL_ATEND = intval($TOTAL_ATEND / 3);
	        $MULT = intval($MULT/3);
	        $A = intval($A/3);
	        $B = intval($B/3);
	        $C = intval($C/3);	
	        $NSA = $A / ($B+$C);
	    }  
	    else //sem revisão de nivel de serviço para a data
	    {     
	        $cor = " ";
	        
	        $sdata = ($qual_ano.'-'.$qual_mes.'-'.$pos_dia);
	        $sqlaux = str_replace('ano-mes-dia',$sdata,$sql);
	        $sqlaux = str_replace('ns_dia', $ns,$sqlaux);
	       // if ($pos_dia == 2) 
	        //  echo $sqlaux;
	        $query = $pdo->prepare($sqlaux);
	        $query->execute();
	        for($i=0; $row = $query->fetch(); $i++)
	        {
	            $NSA = utf8_encode($row['NSA']);
	            $TOTAL_ATEND = utf8_encode($row['TOTAL_ATEND']);
	            $MULT = utf8_encode($row['MULT']);
	            
	            $A = utf8_encode($row['A']);	           	                
                $B = utf8_encode($row['B']);	           	                    
                $C = utf8_encode($row['C']);	           	
	        }
	        
	    }
       	
	    //totalizando e imprimindo
		$SOMA_TOTAL_ATEND = $SOMA_TOTAL_ATEND + $TOTAL_ATEND;
		$SOMA_MULT = $SOMA_MULT + $MULT;
		$SOMA_A = $SOMA_A + $A;
		$SOMA_B = $SOMA_B + $B;
		$SOMA_C = $SOMA_C + $C;
		
		echo "<tr>";
		if ($pos_dia < 10)
	       $pos_dia_imprime = "0$pos_dia";
		else
		   $pos_dia_imprime = "$pos_dia";
        
		if ($revisao_NS)
		   echo "<td $cor><a class='w3-text-indigo' title='Listar Faixas de Horário' href= \"lista_nsa_revisao_ns.php?pos_dia=$pos_dia&qual_ano=$qual_ano&qual_mes=$qual_mes&mes=$mes&ns=$ns&in_filas=$in_filas\" target=\"_blank\">$pos_dia_imprime</a></td>";
		else
		   echo "<td>$pos_dia_imprime</td>";
		echo "<td $cor>$TOTAL_ATEND</td>";
		echo "<td $cor>$A</td>";
		echo "<td $cor>$B</td>";
		echo "<td $cor>$C</td>";
		echo "<td $cor>$ns</td>";
			
	
		//-----------------------------------------------------NSH------------------------------------------------------------
		if ($revisao_NS)
		{		    
		    $sql = "
            		drop table #temp_plano_horas;
            		drop table #tabela_a;
            		drop table #tabela_b;
            		drop table #tabela_c;
            		
                    /*retorna somente o agrupamento de horas de um dia de 24 horas */
                    select   datepart(dd,data_hora) DIA,
            				 datepart(hh,data_hora) HORA,
            				 datepart(minute,data_hora)/30 MINUTO
            		into #temp_plano_horas
            		from tb_eventos_dac where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59'
                    group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
    		        order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
    		        
                    /*-------------tabela A - quantidade de atendidas dentro do tempo estipulado (45 ou 90) por intervalo---------*/
                    
                    /*Data 1- Revisao NS*/
                    select
            		        datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) A
            	    into #tabela_a
            		from tb_eventos_dac where data_hora between '$a_1-$m_1-$d_1' and '$a_1-$m_1-$d_1 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend > 0
                    and tempo_espera <= $tme_1
                    and id_operador <> 'NULL'
                    group by datepart(dd,data_hora),datepart(hh,data_hora), datepart(minute,data_hora)/30
    		        order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30

                    /*Data 2- Revisao NS*/
                    insert into #tabela_a
                    select
            		        datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) A            	    
            		from tb_eventos_dac where data_hora between '$a_2-$m_2-$d_2' and '$a_2-$m_2-$d_2 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend > 0
                    and tempo_espera <= $tme_2
                    and id_operador <> 'NULL'
                    group by datepart(dd,data_hora),datepart(hh,data_hora), datepart(minute,data_hora)/30
    		        order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
    		        
                    /*Data 3- Revisao NS*/
                    insert into #tabela_a
                    select
            		        datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) A            	    
            		from tb_eventos_dac where data_hora between '$a_3-$m_3-$d_3' and '$a_3-$m_3-$d_3 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend > 0
                    and tempo_espera <= $tme_3
                    and id_operador <> 'NULL'
                    group by datepart(dd,data_hora),datepart(hh,data_hora), datepart(minute,data_hora)/30
    		        order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30

                    
                    /*------------tabela B - quantidade de atendidas geral por intervalo------------------------------------------*/

                    /*Data 1- Revisao NS*/
            		select	datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) B
            	    into #tabela_b
            		from tb_eventos_dac where data_hora between '$a_1-$m_1-$d_1' and '$a_1-$m_1-$d_1 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend > 0 and id_operador <> 'NULL'
            		group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
            		order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30

                    /*Data 2- Revisao NS*/
                    insert into #tabela_b
            		select	datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) B            	    
            		from tb_eventos_dac where data_hora between '$a_2-$m_2-$d_2' and '$a_2-$m_2-$d_2 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend > 0 and id_operador <> 'NULL'
            		group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
            		order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30

                    /*Data 3- Revisao NS*/
                    insert into #tabela_b
            		select	datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) B            	    
            		from tb_eventos_dac where data_hora between '$a_3-$m_3-$d_3' and '$a_3-$m_3-$d_3 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend > 0 and id_operador <> 'NULL'
            		group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
            		order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30

                    
            		
                    /*-------------tabela C - Quantidade de abandonadas acima do tempo estipulado (45 ou 90)---------------------*/
                    /*Data 1- Revisao NS*/
            		select	datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) C
            		into #tabela_c
            		from tb_eventos_dac where data_hora between '$a_1-$m_1-$d_1' and '$a_1-$m_1-$d_1 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend = 0 and tempo_espera > $tme_1
            		group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
            		order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30

                    /*Data 2- Revisao NS*/
                    insert into #tabela_c
            		select	datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) C            		
            		from tb_eventos_dac where data_hora between '$a_2-$m_2-$d_2' and '$a_2-$m_2-$d_2 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend = 0 and tempo_espera > $tme_2
            		group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
            		order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30

                    /*Data 3- Revisao NS*/
                    insert into #tabela_c
            		select	datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA,
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) C            		
            		from tb_eventos_dac where data_hora between '$a_3-$m_3-$d_3' and '$a_3-$m_3-$d_3 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend = 0 and tempo_espera > $tme_3
            		group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
            		order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
                                		

                	/*-------------tabela de consolidação dos NSA por faixa de horario---------------------*/
                    insert into ##temp_consolidado 
            		select $pos_dia dia , t.hora, t.minuto, avg(coalesce(A,0)), avg(coalesce(B,0)) B, avg(coalesce(C,0)) C,
            		 (
            			 avg(coalesce(A,0)) / (
            										case (avg(coalesce(B,0)) + avg(coalesce(C,0))) 
            											when 0 then 1
            											else (avg(coalesce(B,0)) + avg(coalesce(C,0))) 
            										end
            									   )
            		) NSA
            		from #temp_plano_horas t 
            		left join #tabela_a a on ( a.HORA = t.HORA and a.MINUTO = t.MINUTO) 
            		left join #tabela_b b on ( b.HORA = t.HORA and b.MINUTO = t.MINUTO) 
            		left join #tabela_c c on ( c.HORA = t.HORA and c.MINUTO = t.MINUTO) 
            		group by t.hora, t.minuto 
            		order by t.hora, t.minuto                                             
                    ";
		    
		    
		}
		else
		{		    
    		$sql = "
            		drop table #temp_plano_horas;
            		drop table #tabela_a;
            		drop table #tabela_b;
            		drop table #tabela_c;        		
                    
                    /*retorna somente o agrupamento de horas de um dia de 24 horas */                    
                    select   datepart(dd,data_hora) DIA, 
            				 datepart(hh,data_hora) HORA, 
            				 datepart(minute,data_hora)/30 MINUTO 
            		into #temp_plano_horas
            		from tb_eventos_dac where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59'
                    group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30 
    		        order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30                 
                    
                    /*-------------tabela A - quantidade de atendidas dentro do tempo estipulado (45 ou 90) por intervalo---------*/              
                    select  
            		        datepart(dd,data_hora) DIA, 
            				datepart(hh,data_hora) HORA, 
            				datepart(minute,data_hora)/30 MINUTO, 
            				count (*) A 
            	    into #tabela_a
            		from tb_eventos_dac where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend > 0 
                    and tempo_espera <= $ns
                    and id_operador <> 'NULL'
                    group by datepart(dd,data_hora),datepart(hh,data_hora), datepart(minute,data_hora)/30 
    		        order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30
    
                    /*------------tabela B - quantidade de atendidas geral por intervalo------------------------------------------*/
            		select	datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA, 
            				datepart(minute,data_hora)/30 MINUTO, 
            				count (*) B 
            	    into #tabela_b
            		from tb_eventos_dac where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend > 0 and id_operador <> 'NULL' 
            		group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30 	
            		order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30 	
    
                    /*-------------tabela C - Quantidade de abandonadas acima do tempo estipulado (45 ou 90)---------------------*/
            		select	datepart(dd,data_hora) DIA,
            				datepart(hh,data_hora) HORA, 
            				datepart(minute,data_hora)/30 MINUTO,
            				count (*) C 
            		into #tabela_c
            		from tb_eventos_dac where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59'
                    and cod_fila in ($in_filas)
                    and tempo_atend = 0 and tempo_espera > $ns 
            		group by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30 
            		order by datepart(dd,data_hora), datepart(hh,data_hora), datepart(minute,data_hora)/30                
                    
                	/*-------------tabela de consolidação dos NSA por faixa de horario---------------------*/
                    insert into ##temp_consolidado
            			select t.dia, t.hora, t.minuto, coalesce(A,0) A, coalesce(B,0) B, coalesce(C,0) C,
            			ISNULL(cast(ISNULL(A, 0) as float) / nullif(cast(ISNULL(B, 0) as float) + cast(ISNULL(C, 0) as float),0),1) NSA 					 
            			from #temp_plano_horas t
            			left join #tabela_a a on ( a.DIA = t.DIA and a.HORA = t.HORA and a.MINUTO = t.MINUTO)
            			left join #tabela_b b on ( b.DIA = t.DIA and b.HORA = t.HORA and b.MINUTO = t.MINUTO)
            			left join #tabela_c c on ( c.DIA = t.DIA and c.HORA = t.HORA and c.MINUTO = t.MINUTO)        			                
                    ";
		}	
						
		$query = $pdo->prepare($sql);
		$query->execute();								
	
		echo "</tr>";
	}
	
	echo "</tbody><tr class='w3-indigo'>";
	
	if($SOMA_TOTAL_ATEND > 0) 
	    $NSA_MENSAL = $SOMA_A / ($SOMA_B + $SOMA_C);
	else 
	    $NSA_MENSAL = 1;		
		
	echo "<td><b></b></td>";
    echo "<td><b>NSA <i>(Média Ponderada)</i>: $NSA_MENSAL</b></td>";	
	echo "<td><b></b></td>";
	echo "<td><b></b></td>";
	echo "<td><b></b></td>";	
	echo "<td><b></b></td>";		    
	echo "</tr>";
	echo "</table>";
	echo "</div>";
	
    include "finaliza_tabela.php"; 
    
    //--------------------------------SEGUNDA TABELA------------------------------//
    echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-margin-top w3-tiny w3-left w3-padding">';
    echo "<br><b class='w3-text-black'>Apuração do NSH (Média simples do NSA por faixa de horário no mês) e Cálculo Final do DNS</b>";
    echo "<br><br>";    
    echo '</div>';
    
    echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important;">';
    echo "<table id='tabela2' name='tabela2' class='w3-table w3-striped w3-hoverable w3-tiny'>";
    echo "<thead>
                <tr class='w3-indigo'>";    
    echo "<td><b>HORA &nbsp</b></td>";
    echo "<td><b>INTERVALO &nbsp</b></td>";
    echo "<td><b>A &nbsp</b></td>";
    echo "<td><b>B &nbsp</b></td>";
    echo "<td><b>C &nbsp</b></td>";
    echo "<td><b>NSA = A / (B + C) &nbsp</b></td>";
    echo "  </tr>
              </thead>
              <tbody>";
    
    //geral
    $qtde = 0;
    $soma_nsa = 0;
    $query = $pdo->prepare("select hora, minuto, sum(A) A, sum(B) B, sum(C) C,
                             (
                            	  coalesce(sum(A),0)
                            	  /
                            	     cast (
                            				(
                            				  case (coalesce(sum(B),0) + coalesce(sum(C),0))
                            					when  0 then 1
                            					else (coalesce(sum(B),0) + coalesce(sum(C),0))
                            				  end 
                            				  )
                            			 as float)
                              ) NSA
                              from ##temp_consolidado
                             group by hora, minuto 
                             order by hora, minuto ");
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {                        
        $hora = utf8_encode($row['hora']);
        $intervalo = utf8_encode($row['minuto']);
        $NSA = utf8_encode($row['NSA']);
                
        $A = utf8_encode($row['A']);
        if($A == NULL)
            $A = 0;
            
        $B = utf8_encode($row['B']);
        if($B == NULL)
            $B = 0;
            
        $C = utf8_encode($row['C']);
        if($C == NULL)
            $C = 0;
                    
        echo "<tr>";
            echo "<td>$hora</td>";
            echo "<td>$intervalo</td>";
            echo "<td>$A</td>";
            echo "<td>$B</td>";
            echo "<td>$C</td>";
            echo "<td>$NSA</td>";
        echo "</tr>";
        $qtde++;
        $soma_nsa = ($soma_nsa + $NSA); 
    }
    $nsh = $soma_nsa / $qtde;
    
    $dif = $NSA_MENSAL - $nsh;
    $redutor = 0;
    if ($dif < 0)
      $dif = $dif * - 1;
    
    if ($dif <= 0.05)
    {    
      $dns = 1;
      $cor =' bgcolor="green"';
    }
    else
    {
        $redutor = ($dif - 0.05);
        $dns = 1 - $redutor;
        $cor =' bgcolor="red"';
    }  
     
    $dif = number_format(($dif * 100), 4,',', '.');
    $redutor = number_format(($redutor * 100),4,',', '.');    
     
    echo "</tbody>
            <tr class='w3-indigo'>";    
            echo "<td $cor><b>DNS: $dns</b></td>";
            echo "<td $cor><b>Dif:</b> $dif%   -  <b>Redutor:</b> $redutor%</td>";
            echo "<td><b></b></td>";    
            echo "<td><b>NSA (Média Ponderada): $NSA_MENSAL</b></td>";            
            echo "<td><b></b></td>";
            echo "<td><b>NSH: $nsh</b></td>";
    echo "</tr>";
    echo "</table>";
    echo "</div>";    
    
    //--------------------------------TERCEIRA TABELA------------------------------//
    echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-margin-top w3-tiny w3-left w3-padding">';
    echo "<br><b class='w3-text-black'>Distribuição de atendimentos por Dia/Faixa de Horário</b>";
    echo "<br><br>";
    echo '</div>';
    echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important;">';
    echo "<table id='tabela3' name='tabela3' class='w3-table w3-striped w3-hoverable w3-tiny'>";
        echo "<thead>
                <tr class='w3-indigo'>";            
                    echo "<td><b>DIA &nbsp</b></td>";        
                    echo "<td><b>HORA &nbsp</b></td>";       
                    echo "<td><b>INTERVALO &nbsp</b></td>";        
                    echo "<td><b>A &nbsp</b></td>";        
                    echo "<td><b>B &nbsp</b></td>";        
                    echo "<td><b>C &nbsp</b></td>";                                      
        echo "  </tr>
              </thead>
              <tbody>";
    
    //geral
    $query = $pdo->prepare("select * from ##temp_consolidado");
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $qtd_linhas_consulta++;
        $dia = utf8_encode($row['dia']);
        $hora = utf8_encode($row['hora']);
        $intervalo = utf8_encode($row['minuto']);
        $NSA = utf8_encode($row['NSA']);
                        
        
        $A = utf8_encode($row['A']);
        if($A == NULL)
            $A = 0;
            
        $B = utf8_encode($row['B']);
        if($B == NULL)
            $B = 0;
                
        $C = utf8_encode($row['C']);
        if($C == NULL)
            $C = 0;
        
        echo "<tr>";
            if ($dia < 10)
                $dia_imprime = "0$dia";
            else
                $dia_imprime = "$dia";
            
            echo "<td>$dia_imprime</td>";
            echo "<td>$hora</td>";
            echo "<td>$intervalo</td>";
            echo "<td>$A</td>";
            echo "<td>$B</td>";
            echo "<td>$C</td>";        
          
        echo "</tr>";
    }
    
    echo "</tbody>
            <tr class='w3-indigo'>";
            echo "<td><b></b></td>";
            echo "<td><b></b></td>";
            echo "<td><b></b></td>";
            echo "<td><b></b></td>";
            echo "<td><b></b></td>";
            echo "<td><b></b></td>";
                    
    echo "</tr>";
    echo "</table>";
    echo "</div>";    
    
?>

    <script>  
    $('#tabela').DataTable( {
    	"order": [[ 0, "asc" ]]
    } );
    
    $('#tabela2').DataTable( {
    	"order": [[ 0, "asc" ]]
    } );

    $('#tabela3').DataTable( {
    	"order": [[ 0, "asc" ]]
    } );
    </script>

</body>
</html>

