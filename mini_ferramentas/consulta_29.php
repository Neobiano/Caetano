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
    <link rel="stylesheet" type="text/css" href="css/dataTables.css">  
    <script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

    <script>
        $(document).ready( function () {
            $('#tabela').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
</head>

<body>

       <?php
        /*filtros*/
       $swhere = '';
       if ($chk_visa)
       {
           if ($swhere <> '')
               $swhere = $swhere.' or ';
               
               $swhere = $swhere." bandeira = 'VISA'";
       }
       
       if ($chk_elo)
       {
           if ($swhere <> '')
               $swhere = $swhere.' or ';
               
               $swhere = $swhere." bandeira = 'ELO'";
       }
       
       if ($chk_master)
       {
           if ($swhere <> '')
               $swhere = $swhere.' or ';
               
               $swhere = $swhere." bandeira = 'MASTERCARD'";
       }
       
       if ($chk_jcb)
       {
           if ($swhere <> '')
               $swhere = $swhere.' or ';
               
               $swhere = $swhere." bandeira = 'JCB'";
       }
       
      
       
        $sql = '';                                                   
        $nome_relatorio = "Retencao_ATC"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Retenção ATC - Análise de Dados"; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
                	                                                  
        echo '<div class="w3-margin w3-tiny w3-center">'; 
        echo "<b>$titulo</b>";
        echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto ";                
        echo "<br><br>";
        
            echo '<div class="w3-border" style="padding:16px 16px;">';
                echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
                    echo '<thead>
                                <tr class="w3-indigo w3-tiny">';
                                echo '<td><b>Data</b></td>';
                                echo '<td><b>Bandeira</b></td>';                               
                                echo '<td><b>Atendimentos</b></td>';
                                echo '<td><b>Não Retidos</b></td>';
                                echo '<td><b>Retidos</b></td>';
                                echo '<td><b>%</b></td>';
                                echo '<td><b>Ret. Desconto</b></td>';
                                echo '<td><b>%</td>';
                                echo '<td><b>Ret. Argumentação </b></td>';
                                echo '<td><b>%</td>';
                        echo '</tr>
                          </thead>
                            <tbody>';
                      
                                $sql ="	select 
                                		cast(data_hora as date) data,
                                        datepart(dw,data_hora) dia_semana,  
                                		bandeira,
                                		count(*) qtde,
                                		( 
                                			select count(*) 
                                			from tb_dados_retencao t1 (nolock)
                                			where cast(t1.data_hora as date) = cast(t.data_hora as date) 
                                			and t1.tipo_retencao = 'CARTÃO NÃO RETIDO'
                                			and t1.bandeira = t.bandeira
                                		) nao_retido,
                                		( 
                                			select count(*) 
                                			from tb_dados_retencao t2 (nolock)
                                			where cast(t2.data_hora as date) = cast(t.data_hora as date) 
                                			and t2.tipo_retencao = 'DESCONTO DE ANUIDADE'
                                			and t2.bandeira = t.bandeira
                                		) ret_desc,
                                		( 
                                			select count(*) 
                                			from tb_dados_retencao t3 (nolock)
                                			where cast(t3.data_hora as date) = cast(t.data_hora as date) 
                                			and t3.tipo_retencao = 'ARGUMENTAÇÃO'
                                			and t3.bandeira = t.bandeira
                                		) ret_arg
                                         
                                		from tb_dados_retencao t (nolock)
                                		where t.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59'
                                        and ($swhere)	
                                	    group by cast(data_hora as date), datepart(dw,data_hora),bandeira 
                                		order by cast(data_hora as date), bandeira                                        
                                          
                                        ";                                                                         
                                
                                //echo $sql;
                                $total_qtde = 0;
                                $total_retido = 0;
                                $total_retido_desc = 0;
                                $total_retido_arg = 0;
                                
                                $query = $pdo->prepare($sql);
                                $query->execute();                                
                                for($i=0; $row = $query->fetch(); $i++)
                                {
                                    $pct_retido = 0;
                                    $pct_ret_arg = 0;
                                    $pct_ret_desc = 0;
                                    
                                    $data = utf8_encode($row['data']);	
                                    $dia_semana = $row['dia_semana'];                                    
                                    $data = date("Y-m-d", strtotime($data));
                                                                       
                                    $dia_semana = diaSemana($dia_semana);                                    
                                    $bandeira = utf8_encode($row['bandeira']);	
                                    $qtde = utf8_encode($row['qtde']);
                                    $nao_retido = utf8_encode($row['nao_retido']);
                                    $ret_desc = utf8_encode($row['ret_desc']);
                                    $ret_arg = utf8_encode($row['ret_arg']);
                                    $ret_total = $ret_desc + $ret_arg;
                                    
                                    $total_qtde = $total_qtde + $qtde;
                                    $total_retido = $total_retido + $ret_total;
                                    $total_retido_desc = $total_retido_desc + $ret_desc;
                                    $total_retido_arg = $total_retido_arg + $ret_arg;
                                    
                                    if ($ret_total > 0)
                                    {    
                                        $pct_retido = ($ret_total/$qtde)*100;
                                        $pct_ret_arg = ($ret_arg/$ret_total)*100;                                        
                                        $pct_ret_desc = ($ret_desc/$ret_total)*100;
                                        
                                        $pct_ret_desc = number_format($pct_ret_desc, 2, ',', '.');
                                        $pct_ret_arg = number_format($pct_ret_arg, 2, ',', '.');
                                        $pct_retido = number_format($pct_retido, 2, ',', '.');
                                    }
                                    
                                    
                                    //imprimindo resultados
                                    echo '<tr>';
                                    echo "<td>$data ($dia_semana)</td>";
                                    echo "<td>$bandeira</td>";    
                                    if ($qtde > 0) 
                                      echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao.php?pData=$data&pBandeira=$bandeira&pGrupo=Atendimentos\" target=\"_blank\">$qtde</a></td>";                                   
                                    else 
                                      echo "<td><b>$qtde</b></td>";
                                    
                                    if ($nao_retido> 0)
                                       echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao.php?pData=$data&pBandeira=$bandeira&pGrupo=NaoRetido\" target=\"_blank\">$nao_retido</a></td>";
                                    else
                                       echo "<td><b>$nao_retido</b></td>";
                                    
                                    if ($ret_total> 0)
                                       echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao.php?pData=$data&pBandeira=$bandeira&pGrupo=Retido\" target=\"_blank\">$ret_total</a></td>";
                                    else 
                                       echo "<td><b>$ret_desc</b></td>";
                                    
                                    echo "<td><b>$pct_retido%</b></td>";
                                    if ($ret_desc > 0) 
                                       echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao.php?pData=$data&pBandeira=$bandeira&pGrupo=RetidoDesc\" target=\"_blank\">$ret_desc</a></td>";
                                    else 
                                       echo "<td><b>$ret_desc</b></td>";
                                   
                                    echo "<td><b>$pct_ret_desc%</b></td>";
                                    
                                    if($ret_arg > 0)   
                                       echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao.php?pData=$data&pBandeira=$bandeira&pGrupo=RetidoArg\" target=\"_blank\">$ret_arg</a></td>";
                                    else 
                                       echo "<td><b>$ret_arg</b></td>";
                                    
                                    echo "<td><b>$pct_ret_arg%</b></td>";                                                        
                                    echo '</tr>';
                                }
                                
                                $pct_total_retido = $total_retido / $total_qtde *100 ;
                                $pct_total_retido =  number_format($pct_total_retido, 2, ',', '.');
                                
                                $pct_total_ret_desc = $total_retido_desc / $total_qtde *100;
                                $pct_total_ret_desc =  number_format($pct_total_ret_desc, 2, ',', '.');
                                
                                $pct_total_ret_arg = $total_retido_arg / $total_qtde *100;
                                $pct_total_ret_arg =  number_format($pct_total_ret_arg, 2, ',', '.');
                                $total_nao_retido = $total_qtde - $total_retido;
                       echo "</tbody>
                       <tr class='w3-indigo'>                                              	                        	                        
                        	<td><b>TOTAL</b></td>
                        	<td></td>
                        	<td><b>$total_qtde</b></td>
                        	<td><b>$total_nao_retido</b></td>                        	                        
                        	<td><b>$total_retido</b></td>                            
                            <td><b>$pct_total_retido</b></td>
                            <td><b>$total_retido_desc</b></td>
                            <td><b>$pct_total_ret_desc</b></td>
                            <td><b>$total_retido_arg</b></td>
                            <td><b>$pct_total_ret_arg</b></td>                                                        
                        </tr>  
                    </table>";
		     echo "</div>";
		echo "</div>";
		
		/*Dados do primeiro grafico*/		                         
		
		$sql = "set nocount on; 
   
                declare @T TABLE(dia date,
                                    q_elo float,
                					q_visa float,
                					q_master float,
                					q_jcb float,
                					q_naodefinida float
                                ); 
                insert @T EXEC sp_CERATFO_radar_cartoes_query29a '$data_inicial_u 00:00:00','$data_final_u 23:59:59',''
                                            
                select dia,
                DATEPART(dd,dia) d_dia,
                DATEPART(mm,dia) d_mes,
                DATEPART(YYYY,dia) d_ano,
                q_elo ,
                q_visa ,
                q_master ,
                q_jcb,
                q_naodefinida  
                from @T 
                
                ";
		
		$dadosgrafico1 = '';
		$query = $pdo->prepare($sql);
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
		    $dia = utf8_encode($row['dia']);
		    $dia = utf8_encode($row['dia']);
		    $d_dia = $row['d_dia'];
		    $d_mes = $row['d_mes'];
		    $d_ano = $row['d_ano'];
		    $q_elo = ($chk_elo) ? round($row['q_elo'],4)*100.00 : 0;
		    $q_jcb = ($chk_jcb) ? round($row['q_jcb'],4)*100.00 : 0;
		    $q_master = ($chk_master) ? round($row['q_master'],4)*100.00 : 0;
		    $q_visa = ($chk_visa) ? round($row['q_visa'],4)*100.00 : 0;
		    		             
		    		        
		    $dadosgrafico1 = $dadosgrafico1."[new Date($d_ano,$d_mes,$d_dia),$q_elo, $q_master, $q_visa, $q_jcb],";		    	    		  		   
		}
			
		$sql = "select 
                    bandeira,
                    count(*) qtde,
                    ( 
                        select count(*) 
                        from tb_dados_retencao t1 (nolock)
                        where  t1.tipo_retencao = 'CARTÃO NÃO RETIDO'
                        and t1.bandeira = t.bandeira
                		and t1.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59'	
                    ) nao_retido	                        
                    from tb_dados_retencao t (nolock)
                    where t.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59'	
                    and ($swhere)	
                    group by  bandeira 
                    order by  bandeira                  
                ";
		
		//echo ($sql);
		$query = $pdo->prepare($sql);
		$query->execute();
		$dadosgrafico2 = '';
		for($i=0; $row = $query->fetch(); $i++)
		{
		    $bandeira = utf8_encode($row['bandeira']);
		    $qtde = $row['qtde'];
		    $nao_retido = $row['nao_retido'];
		    $retido = ($qtde - $nao_retido);		    
		    if ($qtde > 0)
		    {
		       $pct_retido = round(($retido/$qtde),4)*100.00;
		    }    
		    $bandeira = $bandeira.' ('.$pct_retido.'%)';
		    $dadosgrafico2 = $dadosgrafico2."['$bandeira',$qtde,$qtde, $retido,$retido, $nao_retido, $nao_retido],";
		}

		$altura = 300;
		
		$grafico =   '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                             <script type="text/javascript">'.
                             "google.charts.load('current', {'packages':['corechart'], 'language': 'pt'});
                                  google.charts.setOnLoadCallback(drawChart);
                                  
                                  function drawChart()
                                  {
    		                         var data1 = new google.visualization.DataTable();
                                     data1.addColumn('date', 'Dia');
            		                 data1.addColumn('number', 'ELO');
            		                 data1.addColumn('number', 'MASTERCARD');
            		                 data1.addColumn('number', 'VISA');
                                     data1.addColumn('number', 'JCB');            		                                                    
                                     data1.addRows([$dadosgrafico1]);
                                     var options1 =
                                                    {
                                                        title: '".$titulo."',
                                                        curveType: 'function',
                                                        series: {
                                                        		    0: { pointShape: 'star', pointSize:10 },
                                                                  	1: { color: 'red', pointShape: 'square', pointSize:6 },
                                                                  	2: { color: 'green',pointShape: 'polygon', pointSize:6 },
                                                                  	3: { color: '#f28509', pointShape: 'circle', pointSize:6}
                                                                    
                                                                    
                                                        		},
                                                        vAxis: {
                                                                    viewWindowMode:'explicit',
                                                                    viewWindow: {
                                                                        max:100,
                                                                        min:0
                                                                    }
                                                                } ,
                                                		vAxes: {
                                                		    0: {title: 'Pct(%) Retenção'}
                                                		},
                                                      legend: { position: 'top' },                                                     
                                                    };
                                     
                                     var chart = new google.visualization.LineChart(document.getElementById('chart1_div'));
                                     chart.draw(data1, options1);

                                     var data2 = new google.visualization.DataTable();
                                     data2.addColumn('string', 'Bandeira');
                                     data2.addColumn('number', 'Atendimentos');
                                     data2.addColumn({type: 'number', role: 'annotation'});  
                                     data2.addColumn('number', 'Retidos');
                                     data2.addColumn({type: 'number', role: 'annotation'});  
                                     data2.addColumn('number', 'Não Retidos');
                                     data2.addColumn({type: 'number', role: 'annotation'});        
                                     
                                                                        
                                     data2.addRows([$dadosgrafico2]);                                     
                                     var options2 = {
                                                        title: '".$titulo."',                                       
                                                        bars: 'vertical',
                                                        isStacked: false,
                                                        legend: { position: 'top' },
                                                        vAxes: {
                                                		    0: {title: 'Qtde de Atendimentos'}
                                                		},
                                                     };
                                      var chart2 = new google.visualization.ColumnChart(document.getElementById('chart2_div'));
                                      chart2.draw(data2, options2);
                                       
                                   }
                                 </script> ".
                                 '<div id="chart1_div" style="margin-top: 50px; width: auto; height: 500px"></div>
                                  <div id="chart2_div" style="margin-top: 50px; width: auto; height: 500px"></div>  ';                                                                                                            
                                                      
                                                      
        echo $grafico;
		include "desconecta.php";
?>


</body>
</html>

