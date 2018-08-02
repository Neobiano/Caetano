<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

</head>
<body>

<?php
$nome_relatorio = "transferencias_recorrentes"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Transferências Recorrentes"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$TOTAL_SEM_TRANSFERENCIA = 0;
$TOTAL_COM_TRANSFERENCIA = 0;
$PERCENTUAL_TOTAL = 0;
$TOTAL_TRANSFERENCIAS_PERIODO = 0;

//IMPRIME TÍTULO DA CONSULTA
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
echo "<b>$titulo</b>";
echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
echo '</div>';

//include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
//include "inicia_tabela_organizada.php"; // INICIA A TABELA
echo '<div class="w3-border" style="padding:16px 16px;">';
    echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
        echo '<thead>
                    <tr class="w3-indigo w3-tiny">';
                        echo '<td><b>DATA</b></td>';
                        echo '<td><b>CALLID</b></td>';
                        echo '<td><b>1ª TRANSF &nbsp</b></td>';
                        echo '<td><b>2ª TRANSF &nbsp</b></td>';
                        echo '<td><b>3ª TRANSF &nbsp</b></td>';
                        echo '<td><b>4ª TRANSF &nbsp</b></td>';
                        echo '<td><b>5ª TRANSF &nbsp</b></td>';
                        echo '<td><b>6ª TRANSF &nbsp</b></td>';
                                              
                        
                echo '</tr>
              </thead>
                <tbody>';


// INFORMA A CONSULTA
$query = $pdo->prepare("select callid, data_hora, cod_fila from tb_eventos_dac where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and callid is not null and callid in (		
						select callid from tb_eventos_dac 
						where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0 and callid is not null
						group by callid
						having count(*) >= 3)
						order by callid, data_hora");
$query->execute(); // EXECUTA A CONSULTA

$callids = array();
$transf_indevida = 0;
$percurso = array();
$data = array();

$callid_anterior = "";
$data_hora_callid_anterior = "";
$percurso_atual = array();

// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
for($i=0; $row = $query->fetch(); $i++){
	
	// RECEBE RESULTADOS DA CONSULTA - INÍCIO
	$data_hora = utf8_encode($row['data_hora']);
	$callid = utf8_encode($row['callid']);
	$cod_fila = utf8_encode($row['cod_fila']);
	$cod_fila = number_format($cod_fila, 0, ',', '.');
	// RECEBE RESULTADOS DA CONSULTA - FIM
	
	if($callid != $callid_anterior){
		if($transf_indevida == 1){
			$qtd_linhas_consulta++;
			array_push($callids, "$callid_anterior");
			$percurso["$callid_anterior"] = $percurso_atual;
			$data["$callid_anterior"] = $data_hora_callid_anterior;
		}
		$percurso_atual = array();
		$transf_indevida = 0;
		$data_hora_callid_anterior = $data_hora;
	}

	else if(in_array($cod_fila, $percurso_atual)){
		$transf_indevida = 1;
	}
	
	array_push($percurso_atual, $cod_fila);
	$callid_anterior = $callid;
}
// IMPRIME O RESULTADO DA CONSULTA - FIM

//IMPRIME INFORMAÇÕES DA TABELA - INÍCIO
foreach($callids as $callid){
	$texto = '<tr>';
	echo incrementa_tabela($texto);
	
	$data_hora = $data[$callid];
	$texto = "<td>$data_hora</td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td>$callid</td>";
	echo incrementa_tabela($texto);
	
	$texto = "";
	$ja_tem_valor = 0;
	foreach($percurso[$callid] as $fila)
	{
		$desc_fila = $cod_desc[$fila];
		$texto = "<b>$fila</b> <i>($desc_fila)</i>";
		$texto = "<td>$texto</td>";
		echo incrementa_tabela($texto);
		/*if(!$ja_tem_valor) 
		  $texto = "<b>$fila</b> <i>($desc_fila)</i>";
		else 
		    $texto = $texto.", <b>$fila</b> <i>($desc_fila)</i>";
		$ja_tem_valor = 1;*/
	}
	//$texto = "<td>$texto</td>";
	//echo incrementa_tabela($texto);
		
	
	$texto = '</tr>';
	echo incrementa_tabela($texto);
}
$texto = '</tbody>';
echo incrementa_tabela($texto);

		     echo "</table>
            </div>";
		echo "</div>";
//IMPRIME INFORMAÇÕES DA TABELA - FIM

//include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php"; // IMPRIME O GRÁFICO
?>

</body>
</html>

<script>  
$('#tabela').DataTable( {
	"order": [[ 0, "asc" ]],
	 "iDisplayLength": -1,
	 "columnDefs": [ {
      "targets": [ 1, 2 ],
      "orderable": false
    } ]
} );
</script>