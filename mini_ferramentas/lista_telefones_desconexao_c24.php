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
$(document).ready( function () {
    $('#tabela').DataTable( {
        "order": [[ 1, "desc" ]]
    } );
} );
</script>

</head>
<body>
<?php 
include "conecta.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$valor_dado = $_GET['valor_dado'];
$qtde_total = $_GET['qtde_total'];
$cod_evento = $_GET['cod_evento'];

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Detalhamento de Telefones  - Ligações Sem Derivação/ Sem Serviço</b>";
echo "<br><br><b><i>Período de Consulta:</i></b> $valor_dado";
echo "<br><br><b><i>Evento:</i></b> $cod_evento";
echo "<br><br><b><i>Total de Ligações:</i></b> $qtde_total";
echo "<br><br>";

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>Telefone</b></td>';
echo '<td><b>Qtde Ligações</b></td>';
echo '<td><b>Pct(%)</b></td>';
echo '</tr></thead><tbody>';


$valor_dado = trim($valor_dado);
$sql = " select t2.valor_dado fone, count(distinct t.callid) qtde  from tb_eventos_ura t
            inner join tb_dados_cadastrais t2 on (t2.callid = t.callid)
            where t.data_hora between '$valor_dado 00:00:00' and '$valor_dado 23:59:59'
            and t2.data_hora between '$valor_dado 00:00:00' and '$valor_dado 23:59:59'
            and cod_evento like '$cod_evento'
            and t2.cod_dado = '3'
            group by t2.valor_dado
            order by count(distinct t.callid) desc ";
        


			
$query = $pdo->prepare($sql);
$query->execute();
for($i=0; $row = $query->fetch(); $i++)
{
	$fone = $row['fone'];
	$qtde = $row['qtde'];	
	$pctfone = number_format(($row['qtde']/$qtde_total * 100), 2, ',', '.');
	echo '<tr>';
	   echo "<td>$fone</td>";	
	   echo "<td>$qtde</td>";
	   echo "<td>$pctfone</td>";
	echo '</tr>';
}
echo "</tbody></table>";
echo "</div>";
echo "</div>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>