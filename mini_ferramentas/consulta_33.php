
<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" href="css/radar.css">

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
  <link rel="stylesheet" href="css/jquery-ui.css" />


  <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
  <script src="js/jquery-1.8.2.js"></script>
  <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
  <script src="js/jquery-ui.js"></script>
  
  <script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>    
  <link rel="stylesheet" type="text/css" href="css/dataTables.css">  
  <script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>
  
    	
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
  <script type="text/javascript">    

		//---------------------RELOAD DA PÁGINA-------------------
    	 var time = new Date().getTime();
         $(document.body).bind("mousemove keypress", function(e) {
             time = new Date().getTime();
         });
    
         function refresh() {
             if(new Date().getTime() - time >= 300000) 
                 window.location.reload(true);
             else 
                 setTimeout(refresh, 15000);
         }
    
         setTimeout(refresh, 15000);
       //---------------------FIM RELOAD-------------------
      <?php           
           include_once "conecta.php";
          
    	      	   
    	   $data1 = $_GET['pData1'];
    	   $data2 = $_GET['pData2'];
    	   $sdata1 = substr($data1,8,2).'/'.substr($data1,5,2).'/'.substr($data1,0,4);
    	   $sdata2 = substr($data2,8,2).'/'.substr($data2,5,2).'/'.substr($data2,0,4);
    	   
    	   $sql = "
                    set nocount on;

                    select * 
                    into #temp_dac  
                    from tb_eventos_dac (nolock)
                    where (
                    		(data_hora between '$data1 00:00:00' and '$data1 23:59:59.999')
                    		or 
                    		(data_hora between '$data2 00:00:00' and '$data2 23:59:59.999')
                    		)							
                    and cod_fila  in (73,77,81,85,116,150,72,76,80,84,111,60,88,90,93,96,87,91,94,97,120,70,71,74,75,78,79,86,58,89,92,95,102,106,108,109,114,118,57,82,83,98,107,99,101,110,63,61,117,125,137,126,100,130,138,139);			
                                    
                    select	datepart(hh,data_hora) as hora,
                        datepart(minute,data_hora)/15 as minuto    
                    into #temp_intervalo
                    from #temp_dac t  		
                    where tempo_atend > 0		
                    group by datepart(hh,t.data_hora), datepart(minute,t.data_hora)/15
                    order by datepart(hh,t.data_hora), datepart(minute,t.data_hora)/15
                                      
                    				
                    select 
                    		cast('$data1' as date) as data,  
                    		A.* 						
                        into #temp_data_1
                        from  (
                                select datepart(hh,data_hora) as hora,
                                        datepart(minute,data_hora)/15 as minuto,                					                           					
                                            (
                                            	select count(*) from 
                                            						(   
                                            							select distinct * from #temp_dac t2
                                            							where  t2.id_operador <> 'NULL'  
                                            							and t2.tempo_atend > 0  
                                    									and t2.data_hora between '$data1 00:00:00' and '$data1 23:59:59.999'
                                            						) b
                                            	where datepart(hh,b.data_hora) = datepart(hh,t.data_hora)
                                            	and (datepart(minute,b.data_hora)/15) = (datepart(minute,t.data_hora)/15)
                                            )  atendidas,
                                            (
                                            	select count(*) from 
                                            						(  
                                            							select distinct * from #temp_dac t3
                                            							where t3.tempo_atend <= 0
                                                						and cast(t3.tempo_espera as integer) > 10 
                                    									and t3.data_hora between '$data1 00:00:00' and '$data1 23:59:59.999'
                                            						) c
                                            	where datepart(hh,c.data_hora) = datepart(hh,t.data_hora)
                                            	and (datepart(minute,c.data_hora)/15) = (datepart(minute,t.data_hora)/15)
                                            )  abandonadas,
                    						(
                                            	select avg(tempo_atend) from 
                                            						(  
                                            							select distinct * from #temp_dac t3
                                            							where t3.tempo_atend > 0                            						
                                    									and t3.data_hora between '$data1 00:00:00' and '$data1 23:59:59.999'
                                            						) d
                                            	where datepart(hh,d.data_hora) = datepart(hh,t.data_hora)
                                            	and (datepart(minute,d.data_hora)/15) = (datepart(minute,t.data_hora)/15)
                                            )  tma,
                    						(
                                            	select count(distinct id_operador) from 
                                            						(  
                                            							select distinct * from #temp_dac t3
                                            							where t3.tempo_atend > 0                            						
                                    									and t3.data_hora between '$data1 00:00:00' and '$data1 23:59:59.999'
                                            						) e
                                            	where datepart(hh,e.data_hora) = datepart(hh,t.data_hora)
                                            	and (datepart(minute,e.data_hora)/15) = (datepart(minute,t.data_hora)/15)
                                            )  qtde_operador						                      							        
                                from #temp_dac t  				
                                group by datepart(hh,t.data_hora), datepart(minute,t.data_hora)/15                				
                            ) A
                            where (abandonadas + atendidas) > 0  
                            order by hora, minuto
                    
                    	select 
                    	cast('$data1' as date) as data,  
                    	A.* 						
                        into #temp_data_2
                        from  (
                                select datepart(hh,data_hora) as hora,
                                        datepart(minute,data_hora)/15 as minuto,                					                           					
                                            (
                                            	select count(*) from 
                                            						(   
                                            							select distinct * from #temp_dac t2
                                            							where  t2.id_operador <> 'NULL'  
                                            							and t2.tempo_atend > 0  
                                    									and t2.data_hora between '$data2 00:00:00' and '$data2 23:59:59.999'
                                            						) b
                                            	where datepart(hh,b.data_hora) = datepart(hh,t.data_hora)
                                            	and (datepart(minute,b.data_hora)/15) = (datepart(minute,t.data_hora)/15)
                                            )  atendidas,
                                            (
                                            	select count(*) from 
                                            						(  
                                            							select distinct * from #temp_dac t3
                                            							where t3.tempo_atend <= 0
                                                						and cast(t3.tempo_espera as integer) > 10
                                    									and t3.data_hora between '$data2 00:00:00' and '$data2 23:59:59.999'
                                            						) c
                                            	where datepart(hh,c.data_hora) = datepart(hh,t.data_hora)
                                            	and (datepart(minute,c.data_hora)/15) = (datepart(minute,t.data_hora)/15)
                                            )  abandonadas,
                    						(
                                            	select avg(tempo_atend) from 
                                            						(  
                                            							select distinct * from #temp_dac t3
                                            							where t3.tempo_atend > 0                            						
                                    									and t3.data_hora between '$data2 00:00:00' and '$data2 23:59:59.999'
                                            						) d
                                            	where datepart(hh,d.data_hora) = datepart(hh,t.data_hora)
                                            	and (datepart(minute,d.data_hora)/15) = (datepart(minute,t.data_hora)/15)
                                            )  tma,
                    						(
                                            	select count(distinct id_operador) from 
                                            						(  
                                            							select distinct * from #temp_dac t3
                                            							where t3.tempo_atend > 0                            						
                                    									and t3.data_hora between '$data2 00:00:00' and '$data2 23:59:59.999'
                                            						) e
                                            	where datepart(hh,e.data_hora) = datepart(hh,t.data_hora)
                                            	and (datepart(minute,e.data_hora)/15) = (datepart(minute,t.data_hora)/15)
                                            )  qtde_operador
                                    							        
                                from #temp_dac t  				
                                group by datepart(hh,t.data_hora), datepart(minute,t.data_hora)/15                				
                            ) A
                            where (abandonadas + atendidas) > 0  
                            order by hora, minuto                      	
                    																
                    select t.hora, t.minuto,                   					 
                        case 
							when t.minuto = 0 then rtrim(cast(t.hora as char(2)))+':00:00 à '+rtrim(cast(t.hora as char(2)))+':15:00' 
							when t.minuto = 1 then rtrim(cast(t.hora as char(2)))+':15:01 à '+rtrim(cast(t.hora as char(2)))+':30:00' 
							when t.minuto = 2 then rtrim(cast(t.hora as char(2)))+':30:01 à '+rtrim(cast(t.hora as char(2)))+':45:00'
							when t.minuto = 3 then rtrim(cast(t.hora as char(2)))+':45:01 à '+rtrim(cast(t.hora as char(2)))+':59:59'
                        end intervalo,
                        case 
							when t.minuto = 0 then rtrim(cast(t.hora as char(2)))+':00:00'
							when t.minuto = 1 then rtrim(cast(t.hora as char(2)))+':15:01'
							when t.minuto = 2 then rtrim(cast(t.hora as char(2)))+':30:01'
							when t.minuto = 3 then rtrim(cast(t.hora as char(2)))+':45:01'
                        end shora,
                    	cast('$data1' as date) as data_1, 	  
                        coalesce(t1.abandonadas,0) abandonadas_1, 
                        coalesce(t1.atendidas,0) atendidas_1,
                    	coalesce(t1.tma,0) tma_1, 
                        coalesce(t1.qtde_operador,0) qtde_operador_1,	
                        coalesce((t1.atendidas + t1.abandonadas),0) recebidas_1,                	   
                        coalesce(ROUND(((t1.abandonadas / cast(t1.atendidas + t1.abandonadas as float)/0.977) * 100.00), 2),null) pct_abandonadas_1,
                    	cast('$data2' as date) as data_2, 	  
                        coalesce(t2.abandonadas,0) abandonadas_2, 
                        coalesce(t2.atendidas,0) atendidas_2,	
                    	coalesce(t2.tma,0) tma_2, 
                        coalesce(t2.qtde_operador,0) qtde_operador_2,	
                        coalesce((t2.atendidas + t2.abandonadas),0) recebidas_2,                	   
                        coalesce(ROUND(((t2.abandonadas / cast(t2.atendidas + t2.abandonadas as float)/0.977) * 100.00), 2),null) pct_abandonadas_2
					into #temp_retorno
                    from #temp_intervalo t
                    left join #temp_data_1 t1 on (t1.hora = t.hora and t1.minuto = t.minuto)
                    left join #temp_data_2 t2 on (t2.hora = t.hora and t2.minuto = t.minuto)                   
                    order by t.hora, t.minuto


					select 
					*, 
                    case 
					  when t.recebidas_1 > 0 then (atendidas_1/recebidas_1/0.977) 
					  else 0
					end ia_car_1,
					case 
					  when recebidas_2 > 0 then (atendidas_2/recebidas_2/0.977) 
					  else 0
					end ia_car_2,
					datepart(hh,t.shora) g_hora,
                	datepart(mi,t.shora) g_minuto,
                	datepart(ss,t.shora) g_segundo,
					( select max(cast(t.data_hora as time))  
					  from #temp_dac t 
					  where cast(data_hora as date) = '$data1'
					 ) ult_reg_1,
					 ( select max(cast(t.data_hora as time))  
					  from #temp_dac t 
					  where cast(data_hora as date) = '$data2'
					 ) ult_reg_2
					from #temp_retorno t

                    ";
    	   
	       //echo $sql;
	       $query = $pdo->prepare($sql);
	       $query->execute();
	       $linha = '';
	       $lista = '';
	       $dadosgrafico1 = '';
	       $total_abandonadas_1 = 0;
	       $total_recebidas_1 = 0;
	       $total_abandonadas_2 = 0;
	       $total_recebidas_2 = 0;
	       $max_indice = 15;
	       for($i=0; $row = $query->fetch(); $i++)
	       {
	                    
	           $ult_reg_2 = substr($row['ult_reg_2'], 0,8);
	           $ult_reg_1 = substr($row['ult_reg_1'], 0,8);
	           $ult_execucao_h = substr($row['ult_execucao'],11,8);
	           //$ult_execucao_d = substr($row['ult_execucao'],0,10);
	           //$ult_execucao_d = substr($ult_execucao_d,8,2).'/'.substr($ult_execucao_d,5,2).'/'.substr($ult_execucao_d,0,4);
	           
	           
	           $hora = $row['hora'];	           
	           $minuto = $row['minuto'];
	           $intervalo = $row['intervalo'];
	           $abandonadas_1 = $row['abandonadas_1'];
	           $atendidas_1 = $row['atendidas_1'];
	           $recebidas_1 = $row['recebidas_1'];
	           $pct_abandonadas_1 = $row['pct_abandonadas_1'];
	           $tma_1 = $row['tma_1'];
	           $qtde_operador_1 = $row['qtde_operador_1'];	          	  
	           $pct_abandonadas_1 = round($pct_abandonadas_1,2);
	           
	           $total_abandonadas_1 += $abandonadas_1;
	           $total_recebidas_1 += $recebidas_1;
	           
	           $hora = $row['hora'];
	           $minuto = $row['minuto'];
	           $intervalo = $row['intervalo'];
	           $abandonadas_2 = $row['abandonadas_2'];
	           $atendidas_2 = $row['atendidas_2'];
	           $recebidas_2 = $row['recebidas_2'];
	           $pct_abandonadas_2 = $row['pct_abandonadas_2'];
	           $tma_2 = $row['tma_2'];
	           $qtde_operador_2 = $row['qtde_operador_2'];
	           $pct_abandonadas_2 = round($pct_abandonadas_2,2);
	          
	           if ($max_indice < $pct_abandonadas_1)
	               $max_indice = $pct_abandonadas_1;
	           
	           if ($max_indice < $pct_abandonadas_2)
	                $max_indice = $pct_abandonadas_2;
	           
	           $total_abandonadas_2 += $abandonadas_2;
	           $total_recebidas_2 += $recebidas_2;
	           
	           //dados para o grafico
	           $g_hora = $row['g_hora'];
	           $g_minuto = $row['g_minuto'];
	           $g_segundo = $row['g_segundo'];
	           
	           if($recebidas_1 == 0)
	              $pct_abandonadas_1 = 'null';
	           
	              if($recebidas_2 ==0)
	                $pct_abandonadas_2 = 'null';
	           
	           $dadosgrafico1 = $dadosgrafico1."[[$g_hora, $g_minuto, $g_segundo],$pct_abandonadas_1, $pct_abandonadas_2, 2.3],";	
	           
	           if ($linha <>'')
	               $linha.=',';	          
	          	           
	                     
	           //preenchendo os dados para a a tabela
	           $lista .= "<tr>";
	           $lista .= "<td>$hora</td>";
	           $lista .= "<td>$minuto</td>";
	           $lista .= "<td>$intervalo</td>";
	           $lista .= "<td>$abandonadas_1</td>";
	           $lista .= "<td>$atendidas_1</td>";
	           $lista .= "<td>$recebidas_1</td>";
	           $lista .= "<td>$tma_1</td>";
	           $lista .= "<td>$qtde_operador_1</td>";
	           $lista .= "<td>$pct_abandonadas_1</td>";    	             	          
	           $lista .= "<td>$abandonadas_2</td>";
	           $lista .= "<td>$atendidas_2</td>";
	           $lista .= "<td>$recebidas_2</td>";
	           $lista .= "<td>$tma_2</td>";
	           $lista .= "<td>$qtde_operador_2</td>";
	           $lista .= "<td>$pct_abandonadas_2</td>";
	           $lista .= "</tr>";
	       }
    	   echo $linha;   
    	   $max_indice = intval(($max_indice + 5));
    	   //$media_abandonadas_1 = ($total_abandonadas_1/$total_recebidas_1);
    	   $media_abandonadas_1 = round((($total_abandonadas_1/$total_recebidas_1/0.977)*100),2);
    	   $media_abandonadas_2 = round((($total_abandonadas_2/$total_recebidas_2/0.977)*100),2);
    	   
      ?>      

          google.charts.load('current', {'packages':['corechart'], 'language': 'pt'});
          google.charts.setOnLoadCallback(drawChart);
          
          function drawChart()
          {
             var data1 = new google.visualization.DataTable();
             data1.addColumn('timeofday', 'Hora');
             data1.addColumn('number', '<?php echo $sdata1.' - Média ( '.$media_abandonadas_1.'% )'?>');
             data1.addColumn('number', '<?php echo $sdata2.' - Média ( '.$media_abandonadas_2.'% )'?>');
             data1.addColumn('number', 'Benchmark 2,3% (Considerando Abanonos > 10s)');                      		                                                   
             data1.addRows([<?php echo $dadosgrafico1?>]);
             var options1 =
                            {
                                title: 'Acompanhamento de Abandonos',
                                curveType: 'function',
                                series: {
                                		    0: { color: 'red', pointSize:3 },
                                          	1: { color: 'blue',  pointSize:2 },
                                          	2: { color: 'green', pointSize:2 },
                                          	                                                                                      
                                		},
                                vAxis: {
                                            viewWindowMode:'explicit',
                                            viewWindow: {
                                                max:<?php echo $max_indice?>,
                                                min:0
                                            }
                                        } ,
                        		vAxes: {
                        		    0: {title: 'Pct(%) Abandonos'}
                        		},
                              legend: { position: 'top' },   
                              interpolateNulls : true                                                  
                            };
             
             var chart = new google.visualization.LineChart(document.getElementById('chart_div'));                           
             
             chart.draw(data1, options1);
          }    
                                                  	     
  	
      $(document).ready( function () {
          $('#tabela').DataTable( {
        	  "lengthMenu": [[100, -1], [100, "All"]],
              "order": [[ 0, "asc" ]],
              "columnDefs": [
                  {"className": "dt-center", "targets": "_all"}
                ]
          } );
      } );
          
    </script>
  </head>
  <body>

    <!-- LOGO CAIXA -->
    <br>
    <div class="w3-container w3-center">
    	<img src="logo.png" style="width:140px">
    </div>			
    <hr>
    
    <!-- TÍTULO -->
    <div class='w3-container w3-padding w3-margin w3-tiny w3-center w3-indigo w3-wide w3-card-4'><b>RADAR CARTÕES - Painel de Acompanhamento de ABANDONOS</b></div>
      <?php 
       $ult_execucao = getdate();
       date_default_timezone_set('America/Santiago');
       $sult_execucao = $ult_execucao[mday].'/'.$ult_execucao[mon].'/'.$ult_execucao[year].' às '.$ult_execucao[hours].':'.$ult_execucao[minutes].':'.$ult_execucao[seconds];
      // IMPRIME TÍTULO DA CONSULTA
    	echo '<div id="divtitulo" class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';    	
    	echo "<br><b>Datas:</b> $sdata1 <b>(Últ. Registro $ult_reg_1)</b> comparado com $sdata2 <b>(Últ. Registro $ult_reg_2)</b>";
    	echo "<br><br><b>Obs:</b> Em caso de inatividade, a página irá se atualizar a cada <b>5 minutos <p id='hora'></p></b>";
    	
    	
    	echo '</div>';    	   
    	//echo $lista;
    	//echo $sql;
    	?>
    	
      <div class="w3-border w3-margin w3-padding-bottom w3-card-4" style="margin-top:0; !important;">
        <div id="chart_div" style="height: 500px;" ></div>    
        
      </div>  
    
  
  <div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important;">
    <table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>
  	
      	<thead>
            <tr class="w3-indigo">              	
                	<tr class="w3-indigo">
                       <td colspan="3"><b>Intervalo<b></td>
                       <td colspan="6"><b><?php echo $sdata1?></b></td>
                       <td colspan="6"><b><?php echo $sdata2?></b></td>
                    </tr>   
                	<tr class="w3-indigo">
                    	<td><b>Hora</b></td>                
                        <td><b>Minuto</b></td>          	                                    
                        <td><b>Descrição</b></td>
                    
                    	<td><b>Abandonadas</b></td>               
                        <td><b>Atendidas</b></td>
                        <td><b>Recebidas</b></td>
                        <td><b>TMA</b></td>
                        <td><b>Qtde Operador</b></td>
                        <td><b>Pct(%)</b></td>
                    
                    	<td><b>Abandonadas</b></td>               
                        <td><b>Atendidas</b></td>
                        <td><b>Recebidas</b></td>
                        <td><b>TMA</b></td>
                        <td><b>Qtde Operador</b></td>
                        <td><b>Pct(%)</b></td>
                    </tr>           	
           </tr>
        </thead>
        <tbody>
          <?php echo $lista;
                     
          ?>
         </tbody>
    </table>     
  </div>
  <script>  
      var d = new Date();
      var texto = '(Últ. Execução '+d.getDate()+'/'+d.getMonth()+'/'+d.getFullYear()+' às '+ d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()+' )';      
      document.getElementById("hora").innerHTML = texto;    
 </script>

  </body>
</html>





