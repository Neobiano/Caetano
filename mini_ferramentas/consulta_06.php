<?php
$nome_relatorio = "tma_ns"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "TMA e Nível de Serviço"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

$dados_grafico = "['Ilha', 'TMA', 'NSA 45', 'NSA 90']";

//VARIÁVEIS TOTALIZADORAS
$TOTAL_SOMA_TOTAL_ATENDIMENTOS = 0;
$TOTAL_SOMA_TMA = 0;
$TOTAL_SOMA_NSA_45 = 0;
$TOTAL_SOMA_NSA_90 = 0;

$INC_NSA_45 = 0;
$INC_NSA_90 = 0;
$INC_TMA = 0;

// PREPARA VETOR $ILHAS - INÍCIO
$ilhas = "";
$query = $pdo->prepare("select * from tb_ilhas");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$desc_ilha = utf8_encode($row['desc_ilha']);
	$cod_filas = utf8_encode($row['cod_filas']);	
	$ilhas[$i]['desc_ilha'] = "$desc_ilha";
	$ilhas[$i]['cod_filas'] = "$cod_filas";
}
// PREPARA VETOR $ILHAS - FIM

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo '</div>';
	
	include "inicia_div_tabela.php"; // INICIA A <DIV> DA TABELA
	include "inicia_tabela.php"; // INICIA A TABELA
	
// LOOP PARA IMPRESSÃO DAS TABELAS POR ILHA - INÍCIO
$qtd_ilas = count($ilhas);

