<?php
$nome_relatorio = "percentual_de_nao_categorizacao"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Percentual de Não Categorização"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$SOMA_TOTAL_ATENDIDAS = 0;
$SOMA_TOTAL_CATEGORIZADAS = 0;

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo "<br><br><b style='color: red'>Dica:</b> Clique no nome do operador para listar os atendimentos que não foram categorizados durante o atendimento.";
	echo "<br><br><b style='color: red'>Observação:</b> Devido problemas com o registro na base de dados pela <i>INDRA</i>, os valores exibidos na consulta podem divergir da realidade.";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO	
	$texto = "<td><b>NOME DO OPERADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>MATRÍCULA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ID</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL ATENDIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL CATEGORIZADAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PERCENTUAL NÃO CATEGORIZADO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$query = $pdo->prepare("select a.NOME NOME, a.MATRICULA MATRICULA, a.LOGIN_DAC ID, TOTAL_ATENDIDAS, TOTAL_CATEGORIZADAS, (cast(TOTAL_ATENDIDAS as float) - cast(TOTAL_CATEGORIZADAS as float)) / cast(TOTAL_ATENDIDAS as float) * 100 PERCENTUAL_NAO_CATEGORIZADO
							from tb_colaboradores_indra as a
							inner join
							(
							select id_operador LOGIN_DAC, count(distinct callid) TOTAL_ATENDIDAS from tb_eventos_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and datepart(dw,data_hora) in $in_semana
							group by id_operador
							) as b
							on a.LOGIN_DAC = b.LOGIN_DAC
							inner join
							(
							select login_front MATRICULA, count(distinct callid) TOTAL_CATEGORIZADAS from tb_log_categorizacao
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana
							group by login_front
							) as c
							on a.MATRICULA = c.MATRICULA
							order by PERCENTUAL_NAO_CATEGORIZADO desc");
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		
		$NOME = utf8_encode($row['NOME']);
			if($NOME == "") $NOME = "OPERADOR SEM NOME CADASTRADO";
		
		$MATRICULA = utf8_encode($row['MATRICULA']);
		
		$ID = utf8_encode($row['ID']);
		$ID = number_format($ID, 0, '', '');
		
		$TOTAL_ATENDIDAS = utf8_encode($row['TOTAL_ATENDIDAS']);
			$SOMA_TOTAL_ATENDIDAS = $SOMA_TOTAL_ATENDIDAS + $TOTAL_ATENDIDAS;
		
		$TOTAL_CATEGORIZADAS = utf8_encode($row['TOTAL_CATEGORIZADAS']);
			$SOMA_TOTAL_CATEGORIZADAS = $SOMA_TOTAL_CATEGORIZADAS + $TOTAL_CATEGORIZADAS;
		
		$PERCENTUAL_NAO_CATEGORIZADO = utf8_encode($row['PERCENTUAL_NAO_CATEGORIZADO']);
			if($PERCENTUAL_NAO_CATEGORIZADO < 0) $PERCENTUAL_NAO_CATEGORIZADO = 0;
		
		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		$texto = '<tr>';
		echo incrementa_tabela($texto);
		
			echo "<td><a class='w3-text-indigo' title='Rastrear Atendimentos não Categorizados Durante o Atendimento' href= \"lista_atendimentos_nao_categorizados.php?NOME=$NOME&data_inicial=$data_inicial&data_final=$data_final&txt_dias_semana=$txt_dias_semana&in_semana=$in_semana\" target=\"_blank\">$NOME</a></td>";
			$tabela=$tabela."<td>$NOME</td>";
			
			$texto = "<td>$MATRICULA</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$ID</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_ATENDIDAS = number_format($TOTAL_ATENDIDAS, 0, ',', '.');
			$texto = "<td>$TOTAL_ATENDIDAS</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_CATEGORIZADAS = number_format($TOTAL_CATEGORIZADAS, 0, ',', '.');
			$texto = "<td>$TOTAL_CATEGORIZADAS</td>";
			echo incrementa_tabela($texto);
			
			$PERCENTUAL_NAO_CATEGORIZADO = number_format($PERCENTUAL_NAO_CATEGORIZADO, 2, '.', '');
			$texto = "<td>$PERCENTUAL_NAO_CATEGORIZADO%</td>";
			echo incrementa_tabela($texto);
			
		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM

// IMPRIME <TR> FINALIZADORA - INÍCIO
$SOMA_PERCENTUAL_NAO_CATEGORIZADO = ($SOMA_TOTAL_ATENDIDAS - $SOMA_TOTAL_CATEGORIZADAS) / $SOMA_TOTAL_ATENDIDAS * 100;
if($SOMA_PERCENTUAL_NAO_CATEGORIZADO < 0) $SOMA_PERCENTUAL_NAO_CATEGORIZADO = 0;

$SOMA_PERCENTUAL_NAO_CATEGORIZADO = number_format($SOMA_PERCENTUAL_NAO_CATEGORIZADO, 2, ',', '.');

$SOMA_TOTAL_ATENDIDAS = number_format($SOMA_TOTAL_ATENDIDAS, 0, ',', '.');
$SOMA_TOTAL_CATEGORIZADAS = number_format($SOMA_TOTAL_CATEGORIZADAS, 0, ',', '.');

echo "</tbody><tr class='w3-indigo'>";
$tabela = $tabela."<tr>";
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$SOMA_TOTAL_ATENDIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$SOMA_TOTAL_CATEGORIZADAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$SOMA_PERCENTUAL_NAO_CATEGORIZADO%</b></td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
?>