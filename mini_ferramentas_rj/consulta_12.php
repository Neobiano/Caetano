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
//$data_atual = date("d-m-Y");

//if(strtotime($data_final)<strtotime($data_inserida))

$nome_relatorio = "dns"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Dispersão do Nível de Serviço por Faixa de Horário"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

$dmm_imprime = $dmm;
$dmm = explode(",", $dmm);

$dias_excluir_imprime = $dias_excluir;
$dias_excluir = explode(",", $dias_excluir);

//DEFINE QUANTIDADE DE DIAS DE CADA MÊS
if($qual_mes=='01') $qtd_dias = 31;
if($qual_mes=='02') {
	if ($qual_ano%4 != 0) $qtd_dias = 28;
	else $qtd_dias = 29;
}
if($qual_mes=='03') $qtd_dias = 31;
if($qual_mes=='04') $qtd_dias = 30;
if($qual_mes=='05') $qtd_dias = 31;
if($qual_mes=='06') $qtd_dias = 30;
if($qual_mes=='07') $qtd_dias = 31;
if($qual_mes=='08') $qtd_dias = 31;
if($qual_mes=='09') $qtd_dias = 30;
if($qual_mes=='10') $qtd_dias = 31;
if($qual_mes=='11') $qtd_dias = 30;
if($qual_mes=='12') $qtd_dias = 31;

$dia_atual = $today = date("d");
$mes_atual = $today = date("m");

// DEFINE VARIÁVEL $MES POR EXTENSO
if ($qual_mes == '01') $mes = 'Janeiro';
if ($qual_mes == '02') $mes = 'Fevereiro';
if ($qual_mes == '03') $mes = 'Março';
if ($qual_mes == '04') $mes = 'Abril';
if ($qual_mes == '05') $mes = 'Maio';
if ($qual_mes == '06') $mes = 'Junho';
if ($qual_mes == '07') $mes = 'Julho';
if ($qual_mes == '08') $mes = 'Agosto';
if ($qual_mes == '09') $mes = 'Setembro';
if ($qual_mes == '10') $mes = 'Outubro';
if ($qual_mes == '11') $mes = 'Novembro';
if ($qual_mes == '12') $mes = 'Dezembro';


$SOMA_MULT = 0;
$SOMA_TOTAL_ATEND = 0;
$SOMA_NSH = 0;
$SOMA_A = 0;
$SOMA_B = 0;
$SOMA_C = 0;
$SOMA_TOTAL_ATEND = 0;

