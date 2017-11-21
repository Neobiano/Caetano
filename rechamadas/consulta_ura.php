<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabela').DataTable( {
        "order": [[ 1, "asc" ]]
    } );
} );
</script>

</head>
<body>

<div id="div_loading" class="w3-modal">
<div class="w3-modal-content" style="width:100%;height:100%;position:absolute;top:0;right:0;padding:0;margin:0;">
  <div class="w3-container w3-center w3-margin w3-padding-64">
	<img src="loading.gif" style="width:100px;">
	<p class="w3-text-red w3-center">Carregando...</p>
  </div>
</div>
</div>
<script>
	document.getElementById('div_loading').style.display='block';
</script>

<?php 
include "conecta.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$data_inicial = $_GET['data_inicial'];
$data_final = $_GET['data_final'];
$DADO = $_GET['DADO'];

echo "<title>Log URA - $DADO</title>";

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);
$t_final = strtotime($data_final);
$data_final_pre = date('m/d/Y',$t_final);
$data_final_texto = date('d/m/Y', strtotime("-1 day", strtotime($data_final_pre)));
//Conversão Data Texto - Fim

include "def_var_ura.php";

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Log URA - Atendimento Eletrônico</b><br><br>";
echo "<b>Telefone:</b> $DADO<br>";
echo "<b>Data Inicial:</b> $data_inicial_texto<br>";
echo "<b>Data Final:</b> $data_final_texto</b><br><br>";

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>CALLID</b></td>';
echo '<td><b>DATA_HORA</b></td>';
echo '<td><b>EVENTOS</b></td>';
echo '</tr></thead><tbody>';

$query = $pdo->prepare("select a.*
						from (select * from tb_eventos_ura
						where data_hora between '$data_inicial' and '$data_final') as a
						inner join (select distinct callid from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and cod_dado = 3 and valor_dado = '$DADO') as b
						on a.callid = b.callid");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	
	if ($i > 0) $callid_anterior = $callid;
	
	$callid = utf8_encode($row['callid']);
	$data_hora = utf8_encode($row['data_hora']);
	$cod_evento = utf8_encode($row['cod_evento']);
	
	echo '<tr>';	
	echo "<td>$callid</td>";	
	echo "<td>$data_hora</td>";
	
		$fluxo_ura_array = explode(";", $cod_evento);
		$count = count($fluxo_ura_array);
		
		$texto = "";
		
		for ($o = 0; $o<$count; $o++){	
		
			$cod = $fluxo_ura_array[$o];
			$palavra = "evento_$cod";
			
			if (isset($$palavra)) $txt_inc = $$palavra;
			else $txt_inc = "EVENTO SEM DESCRIÇÃO NA TABELA TB_EVENTOS";
			
			if ($o == 0)$texto = $texto."$cod($txt_inc)";
			if ($o > 0)$texto = $texto.";$cod($txt_inc)";
				
		}
		
		echo "<td>$texto</td>";

	echo '</tr>';	
}

echo "</tbody></table>";
echo "</div>";
echo "</div>";

include "desconecta.php";
?>

<script>
	$("#div_loading").fadeOut('slow');
</script>

</body>
</html>