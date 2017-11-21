<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script>
	function SomenteNumero(e){	
    	var tecla=(window.event)?event.keyCode:e.which;   
    	if((tecla>47 && tecla<58)) 
    	  		return true;
    	else{
    		if (tecla==8 || tecla==0)
    			return true;
    		else return false;
    	}
	}
</script>

<script>
    function mascaraHora_Inicial(campoHora, e){
    
        var tecla=(window.event)?event.keyCode:e.which;   
        if((tecla == 8)) return true;   
        var hora = campoHora.value;
        
            if (data.length == 2){
                hora = hora + ':';
                document.forms[0].hora_inicial.value = hora;
                return true;              
            }
            if (data.length == 5){
                hora = hora + ':';
                document.forms[0].hora_inicial.value = hora;
                return true;
            }
    }

</script>

<script>
    function mascaraHora_Final(campoHora, e){
    
        var tecla=(window.event)?event.keyCode:e.which;   
        if((tecla == 8)) return true;   
        var hora = campoHora.value;
        
            if (data.length == 2){
                hora = hora + ':';
                document.forms[0].hora_final.value = hora;
                return true;              
            }
            if (data.length == 5){
                hora = hora + ':';
                document.forms[0].hora_final.value = hora;
                return true;
            }
    }
</script>

<script>
function mascaraData_inicial(campoData, e){

	var tecla=(window.event)?event.keyCode:e.which;   
	if((tecla == 8)) return true;	
	var data = campoData.value;
    
	    if (data.length == 2){
	        data = data + '-';
	        document.forms[0].data_inicial.value = data;
			return true;              
	    }
	    if (data.length == 5){
	        data = data + '-';
	        document.forms[0].data_inicial.value = data;
	        return true;
	    }
}

function mascaraData_final(campoData, e){

	var tecla=(window.event)?event.keyCode:e.which;   
	if((tecla == 8)) return true;	
	var data = campoData.value;
    
	    if (data.length == 2){
	        data = data + '-';
	        document.forms[0].data_final.value = data;
			return true;              
	    }
	    if (data.length == 5){
	        data = data + '-';
	        document.forms[0].data_final.value = data;
	        return true;
	    }
}
</script>

<script>
$(function() {
    $( "#data_inicial" ).datepicker({
		prevText: 'Anterior',
		nextText: 'Próximo',
		currentText: 'Hoje',
        dateFormat: 'dd-mm-yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
    });
});
</script>

<script>
$(function() {
    $( "#data_final" ).datepicker({
		prevText: 'Anterior',
		nextText: 'Próximo',
		currentText: 'Hoje',
        dateFormat: 'dd-mm-yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
    });
});
</script>

<style>
.ui-datepicker{
	font-family:Verdana,sans-serif;
	font-size: 12px;
	padding-left:3px;
	padding-right:3px;
}

.ui-datepicker-header{
		margin-top:1px;
}

</style>

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

set_time_limit(3000);
ini_set('max_execution_time', 3000);

$usuario = $_SERVER['LOGON_USER'];	// EXCLUIR PARA EXECUÇÃO LOCAL
$usuario = strtoupper($usuario); // EXCLUIR PARA EXECUÇÃO LOCAL
$usuario = substr($usuario, 10); // EXCLUIR PARA EXECUÇÃO LOCAL

// $usuario = 'C112568'; // INCLUIR PARA EXECUÇÃO LOCAL
$txt_dados = "";

//---------------------iniciando contador de tempo de execução da consulta---------------------// 
list($usec, $sec) = explode(' ', microtime()); 
$script_start = (float) $sec + (float) $usec;

$txt_filtro = '';
//Variáveis do Formulário - Início
$tipo_consulta = $_POST['tipo_consulta'];
$qtd_minima = $_POST['qtd_minima'];
	if ($qtd_minima == NULL) $qtd_minima = '0';
$data_inicial = $_POST['data_inicial'];
$data_inicial_txt_tela = $data_inicial;
$data_final = $_POST['data_final'];
$data_final_txt_tela = $data_final;

