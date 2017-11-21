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

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready( function () {
    $('#tabela').DataTable();
} );
</script>
</head>
<body>

<?php
$nome_relatorio = "verifica_alimentacao_bd"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Verifica Alimentação BD"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL


//IMPRIME TÍTULO DA CONSULTA
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
echo "<b>$titulo</b>";
echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
echo '</div>';


echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important; ">';

echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";

echo "<thead><tr class='w3-indigo'>";

	echo "<td><b>DATA</b></td>";
	echo "<td><b>TABELA</b></td>";
	echo "<td><b>QUANTIDADE DE FAIXAS DE HORÁRIO ALIMENTADAS</b></td>";
	
echo "</tr></thead><tdoby>";

$tabelas = array('tb_eventos_ura', 'tb_eventos_ura_2', 'tb_eventos_dac', 'tb_eventos_front', 'tb_dados_cadastrais', 'tb_log_categorizacao');

foreach($tabelas as $qual_tabela){
	
	$query = $pdo->prepare("select DIA, count(*) QTD_FAIXAS_HORARIO from
						(
						select CONVERT(varchar, data_hora, 103) DIA, datepart(hh,data_hora) HORA, count (*) TOTAL from $qual_tabela
						where data_hora between '$data_inicial' and '$data_final 23:59:59'
						group by CONVERT(varchar, data_hora, 103), datepart(hh,data_hora)
						) as a group by DIA
						having count(*) < 24
						order by DIA 
						");
	$query->execute(); // EXECUTA A CONSULTA

	for($i=0; $row = $query->fetch(); $i++){

		$DIA = utf8_encode($row['DIA']);
		$QTD_FAIXAS_HORARIO = utf8_encode($row['QTD_FAIXAS_HORARIO']);
		
		echo "<tr>";
			echo "<td>$DIA</td>";
			echo "<td><a class='w3-text-indigo' title='Rastrear Atendimentos' href= \"lista_tabela_diaria.php?dia=$DIA&qual_tabela=$qual_tabela\" target=\"_blank\">$qual_tabela</a></td>";
			echo "<td>$QTD_FAIXAS_HORARIO</td>";
		echo "</tr>";
	}	
}

echo "</tbody></table></div>";
?>


</body>
</html>

<script>
$(document).ready( function () {
    $('#tabela').DataTable();
} );
</script>