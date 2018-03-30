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
include "funcoes.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$valor_dado = $_GET['valor_dado'];
$qtde_total = $_GET['qtde_total'];

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Detalhamento de Eventos - Sem Derivação/ Sem Serviço</b>";
echo "<br><br><b><i>Período de Consulta:</i></b> $valor_dado";
echo "<br><br><b><i>Total de Ligaçõe:</i></b> $qtde_total";
echo "<br><br>";

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>Cód. Evento</b></td>';
echo '<td><b>Qtde Ligações</b></td>';
echo '<td><b>Pct(%)</b></td>';
echo '</tr></thead><tbody>';


$valor_dado = trim($valor_dado);
$sql = " select cod_evento, count(distinct callid) qtde
        from tb_eventos_ura
        where data_hora between '$valor_dado ' and '$valor_dado  23:59:59.999'
        and callid not in (select callid from tb_eventos_dac where data_hora between'$valor_dado ' and '$valor_dado  23:59:59.999')
        and (cod_evento not like '%020%') 
        and (cod_evento not like '%031%') 
        and (cod_evento not like '%037%') 
        and (cod_evento not like '%039%') 
        and (cod_evento not like '%042%') 
        and (cod_evento not like '%045%') 
        and (cod_evento not like '%047%') 
        and (cod_evento not like '%050%') 
        and (cod_evento not like '%051%') 
        and (cod_evento not like '%061%') 
        and (cod_evento not like '%062%') 
        and (cod_evento not like '%076%') 
        and (cod_evento not like '%078%') 
        and (cod_evento not like '%136%') 
        and (cod_evento not like '%137%') 
        and (cod_evento not like '%138%') 
        and (cod_evento not like '%139%') 
        and (cod_evento not like '%140%')
        group by cod_evento
        order by count(distinct callid) desc";


			
$query = $pdo->prepare($sql);
$query->execute();
for($i=0; $row = $query->fetch(); $i++)
{
	$cod_evento = $row['cod_evento'];
	$qtde = $row['qtde'];	
	$pctevento = number_format(($row['qtde']/$qtde_total * 100), 2, ',', '.');
	echo '<tr>';
	//class='tooltip'w3-text-indigo
	$traducao = traduzNovaura($cod_evento,$pdo,false);
	echo "<td><a class='w3-text-indigo' title='$traducao' href= \"lista_telefones_desconexao_c24.php?valor_dado=$valor_dado&cod_evento=$cod_evento&qtde_total=$qtde\" target=\"_blank\">$cod_evento</a>
    
    </td>";	
	   echo "<td>$qtde</td>";
	   echo "<td>$pctevento</td>";
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