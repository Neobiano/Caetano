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
    
    $pData1 = $_GET['pData1'];
    $pData2 = $_GET['pData2'];
    $pBandeira = $_GET['pBandeira'];
    if (isset($_GET['pOperador']))
       $pOperador = $_GET['pOperador'];
    else
       $pOperador = 0;
           
    if (isset($_GET['pSupervisor']))
        $pSupervisor = $_GET['pSupervisor'];
    else 
        $pSupervisor = 0;
    
    $pComparacao = $_GET['pComparacao'];
    $pGrupo = $_GET['pGrupo'];
   
    $sbandeira = ($pBandeira =='')?'Todas':$pBandeira;
    
    $sdatas =  $pData1.' à '.$pData2;
    $sGrupo = $pGrupo;
    if ($pGrupo == 'Atendimentos')
        $swhere = " and cast(tdr.data_hora as date) between '$pData1' and '$pData2'";    
    if ($pGrupo == 'NaoRetido')
    {    
        $swhere = " and cast(tdr.data_hora as date) between '$pData1' and '$pData2' and tdr.tipo_retencao = 'CARTÃO NÃO RETIDO'";
        $sGrupo = 'Atendidos não Retidos';
    }
    else if ($pGrupo == 'Retido')
    {    
        $swhere = " and cast(tdr.data_hora as date) between '$pData1' and '$pData2' and tdr.tipo_retencao <> 'CARTÃO NÃO RETIDO'";
        $sGrupo = 'Atendidos Retidos';
    }
    else if ($pGrupo == 'AtendimentoAnt')
    {
        $swhere = " and cast(tdr.data_hora as date) between DATEADD(day,($pComparacao * -1) , '$pData1') and DATEADD(day,($pComparacao * -1) , '$pData2')  ";
        $sGrupo = 'Atendidos no Período Anterior';
    }
    else if ($pGrupo == 'RetidoAnt')
    {     
        $swhere = " and cast(tdr.data_hora as date) between DATEADD(day,($pComparacao * -1) , '$pData1') and DATEADD(day,($pComparacao * -1) , '$pData2') and tdr.tipo_retencao <> 'CARTÃO NÃO RETIDO' ";
        $sGrupo = 'Retidos no Período Anterior';
    }
    
    
echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Rastreio de Atendimentos - Retenção</b>";
echo "<br><br><b><i>Período de Consulta:</i></b> $sdatas ";   
echo "<br><br><b><i>Filtros: </i> $sGrupo</b>, Bandeira: $sbandeira, Cód. Operador: $pOperador, Base de Comparação: $pComparacao dias";
echo "<br><br>";

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>CALLID</b></td>';
echo '<td><b>DATA/HORA</b></td>';
echo '<td><b>ID OPERADOR</b></td>';
echo '<td><b>NOME OPERADOR</b></td>';
echo '<td><b>BANDEIRA</b></td>';
echo '<td><b>VARIANTE</b></td>';
echo '<td><b>PAN</b></td>';
echo '<td><b>TIPO RETENCAO</b></td>';

echo '</tr></thead><tbody>';

    
        
    
    $sql ="	select tdr.*, coalesce(tci.nome,'OPERADOR SEM NOME CADASTRADO') operador from tb_dados_retencao tdr 
            left join tb_colaboradores_indra tci on (tci.matricula = tdr.id_operador)
            where ((tdr.bandeira = '$pBandeira') or ('$pBandeira'=''))
            and ((tdr.id_operador= $pOperador) or ($pOperador=0))
            and ((tci.supervisor= $pSupervisor) or ($pSupervisor=0))             
            $swhere					
			order by tdr.callid, tdr.data_hora
		";
                 
   

//echo $sql;			
$query = $pdo->prepare($sql);
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$callid = $row['callid'];
	$data_hora = $row['data_hora'];
	$id_operador = $row['id_operador'];
	$operador = $row['operador'];
	$bandeira = $row['bandeira'];
	$variante = $row['variante'];
	$pan = $row['pan'];
	$tipo_retencao = $row['tipo_retencao'];		
	
	echo '<tr>';
		echo "<td>$callid</td>";
		echo "<td>$data_hora</td>";
		echo "<td>$id_operador</td>";
		echo "<td>$operador</td>";
		echo "<td>$bandeira</td>";
		echo "<td>$variante</td>";
		echo "<td>$pan</td>";
		echo "<td>$tipo_retencao</td>";
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