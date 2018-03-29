<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
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
$nome_relatorio = "transferencias_para_mesma_fila"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Transferências para Mesma Fila"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$TOTAL_SEM_TRANSFERENCIA = 0;
$TOTAL_COM_TRANSFERENCIA = 0;
$PERCENTUAL_TOTAL = 0;
$TOTAL_TRANSFERENCIAS_PERIODO = 0;

//IMPRIME TÍTULO DA CONSULTA
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
echo "<b>$titulo</b>";
echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
echo '</div>';

// INFORMA A CONSULTA
$sql = "select callid, data_hora, cod_fila from tb_eventos_dac where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and callid is not null and callid in (
						select callid from tb_eventos_dac
						where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and callid is not null
						group by callid
						having count(*) >= 3)
						order by callid, data_hora";
//echo $sql;
$query = $pdo->prepare($sql);
$query->execute(); // EXECUTA A CONSULTA

$todos_callids = array();
$callid_x_transf = array();
$qtd_transfs = 0;
$max_transf = 0;

$callids = array();
$transf_indevida = 0;
$percurso = array();
$data = array();

$callid_anterior = "";
$data_hora_callid_anterior = "";
$percurso_atual = array();
$cod_fila_anterior = 0;

// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
for($i=0; $row = $query->fetch(); $i++){
	
	// RECEBE RESULTADOS DA CONSULTA - INÍCIO
	$data_hora = utf8_encode($row['data_hora']);
	$callid = utf8_encode($row['callid']);
	$cod_fila = utf8_encode($row['cod_fila']);
	$cod_fila = number_format($cod_fila, 0, ',', '.');
	// RECEBE RESULTADOS DA CONSULTA - FIM
	
	if($callid != $callid_anterior){
		if($transf_indevida == 0) array_pop($todos_callids);
		
		$callid_x_transf[$callid] = array();
		$callid_x_transf[$callid]['data_hora'] = $data_hora;
		array_push($todos_callids, $callid);
		$qtd_transfs = 0;
		if($transf_indevida == 1){
			$qtd_linhas_consulta++;
			array_push($callids, "$callid_anterior");
			$percurso["$callid_anterior"] = $percurso_atual;
			$data["$callid_anterior"] = $data_hora_callid_anterior;
		}
		$percurso_atual = array();
		$transf_indevida = 0;
		$data_hora_callid_anterior = $data_hora;
	}

	else if($cod_fila == $cod_fila_anterior){
		$transf_indevida = 1;
	}
	
	array_push($percurso_atual, $cod_fila);
	$callid_anterior = $callid;
	$cod_fila_anterior = $cod_fila;
	
	array_push($callid_x_transf[$callid], $cod_fila);
	$qtd_transfs++;
	if($qtd_transfs > $max_transf && $transf_indevida == 1) $max_transf = $qtd_transfs;
	
}
if($transf_indevida == 0) array_pop($todos_callids);
// IMPRIME O RESULTADO DA CONSULTA - FIM



echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important; padding-top:16px !important;">';
echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";

echo "<thead><tr class='w3-indigo'>";
	echo "<td><b>CALLID</b></td>";
	echo "<td><b>DATA/HORA</b></td>";
	echo "<td><b>FILA_ORIGEM</b></td>";
	for($y=1; $y<$max_transf ; $y++){
		echo "<td><b>$y ª TRANSF.</b></td>";
	}
echo "</tr></thead><tbody>";

foreach($todos_callids as $callid){
	echo "<tr>";
		echo "<td>$callid</td>";
		$data_hora = $callid_x_transf[$callid]['data_hora'];
		echo "<td>$data_hora</td>";
		for($y=0; $y<$max_transf; $y++){
			if(isset($callid_x_transf[$callid][$y])) {
				$fila = $callid_x_transf[$callid][$y];
				$imprimir = $cod_desc[$fila];
			}
			else $imprimir = "";
			echo "<td>$imprimir</td>";
		}
	echo "</tr>";
}

echo "</tbody></table></div>";



?>

</body>
</html>

