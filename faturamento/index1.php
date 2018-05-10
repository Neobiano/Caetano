<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<style>
.div_dia {
    float: left;
}
</style>

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
    	if((tecla>47 && tecla<58 && numero.length<limite)||(tecla==46 && numero.length<limite)) 
    	  		return true;
    	else{
    		if (tecla==8 || tecla==0)
    			return true;
    		else  return false;
    	}
	}	
</script>

<script>
$(document).ready(function(){
	$("#sel_eventos_ura").change(function(){
		
		if ($("#sel_eventos_ura").val() == "01"){
			$("#div_eventos_ura").hide();
		}

		if ($("#sel_eventos_ura").val() == "02"){
			$("#div_eventos_ura").show();
		}

		if ($("#sel_eventos_ura").val() == "03"){
			$("#div_eventos_ura").show();
		}
		
	});    
});
</script>


<script>
$(document).ready(function(){
	$("#dns_automatico").change(function(){
		
		if ($("#dns_automatico").val() == "nao"){
			$("#dns").show();
		}
		else{
			alert ("O cálculo do DNS é finalizado em aproximadamente 1 hora.");
			$("#dns").hide();
		}
	});    
});
</script>


</head>

<body class="w3-indigo-dark">

<div class="w3-container w3-center">
	<img src="bg_caixa.png" style="width:7%">
</div>		

<div class="w3-container w3-margin w3-white w3-card-8 w3-azulzinho w3-border w3-round">

<!-- TÍTULO -->
<div class='w3-container w3-padding w3-margin-16 w3-tiny w3-center w3-indigo-dark w3-wide w3-card-4 '><b>FATURAMENTO - CONTRATO INDRA MARACANAÚ</b></div>

<!-- FORMULÁRIO - INÍCIO -->
<form action="imprime_tabela.php" method="post" class="w3-container w3-tiny" target="_blank">

<!-- DIV 1 - INÍCIO -->
<div class="w3-tiny w3-container w3-white w3-padding-0 w3-border-indigo-dark  w3-border  w3-card-4">	
	
	<div class="w3-left w3-padding">
		
		<p id="txt_ACP" class="w3-margin-8"><b>Parâmetros:</b></p>
		
		<div class="div_dia w3-margin-8">
			Mês:
			<select id= "qual_mes" name="qual_mes">
				<option value="00"></option>
		    	<option value="01">Janeiro</option>	
		    	<option value="02">Fevereiro</option>
		    	<option value="03">Março</option>
		    	<option value="04">Abril</option>
		    	<option value="05">Maio</option>
		    	<option value="06">Junho</option>
		    	<option value="07">Julho</option>
		    	<option value="08">Agosto</option>
		    	<option value="09">Setembro</option>
		    	<option value="10">Outubro</option>
		    	<option value="11">Novembro</option>
		    	<option value="12">Dezembro</option>	    	
			</select>
		</div>

		<div class="div_dia w3-margin-8">
		Ano:
		<input size='10' id="qual_ano" type='text' name="qual_ano" value='2018' onkeypress='return SomenteNumero(event, this, 4)'>
		</div>
			
		<div class="div_dia w3-margin-8">
		Limite Pagamento Shortcall (%):
		<input size='1' id="shortcall_porcentagem" type='text' name="shortcall_porcentagem" value='5' onkeypress='return SomenteNumero(event, this, 2)'>
		</div>
		
		<div class="div_dia w3-margin-8">
		Tempo Shortcall (segundos):
		<input size='1' id="shortcall_tempo" type='text' name="shortcall_tempo" value='20' onkeypress='return SomenteNumero(event, this, 2)'>
		</div>
		<fieldset>
    		<fieldset>
    			<legend>DMM</legend>
        		<div class="div_dia w3-margin-8">
        		NSR Normal(%):
        		<input size='1' id="dmm_nsr" type='text' name="dmm_nsr" value='90' onkeypress='return SomenteNumero(event, this, 2)'>		
        		</div>
        		
        		<div class="div_dia w3-margin-8">
        		NSR Premium(%):
        		<input size='1' id="dmm_nsr_premium" type='text' name="dmm_nsr_premium" value='90' onkeypress='return SomenteNumero(event, this, 2)'>
        		</div>
    		</fieldset>
    		
    		<fieldset>
    			<legend>Dia Convencional</legend>
        		<div class="div_dia w3-margin-8">
        		NSR Normal(%):
        		<input size='1' id="nsr" type='text' name="nsr" value='90' onkeypress='return SomenteNumero(event, this, 2)'>		
        		</div>
        		
        		<div class="div_dia w3-margin-8">
        		NSR Premium(%):
        		<input size='1' id="nsr_premium" type='text' name="nsr_premium" value='95' onkeypress='return SomenteNumero(event, this, 2)'>
        		</div>
    		</fieldset>
		</fieldset>
		<div class="div_dia w3-margin-8">
		Tempo de Espera Padrão(segundos):
		<input size='1' id="ns_normal" type='text' name="ns_normal" value='45' onkeypress='return SomenteNumero(event, this, 2)'>
		</div>
		
		<div class="div_dia w3-margin-8">
		Tempo de Espera Diferenciado(segundos):
		<input size='1' id="ns_diferenciado" type='text' name="ns_diferenciado" value='90' onkeypress='return SomenteNumero(event, this, 2)'>
		</div>
		
		<div class="div_dia w3-margin-8">
		Valor do Atendimento Humano(R$):
		<input size='11' id="valor_atendimento" type='text' name="valor_atendimento" value='1.7469685233' onkeypress='return SomenteValor(event, this, 12)'>
		</div>
		
		<div class="div_dia w3-margin-8">
		Valor do Atendimento Eletrônico(R$):
		<input size='11' id="valor_atendimento_ura" type='text' name="valor_atendimento_ura" value='0.3536200507' onkeypress='return SomenteValor(event, this, 12)'>
		</div>
		
	</div>
