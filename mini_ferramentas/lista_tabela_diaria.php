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

$dia = $_GET['dia'];
$qual_tabela = $_GET['qual_tabela'];





echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Verifica Alimentação BD</b>";
echo "<br><br><b>DATA:</b> $dia";
echo "<br><b>TABELA:</b> $qual_tabela";
echo "<br><br>";

$dia;
$dia = str_replace('/', '-', $dia);
$dia = date('m/d/Y', strtotime($dia));





$array_faixas = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);




echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important; ">';

echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";

echo "<thead><tr class='w3-indigo'>";

	echo "<td><b>FAIXA DE HORÁRIO</b></td>";
	echo "<td><b>QUANTIDADE DE REGISTROS</b></td>";
	
echo "</tr></thead><tdoby>";


	
$query = $pdo->prepare("select datepart(hh,data_hora) faixa_de_horario, count(*) TOTAL from $qual_tabela
						where data_hora between '$dia' and '$dia 23:59:59.999'
						group by datepart(hh,data_hora)");
$query->execute(); // EXECUTA A CONSULTA

for($i=0; $row = $query->fetch(); $i++){

	$faixa_de_horario = utf8_encode($row['faixa_de_horario']);
	$TOTAL = utf8_encode($row['TOTAL']);
	
	$array_faixas[$faixa_de_horario] = $TOTAL;
}	


$faixa_imprime = 0;
	
for($y=0;$y<=23;$y++){
	
	$total = $array_faixas[$y];
	
	if($y<10) $faixa_imprime = "0$y:00 - 0$y:59";
	else $faixa_imprime = "$y:00 - $y:59";
	
	echo "<tr>";
		if($total == 0) echo "<td style='color: red;'><b>$faixa_imprime</b></td>";
		else echo "<td>$faixa_imprime</td>";
		
		if($total == 0) echo "<td style='color: red;'><b>$total</b></td>";
		else echo "<td>$total</td>";
	echo "</tr>";
}
	
	











echo "</tbody></table></div>";


include "desconecta.php";
?>
</body>
</html>