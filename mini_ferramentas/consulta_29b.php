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
    <link rel="stylesheet" type="text/css" href="css/dataTables.css">  
    <script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

    <script>
        $(document).ready( function () {
            $('#tabela').DataTable( {
                "order": [[ 4, "desc" ]]
            } );
        } );
    </script>
</head>

<body>

       <?php
                  
        $sql = '';                                                   
        $nome_relatorio = "Retencao_ATC"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Retenção ATC - Análise de Dados"; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
        
        $sbandeira = ($rd_bandeira == '' ? 'Todas' : $rd_bandeira);
        
        echo '<div class="w3-margin w3-tiny w3-center">'; 
        echo "<b>$titulo (Por Operador)</b>";
        echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto ";        
        echo "<br><b><i>Qtde Mínima de Atendimentos:</i></b> $corte_retencao";
        echo "<br><b><i>Base de Comparação:</i></b> $base_comp_retencao dias";
        echo "<br><b><i>Bandeira:</i></b> $sbandeira";
        echo "<br><br>";
        
            echo '<div class="w3-border" style="padding:16px 16px;">';
                echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
                    echo '<thead>
                                <tr class="w3-indigo w3-tiny">';
                                echo '<td><b>Operador</b></td>';
                                echo '<td><b>Supervisor</b></td>';                               
                                echo '<td><b>Atendidos</b></td>';
                                
                                echo '<td><b>Retidos</b></td>';
                                echo '<td><b>Pct(%)</b></td>';
                                
                                echo '<td><b>Não Ret.</b></td>';
                                echo '<td><b>Pct(%)</b></td>';
                                
                                echo "<td class='tooltip'><b>Atend. Ant.*</b>
                                <span class='tooltiptext'>Total de Atendimentos efetuados no período anterior de comparação</span>
                                </td>";
                                echo "<td class='tooltip'><b>Ret. Ant.*</b>
                                <span class='tooltiptext'>Total de Atendimentos Retidos no período anterior de comparação</span>
                                </td>";
                                echo '<td><b>Pct(%)</b></td>';
                                echo "<td class='tooltip'><b>Diferença(%)*</b>
                                <span class='tooltiptext'>Diferença em pontos percentuais Retidos x Retidos Anteriormente</span>
                                </td>";
                                echo "<td bgcolor='green' class='tooltip'><b>Incremento(%)</b>
                                <span class='tooltiptext'>Percentual de incremento sobre índice de retenção do período anterior</span>    
                                </td>";                                
                                
                        echo '</tr>
                          </thead>
                            <tbody>';
                      
                        $sql = "    set nocount on;
                                    declare @T TABLE(id_operador integer,
                                                  operador varchar(100),
                                                  login_dac_operador int,
                                                  id_supervisor int,
                                                  supervisor varchar(100),
                                                  login_dac_supervisor int,
                                                  qtde_atendido integer,
                                                  qtde_retido integer,
                                                  pct_retido float,
                                                  qtde_n_retido integer,
                                                  pct_n_retido float,
                                                  qtde_retido_desc integer,
                                                  pct_retido_desc float,
                                                  qtde_retido_arg integer,
                                                  pct_retido_arg float,
                                                  tma int,
                                                  qtde_atendido_ant int,
                                                  qtde_retido_ant int,
                                                  pct_retido_ant float,
                                                  dif_ret_ant float,
                                                  inc_ret_ant float,
                                                  data1_ant date,
                                                  data2_ant date
                                                );
                                
                                insert @T EXEC sp_CERATFO_radar_cartoes_query29c '$data_inicial_u','$data_final_u',0,0,$corte_retencao,$base_comp_retencao,'$rd_bandeira',''
                                
                                select id_operador,
                            			operador,
                            			login_dac_operador,
                            			id_supervisor,
                            			supervisor,
                            			login_dac_supervisor,
                            			qtde_atendido,
                            			qtde_retido,
                            			pct_retido,
                            			qtde_n_retido,
                            			pct_n_retido,
                            			qtde_retido_desc,
                            			pct_retido_desc,
                            			qtde_retido_arg,
                            			pct_retido_arg,
                            			tma,
                            			qtde_atendido_ant,
                            			qtde_retido_ant,
                            			pct_retido_ant,
                            			dif_ret_ant,
                            			inc_ret_ant,
                            			data1_ant,
                            			data2_ant from @T ";
                                
                               // echo $sql;
                                $total_atendido = 0;
                                $total_retido = 0;
                                $total_nretido = 0;
                                $total_atendido_ant = 0;
                                $total_retido_ant = 0;
                                $dadosgrafico2 = '';
                                $query = $pdo->prepare($sql);
                                $query->execute();                                
                                for($i=0; $row = $query->fetch(); $i++)
                                {                                                                  
                                    $id_operador = utf8_encode($row['id_operador']);	
                                    $operador = utf8_encode($row['operador']);                                    
                                    $login_dac_operador = utf8_encode($row['login_dac_operador']);
                                    $id_supervisor = utf8_encode($row['id_supervisor']);
                                    $supervisor = utf8_encode($row['supervisor']);
                                    $login_dac_supervisor = utf8_encode($row['login_dac_supervisor']);
                                    $qtde_atendido = utf8_encode($row['qtde_atendido']);
                                    $qtde_retido = utf8_encode($row['qtde_retido']);
                                    
                                    $pct_retido = utf8_encode($row['pct_retido']);
                                    $pct_retido = number_format($pct_retido, 2, ',', '.');
                                    
                                    $qtde_n_retido = utf8_encode($row['qtde_n_retido']);
                                    $pct_n_retido = utf8_encode($row['pct_n_retido']);
                                    $pct_n_retido = number_format($pct_n_retido, 2, ',', '.');
                                    
                                    $qtde_retido_desc = utf8_encode($row['qtde_retido_desc']);
                                    $pct_retido_desc = utf8_encode($row['pct_retido_desc']);
                                    $pct_retido_desc = number_format($pct_retido_desc, 2, ',', '.');
                                    
                                    $qtde_retido_arg = utf8_encode($row['qtde_retido_arg']);
                                    $pct_retido_arg = utf8_encode($row['pct_retido_arg']);
                                    $pct_retido_arg = number_format($pct_retido_arg, 2, ',', '.');
                                    
                                    $tma = utf8_encode($row['tma']);
                                    $qtde_atendido_ant = utf8_encode($row['qtde_atendido_ant']);
                                    $qtde_retido_ant = utf8_encode($row['qtde_retido_ant']);
                                    
                                    $pct_retido_ant = utf8_encode($row['pct_retido_ant']);
                                    $pct_retido_ant = number_format($pct_retido_ant, 2, ',', '.');
                                    
                                    $dif_ret_ant = utf8_encode($row['dif_ret_ant']);
                                    $dif_ret_ant = number_format($dif_ret_ant, 2, ',', '.');
                                    
                                    $inc_ret_ant = utf8_encode($row['inc_ret_ant']);
                                    $inc_ret_ant = number_format($inc_ret_ant, 2, ',', '.');
                                  
                                    $data1_ant = utf8_encode($row['data1_ant']);
                                    $data1_ant = date("Y-m-d", strtotime($data1_ant));
                                    
                                    $data2_ant = utf8_encode($row['data2_ant']);
                                    $data2_ant = date("Y-m-d", strtotime($data2_ant));
                                     
                                    //totais 
                                    $total_atendido += $qtde_atendido;
                                    $total_retido += $qtde_retido;
                                    $total_nretido += $qtde_n_retido;
                                    $total_atendido_ant += $qtde_atendido_ant;
                                    $total_nretido_ant += $qtde_retido_ant;
                                    
                                    
                                    //imprimindo resultados
                                    echo '<tr>';
                                    echo "<td>($id_operador) $operador</td>";
                                    echo "<td>($id_supervisor) $supervisor</td>";                                    
                                    if ($qtde_atendido > 0) 
                                       echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao_oper_super.php?pData1=$data_inicial_u&pData2=$data_final_u&pBandeira=$rd_bandeira&pOperador=$id_operador&pComparacao=$base_comp_retencao&pGrupo=Atendimentos\" target=\"_blank\">$qtde_atendido</a></td>";                                   
                                    else 
                                       echo "<td><b>$qtde_atendido</b></td>";
                                    
                                    if ($qtde_retido> 0)
                                       echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao_oper_super.php?pData1=$data_inicial_u&pData2=$data_final_u&pBandeira=$rd_bandeira&pOperador=$id_operador&pComparacao=$base_comp_retencao&pGrupo=Retido\" target=\"_blank\">$qtde_retido</a></td>";
                                    else
                                       echo "<td><b>$qtde_retido</b></td>";
                                    
                                    echo "<td><b>$pct_retido%</b></td>";
                                    
                                    if ($qtde_n_retido> 0)
                                        echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao_oper_super.php?pData1=$data_inicial_u&pData2=$data_final_u&pBandeira=$rd_bandeira&pOperador=$id_operador&pComparacao=$base_comp_retencao&pGrupo=NaoRetido\" target=\"_blank\">$qtde_n_retido</a></td>";
                                    else 
                                        echo "<td><b>$qtde_n_retido</b></td>";
                                    
                                    echo "<td><b>$pct_n_retido%</b></td>";
                                   
                                    if ($qtde_atendido_ant > 0) 
                                        echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao_oper_super.php?pData1=$data_inicial_u&pData2=$data_final_u&pBandeira=$rd_bandeira&pOperador=$id_operador&pComparacao=$base_comp_retencao&pGrupo=AtendimentoAnt\" target=\"_blank\">$qtde_atendido_ant</a></td>";
                                    else 
                                        echo "<td><b>$qtde_atendido_ant</b></td>";
                                   
                                        if ($qtde_retido_ant > 0)
                                            echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_retencao_oper_super.php?pData1=$data_inicial_u&pData2=$data_final_u&pBandeira=$rd_bandeira&pOperador=$id_operador&pComparacao=$base_comp_retencao&pGrupo=RetidoAnt\" target=\"_blank\">$qtde_retido_ant</a></td>";
                                    else
                                        echo "<td><b>$qtde_retido_ant</b></td>";
                                    
                                    echo "<td><b>$pct_retido_ant%</b></td>";
                                    echo "<td><b>$dif_ret_ant%</b></td>";
                                    echo "<td><b>$inc_ret_ant%</b></td>";                                                                       
                                    echo '</tr>';
                                    
                                    //listando somente os 20 primeiro operadores por nível de retenção
                                    if ($i<15) 
                                    {
                                        $operador = $operador.' ('.$pct_retido.'%)';
                                        $dadosgrafico2 = $dadosgrafico2."['$operador',$qtde_atendido,$qtde_atendido, $qtde_retido,$qtde_retido, $qtde_n_retido, $qtde_n_retido],";
                                    }
                                }                                                                
                       echo "</tbody>
                        <tr class='w3-indigo'>                                              	                        	                        
                        	<td><b>TOTAL</b></td>
                        	<td></td>                            
                        	<td><b>$total_atendido</b></td>
                            <td><b>$total_retido</b></td>
                            <td></td>
                            <td><b>$total_nretido</b></td>
                            <td></td>
                            <td><b>$total_atendido_ant</b></td>
                            <td><b>$total_nretido_ant</b></td>  
                            <td></td>
                            <td></td>
                            <td></td>                          	                                                            
                        </tr>                        
                    </table>";
		     echo "</div>";
		echo "</div>";
		
		
		$altura = 300;
		
		$grafico =   '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                             <script type="text/javascript">'.
                             "google.charts.load('current', {'packages':['corechart'], 'language': 'pt'});
                                  google.charts.setOnLoadCallback(drawChart);
                                  
                                  function drawChart()
                                  {    		                        
                                     var data2 = new google.visualization.DataTable();
                                     data2.addColumn('string', 'Operador');
                                     data2.addColumn('number', 'Atendimentos');
                                     data2.addColumn({type: 'number', role: 'annotation'});  
                                     data2.addColumn('number', 'Retidos');
                                     data2.addColumn({type: 'number', role: 'annotation'});  
                                     data2.addColumn('number', 'Não Retidos');
                                     data2.addColumn({type: 'number', role: 'annotation'});        
                                     
                                                                        
                                     data2.addRows([$dadosgrafico2]);                                     
                                     var options2 = {
                                                        title: '".$titulo." - Por Operador (15 Primeiros que mais retém)',                                       
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
                                 '<div id="chart2_div" style="margin-top: 50px; width: auto; height: 500px"></div>  ';                                                                                                            
                                                      
                                                      
        echo $grafico;
        
		include "desconecta.php";
?>


</body>
</html>