if ($qual_mes == $mes_atual) $qtd_dias = $dia_atual - 1;

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
		echo "<b>$titulo</b>";
		echo "<br><br><b>Período de Consulta:</b> $mes/$qual_ano";
		echo "<br><b>DMM:</b> $dmm_imprime";
		echo "<br><b>Filas:</b> $in_filas";
		echo "<br><b>Dias Excluídos:</b> $dias_excluir_imprime";
	echo '</div>';
	
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-margin-top w3-tiny w3-left w3-padding">';
		echo "<br><b class='w3-text-black'>Legenda:</b>";
		echo "<br><br><b class='w3-text-black'>DNS:</b> Dispersão de Nível de Serviço por Faixa de Horário;";
		echo "<br><b class='w3-text-black'>NSA:</b> Nível de Serviço Ponderado Acumulado Mensal;";
		echo "<br><b class='w3-text-black'>NSH:</b> Média Simples dos Níveis de Serviço Apurados por Faixa de Horário Acumulados Mensais;";
		echo "<br><b class='w3-text-black'>A:</b> Número de atendimentos onde o cliente esperou menos do que o tempo em segundos definido e/ou dentro dos prazos estipulados pela CAIXA;";
		echo "<br><b class='w3-text-black'>B:</b> Soma de todos os atendimentos;";
		echo "<br><b class='w3-text-black'>C:</b> Chamadas abandonadas com espera superior ao tempo em segundos definido e/ou superior aos prazos estipulados pela CAIXA, ou não atendidos.";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DIA &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL DE ATENDIMENTOS &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>A &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>B &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>C &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TEMPO DE ESPERA PADRÃO &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>NSA = A / (B + C) &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>NSH &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>|NSH - NSA| &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	$soma_nsh_dia = 0;
	$soma_atendimentos_dia = 0;
	$soma_mult_dia = 0;
	
	$qtd_dias_div = $qtd_dias;
	
	for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){
		if(in_array($pos_dia, $dias_excluir)){
			$qtd_dias_div = $qtd_dias_div - 1;
			continue;
		}
		$qtd_linhas_consulta++;
		echo "<tr>";
		if ($pos_dia < 10) $pos_dia_imprime = "0$pos_dia";
		else $pos_dia_imprime = "$pos_dia";
		echo "<td>$pos_dia_imprime</td>";
	
		if(in_array($pos_dia,$dmm)) $ns = 90;
		else $ns = 45;
	
		//NSA
		$query = $pdo->prepare("select A, B, C, ISNULL(cast(ISNULL(A, 0) as float) / nullif(cast(ISNULL(B, 0) as float) + cast(ISNULL(C, 0) as float),0),1) NSA, ISNULL(B, 0) TOTAL_ATEND, ISNULL(cast(ISNULL(A, 0) as float) / nullif(cast(ISNULL(B, 0) as float) + cast(ISNULL(C, 0) as float),0),1) * ISNULL(B, 0) MULT from
				(
				select
				(
				select count(*) A from tb_eventos_dac
				where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59' and cod_fila in ($in_filas)
				and tempo_espera <= $ns and tempo_atend > 0
				) as A,
				(
				select count(*) B from tb_eventos_dac
				where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59' and cod_fila in ($in_filas)
				and tempo_atend > 0
				) as B,
				(
				select count(*) C from tb_eventos_dac
				where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59' and cod_fila in ($in_filas)
				and tempo_espera > $ns and tempo_atend = 0
				) as C
				) as NSA");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){
			
			$NSA = utf8_encode($row['NSA']);
			
			$TOTAL_ATEND = utf8_encode($row['TOTAL_ATEND']);
			if(!in_array($pos_dia, $dias_excluir)) $SOMA_TOTAL_ATEND = $SOMA_TOTAL_ATEND + $TOTAL_ATEND;
			
			if(in_array($pos_dia, $dias_excluir)) $qtd_dias_div = $qtd_dias_div - 1;
			
			$MULT = utf8_encode($row['MULT']);

			if(!in_array($pos_dia, $dias_excluir)) $SOMA_MULT = $SOMA_MULT + $MULT;
	
				
			$A = utf8_encode($row['A']);
			if($A == NULL) $A = 0;
			$SOMA_A = $SOMA_A + $A;
	
			$B = utf8_encode($row['B']);
			if($B == NULL) $B = 0;
			$SOMA_B = $SOMA_B + $B;
	
			$C = utf8_encode($row['C']);
			if($C == NULL) $C = 0;
			$SOMA_C = $SOMA_C + $C;
				
			echo "<td>$TOTAL_ATEND</td>";
			echo "<td>$A</td>";
			echo "<td>$B</td>";
			echo "<td>$C</td>";
			echo "<td>$ns</td>";
			echo "<td>$NSA</td>";
		}
	
		//NSH
		$query = $pdo->prepare("select avg(NSA) NSH from
				(
				select x.HORA, x.MINUTO, ISNULL(cast(ISNULL(A, 0) as float) / nullif(cast(ISNULL(B, 0) as float) + cast(ISNULL(C, 0) as float),0),1) NSA from
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
				) as NSH");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){
			$NSH = utf8_encode($row['NSH']);
			if(!in_array($pos_dia, $dias_excluir)) $SOMA_NSH = $SOMA_NSH + $NSH;
	
			echo "<td><a class='w3-text-indigo' title='Listar Faixas de Horário' href= \"lista_nsh.php?NSH=$NSH&pos_dia=$pos_dia&qual_ano=$qual_ano&qual_mes=$qual_mes&mes=$mes&ns=$ns&in_filas=$in_filas\" target=\"_blank\">$NSH</a></td>";
		}
		
		$diferenca = $NSH - $NSA;
		if($diferenca < 0) $diferenca = $diferenca * (-1);
		
		$diferenca_imprime = number_format($diferenca, 2, ',', '.');
		
		if($diferenca <= 0.05) echo "<td>$diferenca_imprime</td>";
		else echo "<td><b class='w3-text-red'>$diferenca_imprime</b></td>";
	
		echo "</tr>";
	}
	
	echo "</tbody><tr class='w3-indigo'>";
	
	if($SOMA_TOTAL_ATEND > 0) $NSA_MENSAL = $SOMA_MULT / $SOMA_TOTAL_ATEND;
	else $NSA_MENSAL = 1;
	
	$NSH_MENSAL = $SOMA_NSH / $qtd_dias_div;
	
	$DIF = $NSA_MENSAL - $NSH_MENSAL;
	
	if($DIF < 0) $DIF = $DIF * (-1);
	
	if($DIF <= 0.05) $DNS = 1;
	else{
		$DIF = $DIF - 0.05;
		$DNS = 1 - $DIF;
	}
	
	
	
	echo "<td><b>DNS: $DNS</b></td>";
	echo "<td><b></b></td>";
	echo "<td><b></b></td>";
	echo "<td><b></b></td>";
	echo "<td><b></b></td>";
	echo "<td><b></b></td>";
	echo "<td><b>NSA <i>(Média Ponderada)</i>: $NSA_MENSAL</b></td>";
	echo "<td><b>NSH - <i>(Média Simples)</i>: $NSH_MENSAL</b></td>";
	
	$diferenca = $NSA_MENSAL - $NSH_MENSAL;
	if($diferenca < 0) $diferenca = $diferenca * (-1);
	$diferenca_imprime = number_format($diferenca, 10, ',', '.');
	echo "<td><b>$diferenca_imprime</b></td>";
	
	echo "<td><b></b></td>";
	echo "</tr>";
	echo "</table>";
	echo "</div>";

	
include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>

</body>
</html>

<script>  
$('#tabela').DataTable( {
	"order": [[ 0, "asc" ]]
} );
</script>