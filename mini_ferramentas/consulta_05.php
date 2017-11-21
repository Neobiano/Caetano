<?php
$nome_relatorio = "categorizacao_de_chamadas"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Categorização de Chamadas"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$SOMA_TOTAL = 0;

//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO	
	$texto = "<td><b>MOTIVO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>SUBMOTIVO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$query = $pdo->prepare("select ds_motivo MOTIVO, ds_submotivo SUBMOTIVO, count(*) TOTAL from tb_log_categorizacao
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana
							group by ds_motivo, ds_submotivo
							order by ds_motivo, ds_submotivo");
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO		
		$MOTIVO = utf8_encode($row['MOTIVO']);
		
		$SUBMOTIVO = utf8_encode($row['SUBMOTIVO']);			
			
		$TOTAL = utf8_encode($row['TOTAL']);
			$SOMA_TOTAL = $SOMA_TOTAL + $TOTAL;		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		$texto = '<tr>';
		echo incrementa_tabela($texto);
		
			$VAR_MOT_SUB = "$MOTIVO - $SUBMOTIVO";
			$incrementa_grafico = $incrementa_grafico.",['$VAR_MOT_SUB'"; // INCREMENTA OS DADOS DO GRÁFICO
		 
			$texto = "<td>$MOTIVO</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$SUBMOTIVO</td>";
			echo incrementa_tabela($texto);
			
			if($TOTAL > $max) $max = $TOTAL; // ALTERA O VALOR MÁXIMO DE 'Y' DO GRÁFICO
			if($TOTAL < $min) $min = $TOTAL; // ALTERA O VALOR MÍNIMO DE 'Y' DO GRÁFICO
			$incrementa_grafico = $incrementa_grafico.",$TOTAL]"; // INCREMENTA OS DADOS DO GRÁFICO
			$tabela = $tabela."<td>$TOTAL</td>";
			$TOTAL = number_format($TOTAL, 0, '.', ',');
			echo "<td>$TOTAL</td>";

		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM
	
// IMPRIME <TR> FINALIZADORA - INÍCIO
$SOMA_TOTAL = number_format($SOMA_TOTAL, 0, ',', '.');

echo "</tbody><tr class='w3-indigo'>";
$tabela = $tabela."<tr>";

	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL DE CATEGORIZAÇÕES</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$SOMA_TOTAL</b></td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM

$min = 0;	
include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>

<script>  
$('#tabela').DataTable( {
	"order": [[ 2, "desc" ]],
	 "iDisplayLength": -1
} );
</script>