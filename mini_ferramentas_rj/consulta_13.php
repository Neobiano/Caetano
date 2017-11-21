<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

</head>
<body>

<?php
$nome_relatorio = "percentual_de_transferencias"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Percentual de Transferências"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

$vet_filas = array();

$filas_ativas = array();

//VARIÁVEIS TOTALIZADORAS
$TOTAL_SEM_TRANSFERENCIA = 0;
$TOTAL_COM_TRANSFERENCIA = 0;
$PERCENTUAL_TOTAL = 0;
$TOTAL_TRANSFERENCIAS_PERIODO = 0;

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>COD</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>DESCRIÇÃO</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ATE_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ATE1_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE1_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE1_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ATE2_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE2_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE2_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ATE3_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE3_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE3_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ATE4_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE4_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ATE4_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ABA_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ABA1_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA1_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA1_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ABA2_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA2_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA2_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ABA3_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA3_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA3_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$texto = "<td><b>ABA4_DAC</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA4_ACU</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ABA4_DIF</b></td>";
	echo incrementa_tabela($texto);
	
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// ATENDIDAS DAC
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, count(*) ATENDIDAS_DAC from tb_eventos_DAC as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila
							");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
			
		$desc_fila = utf8_encode($row['desc_fila']);
		$ATENDIDAS_DAC = utf8_encode($row['ATENDIDAS_DAC']);
		
		$vet_filas["ATENDIDAS_DAC"][$cod_fila] = $ATENDIDAS_DAC;
	}
	
	// ATENDIDAS ACU
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, sum(atendidas) ATENDIDAS_ACU from tb_fila_acumulado as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data between '$data_inicial' and '$data_final 23:59:59.999' and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ATENDIDAS_ACU = utf8_encode($row['ATENDIDAS_ACU']);
		
		$vet_filas["ATENDIDAS_ACU"][$cod_fila] = $ATENDIDAS_ACU;
	}
	
	// ATENDIDAS_1  DAC
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, count(*) ATENDIDAS_1_DAC from tb_eventos_DAC as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and tempo_espera <= 10 and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila
							");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ATENDIDAS_1_DAC = utf8_encode($row['ATENDIDAS_1_DAC']);
		
		$vet_filas["ATENDIDAS_1_DAC"][$cod_fila] = $ATENDIDAS_1_DAC;
	}
	
	// ATENDIDAS_1 ACU
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, sum(atendidas_1) ATENDIDAS_1_ACU from tb_fila_acumulado as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data between '$data_inicial' and '$data_final 23:59:59.999' and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ATENDIDAS_1_ACU = utf8_encode($row['ATENDIDAS_1_ACU']);
		
		$vet_filas["ATENDIDAS_1_ACU"][$cod_fila] = $ATENDIDAS_1_ACU;
	}
	
	// ATENDIDAS_2 DAC
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, count(*) ATENDIDAS_2_DAC from tb_eventos_DAC as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and tempo_espera > 10 and tempo_espera <= 45 and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ATENDIDAS_2_DAC = utf8_encode($row['ATENDIDAS_2_DAC']);
		
		$vet_filas["ATENDIDAS_2_DAC"][$cod_fila] = $ATENDIDAS_2_DAC;
	}
	
	// ATENDIDAS_2 ACU
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, sum(atendidas_2) ATENDIDAS_2_ACU from tb_fila_acumulado as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data between '$data_inicial' and '$data_final 23:59:59.999' and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ATENDIDAS_2_ACU = utf8_encode($row['ATENDIDAS_2_ACU']);
		
		$vet_filas["ATENDIDAS_2_ACU"][$cod_fila] = $ATENDIDAS_2_ACU;
	}
	
	// ATENDIDAS_3 DAC
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, count(*) ATENDIDAS_3_DAC from tb_eventos_DAC as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and tempo_espera > 45 and tempo_espera <= 90 and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ATENDIDAS_3_DAC = utf8_encode($row['ATENDIDAS_3_DAC']);
		
		$vet_filas["ATENDIDAS_3_DAC"][$cod_fila] = $ATENDIDAS_3_DAC;
	}
	
	// ATENDIDAS_3 ACU
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, sum(atendidas_3) ATENDIDAS_3_ACU from tb_fila_acumulado as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data between '$data_inicial' and '$data_final 23:59:59.999' and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ATENDIDAS_3_ACU = utf8_encode($row['ATENDIDAS_3_ACU']);
		
		$vet_filas["ATENDIDAS_3_ACU"][$cod_fila] = $ATENDIDAS_3_ACU;
	}
	
	// ABANDONADAS DAC
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, count(*) ABANDONADAS_DAC from tb_eventos_DAC as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend = 0 and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ABANDONADAS_DAC = utf8_encode($row['ABANDONADAS_DAC']);
		
		$vet_filas["ABANDONADAS_DAC"][$cod_fila] = $ABANDONADAS_DAC;
	}
	
	// ABANDONADAS ACU
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, sum(abandonadas) ABANDONADAS_ACU from tb_fila_acumulado as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data between '$data_inicial' and '$data_final 23:59:59.999' and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ABANDONADAS_ACU = utf8_encode($row['ABANDONADAS_ACU']);
		
		$vet_filas["ABANDONADAS_ACU"][$cod_fila] = $ABANDONADAS_ACU;
	}
	
	// ABANDONADAS_1 DAC
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, count(*) ABANDONADAS_1_DAC from tb_eventos_DAC as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend = 0 and tempo_espera <= 10 and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ABANDONADAS_1_DAC = utf8_encode($row['ABANDONADAS_1_DAC']);
		
		$vet_filas["ABANDONADAS_1_DAC"][$cod_fila] = $ABANDONADAS_1_DAC;
	}
	
	// ABANDONADAS_1 ACU
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, sum(abandonadas_1) ABANDONADAS_1_ACU from tb_fila_acumulado as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data between '$data_inicial' and '$data_final 23:59:59.999' and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ABANDONADAS_1_ACU = utf8_encode($row['ABANDONADAS_1_ACU']);
		
		$vet_filas["ABANDONADAS_1_ACU"][$cod_fila] = $ABANDONADAS_1_ACU;
	}
	
	// ABANDONADAS_2 DAC
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, count(*) ABANDONADAS_2_DAC from tb_eventos_DAC as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend = 0 and tempo_espera > 10  and tempo_espera <= 45 and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ABANDONADAS_2_DAC = utf8_encode($row['ABANDONADAS_2_DAC']);
		
		$vet_filas["ABANDONADAS_2_DAC"][$cod_fila] = $ABANDONADAS_2_DAC;
	}
	
	// ABANDONADAS_2 ACU
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, sum(abandonadas_2) ABANDONADAS_2_ACU from tb_fila_acumulado as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data between '$data_inicial' and '$data_final 23:59:59.999' and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ABANDONADAS_2_ACU = utf8_encode($row['ABANDONADAS_2_ACU']);
		
		$vet_filas["ABANDONADAS_2_ACU"][$cod_fila] = $ABANDONADAS_2_ACU;
	}
	
	// ABANDONADAS_3 DAC
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, count(*) ABANDONADAS_3_DAC from tb_eventos_DAC as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend = 0 and tempo_espera > 45  and tempo_espera <= 90 and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ABANDONADAS_3_DAC = utf8_encode($row['ABANDONADAS_3_DAC']);
		
		$vet_filas["ABANDONADAS_3_DAC"][$cod_fila] = $ABANDONADAS_3_DAC;
	}
	
	// ABANDONADAS_3 ACU
	$query = $pdo->prepare("select a.cod_fila, b.desc_fila, sum(abandonadas_3) ABANDONADAS_3_ACU from tb_fila_acumulado as a
							inner join tb_filas as b
							on a.cod_fila = b.cod_fila
							where data between '$data_inicial' and '$data_final 23:59:59.999' and desc_fila like 'CXA%'
							group by a.cod_fila, b.desc_fila");
	$query->execute();	
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			$cod_fila = number_format($cod_fila, 0, ',', '.'); if (!in_array($cod_fila, $filas_ativas)) array_push($filas_ativas, $cod_fila);
		$desc_fila = utf8_encode($row['desc_fila']);
		$ABANDONADAS_3_ACU = utf8_encode($row['ABANDONADAS_3_ACU']);
		
		$vet_filas["ABANDONADAS_3_ACU"][$cod_fila] = $ABANDONADAS_3_ACU;
	}
	foreach ($filas_ativas as $value){
		echo "<tr>";
			echo "<td>$value</td>";
			echo "<td>$value</td>";
			
			if (isset($vet_filas["ATENDIDAS_DAC"][$value])) $ATENDIDAS_DAC = $vet_filas["ATENDIDAS_DAC"][$value];
			else $ATENDIDAS_DAC = 0;
			echo "<td>$ATENDIDAS_DAC</td>";		
			
			
			if (isset($vet_filas["ATENDIDAS_ACU"][$value])) $ATENDIDAS_ACU = $vet_filas["ATENDIDAS_ACU"][$value];
			else $ATENDIDAS_ACU = 0;
			echo "<td>$ATENDIDAS_ACU</td>";
			
			$dif = $ATENDIDAS_DAC - $ATENDIDAS_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
			if (isset($vet_filas["ATENDIDAS_1_DAC"][$value])) $ATENDIDAS_1_DAC = $vet_filas["ATENDIDAS_1_DAC"][$value];
			else $ATENDIDAS_1_DAC = 0;
			echo "<td>$ATENDIDAS_1_DAC</td>";
			
			if (isset($vet_filas["ATENDIDAS_1_ACU"][$value])) $ATENDIDAS_1_ACU = $vet_filas["ATENDIDAS_1_ACU"][$value];
			else $ATENDIDAS_1_ACU = 0;
			echo "<td>$ATENDIDAS_1_ACU</td>";
			
			$dif = $ATENDIDAS_1_DAC - $ATENDIDAS_1_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
			if (isset($vet_filas["ATENDIDAS_2_DAC"][$value])) $ATENDIDAS_2_DAC = $vet_filas["ATENDIDAS_2_DAC"][$value];
			else $ATENDIDAS_2_DAC = 0;
			echo "<td>$ATENDIDAS_2_DAC</td>";
			
			if (isset($vet_filas["ATENDIDAS_2_ACU"][$value])) $ATENDIDAS_2_ACU = $vet_filas["ATENDIDAS_2_ACU"][$value];
			else $ATENDIDAS_2_ACU = 0;
			echo "<td>$ATENDIDAS_2_ACU</td>";
			
			$dif = $ATENDIDAS_2_DAC - $ATENDIDAS_2_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
			if (isset($vet_filas["ATENDIDAS_3_DAC"][$value])) $ATENDIDAS_3_DAC = $vet_filas["ATENDIDAS_3_DAC"][$value];
			else $ATENDIDAS_3_DAC = 0;
			echo "<td>$ATENDIDAS_3_DAC</td>";
			
			if (isset($vet_filas["ATENDIDAS_3_ACU"][$value])) $ATENDIDAS_3_ACU = $vet_filas["ATENDIDAS_3_ACU"][$value];
			else $ATENDIDAS_3_ACU = 0;
			echo "<td>$ATENDIDAS_3_ACU</td>";
			
			$dif = $ATENDIDAS_3_DAC - $ATENDIDAS_3_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
			$ATENDIDAS_4_DAC = $ATENDIDAS_DAC - $ATENDIDAS_1_DAC - $ATENDIDAS_2_DAC - $ATENDIDAS_3_DAC;
			echo "<td>$ATENDIDAS_4_DAC</td>";
			
			$ATENDIDAS_4_ACU = $ATENDIDAS_ACU - $ATENDIDAS_1_ACU - $ATENDIDAS_2_ACU - $ATENDIDAS_3_ACU;
			echo "<td>$ATENDIDAS_4_ACU</td>";

			$dif = $ATENDIDAS_4_DAC - $ATENDIDAS_4_ACU;
			echo "<td><b>$dif</b></td>";			

			
			
			if (isset($vet_filas["ABANDONADAS_DAC"][$value])) $ABANDONADAS_DAC = $vet_filas["ABANDONADAS_DAC"][$value];
			else $ABANDONADAS_DAC = 0;
			echo "<td>$ABANDONADAS_DAC</td>";
			
			if (isset($vet_filas["ABANDONADAS_ACU"][$value])) $ABANDONADAS_ACU = $vet_filas["ABANDONADAS_ACU"][$value];
			else $ABANDONADAS_ACU = 0;
			echo "<td>$ABANDONADAS_ACU</td>";
			
			$dif = $ABANDONADAS_DAC - $ABANDONADAS_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
			if (isset($vet_filas["ABANDONADAS_1_DAC"][$value])) $ABANDONADAS_1_DAC = $vet_filas["ABANDONADAS_1_DAC"][$value];
			else $ABANDONADAS_1_DAC = 0;
			echo "<td>$ABANDONADAS_1_DAC</td>";
			
			if (isset($vet_filas["ABANDONADAS_1_ACU"][$value])) $ABANDONADAS_1_ACU = $vet_filas["ABANDONADAS_1_ACU"][$value];
			else $ABANDONADAS_1_ACU = 0;
			echo "<td>$ABANDONADAS_1_ACU</td>";
			
			$dif = $ABANDONADAS_1_DAC - $ABANDONADAS_1_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
			if (isset($vet_filas["ABANDONADAS_2_DAC"][$value])) $ABANDONADAS_2_DAC = $vet_filas["ABANDONADAS_2_DAC"][$value];
			else $ABANDONADAS_2_DAC = 0;
			echo "<td>$ABANDONADAS_2_DAC</td>";
			
			if (isset($vet_filas["ABANDONADAS_2_ACU"][$value])) $ABANDONADAS_2_ACU = $vet_filas["ABANDONADAS_2_ACU"][$value];
			else $ABANDONADAS_2_ACU = 0;
			echo "<td>$ABANDONADAS_2_ACU</td>";
			
			$dif = $ABANDONADAS_2_DAC - $ABANDONADAS_2_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
			if (isset($vet_filas["ABANDONADAS_3_DAC"][$value])) $ABANDONADAS_3_DAC = $vet_filas["ABANDONADAS_3_DAC"][$value];
			else $ABANDONADAS_3_DAC = 0;
			echo "<td>$ABANDONADAS_3_DAC</td>";
			
			if (isset($vet_filas["ABANDONADAS_3_ACU"][$value])) $ABANDONADAS_3_ACU = $vet_filas["ABANDONADAS_3_ACU"][$value];
			else $ABANDONADAS_3_ACU = 0;
			echo "<td>$ABANDONADAS_3_ACU</td>";
			
			$dif = $ABANDONADAS_3_DAC - $ABANDONADAS_3_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
			$ABANDONADAS_4_DAC = $ABANDONADAS_DAC - $ABANDONADAS_1_DAC - $ABANDONADAS_2_DAC - $ABANDONADAS_3_DAC;
			echo "<td>$ABANDONADAS_4_DAC</td>";
			
			$ABANDONADAS_4_ACU = $ABANDONADAS_ACU - $ABANDONADAS_1_ACU - $ABANDONADAS_2_ACU - $ABANDONADAS_3_ACU;
			echo "<td>$ABANDONADAS_4_ACU</td>";
			
			$dif = $ABANDONADAS_4_DAC - $ABANDONADAS_4_ACU;
			echo "<td><b>$dif</b></td>";
			
			
			
		echo "</tr>";
	}

echo "</tbody>";

echo "</table></div>";

echo "<script>$('#tabela').show();</script>";

	
//include "finaliza_tabela.php"; // FINALIZA A TABELA
include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>

</body>
</html>

<script>  
$('#tabela').DataTable( {
	"order": [[ 0, "asc" ]]
} );
</script>