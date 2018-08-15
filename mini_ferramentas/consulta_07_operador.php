<?php
$nome_relatorio = "tma_operador"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "TMA - Operador"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$SOMA_TOTAL_DE_ATENDIMENTOS = 0;
$SOMA_TMA = 0;

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo "<br><br><b style='color: red'>Dica 1:</b> Clique no nome do operador para rastrear os atendimentos.";
	echo "<br><br><b style='color: red'>Dica 2:</b> Clique no nome do supervisor para listar o TMA dos operadores vinculados.";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>NOME OPERADOR</b></td>";
	echo incrementa_tabela($texto);	
	
	$texto = "<td><b>MATRÍCULA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ID</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>SUPERVISOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL DE ATENDIMENTOS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TMA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$sql = "select MATRICULA, id_operador ID, b.NOME, SUPERVISOR, count (*) TOTAL_DE_ATENDIMENTOS, avg(tempo_atend) TMA from tb_eventos_dac as a
							inner join tb_colaboradores_indra as b
							on a.id_operador = b.login_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and datepart(dw,data_hora) in $in_semana
							group by MATRICULA, id_operador, b.NOME, SUPERVISOR";
	echo $sql;
	
	$query = $pdo->prepare($sql);
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		$NOME = utf8_encode($row['NOME']);
			if($NOME == "") $NOME = "OPERADOR SEM NOME CADASTRADO";
		$MATRICULA = utf8_encode($row['MATRICULA']);
			$MATRICULA = number_format($MATRICULA, 0, '', '');
		$ID = utf8_encode($row['ID']);
			$ID = number_format($ID, 0, '', '');
		$SUPERVISOR = utf8_encode($row['SUPERVISOR']);
		$TOTAL_DE_ATENDIMENTOS = utf8_encode($row['TOTAL_DE_ATENDIMENTOS']);
			$SOMA_TOTAL_DE_ATENDIMENTOS = $SOMA_TOTAL_DE_ATENDIMENTOS + $TOTAL_DE_ATENDIMENTOS;
		$TMA = utf8_encode($row['TMA']);		
			$SOMA_TMA = $SOMA_TMA + ($TMA * $TOTAL_DE_ATENDIMENTOS);
		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		$texto = '<tr>';
		echo incrementa_tabela($texto);
			
			echo "<td><a class='w3-text-indigo' title='Rastrear Atendimentos' href= \"lista_atendimentos_operador.php?ID=$ID&data_inicial=$data_inicial&data_final=$data_final&txt_dias_semana=$txt_dias_semana&in_semana=$in_semana\" target=\"_blank\">$NOME</a></td>";
			$tabela=$tabela."<td>$NOME</td>";
			
			$texto = "<td>$MATRICULA</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$ID</td>";
			echo incrementa_tabela($texto);
			
			if(isset($array_supervisores[$SUPERVISOR])) $supervisor_nome = $array_supervisores[$SUPERVISOR];
			else $supervisor_nome = "";
			echo "<td><a class='w3-text-indigo' title='Listar Operadores Vinculados' href= \"lista_operadores_vinculados.php?SUPERVISOR=$SUPERVISOR&data_inicial=$data_inicial&data_final=$data_final&txt_dias_semana=$txt_dias_semana&in_semana=$in_semana&supervisor_nome=$supervisor_nome\" target=\"_blank\">$supervisor_nome</a></td>";
			$tabela=$tabela."<td>$supervisor_nome</td>";
			
			$TOTAL_DE_ATENDIMENTOS = number_format($TOTAL_DE_ATENDIMENTOS, 0, ',', '.');
			$texto = "<td>$TOTAL_DE_ATENDIMENTOS</td>";
			echo incrementa_tabela($texto);
			
			$TMA = number_format($TMA, 0, ',', '.');
			$texto = "<td>$TMA</td>";
			echo incrementa_tabela($texto);			
			
		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM

// IMPRIME <TR> FINALIZADORA - INÍCIO

$SOMA_TMA = $SOMA_TMA / $SOMA_TOTAL_DE_ATENDIMENTOS;
$SOMA_TOTAL_DE_ATENDIMENTOS = number_format($SOMA_TOTAL_DE_ATENDIMENTOS, 0, ',', '.');
$SOMA_TMA = number_format($SOMA_TMA, 0, ',', '.');

echo "</tbody><tr class='w3-indigo'>";
$tabela = $tabela."<tr>";
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);	
	
	$texto = "<td><b>TOTAL DE ATENDIMENTOS / MÉDIA TMA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$SOMA_TOTAL_DE_ATENDIMENTOS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$SOMA_TMA</b></td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
// include"imprime_grafico.php";// IMPRIME O GRÁFICO

echo"
<script>

    $('#tabela').DataTable( {
        'order': [[ 5, 'desc' ]]
    } );

</script>";
?>