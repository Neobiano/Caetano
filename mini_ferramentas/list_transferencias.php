<!DOCTYPE html>
<html>
<head>
<title>CAIXA - MINI FERRAMENTAS - Contrato INDRA Maracanaú</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>
</head>
<body>
<?php 
include "conecta.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$data_inicial = $_GET['data_inicial'];
$data_final = $_GET['data_final'];
$CALLID = $_GET['CALLID'];

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);

$t_final = strtotime($data_final);
$data_final_texto = date('d/m/Y',$t_final);
//Conversão Data Texto - Fim

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Rastreio de Atendimentos</b>";
echo "<br><br><b><i>CALLID:</i></b> $CALLID<br>";
echo "<b><i>Data Inicial:</i></b> $data_inicial_texto<br>";
echo "<b><i>Data Final:</i></b> $data_final_texto<br><br>";

echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<tr class="w3-indigo w3-tiny">';
echo '<td><b>DATA/HORA</b></td>';
echo '<td><b>CÓDIGO FILA</b></td>';
echo '<td><b>DESCRIÇÃO FILA</b></td>';
echo '<td><b>TEMPO DE ESPERA</b></td>';
echo '<td><b>TEMPO DE ATENDIMENTO</b></td>';
echo '<td><b>ID OPERADOR</b></td>';
echo '<td><b>NOME OPERADOR</b></td>';
echo '</tr>';

$query = $pdo->prepare("select data_hora, b.cod_fila, b.desc_fila, tempo_espera, tempo_atend, id_operador, desc_operador
						from tb_eventos_DAC as a
						inner join tb_filas as b
						on a.cod_fila = b.cod_fila
						where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and callid = '$CALLID'
						order by data_hora");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$cod_fila = $row['cod_fila'];
		$cod_fila = number_format($cod_fila, 0, ',', '.');
	$data_hora = $row['data_hora'];
	$desc_fila = $row['desc_fila'];
	$tempo_espera = $row['tempo_espera'];
	$tempo_atend = $row['tempo_atend'];
	$id_operador = $row['id_operador'];
	$desc_operador = $row['desc_operador'];	
	
	echo '<tr>';
		echo "<td>$data_hora</td>";
		echo "<td>$cod_fila</td>";
		echo "<td>$desc_fila</td>";
		echo "<td>$tempo_espera</td>";
		echo "<td>$tempo_atend</td>";
		echo "<td>$id_operador</td>";
		echo "<td>$desc_operador</td>";
	echo '</tr>';
}
echo "</div>";
echo "</table>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>