<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabela').DataTable( {
        "order": [[ 1, "asc" ]]
    } );
} );
</script>

</head>
<body>

<div id="div_loading" class="w3-modal">
<div class="w3-modal-content" style="width:100%;height:100%;position:absolute;top:0;right:0;padding:0;margin:0;">
  <div class="w3-container w3-center w3-margin w3-padding-64">
	<img src="loading.gif" style="width:100px;">
	<p class="w3-text-red w3-center">Carregando...</p>
  </div>
</div>
</div>
<script>
	document.getElementById('div_loading').style.display='block';
</script>

<?php 
include "conecta.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$data_inicial = $_GET['data_inicial'];
$data_final = $_GET['data_final'];
$DADO = $_GET['DADO'];

echo "<title>Log REGISTROS - $DADO</title>";

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);
$t_final = strtotime($data_final);
$data_final_pre = date('m/d/Y',$t_final);
$data_final_texto = date('d/m/Y', strtotime("-1 day", strtotime($data_final_pre)));
//Conversão Data Texto - Fim

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Log - REGISTROS</b><br><br>";
echo "<b>Telefone:</b> $DADO<br>";
echo "<b>Data Inicial:</b> $data_inicial_texto<br>";
echo "<b>Data Final:</b> $data_final_texto</b><br><br>";

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id="tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>CALLID</b></td>';
echo '<td><b>DATA_HORA</b></td>';
echo '<td><b>TIPO</b></td>';
echo '<td><b>VALOR</b></td>';
echo '<td><b>ORIGEM</b></td>';
echo '</tr></thead><tbody>';

$query = $pdo->prepare("select distinct a.callid, a.data_hora, c.desc_dado TIPO, a.valor_dado VALOR, d.desc_fonte ORIGEM
						from tb_dados_cadastrais as a
						inner join(select callid from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and cod_dado = 3 and valor_dado = '$DADO') as b
						on a.callid = b.callid
						inner join tb_tipo_dados as c on a.cod_dado = c.cod_dado
						inner join tb_fonte as d on a.cod_fonte = d.cod_fonte
						where a.data_hora between '$data_inicial' and '$data_final' and a.valor_dado <> ''
						order by a.data_hora");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	
	//if ($i > 0) $callid_anterior = $callid;
	
	$callid = utf8_encode($row['callid']);
	$data_hora = utf8_encode($row['data_hora']);
	$TIPO = utf8_encode($row['TIPO']);
	$VALOR = utf8_encode($row['VALOR']);
	$ORIGEM = utf8_encode($row['ORIGEM']);
		if ($ORIGEM == "FRONTEND-CAT") $ORIGEM = "FRONTEND";
	
	
	//if (($i > 0) && ($callid_anterior != $callid)) echo "<tr class = 'w3-topbartable'>";
	//else echo '<tr>';
	echo '<tr>';
		
	echo "<td>$callid</td>";	
	echo "<td>$data_hora</td>";
	echo "<td>$TIPO</td>";	
	echo "<td>$VALOR</td>";
	echo "<td>$ORIGEM</td>";
	echo '</tr>';	
}

echo "</tbody></table>";
echo "</div>";
echo "</div>";

include "desconecta.php";
?>

<script>
	$("#div_loading").fadeOut('slow');
</script>

</body>
</html>