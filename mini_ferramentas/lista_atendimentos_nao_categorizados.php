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
$NOME = $_GET['NOME'];
$txt_dias_semana = $_GET['txt_dias_semana'];
$in_semana = $_GET['in_semana'];

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);

$t_final= strtotime($data_final);
$data_final_texto = date('d/m/Y',$t_final);
//Conversão Data Texto - Fim

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Rastreio de Atendimentos não Categorizados Durante o Atendimento</b>";
echo "<br><br><b>Operador: $NOME</b>";
echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto";
echo "<br><br><b><i>Dias da Semana Selecionados:</i></b> $txt_dias_semana";
echo "<br><br><b style='color: red'>Observação:</b> Devido problemas com o registro na base de dados pela <i>INDRA</i>, os valores exibidos na consulta podem divergir da realidade.";
echo "<br><br>";

echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<tr class="w3-indigo w3-tiny">';
echo '<td><b>CALLID</b></td>';
echo '<td><b>DATA/HORA</b></td>';
echo '<td><b>CÓDIGO FILA</b></td>';
echo '<td><b>DESCRIÇÃO FILA</b></td>';
echo '<td><b>TEMPO DE ESPERA</b></td>';
echo '<td><b>TEMPO DE ATENDIMENTO</b></td>';
echo '<td><b>ID OPERADOR</b></td>';
echo '<td><b>NOME OPERADOR</b></td>';
echo '</tr>';

$query = $pdo->prepare("select a.*, c.MATRICULA, d.desc_fila from
						(
						select * from tb_eventos_DAC
						where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and desc_operador = '$NOME' and datepart(dw,data_hora) in $in_semana
						) as a
						left join
						(
						select * from tb_log_categorizacao
						where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana
						) as b
						on a.callid = b.callid
						inner join tb_colaboradores_indra as c on a.id_operador = c.LOGIN_DAC
						left join tb_filas as d on a.cod_fila = d.cod_fila
						where b.callid is null");
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
echo "</div>";
echo "</table>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>