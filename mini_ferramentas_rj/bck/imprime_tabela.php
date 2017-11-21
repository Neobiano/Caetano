<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>
</head>

<body>

<?php
include "conecta.php";

set_time_limit(9999);
ini_set('max_execution_time', 9999);

$qtd_ilhas = 0;
$todas_ilhas = array();
$query = $pdo->prepare("select * from tb_ilhas");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$nome = utf8_encode($row['nome_ilha']);	
	$cod_filas = utf8_encode($row['cod_filas']);
	$desc = utf8_encode($row['desc_ilha']);	
	$$nome = explode(";", $cod_filas);
	$todas_ilhas[$qtd_ilhas] = "$nome";
	$desc_todas_ilhas[$qtd_ilhas] = "$desc";
	$qtd_ilhas++;
}

//Variáveis do Formulário - Início
$data_inicial = $_POST['data_inicial'];
$data_final = $_POST['data_final'];
$tipo_consulta = $_POST['tipo_consulta'];
$codigo_evento = $_POST['codigo_evento'];
$qtd_pesq = $_POST['qtd_pesq'];
$hora_inicial = $_POST['hora_inicial'];
$hora_final = $_POST['hora_final'];
$cb_ilha = $_POST['cb_ilha'];
$qual_tabela = $_POST['qual_tabela'];
$min_transf = $_POST['min_transf'];
//Variáveis do Formulário - Início


//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);
$t_inicial = strtotime($data_final);
$data_final_texto = date('d/m/Y',$t_inicial);
//Conversão Data Texto - Fim

//Conversão Data - Início
$t_inicial = strtotime($data_inicial);
$data_inicial = date('m/d/Y',$t_inicial);
$dt_mod = date('Y-n-d',$t_inicial);
$t_final = strtotime($data_final);
$data_final_pre = date('m/d/Y',$t_final);
$data_final = date('m/d/Y', strtotime("+1 day", strtotime($data_final_pre)));
//Conversão Data - Fim

$total_geral = 0; // CONSULTA 3

if($tipo_consulta == '01'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br><br>";
	
	$query = $pdo->prepare("select count (distinct callid) TOTAL, count (callid) ATENDIDAS
							from tb_eventos_DAC
							where data_hora between '$data_inicial' and '$data_final' and callid is not null and tempo_atend > '0'");		
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$total = utf8_encode($row['TOTAL']); //callid distinto
		$atendidas = utf8_encode($row['ATENDIDAS']);
	}
	$total = number_format($total, 0, ',', '.');
	$atendidas = number_format($atendidas, 0, ',', '.');
	
		echo "<b><i>Quantidade de Ligações Atendidas (Transferências Ignoradas):</i></b> $total";
		echo "<br><br><b><i>Quantidade de Ligações Atendidas (Transferências Contabilizadas):</i></b> $atendidas";
		$perc_transferidas = ($atendidas - $total) / ($atendidas) * 100;
		$perc_transferidas = number_format($perc_transferidas, 2, ',', '.');
		echo "<br><br><b><i>Percentual de Ligações Transferidas:</i></b> $perc_transferidas%";
		echo "</div>";
}

