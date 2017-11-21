<!DOCTYPE html>
<html>
<head>
<title>CAIXA - Contingenciamento Contrato INDRA Maracanaú</title>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready( function () {
    $('#tabela').DataTable();
} );
</script>

</head>
<body>

<?php
// VALIDA SE O ARQUIVO É .CSV - INÍCIO
$tipo_arquivo = $_FILES['userfile']['type'];
$nome_arquivo = $_FILES['userfile']['name'];
$final_arquivo = substr($nome_arquivo, -3);
if ($tipo_arquivo != "application/vnd.ms-excel"){
	echo "<div class='w3-container w3-padding w3-margin w3-center w3-tiny w3-text-red'><b>Tipo de arquivo inválido!<br>Por favor enviar somente arquivos com formato .csv</b></div>";
	exit;
}
if ($final_arquivo != "csv"){
	echo "Tipo de Arquivo inválido! Por favor enviar somente arquivos com formato .csv";
	exit;
}
// VALIDA SE O ARQUIVO É .CSV - FIM

// RECEBE AS VARIÁVEIS DO FORM - INÍCIO
$mes_arquivo = $_POST['mes_arquivo'];
if ($mes_arquivo == '00'){
	echo "<div class='w3-container w3-padding w3-margin w3-center w3-tiny w3-text-red'><b>Mês de referência não informado!</b></div>";
	exit;
}
$ano_arquivo = $_POST['ano_arquivo'];
if (($ano_arquivo == NULL) || ($ano_arquivo < 2014) || ($ano_arquivo > 2200)){
	echo "<div class='w3-container w3-padding w3-margin w3-center w3-tiny w3-text-red'><b>Ano de referência não informado ou inválido!</b></div>";
	exit;
}
// RECEBE AS VARIÁVEIS DO FORM - FIM

// TRADUZ MÊS - INÍCIO
switch ($mes_arquivo) {
						case '01':
							$mes = 'Janeiro';
							break;
							
						case '02':
							$mes = 'Fevereiro';
							break;
							
						case '03':
							$mes = 'Março';
							break;
							
						case '04':
							$mes = 'Abril';
							break;
							
						case '05':
							$mes = 'Maio';
							break;
							
						case '06':
							$mes = 'Junho';
							break;
							
						case '07':
							$mes = 'Julho';
							break;
							
						case '08':
							$mes = 'Agosto';
							break;
							
						case '09':
							$mes = 'Setembro';
							break;
							
						case '10':
							$mes = 'Outubro';
							break;
							
						case '11':
							$mes = 'Novembro';
							break;
							
						case '12':
							$mes = 'Dezembro';
							break;
}
// TRADUZ MÊS - FIM

// GRAVA O ARQUIVO CSV RECEBIDO NA PASTA - INÍCIO
$txt_data = "$mes de "."$ano_arquivo";
$nome_arquivo = "$ano_arquivo"."$mes_arquivo".".csv";

$pasta = '../contingenciamento/arquivos/';
$arquivos = scandir($pasta);

$uploaddir = '../contingenciamento/arquivos/';
$uploadfile = $uploaddir . basename($nome_arquivo);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
} else {
    echo "ERRO!\n";
	exit;
}
// GRAVA O ARQUIVO CSV RECEBIDO NA PASTA - FIM

// IMPRIME A TABELA - INÍCIO
$row = 0;
if (($handle = fopen("arquivos/$nome_arquivo", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		$vetor[$row] = $data;
        $row++;
    }
    fclose($handle);
}

$qtd_linhas = count($vetor);

echo "<div class='w3-container w3-center w3-padding'>";
    echo "<b class='w3-small w3-text-dark-grey'>Planilha enviada com sucesso referente $txt_data</b>";
	echo "</div>";

echo "<div class='w3-border w3-margin'><div class='w3-container w3-padding w3-tiny w3-margin'><table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny w3-card-4'>";
for($m=0;$m<$qtd_linhas;$m++){
	$linha = $vetor[$m];
	$tamanho = count($linha);	
	if ($linha[0]=="") continue;
	
		if($m==0) echo"<thead><tr class='w3-indigo-dark'>";
		else echo "<tr>";
		
			$saiu = 0;
			for($n=0;$n<$tamanho;$n++){
				$imprime = utf8_encode($linha[$n]);			
				echo "<td>$imprime</td>";
			}
		echo "</tr>";
		if($m==0) echo"</thead><tbody>";
}
echo "</tbody></table></div></div>";
// IMPRIME A TABELA - FIM
?>

</body>
</html>

<script>  
$('#tabela').DataTable( {
	"order": [[ 4, "asc" ]]
} );
</script>