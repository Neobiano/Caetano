<?php
        //função retornará o dia da semana
        function diaSemana($dia)
        {
            switch ($dia) { // TRADUZ O DIA DA SEMANA
                case 1:
                    return "Domingo";
                    break;
                    
                case 2:
                    return  "Segunda-Feira";
                    break;
                    
                case 3:
                    return  "Terça-Feira";
                    break;
                    
                case 4:
                    return  "Quarta-Feira";
                    break;
                    
                case 5:
                    return "Quinta-Feira";
                    break;
                    
                case 6:
                    return  "Sexta-Feira";
                    break;
                    
                case 7:
                    return  "Sábado";
                    break;
            }
        }
        //Função calculará tempo decorrido a partir do tempo inicial e final passado  
		function tempoDecorrido($script_start,$script_end)
		{
			$elapsed_time = round($script_end - $script_start, 5);
		
			$elapsed_time = intval($elapsed_time);
			if ($elapsed_time >= 60)
			{
				$minutos = intval($elapsed_time/60);
				$segundos = ((($elapsed_time/60) - $minutos)*60);
			}
			else
			{
				$minutos = 0;
				$segundos = $elapsed_time;
			}    
			
			
			if ($minutos == 1)
			  $texto_tempo = "$minutos min";
			else if ($minutos > 1) 
			  $texto_tempo = "$minutos min";
			else
			  $texto_tempo = "";
			
			if (($texto_tempo != "") and ($segundos >0))
			  $texto_tempo = $texto_tempo." e ";
			
			if ($segundos == 1)
			  $texto_tempo = $texto_tempo."$segundos segundo";
			else if ($segundos > 1)  
			  $texto_tempo = $texto_tempo."$segundos segundos"; 
			  
			$html =  '<div id="tmp" class="w3-margin w3-tiny w3-center">'.
					 '<b>Tempo de Execução: </b><i>'.$texto_tempo.'</i><br>'.
					 '</div>';
					 
			return $html;  
		}
		
		
		function defineTime()
		{
			list($usec, $sec) = explode(' ', microtime());
			$script = (float) $sec + (float) $usec;
			
			return $script;  
		}
		
		//Função irá gerar dia,mes e ano a partir de uma data passada como parâmetro.
		function extraiDiaMesAno($date) 
		{
			list( $month,$day,$year ) = explode('/', $date);
			return array( 'month'=>$month, 'day'=>$day, 'year'=>$year );
			/* --------Modo de Uso-------
			   $data =  extraiDiaMesAno($data_inicial);                         
			   $mes = $a_data['month'];
			   $ano = $a_data['year'];
			   $dia = $a_data['day'];
			 */
		}
				
		function incrementa_tabela($texto)
		{
		    $GLOBALS['tabela'] = $GLOBALS['tabela']."$texto";
			return ($texto);
		}
		
		function imprimeGraficoLinha($dados_grafico, $titulo,$largura, $altura, $max, $min, $tipo, $parametros_adicionais)
		        {
					if ($titulo == 'Dica: Utilize o scroll do mouse para modificar o zoom do gráfico. Clique, segure e arraste para percorrer.')
					{
						$dica = "titleTextStyle: {color:'red'},";
						$titulo = "";
					}
					else $dica = "";												    
					
									
		           $grafico =   '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		                         <script type="text/javascript">'.
		                             "google.charts.load('current', {'packages':['corechart'], 'language': 'pt'});
		                              google.charts.setOnLoadCallback(drawChart);        
		                              function drawChart() 
		                              {
		                                var data = google.visualization.arrayToDataTable([".$dados_grafico."]);                    
		                                var options = 
		                                {
		                                  title: '".$titulo."',
		                                  curveType: 'function',
		                                  $parametros_adicionais
                                                                                     	                                  
                                            
		                                  legend: { position: 'top' }
		                                };
		                        
		                                var chart = new google.visualization.$tipo(document.getElementById('curve_chart'));
		                        
		                                chart.draw(data, options);
		                              }
		                        </script> ".
		                        '<div id="curve_chart" style="margin-top: 50px; width: auto; height: '.$altura.'px"></div>';                                                  
		                                   
		            return $grafico;
		        }
        
        function imprimeGraficoPizza($dados_pizza,$nome)
        {
        	$nome_p = "p_$nome";
        	$nome_p =   '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                         <script type="text/javascript">'.
                                 "google.charts.load('current', {'packages':['corechart']});
                              google.charts.setOnLoadCallback(drawChart);
                              function drawChart()
                              {
                                var data = google.visualization.arrayToDataTable([".$dados_pizza."]);
                                var options =
                                {
                                  title: '',
                                  chartArea:{width:800,height: 400},              
        						};
        
        var chart = new google.visualization.ColumnChart(document.getElementById('$nome'));
        
        chart.draw(data, options);
        }
        </script> ".
        '<div style:"float:none;" id="'.$nome.'" style="width: auto; height: auto;"></div>';
        	 
        	return $nome_p;
        }
?>