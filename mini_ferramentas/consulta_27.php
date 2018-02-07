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
    
    <link rel="stylesheet" type="text/css" href="css/dataTables.css">  
    <script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

    <script>
        $(document).ready( function () {
            $('#tabela').DataTable( {
                "order": [[ 1, "asc" ]]
            } );
        } );
    </script>
</head>

<body>

       <?php
       
       $sql = '';
                                            
        
        $nome_relatorio = "Quantidade de Operadores - Detalhamento"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Quantidade de Operadores - Detalhamento "; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
        	                                                    
        echo '<div class="w3-margin w3-tiny w3-center">'; 
        echo "<b>$titulo</b>";
        echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto ";
        echo "<br><br><b><i>Intervalo de Horas:</i></b> $hora_inicial à $hora_final";
        echo "<br><br><b><i>Obs:</i></b> Operadores HE* - Operadores com intervalo de chamadas inicial/final maior que 7h e 15min/Dia.";
            echo "<br><br>";
        
            echo '<div class="w3-border" style="padding:16px 16px;">';
                echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
                    echo '<thead>
                                <tr class="w3-indigo w3-tiny">';                                
                                echo '<td><b>Data</b></td>';                               
                                echo '<td><b>Qtde Atendentes (Total)</b></td>';
                                echo '<td><b>Operadores</b></td>';
                                echo '<td><b>Prepostos</b></td>';
                                echo '<td><b>Operadores HE*</b></td>';
                                echo '<td><b>Operadores S/HE*</b></td>';
                                echo '<td><b>Op. >= 90 Ligações</b></td>';
                                echo '<td><b>Operadores Licença*</b></td>';
                        echo '</tr>
                          </thead>
                            <tbody>';
                      
                                $sql ="	select 
                                        	CONVERT (VARCHAR, CONVERT(DATETIME, data_hora, 103), 105) as data, 
                                        	datepart(dw,data_hora) dia_semana, 
                                        	count(distinct id_operador) total 
                                        from tb_eventos_dac
                                        where data_hora between '$data_inicial $hora_inicial' and '$data_final $hora_final'
                                        and tempo_atend > 0
                                        group by CONVERT (VARCHAR, CONVERT(DATETIME, data_hora, 103), 105), datepart(dw,data_hora)
                                        order by data ";                                                                                
                                
                                //echo $sql;
                                $query = $pdo->prepare($sql);
                                $query->execute();
                                for($i=0; $row = $query->fetch(); $i++)
                                {
                                    $data = utf8_encode($row['data']);	
                                    $data = date("Y-m-d", strtotime($data));
                                   
                                    $dia_semana = $row['dia_semana'];
                                    $dia_semana = diaSemana($dia_semana);                                    
                                    $total = utf8_encode($row['total']);	
                                    
                                    $sql = "select     
                                                count(distinct id_operador) qtde 	
                                            from tb_eventos_dac
                                            where data_hora between '$data $hora_inicial' and '$data $hora_final' 
                                            and tempo_atend > 0
                                            and id_operador in (30103,
                                            30673,30912,30914,30686,30104,30459,31243,30463,31251,30100,31923,30480,30480,31649,31287,30960,30500,30075,30997,30076,31611,
                                            30077,31007,30539,31010,30395,31355,30667,31925,31367,31373,30800,31552,30566,31064,31387,31089,31090,31099,30414,30416,30589,
                                            30591,30083,31926,31440,31139,30857,30102,30069,32005)
                                            ";
                                    
                                    //echo $sql;
                                    $query1 = $pdo->prepare($sql);
                                    $query1->execute();
                                    for($x=0; $row2 = $query1->fetch(); $x++)
                                    {
                                        $prepostos = $row2['qtde'];
                                    }
                                    
                                    $operadores = $total - $prepostos;
                                    
                                    //OPERADORES HORA EXTRAS
                                    $sql = " select count(*) qtde from (
                                            --lista de operadores por dia com horario inicial e final de trabalho
                                            select datepart(dd,t3.data_hora) dia,
                                            t3.id_operador,
                                            min(data_hora) hr_maxima,
                                            max(data_hora) hr_min,
                                            (DATEDIFF(minute, min(t3.data_hora),max(t3.data_hora))/60.00) hr_dif
                                            from tb_eventos_dac t3
                                            where t3.data_hora between '$data $hora_inicial' and '$data $hora_final'
                                                and t3.id_operador is not null
                                                group by datepart(dd,t3.data_hora),  t3.id_operador
                                                having
                                                (
                                                ((DATEDIFF(minute, min(t3.data_hora),max(t3.data_hora))/60.00) > 7.25)
                                                and
                                                ((DATEDIFF(minute, min(t3.data_hora),max(t3.data_hora))/60.00) < 15.00)
                                                )
                                                --order by (DATEDIFF(minute, min(t3.data_hora),max(t3.data_hora))/60.00) ,datepart(dd,t3.data_hora), id_operador
                                                
                                                ) as a ";
                                    
                                    $query1 = $pdo->prepare($sql);
                                    $query1->execute();
                                    for($x=0; $row2 = $query1->fetch(); $x++)
                                    {
                                        $qtde_he = $row2['qtde'];
                                    }
                                    
                                    $qtde_she = $total - $qtde_he;
                                    
                                    //OPERADORES QUE ATENDEM MAIS DE 90 LIGAÇÕES
                                    $sql = " select count(*) qtde from (	
                        						select     
                        						id_operador, count(distinct callid) qtde
                        						from tb_eventos_dac t
                        						where t.data_hora between '$data $hora_inicial' and '$data $hora_final'
                        						and t.tempo_atend > 0
                        						--and id_operador not in (30103,
                        						--30673,30912,30914,30686,30104,30459,31243,30463,31251,30100,31923,30480,30480,31649,31287,30960,30500,30075,30997,30076,31611,
                        						--30077,31007,30539,31010,30395,31355,30667,31925,31367,31373,30800,31552,30566,31064,31387,31089,31090,31099,30414,30416,30589,
                        						--30591,30083,31926,31440,31139,30857,30102,30069,32005)
                        						group by id_operador
                        						having count(distinct callid) >= 90
                        					) as A ";
                                    
                                    $query1 = $pdo->prepare($sql);
                                    $query1->execute();
                                    for($x=0; $row2 = $query1->fetch(); $x++)
                                    {
                                        $qtde_90l = $row2['qtde'];
                                    }
                                    
                                    //echo $sql;
                                    
                                    //imprimindo resultados
                                    echo '<tr>';
                                    echo "<td>$data ($dia_semana)</td>";
                                    echo "<td><a class='w3-text-indigo' title='Listar Operadores' href= \"lista_operadores_c27.php?data=$data&horainicial=$hora_inicial&horafinal=$hora_final&coluna=2\" target=\"_blank\">$total</a></td>";                                 
                                    echo "<td><a class='w3-text-indigo' title='Listar Operadores' href= \"lista_operadores_c27.php?data=$data&horainicial=$hora_inicial&horafinal=$hora_final&coluna=3\" target=\"_blank\">$operadores</a></td>";                                   
                                    echo "<td><a class='w3-text-indigo' title='Listar Operadores' href= \"lista_operadores_c27.php?data=$data&horainicial=$hora_inicial&horafinal=$hora_final&coluna=4\" target=\"_blank\">$prepostos</a></td>";                                  
                                    echo "<td><a class='w3-text-indigo' title='Listar Operadores' href= \"lista_operadores_c27.php?data=$data&horainicial=$hora_inicial&horafinal=$hora_final&coluna=5\" target=\"_blank\">$qtde_he</a></td>";                                   
                                    echo "<td>$qtde_she</td>";
                                    echo "<td><a class='w3-text-indigo' title='Listar Operadores' href= \"lista_operadores_c27.php?data=$data&horainicial=$hora_inicial&horafinal=$hora_final&coluna=7\" target=\"_blank\">$qtde_90l</a></td>";                                    
                                    echo "<td>0</td>";
                                    echo '</tr>';
                                }
            
                       echo "</tbody>
                    </table>";
		     echo "</div>";
		echo "</div>";
		echo "<br><br>";
		include "desconecta.php";
?>


</body>
</html>

