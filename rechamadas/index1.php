<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<!-- CSS CHECKBOX NS 90 SEGUNDOS -->
<style>
.div_dia {
    float: left;
}
</style>

<script>
$(document).ready(function(){
	$("#tipo_consulta").change(function(){
		if ($("#tipo_consulta").val() == "01"){
			$ ('#frame_230', top.document).eq(0).attr ('rows', '205,*');
			$("#txt_qtd_pesq").text("Quantidade mínima de CPF/CNPJ acessados:");
			$("#txt_qtd_pesq").show();
			$("#qtd_minima").show();
			$("#dados_criticos").hide();
			$("#div_matricula").hide();
			$("#div_identificador").hide();
			$("#div_datas").show();
			$("#div_acao").hide();
			$("#btn_pesquisar").html("Consultar");
			$("#div_telefone").hide();
			$("#div_ins_identificador").hide();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
			$("#div_cpf").hide();
			$("#div_numero_chamador").hide();
			$("#div_inicio_cartao").hide();
			$("#div_final_cartao").hide();
		}
		if ($("#tipo_consulta").val() == "02"){
			$ ('#frame_230', top.document).eq(0).attr ('rows', '205,*');
			$("#txt_qtd_pesq").text("Quantidade mínima de acessos:");
			$("#txt_qtd_pesq").show();
			$("#qtd_minima").show();
			$("#dados_criticos").hide();
			$("#div_matricula").hide();
			$("#div_identificador").hide();
			$("#div_datas").show();
			$("#div_acao").hide();
			$("#btn_pesquisar").html("Consultar");
			$("#div_telefone").hide();
			$("#div_ins_identificador").hide();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
			$("#div_cpf").hide();
			$("#div_numero_chamador").hide();
			$("#div_inicio_cartao").hide();
			$("#div_final_cartao").hide();
		}
		if ($("#tipo_consulta").val() == "03"){
			$ ('#frame_230', top.document).eq(0).attr ('rows', '205,*');
			$("#txt_qtd_pesq").text("Quantidade mínima de acessos:");
			$("#txt_qtd_pesq").show();
			$("#qtd_minima").show();
			$("#dados_criticos").hide();
			$("#div_matricula").hide();
			$("#div_identificador").hide();
			$("#div_datas").show();
			$("#div_acao").hide();
			$("#btn_pesquisar").html("Consultar");
			$("#div_telefone").hide();
			$("#div_ins_identificador").hide();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
			$("#div_cpf").hide();
			$("#div_numero_chamador").hide();
			$("#div_inicio_cartao").hide();
			$("#div_final_cartao").hide();
		}
		if ($("#tipo_consulta").val() == "04"){	
			$ ('#frame_230', top.document).eq(0).attr ('rows', '241,*'); 
			$("#txt_qtd_pesq").hide();
			$("#qtd_minima").hide();
			$("#dados_criticos").show();
			$("#div_matricula").show();
			$("#div_identificador").show();
			$("#div_datas").show();
			$("#div_acao").hide();
			$("#btn_pesquisar").html("Consultar");
			$("#div_telefone").hide();
			$("#div_ins_identificador").hide();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
			$("#div_cpf").hide();
			$("#div_numero_chamador").hide();
			$("#div_inicio_cartao").hide();
			$("#div_final_cartao").hide();
		}
		if ($("#tipo_consulta").val() == "05"){
			$ ('#frame_230', top.document).eq(0).attr ('rows', '205,*');
			$("#txt_qtd_pesq").hide();
			$("#qtd_minima").hide();
			$("#dados_criticos").hide();
			$("#div_matricula").hide();
			$("#div_identificador").hide();
			$("#div_datas").hide();
			$("#div_acao").show();
			$("#btn_pesquisar").html("Executar");
			$("#div_telefone").show();
			$("#div_ins_identificador").hide();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
			$("#div_cpf").hide();
			$("#div_numero_chamador").hide();
			$("#div_inicio_cartao").hide();
			$("#div_final_cartao").hide();
			if ($("#tipo_acao").val() == "03"){
				$("#div_telefone").hide();
				$("#div_matricula").show();
				$("#div_identificador").show();
			}
			if ($("#tipo_acao").val() == "02"){
				$("#div_ins_identificador").show();
			}
		}
		
		if ($("#tipo_consulta").val() == "06"){
			$ ('#frame_230', top.document).eq(0).attr ('rows', '241,*');
			$("#txt_qtd_pesq").hide();
			$("#qtd_minima").hide();
			$("#dados_criticos").hide();
			$("#div_matricula").hide();
			$("#div_identificador").hide();
			$("#div_datas").show();
			$("#div_acao").hide();
			$("#btn_pesquisar").html("Pesquisar");
			$("#div_telefone").show();
			$("#div_ins_identificador").hide();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
			$("#div_cpf").show();
			$("#div_numero_chamador").show();
			$("#div_inicio_cartao").show();
			$("#div_final_cartao").show();
		}
	});    
});
</script>

