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
        "order": [[ 1, "asc" ]]
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

$data = $_GET['pData'];
$grupo = $_GET['pGrupo'];
$valor = $_GET['pValor'];
$pcallid = $_GET['pCallid'];
$pTipoDado = $_GET['pTipoDado'];


//Conversão Data Texto - Início
$t_inicial = strtotime($data);
$data_inicial_texto = date('d/m/Y',$t_inicial);


//Conversão Data Texto - Fim

echo '<div class="w3-margin w3-tiny w3-center">';
echo '<div id="divtitulo" class="w3-margin w3-tiny w3-center">';
echo "<b>Rastreio de Atendimentos</b>";
echo "<br><br><b> Tipo de Atendimento:</b> $grupo";
echo "<br><br><b> $pTipoDado:</b> $valor - <b> CallID: </b> $pcallid";
echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto ";
echo "<br><br>";
echo "</div>";
   
echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
    if ($grupo == 'ATC')
    {        
        echo '<td><b>CALLID</b></td>';
        echo '<td><b>DATA/HORA</b></td>';
        echo '<td><b>CÓDIGO FILA</b></td>';
        echo '<td><b>DESCRIÇÃO FILA</b></td>';
        echo '<td><b>TEMPO DE ESPERA</b></td>';
        echo '<td><b>TEMPO DE ATENDIMENTO</b></td>';
        echo '<td><b>ID OPERADOR</b></td>';
        echo '<td><b>NOME OPERADOR</b></td>';
        echo '</tr></thead><tbody>';
        
        $sql = "select a.*, b.desc_fila from tb_eventos_dac as a
        						left join tb_filas as b
        						on a.cod_fila = b.cod_fila
        						where data_hora between '$data' and '$data 23:59:59.999' 
                                and callid = '$pcallid'
                                order by data_hora
                               ";
        
        //echo($sql);
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
        
    } 
    else if ($grupo == 'URA')
    {
        echo '<td><b>CALLID</b></td>';
        echo '<td><b>DATA/HORA</b></td>';
        echo '<td><b>EVENTO</b></td>';        
        echo '</tr></thead><tbody>';
        
        $sql = "select * from tb_eventos_ura         						        						
				where data_hora between '$data' and '$data 23:59:59.999'
                and callid = '$pcallid'
                order by data_hora";
        
        //echo($sql);
        $query = $pdo->prepare($sql);
        $query->execute();
        for($i=0; $row = $query->fetch(); $i++){
            $callid = $row['callid'];
            $data_hora = $row['data_hora'];
            $cod_evento = $row['cod_evento'];            
            $cod_evento = traduzNovaura($cod_evento,$pdo);
            echo '<tr>';
            echo "<td>$callid</td>";
            echo "<td>$data_hora</td>";
            echo "<td>$cod_evento</td>";            
            echo '</tr>';
        }
    }
    else if ($grupo == 'Pesquisa')
    {
        echo '<td><b>CALLID</b></td>';
        echo '<td><b>DATA/HORA</b></td>';
        echo '<td><b>Perg.3 (Cordialidade)</b></td>';
        echo '<td><b>Perg.4 (Prob. Resolvido)</b></td>';
        echo '</tr></thead><tbody>';
        
        $sql = "select t.callid, t.data_hora,
                case  
                   when t.perg3 = 1 then 'Satisfeito'
                   when t.perg3 = 2 then 'Indiferente'
                   when t.perg3 = 3 then 'Insatisfeito'
                   else 'Não Respondeu'
                end perg3,
                case  
                   when t.perg4 = 1 then 'Satisfeito'
                   when t.perg4 = 2 then 'Indiferente'
                   when t.perg4 = 3 then 'Insatisfeito'
                   else 'Não Respondeu'
                end perg4
                  from tb_pesq_satisfacao t                
				where data_hora between '$data' and '$data 23:59:59.999'
                and callid = '$pcallid'
                order by data_hora";
        
        //echo($sql);
        $query = $pdo->prepare($sql);
        $query->execute();
        for($i=0; $row = $query->fetch(); $i++){
            $callid = $row['callid'];
            $data_hora = $row['data_hora'];
            $perg3 = $row['perg3'];
            $perg4 = $row['perg4'];
            
            echo '<tr>';
            echo "<td>$callid</td>";
            echo "<td>$data_hora</td>";
            echo "<td>$perg3</td>";
            echo "<td>$perg4</td>";
            echo '</tr>';
        }
    }
echo "</tbody></table>";
echo "</div>";
echo "</div>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>