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
$nome_relatorio = "incidência_de_rechamadas"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Incidência de Rechamadas - Total de Rechamadas (URA + ATC)"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$SOMA_TOTAL_URA = 0;
$SOMA_TOTAL_RECHAMADAS = 0;

	// IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	// echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DATA &nbsp</b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td><b>DIA DA SEMANA &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL DE LIGAÇÕES (URA e ATC)&nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL DE RECHAMADAS &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PERCENTUAL DE RECHAMADAS &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$query = $pdo->prepare("select g.DIA DATA, g.DIA_SEMANA DIA_SEMANA, TOTAL_URA, TOTAL_RECHAMADAS, cast(TOTAL_RECHAMADAS as float) / cast(TOTAL_URA as float) * 100 PERC from
							(
							select DIA, a.DIA_SEMANA, sum(TOTAL) TOTAL_RECHAMADAS from
							(
							select convert(date,data_hora,11) DIA, datepart(dw,data_hora) DIA_SEMANA, valor_dado DADO, count(distinct callid) - 1 TOTAL
							from tb_dados_cadastrais
							where cod_dado = '3' and data_hora between '$data_inicial' and '$data_final 23:59:59.999' and VALOR_dado <> ''
							group by convert(date,data_hora,11), datepart(dw,data_hora), valor_dado
							having count(distinct callid) >= 2
							) as a
							group by DIA, a.DIA_SEMANA
							) as g
							inner join
							(
							select convert(date,data_hora,11) DIA, datepart(dw,data_hora) DIA_SEMANA, count (distinct callid) TOTAL_URA from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
							group by convert(date,data_hora,11), datepart(dw,data_hora)
							) as h on g.DIA = h.DIA");
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		$DIA_SEMANA = utf8_encode($row['DIA_SEMANA']);
		include "traduz_dia_semana.php"; // TRADUZ O DIA DA SEMANA
		
		$DATA = utf8_encode($row['DATA']);
		
		$TOTAL_URA = utf8_encode($row['TOTAL_URA']);
			$SOMA_TOTAL_URA = $SOMA_TOTAL_URA + $TOTAL_URA;
			
		$TOTAL_RECHAMADAS = utf8_encode($row['TOTAL_RECHAMADAS']);
			$SOMA_TOTAL_RECHAMADAS = $SOMA_TOTAL_RECHAMADAS + $TOTAL_RECHAMADAS;
			
		$PERC = utf8_encode($row['PERC']);
		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		$texto = '<tr>';
		echo incrementa_tabela($texto);
		
			$DATA = date("d-m-Y", strtotime($DATA));   			
			$texto = "<td>$DATA</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$DIA_SEMANA</td>";
			echo incrementa_tabela($texto);
			
			$incrementa_grafico = $incrementa_grafico.",['$DATA ($DIA_SEMANA)'"; // INCREMENTA OS DADOS DO GRÁFICO
			
			$TOTAL_URA = number_format($TOTAL_URA, 0, ',', '.');
			$texto = "<td>$TOTAL_URA</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_RECHAMADAS = number_format($TOTAL_RECHAMADAS, 0, ',', '.');
			$texto = "<td>$TOTAL_RECHAMADAS</td>";
			echo incrementa_tabela($texto);
			
			$PERC_grafico = number_format($PERC, 2, '.', '');
			$PERC = number_format($PERC, 2, ',', '.');
			$texto = "<td>$PERC%</td>";
			echo incrementa_tabela($texto);
			
			$incrementa_grafico = $incrementa_grafico.",$PERC_grafico]"; // INCREMENTA OS DADOS DO GRÁFICO
			
			if($PERC_grafico > $max) $max = $PERC_grafico; // ALTERA O VALOR MÁXIMO DE 'Y' DO GRÁFICO
			if($PERC_grafico < $min) $min = $PERC_grafico; // ALTERA O VALOR MÍNIMO DE 'Y' DO GRÁFICO
			
		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM

// IMPRIME <TR> FINALIZADORA - INÍCIO
$PERC_FINAL = $SOMA_TOTAL_RECHAMADAS / $SOMA_TOTAL_URA * 100;
$PERC_FINAL = number_format($PERC_FINAL, 2, ',', '.');
$SOMA_TOTAL_URA = number_format($SOMA_TOTAL_URA, 0, ',', '.');
$SOMA_TOTAL_RECHAMADAS = number_format($SOMA_TOTAL_RECHAMADAS, 0, ',', '.');

echo "</tbody><tr class='w3-indigo'>";
$tabela = $tabela."<tr>";
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$SOMA_TOTAL_URA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$SOMA_TOTAL_RECHAMADAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$PERC_FINAL%</b></td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>

</body>
</html>

<script>  
$('#tabela').DataTable( {
	 "columnDefs": [ {
      "targets": [ 0 ],
      "orderable": false
    } ]
} );
</script>