for($pos=0;$pos < $qtd_ilas;$pos++){

	$nome_ilha = $ilhas[$pos]['desc_ilha'];
	$cod_filas_ilha = $ilhas[$pos]['cod_filas'];
	$SOMA_TOTAL_ATENDIMENTOS = 0;
	$SOMA_TMA = 0;
	$SOMA_NSA_45 = 0;
	$SOMA_NSA_90 = 0;
	
		// IMPRIME COLUNAS DA TABELA - INÍCIO
		
		if ($pos>0){
			echo "<tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>";
			echo "<tr class='w3-indigo'>";
			$tabela = $tabela."<table><tr>";
		}
		
		$texto = "<td><b>FILA <i>($nome_ilha)</i></b></td>";
		echo incrementa_tabela($texto);	
		
		$texto = "<td><b>TOTAL DE ATENDIMENTOS</b></td>";
		echo incrementa_tabela($texto);
		
		$texto = "<td><b>TMA</b></td>";
		echo incrementa_tabela($texto);
		
		$texto = "<td><b>NSA 45</b></td>";
		echo incrementa_tabela($texto);
		
		$texto = "<td><b>NSA 90</b></td>";
		echo incrementa_tabela($texto);
		
		$texto = "</tr>";
		echo incrementa_tabela($texto);
		// IMPRIME COLUNAS DA TABELA - FIM
		
		echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
		// INFORMA A CONSULTA
		$sql = "select a.cod_fila, D.desc_fila, TOTAL_ATENDIMENTOS, TMA, ATEND_ATE_45, ATEND_ATE_90, ABANDONADAS_APOS_45, ABANDONADAS_APOS_90
								from
								(
								select cod_fila, count (*) TOTAL_ATENDIMENTOS from tb_eventos_DAC (nolock)
								where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and cod_fila in ($cod_filas_ilha) --and datepart(dw,data_hora) in $in_semana
								group by cod_fila
								) as a
								inner join
								(
								select cod_fila, count (*) ATEND_ATE_45 from tb_eventos_DAC (nolock)
								where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and tempo_espera <= 45 and cod_fila in ($cod_filas_ilha) --and datepart(dw,data_hora) in $in_semana
								group by cod_fila
								) as b
								on a.cod_fila = b.cod_fila
								
								inner join
								(
								select cod_fila, avg(tempo_atend) TMA from tb_eventos_DAC (nolock)
								where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > $tempo_de_corte and cod_fila in ($cod_filas_ilha) --and datepart(dw,data_hora) in $in_semana
								group by cod_fila
								) as xx
								on a.cod_fila = xx.cod_fila
								
								
								inner join
								(
								select cod_fila, count (*) ATEND_ATE_90 from tb_eventos_DAC (nolock)
								where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and tempo_espera <= 90 and cod_fila in ($cod_filas_ilha) --and datepart(dw,data_hora) in $in_semana
								group by cod_fila
								) as c
								on a.cod_fila = c.cod_fila
								left join
								(
								select cod_fila, count (*) ABANDONADAS_APOS_45 from tb_eventos_DAC (nolock)
								where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend = 0 and tempo_espera > 45 and cod_fila in ($cod_filas_ilha) --and datepart(dw,data_hora) in $in_semana
								group by cod_fila
								) as e
								on a.cod_fila = e.cod_fila
								left join
								(
								select cod_fila, count (*) ABANDONADAS_APOS_90 from tb_eventos_DAC (nolock)
								where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend = 0 and tempo_espera > 90 and cod_fila in ($cod_filas_ilha) --and datepart(dw,data_hora) in $in_semana
								group by cod_fila
								) as f
								on a.cod_fila = f.cod_fila
								inner join tb_filas as d
								on a.cod_fila = d.cod_fila
								order by cod_fila";
	   // echo $sql;
		$query = $pdo->prepare($sql);
		
		$query->execute(); // EXECUTA A CONSULTA
		
		// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
		for($i=0; $row = $query->fetch(); $i++){
			$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
			$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
			
			// RECEBE RESULTADOS DA CONSULTA - INÍCIO
			$COD_FILA = utf8_encode($row['cod_fila']);
				$COD_FILA = number_format($COD_FILA, 0, ',', '.');
			$DESC_FILA = utf8_encode($row['desc_fila']);
			$TOTAL_ATENDIMENTOS = utf8_encode($row['TOTAL_ATENDIMENTOS']);
				$SOMA_TOTAL_ATENDIMENTOS = $SOMA_TOTAL_ATENDIMENTOS + $TOTAL_ATENDIMENTOS;
				$TOTAL_SOMA_TOTAL_ATENDIMENTOS = $TOTAL_SOMA_TOTAL_ATENDIMENTOS + $TOTAL_ATENDIMENTOS;
			$TMA = utf8_encode($row['TMA']);
				$SOMA_TMA = $SOMA_TMA + ($TMA * $TOTAL_ATENDIMENTOS);
				$TOTAL_SOMA_TMA = $TOTAL_SOMA_TMA + ($TMA * $TOTAL_ATENDIMENTOS);
			
			$ATEND_ATE_45 = utf8_encode($row['ATEND_ATE_45']);
				if ($ATEND_ATE_45 == NULL) $ATEND_ATE_45 = 0;
			$ATEND_ATE_90 = utf8_encode($row['ATEND_ATE_90']);
				if ($ATEND_ATE_90 == NULL) $ATEND_ATE_90 = 0;
			$ABANDONADAS_APOS_45 = utf8_encode($row['ABANDONADAS_APOS_45']);
				if ($ABANDONADAS_APOS_45 == NULL) $ABANDONADAS_APOS_45 = 0;
			$ABANDONADAS_APOS_90 = utf8_encode($row['ABANDONADAS_APOS_90']);
				if ($ABANDONADAS_APOS_90 == NULL) $ABANDONADAS_APOS_90 = 0;			
			
			$NSA_45 = ($ATEND_ATE_45 / ($TOTAL_ATENDIMENTOS + $ABANDONADAS_APOS_45) * 100);
				$SOMA_NSA_45 = $SOMA_NSA_45 + ($NSA_45 * $TOTAL_ATENDIMENTOS);
				$TOTAL_SOMA_NSA_45 = $TOTAL_SOMA_NSA_45 + ($NSA_45 * $TOTAL_ATENDIMENTOS);				
				
			$NSA_90 = ($ATEND_ATE_90 / ($TOTAL_ATENDIMENTOS + $ABANDONADAS_APOS_90) * 100);
				$SOMA_NSA_90 = $SOMA_NSA_90 + ($NSA_90 * $TOTAL_ATENDIMENTOS);
				$TOTAL_SOMA_NSA_90 = $TOTAL_SOMA_NSA_90 + ($NSA_90 * $TOTAL_ATENDIMENTOS);				
				
			// RECEBE RESULTADOS DA CONSULTA - FIM
			
			// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
			$texto = '<tr>';
			echo incrementa_tabela($texto);
				
				$txt_fila = "$COD_FILA - $DESC_FILA";				
				$texto = "<td>$txt_fila</td>";
				echo incrementa_tabela($texto);
				
				$txt_TOTAL_ATENDIMENTOS = number_format($TOTAL_ATENDIMENTOS, 0, ',', '.');
				$texto = "<td>$txt_TOTAL_ATENDIMENTOS</td>";
				echo incrementa_tabela($texto);
				
				$txt_TMA = number_format($TMA, 0, ',', '.');
				$texto = "<td>$txt_TMA</td>";
				echo incrementa_tabela($texto);
				
				$txt_NSA_45 = number_format($NSA_45, 2, ',', '.');
				$texto = "<td>$txt_NSA_45%</td>";
				echo incrementa_tabela($texto);
				
				$txt_NSA_90 = number_format($NSA_90, 2, ',', '.');
				$texto = "<td>$txt_NSA_90%</td>";
				echo incrementa_tabela($texto);
				
			$texto = '</tr>';
			echo incrementa_tabela($texto);			
			// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
		}
		// IMPRIME O RESULTADO DA CONSULTA - FIM

	// IMPRIME <TR> FINALIZADORA - INÍCIO	
	if($nome_ilha != "Demais Filas"){
		
		if ($SOMA_TOTAL_ATENDIMENTOS > 0){
			$INC_TMA = $SOMA_TMA / $SOMA_TOTAL_ATENDIMENTOS;
			$INC_TMA_IMP = $INC_TMA;
			$INC_TMA = number_format($INC_TMA, 0, ',', '.');
		} else{
			$INC_TMA = 0;
			$INC_TMA_IMP = $INC_TMA;
		}
		
		if ($SOMA_TOTAL_ATENDIMENTOS > 0){
			$INC_NSA_45 = $SOMA_NSA_45 / $SOMA_TOTAL_ATENDIMENTOS;
			$INC_NSA_45_IMP = $INC_NSA_45;
			$INC_NSA_45 = number_format($INC_NSA_45, 2, ',', '.');
		} else{
			$INC_NSA_45 = 0;
			$INC_NSA_45_IMP = $INC_NSA_45;
		}
		
		if ($SOMA_TOTAL_ATENDIMENTOS > 0){
			$INC_NSA_90 = $SOMA_NSA_90	/ $SOMA_TOTAL_ATENDIMENTOS;
			$INC_NSA_90_IMP = $INC_NSA_90;
			$INC_NSA_90 = number_format($INC_NSA_90, 2, ',', '.');
		} else{
			$INC_NSA_90 = 0;
		}

		$texto = "<tr>";
		echo incrementa_tabela($texto);

			$dados_grafico = $dados_grafico.",['$nome_ilha',$INC_TMA_IMP,$INC_NSA_45_IMP,$INC_NSA_90_IMP]";
		
			$texto = "<td><b>TOTALIZADOR ($nome_ilha)</b></td>";
			echo incrementa_tabela($texto);
			
			$SOMA_TOTAL_ATENDIMENTOS = number_format($SOMA_TOTAL_ATENDIMENTOS, 0, ',', '.');
			$texto = "<td><b>$SOMA_TOTAL_ATENDIMENTOS</b></td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td><b>$INC_TMA</b></td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td><b>$INC_NSA_45%</b></td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td><b>$INC_NSA_90%</b></td>";
			echo incrementa_tabela($texto);
			
		$texto = "</tr>";
		echo incrementa_tabela($texto);
	}
	// IMPRIME <TR> FINALIZADORA - FIM
}
// LOOP PARA IMPRESSÃO DAS TABELAS POR ILHA - FIM

