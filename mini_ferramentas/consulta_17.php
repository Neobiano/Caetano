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
$nome_relatorio = "pesquisa_de_satisfacao"; // NOME DO RELATÃâ€œRIO (UTILIZAR UNDERLINE, POIS Ãâ€° PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Pesquisa de Satisfação"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃÆ’O IMPRIMIR BOTÃÆ’O EXCEL
include "inicia_variaveis_grafico.php";
$inicio = defineTime();

//VARIÁVEIS TOTALIZADORAS
$TOTAL_SATISFEITO = 0;
$TOTAL_INDIFERENTE = 0;
$TOTAL_INSATISFEITO = 0;
$TOTAL_ERRO = 0;
$TOTAL_SEMINTERACAO = 0;
$TOTAL_OPCAOINVALIDA = 0;

//IMPRIME TÁTULO DA CONSULTA
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
echo "<b>$titulo</b>";
echo "<br><br><b>Obs:</b> À partir de 01/03/2017 a pesquisa de satisfação considera somente as perguntas e respostas 3 e 4 para realização dos calculos";
echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à  $data_final_texto";
echo '</div>';

include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
include "inicia_tabela_organizada.php"; // INICIA A TABELA

// IMPRIME COLUNAS DA TABELA - INÁCIO
$texto = "<td><b>CÓDIGO &nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>FILA &nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>SATISFEITO &nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>SATISFEITO (%)&nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>INDIFERENTE &nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>INDIFERENTE (%)&nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>INSATISFEITO &nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>INSATISFEITO (%)&nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>SEM INTERAÇÃO &nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>SEM INTERAÇÃO (%)&nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "<td><b>TOTAL &nbsp</b></td>";
echo incrementa_tabela($texto);

$texto = "</tr></thead><tbody>";
echo incrementa_tabela($texto);
// IMPRIME COLUNAS DA TABELA - FIM

echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA

$VNDs_listadas = array();
$VDNs = array();

// PERGUNTAS - INÍCIO
$j = ($data_inicial >='03/01/2018') ? 3 : 1;

//echo "<br><br>testando J".$j;
//considendo que a partir de 01/03/2018 as perguntas respondidas serão somente a 3 e 4, mudamos o 'j' do for
for($j=1;$j<=4;$j++){
//for((($data_inicial >='03/01/2018') ? 3 : 1);$j<=4;$j++){
	$perg = "perg$j";
	$sql =  "select cod_fila vnd, $perg resposta, count(*) total from tb_pesq_satisfacao
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
							group by cod_fila, $perg";
	echo $sql;
	$query = $pdo->prepare($sql);
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÁCIO
	for($i=0; $row = $query->fetch(); $i++){
		
		// RECEBE RESULTADOS DA CONSULTA - INÁCIO
		$vdn = utf8_encode($row['vnd']);
		$vdn = number_format($vdn, 0, ',', '');
		$resposta = utf8_encode($row['resposta']);
		$total = utf8_encode($row['total']);
		
		if(!isset($VDNs[$vdn])){
			$VDNs[$vdn]['Satisfeito'] = 0;
			$VDNs[$vdn]['Indiferente'] = 0;
			$VDNs[$vdn]['Insatisfeito'] = 0;
			$VDNs[$vdn]['Erro'] = 0;
			$VDNs[$vdn]['SemInteracao'] = 0;
			$VDNs[$vdn]['OpcaoInvalida'] = 0;
			
			array_push($VNDs_listadas, $vdn);
		}
		
		switch ($resposta) {			
				case '1':
					$VDNs[$vdn]['Satisfeito'] = $total;
					$TOTAL_SATISFEITO += $total;
					break;
				
				case '2':
					$VDNs[$vdn]['Indiferente'] = $total;
					$TOTAL_INDIFERENTE += $total;
					break;
					
				case '3':
					$VDNs[$vdn]['Insatisfeito'] = $total;
					$TOTAL_INSATISFEITO += $total;
					break;
				
				case '-1':
					$VDNs[$vdn]['Erro'] += $total;
					$TOTAL_ERRO = $total;
					break;
				
				case '-2':
					$VDNs[$vdn]['SemInteracao'] = $total;
					$TOTAL_SEMINTERACAO += $total;
					break;
				
				case '0':
					$VDNs[$vdn]['OpcaoInvalida'] = $total;
					$TOTAL_OPCAOINVALIDA += $total;
					break;
		}
		// RECEBE RESULTADOS DA CONSULTA - FIM
		$qtd_linhas_consulta++;
	}
}
// PERGUNTAS - FIM

