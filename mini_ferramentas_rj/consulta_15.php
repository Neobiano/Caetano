<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

</head>
<body>

<?php
set_time_limit(3000);
ini_set('max_execution_time', 3000);

$nome_relatorio = "percentual_de_transferencias"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Percentual de Transferências"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>EVENTO &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>CÓDIGO &nbsp</b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td><b>QUANTIDADE &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	$vet_todos_eventos = array();
	$vet_desc_eventos = array();
	$vet_quantidade_eventos = array();
	
	if(strtotime($data_inicial) > strtotime('07/31/2017')){
		$query = $pdo->prepare("select cod_evento, desc_evento from tb_eventos_novaura");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){
			$cod_evento = utf8_encode($row['cod_evento']);
			$desc_evento = utf8_encode($row['desc_evento']);
			$vet_desc_eventos["$cod_evento"] = $desc_evento;
			array_push($vet_todos_eventos, $cod_evento);
		}	
	} else{
		$query = $pdo->prepare("select cod_evento, desc_evento from tb_eventos");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){
			$cod_evento = utf8_encode($row['cod_evento']);
			$desc_evento = utf8_encode($row['desc_evento']);
			$vet_desc_eventos["$cod_evento"] = $desc_evento;
			array_push($vet_todos_eventos, $cod_evento);
		}
	}
	
	$query = $pdo->prepare("select cod_evento from tb_eventos_ura where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_evento = utf8_encode($row['cod_evento']);
		$eventos = explode(";", $cod_evento);
		foreach($eventos as $value){
			if(!isset($vet_quantidade_eventos["$value"])) $vet_quantidade_eventos["$value"] = 1;
			else $vet_quantidade_eventos["$value"]++;
		}
	}
	
	foreach($vet_todos_eventos as $value){
		if(isset($vet_quantidade_eventos["$value"])){
			$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
			$texto = "<tr>";
			echo incrementa_tabela($texto);

			$desc = $vet_desc_eventos["$value"];
			$texto = "<td>$desc</td>";
			echo incrementa_tabela($texto);
			
			//$cod = $array_eventos_desc_cod["$desc"];
			$texto = "<td>$value</td>";
			echo incrementa_tabela($texto);
				
			$qtd = $vet_quantidade_eventos["$value"];
			$qtd = number_format($qtd, 0, '.', ',');
			$texto = "<td>$qtd</td>";
			echo incrementa_tabela($texto);
				
			$texto = "</tr>";
			echo incrementa_tabela($texto);
		}
	}
	$texto = "</tbody></table>";
	echo incrementa_tabela($texto);
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>

</body>
</html>

<script>  
$('#tabela').DataTable( {
	"order": [[ 2, "desc" ]]
} );
</script>