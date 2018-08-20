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
    $pergunta = $_GET['pPergunta'];
    $idpos = $_GET['pIDPOS'];
    
    $sisindisponivel=$_GET['pSisindisponivel'];
    $ligindevida = $_GET['pLigindevida'];
    $ligimprodutiva= $_GET['pLigimprodutiva'];
    $shortCall = $_GET['pShortCall'];
    
  
    $sFiltro = '';
    if (trim($operador) <> '0')
        $sFiltro = " Operador: $operador, ";
        
    if (trim($fila) <> '0')    
        $sFiltro = ("$sFiltro  Fila: $fila, ");
        
    if (trim($pergunta) <> '')
        $sFiltro = ("$sFiltro  Pergunta: $pergunta, ");
            
    if (trim($idpos) <> '')
        $sFiltro = ("$sFiltro  At. Falha de IDPos: $idpos, ");
    
    if (trim($sisindisponivel) <> '')
        $sFiltro = ("$sFiltro  Sis. Indisponível: $sisindisponivel, ");

    if (trim($ligindevida) <> '')
        $sFiltro = ("$sFiltro  Lig. Indevida: $ligindevida, ");

    if (trim($ligimprodutiva) <> '')
        $sFiltro = ("$sFiltro  Lig. Improdutiva: $ligimprodutiva, ");
    
    if ($shortCall > 0)
        $sFiltro = ("$sFiltro  ShortCall: $shortCall ");
                
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
    			
    $sql ="set nocount on EXEC sp_CERATFO_radar_cartoes_query31b '$data1 00:00:00','$data2 23:59:59.999',$operador,$fila,$pergunta,$idpos,$valor_dado,$sisindisponivel,$ligindevida,$ligimprodutiva,$shortCall";
    	       	    
    echo '<div class="w3-margin w3-tiny w3-center">';
    echo "<b>Rastreio de Atendimentos - Campanha Cordialidade do Operador</b>";
    echo "<br><br><b><i>Data:</i></b> $data_inicial_texto à $data_final_texto";
    echo "<br><b><i>Filtros:</i></b> $sFiltro ";       
    echo "<br><br><b><i>Listando:</i> $valor_dado</b> ";
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