//IMPRIME INFORMAÇÕES DA TABELA - INÁCIO
foreach($VNDs_listadas as $vdn_atual){
	$texto = '<tr>';
	echo incrementa_tabela($texto);
	
	$vdn_nome = "vdn_$vdn_atual";
	if (!isset($$vdn_nome)){
		$desc_fila = $vdn_atual;
		$cod_fila = $vdn_atual;
	}
	else{
		$desc_fila = $$vdn_nome;
		if(isset($desc_cod[$desc_fila])) $cod_fila = $desc_cod[$desc_fila];
		else $cod_fila = '';
	}
	$texto = "<td>$cod_fila</td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td>$desc_fila</td>";
	echo incrementa_tabela($texto);
	
	$TOTAL_DE_RESPOSTAS = $VDNs[$vdn_atual]["Satisfeito"] + $VDNs[$vdn_atual]["Indiferente"] + $VDNs[$vdn_atual]["Insatisfeito"] + $VDNs[$vdn_atual]["SemInteracao"];
	
	$resposta = $VDNs[$vdn_atual]["Satisfeito"];
	$texto = "<td>$resposta</td>";
	echo incrementa_tabela($texto);
	
	$resposta = $VDNs[$vdn_atual]["Satisfeito"] / $TOTAL_DE_RESPOSTAS * 100;
	$resposta = number_format($resposta, 2, ',', '.');
	$texto = "<td>$resposta%</td>";
	echo incrementa_tabela($texto);
	
	$resposta = $VDNs[$vdn_atual]["Indiferente"];
	$texto = "<td>$resposta</td>";
	echo incrementa_tabela($texto);
	
	$resposta = $VDNs[$vdn_atual]["Indiferente"] / $TOTAL_DE_RESPOSTAS * 100;
	$resposta = number_format($resposta, 2, ',', '.');
	$texto = "<td>$resposta%</td>";
	echo incrementa_tabela($texto);
	
	$resposta = $VDNs[$vdn_atual]["Insatisfeito"];
	$texto = "<td>$resposta</td>";
	echo incrementa_tabela($texto);
	
	$resposta = $VDNs[$vdn_atual]["Insatisfeito"] / $TOTAL_DE_RESPOSTAS * 100;
	$resposta = number_format($resposta, 2, ',', '.');
	$texto = "<td>$resposta%</td>";
	echo incrementa_tabela($texto);
	
	$resposta = $VDNs[$vdn_atual]["SemInteracao"];
	$texto = "<td>$resposta</td>";
	echo incrementa_tabela($texto);
	
	$resposta = $VDNs[$vdn_atual]["SemInteracao"] / $TOTAL_DE_RESPOSTAS * 100;
	$resposta = number_format($resposta, 2, ',', '.');
	$texto = "<td>$resposta%</td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td>$TOTAL_DE_RESPOSTAS</td>";
	echo incrementa_tabela($texto);
	
	$texto = '</tr>';
	echo incrementa_tabela($texto);
}
$texto = '</tbody>';
echo incrementa_tabela($texto);

// IMPRIME TR FINALIZADORA - INÍCIO
$TOTAL_RESPOSTAS = $TOTAL_SATISFEITO + $TOTAL_INDIFERENTE + $TOTAL_INSATISFEITO + $TOTAL_ERRO + $TOTAL_SEMINTERACAO + $TOTAL_OPCAOINVALIDA;

$PERC_TOTAL_SATISFEITO = $TOTAL_SATISFEITO / $TOTAL_RESPOSTAS * 100;
$PERC_TOTAL_INDIFERENTE = $TOTAL_INDIFERENTE / $TOTAL_RESPOSTAS * 100;
$PERC_TOTAL_INSATISFEITO = $TOTAL_INSATISFEITO / $TOTAL_RESPOSTAS * 100;
$PERC_TOTAL_ERRO = $TOTAL_ERRO / $TOTAL_RESPOSTAS * 100;
$PERC_TOTAL_SEMINTERACAO = $TOTAL_SEMINTERACAO / $TOTAL_RESPOSTAS * 100;
$PERC_TOTAL_OPCAOINVALIDA = $TOTAL_OPCAOINVALIDA / $TOTAL_RESPOSTAS * 100;