<script>
$(document).ready(function(){
	$("#tipo_acao").change(function(){
		if ($("#tipo_acao").val() == "01"){
			$("#div_telefone").show();
			$("#div_matricula").hide();
			$("#div_identificador").hide();
			$("#div_ins_identificador").hide();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
		}
		if ($("#tipo_acao").val() == "02"){
			$("#div_telefone").show();
			$("#div_matricula").hide();
			$("#div_identificador").hide();
			$("#div_ins_identificador").show();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
		}
		if ($("#tipo_acao").val() == "03"){
			$("#div_telefone").hide();
			$("#div_matricula").show();
			$("#div_identificador").show();
			$("#div_ins_identificador").hide();
			$('#frame_secundario', top.document).eq(0).attr('src', "");
		}
	});    
});
</script>

<script>
	function SomenteNumero(e, dado, limite){
		var numero = dado.value;
    	var tecla=(window.event)?event.keyCode:e.which;   
    	if((tecla>47 && tecla<58 && numero.length<limite)) 
    	  		return true;
    	else{
    		if (tecla==8 || tecla==0)
    			return true;
    		else  return false;
    	}
	}

	function SomenteValor(e, dado, limite){	
		var numero = dado.value;
    	var tecla=(window.event)?event.keyCode:e.which;   
    	if((tecla>47 && tecla<58 && numero.length<limite)||(tecla==44 && numero.length<limite)) 
    	  		return true;
    	else{
    		if (tecla==8 || tecla==0)
    			return true;
    		else  return false;
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
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		beforeShow: aumentaFrame,
		onClose: diminuiFrame
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
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		beforeShow: aumentaFrame,
		onClose: diminuiFrame
    });
});
</script>

<script>
function aumentaFrame(){  
     $ ('#frame_230', top.document).eq(0).attr ('rows', '390,*');
};
</script>

<script>
function diminuiFrame(){  
     
	 if ($("#tipo_consulta").val() == "04" || $("#tipo_consulta").val() == "06"){	
			$ ('#frame_230', top.document).eq(0).attr ('rows', '241,*'); 
		} else $ ('#frame_230', top.document).eq(0).attr ('rows', '205,*');
	 
};
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

<!-- LOGO INÍCIO -->
<br>
	<div class="w3-container w3-center">
		<img src="logo.png" style="width:140px">		
		<a href="manual.pdf" target="_blank" style ="position:absolute;right:16px;top:27px;">
			<div class="w3-right w3-padding-0 w3-padding-top">
				<font style="font-family:Verdana;font-size:14px;"><u>Manual</u></font> <img src="livro.png" style="padding-bottom:2px;">
			</div>
		</a>
	</div>			
<hr class="w3-margin">
<!-- LOGO FIM -->

<!-- TÍTULO INÍCIO -->
<div class='w3-container w3-padding w3-margin w3-tiny w3-center w3-indigo w3-wide w3-card-4'><b>FraudSCAM  - Monitoramento de Ligações - Cartão de Crédito</b>
</div>
<!-- TÍTULO FIM -->