// IMPRIME <TR> FINALIZADORA - INÍCIO
echo "<tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>";

if ($TOTAL_SOMA_TOTAL_ATENDIMENTOS > 0){
	$INC_TMA = $TOTAL_SOMA_TMA / $TOTAL_SOMA_TOTAL_ATENDIMENTOS;
	$INC_TMA = number_format($INC_TMA, 0, ',', '.');
} else $INC_TMA = 0;

if ($TOTAL_SOMA_TOTAL_ATENDIMENTOS > 0){
	$INC_NSA_45 = $TOTAL_SOMA_NSA_45 / $TOTAL_SOMA_TOTAL_ATENDIMENTOS;
	$INC_NSA_45 = number_format($INC_NSA_45, 2, ',', '.');
} else $INC_NSA_45 = 0;

if ($TOTAL_SOMA_TOTAL_ATENDIMENTOS > 0){
	$INC_NSA_90 = $TOTAL_SOMA_NSA_90 / $TOTAL_SOMA_TOTAL_ATENDIMENTOS;
	$INC_NSA_90 = number_format($INC_NSA_90, 2, ',', '.');
} else $INC_NSA_90 = 0;

echo "<tr style='background: #333; color: white;'>";
$tabela = $tabela."<tr>";

	$texto = "<td><b>TOTALIZADOR FINAL</b></td>";
	echo incrementa_tabela($texto);
	
	$TOTAL_SOMA_TOTAL_ATENDIMENTOS = number_format($TOTAL_SOMA_TOTAL_ATENDIMENTOS, 0, ',', '.');
	$texto = "<td><b>$TOTAL_SOMA_TOTAL_ATENDIMENTOS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$INC_TMA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$INC_NSA_45%</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$INC_NSA_90%</b></td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM

include "finaliza_tabela.php"; // FINALIZA A TABELA

//include"imprime_grafico.php";// IMPRIME O GRÁFICO

$query = $pdo->prepare("select max(data_hora) MAX from tb_eventos_DAC where data_hora between '$data_inicial' and '$data_final 23:59:59.999'");
		$query->execute(); // EXECUTA A CONSULTA
		
		// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
		for($i=0; $row = $query->fetch(); $i++){
			$MAX = utf8_encode($row['MAX']);
		}
		echo "<br><font class='w3-container w3-text-indigo w3-tiny'>Data/Hora da última inclusão no período: <b>$MAX</b></font>";

		$largura = "1200";
		$altura = "400";
		$max = "0";
		$min = "5000";
		$tipo = "ColumnChart";
		
echo "<div class='w3-border w3-margin w3-padding-bottom w3-card-4' style='margin-top:0; !important;'>";		
	echo imprimeGraficoLinha($dados_grafico,"",$largura,$altura,$max,$min,$tipo,$parametros_adicionais);
echo "</div>";
?>