</div>	
<!-- DIV 1 - FIM -->

<!-- DIV 2 - INÍCIO -->		
<div class="w3-tiny w3-container w3-section w3-white w3-padding-0 w3-border-indigo-dark w3-border w3-card-4">
		
		<div class="w3-left w3-padding">
	
	<p id="txt_ACP" class="w3-margin-8"><b>Dias com Tempo de Espera Diferenciado</b></p>
			
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_1" name="chk_1" value = "1">01 &nbsp &nbsp </div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_2" name="chk_2" value = "2"  checked>02 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_3" name="chk_3" value = "3">03 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_4" name="chk_4" value = "4">04 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_5" name="chk_5" value = "5">05 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_6" name="chk_6" value = "6" >06 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_7" name="chk_7" value = "7">07 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_8" name="chk_8" value = "8">08 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_9" name="chk_9" value = "9" checked>09 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_10" name="chk_10" value = "10">10 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_11" name="chk_11" value = "11" >11 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_12" name="chk_12" value = "12" >12 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_13" name="chk_13" value = "13" >13 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_14" name="chk_14" value = "14" checked>14 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_15" name="chk_15" value = "15" checked>15 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_16" name="chk_16" value = "16" checked>16 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_17" name="chk_17" value = "17">17 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_18" name="chk_18" value = "18" >18 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_19" name="chk_19" value = "19">19 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_20" name="chk_20" value = "20" >20 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_21" name="chk_21" value = "21">21 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_22" name="chk_22" value = "22" checked>22 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_23" name="chk_23" value = "23" checked>23 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_24" name="chk_24" value = "24">24 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_25" name="chk_25" value = "25" >25 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_26" name="chk_26" value = "26">26 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_27" name="chk_27" value = "27" >27 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_28" name="chk_28" value = "28">28 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_29" name="chk_29" value = "29">29 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_30" name="chk_30" value = "30" checked>30 &nbsp &nbsp</div>
	<div class="div_dia"> <input class="w3-margin-8 ckb" type="checkbox" id="chk_31" name="chk_31" value = "31">31 &nbsp &nbsp</div>
	
	</div>
</div>	
<!-- DIV 2 - FIM -->

