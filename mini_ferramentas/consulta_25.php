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
       $swhere = '';
       $criterios = '';
       if ($cd_motivo > 0)
       {           
          $swhere .= " and tl.cd_motivo = '$cd_motivo'";
          
          $query = $pdo->prepare("select distinct  ds_motivo from tb_log_categorizacao
                        where data_hora between (GETDATE() - 5) and (GETDATE() - 3)
                        and cd_motivo = $cd_motivo");
          $query->execute();
          for($i=0; $row = $query->fetch(); $i++)
          {
              $criterios .= " Motivo: ".$row['ds_motivo'];             
          }          
          
       }
       else
           $criterios .= " Motivo: Todos ";
       
       if ($cd_submotivo > 0)
       {
          $swhere .= " and tl.cd_submotivo = '$cd_submotivo'";
          $query = $pdo->prepare("select distinct  ds_submotivo from tb_log_categorizacao
                        where data_hora between (GETDATE() - 5) and (GETDATE() - 3)
                        and cd_submotivo = $cd_submotivo");
          $query->execute();
          for($i=0; $row = $query->fetch(); $i++)
          {
              $criterios .= " -  SubMotivo: ".$row['ds_submotivo'];
          }          
        }
        else 
            $criterios .= " -  SubMotivo: Todos";
        
       if ($pesq_satisf_perg1 > 0)
       {    
         $swhere .= " and tps.perg1 = '$pesq_satisf_perg1'";
         switch ($pesq_satisf_perg1)
         {                             
             case 1:
                 $criterios .= "  </br> <b>Perg. 1:</b> Satisfeito";              
                 break;
                 
             case 2:
                 $criterios .= "  </br> <b>Perg. 1:</b> Indiferente";
                 break;
             case 3:
                 $criterios .= "  </br> <b>Perg. 1:</b> Insatisfeito";
                 break;
         }
        
       }
       else 
           $criterios .= " </br>  <b>Perg. 1:</b> Todas";
       
       if ($pesq_satisf_perg2 > 0)
       {            
         $swhere .= " and tps.perg2 = '$pesq_satisf_perg2'";
         switch ($pesq_satisf_perg2)
         {
             case 1:
                 $criterios .= "  <b>Perg. 2:</b> Satisfeito";
                 break;
                 
             case 2:
                 $criterios .= "  <b>Perg. 2:</b> Indiferente";
                 break;
             case 3:
                 $criterios .= "  <b>Perg. 2:</b> Insatisfeito";
                 break;
         }
       }
       else
            $criterios .= "  <b>Perg. 2:</b> Todas";
       
       if ($pesq_satisf_perg3 > 0)
       {    
         $swhere .= " and tps.perg3 = '$pesq_satisf_perg3'";
         
         switch ($pesq_satisf_perg3)
         {
             case 1:
                 $criterios .= "  <b>Perg. 3:</b> Satisfeito";
                 break;
                 
             case 2:
                 $criterios .= "  <b>Perg. 3:</b> Indiferente";
                 break;
             case 3:
                 $criterios .= "  <b>Perg. 3:</b> Insatisfeito";
                 break;
         }
       }
       else
           $criterios .= "  <b>Perg. 3:</b> Todas";
       
       if ($pesq_satisf_perg4 > 0)
       {
         $swhere .= " and tps.perg4 = '$pesq_satisf_perg4'";
         switch ($pesq_satisf_perg4)
         {
             case 1:
                 $criterios .= "  <b>Perg. 4:</b> Sim";
                 break;
                 
             case 2:
                 $criterios .= "  <b>Perg. 4:</b> Parcialmente";
                 break;
             case 3:
                 $criterios .= "  <b>Perg. 4:</b> Não";
                 break;
         }
       }  
       else
           $criterios .= "  <b>Perg. 4:</b> Todas";
       
        
        $nome_relatorio = "Pesquisa_de_Satisfação_Motivo_SubMotivo"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Relatório - Pesquisa de Satisfação - Motivo/SubMotivo "; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
        	                                                    
        echo '<div class="w3-margin w3-tiny w3-center">';
        echo "<b>$titulo</b>";
            echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto1 à $data_final_texto1";
           echo "<br><br><b><i>Critérios de Filtro:</i></b> $criterios";
            echo "<br><br>";
        
            echo '<div class="w3-border" style="padding:16px 16px;">';
                echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
                    echo '<thead>
                                <tr class="w3-indigo w3-tiny">';
                                echo '<td><b>CALLID</b></td>';
                                echo '<td><b>DATA/HORA</b></td>';
                                echo '<td><b>CÓDIGO FILA</b></td>';
                                echo '<td><b>DESCRIÇÃO FILA</b></td>';
                                echo '<td><b>TEMPO DE ESPERA</b></td>';
                                echo '<td><b>TEMPO DE ATENDIMENTO</b></td>';
                                echo '<td><b>ID OPERADOR</b></td>';
                                echo '<td><b>NOME OPERADOR</b></td>';
                        echo '</tr>
                          </thead>
                            <tbody>';                   
                                $sql ="	select distinct ted.*, f.desc_fila from tb_eventos_dac ted
                                		left join tb_filas f on (ted.cod_fila = f.cod_fila)
                                		inner join tb_log_categorizacao tl on (tl.callid = ted.callid)
                                		inner join tb_pesq_satisfacao tps on (ted.callid = tps.callid)
                                		where  ted.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                		and tps.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                		and tl.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'   
                                        $swhere
                                		and ted.id_operador is not null
                                		order by ted.callid, ted.data_hora
                                		";
                        		
                        		        //echo $sql;
                                		$query = $pdo->prepare($sql);
                                		$query->execute();
                                		for($i=0; $row = $query->fetch(); $i++){
                                		    $callid = $row['callid'];
                                		    $data_hora = $row['data_hora'];
                                		    $cod_fila = $row['cod_fila'];
                                		    $cod_fila = number_format($cod_fila, 0, ',', '.');
                                		    $desc_fila = $row['desc_fila'];
                                		    if($desc_fila == NULL) $desc_fila = "";
                                		    $tempo_espera = $row['tempo_espera'];
                                		    $tempo_atend = $row['tempo_atend'];
                                		    $id_operador = $row['id_operador'];
                                		    $desc_operador = $row['desc_operador'];
                                		    if($desc_operador=='') $desc_operador = "OPERADOR SEM NOME CADASTRADO";
        		    
                            		    echo '<tr>';
                                		    echo "<td>$callid</td>";
                                		    echo "<td>$data_hora</td>";
                                		    echo "<td>$cod_fila</td>";
                                		    echo "<td>$desc_fila</td>";
                                		    echo "<td>$tempo_espera</td>";
                                		    echo "<td>$tempo_atend</td>";
                                		    echo "<td>$id_operador</td>";
                                		    echo "<td>$desc_operador</td>";
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

