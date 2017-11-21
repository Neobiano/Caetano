<?php
$nome_relatorio = "quantidade_de_operadores"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Quantidade de Operadores - Diário"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$TOTAL_OPERADORES = 0;
$TOTAL_DIAS = 0;


	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	
	if($select_ilhas == 0) echo "<br><br><b>Ilhas Selecionadas:</b> Todas";
	else echo "<br><br><b>Ilhas Selecionadas:</b> $ilhas_selecionadas_txt";
	
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DATA </b></td>";
	echo incrementa_tabela($texto);	
	
	$texto = "<td><b>QUANTIDADE DE OPERADORES </b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	if($select_ilhas == 0) $query = $pdo->prepare("select CONVERT (VARCHAR, CONVERT(DATETIME, data_hora, 103), 105) as DATA, datepart(dw,data_hora) DIA_SEMANA, count(distinct id_operador) TOTAL from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and id_operador is not NULL
							group by CONVERT (VARCHAR, CONVERT(DATETIME, data_hora, 103), 105), datepart(dw,data_hora)
							order by DATA");
	else $query = $pdo->prepare("select CONVERT (VARCHAR, CONVERT(DATETIME, data_hora, 103), 105) as DATA, datepart(dw,data_hora) DIA_SEMANA, count(distinct id_operador) TOTAL from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and id_operador is not NULL and cod_fila in ($in_ilhas)
							group by CONVERT (VARCHAR, CONVERT(DATETIME, data_hora, 103), 105), datepart(dw,data_hora)
							order by DATA");
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		$DIA_SEMANA = utf8_encode($row['DIA_SEMANA']);
		include "traduz_dia_semana.php"; // TRADUZ O DIA DA SEMANA
		
		$TOTAL = utf8_encode($row['TOTAL']);
			$TOTAL_OPERADORES = $TOTAL_OPERADORES + $TOTAL;
			
		$DATA = utf8_encode($row['DATA']);	

		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		$texto = '<tr>';
		echo incrementa_tabela($texto);
		
			$DATA = date("d-m-Y", strtotime($DATA));   
			$texto = "<td>$DATA ($DIA_SEMANA)</td>";
			echo incrementa_tabela($texto);
			$incrementa_grafico = $incrementa_grafico.",['$DATA ($DIA_SEMANA)'"; // INCREMENTA OS DADOS DO GRÁFICO
			
			$texto = "<td>$TOTAL</td>";
			echo incrementa_tabela($texto);
			
			$incrementa_grafico = $incrementa_grafico.",$TOTAL]"; // INCREMENTA OS DADOS DO GRÁFICO
			
			if($TOTAL > $max) $max = $TOTAL; // ALTERA O VALOR MÁXIMO DE 'Y' DO GRÁFICO
			if($TOTAL < $min) $min = $TOTAL; // ALTERA O VALOR MÍNIMO DE 'Y' DO GRÁFICO
			
			$TOTAL_DIAS = $TOTAL_DIAS + 1;
		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM

// IMPRIME <TR> FINALIZADORA - INÍCIO
if($TOTAL_DIAS > 0) $MEDIA_OPERADORES = $TOTAL_OPERADORES / $TOTAL_DIAS;
else $MEDIA_OPERADORES = 0;
$MEDIA_OPERADORES = number_format($MEDIA_OPERADORES, 0, ',', '.');

echo "</tbody><tr class='w3-indigo'>";
$tabela = $tabela."<tr>";

	$texto = "<td><b>MÉDIA DE OPERADORES</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$MEDIA_OPERADORES</b></td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>

</body>
</html>