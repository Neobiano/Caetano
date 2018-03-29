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
                "order": [[ 1, "asc" ]]
            } );
        } );
    </script>
</head>

<body>

       <?php
       $swhere = '';
       $sql = '';
       $criterios = '';
        
       if ($cb_motivos <> '')
       {           
          $criterios = 'Motivo(s): ';
          $swhere .= " and tl.cd_motivo in ($cb_motivos)";
          
          $query = $pdo->prepare("select distinct  ds_motivo from tb_log_categorizacao
                        where data_hora between (GETDATE() - 5) and (GETDATE() - 3)
                        and cd_motivo in ($cb_motivos)");
          $query->execute();
          for($i=0; $row = $query->fetch(); $i++)
          {
              $criterios .= $i==0 ? $criterios .= $row['ds_motivo'] : $criterios .= " , ".$row['ds_motivo'];                         
          }          
          
       }
       else
           $criterios .= " Motivo: Todos ";
       
       if ($cb_submotivos <> '')
       {
           $criterios .= trim($criterios)<>'' ? $criterios .= " -  SubMotivo(s): " : $criterios .= "SubMotivo(s): "; 
          
          $swhere .= " and tl.cd_submotivo in ($cb_submotivos)";
          $query = $pdo->prepare("select distinct  ds_submotivo from tb_log_categorizacao
                        where data_hora between (GETDATE() - 5) and (GETDATE() - 3)
                        and cd_submotivo in ($cb_submotivos)");
          $query->execute();
          for($i=0; $row = $query->fetch(); $i++)
          {
              $criterios .= $i==0 ? $criterios .= $row['ds_submotivo'] : $criterios .= " , ".$row['ds_submotivo'];  
          }          
        }
        else 
            $criterios .= trim($criterios)<>'' ? $criterios .= " -  SubMotivo(s): " : $criterios .= "SubMotivo(s): "; 
                      
        
        $nome_relatorio = "Pesquisa de Satisfação - Monitoramento de Respostas"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Pesquisa de Satisfação - Monitoramento de Respostas "; // MESMO NOME DO INDEX
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
                                echo '<td><b>Resposta</b></td>';
                                echo '<td><b>No Geral, qual seu grau de satisfação</b></td>';
                                echo '<td><b>Quanto ao tempo de espera, você se considera</b></td>';
                                echo '<td><b>Quanto à cordialidade do atendente, você se considera</b></td>';
                                echo '<td><b>A solicitação foi atendida ao final do atendimento?</b></td>';                               
                        echo '</tr>
                          </thead>
                            <tbody>';
                                
                                for($i=1; $i < 4; $i++)
                                {
                                    
                                    switch ($i) {
                                        case 1: $resposta = 'Satisfeito';
                                        break;
                                        
                                        case 2: $resposta = 'Indiferente';                                           
                                        break;
                                            
                                        case 3: $resposta = 'Insatisfeito';                                                                                        
                                        break;
                                        
                                    }
                                    
                                    //pergunta 1
                                    $sql ="	select  count(distinct t.callid) qtde
                                            from tb_pesq_satisfacao t
                                            inner join tb_log_categorizacao tl on (tl.callid = t.callid)
                                            where t.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                            and tl.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                            and t.perg1 = '$i'
                                            $swhere
                                           ";
                                    echo($sql);                                            
                                    $query = $pdo->prepare($sql);
                                    $query->execute();
                                    for($x=0; $row = $query->fetch(); $x++){
                                        $perg1 = $row['qtde'];
                                    }
                                   
                                    //pergunta 2
                                    $sql ="	select  count(distinct t.callid) qtde
                                            from tb_pesq_satisfacao t
                                            inner join tb_log_categorizacao tl on (tl.callid = t.callid)
                                            where t.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                            and tl.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                            and t.perg2 = '$i'
                                            $swhere
                                           ";
                                    $query = $pdo->prepare($sql);
                                    $query->execute();
                                    for($x=0; $row = $query->fetch(); $x++){
                                        $perg2 = $row['qtde'];
                                    }
                                    
                                    //pergunta 3
                                    $sql ="	select  count(distinct t.callid) qtde
                                            from tb_pesq_satisfacao t
                                            inner join tb_log_categorizacao tl on (tl.callid = t.callid)
                                            where t.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                            and tl.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                            and t.perg3 = '$i'
                                            $swhere
                                           ";
                                    $query = $pdo->prepare($sql);
                                    $query->execute();
                                    for($x=0; $row = $query->fetch(); $x++){
                                        $perg3 = $row['qtde'];
                                    }
                                    
                                    //pergunta 4
                                    $sql ="	select  count(distinct t.callid) qtde
                                            from tb_pesq_satisfacao t
                                            inner join tb_log_categorizacao tl on (tl.callid = t.callid)
                                            where t.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                            and tl.data_hora between '$data_inicial1' and '$data_final1 23:59:59.999'
                                            and t.perg4 = '$i'
                                            $swhere
                                           ";
                                    $query = $pdo->prepare($sql);
                                    $query->execute();
                                    for($x=0; $row = $query->fetch(); $x++){
                                        $perg4 = $row['qtde'];
                                    }
                                   
                                    
                                    //imprimindo resultados
                                    echo '<tr>';
                                    echo "<td>$resposta</td>";
                                    echo "<td>$perg1</td>";
                                    echo "<td>$perg2</td>";
                                    echo "<td>$perg3</td>";
                                    echo "<td>$perg4</td>";
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

