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
    $('#tabela').DataTable();
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
$select_origem_reicidencia = $_GET['select_origem_reicidencia'];
$valor_dado = $_GET['valor_dado'];
$perg1 = $_GET['perg1'];
$perg2 = $_GET['perg2'];
$perg3 = $_GET['perg3'];
$perg4 = $_GET['perg4'];
    
    $swhere = '';
    if ($perg1> 0)    
        $swhere .= " and tps.perg1 = '$perg1'";
 
    if ($perg2 > 0)       
        $swhere .= " and tps.perg2 = '$perg2'";
                                   
    if ($perg3 > 0)            
        $swhere .= " and tps.perg3 = '$perg3'";                                           
        
    if ($perg4 > 0)                
        $swhere .= " and tps.perg4 = '$perg4'";
                   

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);

$t_final= strtotime($data_final);
$data_final_texto = date('d/m/Y',$t_final);
//Conversão Data Texto - Fim

switch ($select_origem_reicidencia) 
{			
	case '03':
		$s_select_origem_reicidencia = 'Tefone Originador';
		$sql_tipo_dado = " and cod_dado = '3' ";
		break;
	
	case '02':
		$s_select_origem_reicidencia = 'CPF Demandante';
		$sql_tipo_dado = " and cod_dado = '2' ";
		break;
		
	case '01':
		$s_select_origem_reicidencia = 'Cartão Demandante';
		$sql_tipo_dado = " and cod_dado = '1' ";
		break;	
}

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Rastreio de Atendimentos - Reicidencia de Insatisfação</b>";
echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto";
echo "<br><br><b><i>Agrupado Por:</i></b> $s_select_origem_reicidencia - $valor_dado";
echo "<br><br>";

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>CALLID</b></td>';
echo '<td><b>DATA/HORA</b></td>';
echo '<td><b>CÓDIGO FILA</b></td>';
echo '<td><b>DESCRIÇÃO FILA</b></td>';
echo '<td><b>TEMPO DE ESPERA</b></td>';
echo '<td><b>TEMPO DE ATENDIMENTO</b></td>';
echo '<td><b>ID OPERADOR</b></td>';
echo '<td><b>NOME OPERADOR</b></td>';
echo '</tr></thead><tbody>';


$valor_dado = trim($valor_dado);
$sql ="	select distinct ted.*, f.desc_fila from tb_eventos_dac ted
		left join tb_filas f on (ted.cod_fila = f.cod_fila)
		inner join tb_dados_cadastrais tdc on (tdc.callid = ted.callid)
		inner join tb_pesq_satisfacao tps on (ted.callid = tps.callid)
		where  ted.data_hora between '$data_inicial' and '$data_final 23:59:59.999'
		and tps.data_hora between '$data_inicial' and '$data_final 23:59:59.999'
		and tdc.data_hora between '$data_inicial' and '$data_final 23:59:59.999'
		$sql_tipo_dado
		and  valor_dado = '$valor_dado'
		$swhere
		and ted.id_operador is not null
		order by ted.callid, ted.data_hora
		";

//echo $sql;			
$query = $pdo->prepare($sql);
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$callid = $row['callid'];
	$data_hora = $row['data_hora'];
	$cod_fila = $row['cod_fila'];
		$cod_fila = number_format($cod_fila, 0, ',', '.');
	$desc_fila = $row['desc_fila'];
		if($desc_fila == NULL) $desc_fila = "";
	$tempo_espera = $row['tempo_espera'];
	$tempo_atend = $row['tempo_atend'];
	$id_operador = $row['id_operador'];
	$desc_operador = $row['desc_operador'];	
		if($desc_operador=='') $desc_operador = "OPERADOR SEM NOME CADASTRADO";
	
	echo '<tr>';
		echo "<td>$callid</td>";
		echo "<td>$data_hora</td>";
		echo "<td>$cod_fila</td>";
		echo "<td>$desc_fila</td>";
		echo "<td>$tempo_espera</td>";
		echo "<td>$tempo_atend</td>";
		echo "<td>$id_operador</td>";
		echo "<td>$desc_operador</td>";
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