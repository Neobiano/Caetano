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
$icpf = $_GET['cpf'];



//Conversão Data Texto - Início
$t_inicial = strtotime($data);
$data_inicial_texto = date('d/m/Y',$t_inicial);



echo '<div class="w3-margin w3-tiny w3-center">';
echo '<div id="divtitulo">';
echo "<b>Rastreio de Atendimentos (Incidência de Rechamados)</b>";
echo "<br><br><b><i>Data da Consulta:</i></b> $data_inicial_texto";
echo "<br><b><i>CPF/CNPJ:</i></b> $icpf";
echo "<br><br>";
echo "<b>Obs:</b> A quantidade de <b>Atendimentos</b> é em regra diferente da quantidade de <b>Rechamados.</b>";
echo "<br>A cardinalidade para a relação é,'1' Chamada Originadora --> 'N' Rechamados e '1' Rechamado --> 'N' Atendimentos";
echo "<br><br>";
echo '</div>';

echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>CPF/CNPJ</b></td>';
echo '<td><b>DATA/HORA</b></td>';
echo '<td><b>CALLID</b></td>';
echo '<td><b>CÓD. FILA</b></td>';
echo '<td><b>DESC. FILA</b></td>';
echo '<td><b>TMP. ESPERA</b></td>';
echo '<td><b>TMP. ATEND.</b></td>';
echo '<td><b>ID OP.</b></td>';
echo '<td><b>NOME OPERADOR</b></td>';
echo '</tr></thead><tbody>';
$sql = "select distinct a.valor_dado, t.*, t2.desc_fila  
        from tb_eventos_dac t
        left join tb_filas t2 on t.cod_fila = t2.cod_fila
        inner join
        (
            select b.* from tb_dados_cadastrais b
            where b.data_hora between '$data' and '$data 23:59:59.999'
            and b.cod_dado = 2
            and b.valor_dado in (
                select distinct c.cpf from (
                    select
                    valor_dado cpf , count(distinct callid) - 1 TOTAL
                    from tb_dados_cadastrais where cod_dado = '2'
                    and data_hora between '$data' and '$data 23:59:59.999'
                    and VALOR_dado = '$icpf'
                    and callid in (
                        select callid from tb_eventos_dac
                        where data_hora between '$data' and '$data 23:59:59.999'
                        )
                    group by  valor_dado
                    having count(distinct callid) >= 2
                    ) as c
                )
            ) a on a.callid = t.callid
            where t.data_hora between '$data' and '$data 23:59:59.999'
                        and t.id_operador not like '%NULL%' /*ignorando os arquivos 'lixo' gerados no tb_eventos_dac*/
                        order by a.valor_dado, t.data_hora, t.callid";

//$sql = "select a.*, b.desc_fila from tb_eventos_dac as a	left join tb_filas as b	on a.cod_fila = b.cod_fila	where data_hora between '$data' and '$data 23:59:59.999'";

//echo($sql);
$query = $pdo->prepare($sql);
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
    $cpf = $row['valor_dado'];
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
	    echo "<td>$cpf</td>";
	    echo "<td>$data_hora</td>";
	    echo "<td>$callid</td>";	
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
$fim = defineTime();
//echo tempoDecorrido($inicio,$fim);
?>
</body>
</html>
<script>  
	//document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 
</script>