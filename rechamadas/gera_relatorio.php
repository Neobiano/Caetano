<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabela').DataTable( {
        "order": [[ 2, "asc" ]]
    } );
} );
</script>

</head>
<body>

<div id="div_loading" class="w3-modal">
<div class="w3-modal-content" style="width:100%;height:100%;position:absolute;top:0;right:0;padding:0;margin:0;">
  <div class="w3-container w3-center w3-margin w3-padding-64">
	<img src="loading.gif" style="width:100px;">
	<p class="w3-text-red w3-center">Carregando...</p>
  </div>
</div>
</div>
<script>
	document.getElementById('div_loading').style.display='block';
</script>

<?php 
include "conecta.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);



$txt_dados = $_GET['txt_dados']; //Recebe txt de todos os telefones exibidos no resultado
$gerenciar = $_GET['gerenciar'];
if ($gerenciar==0) $identificador = $_POST['identificador'];
else $identificador = '';

$data_inicial = $_POST['data_inicial'];
$data_final = $_POST['data_final'];

$qual_btn = $_POST['btn_pesquisar'];

if ($qual_btn=='01') echo "<title>Relatório de Dados Críticos</title>";
if ($qual_btn=='02') echo "<title>Inclusão BLACKLIST</title>";
if ($qual_btn=='03') echo "<title>Exclusão BLACKLIST</title>";

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);
$t_inicial = strtotime($data_final);
$data_final_texto = date('d/m/Y',$t_inicial);
//Conversão Data Texto - Fim

//Conversão Data - Início
$t_inicial = strtotime($data_inicial);
$data_inicial = date('m/d/Y',$t_inicial);
$t_final = strtotime($data_final);
$data_final_pre = date('m/d/Y',$t_final);
$data_final = date('m/d/Y', strtotime("+1 day", strtotime($data_final_pre)));
//Conversão Data - Fim

$vet_dados = explode(";", $txt_dados); //Converte em vetor
$tamanho_vet_dados = count($vet_dados); //Tamanho do vetor

$txt_in_telefones = "";
$txt_in_blacklist = "";
$flag = 0;

$tamanho_vet_dados_novo = 0;
$usuario = $_SERVER['LOGON_USER'];	// EXCLUIR PARA EXECUÇÃO LOCAL
$usuario = strtoupper($usuario); // EXCLUIR PARA EXECUÇÃO LOCAL
$usuario = substr($usuario, 10); // EXCLUIR PARA EXECUÇÃO LOCAL

// $usuario = 'C112568'; // INCLUIR PARA EXECUÇÃO LOCAL
$sql_blacklist = "";

for($a=0;$a<$tamanho_vet_dados;$a++){
	$tel = substr($vet_dados[$a], 0, 11); // TRATAMENTO ESPAÇOS EM BRANCO
	
	$palavra = "chk_$tel";
	if(isset($_POST["$palavra"])){
		if($flag == 0){
			$txt_in_telefones = "'$tel'";				
			$sql_blacklist = "INSERT INTO tb_blacklist (tipo, valor, matricula, identificador) VALUES ('telefone','$tel','$usuario','$identificador')";
		}
		else{
			$txt_in_telefones = "$txt_in_telefones,'$tel'";
			$sql_blacklist = "$sql_blacklist,('telefone','$tel','$usuario','$identificador')";
		}
		$flag = 1;
	}
}
$sql_blacklist = "$sql_blacklist;"; //FINALIZA SQL BLACKLIST