<!-- DIV 3 - INÍCIO
<div class="w3-tiny w3-container w3-section w3-white w3-padding-0 w3-border-indigo-dark w3-border w3-card-4">
		
		<div class="w3-left w3-padding">
		
		<p id="txt_ACP" class="w3-margin-8"><b>Adicional de Complexidade e Prioridade(ACP):</b></p>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_acp_retencao" class="">Retenção(%):</b>
		<input size='1' id="acp_retencao" type='text' name="acp_retencao" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_acp_triagem" class="">Triagem Preventiva(%):</b>
		<input size='1' id="acp_triagem" type='text' name="acp_triagem" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_acp_parcelamento" class="">Parcelamento(%):</b>
		<input size='1' id="acp_parcelamento" type='text' name="acp_parcelamento" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_acp_contestacao" class="">Contestação(%):</b>
		<input size='1' id="acp_contestacao" type='text' name="acp_contestacao" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_acp_pontos" class="">Programa de Pontos(%):</b>
		<input size='1' id="acp_pontos" type='text' name="acp_pontos" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_acp_geral_normal" class="">Geral Normal(%):</b>
		<input size='1' id="acp_geral_normal" type='text' name="acp_geral_normal" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_acp_geral_premium" class="">Geral Premium(%):</b>
		<input size='1' id="acp_geral_premium" type='text' name="acp_geral_premium" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">				
		<b id="txt_acp_pj" class="">Pessoa Jurídica(%):</b>
		<input size='1' id="acp_pj" type='text' name="acp_pj" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_caixa_empregado" class="">Aviso de Viagem(%):</b>
		<input size='1' id="acp_caixa_empregado" type='text' name="acp_caixa_empregado" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_deficiente_auditivo" class="">Aviso de Viagem(%):</b>
		<input size='1' id="acp_deficiente_auditivo" type='text' name="acp_deficiente_auditivo" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>
		
		<div class="div_dia w3-margin-8">
		<b id="txt_mala_direta" class="">Aviso de Viagem(%):</b>
		<input size='1' id="acp_mala_direta" type='text' name="acp_mala_direta" value='00' onkeypress='return SomenteNumero(event, this, 2)' >
		</div>

		</div>
</div>	
DIV 3 - FIM -->

<!-- DIV 4 - INÍCIO -->		
<div class="w3-tiny w3-container w3-section w3-white w3-padding-0 w3-border-indigo-dark w3-border w3-card-4">		
		<div class="w3-left w3-padding">
		
		<p id="txt_ACP" class="w3-margin-8"><b>ANSM dos Dias com Revisão de Nível:</b></p>
		<div class="div_dia w3-margin-8">01:<input size='12' id="ansm1" type='text' name="ansm1" value='0.000' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">02:<input size='12' id="ansm2" type='text' name="ansm2" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">03:<input size='12' id="ansm3" type='text' name="ansm3" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">04:<input size='12' id="ansm4" type='text' name="ansm4" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">05:<input size='12' id="ansm5" type='text' name="ansm5" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">06:<input size='12' id="ansm6" type='text' name="ansm6" value='0.9982' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">07:<input size='12' id="ansm7" type='text' name="ansm7" value='0.995215' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">08:<input size='12' id="ansm8" type='text' name="ansm8" value='0.974743' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">09:<input size='12' id="ansm9" type='text' name="ansm9" value='1.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">10:<input size='12' id="ansm10" type='text' name="ansm10" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">11:<input size='12' id="ansm11" type='text' name="ansm11" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">12:<input size='12' id="ansm12" type='text' name="ansm12" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">13:<input size='12' id="ansm13" type='text' name="ansm13" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">14:<input size='12' id="ansm14" type='text' name="ansm14" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">15:<input size='12' id="ansm15" type='text' name="ansm15" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">16:<input size='12' id="ansm16" type='text' name="ansm16" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">17:<input size='12' id="ansm17" type='text' name="ansm17" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">18:<input size='12' id="ansm18" type='text' name="ansm18" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">19:<input size='12' id="ansm19" type='text' name="ansm19" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">20:<input size='12' id="ansm20" type='text' name="ansm20" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">21:<input size='12' id="ansm21" type='text' name="ansm21" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">22:<input size='12' id="ansm22" type='text' name="ansm22" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">23:<input size='12' id="ansm23" type='text' name="ansm23" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">24:<input size='12' id="ansm24" type='text' name="ansm24" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">25:<input size='12' id="ansm25" type='text' name="ansm25" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">26:<input size='12' id="ansm26" type='text' name="ansm26" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">27:<input size='12' id="ansm27" type='text' name="ansm27" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">28:<input size='12' id="ansm28" type='text' name="ansm28" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">29:<input size='12' id="ansm29" type='text' name="ansm29" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">30:<input size='12' id="ansm30" type='text' name="ansm30" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		<div class="div_dia w3-margin-8">31:<input size='12' id="ansm31" type='text' name="ansm31" value='0.00' onkeypress='return SomenteValor(event, this, 12)'></div>
		</div>
</div>	
<!-- DIV 4 - FIM -->