if($tipo_consulta == '02'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	
	// PERGUNTA 1 - INÍCIO		
	$total_geral = 0;
	
	$query = $pdo->prepare("select perg1 RESPOSTA, COUNT(*) TOTAL from tb_pesq_satisfacao
							where data_hora between '$data_inicial' and '$data_final'
							group by perg1");
	$query->execute();
	
	echo '<br><br>';
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>No Geral, qual seu grau de satisfação?</b></td>';
	echo '<td class="w3-right"><b>QUANTIDADE</b></td>';
	echo '</tr>';
	
	for($i=0; $row = $query->fetch(); $i++){
		$status = utf8_encode($row['RESPOSTA']);
		$total = utf8_encode($row['TOTAL']);
		
		$total_geral = $total_geral + $total;
		
		if ($status=='1') $status = 'Satisfeito';
		if ($status=='2') $status = 'Indiferente';
		if ($status=='3') $status = 'Insatisfeito';
		if ($status=='-1') $status = 'Erro';
		if ($status=='-2') $status = 'Sem Interação';
		if ($status=='1') $status = 'Opção Inválida';
				
		echo "<tr>";
		echo "<td>$status</td>";
		echo "<td class='w3-right'>$total</td>";
		echo "</tr>";		
	}
	echo "<tr>";
	echo "<td><b>TOTAL </b></td>";
	echo "<td class='w3-right'><b>$total_geral</b></td>";
	echo "</tr>";
	
	echo "</table>";
	
	echo "<br><br>";
	// PERGUNTA 1 - FIM
	
	// PERGUNTA 2 - INÍCIO
	$total_geral = 0;
	
	$query = $pdo->prepare("select perg2 RESPOSTA, COUNT(*) TOTAL from tb_pesq_satisfacao
							where data_hora between '$data_inicial' and '$data_final'
							group by perg2");
	$query->execute();
	
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>Quanto ao tempo de espera, você se considera:</b></td>';
	echo '<td class="w3-right"><b>QUANTIDADE</b></td>';
	echo '</tr>';
	
	for($i=0; $row = $query->fetch(); $i++){
		$status = utf8_encode($row['RESPOSTA']);
		$total = utf8_encode($row['TOTAL']);
		
		$total_geral = $total_geral + $total;
	
		if ($status=='1') $status = 'Satisfeito';
		if ($status=='2') $status = 'Indiferente';
		if ($status=='3') $status = 'Insatisfeito';
		if ($status=='-1') $status = 'Erro';
		if ($status=='-2') $status = 'Sem Interação';
		if ($status=='1') $status = 'Opção Inválida';
		
		echo "<tr>";
		echo "<td>$status</td>";
		echo "<td class='w3-right'>$total</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td><b>TOTAL </b></td>";
	echo "<td class='w3-right'><b>$total_geral</b></td>";
	echo "</tr>";
	
	echo "</table>";
	
	echo "<br><br>";
	// PERGUNTA 2 - FIM
	
	// PERGUNTA 3 - INÍCIO
	$total_geral = 0;
	
	$query = $pdo->prepare("select perg3 RESPOSTA, COUNT(*) TOTAL from tb_pesq_satisfacao
							where data_hora between '$data_inicial' and '$data_final'
							group by perg3");
	$query->execute();
	
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>Quanto à cordialidade do atendente, você se considera:</b></td>';
	echo '<td class="w3-right"><b>QUANTIDADE</b></td>';
	echo '</tr>';
	
	for($i=0; $row = $query->fetch(); $i++){
		$status = utf8_encode($row['RESPOSTA']);
		$total = utf8_encode($row['TOTAL']);
		
		$total_geral = $total_geral + $total;
		
		if ($status=='1') $status = 'Satisfeito';
		if ($status=='2') $status = 'Indiferente';
		if ($status=='3') $status = 'Insatisfeito';
		if ($status=='-1') $status = 'Erro';
		if ($status=='-2') $status = 'Sem Interação';
		if ($status=='1') $status = 'Opção Inválida';
			
		echo "<tr>";
		echo "<td>$status</td>";
		echo "<td class='w3-right'>$total</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td><b>TOTAL </b></td>";
	echo "<td class='w3-right'><b>$total_geral</b></td>";
	echo "</tr>";
	
	echo "</table>";
	
	echo "<br><br>";
	// PERGUNTA 3 - FIM
	
	// PERGUNTA 4 - INÍCIO
	$total_geral = 0;
	
	$query = $pdo->prepare("select perg4 RESPOSTA, COUNT(*) TOTAL from tb_pesq_satisfacao
							where data_hora between '$data_inicial' and '$data_final'
							group by perg4");
	$query->execute();
	
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>A solicitação foi atendida ao final do atendimento?</b></td>';
	echo '<td class="w3-right"><b>QUANTIDADE</b></td>';
	echo '</tr>';
	
	for($i=0; $row = $query->fetch(); $i++){
		$status = utf8_encode($row['RESPOSTA']);
		$total = utf8_encode($row['TOTAL']);
		
		$total_geral = $total_geral + $total;
		
		if ($status=='1') $status = 'Satisfeito';
		if ($status=='2') $status = 'Indiferente';
		if ($status=='3') $status = 'Insatisfeito';
		if ($status=='-1') $status = 'Erro';
		if ($status=='-2') $status = 'Sem Interação';
		if ($status=='1') $status = 'Opção Inválida';
			
		echo "<tr>";
		echo "<td>$status</td>";
		echo "<td class='w3-right'>$total</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td><b>TOTAL</b></td>";
	echo "<td class='w3-right'><b>$total_geral</b></td>";
	echo "</tr>";
	
	echo "</table>";
	// PERGUNTA 4 - FIM
	
	echo "</div>";
}

if($tipo_consulta == '03'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	
		$query = $pdo->prepare("SELECT DS_MOTIVO MOTIVO, DS_SUBMOTIVO SUBMOTIVO, count(*) TOTAL
	  							FROM TB_LOG_CATEGORIZACAO
								WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final'
								GROUP BY  DS_MOTIVO, DS_SUBMOTIVO
								ORDER BY MOTIVO, SUBMOTIVO");
	$query->execute();
	
	echo '<br><br>';
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>MOTIVO</b></td>';
	echo '<td><b>SUBMOTIVO</b></td>';
	echo '<td><b>TOTAL</b></td>';
	echo '</tr>';
	
	for($i=0; $row = $query->fetch(); $i++){
		//$motivo = utf8_encode($row['MOTIVO']);
		//$submotivo = utf8_encode($row['SUBMOTIVO']);
		$motivo = utf8_encode($row['MOTIVO']);
		$submotivo = utf8_encode($row['SUBMOTIVO']);
		$total = utf8_encode($row['TOTAL']);
		$total_geral = $total_geral + $total;
		
		echo "<tr>";
		echo "<td>$motivo</td>";
		echo "<td>$submotivo</td>";
		echo "<td>$total</td>";
		echo "</tr>";
	}
	
	echo "<tr class='w3-indigo'>";
	echo "<td><b>TOTAL</b></td>";
	echo "<td></td>";
	echo "<td><b>$total_geral</b></td>";
	echo "</tr>";
	
	echo "</table>";
	
	
	echo "</div>";		
}
// CONSULTA 07 - INÍCIO
if($tipo_consulta == '07'){
	$total_de_atendimentos = 0;
	$quantidade_de_filas = 0;
	$soma_tma = 0;
	$media_tma = 0;
	
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br>";

	// PESQUISA NOME DA FILA
	$query = $pdo->prepare("SELECT * FROM TB_FILAS");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
		$nome_variavel_sc = "nome_fila_$cod_fila";
		$$nome_variavel_sc = utf8_encode($row['desc_fila']);
	}
	
// INI
for($a=0;$a<$qtd_ilhas;$a++){
	
	$mult_tma = 0;
	$soma_total = 0;
	
	$in_ilhas = implode(",", $$todas_ilhas[$a]);
		
	$query = $pdo->prepare("SELECT COD_FILA, SUM(1) TOTAL, AVG(TEMPO_ATEND) TMA FROM
			(SELECT DISTINCT *
			FROM TB_EVENTOS_DAC
			WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($in_ilhas)) AS A
			GROUP BY COD_FILA
			ORDER BY COD_FILA");
	$query->execute();

	echo '<br><br>';
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	if ($desc_todas_ilhas[$a] == 'PESSOA JURÍDICA') echo "<td><b>ILHA $desc_todas_ilhas[$a]</b></td>";
	else echo "<td><b>ILHA $desc_todas_ilhas[$a]</b></td>";
	echo '<td><b>TOTAL DE ATENDIMENTOS</b></td>';
	echo '<td><b>TMA (Segundos)</b></td>';
	echo '</tr>';

	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$tma = utf8_encode($row['TMA']);
	
		
		$imprimir_fila_atual = "nome_fila_$cod_fila"; //TROCAR COD_FILA PELO NOME
		if (isset($$imprimir_fila_atual)){
			$imp_fila = $$imprimir_fila_atual;
		}
		else $imp_fila = '';

		
		$mult_tma = $mult_tma + ($total*$tma);
		$soma_total = $soma_total + $total;

		echo "<tr>";
		echo "<td>$cod_fila <i>$imp_fila</i></td>";
		echo "<td>$total</td>";
		echo "<td>$tma</td>";
		echo "</tr>";
		}
	$media_tma = $mult_tma / $soma_total;
	$imprime_media_tma = number_format($media_tma, 0, ',', '.');

	echo "<tr class='w3-indigo'>";
	echo "<td><b>TOTAL DE ATENDIMENTOS / TMA DA ILHA</b></td>";
	echo "<td><b>$soma_total</b></td>";
	echo "<td><b>$imprime_media_tma</b></td>";
	echo "</tr>";

	echo "</table>";
}

// FIM
}

if($tipo_consulta == '04'){
	$total_de_atendimentos = 0;
	$quantidade_de_filas = 0;
	$soma_tma = 0;
	$media_tma = 0;
	$mult_tma = 0;
	$soma_total = 0;

	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br>";

	// PESQUISA NOME DA FILA
	$query = $pdo->prepare("SELECT * FROM TB_FILAS");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
		$nome_variavel_sc = "nome_fila_$cod_fila";
		$$nome_variavel_sc = utf8_encode($row['desc_fila']);
	}

	$query = $pdo->prepare("SELECT COD_FILA, SUM(1) TOTAL, AVG(TEMPO_ATEND) TMA FROM
			(SELECT DISTINCT *
			FROM TB_EVENTOS_DAC
			WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0') AS A
			GROUP BY COD_FILA
			ORDER BY COD_FILA");
	$query->execute();

	echo '<br><br>';
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>FILA</b></td>';
	echo '<td><b>TOTAL DE ATENDIMENTOS</b></td>';
	echo '<td><b>TMA (Segundos)</b></td>';
	echo '</tr>';

	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$tma = utf8_encode($row['TMA']);

		$imprimir_fila_atual = "nome_fila_$cod_fila"; //TROCAR COD_FILA PELO NOME
		if (isset($$imprimir_fila_atual)) $imp_fila = $$imprimir_fila_atual;
		else $imp_fila = '';

		$mult_tma = $mult_tma + ($total*$tma);
		$soma_total = $soma_total + $total;

		echo "<tr>";
		echo "<td>$cod_fila <i>$imp_fila</i></td>";
		echo "<td>$total</td>";
		echo "<td>$tma</td>";
		echo "</tr>";
	}
	$media_tma = $mult_tma / $soma_total;
	$imprime_media_tma = number_format($media_tma, 0, ',', '.');

	echo "<tr class='w3-indigo'>";
	echo "<td><b>TOTAL DE ATENDIMENTOS / MÉDIA TMA</b></td>";
	echo "<td><b>$soma_total</b></td>";
	echo "<td><b>$imprime_media_tma</b></td>";
	echo "</tr>";

	echo "</table>";
}


if($tipo_consulta == '05'){
	$total_de_atendimentos = 0;
	$quantidade_de_filas = 0;
	$soma_tma = 0;
	$media_tma = 0;
	
	$soma_a45 = 0;
	$soma_b45 = 0;
	$soma_c45 = 0;
	
	$soma_a90 = 0;
	$soma_b90 = 0;
	$soma_c90 = 0;
	
	$total_periodo = 0;
	
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br>";
	
	// PESQUISA NOME DA FILA
	
	echo '<br><br>';
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>FILA</b></td>';
	echo '<td><b>TOTAL DE ATENDIMENTOS</b></td>';
	echo '<td><b>TMA (Segundos)</b></td>';
	echo '<td><b>NSA 45 Segundos</b></td>';
	echo '<td><b>NSA 90 Segundos</b></td>';
	echo '</tr>';
	
	// A 45
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
							FROM TB_EVENTOS_DAC
							WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND TEMPO_ESPERA <= '45'
							GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$palavra = "a45_$cod_fila";
		$$palavra = $total;
	}
	
	// B 45
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
							FROM TB_EVENTOS_DAC
							WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0'
							GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$palavra = "b45_$cod_fila";
		$$palavra = $total;
	}
	
	// C 45
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
							FROM TB_EVENTOS_DAC
							WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND = '0' AND TEMPO_ESPERA > '45'
							GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$palavra = "c45_$cod_fila";
		$$palavra = $total;
	}
	
	// A 90
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
			FROM TB_EVENTOS_DAC
			WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND TEMPO_ESPERA <= '90'
			GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$palavra = "a90_$cod_fila";
		$$palavra = $total;
	}
	
	// B 90
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
			FROM TB_EVENTOS_DAC
			WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0'
			GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$palavra = "b90_$cod_fila";
		$$palavra = $total;
	}
	
	// C 90
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
			FROM TB_EVENTOS_DAC
			WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND = '0' AND TEMPO_ESPERA > '90'
			GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$palavra = "c90_$cod_fila";
		$$palavra = $total;
	}

	// TMA
	$soma_nsa45 = 0;
	$soma_nsa90 = 0;
	
	$query = $pdo->prepare("SELECT * FROM TB_FILAS");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
		$nome_variavel_sc = "nome_fila_$cod_fila";
		$$nome_variavel_sc = utf8_encode($row['desc_fila']);
	}
	
	$query = $pdo->prepare("SELECT COD_FILA, SUM(1) TOTAL, AVG(TEMPO_ATEND) TMA FROM
			(SELECT DISTINCT *
			FROM TB_EVENTOS_DAC
			WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0') AS A
			GROUP BY COD_FILA
			ORDER BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['COD_FILA']);
		$total = utf8_encode($row['TOTAL']);
		$tma = utf8_encode($row['TMA']);
		
		$imprimir_fila_atual = "nome_fila_$cod_fila"; //TROCAR COD_FILA PELO NOME
		if(isset($$imprimir_fila_atual)) $imp_fila = $$imprimir_fila_atual;
		else $imp_fila = '';
		
		$total_de_atendimentos = $total_de_atendimentos + $total;
		$quantidade_de_filas++;
		$soma_tma = $soma_tma + $tma;
		
		$imprime_cod_fila = number_format($cod_fila, 0, ',', '.');
		
		$a45 = "a45_$cod_fila";
		if (isset($$a45)) $aa45 = $$a45;
		$b45 = "b45_$cod_fila";
		if (isset($$b45)) $bb45 = $$b45;
		$c45 = "c45_$cod_fila";
		if (isset($$c45)) $cc45 = $$c45;
		
		$a90 = "a90_$cod_fila";
		if (isset($$a90)) $aa90 = $$a90;
		$b90 = "b90_$cod_fila";
		if (isset($$b90)) $bb90 = $$b90;
		$c90 = "c90_$cod_fila";
		if (isset($$c90)) $cc90 = $$c90;
		
		if (isset($$a45)) $a = $aa45;
		else $a = 0;
		if (isset($$b45)) $b = $bb45;
		else $b = 0;
		if (isset($$c45)) $c = $cc45;
		else $c = 0;
		if (($b + $c)>0) $nsa45 = ($a / ($b + $c))*100;
		else $nsa45 = 0;
		
		$soma_a45 = $soma_a45 + $a;
		$soma_b45 = $soma_b45 + $b;
		$soma_c45 = $soma_c45 + $c;
		
		if (isset($$a90)) $a = $aa90;
		else $a = 0;
		if (isset($$b90)) $b = $bb90;
		else $b = 0;
		if (isset($$c90)) $c = $cc90;
		else $c = 0;
		if (($b + $c)>0) $nsa90 = ($a / ($b + $c))*100;
		else $nsa90 = 0;
		
		$soma_a90 = $soma_a90 + $a;
		$soma_b90 = $soma_b90 + $b;
		$soma_c90 = $soma_c90 + $c;
		
		//$soma_nsa45 = $soma_nsa45+ $nsa45;
		//$soma_nsa90 = $soma_nsa90+ $nsa90;
		
		echo "<tr>";
		echo "<td>$imprime_cod_fila <i>$imp_fila</i></td>";
		echo "<td>$total</td>";
		echo "<td>$tma</td>";
		$imprime_nsa45 = number_format($nsa45, 2, ',', '.');
		echo "<td>$imprime_nsa45%</td>";
		$imprime_nsa90 = number_format($nsa90, 2, ',', '.');
		echo "<td>$imprime_nsa90%</td>";
		echo "</tr>";
	}
	$media_tma = $soma_tma / $quantidade_de_filas;
	$media_nsa45 = ($soma_a45 / ($soma_b45 + $soma_c45))*100;
	$media_nsa90 = ($soma_a90 / ($soma_b90 + $soma_c90))*100;
	$imprime_media_tma = number_format($media_tma, 0, ',', '.');

	$query = $pdo->prepare("SELECT AVG(TEMPO_ATEND) TMA FROM TB_EVENTOS_DAC
							WHERE DATA_HORA BETWEEN '$data_inicial' AND '$data_final' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0'");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++){
		$tma_geral = utf8_encode($row['TMA']);
	}
	$imprime_media_tma = number_format($tma_geral, 0, ',', '.');
	
	echo "<tr class='w3-indigo'>";
	echo "<td><b>TOTAL DE ATENDIMENTOS / MÉDIA TMA / MÉDIA NSA 45 / MÉDIA NSA 90</b></td>";
	echo "<td><b>$total_de_atendimentos</b></td>";
	echo "<td><b>$imprime_media_tma</b></td>";
	$imprime_media_nsa45 = number_format($media_nsa45, 2, ',', '.');
	echo "<td><b>$imprime_media_nsa45%</b></td>";
	$imprime_media_nsa90 = number_format($media_nsa90, 2, ',', '.');
	echo "<td><b>$imprime_media_nsa90%</b></td>";
	echo "</tr>";
	echo "</table>";
}
// PERGUNTA 5 - FIM 

