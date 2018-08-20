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
 
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$TOTAL_SEM_TRANSFERENCIA = 0;
$TOTAL_COM_TRANSFERENCIA = 0;
$PERCENTUAL_TOTAL = 0;
$TOTAL_TRANSFERENCIAS_PERIODO = 0;

$nome_relatorio = "transferencias_para_mesma_fila";
$titulo = "Transferências para Mesma Fila";
$nao_gerar_excel = 1;

//
echo '<div class="w3-margin w3-tiny w3-center">';
echo '<div id="divtitulo" class="w3-margin w3-tiny w3-center">';
echo "<b>$titulo</b>";
echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto ";
echo "<br>";
echo "</div>";

        $sql = "    set nocount on;   EXEC sp_CERATFO_radar_cartoes_query21 '$data_inicial 00:00:00','$data_final 23:59:59.999' ";                         

        //echo $sql;
        $query = $pdo->prepare($sql);
        $query->execute(); // EXECUTA A CONSULTA
                      
        $callid_anterior = '';        
        $shead = '';
        $srow = '';
        $coluna = 1;

        //echo '<div class="w3-border" style="padding:16px 16px;">';
        echo '<table id = "tabela"  class="w3-table w3-responsive w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
        
        for($i=0; $row = $query->fetch(); $i++){
           
           //criando o cabeçalho dinamico de acordo com o maximo de colunas encontradas na consulta
            $max_colunas = utf8_encode($row['max_colunas']);            
            if ($shead == '')
            {
                $shead = "<thead><tr class='w3-indigo'>";
                $shead = " $shead <td><b>CALLID</b></td>";
                $shead = " $shead <td><b>DATA/HORA</b></td>";
                for($j=1;  ($j <= $max_colunas); $j++){
                    if ($j==1)                    
                      $shead = " $shead <td><b>FILA_ORIGEM</b></td>";                    
                    else
                      $shead = " $shead <td><b>$j ª TRANSF.</b></td>";
                    
                    $shead = " $shead <td><b>OPERADOR</b></td>";                    
                }
                $shead =" $shead </tr></thead><tbody>";
                echo $shead;
            }
        	
        	$data_hora = utf8_encode($row['data_hora']);
        	$callid = utf8_encode($row['callid']);
        	$cod_fila = utf8_encode($row['cod_fila']);        	
        	$desc_operador = utf8_encode($row['desc_operador']);
        	$desc_fila = utf8_encode($row['desc_fila']);
        	$cod_fila = number_format($cod_fila, 0, ',', '.');
        	
        	//se trocou o callid, entao imprime a linha anterior e inicia uma nova
        	if($callid != $callid_anterior)
        	{        	           	    
        	    $callid_anterior = $callid;        	           	   
        	    if ($srow <> '')
        	    {  
        	        //apenas para preencher o tamanho restante das colunas sem texto        	       
    	            for($j=$coluna;($j < $max_colunas); $j++){
    	                $srow = "$srow <td></td> <td></td>";
    	            }    
    	            
    	            echo "<tr>$srow</tr>";
        	    }
        	            	    
        	    $coluna = 1;
        	    $srow = "<td>$callid</td>";
        	    $srow = "$srow <td>$data_hora</td>";
        	    $srow = "$srow <td>$desc_fila</td>";
        	    $srow = "$srow <td>$desc_operador</td>";        	    
        	}
        	else 
        	{        	    
        	    $srow = "$srow <td>$desc_fila</td>";
        	    $srow = "$srow <td>$desc_operador</td>";
        	    $coluna++;
        	}
        	
        }        
        for($j=$coluna;($j < $max_colunas); $j++){
            $srow = "$srow <td></td> <td></td>";
        }
       
        echo "<tr>$srow</tr>";
        
        echo "</tbody>          
        </table>";
            	echo "</div>";
            	//echo "</div>";
?>
</body>
</html>