$texto = "<tr class='w3-indigo'>";
echo incrementa_tabela($texto);

	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b></b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = number_format($TOTAL_SATISFEITO, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = number_format($PERC_TOTAL_SATISFEITO, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$imprime = number_format($TOTAL_INDIFERENTE, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = number_format($PERC_TOTAL_INDIFERENTE, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$imprime = number_format($TOTAL_INSATISFEITO, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = number_format($PERC_TOTAL_INSATISFEITO, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	
	
	$imprime = number_format($TOTAL_SEMINTERACAO, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = number_format($PERC_TOTAL_SEMINTERACAO, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	
	$imprime = number_format($TOTAL_RESPOSTAS, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);

$texto = '</tr>';
echo incrementa_tabela($texto);
// IMPRIME TR FINALIZADORA - FIM

//IMPRIME INFORMAÃâ€¡Ãâ€¢ES DA TABELA - FIM

include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php"; // IMPRIME O GRÁFICO

//for($j=1;$j<=4;$j++){
for($j=(($data_inicial >='03/01/2018') ? 3 : 1);$j<=4;$j++)
{
	$perg = "perg$j";
	echo "<div class='w3-container w3-tiny w3-margin w3-padding w3-card-4 w3-border'>";
	
	echo "<table class='w3-table w3-hoverable w3-striped w3-padding w3-tiny w3-margin-top'>";
	echo "<tr class='w3-indigo'>";
	
		if($perg == 'perg1') echo "<td><b>No Geral, qual seu grau de satisfação£o?</b></td>";
		if($perg == 'perg2') echo "<td><b>Quanto ao tempo de espera, você se considera:</b></td>";
		if($perg == 'perg3') echo "<td><b>Quanto a  cordialidade do atendente, voce se considera:</b></td>";
		if($perg == 'perg4') echo "<td><b>A solicitação foi atendida ao final do atendimento?</b></td>";
		
		echo "<td class='w3-right'><b>QUANTIDADE</b></td>";
	echo "</tr>";
	
	$query = $pdo->prepare("select $perg resposta, count(*) total from tb_pesq_satisfacao
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
							group by $perg");
	$query->execute();
	
	$array_respostas = array();
	
	$resultado = array();
	$resultado[1] = 0;
	$resultado[2] = 0;
	$resultado[3] = 0;
	$resultado[-1] = 0;
	$resultado[-2] = 0;
	$resultado[0] = 0;
	
	$resultado['TOTAL_DE_RESPOSTAS'] = 0;
	
	for($i=0; $row = $query->fetch(); $i++){	
		$resposta = utf8_encode($row['resposta']);
		$total = utf8_encode($row['total']);
		
		array_push($array_respostas, $resposta);
		
		if($resposta == '1') $resultado[1] = $total;
		if($resposta == '2') $resultado[2] = $total;
		if($resposta == '3') $resultado[3] = $total;
		if($resposta == '-1') $resultado[-1] = $total;
		if($resposta == '-2') $resultado[-2] = $total;
		if($resposta == '0') $resultado[O] = $total;
		
		$resultado['TOTAL_DE_RESPOSTAS'] = $resultado['TOTAL_DE_RESPOSTAS'] + $total;
		
	}
	
	foreach($array_respostas as $resposta){
		$total = $resultado[$resposta];
		$porcentagem = $total / $resultado['TOTAL_DE_RESPOSTAS'] * 100;
		$porcentagem = number_format($porcentagem, 2, ',', '.');
		echo "<tr>";
		if($resposta == '1') echo "<td>Satisfeito</td>";
		if($resposta == '2') echo "<td>Indiferente</td>";
		if($resposta == '3') echo "<td>Insatisfeito</td>";
		if($resposta == '-1') echo "<td>Erro</td>";
		if($resposta == '-2') echo "<td>Sem Interação</td>";
		if($resposta == '0') echo "<td>Opção Inválida</td>";
		echo "<td class='w3-right'>$total ($porcentagem%)</td>";
		echo "</tr>";
	}
	
	$TOTAL_DE_RESPOSTAS = $resultado['TOTAL_DE_RESPOSTAS'];
	echo "<tr>";
	echo "<td><b>TOTAL</b></td>";
	echo "<td class='w3-right'><b>$TOTAL_DE_RESPOSTAS</b></td>";
	echo "</tr>";
	echo "</table></div>";
}

?>

</body>
</html>

<script>  
$('#tabela').DataTable( {
	"order": [[ 0, "asc" ]],
	 "iDisplayLength": -1,
	 "columnDefs": [ {
      "targets": [ ],
      "orderable": false
    } ]
} );
</script>