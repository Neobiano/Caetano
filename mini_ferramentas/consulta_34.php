
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
    	   $cod_fila  = $_GET['pFila'];
    	   if ($cod_fila > 0)
    	   {
    	       $scod_fila = $cod_fila;
    	       $ffiltro = 'Fila: '.$scod_fila;
    	   }
    	   else
    	   {
    	       $scod_fila = '73,77,81,85,116,150,72,76,80,84,111,60,88,90,93,96,87,91,94,97,120,70,71,74,75,78,79,86,58,89,92,95,102,106,108,109,114,118,57,82,83,98,107,99,101,110,63,61,117,125,137,126,100,130,138,139';
    	       $ffiltro = 'Fila: TODAS';
    	   }
    	      
    	   $sdata1 = substr($data1,8,2).'/'.substr($data1,5,2).'/'.substr($data1,0,4);
    	   $sdata2 = substr($data2,8,2).'/'.substr($data2,5,2).'/'.substr($data2,0,4);
    	   
    	   $sql = "
                
                    	set nocount on;
                			
                    select 
						   datepart(hh,data_hora) as hora,
						   datepart(minute,data_hora)/15 as minuto,  						   	
						   cast(null as integer) cod_fila_orig, 
                    	   cast(null as datetime) data_hora_orig,
						   cast(null as integer) cod_fila_dest, 
                    	   cast(null as datetime) data_hora_dest,						   
                    * 
                    into #temp_dac  
                    from tb_eventos_dac (nolock)
                    where (
                            (data_hora between '$data1 00:00:00' and '$data1 23:59:59.999')
                            or 
                            (data_hora between '$data2 00:00:00' and '$data2 23:59:59.999')
                            )							
                    and cod_fila  in (73,77,81,85,116,150,72,76,80,84,111,60,88,90,93,96,87,91,94,97,120,70,71,74,75,78,79,86,58,89,92,95,102,106,108,109,114,118,57,82,83,98,107,99,101,110,63,61,117,125,137,126,100,130,138,139)			
                    and (tempo_atend > 0 or tempo_espera > 10)
                    
					select	datepart(hh,data_hora) as hora,
                        (datepart(minute,data_hora)/15) as minuto,						
					 case 
							when (datepart(minute,data_hora)/15) = 0 then rtrim(cast(datepart(hh,data_hora) as char(2)))+':00:00 à '+rtrim(cast(datepart(hh,data_hora) as char(2)))+':15:00' 
							when (datepart(minute,data_hora)/15) = 1 then rtrim(cast(datepart(hh,data_hora) as char(2)))+':15:01 à '+rtrim(cast(datepart(hh,data_hora) as char(2)))+':30:00' 
							when (datepart(minute,data_hora)/15) = 2 then rtrim(cast(datepart(hh,data_hora) as char(2)))+':30:01 à '+rtrim(cast(datepart(hh,data_hora) as char(2)))+':45:00'
							when (datepart(minute,data_hora)/15) = 3 then rtrim(cast(datepart(hh,data_hora) as char(2)))+':45:01 à '+rtrim(cast(datepart(hh,data_hora) as char(2)))+':59:59'
                        end intervalo,
                        case 
							when (datepart(minute,data_hora)/15) = 0 then rtrim(cast(datepart(hh,data_hora) as char(2)))+':00:00'
							when (datepart(minute,data_hora)/15) = 1 then rtrim(cast(datepart(hh,data_hora) as char(2)))+':15:01'
							when (datepart(minute,data_hora)/15) = 2 then rtrim(cast(datepart(hh,data_hora) as char(2)))+':30:01'
							when (datepart(minute,data_hora)/15) = 3 then rtrim(cast(datepart(hh,data_hora) as char(2)))+':45:01'
                        end shora    
                    into #temp_intervalo
                    from #temp_dac t  		
                    where tempo_atend > 0		
                    group by datepart(hh,t.data_hora), datepart(minute,t.data_hora)/15
                    order by datepart(hh,t.data_hora), datepart(minute,t.data_hora)/15

                    update t set data_hora_orig = (								 
                    								select max(data_hora) 
                    								from #temp_dac t2
                    								where t2.callid = t.callid 
                    								and t2.data_hora < t.data_hora									
                    							),
								data_hora_dest = (								 
                    								select min(data_hora) 
                    								from #temp_dac t2
                    								where t2.callid = t.callid 
                    								and t2.data_hora > t.data_hora									
                    							)
                    from  #temp_dac t
                    
                    update t set cod_fila_orig = (								 
                    								select cod_fila 
                    								from #temp_dac t2
                    								where t2.callid = t.callid 
                    								and t2.data_hora = t.data_hora_orig
                    							),
								 cod_fila_dest = (								 
                    								select cod_fila 
                    								from #temp_dac t2
                    								where t2.callid = t.callid 
                    								and t2.data_hora = t.data_hora_dest
                    							)	
                    from  #temp_dac t
                                                        
                    select cast(t.data_hora as date) data,
					t.hora, t.minuto, t.cod_fila, 
					(
						select count(*) from #temp_dac t1 
						where cast(t1.data_hora as date) = cast(t.data_hora as date)
						and t.hora = t1.hora and t.minuto = t1.minuto
						and t.cod_fila = t1.cod_fila
						and coalesce(t1.cod_fila_orig,0) > 0
						
					 ) qtde_receb_fila, 
					 count(*) qtde_recebidas,
					 (
						select count(*) from #temp_dac t1 
						where cast(t1.data_hora as date) = cast(t.data_hora as date)
						and t.hora = t1.hora and t.minuto = t1.minuto
						and t.cod_fila = t1.cod_fila
						and coalesce(t1.tempo_atend,0) > 0
						
					 ) qtde_atendidas,
					 (
						select count(*) from #temp_dac t1 
						where cast(t1.data_hora as date) = cast(t.data_hora as date)
						and t.hora = t1.hora and t.minuto = t1.minuto
						and t.cod_fila = t1.cod_fila
						and coalesce(t1.tempo_atend,0) = 0
						and coalesce(t1.tempo_atend,0) = 0
						
					 ) qtde_abandonadas,
					(
						select count(*) from #temp_dac t1 
						where cast(t1.data_hora as date) = cast(t.data_hora as date)
						and t.hora = t1.hora and t.minuto = t1.minuto
						and t.cod_fila = t1.cod_fila
						and coalesce(t1.cod_fila_dest,0) > 0
						
					 ) qtde_transferidas				
					into #temp_resumo
				    from #temp_dac t							
					group by hora, minuto, cod_fila, cast(t.data_hora as date)
					order by  cast(t.data_hora as date), t.hora, t.minuto, t.cod_fila

					
					select data,hora, minuto,					
					sum(qtde_receb_fila) qtde_receb_fila,
					sum(qtde_recebidas) qtde_recebidas,
					sum(qtde_atendidas) qtde_atendidas,
					sum(qtde_abandonadas) qtde_abandonadas,
					sum(qtde_transferidas) qtde_transferidas,
					coalesce(ROUND(((sum(qtde_transferidas) / cast(sum(qtde_recebidas) as float)) * 100.00), 2),null) pct_transferidas
					into #temp_data_1
					from #temp_resumo t					
					where data = '$data1'
                    and t.cod_fila  in ($scod_fila)
					group by data,  hora, minuto	

					select data,  hora, minuto,					
					sum(qtde_receb_fila) qtde_receb_fila,
					sum(qtde_recebidas) qtde_recebidas,
					sum(qtde_atendidas) qtde_atendidas,
					sum(qtde_abandonadas) qtde_abandonadas,
					sum(qtde_transferidas) qtde_transferidas,
					coalesce(ROUND(((sum(qtde_transferidas) / cast(sum(qtde_recebidas) as float)) * 100.00), 2),null) pct_transferidas
					into #temp_data_2
					from #temp_resumo t					
					where data = '$data2'
                    and t.cod_fila  in ($scod_fila)
					group by data,  hora, minuto	
					
					 select t.hora, t.minuto, t.intervalo, t.shora,
                    	cast('$data1' as date) as data_1, 	  
                        coalesce(t1.qtde_recebidas,0) qtde_recebidas_1, 
                        coalesce(t1.qtde_atendidas,0) qtde_atendidas_1,
                    	coalesce(t1.qtde_abandonadas,0) qtde_abandonadas_1, 
                        coalesce(t1.qtde_transferidas,0) qtde_transferidas_1,	
                        t1.pct_transferidas pct_transferidas_1,        
						cast('$data2' as date) as data_2, 	  
                        coalesce(t2.qtde_recebidas,0) qtde_recebidas_2, 
                        coalesce(t2.qtde_atendidas,0) qtde_atendidas_2,
                    	coalesce(t2.qtde_abandonadas,0) qtde_abandonadas_2, 
                        coalesce(t2.qtde_transferidas,0) qtde_transferidas_2,	
                        t2.pct_transferidas pct_transferidas_2,                        
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
                    from #temp_intervalo t
                    left join #temp_data_1 t1 on (t1.hora = t.hora and t1.minuto = t.minuto)
                    left join #temp_data_2 t2 on (t2.hora = t.hora and t2.minuto = t.minuto)  
                    order by t.hora, t.minuto, intervalo, shora

                    ";
    	   
	       //echo $sql;
	       $query = $pdo->prepare($sql);
	       $query->execute();
	       $linha = '';
	       $lista = '';
	       $dadosgrafico1 = '';
	       $total_transferidas_1 = 0;
	       $total_recebidas_1 = 0;
	       $total_transferidas_2 = 0;
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
	           $abandonadas_1 = $row['qtde_abandonadas_1'];
	           $atendidas_1 = $row['qtde_atendidas_1'];
	           $recebidas_1 = $row['qtde_recebidas_1'];
	           $transferidas_1 = $row['qtde_transferidas_1'];
	           $pct_transferidas_1 = $row['pct_transferidas_1'];	           	          	  
	           $pct_transferidas_1 = round($pct_transferidas_1,2);
	           
	           $total_transferidas_1 += $transferidas_1;
	           $total_recebidas_1 += $recebidas_1;
	
	           $abandonadas_2 = $row['qtde_abandonadas_2'];
	           $atendidas_2 = $row['qtde_atendidas_2'];
	           $recebidas_2 = $row['qtde_recebidas_2'];
	           $transferidas_2 = $row['qtde_transferidas_2'];
	           $pct_transferidas_2 = $row['pct_transferidas_2'];
	           $pct_transferidas_2 = round($pct_transferidas_2,2);
	          
	           if ($max_indice < $pct_transferidas_1)
	               $max_indice = $pct_transferidas_1;
	           
               if ($max_indice < $pct_transferidas_2)
                   $max_indice = $pct_transferidas_2;
	           
               $total_transferidas_2 += $transferidas_2;
	           $total_recebidas_2 += $recebidas_2;
	           
	           //dados para o grafico
	           $g_hora = $row['g_hora'];
	           $g_minuto = $row['g_minuto'];
	           $g_segundo = $row['g_segundo'];
	           
	           if($recebidas_1 == 0)
	               $pct_transferidas_1 = 'null';
	           
	           if($recebidas_2 ==0)
	               $pct_transferidas_2 = 'null';
	           
	           $dadosgrafico1 = $dadosgrafico1."[[$g_hora, $g_minuto, $g_segundo],$pct_transferidas_1, $pct_transferidas_2, 20.00],";	
	           
	           if ($linha <>'')
	               $linha.=',';	          
	          	           
	                     
	           //preenchendo os dados para a a tabela
	           $lista .= "<tr>";
	           $lista .= "<td>$hora</td>";
	           $lista .= "<td>$minuto</td>";
	           $lista .= "<td>$intervalo</td>";
	           
	           $lista .= "<td>$transferidas_1</td>";
	           $lista .= "<td>$abandonadas_1</td>";
	           $lista .= "<td>$atendidas_1</td>";
	           $lista .= "<td>$recebidas_1</td>";	           	           
	           $lista .= "<td>$pct_transferidas_1</td>";   
	           
	           $lista .= "<td>$transferidas_2</td>";
	           $lista .= "<td>$abandonadas_2</td>";
	           $lista .= "<td>$atendidas_2</td>";
	           $lista .= "<td>$recebidas_2</td>";	           
	           $lista .= "<td>$pct_transferidas_2</td>";
	           $lista .= "</tr>";
	       }
    	   echo $linha;   
    	   $max_indice = intval(($max_indice + 5));
    	   //$media_transferidas_1 = ($total_transferidas_1/$total_recebidas_1);
    	   $media_transferidas_1 = round((($total_transferidas_1/$total_recebidas_1)*100),2);
    	   $media_transferidas_2 = round((($total_transferidas_2/$total_recebidas_2)*100),2);
    	   
      ?>      

          google.charts.load('current', {'packages':['corechart'], 'language': 'pt'});
          google.charts.setOnLoadCallback(drawChart);
          
          function drawChart()
          {
             var data1 = new google.visualization.DataTable();
             data1.addColumn('timeofday', 'Hora');
             data1.addColumn('number', '<?php echo $sdata1.' - Média ( '.$media_transferidas_1.'% )'?>');
             data1.addColumn('number', '<?php echo $sdata2.' - Média ( '.$media_transferidas_2.'% )'?>');
             data1.addColumn('number', 'Benchmark 20%');                      		                                                   
             data1.addRows([<?php echo $dadosgrafico1?>]);
             var options1 =
                            {
                                title: 'Acompanhamento de Transferências',
                                curveType: 'function',
                                series: {
                                		    0: { color: '#960c78', pointSize:3 },
                                          	1: { color: '#09c1b8',  pointSize:2 },
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
                        		    0: {title: 'Pct(%) Transferências'}
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
    <div class='w3-container w3-padding w3-margin w3-tiny w3-center w3-indigo w3-wide w3-card-4'><b>RADAR CARTÕES - Painel de Acompanhamento de TRANSFERÊNCIAS</b></div>
      <?php        
      // IMPRIME TÍTULO DA CONSULTA
    	echo '<div id="divtitulo" class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';    	
    	echo "<br><b>Datas:</b> $sdata1 <b>(Últ. Registro $ult_reg_1)</b> comparado com $sdata2 <b>(Últ. Registro $ult_reg_2) - $ffiltro</b> ";
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
                       <td colspan="5"><b><?php echo $data1?></b></td>
                       <td colspan="5"><b><?php echo $data2?></b></td>
                    </tr>   
                	<tr class="w3-indigo">
                    	<td><b>Hora</b></td>                
                        <td><b>Minuto</b></td>          	                                    
                        <td><b>Descrição</b></td>
                    
                    	<td><b>Transferidas</b></td>
                    	<td><b>Abandonadas</b></td>               
                        <td><b>Atendidas</b></td>
                        <td><b>Recebidas</b></td>                        
                        <td><b>Pct(%)</b></td>
                    
                    	<td><b>Transferidas</b></td>
                    	<td><b>Abandonadas</b></td>               
                        <td><b>Atendidas</b></td>
                        <td><b>Recebidas</b></td>                        
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