// PERGUNTA 6 - INÍCIO
if($tipo_consulta == '06'){
	
$total_geral = 0;

$query = $pdo->prepare("select TOP $qtd_pesq * from tb_eventos_ura
where data_hora between '$data_inicial' and '$data_final' and cod_evento like '%$codigo_evento%'");
$query->execute();

echo '<br><br>';
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>CALLID</b></td>';
	echo '<td><b>DATA_HORA</b></td>';
	echo '<td><b>COD_EVENTO + DESCRIÇÃO</b></td>';
	echo '</tr>';
	
	
	for($i=0; $row = $query->fetch(); $i++){
		$callid = utf8_encode($row['callid']);
		$data_hora = utf8_encode($row['data_hora']);
		$cod_evento = utf8_encode($row['cod_evento']);
	
		echo "<tr>";
		echo "<td>$callid</td>";
		echo "<td>$data_hora</td>";
		
		echo "<td>";
		include "imprime_fluxo_ura.php";
		echo"</td>";
		
		echo "</tr>";
	}
	
}

echo "</table>";
echo "<br><br>";
// PERGUNTA 6 - FIM


// 08 - INÍCIO
if($tipo_consulta == '08'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br><br>";

	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>ID</b></td>';
	echo '<td><b>OPERADOR</b></td>';
	echo '<td><b>TOTAL DE ATENDIMENTOS</b></td>';
	echo '<td><b>TMA</b></td>';
	echo '</tr>';


	$query = $pdo->prepare("select id_operador, desc_operador, count (*) total_atendimentos, avg (tempo_atend) tma_operador
							from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final' and tempo_atend > 0 and desc_operador is not null
							group by id_operador, desc_operador
							order by tma_operador desc");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$id_operador = utf8_encode($row['id_operador']);
		$desc_operador = utf8_encode($row['desc_operador']);
		$total_atendimentos = utf8_encode($row['total_atendimentos']);
		$tma_operador = utf8_encode($row['tma_operador']);

		
		
		echo '<tr>';
		echo "<td>$id_operador</td>";
		echo '<td><b>';
		//echo "<a class='w3-text-indigo' href= \"consulta_categorizacao.php?id=$id&qual_pesquisa=$qual_pesquisa&data_inicial=$data_inicial&data_final=$data_final\" target=\"_blank\">$id</a>";
		echo "<div class=\"w3-dropdown-hover\">
		<u class='w3-text-indigo'>$desc_operador</u>
		<div class=\"w3-dropdown-content w3-indigo w3-round w3-card-4\">
		<a href= \"list_filas_operador.php?data_inicial=$data_inicial&data_final=$data_final&desc_operador=$desc_operador\" target=\"_blank\">Listar Filas</a>
		</div>
		</div>";
		echo '</b></td>';
		
		echo "<td>$total_atendimentos</td>";
		echo "<td>$tma_operador</td>";
		echo '</tr>';
	}

	echo "</div>";
	echo "</table>";
	echo "<br><br>";
}
// 08 - FIM

// 09 - INÍCIO
if($tipo_consulta == '09'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br><br>";
	
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>SUPERVISOR</b></td>';
	echo '<td><b>TOTAL DE ATENDIMENTOS</b></td>';
	echo '<td><b>TMA</b></td>';
	echo '</tr>';
	

	$query = $pdo->prepare("select Y.SUPERVISOR, count (*) total_atendimentos, avg (tempo_atend) tma_supervisor
							from tb_eventos_dac as X
							inner join tb_colaboradores_indra as Y
							on X.desc_operador = Y.NOME
							where data_hora between '$data_inicial' and '$data_final' and tempo_atend > 0 and desc_operador is not null
							group by Y.SUPERVISOR
							order by tma_supervisor desc");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		
		
		$supervisor = utf8_encode($row['SUPERVISOR']);
		$total_atendimentos = utf8_encode($row['total_atendimentos']);
		$tma_supervisor = utf8_encode($row['tma_supervisor']);
			
		echo '<tr>';
		echo '<td><b>';
		//echo "<a class='w3-text-indigo' href= \"consulta_categorizacao.php?id=$id&qual_pesquisa=$qual_pesquisa&data_inicial=$data_inicial&data_final=$data_final\" target=\"_blank\">$id</a>";
		echo "<div class=\"w3-dropdown-hover\">
		<u class='w3-text-indigo'>$supervisor</u>
		<div class=\"w3-dropdown-content w3-indigo w3-round w3-card-4\">
		<a href= \"list_filas.php?data_inicial=$data_inicial&data_final=$data_final&supervisor=$supervisor\" target=\"_blank\">Listar Filas</a>
		<a href= \"list_op_vinculados.php?data_inicial=$data_inicial&data_final=$data_final&supervisor=$supervisor\" target=\"_blank\">Listar Operadores Vinculados</a>
		</div>
		</div>";
		echo '</b></td>';
		echo "<td>$total_atendimentos</td>";
		echo "<td>$tma_supervisor</td>";
		echo '</tr>';
	}
	
	echo "</div>";
	echo "</table>";
	echo "<br><br>";
}
// 09 - FIM

// 10 - INÍCIO
if($tipo_consulta == '10'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br><br>";

	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>OPERADOR</b></td>';
	echo '<td><b>ID</b></td>';
	echo '<td><b>TOTAL ATENDIDAS</b></td>';
	echo '<td><b>TOTAL ATENDIDAS SEM CATEGORIZAÇÃO</b></td>';
	echo '<td class="w3-right"><b>PERCENTUAL DE ATENDIMENTOS NÃO CATEGORIZADOS</b></td>';
	echo '</tr>';


	$query = $pdo->prepare("select U.id_operador ID, U.NOME NOME,  P.total_atendidas TOTAL_ATENDIDAS, U.total_nao_categorizadas TOTAL_NAO_CATEGORIZADAS, (cast(U.total_nao_categorizadas as float) / cast(P.total_atendidas as float) * 100) PERCENTUAL_DE_MARCACAO
							from (select X.id_operador, X.NOME, count(X.callid) as total_nao_categorizadas
							from (select a.callid, a.id_operador, b.NOME
							from tb_eventos_DAC as a
							inner join tb_colaboradores_indra as b
							on a.id_operador = b.LOGIN_DAC
							where a.data_hora between '$data_inicial' and '$data_final' and a.tempo_atend > 0) as X
							left join (select a.callid, b.login_dac, b.NOME
							from tb_log_categorizacao as a
							inner join tb_colaboradores_indra as b
							on a.login_front = b.MATRICULA
							where a.data_hora between '$data_inicial' and '$data_final') as Y
							on (X.callid = Y.callid) and (X.id_operador = Y.login_dac)
							where (Y.callid is null) and (Y.login_dac is null)
							group by X.id_operador, X.NOME
							HAVING count (X.callid) > 2
							) as U
							inner join (select id_operador, count(*) total_atendidas from tb_eventos_dac as m
							where m.data_hora between '$data_inicial' and '$data_final' and m.tempo_atend > 0
							group by m.id_operador) as P
							on U.id_operador = P.id_operador
							order by percentual_de_marcacao desc");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$id_operador = utf8_encode($row['ID']);
		$nome_operador = utf8_encode($row['NOME']);
		$total_atendidas = utf8_encode($row['TOTAL_ATENDIDAS']);
		$total_nao_categorizadas = utf8_encode($row['TOTAL_NAO_CATEGORIZADAS']);		
		$percentual_de_marcacao = utf8_encode($row['PERCENTUAL_DE_MARCACAO']);

		echo '<tr>';
		echo "<td>$nome_operador</td>";
		echo '<td><b>';
		echo "<div class=\"w3-dropdown-hover\">
		<u class='w3-text-indigo'>$id_operador</u>
		<div class=\"w3-dropdown-content w3-indigo w3-round w3-card-4\">
		<a href= \"list_atend_nao_categ.php?data_inicial=$data_inicial&data_final=$data_final&id_operador=$id_operador\" target=\"_blank\">Rastrear Atendimentos</a>
		</div>
		</div>";
		echo '</b></td>';
		echo "<td>$total_atendidas</td>";
		echo "<td>$total_nao_categorizadas</td>";
			$percentual_de_marcacao = number_format($percentual_de_marcacao, 2, ',', '.');
		echo "<td class='w3-right'>$percentual_de_marcacao%</td>";
		echo '</tr>';
	}

	echo "</div>";
	echo "</table>";
	echo "<br><br>";
}
// 10 - FIM

// 11 - INÍCIO
if($tipo_consulta == '11')
{
 
    list($usec, $sec) = explode(' ', microtime());
    $script_start = (float) $sec + (float) $usec; 
      
    $txt_cab = ($cb_ilha == '00') ? "Data: $data_inicial_texto até $data_final_texto - Hora: $hora_inicial às $hora_final":"Data: $data_inicial_texto até $data_final_texto - Hora: $hora_inicial às $hora_final e Ilha: $cb_ilha";
    echo '<div class="w3-margin w3-tiny w3-center">';
    echo "<b>Filtro: </b><i>$txt_cab</i>";
    echo "<br>";
    echo '<br><br>';
    
    echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
    echo '<tr class="w3-indigo w3-tiny">';
    echo '<td><b>INTERVALO</b></td>';
    echo '<td><b>RECEBIDAS</b></td>';
    echo '<td><b>TMA (Segundos)</b></td>';
    echo '<td><b>ATENDENTES</b></td>';
    echo '</tr>';
    
    
    set_time_limit(9999);
    ini_set('max_execution_time', 9999);
    $sql1 = "set nocount on; declare @T TABLE(rdesc_ilha varchar(50),rfilas varchar(250),rintervalo nvarchar(100),rtotal int,rtma int,ratendentes int,rindice int,rtotal_geral int,rtma_geral int,ratendentes_geral int,rtotal_ilha int,rtma_ilha int,ratendentes_ilha int)";
    
    $sql2 = ($cb_ilha == '00') ? " insert @T exec sp_Consulta_TMA_ILHA '$data_inicial','$data_final','$hora_inicial','$hora_final'":" insert @T exec sp_Consulta_TMA_ILHA '$data_inicial','$data_final_pre','$hora_inicial','$hora_final','$cb_ilha'";
    
    $sql3 = " Select rdesc_ilha,rfilas,rintervalo,rtotal,rtma,ratendentes,rindice,rtotal_geral,	rtma_geral,	ratendentes_geral,	rtotal_ilha,	rtma_ilha, ratendentes_ilha from @T ";
	
	
	
    $old_desc_ilha = '';
    $desc_ilha = '';
    
    
    //totalizadores da ilha
    $ti_total = 0;
    $ti_tma = 0;
    $ti_atendentes = 0;
    
    $ti_total = 0;
    $ti_tma = 0;
    $ti_atendentes = 0;
    
    //totalizadores da data 
    $td_total = 0;
    $td_tma = 0;
    $td_atendentes = 0;


    
    $sql = $sql1.$sql2.$sql3; 

    $sql = ($cb_ilha == '00') ? " exec dbo.teste '$data_inicial','$data_final_pre','$hora_inicial','$hora_final'":" exec dbo.teste '$data_inicial','$data_final_pre','$hora_inicial','$hora_final','$cb_ilha'";
	//print_r($sql);
    $query = $pdo->query($sql);
    //$query->execute();

	//$result = $query->fetchAll();
	//print_r($result);
	
    foreach ($query as $row)    
    {

	if (!is_null($row['ErrorMessage'])) {
		die("Erro ao Executar SQL: ". $row['ErrorMessage']);
	}
 
        $desc_ilha = utf8_encode($row['rdesc_ilha']); 
        $filas =  utf8_encode($row['rfilas']);
                         
        if ($desc_ilha != $old_desc_ilha) 
        {   
            if ($old_desc_ilha != '') 
            {           
                echo '<tr class="w3-light-grey">';
                echo "<td><i>Total Ilha '$old_desc_ilha'</i></td>";             
                echo "<td><b>$ti_total</b></td>";
                echo "<td><b>$ti_tma</b></td>";
                echo "<td><b>$ti_atendentes</b></td>";
                echo '</tr>';                
            }   
            echo '<tr class="w3-light-grey">';
            echo "<td><b>$desc_ilha Filas ($filas)</b></td>"; 
            echo "</tr>";
            $old_desc_ilha = $desc_ilha;
        }
        
        
        
        $intervalo = utf8_encode($row['rintervalo']);
        $total = number_format($row['rtotal'],0, ',', '');
        $tma = number_format($row['rtma'],0, ',', ' ');
        $atendentes = number_format($row['ratendentes'],0, ',', '');
        $indice = utf8_encode($row['rindice']);
        $sql = utf8_encode($row['rsql']);
        $ti_total = number_format($row['rtotal_ilha'],0, ',', '');
        $ti_tma = number_format($row['rtma_ilha'],0, ',', '');
        $ti_atendentes = number_format($row['ratendentes_ilha'],0, ',', '');     
         
        echo "<tr>";
        echo "<td><i>$intervalo</i></td>";
        echo "<td>$total</td>";
        echo "<td>$tma</td>";
        echo "<td>$atendentes</td>";
        echo "</tr>";
        
          //total geral
        $td_total = number_format($row['rtotal_geral'],0, ',', '');
        $td_tma = number_format($row['rtma_geral'],0, ',', '');
        $td_atendentes = number_format($row['ratendentes_geral'],0, ',', '');
    }    
   
    
    //totalizadores finais
    //ilha final              
    echo '<tr class="w3-light-grey">';
    echo "<td><i>Total Ilha '$old_desc_ilha'</i></td>";             
    echo "<td><b>$ti_total</b></td>";
    echo "<td><b>$ti_tma</b></td>";
    echo "<td><b>$ti_atendentes</b></td>";
    echo '</tr>';
    
  
        
    echo '<tr class="w3-light-grey">';
    echo "<td><b>Total Final</b></td>";
    echo "<td><b>$td_total</b></td>";
    echo "<td><b>$td_tma</b></td>";
    echo "<td><b>$td_atendentes</b></td>";
    echo '</tr>';
    
    // Terminamos o "contador" e exibimos
    list($usec, $sec) = explode(' ', microtime());
    $script_end = (float) $sec + (float) $usec;
    $elapsed_time = round($script_end - $script_start, 5);
   
    $elapsed_time = intval($elapsed_time);
    if ($elapsed_time >= 60)
    {
        $minutos = intval($elapsed_time/60);
        $segundos = ((($elapsed_time/60) - $minutos)*60);
    }
    else
    {
        $minutos = 0;
        $segundos = $elapsed_time;
    }    
    
    
    if ($minutos == 1)
      $texto_tempo = "$minutos minuto ";
    else if ($minutos > 1) 
      $texto_tempo = "$minutos minutos ";
    else
      $texto_tempo = "";
    
    if (($texto_tempo != "") and ($segundos >0))
      $texto_tempo = $texto_tempo." e ";
    
    if ($segundos == 1)
      $texto_tempo = $texto_tempo."$segundos segundo";
    else if ($segundos > 1)  
      $texto_tempo = $texto_tempo."$segundos segundos"; 
   
    echo '<div class="w3-margin w3-tiny w3-center">';
    echo "<b>Tempo de Execução: </b><i>$texto_tempo</i><br>";
    echo '<br><br>';
}
// 11 - INÍCIO

// 12 - INÍCIO
if($tipo_consulta == '12'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br><br>";

	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>SUPERVISOR</b></td>';
	echo '<td><b>TOTAL ATENDIDAS</b></td>';
	echo '<td><b>TOTAL ATENDIDAS SEM CATEGORIZAÇÃO</b></td>';
	echo '<td class="w3-right"><b>PERCENTUAL DE ATENDIMENTOS NÃO CATEGORIZADOS</b></td>';
	echo '</tr>';


	$query = $pdo->prepare("SELECT M.SUPERVISOR SUPERVISOR, SUM(N.total_atendidas) TOTAL_ATENDIDAS , SUM(N.total_nao_categorizadas) TOTAL_NAO_CATEGORIZADAS, (cast(SUM(N.total_nao_categorizadas) as float) / cast(SUM(N.total_atendidas) as float) * 100) percentual_de_marcacao
							FROM (select U.id_operador, U.NOME,  P.total_atendidas, U.total_nao_categorizadas, (cast(U.total_nao_categorizadas as float) / cast(P.total_atendidas as float) * 100) percentual_de_marcacao
							from (select X.id_operador, X.NOME, count(X.callid) as total_nao_categorizadas
							from (select a.callid, a.id_operador, b.NOME
							from tb_eventos_DAC as a
							inner join tb_colaboradores_indra as b
							on a.id_operador = b.LOGIN_DAC
							where a.data_hora between '$data_inicial' and '$data_final' and a.tempo_atend > 0) as X
							left join (select a.callid, b.login_dac, b.NOME
							from tb_log_categorizacao as a
							inner join tb_colaboradores_indra as b
							on a.login_front = b.MATRICULA
							where a.data_hora between '$data_inicial' and '$data_final') as Y
							on (X.callid = Y.callid) and (X.id_operador = Y.login_dac)
							where (Y.callid is null) and (Y.login_dac is null)
							group by X.id_operador, X.NOME
							HAVING count (X.callid) > 2) as U
							inner join (select id_operador, count(*) total_atendidas from tb_eventos_dac as m
							where m.data_hora between '$data_inicial' and '$data_final' and m.tempo_atend > 0
							group by m.id_operador) as P
							on U.id_operador = P.id_operador) AS N
							inner join tb_colaboradores_indra as M
							on N.NOME = M.NOME
							group by SUPERVISOR
							order by percentual_de_marcacao desc");
							
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$supervisor = utf8_encode($row['SUPERVISOR']);
		$atendidas = utf8_encode($row['TOTAL_ATENDIDAS']);
		$nao_categorizadas = utf8_encode($row['TOTAL_NAO_CATEGORIZADAS']);
		$percentual_de_marcacao = utf8_encode($row['percentual_de_marcacao']);		

		echo '<tr>';
		echo '<td><b>';		
		echo "<div class=\"w3-dropdown-hover\">
		<u class='w3-text-indigo'>$supervisor</u>
		<div class=\"w3-dropdown-content w3-indigo w3-round w3-card-4\">
		<a href= \"list_oper_do_supervisor.php?data_inicial=$data_inicial&data_final=$data_final&supervisor=$supervisor\" target=\"_blank\">Listar Operadores Vinculados</a>
		</div>
		</div>";		
		echo '</b></td>';
		echo "<td>$atendidas</td>";
		echo "<td>$nao_categorizadas</td>";
			$percentual_de_marcacao = number_format($percentual_de_marcacao, 2, ',', '.');
		echo "<td class='w3-right'>$percentual_de_marcacao%</td>";
		echo '</tr>';
	}

	echo "</div>";
	echo "</table>";
	echo "<br><br>";
}
// 12 - FIM

//13 - INÍCIO

// ZERA VARIÁVEIS - INÍCIO
if($tipo_consulta == '13'){
	for($cont=1;$cont<=150;$cont++){
		
		$nome_fila = "fila_$cont";
		$$nome_fila = 0;	
		
		// A(tb_fila_acumulado)
			//cod_fila
			$nome_a_cod_fila = "a_cod_fila_$cont";
			$$nome_a_cod_fila = 0;

			//recebidas
			$nome_a_recebidas = "a_recebidas_$cont";
			$$nome_a_recebidas = 0;

			//atendidas
			$nome_a_atendidas = "a_atendidas_$cont";
			$$nome_a_atendidas = 0;

			//abandonadas
			$nome_a_abandonadas = "a_abandonadas_$cont";
			$$nome_a_abandonadas = 0;

			//tempo_atendimento
			$nome_a_tempo_atendimento = "a_tempo_atendimento_$cont";
			$$nome_a_tempo_atendimento = 0;

			//tempo_espera
			$nome_a_tempo_espera = "a_tempo_espera_$cont";
			$$nome_a_tempo_espera = 0;

			//atendidas_1
			$nome_a_atendidas_1 = "a_atendidas_1_$cont";
			$$nome_a_atendidas_1 = 0;

			//atendidas_2
			$nome_a_atendidas_2 = "a_atendidas_2_$cont";
			$$nome_a_atendidas_2 = 0;

			//atendidas_3
			$nome_a_atendidas_3 = "a_atendidas_3_$cont";
			$$nome_a_atendidas_3 = 0;

			//abandonadas_1
			$nome_a_abandonadas_1 = "a_abandonadas_1_$cont";
			$$nome_a_abandonadas_1 = 0;

			//abandonadas_2
			$nome_a_abandonadas_2 = "a_abandonadas_2_$cont";
			$$nome_a_abandonadas_2 = 0;

			//abandonadas_3
			$nome_a_abandonadas_3 = "a_abandonadas_3_$cont";
			$$nome_a_abandonadas_3 = 0;

			//nsa45
			$nome_a_nsa45 = "a_nsa45_$cont";
			$$nome_a_nsa45 = 0;

			//nsa90
			$nome_a_nsa90 = "a_nsa90_$cont";
			$$nome_a_nsa90 = 0;

		//B(tb_eventos_DAC)
			//cod_fila
			$nome_b_cod_fila = "b_cod_fila_$cont";
			$$nome_b_cod_fila = 0;

			//recebidas
			$nome_b_recebidas = "b_recebidas_$cont";
			$$nome_b_recebidas = 0;

			//atendidas
			$nome_b_atendidas = "b_atendidas_$cont";
			$$nome_b_atendidas = 0;

			//abandonadas
			$nome_b_abandonadas = "b_abandonadas_$cont";
			$$nome_b_abandonadas = 0;

			//tempo_atendimento
			$nome_b_tempo_atendimento = "b_tempo_atendimento_$cont";
			$$nome_b_tempo_atendimento = 0;

			//tempo_espera
			$nome_b_tempo_espera = "b_tempo_espera_$cont";
			$$nome_b_tempo_espera = 0;

			//atendidas_1
			$nome_b_atendidas_1 = "b_atendidas_1_$cont";
			$$nome_b_atendidas_1 = 0;

			//atendidas_2
			$nome_b_atendidas_2 = "b_atendidas_2_$cont";
			$$nome_b_atendidas_2 = 0;

			//atendidas_3
			$nome_b_atendidas_3 = "b_atendidas_3_$cont";
			$$nome_b_atendidas_3 = 0;

			//abandonadas_1
			$nome_b_abandonadas_1 = "b_abandonadas_1_$cont";
			$$nome_b_abandonadas_1 = 0;

			//abandonadas_2
			$nome_b_abandonadas_2 = "b_abandonadas_2_$cont";
			$$nome_b_abandonadas_2 = 0;

			//abandonadas_3
			$nome_b_abandonadas_3 = "b_abandonadas_3_$cont";
			$$nome_b_abandonadas_3 = 0;

			//nsa45
			$nome_b_nsa45 = "b_nsa45_$cont";
			$$nome_b_nsa45 = 0;

			//nsa90
			$nome_b_nsa90 = "b_nsa90_$cont";
			$$nome_b_nsa90 = 0;	
	}
	// ZERA VARIÁVEIS - FIM


	// CONSULTA TB_FILA_ACUMULADO - INÍCIO
	$query = $pdo->prepare("select cod_fila, sum(recebidas) recebidas, sum(atendidas) atendidas, sum(abandonadas) abandonadas, sum(tempo_atendimento) tempo_atendimento, sum(tempo_espera) tempo_espera,
	sum(atendidas_1) atendidas_1, sum(atendidas_2) atendidas_2, sum(atendidas_3) atendidas_3, sum(abandonadas_1) abandonadas_1, sum(abandonadas_2) abandonadas_2, sum(abandonadas_3) abandonadas_3,
	( cast(sum(atendidas_1) as float) + cast(sum(atendidas_2) as float) )/(cast(sum(atendidas) as float) + (cast(sum(abandonadas) as float) - (cast (sum(abandonadas_1) as float) + cast (sum(abandonadas_2) as float)))) nsa45,
	( cast(sum(atendidas_1) as float) + cast(sum(atendidas_2) as float) + cast(sum(atendidas_3) as float) )/(cast(sum(atendidas) as float) + (cast(sum(abandonadas) as float) - (cast (sum(abandonadas_1) as float) + cast (sum(abandonadas_2) as float) + cast (sum(abandonadas_3) as float)))) nsa90
	from tb_fila_acumulado
	where data = '$data_inicial' and recebidas > 0
	group by cod_fila");
							
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
		
			//recebidas
			$nome_a_recebidas = "a_recebidas_$cod_fila";
			$$nome_a_recebidas = utf8_encode($row['recebidas']);

			//atendidas
			$nome_a_atendidas = "a_atendidas_$cod_fila";
			$$nome_a_atendidas = utf8_encode($row['atendidas']);

			//abandonadas
			$nome_a_abandonadas = "a_abandonadas_$cod_fila";
			$$nome_a_abandonadas = utf8_encode($row['abandonadas']);

			//tempo_atendimento
			$nome_a_tempo_atendimento = "a_tempo_atendimento_$cod_fila";
			$$nome_a_tempo_atendimento = utf8_encode($row['tempo_atendimento']);

			//tempo_espera
			$nome_a_tempo_espera = "a_tempo_espera_$cod_fila";
			$$nome_a_tempo_espera = utf8_encode($row['tempo_espera']);

			//atendidas_1
			$nome_a_atendidas_1 = "a_atendidas_1_$cod_fila";
			$$nome_a_atendidas_1 = utf8_encode($row['atendidas_1']);

			//atendidas_2
			$nome_a_atendidas_2 = "a_atendidas_2_$cod_fila";
			$$nome_a_atendidas_2 = utf8_encode($row['atendidas_2']);

			//atendidas_3
			$nome_a_atendidas_3 = "a_atendidas_3_$cod_fila";
			$$nome_a_atendidas_3 = utf8_encode($row['atendidas_3']);

			//abandonadas_1
			$nome_a_abandonadas_1 = "a_abandonadas_1_$cod_fila";
			$$nome_a_abandonadas_1 = utf8_encode($row['abandonadas_1']);

			//abandonadas_2
			$nome_a_abandonadas_2 = "a_abandonadas_2_$cod_fila";
			$$nome_a_abandonadas_2 = utf8_encode($row['abandonadas_2']);

			//abandonadas_3
			$nome_a_abandonadas_3 = "a_abandonadas_3_$cod_fila";
			$$nome_a_abandonadas_3 = utf8_encode($row['abandonadas_3']);

			//nsa45
			$nome_a_nsa45 = "a_nsa45_$cod_fila";
			$$nome_a_nsa45 = utf8_encode($row['nsa45']);

			//nsa90
			$nome_a_nsa90 = "a_nsa90_$cod_fila";
			$$nome_a_nsa90 = utf8_encode($row['nsa90']);
	}
	// CONSULTA TB_FILA_ACUMULADO - FIM


	// CONSULTA TB_EVENTOS_DAC (atendidas) - INÍCIO
	$query = $pdo->prepare("select cod_fila, count (*) atendidas from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_atend > 0
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_atendidas = "b_atendidas_$cod_fila";
			$$nome_b_atendidas = utf8_encode($row['atendidas']);
	}
	// CONSULTA TB_EVENTOS_DAC (atendidas) - FIM

	// CONSULTA TB_EVENTOS_DAC (abandonadas) - INÍCIO
	$query = $pdo->prepare("select cod_fila, count (*) abandonadas from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_espera > 0 and tempo_atend = 0
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_abandonadas = "b_abandonadas_$cod_fila";
			$$nome_b_abandonadas = utf8_encode($row['abandonadas']);
	}
	// CONSULTA TB_EVENTOS_DAC (abandonadas) - FIM

	// CONSULTA TB_EVENTOS_DAC (tempo_atendimento) - INÍCIO
	$query = $pdo->prepare("select cod_fila, sum(tempo_atend) tempo_atendimento from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_atend > 0
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_tempo_atendimento = "b_tempo_atendimento_$cod_fila";
			$$nome_b_tempo_atendimento = utf8_encode($row['tempo_atendimento']);
	}
	// CONSULTA TB_EVENTOS_DAC (tempo_atendimento) - FIM

	// CONSULTA TB_EVENTOS_DAC (tempo_espera) - INÍCIO
	$query = $pdo->prepare("select cod_fila, sum(tempo_espera) tempo_espera from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_espera > 0
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_tempo_espera = "b_tempo_espera_$cod_fila";
			$$nome_b_tempo_espera = utf8_encode($row['tempo_espera']);
	}
	// CONSULTA TB_EVENTOS_DAC (tempo_espera) - FIM

	// CONSULTA TB_EVENTOS_DAC (atendidas_1) - INÍCIO
	$query = $pdo->prepare("select cod_fila, count (*) atendidas_1 from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_atend > 0 and tempo_espera <= 10
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_atendidas_1 = "b_atendidas_1_$cod_fila";
			$$nome_b_atendidas_1 = utf8_encode($row['atendidas_1']);
	}
	// CONSULTA TB_EVENTOS_DAC (atendidas_1) - FIM

	// CONSULTA TB_EVENTOS_DAC (atendidas_2) - INÍCIO
	$query = $pdo->prepare("select cod_fila, count (*) atendidas_2 from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_atend > 0 and tempo_espera > 10 and tempo_espera <= 45
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_atendidas_2 = "b_atendidas_2_$cod_fila";
			$$nome_b_atendidas_2 = utf8_encode($row['atendidas_2']);
	}
	// CONSULTA TB_EVENTOS_DAC (atendidas_2) - FIM

	// CONSULTA TB_EVENTOS_DAC (atendidas_3) - INÍCIO
	$query = $pdo->prepare("select cod_fila, count (*) atendidas_3 from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_atend > 0 and tempo_espera > 45 and tempo_espera <= 90
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_atendidas_3 = "b_atendidas_3_$cod_fila";
			$$nome_b_atendidas_3 = utf8_encode($row['atendidas_3']);
	}
	// CONSULTA TB_EVENTOS_DAC (atendidas_3) - FIM

	// CONSULTA TB_EVENTOS_DAC (abandonadas_1) - INÍCIO
	$query = $pdo->prepare("select cod_fila, count (*) abandonadas_1 from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_espera > 0 and tempo_espera <= 10 and tempo_atend = 0
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_abandonadas_1 = "b_abandonadas_1_$cod_fila";
			$$nome_b_abandonadas_1 = utf8_encode($row['abandonadas_1']);
	}
	// CONSULTA TB_EVENTOS_DAC (abandonadas_1) - FIM

	// CONSULTA TB_EVENTOS_DAC (abandonadas_2) - INÍCIO
	$query = $pdo->prepare("select cod_fila, count (*) abandonadas_2 from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_espera > 10 and tempo_espera <= 45 and tempo_atend = 0
	group by cod_fila
	order by cod_fila
	");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_abandonadas_2 = "b_abandonadas_2_$cod_fila";
			$$nome_b_abandonadas_2 = utf8_encode($row['abandonadas_2']);
	}
	// CONSULTA TB_EVENTOS_DAC (abandonadas_2) - FIM

	// CONSULTA TB_EVENTOS_DAC (abandonadas_3) - INÍCIO
	$query = $pdo->prepare("select cod_fila, count (*) abandonadas_3 from tb_eventos_dac
	where data_hora between '$data_inicial' and '$data_final' and tempo_espera > 45 and tempo_espera <= 90 and tempo_atend = 0
	group by cod_fila
	order by cod_fila");						
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
			//*****
			$nome_b_abandonadas_3 = "b_abandonadas_3_$cod_fila";
			$$nome_b_abandonadas_3 = utf8_encode($row['abandonadas_3']);
	}
	// CONSULTA TB_EVENTOS_DAC (abandonadas_3) - FIM

	// DEFINE B RECEBIDAS (ATENDIDAS + ABANDONADAS) - INÍCIO
	for($cont=1;$cont<=150;$cont++){
		$nome_b_atendidas = "b_atendidas_$cont";
		$nome_b_abandonadas = "b_abandonadas_$cont";
		$nome_b_recebidas = "b_recebidas_$cont";
		
		$$nome_b_recebidas = $$nome_b_atendidas + $$nome_b_abandonadas;
	}
	// DEFINE B RECEBIDAS (ATENDIDAS + ABANDONADAS) - FIM

	// DEFINE B NSA45 E B NSA90 - INÍCIO
	for($cont=1;$cont<=150;$cont++){
		$nome_b_recebidas = "b_recebidas_$cont";
		$recebidas = $$nome_b_recebidas;
		
		if($recebidas>0){
			$nome_b_atendidas = "b_atendidas_$cont";
			$nome_b_abandonadas = "b_abandonadas_$cont";
			$nome_b_atendidas_1 = "b_atendidas_1_$cont";
			$nome_b_atendidas_2 = "b_atendidas_2_$cont";
			$nome_b_atendidas_3 = "b_atendidas_3_$cont";
			$nome_b_abandonadas_1 = "b_abandonadas_1_$cont";
			$nome_b_abandonadas_2 = "b_abandonadas_2_$cont";
			$nome_b_abandonadas_3 = "b_abandonadas_3_$cont";

			$atendidas = $$nome_b_atendidas;
			$abandonadas = $$nome_b_abandonadas;
			$atendidas_1 = $$nome_b_atendidas_1;
			$atendidas_2 = $$nome_b_atendidas_2;
			$atendidas_3 = $$nome_b_atendidas_3;
			$abandonadas_1 = $$nome_b_abandonadas_1;
			$abandonadas_2 = $$nome_b_abandonadas_2;
			$abandonadas_3 = $$nome_b_abandonadas_3;
			
			$$nome_b_nsa45 = (($atendidas_1 + $atendidas_2) / ($atendidas + ($abandonadas - ($abandonadas_1 + $abandonadas_2))));
			$$nome_b_nsa90 = (($atendidas_1 + $atendidas_2 + $atendidas_3) / ($atendidas + ($abandonadas - ($abandonadas_1 + $abandonadas_2 + $abandonadas_3))));
		}	
	}
	// DEFINE B NSA45 E B NSA90 - FIM

	echo '<div class="w3-margin w3-tiny w3-center">';
		echo "<b>A:</b> tb_log_categorizacao";
		echo "<br><b>B:</b> tb_eventos_DAC";
		echo "<br><br>";
		echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
		echo "<br><br>";

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>cod_fila</b></td>';
		echo '<td><b>recebidas(A)</b></td>';
		echo '<td><b>recebidas(B)</b></td>';
		echo '<td><b>atendidas(A)</b></td>';
		echo '<td><b>atendidas(B)</b></td>';
		echo '<td><b>abandonadas(A)</b></td>';
		echo '<td><b>abandonadas(B)</b></td>';	
		echo '<td><b>tempo_atendimento(A)</b></td>';
		echo '<td><b>tempo_atendimento(B)</b></td>';	
		echo '<td><b>tempo_espera(A)</b></td>';
		echo '<td><b>tempo_espera(B)</b></td>';	
		echo '<td><b>atendidas_1(A)</b></td>';
		echo '<td><b>atendidas_1(B)</b></td>';
		echo '<td><b>atendidas_2(A)</b></td>';
		echo '<td><b>atendidas_2(B)</b></td>';
		echo '<td><b>atendidas_3(A)</b></td>';
		echo '<td><b>atendidas_3(B)</b></td>';
		echo '<td><b>abandonadas_1(A)</b></td>';
		echo '<td><b>abandonadas_1(B)</b></td>';
		echo '<td><b>abandonadas_2(A)</b></td>';
		echo '<td><b>abandonadas_2(B)</b></td>';
		echo '<td><b>abandonadas_3(A)</b></td>';
		echo '<td><b>abandonadas_3(B)</b></td>';
		echo '<td><b>nsa45(A)</b></td>';
		echo '<td><b>nsa45(B)</b></td>';
		echo '<td><b>nsa90(A)</b></td>';
		echo '<td><b>nsa90(B)</b></td>';
		
		echo '</tr>';

	for($cont=1;$cont<=150;$cont++){
		$nome_a_recebidas = "a_recebidas_$cont";
		$recebidas_a = $$nome_a_recebidas;
		
		$nome_b_recebidas = "b_recebidas_$cont";
		$recebidas_b = $$nome_b_recebidas;
		
		if(($recebidas_a > 0)||($recebidas_b > 0)){
			echo '<tr>';
			echo "<td>$cont</td>";
			
			$nome_a_recebidas = "a_recebidas_$cont";
			$imprime = $$nome_a_recebidas;
			echo "<td>$imprime</td>";
			$nome_b_recebidas = "b_recebidas_$cont";
			$imprime = $$nome_b_recebidas;
			echo "<td>$imprime</td>";
			
			$nome_a_atendidas = "a_atendidas_$cont";
			$imprime = $$nome_a_atendidas;
			echo "<td>$imprime</td>";
			$nome_b_atendidas = "b_atendidas_$cont";
			$imprime = $$nome_b_atendidas;
			echo "<td>$imprime</td>";
			
			$nome_a_abandonadas = "a_abandonadas_$cont";
			$imprime = $$nome_a_abandonadas;
			echo "<td>$imprime</td>";
			$nome_b_abandonadas = "b_abandonadas_$cont";
			$imprime = $$nome_b_abandonadas;
			echo "<td>$imprime</td>";
			
			$nome_a_tempo_atendimento = "a_tempo_atendimento_$cont";
			$imprime = $$nome_a_tempo_atendimento;
			echo "<td>$imprime</td>";
			$nome_b_tempo_atendimento = "b_tempo_atendimento_$cont";
			$imprime = $$nome_b_tempo_atendimento;
			echo "<td>$imprime</td>";
			
			$nome_a_tempo_atendimento = "a_tempo_atendimento_$cont";
			$imprime = $$nome_a_tempo_atendimento;
			echo "<td>$imprime</td>";
			$nome_b_tempo_atendimento = "b_tempo_atendimento_$cont";
			$imprime = $$nome_b_tempo_atendimento;
			echo "<td>$imprime</td>";
			
			$nome_a_tempo_atendimento = "a_tempo_atendimento_$cont";
			$imprime = $$nome_a_tempo_atendimento;
			echo "<td>$imprime</td>";
			$nome_b_tempo_atendimento = "b_tempo_atendimento_$cont";
			$imprime = $$nome_b_tempo_atendimento;
			echo "<td>$imprime</td>";
			
			$nome_a_tempo_atendimento = "a_tempo_atendimento_$cont";
			$imprime = $$nome_a_tempo_atendimento;
			echo "<td>$imprime</td>";
			$nome_b_tempo_atendimento = "b_tempo_atendimento_$cont";
			$imprime = $$nome_b_tempo_atendimento;
			echo "<td>$imprime</td>";
			
			$nome_a_tempo_atendimento = "a_tempo_atendimento_$cont";
			$imprime = $$nome_a_tempo_atendimento;
			echo "<td>$imprime</td>";
			$nome_b_tempo_atendimento = "b_tempo_atendimento_$cont";
			$imprime = $$nome_b_tempo_atendimento;
			echo "<td>$imprime</td>";
			
			$nome_a_tempo_atendimento = "a_tempo_atendimento_$cont";
			$imprime = $$nome_a_tempo_atendimento;
			echo "<td>$imprime</td>";
			$nome_b_tempo_atendimento = "b_tempo_atendimento_$cont";
			$imprime = $$nome_b_tempo_atendimento;
			echo "<td>$imprime</td>";
			
			$nome_a_abandonadas_2 = "a_abandonadas_2_$cont";
			$imprime = $$nome_a_abandonadas_2;
			echo "<td>$imprime</td>";
			$nome_b_abandonadas_2 = "b_abandonadas_2_$cont";
			$imprime = $$nome_b_abandonadas_2;
			echo "<td>$imprime</td>";
			
			$nome_a_abandonadas_3 = "a_abandonadas_3_$cont";
			$imprime = $$nome_a_abandonadas_3;
			echo "<td>$imprime</td>";
			$nome_b_abandonadas_3 = "b_abandonadas_3_$cont";
			$imprime = $$nome_b_abandonadas_3;
			echo "<td>$imprime</td>";
			
			$nome_a_nsa45 = "a_nsa45_$cont";
			$imprime = $$nome_a_nsa45;
			$imprime = number_format($imprime, 2, ',', '.');
			echo "<td>$imprime</td>";
			$nome_b_nsa45 = "b_nsa45_$cont";
			$imprime = $$nome_b_nsa45;
			$imprime = number_format($imprime, 2, ',', '.');
			echo "<td>$imprime</td>";
			
			$nome_a_nsa90 = "a_nsa90_$cont";
			$imprime = $$nome_a_nsa90;
			$imprime = number_format($imprime, 2, ',', '.');
			echo "<td>$imprime</td>";
			$nome_b_nsa90 = "b_nsa90_$cont";
			$imprime = $$nome_b_nsa90;
			$imprime = number_format($imprime, 2, ',', '.');
			echo "<td>$imprime</td>";
			
			echo '</tr>';
		}	
	}

		echo "</div>";
		echo "</table>";
		echo "<br><br>";
}
//13 - FIM

//14 - INÍCIO
if($tipo_consulta == '14')
{
	if($qual_tabela == '01')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';
		echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
		echo "<br><br>";

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>callid</b></td>';
		echo '<td><b>data_hora</b></td>';
		echo '<td><b>cod_fila</b></td>';
		echo '<td><b>tempo_espera(A)</b></td>';
		echo '<td><b>tempo_consulta_mudo</b></td>';
		echo '<td><b>tempo_atend</b></td>';
		echo '<td><b>id_operador</b></td>';
		echo '<td><b>desc_operador</b></td>';		
		
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_eventos_dac
								where data_hora between '$data_inicial' and '$data_final'
								order by data_hora");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$callid = utf8_encode($row['callid']);
			$data_hora = utf8_encode($row['data_hora']);
			$cod_fila = utf8_encode($row['cod_fila']);
			$tempo_espera = utf8_encode($row['tempo_espera']);
			$tempo_consulta_mudo = utf8_encode($row['tempo_consulta_mudo']);
			$tempo_atend = utf8_encode($row['tempo_atend']);
			$id_operador = utf8_encode($row['id_operador']);
			$desc_operador = utf8_encode($row['desc_operador']);
			
			echo '<tr>';
			echo "<td>$callid</td>";
			echo "<td>$data_hora</td>";
			echo "<td>$cod_fila</td>";
			echo "<td>$tempo_espera</td>";
			echo "<td>$tempo_consulta_mudo</td>";
			echo "<td>$tempo_atend</td>";
			echo "<td>$id_operador</td>";
			echo "<td>$desc_operador</td>";		
			echo '</tr>';
		}
	}
	
	if($qual_tabela == '02')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';
		echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
		echo "<br><br>";

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>callid</b></td>';
		echo '<td><b>data_hora</b></td>';
		echo '<td><b>cod_evento</b></td>';		
		
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_eventos_ura
								where data_hora between '$data_inicial' and '$data_final'
								order by data_hora");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$callid = utf8_encode($row['callid']);
			$data_hora = utf8_encode($row['data_hora']);
			$cod_evento = utf8_encode($row['cod_evento']);
			
			echo '<tr>';
			echo "<td>$callid</td>";
			echo "<td>$data_hora</td>";
			echo "<td>$cod_evento</td>";
		}
	}
	
	if($qual_tabela == '03')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';
		echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
		echo "<br><br>";

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>callid</b></td>';
		echo '<td><b>data_hora</b></td>';
		echo '<td><b>cod_evento</b></td>';
		echo '<td><b>acao</b></td>';
		echo '<td><b>login_front</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_eventos_front
								where data_hora between '$data_inicial' and '$data_final'
								order by data_hora");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$callid = utf8_encode($row['callid']);
			$data_hora = utf8_encode($row['data_hora']);
			$cod_evento = utf8_encode($row['cod_evento']);
			$acao = utf8_encode($row['acao']);
			$login_front = utf8_encode($row['login_front']);
			
			echo '<tr>';
			echo "<td>$callid</td>";
			echo "<td>$data_hora</td>";
			echo "<td>$cod_evento</td>";
			echo "<td>$acao</td>";
			echo "<td>$login_front</td>";
		}
	}
	
	if($qual_tabela == '04')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';
		echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
		echo "<br><br>";

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>callid</b></td>';
		echo '<td><b>data_hora</b></td>';
		echo '<td><b>cd_motivo</b></td>';
		echo '<td><b>cd_submotivo</b></td>';
		echo '<td><b>ds_motivo</b></td>';
		echo '<td><b>ds_submotivo</b></td>';
		echo '<td><b>login_front</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_log_categorizacao
								where data_hora between '$data_inicial' and '$data_final'
								order by data_hora");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$callid = utf8_encode($row['callid']);
			$data_hora = utf8_encode($row['data_hora']);
			$cod_evento = utf8_encode($row['cd_motivo']);
			$cod_evento = utf8_encode($row['cd_submotivo']);
			$cod_evento = utf8_encode($row['ds_motivo']);
			$cod_evento = utf8_encode($row['ds_submotivo']);
			$cod_evento = utf8_encode($row['login_front']);
			
			echo '<tr>';
			echo "<td>$callid</td>";
			echo "<td>$data_hora</td>";
			echo "<td>$cd_motivo</td>";
			echo "<td>$cd_submotivo</td>";
			echo "<td>$ds_motivo</td>";
			echo "<td>$ds_submotivo</td>";
			echo "<td>$login_front</td>";
		}
	}
	
	if($qual_tabela == '05')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';
		echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
		echo "<br><br>";

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>callid</b></td>';
		echo '<td><b>data_hora</b></td>';
		echo '<td><b>cod_fonte</b></td>';
		echo '<td><b>cod_dado</b></td>';
		echo '<td><b>valor_dado</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_dados_cadastrais
								where data_hora between '$data_inicial' and '$data_final'
								order by data_hora");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$callid = utf8_encode($row['callid']);
			$data_hora = utf8_encode($row['data_hora']);
			$cod_fonte = utf8_encode($row['cod_fonte']);
			$cod_dado = utf8_encode($row['cod_dado']);
			$valor_dado = utf8_encode($row['valor_dado']);
			
			echo '<tr>';
			echo "<td>$callid</td>";
			echo "<td>$data_hora</td>";
			echo "<td>$cod_fonte</td>";
			echo "<td>$cod_dado</td>";
			echo "<td>$valor_dado</td>";
		}
	}
	
	if($qual_tabela == '06')
	{

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>cod_evento</b></td>';
		echo '<td><b>cod_fonte</b></td>';
		echo '<td><b>desc_evento</b></td>';
		echo '<td><b>categorizacao</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_eventos");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$cod_evento = utf8_encode($row['cod_evento']);
			$cod_fonte = utf8_encode($row['cod_fonte']);
			$desc_evento = utf8_encode($row['desc_evento']);
			$categorizacao = utf8_encode($row['categorizacao']);
			
			echo '<tr>';
			echo "<td>$cod_evento</td>";
			echo "<td>$cod_fonte</td>";
			echo "<td>$desc_evento</td>";
			echo "<td>$categorizacao</td>";
		}
	}
	
	if($qual_tabela == '07')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>cod_dado</b></td>';
		echo '<td><b>desc_dado</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_tipo_dados");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$cod_dado = utf8_encode($row['cod_dado']);
			$desc_dado = utf8_encode($row['desc_dado']);
			
			echo '<tr>';
			echo "<td>$cod_dado</td>";
			echo "<td>$desc_dado</td>";
		}
	}
	
	if($qual_tabela == '08')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>cod_fonte</b></td>';
		echo '<td><b>desc_fonte</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_fonte");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$cod_fonte = utf8_encode($row['cod_fonte']);
			$desc_fonte = utf8_encode($row['desc_fonte']);
			
			echo '<tr>';
			echo "<td>$cod_fonte</td>";
			echo "<td>$desc_fonte</td>";
		}
	}
	
	if($qual_tabela == '09')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>cod_fila</b></td>';
		echo '<td><b>desc_fila</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_filas");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$cod_fila = utf8_encode($row['cod_fila']);
			$desc_fila = utf8_encode($row['desc_fila']);
			
			echo '<tr>';
			echo "<td>$cod_fila</td>";
			echo "<td>$desc_fila</td>";
		}
	}
	
	if($qual_tabela == '10')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>COD_PAIS</b></td>';
		echo '<td><b>NM_PAIS</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_pais");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$COD_PAIS = utf8_encode($row['COD_PAIS']);
			$NM_PAIS = utf8_encode($row['NM_PAIS']);
			
			echo '<tr>';
			echo "<td>$COD_PAIS</td>";
			echo "<td>$NM_PAIS</td>";
		}
	}
	
	if($qual_tabela == '11')
	{
		echo '<div class="w3-margin w3-tiny w3-center">';

		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		
		echo '<td><b>MATRICULA</b></td>';
		echo '<td><b>NOME</b></td>';
		echo '<td><b>SUPERVISOR</b></td>';
		echo '<td><b>ENTRADA</b></td>';
		echo '<td><b>SAIDA</b></td>';
		echo '<td><b>CODFUNCAO</b></td>';
		echo '<td><b>SEXO</b></td>';
		echo '<td><b>LOGIN_DAC</b></td>';
		echo '<td><b>CPF</b></td>';
			
		echo '</tr>';
		
		$query = $pdo->prepare("select * from tb_colaboradores_indra");						
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$MATRICULA = utf8_encode($row['MATRICULA']);
			$NOME = utf8_encode($row['NOME']);
			$SUPERVISOR = utf8_encode($row['SUPERVISOR']);
			$ENTRADA = utf8_encode($row['ENTRADA']);
			$SAIDA = utf8_encode($row['SAIDA']);
			$CODFUNCAO = utf8_encode($row['CODFUNCAO']);
			$SEXO = utf8_encode($row['SEXO']);
			$LOGIN_DAC = utf8_encode($row['LOGIN_DAC']);
			$CPF = utf8_encode($row['CPF']);
			
			echo '<tr>';
			echo "<td>$MATRICULA</td>";
			echo "<td>$NOME</td>";
			echo "<td>$SUPERVISOR</td>";
			echo "<td>$ENTRADA</td>";
			echo "<td>$SAIDA</td>";
			echo "<td>$CODFUNCAO</td>";
			echo "<td>$SEXO</td>";
			echo "<td>$LOGIN_DAC</td>";
			echo "<td>$CPF</td>";
		}
	}
}
//14 - FIM

