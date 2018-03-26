<!DOCTYPE html>
<html>
<head>
<title>RADAR CARTÕES - Painel de Monitoramento - Cartão de Crédito</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>
</head>
<body>
<?php 
include "conecta.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$NSH = $_GET['NSH'];
$pos_dia = $_GET['pos_dia'];
$qual_mes = $_GET['qual_mes'];
$qual_ano = $_GET['qual_ano'];
$mes = $_GET['mes'];
$ns = $_GET['ns'];
$in_filas = $_GET['in_filas'];

$contador_de_faixas = 0;
$SOMA_NSA = 0;

if($pos_dia < 10) $pos_dia_imprime = "0$pos_dia";
else $pos_dia_imprime = "$pos_dia";

echo "<div class='w3-container w3-padding w3-margin w3-center'>";

echo "<b class='w3-tiny'>Cálculo NSH: $pos_dia_imprime/$qual_mes/$qual_ano</b><br>";
echo "<b class='w3-tiny'>Tempo de Espera Padrão: $ns segundos</b><br><br>";

echo "<table class='w3-table w3-striped w3-hoverable w3-tiny w3-card-4'>";

echo "<tr class='w3-indigo'>";
echo "<td><b>FAIXA DE HORÁRIO</b></td>";
echo "<td><b>A</b></td>";
echo "<td><b>B</b></td>";
echo "<td><b>C</b></td>";
echo "<td><b>NSA = A : (B + C)</b></td>";
echo "</tr>";

//NSH
$query = $pdo->prepare("select A, B, C, x.HORA, x.MINUTO, ISNULL(cast(ISNULL(A, 0) as float) / nullif(cast(ISNULL(B, 0) as float) + cast(ISNULL(C, 0) as float),0),1) NSA from
						(
							select datepart(hh,data_hora) HORA, datepart(minute,data_hora)/30 MINUTO from tb_eventos_dac
							where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59'
							group by datepart(hh,data_hora), datepart(minute,data_hora)/30
							) as x
						left join
						(
							select datepart(hh,data_hora) HORA, datepart(minute,data_hora)/30 MINUTO, count (*) A from tb_eventos_dac
							where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59' and cod_fila in ($in_filas)
							and tempo_atend > 0 and tempo_espera <= $ns
							group by datepart(hh,data_hora), datepart(minute,data_hora)/30
						) as a on (x.HORA = a.HORA and x.MINUTO = a.MINUTO)
						left join
						(
							select datepart(hh,data_hora) HORA, datepart(minute,data_hora)/30 MINUTO, count (*) B from tb_eventos_dac
							where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59' and cod_fila in ($in_filas)
							and tempo_atend > 0
							group by datepart(hh,data_hora), datepart(minute,data_hora)/30
						) as b on (x.HORA = b.HORA and x.MINUTO = b.MINUTO)
						left join
						(
							select datepart(hh,data_hora) HORA, datepart(minute,data_hora)/30 MINUTO, count (*) C from tb_eventos_dac
							where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59' and cod_fila in ($in_filas)
							and tempo_atend = 0 and tempo_espera > $ns
							group by datepart(hh,data_hora), datepart(minute,data_hora)/30
						) as c on (x.HORA = c.HORA and x.MINUTO = c.MINUTO)
						order by HORA, MINUTO");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$contador_de_faixas++;
	
	echo "<tr>";
		$HORA = utf8_encode($row['HORA']);
		$MINUTO = utf8_encode($row['MINUTO']);
		$NSA = utf8_encode($row['NSA']);
		$A = utf8_encode($row['A']);
		if($A == NULL) $A = 0;
		$B = utf8_encode($row['B']);
		if($B == NULL) $B = 0;
		$C = utf8_encode($row['C']);
		if($C == NULL) $C = 0;
		
		if($HORA < 10) $HORA_INICIO = "0$HORA";
		else $HORA_INICIO = "$HORA";
		
		if($MINUTO == 0){
			$MINUTO_INICIO = "00";
			$MINUTO_FIM = "30";
			$HORA_FIM = $HORA_INICIO;
			
		}
		else{
			$MINUTO_INICIO = "30";
			$MINUTO_FIM = "00";
			if($HORA_INICIO == "23") $HORA_FIM = "00";
			else{
				$HORA_FIM = $HORA + 1;
				if($HORA_FIM < 10) $HORA_FIM = "0$HORA_FIM";
				else $HORA_FIM = "$HORA_FIM";
			}
		}
		
		$faixa_de_horario = "$HORA_INICIO:$MINUTO_INICIO - $HORA_FIM:$MINUTO_FIM";
		
		echo "<td>$faixa_de_horario</td>";
		echo "<td>$A</td>";
		echo "<td>$B</td>";
		echo "<td>$C</td>";
		echo "<td>$NSA</td>";
		
		$SOMA_NSA = $SOMA_NSA + $NSA;
	echo "</tr>";
}

echo "<tr class='w3-indigo'>";

$IMPRIME_NSH = $SOMA_NSA / $contador_de_faixas;
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td><b>MÉDIA SIMPLES:</b></td>";
echo "<td><b>$IMPRIME_NSH</b></td>";

echo "</tr>";
echo "</table>";
echo "</div>";

include "desconecta.php";
?>
</body>
</html>