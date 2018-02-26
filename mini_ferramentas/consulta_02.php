<?php
$nome_relatorio = "percentual_de_retencao_ura"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Percentual de Retenção na URA"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$SOMA_RECEBIDAS = 0;
$SOMA_RETIDAS = 0;
$SOMA_RECEBIDAS_LIQUIDO = 0;
$SOMA_RETIDAS_LIQUIDO = 0;

//IMPRIME TÍTULO DA CONSULTA
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo "<br><br><b style='color: red'>Observação:</b> Os campos que contém \"LIQ\" não contabilizam as filas que não possuem funcionalidades na URA.";
echo "</div>";

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DATA</b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td><b>DIA DA SEMANA</b></td>";
	echo incrementa_tabela($texto);	
	
	$texto = "<td><b>TOTAL RECEBIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL RETIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PERCENTUAL DE RETENÇÃO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL RECEBIDAS (LÍQ)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL RETIDAS (LÍQ)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PERCENTUAL DE RETENÇÃO (LÍQ)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA - deus me defenda.. muito confusa essa consulta
	$query = $pdo->prepare("select x.DIA_SEMANA, x.DATA, x.TOTAL_URA as TOTAL_RECEBIDAS, TOTAL_URA_LIQUIDO as TOTAL_RECEBIDAS_LIQUIDO,
							(x.TOTAL_URA - TOTAL_DAC_TODAS) as TOTAL_RETIDAS,
							(TOTAL_URA_LIQUIDO - y.TOTAL_DAC) as TOTAL_RETIDAS_LIQUIDO,
							(cast(TOTAL_URA_LIQUIDO as float) - cast(y.TOTAL_DAC as float)) / cast(TOTAL_URA_LIQUIDO as float) * 100 as PERCENTUAL_DE_RETENCAO_LIQ,
							(cast(x.TOTAL_URA as float) - cast(z.TOTAL_DAC_TODAS as float)) / cast(x.TOTAL_URA as float) * 100 as PERCENTUAL_DE_RETENCAO
							from
							(
							select datepart(dw,data_hora) DIA_SEMANA, convert(date,data_hora,11) DATA, count(distinct callid) TOTAL_URA from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
							group by datepart(dw,data_hora), convert(date,data_hora,11)

							) as x
							inner join
							(
							select datepart(dw,data_hora) DIA_SEMANA, convert(date,data_hora,11) DATA, count(distinct callid) TOTAL_URA_LIQUIDO from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and callid not in (select a.callid from
							(
							select * from tb_eventos_DAC
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and cod_fila in (63,64,99,100,110,130) and tempo_atend > 0
							) as a
							inner join
							(
							select callid, min(data_hora) data_hora from tb_eventos_DAC
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0
							group by callid
							) as b on a.callid = b.callid and a.data_hora = b.data_hora
							)
							group by datepart(dw,data_hora), convert(date,data_hora,11)							
							) as h on x.DIA_SEMANA = h.DIA_SEMANA and x.DATA = h.DATA
							inner join
							(
							select datepart(dw,a.data_hora) DIA_SEMANA, convert(date,a.data_hora,11) DATA, count(*) TOTAL_DAC from
							(
							select * from tb_eventos_DAC
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and cod_fila not in (63,64,99,100,110,130) and tempo_atend > 0
							) as a
							inner join
							(
							select callid, min(data_hora) as data_hora from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and cod_fila not in (63,64,99,100,110,130) and tempo_atend > 0
							group by callid
							) as b on a.callid = b.callid and a.data_hora = b.data_hora
							group by datepart(dw,a.data_hora), convert(date,a.data_hora,11)
							) as y on x.DIA_SEMANA = y.DIA_SEMANA and x.DATA = y.DATA
							inner join
							(
							select datepart(dw,a.data_hora) DIA_SEMANA, convert(date,a.data_hora,11) DATA, count(*) TOTAL_DAC_TODAS from
							(
							select * from tb_eventos_DAC
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0
							) as a
							inner join
							(
							select callid, min(data_hora) as data_hora from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0
							group by callid
							) as b on a.callid = b.callid and a.data_hora = b.data_hora
							group by datepart(dw,a.data_hora), convert(date,a.data_hora,11)
							) as z on x.DIA_SEMANA = z.DIA_SEMANA and x.DATA = z.DATA");
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		$DIA_SEMANA = utf8_encode($row['DIA_SEMANA']);
		include "traduz_dia_semana.php"; // TRADUZ O DIA DA SEMANA
		
		$DATA = date(utf8_encode($row['DATA']));
		
		$TOTAL_RECEBIDAS = utf8_encode($row['TOTAL_RECEBIDAS']);
		$SOMA_RECEBIDAS += $TOTAL_RECEBIDAS;
		
		$TOTAL_RECEBIDAS_LIQUIDO = utf8_encode($row['TOTAL_RECEBIDAS_LIQUIDO']);
		$SOMA_RECEBIDAS_LIQUIDO += $TOTAL_RECEBIDAS_LIQUIDO;
			
		$TOTAL_RETIDAS = utf8_encode($row['TOTAL_RETIDAS']);
		$SOMA_RETIDAS += $TOTAL_RETIDAS;
		
		$TOTAL_RETIDAS_LIQUIDO = utf8_encode($row['TOTAL_RETIDAS_LIQUIDO']);
		$SOMA_RETIDAS_LIQUIDO += $TOTAL_RETIDAS_LIQUIDO;
			
		$PERCENTUAL_DE_RETENCAO = utf8_encode($row['PERCENTUAL_DE_RETENCAO']);
			
		$PERCENTUAL_DE_RETENCAO_LIQ = utf8_encode($row['PERCENTUAL_DE_RETENCAO_LIQ']);
		
		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		$texto = '<tr>';
		echo incrementa_tabela($texto);
		
			$DATA = date("d-m-Y", strtotime($DATA));   
			$texto = "<td>$DATA</td>";
			echo incrementa_tabela($texto);
			$incrementa_grafico = $incrementa_grafico.",['$DATA ($DIA_SEMANA)'"; // INCREMENTA OS DADOS DO GRÁFICO
			
			$texto = "<td>$DIA_SEMANA</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_RECEBIDAS = number_format($TOTAL_RECEBIDAS, 0, ',', '.');
			$texto = "<td>$TOTAL_RECEBIDAS</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_RETIDAS = number_format($TOTAL_RETIDAS, 0, ',', '.');
			$texto = "<td>$TOTAL_RETIDAS</td>";
			echo incrementa_tabela($texto);
			
			$PERCENTUAL_DE_RETENCAO = number_format($PERCENTUAL_DE_RETENCAO, 2, ',', '.');
			$texto = "<td>$PERCENTUAL_DE_RETENCAO%</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_RECEBIDAS_LIQUIDO = number_format($TOTAL_RECEBIDAS_LIQUIDO, 0, ',', '.');
			$texto = "<td>$TOTAL_RECEBIDAS_LIQUIDO</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_RETIDAS_LIQUIDO = number_format($TOTAL_RETIDAS_LIQUIDO, 0, ',', '.');
			$texto = "<td>$TOTAL_RETIDAS_LIQUIDO</td>";
			echo incrementa_tabela($texto);
			
			$PERCENTUAL_DE_RETENCAO_LIQ = number_format($PERCENTUAL_DE_RETENCAO_LIQ, 2, ',', '.');
			$texto = "<td>$PERCENTUAL_DE_RETENCAO_LIQ%</td>";
			echo incrementa_tabela($texto);
			
		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM

// IMPRIME <TR> FINALIZADORA - INÍCIO

$PERCENTUAL_RETIDAS = $SOMA_RETIDAS / $SOMA_RECEBIDAS * 100;
$PERCENTUAL_RETIDAS_LIQ = $SOMA_RETIDAS_LIQUIDO / $SOMA_RECEBIDAS_LIQUIDO * 100;

$texto = "</tbody><tr class='w3-indigo'>";
echo incrementa_tabela($texto);

	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$SOMA_RECEBIDAS = number_format($SOMA_RECEBIDAS, 0, ',', '.');
	$texto = "<td><b>$SOMA_RECEBIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$SOMA_RETIDAS = number_format($SOMA_RETIDAS, 0, ',', '.');
	$texto = "<td><b>$SOMA_RETIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$PERCENTUAL_RETIDAS = number_format($PERCENTUAL_RETIDAS, 2, ',', '.');
	$texto = "<td><b>$PERCENTUAL_RETIDAS%</b></td>";
	echo incrementa_tabela($texto);
	
	$SOMA_RECEBIDAS_LIQUIDO = number_format($SOMA_RECEBIDAS_LIQUIDO, 0, ',', '.');
	$texto = "<td><b>$SOMA_RECEBIDAS_LIQUIDO</b></td>";
	echo incrementa_tabela($texto);
	
	$SOMA_RETIDAS_LIQUIDO = number_format($SOMA_RETIDAS_LIQUIDO, 0, ',', '.');
	$texto = "<td><b>$SOMA_RETIDAS_LIQUIDO</b></td>";
	echo incrementa_tabela($texto);
	
	$PERCENTUAL_RETIDAS_LIQ = number_format($PERCENTUAL_RETIDAS_LIQ, 2, ',', '.');
	$texto = "<td><b>$PERCENTUAL_RETIDAS_LIQ%</b></td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>

<script>  
$('#tabela').DataTable( {
	 "columnDefs": [ {
      "targets": [ 0 ],
      "orderable": false
    } ]
} );
</script>