//INCLUSÃO BD BLACKLIST - INÍCIO
if ($qual_btn == '02'){
	
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b>Inclusão BLACKLIST</b><br><br>";
	if ($identificador=='') echo "<b>Identificador:</b> Sem Identificador<br>";
	else echo "<b>Identificador:</b> $identificador<br>";
	echo "<b>Matrícula:</b> $usuario<br><br>";
	echo "<b>Telefones Selecionados:</b>";
	$vet_dados_novo = explode(",", $txt_in_telefones); //Converte em vetor
	$tamanho_vet_dados_novo = count($vet_dados_novo); //Tamanho do vetor
	$flag = 0;
	for ($b=0;$b<$tamanho_vet_dados_novo;$b++){
		$tel_imprimir = substr($vet_dados_novo[$b], 0, 13);
		if ($flag == 0) echo "<br>$tel_imprimir";
		else echo "; $tel_imprimir";
		$flag = 1;
	}
	if ($vet_dados_novo[0] == ''){
		echo "<b class='w3-text-red'>Nenhum Telefone Selecionado</b>";
		echo "</div>";
		include "desconecta.php";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;		
	}
	
	$query = $pdo->prepare("$sql_blacklist");
	if ($query->execute() == '1'){	
		echo "<br><br><b class='w3-text-green'>TELEFONES INCLUÍDOS COM SUCESSO !</b>";
	}
	else echo "<br><br><b class='w3-text-red'>FALHA AO INCLUIR OS TELEFONES !</b>";
	
	echo "</div>";
	include "desconecta.php";
	echo "<script>$('#div_loading').fadeOut('slow');</script>";
	return;
}
//INCLUSÃO BD BLACKLIST - FIM

//EXCLUSÃO BD BLACKLIST - INÍCIO
if ($qual_btn == '03'){
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b>Exclusão BLACKLIST</b><br><br>";
	echo "<b>Matrícula:</b> $usuario<br><br>";
	echo "<b>Telefones Selecionados:</b>";
	$vet_dados_novo = explode(",", $txt_in_telefones); //Converte em vetor
	$tamanho_vet_dados_novo = count($vet_dados_novo); //Tamanho do vetor
	$flag = 0;
	for ($b=0;$b<$tamanho_vet_dados_novo;$b++){
		$tel_imprimir = substr($vet_dados_novo[$b], 0, 13);
		if ($flag == 0) echo "<br>$tel_imprimir";
		else echo "; $tel_imprimir";
		$flag = 1;
	}
	if ($vet_dados_novo[0] == ''){
		echo "<b class='w3-text-red'>Nenhum Telefone Selecionado</b>";
		echo "</div>";
		include "desconecta.php";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;		
	}
	
	$query = $pdo->prepare("delete from tb_blacklist where valor in ($txt_in_telefones)");
	if ($query->execute() == '1'){	
		echo "<br><br><b class='w3-text-green'>TELEFONES EXCLUÍDOS COM SUCESSO !</b>";
	}
	else echo "<br><br><b class='w3-text-red'>FALHA AO EXCLUIR OS TELEFONES !</b>";
	
	echo "</div>";
	include "desconecta.php";
	echo "<script>$('#div_loading').fadeOut('slow');</script>";
	return;
}
//EXCLUSÃO BD BLACKLIST - FIM



$flag = 0;
$txt_cod_dados = "";

if(isset($_POST["op_cpf"])){
	if ($flag == 0) $txt_cod_dados = "2";
	$flag = 1;
}

if(isset($_POST["op_endres"])){
	if ($flag == 0) $txt_cod_dados = "16";
	else $txt_cod_dados = "$txt_cod_dados,16";
	$flag = 1;
}

if(isset($_POST["op_endcom"])){
	if ($flag == 0) $txt_cod_dados = "18";
	else $txt_cod_dados = "$txt_cod_dados,18";
	$flag = 1;
}

if(isset($_POST["op_tel"])){
	if ($flag == 0) $txt_cod_dados = "22";
	else $txt_cod_dados = "$txt_cod_dados,22";
	$flag = 1;
}

if(isset($_POST["op_numoco"])){
	if ($flag == 0) $txt_cod_dados = "28";
	else $txt_cod_dados = "$txt_cod_dados,28";
	$flag = 1;
}

if(isset($_POST["op_descoco"])){
	if ($flag == 0) $txt_cod_dados = "31";
	else $txt_cod_dados = "$txt_cod_dados,31";
	$flag = 1;
}

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);
$t_final = strtotime($data_final);
$data_final_pre = date('m/d/Y',$t_final);
$data_final_texto = date('d/m/Y', strtotime("-1 day", strtotime($data_final_pre)));
//Conversão Data Texto - Fim

echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Relatório de Dados Críticos</b><br><br>";
echo "<b>Telefones Selecionados:</b>";
	
	$vet_dados_novo = explode(",", $txt_in_telefones); //Converte em vetor
	$tamanho_vet_dados_novo = count($vet_dados_novo); //Tamanho do vetor
	
	$flag = 0;
	
	for ($b=0;$b<$tamanho_vet_dados_novo;$b++){
		$tel_imprimir = substr($vet_dados_novo[$b], 0, 13);
		if ($flag == 0) echo "<br>$tel_imprimir";
		else echo "; $tel_imprimir";
		$flag = 1;
	}
	
	if ($vet_dados_novo[0] == '') echo "<b class='w3-text-red'>Nenhum Telefone Selecionado</b>";
	
echo "<br><br><b>Data Inicial:</b> $data_inicial_texto<br>";
echo "<b>Data Final:</b> $data_final_texto</b><br><br>";

	if ($vet_dados_novo[0] == ''){
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;
	}
	
echo '<div class="w3-border" style="padding:16px 16px;">';
echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<thead><tr class="w3-indigo w3-tiny">';
echo '<td><b>TELEFONE</b></td>';
echo '<td><b>CALLID</b></td>';
echo '<td><b>DATA_HORA</b></td>';
echo '<td><b>TIPO</b></td>';
echo '<td><b>VALOR</b></td>';
echo '</tr></thead><tbody>';

$query = $pdo->prepare("select a.TELEFONE, b.callid, b.data_hora, c.desc_dado, b.valor_dado
						from (select distinct callid, valor_dado TELEFONE from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and cod_dado = 3 and valor_dado in ($txt_in_telefones)) as a
						inner join (select distinct callid, data_hora, cod_dado, valor_dado from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and cod_dado in ($txt_cod_dados) and valor_dado <> '') as b
						on a.callid = b.callid
						inner join tb_tipo_dados as c on b.cod_dado = c.cod_dado");
$query->execute();

$passa = 1;
$TELEFONE_ant = "";
$callid_ant = "";
$data_hora_ant = "";
$desc_dado_ant = "";
$valor_dado_ant = "";

for($i=0; $row = $query->fetch(); $i++){
		
	$TELEFONE = utf8_encode($row['TELEFONE']);
	$callid = utf8_encode($row['callid']);
	$data_hora = utf8_encode($row['data_hora']);
	$desc_dado = utf8_encode($row['desc_dado']);
		if ($desc_dado == 'CPF') $desc_dado = 'CPF/CNPJ';
	$valor_dado = utf8_encode($row['valor_dado']);
	
	if (($TELEFONE_ant == $TELEFONE) && ($callid_ant == $callid) && ($data_hora_ant == $data_hora) && ($desc_dado_ant == $desc_dado) && ($valor_dado_ant == $valor_dado)) $passa = 0;
	else $passa = 1;
	
	if($passa == 1){
	
	if (($i > 0) && ($callid_anterior != $callid)) echo "<tr class = 'w3-topbartable'>";
	else echo '<tr>';	
		echo "<td>$TELEFONE</td>";	
		echo "<td>$callid</td>";	
		echo "<td>$data_hora</td>";		
		echo "<td>$desc_dado</td>";
		echo "<td>$valor_dado</td>";
	echo '</tr>';
	$callid_anterior = $callid;
	
	$TELEFONE_ant = $TELEFONE;
	$callid_ant = $callid;
	$data_hora_ant = $data_hora;
	$desc_dado_ant = $desc_dado;
	$valor_dado_ant = $valor_dado;
	}
}

echo "</tbody></table>";
echo "</div>";

include "desconecta.php";
?>

<script>
	$("#div_loading").fadeOut('slow');
</script>

</body>
</html>