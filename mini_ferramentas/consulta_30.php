<!DOCTYPE html>
<html>
<head>

   <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/radar.css">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />

<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>
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
       $inicio = defineTime();
       
        $sql = '';                                                   
        $nome_relatorio = "Campanha de UPGRADE - Mastercard"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Campanha de UPGRADE - Mastercard"; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
                	                                                  
        echo '<div class="w3-margin w3-tiny w3-center">'; 
        echo '<div id="divtitulo" class="w3-margin w3-tiny w3-center">';
            echo "<b>$titulo</b>";
            echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto ";                
            echo "<br>";
        echo "</div>";
            
        $texto = "<td class='tooltip'><b>TOTAL DE ATENDIMENTOS *&nbsp</b>
                    <span class='tooltiptext'>ATENDIMENTOS no ATC (atendimento humano) com tempo de atendimento > 0 segundos</span>
                  </td>";
            echo '<div class="w3-border" style="padding:16px 16px;">';
                echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
                    echo '<thead>
                                <tr class="w3-indigo w3-tiny">';
                                echo '<td><b>Data</b></td>';
                                
                                echo "<td class='tooltip'><b>Recebidas *&nbsp</b>
                                <span class='tooltiptext'>Ligações Recebidas na fila 64-CXA_MALA_DIRETA (Campanha)</span>
                                </td>";                               
                                
                                echo "<td class='tooltip'><b>Categorizadas *&nbsp</b>
                                <span class='tooltiptext'>Ligações Recebidas e Categorizadas</span>
                                </td>";
                                
                                echo "<td class='tooltip'><b>Não Categorizadas *&nbsp</b>
                                <span class='tooltiptext'>Ligações Recebidas NÃO Categorizadas</span>  
                                </td>";
                                
                                echo "<td class='tooltip'><b>Categorizadas (NÃO Campanha) *&nbsp</b>
                                <span class='tooltiptext'>Ligações Recebidas e Categorizadas com Motivos/Submotivos NÃO PERTENCENTES a Campanha</span>
                                </td>";                                                               
                          
                                echo "<td class='tooltip'><b>Categorizadas (Campanha) *&nbsp</b>
                                <span class='tooltiptext'>Ligações Recebidas e Categorizadas conforme a campanha</span>
                                </td>";
                                
                                echo "<td class='tooltip'><b>Campanha (Aceitou) *&nbsp</b>
                                <span class='tooltiptext'>Cliente aceitou o Upgrade</span>    
                                </td>";
                                
                                echo "<td class='tooltip'><b>Campanha (Ñ - Anuidade Alta) *&nbsp</b>
                                <span class='tooltiptext'>Cliente Não Aceitou Upgrade - Anuidade Alta</span>
                                </td>";
                                
                                echo "<td class='tooltip'><b>Campanha (Ñ - Sem Interesse Pontos) *&nbsp</b>
                                <span class='tooltiptext'>Cliente Não Aceitou Upgrade - Sem Interesse na Pontuação</span>
                                </td>";
                                
                                echo "<td class='tooltip'><b>Campanha (Ñ - Sem Interesse Variante) *&nbsp</b>
                                <span class='tooltiptext'>Cliente Não Aceitou Upgrade - Sem Interesse na Variante</span>
                                </td>";
                                echo "<td class='tooltip'><b>Campanha (Ñ - Não Informou) *&nbsp</b>
                                <span class='tooltiptext'>Cliente Não Aceitou Upgrade - Não Informou</span>
                                </td>";   
                                
                                echo "<td bgcolor='red' class='tooltip'><b>Categorizadas Erroneamente (BASE) *&nbsp</b>
                                <span class='tooltiptext'>Ligações Recebidas de clientes da BASE mas Categorizadas com Motivos/Submotivos NÃO PERTENCENTES a Campanha</span>
                                </td>";
                                
                        echo '</tr>
                          </thead>
                            <tbody>';
                      
                        $sql = "set nocount on;
                        
                                declare @T TABLE(   dia date,
                                                    recebidas int,
                                					recebidas_categorizadas int,
                                                    recebidas_nao_categorizadas int,
                                                    categorizadas_nao_campanha	int,
                                                    categorizadas_nao_campanha_BASE	int,
                                                    categorizadas_campanha int,
                                                    campanha_nao_aceito_anui_alta int,
                                                    campanha_nao_aceito_nao_int_pontos int,
                                                    campanha_nao_aceito_nao_int_variant	int,
                                                    campanha_nao_aceito_nao_informou int,
                                                    campanha_aceitou int);
                                insert @T EXEC sp_CERATFO_radar_cartoes_query30 '$data_inicial_u','$data_final_u',1,64
                                
                                select  dia,	
                                        datepart(dw,dia) dia_semana,
                                        DATEPART(dd,dia) d_dia,
                                        DATEPART(mm,dia) d_mes,
                                        DATEPART(YYYY,dia) d_ano,
                                        recebidas,	
                                        recebidas_categorizadas,	
                                        recebidas_nao_categorizadas,	
                                        categorizadas_nao_campanha,	
                                        categorizadas_nao_campanha_BASE,	
                                        categorizadas_campanha,	
                                        campanha_nao_aceito_anui_alta,	
                                        campanha_nao_aceito_nao_int_pontos,	
                                        campanha_nao_aceito_nao_int_variant,	
                                        campanha_nao_aceito_nao_informou,	
                                        campanha_aceitou
                                from @T";
                                                                                                        
                                $total_recebidas = 0;
                                $total_recebidas_categorizadas = 0;
                                $total_recebidas_nao_categorizadas = 0;
                                $total_categorizadas_nao_campanha = 0;
                                $total_categorizadas_nao_campanha_BASE = 0;
                                $total_categorizadas_campanha = 0;
                                $total_campanha_nao_aceito_anui_alta = 0;
                                $total_campanha_nao_aceito_nao_int_pontos = 0;
                                $total_campanha_nao_aceito_nao_int_variant = 0;
                                $total_campanha_nao_aceito_nao_informou = 0;
                                $total_campanha_aceitou = 0;
                                $dadosgrafico1 = '';
                                $dadosgrafico2 = '';
                                //echo $sql;                                
                                $query = $pdo->prepare($sql);
                                $query->execute();                                
                                for($i=0; $row = $query->fetch(); $i++)
                                {
                                    $pct_retido = 0;
                                    $pct_ret_arg = 0;
                                    $pct_ret_desc = 0;
                                    
                                    $data = utf8_encode($row['dia']);	
                                    $dia_semana = $row['dia_semana'];                                    
                                    $data = date("Y-m-d", strtotime($data));
                                                                       
                                    $dia_semana = diaSemana($dia_semana);                                    
                                    $recebidas = utf8_encode($row['recebidas']);	
                                    $recebidas_categorizadas = utf8_encode($row['recebidas_categorizadas']);
                                    $recebidas_nao_categorizadas = utf8_encode($row['recebidas_nao_categorizadas']);
                                    $categorizadas_nao_campanha = utf8_encode($row['categorizadas_nao_campanha']);
                                    $categorizadas_nao_campanha_BASE = utf8_encode($row['categorizadas_nao_campanha_BASE']);
                                    $categorizadas_campanha = utf8_encode($row['categorizadas_campanha']);
                                    $campanha_nao_aceito_anui_alta = utf8_encode($row['campanha_nao_aceito_anui_alta']);
                                    $campanha_nao_aceito_nao_int_pontos = utf8_encode($row['campanha_nao_aceito_nao_int_pontos']);
                                    $campanha_nao_aceito_nao_int_variant = utf8_encode($row['campanha_nao_aceito_nao_int_variant']);
                                    $campanha_nao_aceito_nao_informou = utf8_encode($row['campanha_nao_aceito_nao_informou']);
                                    $campanha_aceitou = utf8_encode($row['campanha_aceitou']);
                                    
                                    $total_recebidas = $total_recebidas + $recebidas;
                                    $total_recebidas_categorizadas = $total_recebidas_categorizadas + $recebidas_categorizadas;
                                    $total_recebidas_nao_categorizadas = $total_recebidas_nao_categorizadas + $recebidas_nao_categorizadas;
                                    $total_categorizadas_nao_campanha = $total_categorizadas_nao_campanha + $categorizadas_nao_campanha;
                                    $total_categorizadas_nao_campanha_BASE = $total_categorizadas_nao_campanha_BASE + $categorizadas_nao_campanha_BASE;
                                    $total_categorizadas_campanha = $total_categorizadas_campanha + $categorizadas_campanha;
                                    $total_campanha_nao_aceito_anui_alta = $total_campanha_nao_aceito_anui_alta + $campanha_nao_aceito_anui_alta;
                                    $total_campanha_nao_aceito_nao_int_pontos = $total_campanha_nao_aceito_nao_int_pontos + $campanha_nao_aceito_nao_int_pontos;
                                    $total_campanha_nao_aceito_nao_int_variant = $total_campanha_nao_aceito_nao_int_variant + $campanha_nao_aceito_nao_int_variant;
                                    $total_campanha_nao_aceito_nao_informou = $total_campanha_nao_aceito_nao_informou + $campanha_nao_aceito_nao_informou;
                                    $total_campanha_aceitou = $total_campanha_aceitou + $campanha_aceitou;
                                     
                                    //dados do grafico
                                    $d_dia = $row['d_dia'];
                                    $d_mes = $row['d_mes'];
                                    $d_ano = $row['d_ano'];                                   
                                    $dadosgrafico1 = $dadosgrafico1."[new Date($d_ano,$d_mes,$d_dia),$recebidas, $recebidas_categorizadas, $categorizadas_campanha],";                                    
                                    //imprimindo resultados
                                    echo '<tr>';
                                    echo "<td>$data ($dia_semana)</td>";                                        
                                    if ($recebidas > 0) 
                                        echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=recebidas\" target=\"_blank\">$recebidas</a></b></td>";                                   
                                    else 
                                        echo "<td>$recebidas</td>";
                                    
                                    if ($recebidas_categorizadas> 0)
                                       echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=recebidas_categorizadas\" target=\"_blank\">$recebidas_categorizadas</a></b></td>";
                                    else
                                       echo "<td>$recebidas_categorizadas</td>";
                                    
                                    if ($recebidas_nao_categorizadas> 0)
                                        echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=recebidas_nao_categorizadas\" target=\"_blank\">$recebidas_nao_categorizadas</a></b></td>";
                                    else 
                                        echo "<td>$recebidas_nao_categorizadas</td>";
                                    
                                    if ($categorizadas_nao_campanha> 0)
                                        echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=categorizadas_nao_campanha\" target=\"_blank\">$categorizadas_nao_campanha</a></b></td>";
                                    else
                                       echo "<td>$categorizadas_nao_campanha</td>";                                                                       
                                    
                                    if ($categorizadas_campanha> 0)
                                       echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=categorizadas_campanha\" target=\"_blank\">$categorizadas_campanha</a></b></td>";
                                    else
                                       echo "<td>$categorizadas_campanha</td>";
                                    
                                    if ($campanha_aceitou> 0)
                                       echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=campanha_aceitou\" target=\"_blank\">$campanha_aceitou</a></b></td>";
                                    else
                                      echo "<td>$campanha_aceitou</td>";
                                           
                                    if ($campanha_nao_aceito_anui_alta> 0)
                                       echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=campanha_nao_aceito_anui_alta\" target=\"_blank\">$campanha_nao_aceito_anui_alta</a></b></td>";
                                    else
                                       echo "<td>$campanha_nao_aceito_anui_alta</td>";
                                        
                                    if ($campanha_nao_aceito_nao_int_pontos> 0)
                                       echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=campanha_nao_aceito_nao_int_pontos\" target=\"_blank\">$campanha_nao_aceito_nao_int_pontos</a></b></td>";
                                    else
                                       echo "<td>$campanha_nao_aceito_nao_int_pontos</td>";
                                                
                                    if ($campanha_nao_aceito_nao_int_variant> 0)
                                       echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=campanha_nao_aceito_nao_int_variant\" target=\"_blank\">$campanha_nao_aceito_nao_int_variant</a></b></td>";
                                    else
                                       echo "<td>$campanha_nao_aceito_nao_int_variant</td>";
                                                                                                                    
                                    if ($campanha_nao_aceito_nao_informou> 0)
                                       echo "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=campanha_nao_aceito_nao_informou\" target=\"_blank\">$campanha_nao_aceito_nao_informou</a></b></td>";
                                    else
                                       echo "<td>$campanha_nao_aceito_nao_informou</td>";
                                                                
                                    if ($categorizadas_nao_campanha_BASE> 0)
                                        echo "<td ><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_campanha_upgrade.php?pData=$data&pGrupo=categorizadas_nao_campanha_BASE\" target=\"_blank\">$categorizadas_nao_campanha_BASE</a></b></td>";
                                     else
                                        echo "<td>$categorizadas_nao_campanha_BASE</td>";
                                       
                                    echo '</tr>';
                                }
            
                       echo "</tbody>
                       <tr class='w3-indigo'>                                              	                        	                        
                        	<td><b>TOTAL</b></td>
                        	<td><b>$total_recebidas</b></td>
                        	<td><b>$total_recebidas_categorizadas</b></td>
                        	<td><b>$total_recebidas_nao_categorizadas</b></td>                        	                        
                        	<td><b>$total_categorizadas_nao_campanha</b></td>                            
                            <td><b>$total_categorizadas_campanha</b></td>
                            <td><b>$total_campanha_aceitou</b></td>
                            <td><b>$total_campanha_nao_aceito_anui_alta</b></td>
                            <td><b>$total_campanha_nao_aceito_nao_int_pontos</b></td>
                            <td><b>$total_campanha_nao_aceito_nao_int_variant</b></td>
                            <td><b>$total_campanha_nao_aceito_nao_informou</b></td>
                            <td><b>$total_categorizadas_nao_campanha_BASE</b></td>
                            
                        </tr> 
                    </table>";
		     echo "</div>";
		echo "</div>";
		
		
		$total_campanha_n_aceitou = $total_categorizadas_campanha - $total_campanha_aceitou;
		
		if ($total_categorizadas_campanha > 0)
		    $pct_aceito = round(($total_campanha_aceitou/$total_categorizadas_campanha),4)*100.00;
		else 
		    $pct_aceito = 0;
		
		$campanha = 'Aceitos ('.$pct_aceito.'%)';
		$dadosgrafico2 = $dadosgrafico2."['$campanha',$total_categorizadas_campanha,$total_categorizadas_campanha, $total_campanha_aceitou,$total_campanha_aceitou, $total_campanha_n_aceitou, $total_campanha_n_aceitou]";
		
		$altura = 300;
		
		$grafico =   '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                             <script type="text/javascript">'.
                             "google.charts.load('current', {'packages':['corechart'], 'language': 'pt'});
                                  google.charts.setOnLoadCallback(drawChart);
                                  
                                  function drawChart()
                                  {
    		                         var data1 = new google.visualization.DataTable();
                                     data1.addColumn('date', 'Dia');
            		                 data1.addColumn('number', 'RECEBIDAS');
            		                 data1.addColumn('number', 'CATEGORIZADAS');
            		                 data1.addColumn('number', 'CATEGORIZADAS NA CAMPANHA');                                                                         
                                     data1.addRows([$dadosgrafico1]);
                                     var options1 =
                                                    {
                                                        title: '".$titulo."',
                                                        curveType: 'function',
                                                        series: {
                                                        		    0: { pointShape: 'star', pointSize:10 },
                                                                  	1: { color: 'red', pointShape: 'square', pointSize:6 },
                                                                  	2: { color: 'green',pointShape: 'polygon', pointSize:6 }
                                                                    
                                                        		},
                                                        vAxis: {
                                                                    viewWindowMode:'explicit',
                                                                    viewWindow: {
                                                                        max:$total_recebidas,
                                                                        min:0
                                                                    }
                                                                } ,
                                                		vAxes: {
                                                		    0: {title: 'Quantidade de Ligações'}
                                                		},
                                                      legend: { position: 'top' },                                                     
                                                    };
                                     
                                     var chart = new google.visualization.LineChart(document.getElementById('chart1_div'));
                                     chart.draw(data1, options1);

                                     var data2 = new google.visualization.DataTable();
                                     data2.addColumn('string', 'Campanha');
                                     data2.addColumn('number', 'Categorizadas');
                                     data2.addColumn({type: 'number', role: 'annotation'});  
                                     data2.addColumn('number', 'Aceitos');
                                     data2.addColumn({type: 'number', role: 'annotation'});  
                                     data2.addColumn('number', 'Não Aceitos');
                                     data2.addColumn({type: 'number', role: 'annotation'});        
                                     
                                                                        
                                     data2.addRows([$dadosgrafico2]);                                     
                                     var options2 = {
                                                        title: 'Campanha de UPGRADE Mastercard - Índice de Aceitação',                                       
                                                        bars: 'vertical',
                                                        isStacked: false,
                                                        legend: { position: 'top' },
                                                        vAxes: {
                                                		    0: {title: 'Qtde de Ligações'}
                                                		},
                                                     };
                                      var chart2 = new google.visualization.ColumnChart(document.getElementById('chart2_div'));
                                      chart2.draw(data2, options2);
                                       
                                   }
                                 </script> ".
                                 '<div id="chart1_div" style="margin-top: 50px; width: auto; height: 500px"></div>
                                  <div id="chart2_div" style="margin-top: 50px; width: auto; height: 500px"></div>  
                                  ';                                                                                                            
                                                      
                                                      
        echo $grafico;	
		$fim = defineTime();
		echo tempoDecorrido($inicio,$fim);
		include "desconecta.php";
		
?>

<script>  
    document.getElementById("divtitulo").appendChild(document.getElementById("tmp"));    
</script>
</body>
</html>

