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
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
</head>

<body>

       <?php
        $inicio = defineTime();
        $total_respondida = 0;
        $total_satisfeito = 0;
        $total_indiferente = 0;
        $total_insatisfeito = 0;
        //teste vamos ve, alterei e tal... papapa
        
        $sFiltro = '';
        if (trim($pesq_operador_31) <> '0')
            $sFiltro = " Operador: $pesq_operador_31, ";
        
       if (trim($pesq_fila_31) <> '0')
            $sFiltro = ("$sFiltro  Fila: $pesq_fila_31, ");

       if (trim($rd_pergunta_31) <> '')
           $sFiltro = ("$sFiltro  Pergunta: $rd_pergunta_31, ");
                  
       if (trim($rd_falhaidpos_31) <> '')
           $sFiltro = ("$sFiltro  At. Falha de IDPos: $rd_falhaidpos_31, ");
                  
       if (trim($rd_sisindisponivel_31) <> '')
         $sFiltro = ("$sFiltro  Sis. Indisponível: $rd_sisindisponivel_31, ");
       
       if (trim($rd_ligindevida_31) <> '')
         $sFiltro = ("$sFiltro  Lig. Indevida: $rd_ligindevida_31, ");
         
       if (trim($rd_ligimprodutiva_31) <> '')
         $sFiltro = ("$sFiltro  Lig. Improdutiva: $rd_ligimprodutiva_31, ");
     
       if ($pesq_shortcall_31 > 0)
         $sFiltro = ("$sFiltro  ShortCall: $pesq_shortcall_31 ");
                        
                       
        switch ($select_tipo_31) 
        {
            case 0:
                $sFiltro = ("$sFiltro  Agrupado: Por Operador/Fila");                
                                
                $shead =' <td><b>Operador</b></td> 
                          <td><b>Fila</b></td>                           
                          <td><b>Respondidas</b></td> 
                          <td><b>Satisfeitos</b></td> 
                          <td><b>(%)</b></td> 
                          <td><b>Indiferentes</b></td>
                          <td><b>(%)</b></td> 
                          <td><b>Insatisfeitos</b></td> 
                          <td><b>(%)</b></td> ';
                                                              
                break;
            case 1:
                $sFiltro = ("$sFiltro  Agrupado: Por Operador");                
            
                $shead =' <td><b>Operador</b></td> <td><b>Respondidas</b></td> <td><b>Satisfeitos</b></td> <td><b>(%)</b></td> <td><b>Indiferentes</b></td> <td><b>(%)</b></td> <td><b>Insatisfeitos</b></td> <td><b>(%)</b></td> ';                      
                
                break;
            case 2:
                $sFiltro = ("$sFiltro  Agrupado: Por Fila");                               
                $shead =' <td><b>Fila</b></td> <td><b>Respondidas</b></td> <td><b>Satisfeitos</b></td> <td><b>(%)</b></td> <td><b>Indiferentes</b></td> <td><b>(%)</b></td> <td><b>Insatisfeitos</b></td> <td><b>(%)</b></td> ';              
                
                break;            
        }

        $sql = '';                                                   
        $nome_relatorio = "campanha_pesq_satisfacao"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Campanha Pesquisa de Satisfação "; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
                	                                                  
        echo '<div class="w3-margin w3-tiny w3-center">'; 
        echo '<div id="divtitulo" class="w3-margin w3-tiny w3-center">';
            echo "<b>$titulo</b>";
            echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto ";
            echo "<br><b><i>Filtros:</i></b> $sFiltro ";            
            echo "<br>";
        echo "</div>";            
        
            echo '<div class="w3-border" style="padding:16px 16px;">';
                    
                echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
                
                    echo '<thead>
                                <tr class="w3-indigo w3-tiny"> ';
                                   echo $shead;                                                                
                   echo '       </tr>
                          </thead>
                          <tbody>';                                                                                                                                                  
                                                                
                                $dadosgrafico1 = '';
                                $dadosgrafico2 = '';                                
                                $sql = " set nocount on; EXEC sp_CERATFO_radar_cartoes_query31w '$data_inicial_u 00:00:00','$data_final_u 23:59:59.999',$pesq_operador_31,$pesq_fila_31,$rd_pergunta_31,$select_tipo_31,$rd_falhaidpos_31,$rd_sisindisponivel_31,$rd_ligindevida_31,$rd_ligimprodutiva_31,$pesq_shortcall_31";
                                echo $sql;
                                
                                $query = $pdo->prepare($sql);
                                $query->execute();                                
                                for($i=0; $row = $query->fetch(); $i++)
                                {
                                    echo '<tr>';
                                    $qtde_respondida = utf8_encode($row['qtde_respondido']);
                                    $satisfeito = utf8_encode($row['satisfeito']);
                                    $pct_satisfeito = utf8_encode($row['pct_satisfeito']);
                                    $pct_satisfeito = number_format($pct_satisfeito, 2, ',', '.');
                                    
                                    $indiferente = utf8_encode($row['indiferente']);
                                    $pct_indiferente = utf8_encode($row['pct_indiferente']);
                                    $pct_indiferente = number_format($pct_indiferente, 2, ',', '.');
                                    
                                    $insatisfeito = utf8_encode($row['insatisfeito']);
                                    $pct_insatisfeito = utf8_encode($row['pct_insatisfeito']);
                                    $pct_insatisfeito = number_format($pct_insatisfeito, 2, ',', '.');
                                    
                                    switch ($select_tipo_31) 
                                    {
                                        
                                        case 0:
                                            $cod_operador = utf8_encode($row['cod_operador']);
                                            $desc_operador = utf8_encode($row['desc_operador']);
                                            $cod_fila = utf8_encode($row['cod_fila']);
                                            $desc_fila = utf8_encode($row['desc_fila']);
                                            echo "<td>$desc_operador ($cod_operador)</td>";
                                            echo "<td>$desc_fila ($cod_fila)</td>";
                                            
                                            $texto_respondidas =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=RESPONDIDAS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$qtde_respondida</a></b></td>";
                                            $texto_satisfeitos =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=SATISFEITOS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$satisfeito</a></b></td>";
                                            $texto_indiferentes =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=INDIFERENTES&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$indiferente</a></b></td>";
                                            $texto_insatisfeitos =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=INSATISFEITOS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$insatisfeito</a></b></td>";
                                           
                                            break;
                                        case 1:
                                            if ($pesq_fila_31 > 0)
                                              $cod_fila = $pesq_fila_31;
                                            else 
                                               $cod_fila = 0;
                                            
                                            $cod_operador = utf8_encode($row['cod_operador']);
                                            $desc_operador = utf8_encode($row['desc_operador']);
                                            
                                            echo "<td>$desc_operador ($cod_operador)</td>";
                                            
                                            $texto_respondidas =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=RESPONDIDAS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$qtde_respondida</a></b></td>";
                                            $texto_satisfeitos =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=SATISFEITOS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$satisfeito</a></b></td>";
                                            $texto_indiferentes =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=INDIFERENTES&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$indiferente</a></b></td>";
                                            $texto_insatisfeitos =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=INSATISFEITOS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$insatisfeito</a></b></td>";
                                            break;
                                        case 2:
                                            if ($pesq_operador_31 > 0)
                                               $cod_operador = $pesq_operador_31;
                                             else
                                               $cod_operador = 0;
                                             
                                            $cod_fila = utf8_encode($row['cod_fila']);
                                            $desc_fila = utf8_encode($row['desc_fila']);
                                            
                                            echo "<td>$cod_fila ($desc_fila)</td>";
                                            
                                            $texto_respondidas =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=RESPONDIDAS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$qtde_respondida</a></b></td>";
                                            $texto_satisfeitos =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=SATISFEITOS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$satisfeito</a></b></td>";
                                            $texto_indiferentes =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=INDIFERENTES&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$indiferente</a></b></td>";
                                            $texto_insatisfeitos =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c31.php?pGrupo=INSATISFEITOS&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pIDPOS=$rd_falhaidpos_31&pPergunta=$rd_pergunta_31&pSisindisponivel=$rd_sisindisponivel_31&pLigindevida=$rd_ligindevida_31&pLigimprodutiva=$rd_ligimprodutiva_31&pShortCall=$pesq_shortcall_31\" target=\"_blank\">$insatisfeito</a></b></td>";
                                            break;
                                    }
                                    
                                                                                                                                              
                                    $total_respondida = $total_respondida + $qtde_respondida;
                                    $total_satisfeito = $total_satisfeito + $satisfeito;
                                    $total_indiferente = $total_indiferente + $indiferente;
                                    $total_insatisfeito = $total_insatisfeito + $insatisfeito;
                                    
                                     
                                    /*dados do grafico
                                    $d_dia = $row['d_dia'];
                                    $d_mes = $row['d_mes'];
                                    $d_ano = $row['d_ano'];                                   
                                    $dadosgrafico1 = $dadosgrafico1."[new Date($d_ano,$d_mes,$d_dia),$recebidas, $recebidas_categorizadas, $categorizadas_campanha],";
                                    */                                    
                                    //imprimindo resultados                                                                        
                                    if ($qtde_respondida > 0)                                         //
                                       echo $texto_respondidas;
                                    else 
                                       echo "<td>$qtde_respondida</td>";                                    
                                        
                                    if ($satisfeito> 0)
                                        echo $texto_satisfeitos;
                                    else
                                        echo "<td>$satisfeito</td>";
                                    
                                    echo "<td><b>$pct_satisfeito%</b></td>";
                                    
                                    if ($indiferente> 0)
                                        echo $texto_indiferentes;
                                    else 
                                        echo "<td>$indiferente</td>";
                                    
                                    echo "<td><b>$pct_indiferente%</b></td>";
                                    
                                    if ($insatisfeito> 0)
                                        echo $texto_insatisfeitos;
                                    else
                                        echo "<td>$insatisfeito</td>";                                                                       
                                    
                                    echo "<td><b>$pct_insatisfeito%</b></td>";
                                       
                                    echo '</tr>';
                                } // final FOR
                                
                                 if ($total_respondida > 0)
                                 {
                                     $pct_total_satisfeito = $total_satisfeito / $total_respondida *100;
                                     $pct_total_indiferente = $total_indiferente / $total_respondida *100;
                                     $pct_total_insatisfeito = $total_insatisfeito / $total_respondida *100;
                                 }
                                 
                                 $pct_total_satisfeito =  number_format($pct_total_satisfeito, 2, ',', '.');                                  
                                 $pct_total_indiferente =  number_format($pct_total_indiferente, 2, ',', '.');                                 
                                 $pct_total_insatisfeito =  number_format($pct_total_insatisfeito, 2, ',', '.');
                                 
                                  $sfooter =  " <td><b>TOTAL</b></td>
                                                  <td><b>$total_respondida</b></td>
                                                  <td><b>$total_satisfeito</b></td>
                                                  <td><b>$pct_total_satisfeito%</b></td>
                                                  <td><b>$total_indiferente</b></td>
                                                  <td><b>$pct_total_indiferente%</b></td>
                                                  <td><b>$total_insatisfeito</b></td>
                                                  <td><b>$pct_total_insatisfeito%</b></td>";
                                  
                                  if ($select_tipo_31 == 0)
                                  {
                                      
                                      $sfooter =  " <td><b>TOTAL</b></td>
                                                      <td></td>
                                                      <td><b>$total_respondida</b></td>
                                                      <td><b>$total_satisfeito</b></td>
                                                      <td><b>$pct_total_satisfeito%</b></td>
                                                      <td><b>$total_indiferente</b></td>
                                                      <td><b>$pct_total_indiferente%</b></td>
                                                      <td><b>$total_insatisfeito</b></td>
                                                      <td><b>$pct_total_insatisfeito%</b></td>";
                                  }                      
                  
                       echo "</tbody>
                       <tr class='w3-indigo'>                                                                          	                        	                        
                        	$sfooter                                                                               
                        </tr> 
                    </table>";
		     echo "</div>";
		echo "</div>";
		
		
		
		$fim = defineTime();
		echo tempoDecorrido($inicio,$fim);
		include "desconecta.php";
		
?>

<script>  
    document.getElementById("divtitulo").appendChild(document.getElementById("tmp"));    
</script>
</body>
</html>

