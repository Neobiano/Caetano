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
    
    $pData = $_GET['pData'];    
    $pBandeira = $_GET['pBandeira'];
    $pGrupo = $_GET['pGrupo'];
    
   
  
echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Rastreio de Atendimentos - Retenção</b>";
echo "<br><br><b><i>Data de Consulta:</i> $pData</b> ";
echo "<br><br><b><i>Filtros: </i></b> $pGrupo e Bandeira $pBandeira";
echo "<br><br>";

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>CALLID</b></td>';
echo '<td><b>DATA/HORA</b></td>';
echo '<td><b>ID OPERADOR</b></td>';
echo '<td><b>NOME OPERADOR</b></td>';
echo '<td><b>BANDEIRA</b></td>';
echo '<td><b>VARIANTE</b></td>';
echo '<td><b>PAN</b></td>';
echo '<td><b>TIPO RETENCAO</b></td>';

echo '</tr></thead><tbody>';

    if ($pGrupo == 'NaoRetido')     
       $swhere = " and tdr.tipo_retencao = 'CARTÃO NÃO RETIDO'";
    else if ($pGrupo == 'Retido')
       $swhere = " and tdr.tipo_retencao <> 'CARTÃO NÃO RETIDO'";
    else if ($pGrupo == 'RetidoDesc')
       $swhere = " and tdr.tipo_retencao = 'DESCONTO DE ANUIDADE' ";
    else if ($pGrupo == 'RetidoArg')
       $swhere = " and tdr.tipo_retencao = 'ARGUMENTAÇÃO' ";
    
    $sql ="	select tdr.*, coalesce(tci.nome,'OPERADOR SEM NOME CADASTRADO') operador from tb_dados_retencao tdr 
            left join tb_colaboradores_indra tci on (tci.matricula = tdr.id_operador)
            where cast(tdr.data_hora as date) = '$pData' 					
			and tdr.bandeira = '$pBandeira'
            $swhere					
			order by tdr.callid, tdr.data_hora
		";
    
             

   

//echo $sql;			
$query = $pdo->prepare($sql);
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$callid = $row['callid'];
	$data_hora = $row['data_hora'];
	$id_operador = $row['id_operador'];
	$operador = $row['operador'];
	$bandeira = $row['bandeira'];
	$variante = $row['variante'];
	$pan = $row['pan'];
	$tipo_retencao = $row['tipo_retencao'];		
	
	echo '<tr>';
		echo "<td>$callid</td>";
		echo "<td>$data_hora</td>";
		echo "<td>$id_operador</td>";
		echo "<td>$operador</td>";
		echo "<td>$bandeira</td>";
		echo "<td>$variante</td>";
		echo "<td>$pan</td>";
		echo "<td>$tipo_retencao</td>";
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