// 15 - INÍCIO
if($tipo_consulta == '15'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br><br>";

	$query = $pdo->prepare("select count(distinct callid) as total_dac from tb_eventos_DAC
							where data_hora between '$data_inicial' and '$data_final'");
							
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$total_dac = utf8_encode($row['total_dac']);	
	}	
	
	$query = $pdo->prepare("select count(distinct callid) as total_ura from tb_eventos_URA
							where data_hora between '$data_inicial' and '$data_final'");
							
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$total_ura = utf8_encode($row['total_ura']);	
	}
	
	$total_retidas = $total_ura - $total_dac;
	$percentual_de_retencao = $total_retidas/$total_ura*100;
	$percentual_de_retencao = number_format($percentual_de_retencao, 2, ',', '.');
	$total_retidas = number_format($total_retidas, 0, ',', '.');
	$total_ura = number_format($total_ura, 0, ',', '.');
	echo "<br><br><b>Total de Ligações:</b> $total_ura";
	echo "<br><br><b>Total Retidas:</b> $total_retidas";
	echo "<br><br><b>Percentual de Retenção na URA:</b> $percentual_de_retencao%";
	echo "</div>";
}
// 15 - FIM

// 16 - INÍCIO
if($tipo_consulta == '16'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Consulta para o período:</i></b> $data_inicial_texto até $data_final_texto";
	echo "<br><br>";
	
	echo '<div class="w3-margin w3-tiny w3-center">';

	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';		
			echo '<td><b>CALLID</b></td>';
			echo '<td><b>QUANTIDADE DE TRANSFERÊNCIAS</b></td>';				
		echo '</tr>';

	$query = $pdo->prepare("select callid, count (*) TOTAL_TRANSF from tb_eventos_DAC
							where data_hora between '$data_inicial' and '$data_final' and tempo_atend > 0
							group by callid
							having count (*) > $min_transf
							order by TOTAL_TRANSF DESC");
							
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$callid = utf8_encode($row['callid']);
		$TOTAL_TRANSF = utf8_encode($row['TOTAL_TRANSF']);
		
		echo '<tr>';
			echo '<td><b>';
			
			echo "<div class=\"w3-dropdown-hover\">
			<u class='w3-text-indigo'>$callid</u>
			<div class=\"w3-dropdown-content w3-indigo w3-round w3-card-4\">
			<a href= \"list_transferencias.php?data_inicial=$data_inicial&data_final=$data_final&callid=$callid\" target=\"_blank\">Listar Transferências</a>
			</div>
			</div>";		
			echo '</b></td>';
			
			echo "<td>$TOTAL_TRANSF</td>";
		echo '</tr>';
	}
	echo "</table>";
	echo "</div>";
}
// 16 - FIM