$filtro_matricula = $_POST['filtro_matricula'];
	$filtro_matricula = strtoupper($filtro_matricula);
	if($filtro_matricula == '' || $filtro_matricula == 'CXXXXXX'){
		$filtro_matricula = '';
	} else $txt_filtro = "$txt_filtro and matricula = '$filtro_matricula'";
	
$filtro_identificador = $_POST['filtro_identificador'];
	if($filtro_identificador == ''){
		$filtro_identificador = '';
	} else $txt_filtro = "$txt_filtro and identificador = '$filtro_identificador'";

$input_telefone = $_POST['input_telefone'];
$tipo_acao = $_POST['tipo_acao'];
$input_ins_identificador = $_POST['input_ins_identificador'];

// GERENCIAR BLACKLIST - INÍCIO
if($tipo_consulta=='05'){
	
	// EXCLUIR TELEFONE - INÍCIO
	if($tipo_acao=='01'){
		$query = $pdo->prepare("select count(*) TOTAL from tb_blacklist where tipo = 'telefone' and valor = '$input_telefone'");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){			
			$TOTAL = utf8_encode($row['TOTAL']);
			if($TOTAL == '0'){
				echo "<script>alert('Telefone não existe na BLACKLIST!')</script>";
				echo "<script>$('#div_loading').fadeOut('slow');</script>";
				return;
			}
		}
		$query = $pdo->prepare("delete from tb_blacklist where tipo = 'telefone' and valor = '$input_telefone'");
		if($query->execute()) echo "<script>alert('Telefone $input_telefone excluído com sucesso!')</script>";
		else echo "<script>alert('Falha ao excluir!')</script>";
		include "desconecta.php";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;
	}
	// EXCLUIR TELEFONE - FIM
	
	// INCLUIR TELEFONE - INÍCIO
	if($tipo_acao=='02'){
		$query = $pdo->prepare("insert into tb_blacklist (tipo,valor,matricula,identificador)
								values ('telefone','$input_telefone','$usuario','$input_ins_identificador');");
		if($query->execute()) echo "<script>alert('Telefone $input_telefone incluído com sucesso!')</script>";
		else echo "<script>alert('Falha ao excluir!')</script>";
		include "desconecta.php";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;
	}
	// INCLUIR TELEFONE - FIM
	
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>Exibir BLACKLIST</b><br><br>";
	if($filtro_identificador=='') echo "<b>Filtro Identificador:</b> Sem Filtro<br>";
	else echo "<b>Filtro Identificador:</b> $filtro_identificador<br>";
	if($filtro_matricula=='') echo "<b>Filtro Matrícula:</b> Sem Filtro<br><br>";
	else echo "<b>Filtro Matrícula:</b> $filtro_matricula<br><br>";
	
	// EXIBIR BLACKLIST - INÍCIO
	if($tipo_acao=='03'){
		// DIV NOVA PESQUISA - INÍCIO
		echo "<form name = 'meuform' id = 'meuform' action='gera_relatorio.php' method='post' class='w3-container w3-tiny' target='_blank'>
				<div id = 'div_critica' class='w3-tiny w3-container w3-light-grey w3-border-indigo w3-margin-left w3-margin-right w3-padding-16 w3-card-4 w3-topbar w3-bottombar w3-round'>
					<div class='w3-center'>
						<b class = 'w3-margin-left'>Dados Críticos:</b>
						<input class = 'w3-margin-left' type='checkbox' id='op_cpf' name='op_cpf' checked><font> CPF/CNPJ Acessados</font>
						<input class = 'w3-margin-left' type='checkbox' id='op_endres' name='op_endres' checked><font> Novo Endereço Residencial</font>
						<input class = 'w3-margin-left' type='checkbox' id='op_endcom' name='op_endcom' checked><font> Novo Endereço Comercial</font>
						<input class = 'w3-margin-left' type='checkbox' id='op_tel' name='op_tel' checked><font> Novo Telefone Celular</font>
						<input class = 'w3-margin-left' type='checkbox' id='op_numoco' name='op_numoco' checked><font> Nº da Nova Ocorrência</font>
						<input class = 'w3-margin-left' type='checkbox' id='op_descoco' name='op_descoco' checked><font> Descrição da Nova Ocorrência</font>
					</div>	
				
					<div class='w3-center w3-margin-top'>			
						<font id='txt_data_inicial' class='w3-margin-left'>Data Inicial do Relatório:</font>
						<input id='data_inicial' type='text' size='10' name='data_inicial' value='$data_inicial' onkeypress='mascaraData_inicial(this, event);' maxlength='10'>
							
						<font id='txt_data_final' class='w3-margin-left'>Data Final do Relatório:</font>
						<input id='data_final' type='text' size='10' name='data_final' value='$data_final' onkeypress='mascaraData_final(this, event);' maxlength='10'>
						
						<button id='btn_pesquisar' class='w3-btn w3-deep-orange w3-round w3-tiny w3-margin-left' type='submit' name='btn_pesquisar' value='01'>Relatório de Dados Críticos</button>

						<button id='btn_pesquisar' class='w3-btn w3-black w3-round w3-tiny w3-margin-left' type='submit' name='btn_pesquisar' value='03'>Excluir Seleção da BLACKLIST</button>
					</div>			
				</div>
				<script>$('#div_critica').hide();</script>";
		// DIV NOVA PESQUISA - FIM

		$txt_dados = '';
		$contador = 0;
		$query = $pdo->prepare("select * from tb_blacklist where tipo <> 'gambiarra' $txt_filtro order by data_hora desc");		
		$query->execute();
		
		echo '<div id="tabela" class="w3-margin-right w3-margin-left w3-margin-top w3-tiny w3-center">';
		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-indigo w3-tiny">';
		echo '<td><b>DATA_HORA</b></td>';
		echo "<td><b>TELEFONE</b></td>";
		echo "<td><b>MATRÍCULA</b></td>";
		echo "<td><b>IDENTIFICADOR</b></td>";
		echo '</tr>';
		
		for($i=0; $row = $query->fetch(); $i++){			
			$data_hora = utf8_encode($row['data_hora']);
			$valor = utf8_encode($row['valor']);
			
			$valor = substr($valor, 0, 11); //TRATAMENTO ESPAÇO EM BRANCO, PODE DELETAR, SEM DEMAIS TRATAMENTOS
			if($i==0) $txt_dados = $valor;
			else $txt_dados = $txt_dados.";$valor";
			
			$matricula = utf8_encode($row['matricula']);
			$identificador = utf8_encode($row['identificador']);
			
			echo "<tr>";
				echo "<td>$data_hora&nbsp&nbsp&nbsp&nbsp</font><input class='w3-right' type='checkbox' id='chk_$valor' name='chk_$valor' value='$valor'></td>";
				
				echo "<td>$valor</td>";
				
				echo "<td>$matricula</td>";
				echo "<td>$identificador</td>";
			echo "</tr>";
			$contador++;			
		}
		
		echo "</table>";
		echo "</div>";
		
		if($contador==0){
				echo "<script>$('#tabela').hide();</script>";
				echo '<div class="w3-margin w3-tiny w3-center">';
					echo "<b class='w3-text-red'>Nenhum Registro Encontrado !</b>";
				echo '</div>';
				include "desconecta.php";
				echo "<script>$('#div_loading').fadeOut('slow');</script>";
				return;
		}
		echo "<script>$('#div_critica').show();</script>";
		
		// ALTERA O ENDEREÇO DO FORM
		echo
		"
		<script>
			$('#meuform')[0].setAttribute('action', 'gera_relatorio.php?txt_dados=$txt_dados&data_inicial=$data_inicial&data_final=$data_final&gerenciar=1');
		</script>
		</form>
		";
		
		include "desconecta.php";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;
	}
	// EXIBIR BLACKLIST - FIM
}
// GERENCIAR BLACKLIST - FIM

