<!DOCTYPE html>
<html>
	<head>
		<title>RADAR CARTÕES - Painel de Monitoramento - Cartão de Crédito</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/w3.css">		
		<link rel="stylesheet" href="css/radar.css">
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

$dia = $_GET['data_inicial'];
$tempo_de_corte = $_GET['tempo_corte'];
                 

$qdia = substr($dia,6,4).'-'.substr($dia,3,2).'-'.substr($dia,0,2);

//$dia_inicial_u = date('Y-m-d',$dia);



echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Detalhamento do Nível de Serviço - Diário/Fila</b>";
echo "<br><br><b><i>Data de Consulta:</i></b> $dia";
echo "<br><br>";

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>FILA</b></td>';
echo "<td class='tooltip'><b>REFERÊNCIA*</b>
            	         <span class='tooltiptext'>Tempo de referência 45s (Normal) e 90s (DMM)</span>
            	      </td>";
echo "<td class='tooltip'><b>A*</b>
            	         <span class='tooltiptext'>Atendidas até 45s (Normal) ou 90s (DMM)</span>
            	      </td>";

echo "<td class='tooltip'><b>B*</b>
            	         <span class='tooltiptext'>Todas as Atendidas</span>
            	      </td>";

echo "<td class='tooltip'><b>C*</b>
            	         <span class='tooltiptext'>Abandonadas após 45s (Normal) ou 90s (DMM)</span>
            	      </td>";

echo "<td class='tooltip'><b>NSA* = A/(B+C)</b>
            	         <span class='tooltiptext'>Nível de Serviço Alcançado</span>
            	      </td>";
echo "<td class='tooltip'><b>NSR*</b>
            	         <span class='tooltiptext'>Nível de Serviço de Referência para a Fila (Alvo)</span>
            	      </td>";

echo "<td class='tooltip'><b>NS*</b>
            	         <span class='tooltiptext'>Nível de Serviço (NSA/NSR)</span>
            	      </td>";

echo '<td><b>TMA</b></td>';
echo '</tr></thead><tbody>';

$sql = "
                            set nocount on;
                            
                            declare @T TABLE(dia date,
                            				 sdia_semana varchar(20),
                            				 dia_semana int,
                                             cod_fila int,
                            				 tempo_referencia int,
                            				 a int,
                            				 b int,
                            				 c int,
                            				 nsa float,                            				 
                            				 tma int,
                                             nsr float,
                                             ns float,
                                             desc_fila varchar(50),
                                             ansm float
                            				);
                            insert @T EXEC sp_CERATFO_radar_cartoes_query6b_det '$qdia 00:00:00', '$qdia 23:59:59', $tempo_de_corte
                            
                            select dia ,
                            				 sdia_semana ,
                            				 dia_semana ,
                                             cod_fila ,
                            				 tempo_referencia ,
                            				 coalesce(a,0) a,
                            				 coalesce(b,0) b ,
                            				 coalesce(c,0) c ,
                            				 nsa ,                            				 
                            				 tma ,
                                             nsr ,
                                             ns ,
                                             desc_fila ,
                                             ansm  from @T
                         ";
//echo $sql;

			
$query = $pdo->prepare($sql);
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$fila = $row['desc_fila'];
	$referencia = $row['tempo_referencia'];
	$a = $row['a'];
	$b = $row['b'];
	$c = $row['c'];
	
	$nsa = $row['nsa'];
	$nsa = ($nsa * 100.00);
	$nsa = number_format($nsa, 2, ',', '.');
	
	$nsr = $row['nsr'];
	$nsr = ($nsr * 100.00);
	$nsr = number_format($nsr, 2, ',', '.');
	
	$ns = $row['ns'];
	$ns = ($ns * 100.00);
	$ns = number_format($ns, 2, ',', '.');
	
	$tma = $row['tma'];
	$ansm = $row['ansm'];
	$ansm = ($ansm * 100.00);
	$ansm = number_format($ansm, 5, ',', '.');
	
	echo '<tr>';
	echo "<td>$fila</td>";
	echo "<td>$referencia</td>";
		echo "<td>$a</td>";
		echo "<td>$b</td>";
		echo "<td>$c</td>";
		echo "<td>$nsa</td>";
		echo "<td>$nsr</td>";
		echo "<td>$ns</td>";
		echo "<td>$tma</td>";
	echo '</tr>';
}
echo '</tbody>
      <tfoot>
         <tr class="w3-indigo w3-tiny">
      ';  
echo  "    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td> <b>ANSM = AVG ( NS )</b></td>
            <td></td>
            <td><b>$ansm</b></td>
            <td></td>
         </tr>
      </tfoot>
</table>";
echo "</div>";
echo "</div>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>