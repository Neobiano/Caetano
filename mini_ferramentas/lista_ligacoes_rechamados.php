<!DOCTYPE html>
<html>
<head>
<title>RADAR - Painel de Monitoramento - Cartão de Crédito</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabela').DataTable( {
        "order": [[ 0, "asc" ]]
    } );
} );
</script>

</head>
<body>
<?php 
include "conecta.php";
include "funcoes.php";
$inicio = defineTime();
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$data = $_GET['data'];
$icpf_fone = $_GET['cpf_fone'];
$qual_rechamadas_tipo = $_GET['qual_rechamadas_tipo'];

if ($qual_rechamadas_tipo=='2')
    $tipo_rechamada = 'CPF/CNPJ';
else
    $tipo_rechamada = 'TELEFONE';



//Conversão Data Texto - Início
$t_inicial = strtotime($data);
$data_inicial_texto = date('d/m/Y',$t_inicial);


echo '<div class="w3-margin w3-tiny w3-center">';
echo '<div id="divtitulo">';
echo "<b>Rastreio de Ligações (Incidência de Rechamados)</b>";
echo "<br><br><b><i>Data da Consulta:</i></b> $data_inicial_texto";
echo "<br><b><i>$tipo_rechamada:</i></b> $icpf_fone";
echo "<br><br>";
echo "<b>Obs:</b> A quantidade de ligações, de acordo com o agrupador definido (Telefone ou CPF/CNPJ)</b>";
echo "<br><br>";
echo '</div>';

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>DATA/HORA</b></td>';
echo '<td><b>CALLID</b></td>';
echo '<td><b>CÓD. EVENTO</b></td>';
echo '</tr></thead><tbody>';

$sql = "select distinct t.callid, t.data_hora, t.cod_evento from tb_eventos_URA t
        inner join tb_dados_cadastrais t2 on (t2.callid = t.callid)
        where t.data_hora between '$data' and '$data 23:59:59.999'
        and t2.data_hora between '$data' and '$data 23:59:59.999'
        and t2.valor_dado = '$icpf_fone'
        and t2.cod_dado = '$qual_rechamadas_tipo'
        order by t.data_hora, t.callid";

//echo($sql);
$query = $pdo->prepare($sql);
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
    $callid = $row['callid'];
	$data_hora = $row['data_hora'];
	$cod_evento = $row['cod_evento'];
		
	echo '<tr>';  
	    echo "<td>$data_hora</td>";
	    echo "<td>$callid</td>";	
		echo "<td>$cod_evento</td>";		
	echo '</tr>';
}
echo "</tbody></table>";
echo "</div>";
echo "</div>";
echo "<br><br>";

include "desconecta.php";
$fim = defineTime();
//echo tempoDecorrido($inicio,$fim);
?>
</body>
</html>
<script>  
	//document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 
</script>