// PESQUISA BLACKLIST - INÍCIO
if($tipo_consulta=='04'){
	
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
	
	//Conversão Data Texto - Início
	$t_inicial = strtotime($data_inicial);
	$data_inicial_texto = date('d/m/Y',$t_inicial);
	$t_final = strtotime($data_final);
	$data_final_pre = date('m/d/Y',$t_final);
	$data_final_texto = date('d/m/Y', strtotime("-1 day", strtotime($data_final_pre)));
	//Conversão Data Texto - Fim
	
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>Monitorar BLACKLIST - Dados Críticos</b><br><br>";
	
	echo "<b>Data Inicial:</b> $data_inicial_texto<br>";
	echo "<b>Data Final:</b> $data_final_texto</b><br><br>";
	
	if($filtro_identificador=='') echo "<b>Filtro Identificador:</b> Sem Filtro<br>";
	else echo "<b>Filtro Identificador:</b> $filtro_identificador<br>";

	if($filtro_matricula=='') echo "<b>Filtro Matrícula:</b> Sem Filtro<br>";
	else echo "<b>Filtro Matrícula:</b> $filtro_matricula<br>";
	
	echo '</div>';
	//Verifica Preenchimento do INDEX - Início
	if ($data_inicial==NULL){
		echo "<script> alert('Data Inicial não preenchida!'); </script>";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;
	}
	if ($data_final==NULL){
		echo "<script> alert('Data Final não preenchida!'); </script>";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;	
	}
	
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
	//Verifica Preenchimento do INDEX - Fim
	
	echo '<div id="tabela" class="w3-margin-right w3-margin-left w3-tiny w3-center">';
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
	echo '<tr class="w3-indigo w3-tiny">';
	echo '<td><b>TELEFONE</b></td>';
	echo "<td><b>CALLID</b></td>";
	echo "<td><b>DATA_HORA</b></td>";
	echo "<td><b>TIPO</b></td>";
	echo "<td><b>VALOR</b></td>";
	echo '</tr>';
	
	$query = $pdo->prepare("select telefone, x.callid, data_hora, desc_dado, valor_dado
							from tb_dados_cadastrais as x
							inner join (select distinct callid, valor as telefone
							from tb_dados_cadastrais as a
							inner join tb_blacklist as b
							on a.valor_dado = b.valor
							where a.data_hora between '$data_inicial' and '$data_final' and a.cod_dado = 3 $txt_filtro
							) as y
							on x.callid = y.callid
							inner join tb_tipo_dados as z
							on x.cod_dado = z.cod_dado
							where x.data_hora between '$data_inicial' and '$data_final' and x.cod_dado in ($txt_cod_dados) and valor_dado <> ''
							order by telefone, data_hora");
	$query->execute();
	
	$num_linhas = 0;
	for($i=0; $row = $query->fetch(); $i++){
		
			if ($i > 0) $callid_anterior = $CALLID;
			
			$TELEFONE = utf8_encode($row['telefone']);
			$CALLID = utf8_encode($row['callid']);
			$DATA_HORA = utf8_encode($row['data_hora']);
			$TIPO = utf8_encode($row['desc_dado']);
				IF($TIPO == 'CPF') $TIPO = 'CPF/CNPJ';
			$VALOR = utf8_encode($row['valor_dado']);
			
			if (($i > 0) && ($callid_anterior != $CALLID)) echo "<tr class = 'w3-topbartable'>";
			else echo '<tr>';	
				echo "<td>$TELEFONE</td>";
				echo "<td>$CALLID</td>";
				echo "<td>$DATA_HORA</td>";
				echo "<td>$TIPO</td>";
				echo "<td>$VALOR</td>";
			echo "</tr>";
			$num_linhas++;
	}

	if ($num_linhas==0){
		echo "<script> $('#tabela').hide(); </script>";
		echo "</table>";
		echo "</div>";
		
		echo '<div class="w3-margin w3-tiny w3-center">';
			echo "<b class='w3-text-red'>Nenhum Registro Encontrado !</b>";
		echo '</div>';
		
		include "desconecta.php";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		return;
	}	
	
	echo "</table>";
	echo "</div>";
	include "desconecta.php";
	echo "<script>$('#div_loading').fadeOut('slow');</script>";
	return;
}
// PESQUISA BLACKLIST - FIM

//Verifica Preenchimento do INDEX - Início
if ($data_inicial==NULL){
	echo "<script> alert('Data Inicial não preenchida!'); </script>";
	echo "<script>$('#div_loading').fadeOut('slow');</script>";
	return;
}
if ($data_final==NULL){
	echo "<script> alert('Data Final não preenchida!'); </script>";
	echo "<script>$('#div_loading').fadeOut('slow');</script>";
	return;	
}
//Verifica Preenchimento do INDEX - Fim

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

//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);
$t_final = strtotime($data_final);
$data_final_pre = date('m/d/Y',$t_final);
$data_final_texto = date('d/m/Y', strtotime("-1 day", strtotime($data_final_pre)));
//Conversão Data Texto - Fim

echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
if($tipo_consulta=='01' ) echo "<b>Telefone acessando vários CPF/CNPJ</b><br><br>";
if($tipo_consulta=='02' ) echo "<b>Telefone acessando atendimento humano via menu 'Perda e Roubo'</b><br><br>";
if($tipo_consulta=='03' ) echo "<b>Telefone acessando atendimento humano sem cartão via CPF</b><br><br>";
echo "<b>Data Inicial:</b> $data_inicial_texto<br>";
echo "<b>Data Final:</b> $data_final_texto</b>";
echo "</div>";

// DIV NOVA PESQUISA - INÍCIO
echo "<form name = 'meuform' id = 'meuform' action='gera_relatorio.php' method='post' class='w3-container w3-tiny' target='_blank'>
		<div id = 'div_critica' class='w3-tiny w3-container w3-light-grey w3-border-indigo w3-margin-left w3-margin-right w3-padding-16 w3-card-4 w3-topbar w3-bottombar w3-round'>
			<div class='w3-center'>
				<b class = 'w3-margin-left'>Dados Críticos:</b>
				<input class = 'w3-margin-left' type='checkbox' id='op_cpf' name='op_cpf' checked><font> CPF/CNPJ Acessados</font>
				<input class = 'w3-margin-left' type='checkbox' id='op_endres' name='op_endres' checked><font> Novo Endereço Residencial</font>
				<input class = 'w3-margin-left' type='checkbox' id='op_endcom' name='op_endcom' checked><font> Novo Endereço Comercial</font>
				<input class = 'w3-margin-left' type='checkbox' id='op_tel' name='op_tel' checked><font> Novo Telefone Celular</font>
				<input class = 'w3-margin-left' type='checkbox' id='op_numoco' name='op_numoco' checked><font> Nº da Nova Ocorrência</font>
				<input class = 'w3-margin-left' type='checkbox' id='op_descoco' name='op_descoco' checked><font> Descrição da Nova Ocorrência</font>
			</div>	
		
			<div class='w3-center w3-margin-top'>			
				<font id='txt_data_inicial' class='w3-margin-left'>Data Inicial do Relatório:</font>
				<input id='data_inicial' type='text' size='10' name='data_inicial' value='$data_inicial_txt_tela' onkeypress='mascaraData_inicial(this, event);' maxlength='10'>
					
				<font id='txt_data_final' class='w3-margin-left'>Data Final do Relatório:</font>
				<input id='data_final' type='text' size='10' name='data_final' value='$data_final_txt_tela' onkeypress='mascaraData_final(this, event);' maxlength='10'>
				
				<button id='btn_pesquisar' class='w3-btn w3-deep-orange w3-round w3-tiny w3-margin-left' type='submit' name='btn_pesquisar' value='01'>Relatório de Dados Críticos</button>
				
				<font id='txt_data_final' class='w3-margin-left'><b>Identificador</b> <i>(BLACKLIST)</i>:</font>
				<input id='identificador' type='text' size='10' name='identificador' value='' maxlength='10'>

				<button id='btn_pesquisar' class='w3-btn w3-black w3-round w3-tiny w3-margin-left' type='submit' name='btn_pesquisar' value='02' >Incluir Seleção na BLACKLIST</button>
			</div>			
		</div>
		
		<script>
			$('#div_critica').hide();
		</script>";
// DIV NOVA PESQUISA - FIM

include "def_var_ura.php";

echo '<div class="w3-margin-right w3-margin-left w3-tiny w3-center">';
echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
echo '<tr class="w3-indigo w3-tiny">';
echo '<td><b>TELEFONE</b></td>';
	if($tipo_consulta == '01') echo "<td><b>TOTAL DE CPF/CNPJ DISTINTOS ACESSADOS</b></td>";
	if($tipo_consulta == '02') echo "<td><b>TOTAL DE ACESSOS</b></td>";
	if($tipo_consulta == '03') echo "<td><b>TOTAL DE ACESSOS</b></td>";
echo '</tr>';

if ($tipo_consulta == '01'){
	$query = $pdo->prepare("select a.valor_dado DADO, COUNT (DISTINCT b.valor_dado) TOTAL
							FROM (select callid, valor_dado
							from tb_dados_cadastrais
							where cod_dado = '3' and data_hora between '$data_inicial' and '$data_final' and len(valor_dado) = 11 and substring(valor_dado,1,3) <> 00) as a
							INNER JOIN (select callid, valor_dado
							from tb_dados_cadastrais
							where cod_dado = '2' and data_hora between '$data_inicial' and '$data_final') as b
							ON a.callid = b.callid
							GROUP BY a.valor_dado
							HAVING COUNT (DISTINCT b.valor_dado) >= $qtd_minima
							ORDER BY TOTAL DESC");
	$query->execute();
		
	for($i=0; $row = $query->fetch(); $i++){
		if( ($row['DADO'] != null) && ($row['DADO'] != "undefined") && ($row['DADO'] != "anonymous") ){
			$DADO = utf8_encode($row['DADO']);
				$DADO = substr($DADO, 0, 11); //TRATAMENTO ESPAÇO EM BRANCO, PODE DELETAR, SEM DEMAIS TRATAMENTOS
				if($i==0) $txt_dados = $DADO;
				else $txt_dados = $txt_dados.";$DADO";
			$TOTAL = utf8_encode($row['TOTAL']);
		
			echo "<tr>";
			echo "<td><div class='w3-dropdown-hover'> 
				<font class='w3-text-indigo'><a href='#'>$DADO</a></font><div class='w3-dropdown-content w3-border w3-border-grey w3-light-grey w3-card-16'>
				<a href= \"consulta_ura.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log URA</a>
				<a href= \"consulta_front.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log FRONTEND</a>
				<a href= \"consulta_cadastral.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log REGISTROS</a>
				</div></div><font>&nbsp&nbsp&nbsp&nbsp</font><input type='checkbox' id='chk_$DADO' name='chk_$DADO' value='$DADO'></td>";
			echo "<td><font>$TOTAL</font></td>";
			echo "</tr>";
		}
	}
}

if ($tipo_consulta == '02'){
	$query = $pdo->prepare("select valor_dado DADO, count(distinct a.callid) TOTAL
							from tb_dados_cadastrais as a
							inner join tb_eventos_URA as b
							on a.callid = b.callid
							where a.cod_dado = '3' and a.data_hora between '$data_inicial' and '$data_final' and b.data_hora between '$data_inicial' and '$data_final' and b.cod_evento like '%MENU017%' and len(a.valor_dado) = 11 and substring(valor_dado,1,3) <> 00
							group by valor_dado
							having count(distinct a.callid) >= $qtd_minima
							order by TOTAL desc");
	$query->execute();
		
	for($i=0; $row = $query->fetch(); $i++){
		if( ($row['DADO'] != null) && ($row['DADO'] != "undefined") && ($row['DADO'] != "anonymous") ){
			$DADO = utf8_encode($row['DADO']);
				$DADO = substr($DADO, 0, 11); //TRATAMENTO ESPAÇO EM BRANCO, PODE DELETAR, SEM DEMAIS TRATAMENTOS
				if($i==0) $txt_dados = $DADO;
				else $txt_dados = $txt_dados.";$DADO";
			$TOTAL = utf8_encode($row['TOTAL']);
		
			echo "<tr>";
			echo "<td> <div class='w3-dropdown-hover'> 
				<font class='w3-text-indigo'><a href='#'>$DADO</a></font> <div class='w3-dropdown-content w3-border w3-border-grey w3-light-grey w3-card-16'>
				<a href= \"consulta_ura.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log URA</a>
				<a href= \"consulta_front.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log FRONTEND</a>
				<a href= \"consulta_cadastral.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log REGISTROS</a>
				</div></div><font>&nbsp&nbsp&nbsp&nbsp</font><input type='checkbox' id='chk_$DADO' name='chk_$DADO' value='$DADO'></td>";
			echo "<td><font>$TOTAL</font></td>";
			echo "</tr>";
		}
	}
}

if ($tipo_consulta == '03'){
	$query = $pdo->prepare("select valor_dado DADO, count(distinct a.callid) TOTAL
							from tb_dados_cadastrais as a
							inner join tb_eventos_URA as b
							on a.callid = b.callid
							where a.cod_dado = '3' and a.data_hora between '$data_inicial' and '$data_final' and b.data_hora between '$data_inicial' and '$data_final' and b.cod_evento like '%A036;A008;A010;A011%' and len(a.valor_dado) = 11 and substring(valor_dado,1,3) <> 00
							group by valor_dado
							having count(distinct a.callid) >= $qtd_minima
							order by TOTAL desc");
	$query->execute();
		
	for($i=0; $row = $query->fetch(); $i++){
		if( ($row['DADO'] != null) && ($row['DADO'] != "undefined") && ($row['DADO'] != "anonymous") ){
			$DADO = utf8_encode($row['DADO']);
				$DADO = substr($DADO, 0, 11); //TRATAMENTO ESPAÇO EM BRANCO, PODE DELETAR, SEM DEMAIS TRATAMENTOS
				if($i==0) $txt_dados = $DADO;
				else $txt_dados = $txt_dados.";$DADO";
			$TOTAL = utf8_encode($row['TOTAL']);
		
			echo "<tr>";
			echo "<td> <div class='w3-dropdown-hover'> 
				<font class='w3-text-indigo'><a href='#'>$DADO</a></font> <div class='w3-dropdown-content w3-border w3-border-grey w3-light-grey w3-card-16'>
				<a href= \"consulta_ura.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log URA</a>
				<a href= \"consulta_front.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log FRONTEND</a>
				<a href= \"consulta_cadastral.php?data_inicial=$data_inicial&data_final=$data_final&DADO=$DADO\" target=\"_blank\">Log REGISTROS</a>
				</div></div><font>&nbsp&nbsp&nbsp&nbsp</font><input type='checkbox' id='chk_$DADO' name='chk_$DADO' value='$DADO'></td>";
			echo "<td><font>$TOTAL</font></td>";
			echo "</tr>";
		}
	}
}

echo '</table>';
echo "</div>";

// ALTERA O ENDEREÇO DO FORM
	echo
	"
	<script>
		$('#meuform')[0].setAttribute('action', 'gera_relatorio.php?txt_dados=$txt_dados&data_inicial=$data_inicial&data_final=$data_final&gerenciar=0');
	</script>
	";

// -----------------------------------------FINALIZANDO O CONTADOR DE TEMPO DA CONSULTA ------------------------------------------//
    list($usec, $sec) = explode(' ', microtime());
    $script_end = (float) $sec + (float) $usec;
    $elapsed_time = round($script_end - $script_start, 5);
   
    $elapsed_time = intval($elapsed_time);
    if ($elapsed_time >= 60)
    {
        $minutos = intval($elapsed_time/60);
        $segundos = ((($elapsed_time/60) - $minutos)*60);
    }
    else
    {
        $minutos = 0;
        $segundos = $elapsed_time;
    }    
    
    
    if ($minutos == 1)
      $texto_tempo = "$minutos minuto ";
    else if ($minutos > 1) 
      $texto_tempo = "$minutos minutos ";
    else
      $texto_tempo = "";
    
    if (($texto_tempo != "") and ($segundos >0))
      $texto_tempo = $texto_tempo." e ";
    
    if ($segundos == 1)
      $texto_tempo = $texto_tempo."$segundos segundo";
    else if ($segundos > 1)  
      $texto_tempo = $texto_tempo."$segundos segundos"; 
   
    echo '<div class="w3-margin w3-tiny w3-right w3-margin">';
    echo "<b>Tempo de Execução: </b><i>$texto_tempo</i><br>";
    echo '<br><br><br>';
	
	echo "<script>$('#div_critica').show();</script>";		

include "desconecta.php";
echo "<script>$('#div_loading').fadeOut('slow');</script>";
return;
?>

</form>

<script>
	$("#div_loading").fadeOut('slow');
</script>

</body>
</html>