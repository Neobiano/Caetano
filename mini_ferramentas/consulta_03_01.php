<?php
$nome_relatorio = "quantidade_de_operadores"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Quantidade de Operadores - Intervalo de 30 minutos"; // MESMO NOME DO INDEX
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
		
	echo "<br><br><b style='color: red'>Dica:</b> O total de operadores dia a dia também pode ser vizualidado no gráfico ao final da página.";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DATA</b></td>";
	echo incrementa_tabela($texto);	
	
	$texto = "<td><b>FAIXA DE HORÁRIO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>QUANTIDADE DE OPERADORES</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	if($select_ilhas == 0) $query = $pdo->prepare("select a.DIA_SEMANA, a.DATA, a.HORA, a.MINUTO, a.TOTAL_OPERADORES_FAIXA_HORARIO, b.TOTAL_OPERADORES_DIA
							from 
							(
							select datepart(dw,data_hora) DIA_SEMANA, convert(date,data_hora,11) DATA, datepart(hh,data_hora) HORA, datepart(minute,data_hora)/30 MINUTO, count(distinct id_operador) TOTAL_OPERADORES_FAIXA_HORARIO from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and id_operador is not NULL and datepart(dw,data_hora) in $in_semana
							group by convert(date,data_hora,11), datepart(hh,data_hora), datepart(dw,data_hora), datepart(minute,data_hora)/30
							) as a
							inner join 
							(
							select datepart(dw,data_hora) DIA_SEMANA, convert(date,data_hora,11) DATA, count(distinct id_operador) TOTAL_OPERADORES_DIA from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and id_operador is not NULL and datepart(dw,data_hora) in $in_semana
							group by convert(date,data_hora,11), datepart(dw,data_hora)
							) as b
							on a.DATA = b.DATA
							order by a.DATA, a.HORA, a.MINUTO");
	
	else $query = $pdo->prepare("select a.DIA_SEMANA, a.DATA, a.HORA, a.MINUTO, a.TOTAL_OPERADORES_FAIXA_HORARIO, b.TOTAL_OPERADORES_DIA
							from 
							(
							select datepart(dw,data_hora) DIA_SEMANA, convert(date,data_hora,11) DATA, datepart(hh,data_hora) HORA, datepart(minute,data_hora)/30 MINUTO, count(distinct id_operador) TOTAL_OPERADORES_FAIXA_HORARIO from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and id_operador is not NULL and datepart(dw,data_hora) in $in_semana and cod_fila in ($in_ilhas)
							group by convert(date,data_hora,11), datepart(hh,data_hora), datepart(dw,data_hora), datepart(minute,data_hora)/30
							) as a
							inner join 
							(
							select datepart(dw,data_hora) DIA_SEMANA, convert(date,data_hora,11) DATA, count(distinct id_operador) TOTAL_OPERADORES_DIA from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and id_operador is not NULL and datepart(dw,data_hora) in $in_semana and cod_fila in ($in_ilhas)
							group by convert(date,data_hora,11), datepart(dw,data_hora)
							) as b
							on a.DATA = b.DATA
							order by a.DATA, a.HORA, a.MINUTO");
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	$DATA_ANTERIOR = 0;
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		$DIA_SEMANA = utf8_encode($row['DIA_SEMANA']);
		include "traduz_dia_semana.php"; // TRADUZ O DIA DA SEMANA
		
		$DATA = utf8_encode($row['DATA']);
		
		$HORA = utf8_encode($row['HORA']);
		$MINUTO = utf8_encode($row['MINUTO']);
			//$SOMA_TOTAL_RECEBIDAS = $SOMA_TOTAL_RECEBIDAS + $TOTAL_RECEBIDAS;
			
		$TOTAL_OPERADORES_FAIXA_HORARIO = utf8_encode($row['TOTAL_OPERADORES_FAIXA_HORARIO']);
			//$SOMA_TOTAL_RETIDAS = $SOMA_TOTAL_RETIDAS + $TOTAL_RETIDAS;
			
		$TOTAL_OPERADORES_DIA = utf8_encode($row['TOTAL_OPERADORES_DIA']);
			
			if($DATA!=$DATA_ANTERIOR){
					$TOTAL_OPERADORES = $TOTAL_OPERADORES + $TOTAL_OPERADORES_DIA;
					$TOTAL_DIAS = $TOTAL_DIAS + 1;
					$DATA_ANTERIOR = $DATA;
			}
		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		
		if (($HORA == 00) && ($MINUTO == 0) && ($i > 0)){
			echo "<tr class = 'w3-topbartable w3-border-indigo'><td>&nbsp</td><td><b>Total de Operadores em $DATA_TXT</b></td><td><b>$TOTAL_OPERADORES_DIA_FINALIZADOR</b></td></tr><tr>";
		}
		else echo '<tr>';
		$tabela = $tabela."<tr>";
		
			// TRATA VARIÁVEL FAIXA DE HORÁRIO - INÍCIO
			if($MINUTO == 0) $MINUTO = "00";
			else $MINUTO = "30";			
			if($HORA < 10) $HORA = "0$HORA";			
			$HORA_MINUTO_INICIAL = "$HORA:$MINUTO";
			
			if ($MINUTO == "30"){
				$INC_HORA = $HORA + 1;
				if($INC_HORA < 10) $INC_HORA = "0$INC_HORA";
				$HORA_MINUTO_FINAL = "$INC_HORA:00";
				if($HORA == "23"){
					$HORA_MINUTO_FINAL = "00:00";
				}
			}
			ELSE{
				$HORA_MINUTO_FINAL = "$HORA:30";
			}			
			// TRATA VARIÁVEL FAIXA DE HORÁRIO - FIM
			$FAIXA_HORARIO = "$HORA_MINUTO_INICIAL - $HORA_MINUTO_FINAL";
		
			$DATA = date("d-m-Y", strtotime($DATA));   
			$texto = "<td>$DATA ($DIA_SEMANA)</td>";
			echo incrementa_tabela($texto);			
			
			if($FAIXA_HORARIO == '23:30 - 00:00') $incrementa_grafico = $incrementa_grafico.",['$DATA ($DIA_SEMANA)'"; // INCREMENTA OS DADOS DO GRÁFICO
//			$FAIXA_HORARIO_TABELA = $FAIXA_HORARIO;
			
			$texto = "<td>$FAIXA_HORARIO</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_OPERADORES_FAIXA_HORARIO = number_format($TOTAL_OPERADORES_FAIXA_HORARIO, 0, ',', '.');
			$texto = "<td>$TOTAL_OPERADORES_FAIXA_HORARIO</td>";
			echo incrementa_tabela($texto);
			
			if($FAIXA_HORARIO == '23:30 - 00:00') $incrementa_grafico = $incrementa_grafico.",$TOTAL_OPERADORES_DIA]"; // INCREMENTA OS DADOS DO GRÁFICO
			
			if($FAIXA_HORARIO == '23:30 - 00:00') if($TOTAL_OPERADORES_DIA > $max) $max = $TOTAL_OPERADORES_DIA; // ALTERA O VALOR MÁXIMO DE 'Y' DO GRÁFICO
			if($FAIXA_HORARIO == '23:30 - 00:00') if($TOTAL_OPERADORES_DIA < $min) $min = $TOTAL_OPERADORES_DIA; // ALTERA O VALOR MÍNIMO DE 'Y' DO GRÁFICO
			
		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		$t_inicial = strtotime($DATA);
		$DATA_TXT = date('d/m/Y',$t_inicial);
		$TOTAL_OPERADORES_DIA_FINALIZADOR = number_format($TOTAL_OPERADORES_DIA, 0, ',', '.');
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM
	echo "<tr class = 'w3-topbartable w3-border-indigo'><td>&nbsp</td><td><b>Total de Operadores em $DATA_TXT</b></td><td><b>$TOTAL_OPERADORES_DIA_FINALIZADOR</b></td></tr><tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>";
	
	$tabela = $tabela."<tr><td></td><td><b>Total de Operadores em $DATA_TXT</b></td><td><b>$TOTAL_OPERADORES_DIA_FINALIZADOR</b></td></tr><tr><td></td><td></td><td></td></tr>";


/*
// CALCULA QUANTIDADE DE OPERADORES NO PERÍODO - INÍCIO
$query = $pdo->prepare("select count(distinct id_operador) TOTAL_OPERADORES_PERIODO from tb_eventos_dac where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and id_operador is not NULL and datepart(dw,data_hora) in $in_semana");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$TOTAL_OPERADORES_PERIODO = utf8_encode($row['TOTAL_OPERADORES_PERIODO']);
}		
// CALCULA QUANTIDADE DE OPERADORES NO PERÍODO - FIM
*/

if($TOTAL_DIAS > 0) $MEDIA_OPERADORES = $TOTAL_OPERADORES / $TOTAL_DIAS;
else $MEDIA_OPERADORES = 0;
$MEDIA_OPERADORES = number_format($MEDIA_OPERADORES, 0, ',', '.');		

echo "<tr class='w3-indigo'><td></td><td><b>Média de Operadores no Período ($data_inicial_texto à $data_final_texto)</b></td><td><b>$MEDIA_OPERADORES</b></td></tr>"; // IMPRIME O TOTAL DE OPERADORES NO PERÍODO
$tabela = $tabela."<tr><td></td><td><b>Total de Operadores no Período:</b></td><td><b>$MEDIA_OPERADORES</b></td></tr>";

echo incrementa_tabela($texto);
include "finaliza_tabela.php"; // FINALIZA A TABELA
include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>