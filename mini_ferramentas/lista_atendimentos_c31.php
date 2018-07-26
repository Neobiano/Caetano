<!DOCTYPE html>
<html>
<head>
<title>RADAR CARTÕES - Painel de Monitoramento - Cartão de Crédito</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabela').DataTable();
} );
</script>

</head>
<body>
<?php 
    include "conecta.php";
     
    set_time_limit(9999);
    ini_set('max_execution_time', 9999);
        
    $valor_dado = $_GET['pGrupo'];
    $data1 = $_GET['pData1'];
    $data2 = $_GET['pData2'];
    $operador = $_GET['pOperador'];
    $fila = $_GET['pFila'];
    $idpos = $_GET['pIDPOS'];
  
    $t_inicial = strtotime($data1);
    $data_inicial_texto = date('d/m/Y',$t_inicial);
    
    $t_final = strtotime($data2);
    $data_final_texto = date('d/m/Y',$t_final);
    
    $cabecalho = '<td><b>CALLID</b></td>
                  <td><b>DATA/HORA</b></td>
                  <td><b>TEMPO DE ESPERA</b></td>
                  <td><b>TEMPO DE ATENDIMENTO</b></td>
                  <td><b>ID OPERADOR</b></td>
                  <td><b>NOME OPERADOR</b></td>
                  <td><b>FILA</b></td>
                  <td><b>DESC_FILA</b></td>';
   
                  
    $valor_dado = trim($valor_dado);
    			
    $sql ="	 set nocount on; 

             declare @T TABLE(  callid varchar(100),
                                data_hora datetime,                                        
                                tempo_espera int,                                        
                                tempo_atend int,
                                id_operador int,
                                desc_operador varchar(100),
                                cod_fila int,
                                desc_fila varchar(100)
                            ); 
             insert @T EXEC sp_CERATFO_radar_cartoes_query31b '$data1 00:00:00','$data2 23:59:59.999',$operador,$fila,$idpos,$valor_dado                      
                                                                            
             select * from @T 
		";
    	    
    	    

    echo '<div class="w3-margin w3-tiny w3-center">';
    echo "<b>Rastreio de Atendimentos - Campanha Cordialidade do Operador</b>";
    echo "<br><br><b><i>Data:</i></b> $data_inicial_texto à $data_final_texto";
    echo "<br><br><b><i>Listando:</i></b> $valor_dado";
    echo "<br><br>";
    
    echo '<div class="w3-border" style="padding:16px 16px;">';
    echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
    echo '<thead><tr class="w3-indigo w3-tiny">';
    echo $cabecalho;
    echo '</tr></thead><tbody>';

        //echo $sql;			
        $query = $pdo->prepare($sql);
        $query->execute();
        for($i=0; $row = $query->fetch(); $i++)
        {
        	$callid = $row['callid'];
        	$data_hora = $row['data_hora'];        	
        	$tempo_espera = $row['tempo_espera'];
        	$tempo_atend = $row['tempo_atend'];
        	$id_operador = $row['id_operador'];
        	$desc_operador = $row['desc_operador'];	
        	if($desc_operador=='') $desc_operador = "OPERADOR SEM NOME CADASTRADO";
        	
        	$cod_fila = $row['cod_fila'];
        	$desc_fila = $row['desc_fila'];
        	        	        	    
        	echo '<tr>';
        		echo "<td>$callid</td>";
        		echo "<td>$data_hora</td>";        		
        		echo "<td>$tempo_espera</td>";
        		echo "<td>$tempo_atend</td>";
        		echo "<td>$id_operador</td>";
        		echo "<td>$desc_operador</td>";
        		echo "<td>$cod_fila</td>";
        		echo "<td>$desc_fila</td>";
        	echo '</tr>';
        }
echo "</tbody></table>";
echo "</div>";
echo "</div>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>