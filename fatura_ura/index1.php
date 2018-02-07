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
<div class='w3-container w3-padding w3-margin-16 w3-tiny w3-center w3-indigo-dark w3-wide w3-card-4 '><b>FATURAMENTO URA - CONTRATO INDRA MARACANAÚ</b></div>

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
			<input size='10' id="qual_ano" type='text' name="qual_ano" value='' onkeypress='return SomenteNumero(event, this, 4)'>
		</div>

		<div class="div_dia w3-margin-8">
			Valor do Atendimento Eletrônico(R$):
			<input size='11' id="valor_atendimento_ura" type='text' name="valor_atendimento_ura" value='0.3536200507' onkeypress='return SomenteValor(event, this, 12)'>
		</div>
		
		<div class="div_dia w3-margin-8" id="dns">
			<button id="btn_gerar" class="w3-tinny w3-btn w3-indigo-dark w3-round-small" type="submit" name="btn_gerar" value="Gerar">Gerar Relatório</button>
		</div>
		
	</div>
</div>	
<!-- DIV 1 - FIM -->
		
<!-- DIV 2 - INÍCIO -->
<div class="w3-tiny w3-container w3-section w3-white w3-padding-0 w3-border-indigo-dark w3-border w3-card-4" id="div_eventos_ura">		
		<div class="w3-left w3-padding">
		<p id="txt_glosa" class="w3-margin-8"><b>Eventos Faturáveis URA:</b></p>
<?php	
include "conecta.php";

$eventos_faturaveis = array('020','031','037','039','042','045','047','050','051','061','062','076','078','136','137','138','139','140','149','790');

$query = $pdo->prepare("select * from tb_eventos_novaura");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$cod_evento = $row['cod_evento'];
	$desc_evento = utf8_encode($row['desc_evento']);
	
	if(in_array($cod_evento,$eventos_faturaveis)) echo "<input class='w3-margin-8 ckb' type='checkbox' id='evento_ura_$cod_evento' name='evento_ura_$cod_evento' value = '' checked>$cod_evento ($desc_evento) &nbsp &nbsp<br>";
	else echo "<input class='w3-margin-8 ckb' type='checkbox' id='evento_ura_$cod_evento' name='evento_ura_$cod_evento' value = ''>$cod_evento ($desc_evento) &nbsp &nbsp<br>";
}
include "desconecta.php";
?>
		</div>		
</div>
	
<!-- DIV 2 - FIM -->		
		
		
</form>
<!-- FORMULÁRIO FIM -->

<div class="w3-center w3-tiny w3-margin-right w3-margin-4">Caixa Econômica Federal - CERAT Fortaleza / ceratfo@caixa.gov.br</div>

</div>
</body>
</html>