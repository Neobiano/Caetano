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
$nome_relatorio = "percentual_de_transferencias"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Percentual de Transferências"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";
$dados_grafico = "['Data','$titulo','Qtde Atendimentos']";
$inicio = defineTime();

//VARIÁVEIS TOTALIZADORAS
$TOTAL_SEM_TRANSFERENCIA = 0;
$TOTAL_COM_TRANSFERENCIA = 0;
$PERCENTUAL_TOTAL = 0;
$TOTAL_TRANSFERENCIAS_PERIODO = 0;

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div id="divtitulo" class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DATA &nbsp</b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td ><b>DIA DA SEMANA &nbsp</b> 
    
    </td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td class='tooltip'><b>TOTAL DE LIGAÇÕES * &nbsp</b> 
    <span class='tooltiptext'>LIGAÇÕES distintas no ATC (atendimento humano) com tempo de atendimento > 0 segundos</span>
    </td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td class='tooltip'><b>QUANTIDADE DE TRANSFERÊNCIAS * &nbsp</b>
    <span class='tooltiptext'>QUANTIDADE DE TRANSFERÊNCIAS = TOTAL DE ATENDIMENTOS - TOTAL DE LIGAÇÕES </span>
    </td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td class='tooltip'><b>TOTAL DE ATENDIMENTOS *&nbsp</b>
    <span class='tooltiptext'>ATENDIMENTOS no ATC (atendimento humano) com tempo de atendimento > 0 segundos</span>
    </td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>PERCENTUAL DE TRANSFERÊNCIAS &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$sql = "select datepart(dw,data_hora) DIA_SEMANA, convert(date,data_hora,11) DATA, count(callid) TOTAL_ATENDIMENTOS, count(distinct callid) TOTAL_ATSTRANSF,((cast(count(callid) as float) - cast(count(distinct callid) as float))/cast(count(callid) as float)*100) PERCENTUAL_DE_TRANSFERENCIA from tb_eventos_DAC
							where data_hora between	'$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and datepart(dw,data_hora) in $in_semana and cod_fila in (select cod_fila from tb_filas where desc_fila like 'CXA%')
							group by convert(date,data_hora,11), datepart(dw,data_hora)
							order by convert(date,data_hora,11), datepart(dw,data_hora)";
	
	//echo $sql;
	$query = $pdo->prepare($sql);
	$query->execute(); // EXECUTA A CONSULTA
	 
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		$DIA_SEMANA = utf8_encode($row['DIA_SEMANA']);
		include "traduz_dia_semana.php"; // TRADUZ O DIA DA SEMANA
		
		$DATA = utf8_encode($row['DATA']);
		
		$TOTAL_ATSTRANSF = utf8_encode($row['TOTAL_ATSTRANSF']);
			$TOTAL_SEM_TRANSFERENCIA = $TOTAL_SEM_TRANSFERENCIA + $TOTAL_ATSTRANSF;
			
		$TOTAL_ATENDIMENTOS = utf8_encode($row['TOTAL_ATENDIMENTOS']);
			$TOTAL_COM_TRANSFERENCIA = $TOTAL_COM_TRANSFERENCIA + $TOTAL_ATENDIMENTOS;
			
		$TOTAL_TRANSFERENCIAS = $TOTAL_ATENDIMENTOS - $TOTAL_ATSTRANSF;
			$TOTAL_TRANSFERENCIAS_PERIODO = $TOTAL_TRANSFERENCIAS_PERIODO + $TOTAL_TRANSFERENCIAS;
			
		$PERCENTUAL_DE_TRANSFERENCIA = utf8_encode($row['PERCENTUAL_DE_TRANSFERENCIA']);
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
			
			$TOTAL_ATSTRANSF = number_format($TOTAL_ATSTRANSF, 0, ',', '.');
			$texto = "<td>$TOTAL_ATSTRANSF</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_TRANSFERENCIAS = number_format($TOTAL_TRANSFERENCIAS, 0, ',', '.');
			$texto = "<td>$TOTAL_TRANSFERENCIAS</td>";
			echo incrementa_tabela($texto);
			
			$TOTAL_ATENDIMENTOS = number_format($TOTAL_ATENDIMENTOS, 0, ',', '.');
			$texto = "<td>$TOTAL_ATENDIMENTOS</td>";
			echo incrementa_tabela($texto);
			
			$PERCENTUAL_DE_TRANSFERENCIA_grafico = number_format($PERCENTUAL_DE_TRANSFERENCIA, 2, '.', '');
			$PERCENTUAL_DE_TRANSFERENCIA = number_format($PERCENTUAL_DE_TRANSFERENCIA, 2, ',', '.');
			$texto = "<td>$PERCENTUAL_DE_TRANSFERENCIA%</td>";
			echo incrementa_tabela($texto);
			
			$incrementa_grafico = $incrementa_grafico.",$PERCENTUAL_DE_TRANSFERENCIA_grafico,$TOTAL_ATENDIMENTOS]"; // INCREMENTA OS DADOS DO GRÁFICO
			
						
		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM

// IMPRIME <TR> FINALIZADORA - INÍCIO
$PERCENTUAL_TOTAL = ($TOTAL_COM_TRANSFERENCIA - $TOTAL_SEM_TRANSFERENCIA) / $TOTAL_COM_TRANSFERENCIA * 100;
$PERCENTUAL_TOTAL = number_format($PERCENTUAL_TOTAL, 2, ',', '.');
$TOTAL_COM_TRANSFERENCIA = number_format($TOTAL_COM_TRANSFERENCIA, 0, ',', '.');
$TOTAL_SEM_TRANSFERENCIA = number_format($TOTAL_SEM_TRANSFERENCIA, 0, ',', '.');
$TOTAL_TRANSFERENCIAS_PERIODO = number_format($TOTAL_TRANSFERENCIAS_PERIODO, 0, ',', '.');

echo "</tbody><tr class='w3-indigo'>";
$tabela = $tabela."<tr>";
	
	$texto = "<td></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$TOTAL_SEM_TRANSFERENCIA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$TOTAL_TRANSFERENCIAS_PERIODO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$TOTAL_COM_TRANSFERENCIA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>$PERCENTUAL_TOTAL%</b></td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
$parametros_adicionais = " pointSize: 5,";
include "imprime_grafico.php"; // IMPRIME O GRÁFICO
$fim = defineTime();
echo tempoDecorrido($inicio,$fim);
?>

</body>
</html>

<script>  
document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 
$('#tabela').DataTable( {
	"order": [[ 0, "asc" ]]
} );
</script>