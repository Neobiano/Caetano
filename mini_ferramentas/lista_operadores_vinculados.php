<!DOCTYPE html>
<html>
<head>
<title>RADAR CARTÕES - Painel de Monitoramento - Cartão de Crédito</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabela').DataTable( {
        "order": [[ 5, "desc" ]]
    } );
} );
</script>

</head>
<body>
<?php 
include "conecta.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$data_inicial = $_GET['data_inicial'];
$data_final = $_GET['data_final'];
$SUPERVISOR = $_GET['SUPERVISOR'];
$txt_dias_semana = $_GET['txt_dias_semana'];
$in_semana = $_GET['in_semana'];
$supervisor_nome = $_GET['supervisor_nome'];

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);

$t_final= strtotime($data_final);
$data_final_texto = date('d/m/Y',$t_final);
//Conversão Data Texto - Fim

//VARIÁVEIS TOTALIZADORAS
$SOMA_TOTAL_DE_ATENDIMENTOS = 0;
$SOMA_TMA = 0;

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>TMA Operadores Vinculados</b>";
echo "<br><br><b>Supervisor:</b> $supervisor_nome ($SUPERVISOR)";
echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto";
echo "<br><br><b><i>Dias da Semana Selecionados:</i></b> $txt_dias_semana";
echo "<br><br><b style='color: red'>Dica:</b> Clique no nome do operador para rastrear os atendimentos.";
echo "<br><br>";

echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4" id="tabela"><thead>';
echo '<tr class="w3-indigo w3-tiny">';
echo '<td><b>NOME OPERADOR</b></td>';
echo '<td><b>MATRÍCULA</b></td>';
echo '<td><b>ID</b></td>';
echo '<td><b>SUPERVISOR</b></td>';
echo '<td><b>TOTAL DE ATENDIMENTOS</b></td>';
echo '<td><b>TMA</b></td>';
echo '</tr></thead><tbody>';

$query = $pdo->prepare("select MATRICULA, id_operador ID, desc_operador NOME, SUPERVISOR, count (*) TOTAL_DE_ATENDIMENTOS, avg(tempo_atend) TMA from tb_eventos_dac as a
							inner join tb_colaboradores_indra as b
							on a.id_operador = b.login_dac
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and SUPERVISOR = '$SUPERVISOR' and datepart(dw,data_hora) in $in_semana
							group by MATRICULA, id_operador, desc_operador, SUPERVISOR
							order by TMA desc");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		$NOME = utf8_encode($row['NOME']);
		$MATRICULA = utf8_encode($row['MATRICULA']);
		$ID = utf8_encode($row['ID']);
		$SUPERVISOR = utf8_encode($row['SUPERVISOR']);
		$TOTAL_DE_ATENDIMENTOS = utf8_encode($row['TOTAL_DE_ATENDIMENTOS']);
			$SOMA_TOTAL_DE_ATENDIMENTOS = $SOMA_TOTAL_DE_ATENDIMENTOS + $TOTAL_DE_ATENDIMENTOS;
		$TMA = utf8_encode($row['TMA']);		
			$SOMA_TMA = $SOMA_TMA + ($TMA * $TOTAL_DE_ATENDIMENTOS);
		// RECEBE RESULTADOS DA CONSULTA - FIM
	
	echo '<tr>';
		echo "<td><a class='w3-text-indigo' title='Rastrear Atendimentos' href= \"lista_atendimentos_operador.php?ID=$ID&data_inicial=$data_inicial&data_final=$data_final&txt_dias_semana=$txt_dias_semana&in_semana=$in_semana\" target=\"_blank\">$NOME</a></td>";
		
		echo "<td>$MATRICULA</td>";
		
		echo "<td>$ID</td>";
	
		echo "<td>$supervisor_nome</td>";
		
		$TOTAL_DE_ATENDIMENTOS = number_format($TOTAL_DE_ATENDIMENTOS, 0, ',', '.');
		echo "<td>$TOTAL_DE_ATENDIMENTOS</td>";
		
		$TMA = number_format($TMA, 0, ',', '.');
		echo "<td>$TMA</td>";

	echo '</tr>';
}

// IMPRIME <TR> FINALIZADORA - INÍCIO

$SOMA_TMA = $SOMA_TMA / $SOMA_TOTAL_DE_ATENDIMENTOS;
$SOMA_TOTAL_DE_ATENDIMENTOS = number_format($SOMA_TOTAL_DE_ATENDIMENTOS, 0, ',', '.');
$SOMA_TMA = number_format($SOMA_TMA, 0, ',', '.');

echo "</tbody><tr class='w3-indigo'>";
	
	echo "<td></td>";
	
	echo "<td></td>";
	
	echo "<td></td>";
	
	echo "<td><b>TOTAL DE ATENDIMENTOS / MÉDIA TMA</b></td>";
	
	echo "<td><b>$SOMA_TOTAL_DE_ATENDIMENTOS</b></td>";
	
	echo "<td><b>$SOMA_TMA</b></td>";
	
echo "</tr>";
// IMPRIME <TR> FINALIZADORA - FIM


echo "</div>";
echo "</table>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>