// 17 - INÍCIO
if($tipo_consulta == '17'){
	include "def_var_ura.php";
	
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Tradução solicitada para o evento URA:</i></b> $codigo_evento";
	echo "<br><br>";
	
	$fluxo_ura_array = explode(";", $codigo_evento);
	$count = count($fluxo_ura_array);
		
	$texto = "";
	
	for($i=0; $i<$count; $i++){
		
		$cod = $fluxo_ura_array[$i];
		$palavra = "evento_$cod";
			
		if (isset($$palavra)) $txt_inc = $$palavra;
		else $txt_inc = "EVENTO SEM DESCRIÇÃO NA TABELA TB_EVENTOS";
			
		if ($i == 0)$texto = $texto."$cod($txt_inc)";
		if ($i > 0)$texto = $texto.";$cod($txt_inc)";
	}
	
	echo "<b><i>Tradução:</i></b> $texto";
}
// 17 - FIM

// 18 - INÍCIO
if($tipo_consulta == '18'){
	include "def_var_ura.php";
	
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Tradução solicitada para o evento FRONTEND:</i></b> $codigo_evento";
	echo "<br><br>";
	
	$fluxo_ura_array = explode(";", $codigo_evento);
	$count = count($fluxo_ura_array);
		
	$texto = "";
	
	for($i=0; $i<$count; $i++){
		
		$cod = $fluxo_ura_array[$i];
		$palavra = "evento_$cod";
			
		if (isset($$palavra)) $txt_inc = $$palavra;
		else $txt_inc = "EVENTO SEM DESCRIÇÃO NA TABELA TB_EVENTOS";
			
		if ($i == 0)$texto = $texto."$cod($txt_inc)";
		if ($i > 0)$texto = $texto.";$cod($txt_inc)";
	}
	
	echo "<b><i>Tradução:</i></b> $texto";
}
// 18 - FIM

include "desconecta.php";
?>

</body>
</html>