<!-- FORMULÁRIO - INÍCIO -->
<div class="w3-tiny w3-container w3-light-grey w3-bottombar w3-border-indigo w3-margin-left w3-margin-right w3-padding-0 w3-card-4 w3-round">
	
	<form action="imprime_tabela.php" method="post" class="w3-container w3-tiny" target="frame_secundario">

		<div class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">		
			<b>Consulta:</b>
			<select id= "tipo_consulta" name="tipo_consulta">
				<option value="01">Telefone acessando vários CPF/CNPJ</option>
				<option value="02">Telefone acessando atendimento humano via menu 'Perda e Roubo'</option>
				<!-- <option value="03">Telefone acessando atendimento humano sem cartão via CPF</option> -->
				<option value="04">Monitorar BLACKLIST - Dados Críticos</option>
				<option value="05">Gerenciar BLACKLIST</option>
				<option value="06">Pesquisa por dados</option>
			</select>
		</div>
		
		<div id="div_acao" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">		
			<b>Ação:</b>
			<select id= "tipo_acao" name="tipo_acao">
				<option value="01">Excluir Telefone</option>
				<option value="02">Incluir Telefone</option>
				<option value="03">Exibir BLACKLIST</option>
			</select>
		</div>
		
		<div id="div_datas" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">							
			<b id="txt_data_inicial">Data Inicial:</b>
			<input id="data_inicial" type='text' size='10' name="data_inicial" value='' onkeypress="mascaraData_inicial(this, event);" maxlength="10">
			
			<b id="txt_data_final" class='w3-margin-left'>Data Final:</b>
			<input id="data_final" type='text' size='10' name="data_final" value='' onkeypress="mascaraData_final(this, event);" maxlength="10">
		</div>
		
		<div id="div_telefone" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">
			<b id="txt_telefone">Telefone:</b>
			<input id="input_telefone" type='text' size='11' name="input_telefone" value='' maxlength="11">
		</div>
		
		<div id="div_matricula" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">
			<b id="txt_filtro_matricula">Filtrar Matrícula:</b>
			<input id="input_matricula" type='text' size='10' name="filtro_matricula" value='Cxxxxxx' maxlength="7">
		</div>
		
		<div id="div_identificador" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">	
			<b id="txt_filtro_identificador">Filtrar Identificador:</b>
			<input id="input_identificador" type='text' size='10' name="filtro_identificador" value='' maxlength="10">
		</div>
			
		<div class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">	
			<b id="txt_qtd_pesq">Quantidade Mínima de CPF/CNPJ Acessados:</b>
			<input id="qtd_minima" type='text' size='2' name="qtd_minima" value='3' onkeypress="mascaraData_final(this, event);">			
		</div>
		
		<div id="div_ins_identificador" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">	
			<b id="txt_ins_identificador">Identificador:</b>
			<input id="input_ins_identificador" type='text' size='10' name="input_ins_identificador" value='' maxlength="10">
		</div>
		
		<div id='dados_criticos' class="w3-tiny w3-margin-top w3-margin-left w3-left">
			<b>Dados Críticos:</b>
			<input class = 'w3-margin-left' type='checkbox' id='op_cpf' name='op_cpf' checked><font> CPF/CNPJ Acessados</font>
			<input class = 'w3-margin-left' type='checkbox' id='op_endres' name='op_endres' checked><font> Novo Endereço Residencial</font>
			<input class = 'w3-margin-left' type='checkbox' id='op_endcom' name='op_endcom' checked><font> Novo Endereço Comercial</font>
			<input class = 'w3-margin-left' type='checkbox' id='op_tel' name='op_tel' checked><font> Novo Telefone Celular</font>
			<input class = 'w3-margin-left' type='checkbox' id='op_numoco' name='op_numoco' checked><font> Nº da Nova Ocorrência</font>
			<input class = 'w3-margin-left' type='checkbox' id='op_descoco' name='op_descoco' checked><font> Descrição da Nova Ocorrência</font>			
		</div>
		
		<div id="div_cpf" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">							
			<b>CPF:</b>
			<input id="cpf" type='text' size='11' name="cpf" value='' onkeypress='return SomenteNumero(event, this, 11)' maxlength="11">
		</div>
		
		<div id="div_numero_chamador" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">							
			<b>Número Chamador:</b>
			<input id="numero_chamador" type='text' size='13' name="numero_chamador" value='' onkeypress='return SomenteNumero(event, this, 13)' maxlength="13">
		</div>
		
		<div id="div_inicio_cartao" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">							
			<b>Início Cartão (4 Dígitos):</b>
			<input id="inicio_cartao" type='text' size='4' name="inicio_cartao" value='' onkeypress='return SomenteNumero(event, this, 4)' maxlength="4">
		</div>
		
		<div id="div_final_cartao" class="div_dia w3-tiny w3-margin-top w3-margin-left w3-left">							
			<b>Final Cartão (4 Dígitos):</b>
			<input id="final_cartao" type='text' size='4' name="final_cartao" value='' onkeypress='return SomenteNumero(event, this, 4)' maxlength="4">
		</div>
		
		<div class="div_dia w3-tiny w3-margin-top w3-margin-left w3-margin-bottom w3-left">
			<button id="btn_pesquisar" class="w3-btn w3-deep-orange w3-round w3-tiny" type="submit" name="btn_pesquisar" value="Gerar">Consultar</button>	
		</div>
	</form>
</div>		
<!-- FORMULÁRIO - FIM -->

<script>
	$("#dados_criticos").hide();
	$("#div_matricula").hide();
	$("#div_identificador").hide();
	$("#div_acao").hide();
	$("#div_telefone").hide();
	$("#div_ins_identificador").hide();
	
	$("#div_cpf").hide();
	$("#div_numero_chamador").hide();
	$("#div_inicio_cartao").hide();
	$("#div_final_cartao").hide();
</script>

<hr class="w3-margin-left w3-margin-right w3-margin-top">

</body>
</html>