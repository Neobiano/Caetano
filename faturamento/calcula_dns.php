<?php
// CONECTA BANCO DE DADOS
include "conecta.php";

// TEMPO LIMITE CONSULTAS SQL
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$qual_mes = $_POST['qual_mes'];
$qual_ano = $_POST['qual_ano'];

$a_mes = 0;
$b_mes = 0;
$c_mes = 0;
$soma_nsa = 0;

for ($contador=1; $contador<49; $contador++){
	if ($contador == 1){
		$periodo_inicial = '00:00:00.000';
		$periodo_final = '00:29:59.999';
	}

	if ($contador == 2){
		$periodo_inicial = '00:30:00.000';
		$periodo_final = '00:59:59.999';
	}

	if ($contador == 3){
		$periodo_inicial = '01:00:00.000';
		$periodo_final = '01:29:59.999';
	}

	if ($contador == 4){
		$periodo_inicial = '01:30:00.000';
		$periodo_final = '01:59:59.999';
	}

	if ($contador == 5){
		$periodo_inicial = '02:00:00.000';
		$periodo_final = '02:29:59.999';
	}

	if ($contador == 6){
		$periodo_inicial = '02:30:00.000';
		$periodo_final = '02:59:59.999';
	}

	if ($contador == 7){
		$periodo_inicial = '03:00:00.000';
		$periodo_final = '03:29:59.999';
	}

	if ($contador == 8){
		$periodo_inicial = '03:30:00.000';
		$periodo_final = '03:59:59.999';
	}

	if ($contador == 9){
		$periodo_inicial = '04:00:00.000';
		$periodo_final = '04:29:59.999';
	}

	if ($contador == 10){
		$periodo_inicial = '04:30:00.000';
		$periodo_final = '04:59:59.999';
	}

	if ($contador == 11){
		$periodo_inicial = '05:00:00.000';
		$periodo_final = '05:29:59.999';
	}

	if ($contador == 12){
		$periodo_inicial = '05:30:00.000';
		$periodo_final = '05:59:59.999';
	}

	if ($contador == 13){
		$periodo_inicial = '06:00:00.000';
		$periodo_final = '06:29:59.999';
	}

	if ($contador == 14){
		$periodo_inicial = '06:30:00.000';
		$periodo_final = '06:59:59.999';
	}

	if ($contador == 15){
		$periodo_inicial = '07:00:00.000';
		$periodo_final = '07:29:59.999';
	}

	if ($contador == 16){
		$periodo_inicial = '07:30:00.000';
		$periodo_final = '07:59:59.999';
	}

	if ($contador == 17){
		$periodo_inicial = '08:00:00.000';
		$periodo_final = '08:29:59.999';
	}

	if ($contador == 18){
		$periodo_inicial = '08:30:00.000';
		$periodo_final = '08:59:59.999';
	}

	if ($contador == 19){
		$periodo_inicial = '09:00:00.000';
		$periodo_final = '09:29:59.999';
	}

	if ($contador == 20){
		$periodo_inicial = '09:30:00.000';
		$periodo_final = '09:59:59.999';
	}

	if ($contador == 21){
		$periodo_inicial = '10:00:00.000';
		$periodo_final = '10:29:59.999';
	}

	if ($contador == 22){
		$periodo_inicial = '10:30:00.000';
		$periodo_final = '10:59:59.999';
	}

	if ($contador == 23){
		$periodo_inicial = '11:00:00.000';
		$periodo_final = '11:29:59.999';
	}

	if ($contador == 24){
		$periodo_inicial = '11:30:00.000';
		$periodo_final = '11:59:59.999';
	}

	if ($contador == 25){
		$periodo_inicial = '12:00:00.000';
		$periodo_final = '12:29:59.999';
	}

	if ($contador == 26){
		$periodo_inicial = '12:30:00.000';
		$periodo_final = '12:59:59.999';
	}

	if ($contador == 27){
		$periodo_inicial = '13:00:00.000';
		$periodo_final = '13:29:59.999';
	}

	if ($contador == 28){
		$periodo_inicial = '13:30:00.000';
		$periodo_final = '13:59:59.999';
	}

	if ($contador == 29){
		$periodo_inicial = '14:00:00.000';
		$periodo_final = '14:29:59.999';
	}

	if ($contador == 30){
		$periodo_inicial = '14:30:00.000';
		$periodo_final = '14:59:59.999';
	}

	if ($contador == 31){
		$periodo_inicial = '15:00:00.000';
		$periodo_final = '15:29:59.999';
	}

	if ($contador == 32){
		$periodo_inicial = '15:30:00.000';
		$periodo_final = '15:59:59.999';
	}

	if ($contador == 33){
		$periodo_inicial = '16:00:00.000';
		$periodo_final = '16:29:59.999';
	}

	if ($contador == 34){
		$periodo_inicial = '16:30:00.000';
		$periodo_final = '16:59:59.999';
	}

	if ($contador == 35){
		$periodo_inicial = '17:00:00.000';
		$periodo_final = '17:29:59.999';
	}

	if ($contador == 36){
		$periodo_inicial = '17:30:00.000';
		$periodo_final = '17:59:59.999';
	}

	if ($contador == 37){
		$periodo_inicial = '18:00:00.000';
		$periodo_final = '18:29:59.999';
	}

	if ($contador == 38){
		$periodo_inicial = '18:30:00.000';
		$periodo_final = '18:59:59.999';
	}

	if ($contador == 39){
		$periodo_inicial = '19:00:00.000';
		$periodo_final = '19:29:59.999';
	}

	if ($contador == 40){
		$periodo_inicial = '19:30:00.000';
		$periodo_final = '19:59:59.999';
	}

	if ($contador == 41){
		$periodo_inicial = '20:00:00.000';
		$periodo_final = '20:29:59.999';
	}

	if ($contador == 42){
		$periodo_inicial = '20:30:00.000';
		$periodo_final = '20:59:59.999';
	}

	if ($contador == 43){
		$periodo_inicial = '21:00:00.000';
		$periodo_final = '21:29:59.999';
	}

	if ($contador == 44){
		$periodo_inicial = '21:30:00.000';
		$periodo_final = '21:59:59.999';
	}

	if ($contador == 45){
		$periodo_inicial = '22:00:00.000';
		$periodo_final = '22:29:59.999';
	}

	if ($contador == 46){
		$periodo_inicial = '22:30:00.000';
		$periodo_final = '22:59:59.999';
	}

	if ($contador == 47){
		$periodo_inicial = '23:00:00.000';
		$periodo_final = '23:29:59.999';
	}

	if ($contador == 48){
		$periodo_inicial = '23:30:00.000';
		$periodo_final = '23:59:59.999';
	}

	$a_per = 0;
	$b_per = 0;
	$c_per = 0;

	for($pos_dia=1; ( $pos_dia<($qtd_dias+1) ); $pos_dia++){
		// VERIFICA NS (TEMPO DE ESPERA) 45s ou 90s
		if(isset($_POST["chk_$pos_dia"])){
			$ns = $ns_diferenciado;
			$nsr_premium = $nsr_valor;
		}
		else{
			$ns = $ns_normal;
			$nsr_premium = $nsr_premium_valor;
		}
		// A DO PERÍODO
		$query = $pdo->prepare("SELECT COUNT (*) TOTAL
				FROM TB_EVENTOS_DAC
				WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano $periodo_inicial' AND '$qual_mes/$pos_dia/$qual_ano $periodo_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND TEMPO_ESPERA <= '$ns'");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){
			$a_per = $a_per + $row['TOTAL'];
		}

		// B DO PERÍODO
		$query = $pdo->prepare("SELECT COUNT (*) TOTAL
				FROM TB_EVENTOS_DAC
				WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano $periodo_inicial' AND '$qual_mes/$pos_dia/$qual_ano $periodo_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0'");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){
			$b_per = $b_per + $row['TOTAL'];
		}

		// C DO PERÍODO
		$query = $pdo->prepare("SELECT COUNT (*) TOTAL
				FROM TB_EVENTOS_DAC
				WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano $periodo_inicial' AND '$qual_mes/$pos_dia/$qual_ano $periodo_final' AND CALLID IS NOT NULL AND TEMPO_ATEND = '0' AND TEMPO_ESPERA > '$ns'");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){
			$c_per = $c_per + $row['TOTAL'];
		}
	}

	$a_mes = $a_mes + $a_per;
	$b_mes = $b_mes + $b_per;
	$c_mes = $c_mes + $c_per;
	$nsa_periodo = ($a_per/($b_per+$c_per));
	$soma_nsa = $soma_nsa + $nsa_periodo;
}

$nsa_mes = ($a_mes/($b_mes+$c_mes));
$nsh_mes = $soma_nsa/48;

// CALCULA DNS
$dif_nsa_nsh = $nsa_mes - $nsh_mes;
if ($dif_nsa_nsh < 0) $dif_nsa_nsh = $dif_nsa_nsh * (-1);

if ($dif_nsa_nsh > 0.05) $dns = 1 - ( $dif_nsa_nsh - 0.05 );
else $dns = 1;

// DESCONECTA BANCO DE DADOS
include "desconecta.php";
?>