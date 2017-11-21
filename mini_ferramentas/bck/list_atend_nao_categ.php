<!DOCTYPE html>
<html>
<head>
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
$id_operador = $_GET['id_operador'];

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);
$t_inicial = strtotime($data_final);
$data_final_texto = date('d/m/Y',$t_inicial);
//Conversão Data Texto - Fim

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>ATENDIMENTOS SEM CATEGORIZAÇÃO</b><br><br>";
echo "<b>ID do Operador:</b> $id_operador<br>";
echo "<b>Data Inicial:</b> $data_inicial_texto<br>";
echo "<b>Data Final:</b> $data_final_texto</b><br><br>";

echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<tr class="w3-indigo w3-tiny">';
echo '<td><b>CALLID</b></td>';
echo '<td><b>DATA/HORA</b></td>';
echo '<td><b>CÓDIGO DA FILA</b></td>';
echo '<td><b>TEMPO DE ESPERA</b></td>';
echo '<td><b>TEMPO DE ATENDIMENTO</b></td>';
echo '<td><b>ID DO OPERADOR</b></td>';
echo '<td><b>NOME DO OPERADOR</b></td>';

echo '</tr>';

$query = $pdo->prepare("select X.callid, X.data_hora, X.cod_fila, X.tempo_espera, X.tempo_atend, X.id_operador, x.desc_operador
						from (select a.*, b.NOME
						from tb_eventos_DAC as a
						inner join tb_colaboradores_indra as b
						on a.id_operador = b.LOGIN_DAC
						where a.data_hora between '$data_inicial' and '$data_final' and a.tempo_atend > 15) as X
						left join (select a.callid, b.login_dac, b.NOME
						from tb_log_categorizacao as a
						inner join tb_colaboradores_indra as b
						on a.login_front = b.MATRICULA
						where a.data_hora between '$data_inicial' and '$data_final') as Y
						on (X.callid = Y.callid) and (X.id_operador = Y.login_dac)
						where (Y.callid is null) and (Y.login_dac is null) and X.id_operador = '$id_operador'
						order by X.data_hora");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$callid = $row['callid'];
	$data_hora = $row['data_hora'];
	$cod_fila = $row['cod_fila'];
	$cod_fila = number_format($cod_fila, 0, ',', '.');	
	$tempo_espera = $row['tempo_espera'];
	$tempo_atend = $row['tempo_atend'];
	$idd_operador = $row['id_operador'];
	$descc_operador = $row['desc_operador'];
	
	echo '<tr>';
	echo "<td>$callid</td>";	
	echo "<td>$data_hora</td>";
	echo "<td>$cod_fila</td>";
	echo "<td>$tempo_espera</td>";
	echo "<td>$tempo_atend</td>";
	echo "<td>$idd_operador</td>";
	echo "<td>$descc_operador</td>";		
	echo '</tr>';
	
	
}

echo "</div>";
echo "</table>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>