<!-- DIV 5 - INÍCIO -->		
<div class="w3-tiny w3-container w3-section w3-white w3-padding-0 w3-border-indigo-dark w3-border w3-card-4">		
		<div class="w3-left w3-padding">
		
			<p id="txt_glosa" class="w3-margin-8"><b>Glosas (Quantidade) / IQF / Acertos:</b></p>
			<div class="div_dia w3-margin-8">Quantidade de Atendimentos Prestados com Falta de Cortesia e/ou Fora dos Padrões:<input size='2' id="glosa1" type='text' name="glosa1" value='16' onkeypress='return SomenteValor(event, this, 3)'></div>		
			<div class="div_dia w3-margin-8">IQF(%):<input size='12' id="iqf" type='text' name="iqf" value='89.0580' onkeypress='return SomenteValor(event, this, 13)'></div>
			
	
			<div class="div_dia w3-margin-8">
				Calcular DNS Automaticamente?
				<select id="dns_automatico" name="dns_automatico">
			    	<option value="nao">Não</option>
			    	<option value="sim">Sim</option>	    	
				</select>
			</div>
				
			<div class="div_dia w3-margin-8" id="dns">DNS:<input size='12' id="qual_dns" type='text' name="qual_dns" value='1.0' onkeypress='return SomenteValor(event, this, 12)'></div>
			<div class="div_dia w3-margin-8" id="dns">Acertos Acréscimos:<input size='12' id="acertos_acre" type='text' name="acertos_acre" value='0.00' onkeypress='return SomenteValor(event, this, 100)'></div>
			<div class="div_dia w3-margin-8" id="dns">Acertos Decréscimos:<input size='12' id="acertos_decre" type='text' name="acertos_decre" value='0.00' onkeypress='return SomenteValor(event, this, 100)'></div>
		</div>
</div>	
<!-- DIV 5 - FIM -->

<!-- DIV 6 - INÍCIO -->		
<div class="w3-tiny w3-container w3-section w3-white w3-padding-0 w3-border-indigo-dark w3-border w3-card-4">		
		<div class="w3-left w3-padding">

		<p id="txt_glosa" class="w3-margin-8"><b>Faturamento URA:</b></p>

		<div class="div_dia w3-margin-8">
			Tipo de Faturamento URA:
			<select id="sel_eventos_ura" name="sel_eventos_ura">
		    	<option value="01">Faturar CALLID's Distintos</option>
		    	<option value="02">Faturar CALLID's com serviço faturável</option>
		    	<option value="03">Faturar por serviço</option>	    	
			</select>
		</div>

		<div class="div_dia w3-margin-8" id="dns">
		<button id="btn_gerar" class="w3-tinny w3-btn w3-indigo-dark w3-round-small" type="submit" name="btn_gerar" value="Gerar">Gerar Relatório</button>
		</div>		
		</div>
</div>	
<!-- DIV 6 - FIM -->
		
		
<!-- DIV 7 - INÍCIO -->
<div class="w3-tiny w3-container w3-section w3-white w3-padding-0 w3-border-indigo-dark w3-border w3-card-4" id="div_eventos_ura">		
		<div class="w3-left w3-padding">
		<p id="txt_glosa" class="w3-margin-8"><b>Eventos Faturáveis URA:</b></p>
<?php	
include "conecta.php";

$eventos_faturaveis = array('020','031','037','039','042','045','047','050','051','061','062','076','078','136','137','138','139','140');
$query = $pdo->prepare("select * from tb_eventos_novaura");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$cod_evento = $row['cod_evento'];
	$desc_evento = $row['desc_evento'];
	
	if(in_array($cod_evento,$eventos_faturaveis)) 
	    echo "<input class='w3-margin-8 ckb' type='checkbox' id='evento_ura_$cod_evento' name='evento_ura_$cod_evento' value = '' checked>$cod_evento ($desc_evento) &nbsp &nbsp<br>";
	else 
	    echo "<input class='w3-margin-8 ckb' type='checkbox' id='evento_ura_$cod_evento' name='evento_ura_$cod_evento' value = ''>$cod_evento ($desc_evento) &nbsp &nbsp<br>";
	
	    //echo "<input class='w3-margin-8 ckb' type='checkbox' id='evento_ura_$cod_evento' name='evento_ura_$cod_evento' value = ''>$cod_evento ($desc_evento) &nbsp &nbsp<br>";
}
include "desconecta.php";
?>
		</div>		
</div>

<script>
	$("#div_eventos_ura").hide();
</script>
	
<!-- DIV 7 - FIM -->		
		
		
</form>
<!-- FORMULÁRIO FIM -->

<div class="w3-center w3-tiny w3-margin-right w3-margin-4">Caixa Econômica Federal - CERAT Fortaleza / ceratfo@caixa.gov.br</div>

</div>
</body>
</html>