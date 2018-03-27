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
        "order": [[ 1, "desc" ]]
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
echo "<b>Detalhamento (Incidência de Rechamados)</b>";
echo "<br><br><b><i>Data da Consulta:</i></b> $data_inicial_texto";
echo "<br>";
echo '</div>';

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo "<td><b>$tipo_rechamada</b></td>";
echo '<td><b>QTDE RECHAMADOS</b></td>';
echo '</tr></thead><tbody>';
$sql = "select 
        	valor_dado , count(distinct callid) - 1 total
        from tb_dados_cadastrais 
        where cod_dado = '$qual_rechamadas_tipo'  
        and data_hora between '$data' and '$data 23:59:59.999'
        and VALOR_dado <> '' 
        and callid in (
        				select callid from tb_eventos_dac 
        				where data_hora between '$data' and '$data 23:59:59.999'
        			) 
        group by  valor_dado 
        having count(distinct callid) >= 2 
        order by count(distinct callid) - 1 desc";

echo($sql);
$query = $pdo->prepare($sql);
$query->execute();
$totalgeral = 0;
for($i=0; $row = $query->fetch(); $i++){
    $cpf = $row['valor_dado'];
	$total = $row['total'];
	
	$totalgeral = $totalgeral + $total;
	
	echo '<tr>';
	    echo "<td>$cpf</td>";
	    echo "<td><a class='w3-text-indigo' title='Rastrear Atendimentos' href= \"lista_atendimentos_rechamados.php?data=$data&cpf=$cpf\" target=\"_blank\">$total</a></td>";	    
	echo '</tr>';
}
echo "</tbody>";
echo "<tr class='w3-indigo'>";
echo    "<td>TOTAL DE RECHAMADAS</td>";
echo    "<td>$totalgeral</td>";
echo '</tr>';

echo "</table>";
echo "</div>";
echo "</div>";
echo "<br><br>";

include "desconecta.php";
$fim = defineTime();
echo tempoDecorrido($inicio,$fim);
?>
</body>
</html>
<script>  
	document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 
</script>