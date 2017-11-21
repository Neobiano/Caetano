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

<script language='JavaScript'>
	function apagar(){
		var data = $("#qual_data").val();
		if(confirm("Deseja Apagar o Arquivo?")){
			$.ajax({
			  type: "POST",
			  url: "apagar.php",
			  data: { meuParametro1: data},
			});
		}
	}
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
<div class='w3-container w3-padding w3-margin-16 w3-tiny w3-center w3-indigo-dark w3-wide w3-card-4'><b>CONTIGENCIAMENTO - CONTRATO INDRA MARACANAÚ</b></div>

<!-- DIV 1 - INÍCIO -->
<div class="w3-tiny w3-container w3-white w3-padding w3-border-indigo-dark w3-border w3-card-4 w3-margin">	

		<p id="txt_ACP" class=""><b>Gerar Relatórios / Enviar Arquivos:</b></p>
		
		<form action="gerar_relatorio.php" method="post" target="_blank">
		<div class="div_dia w3-margin-top w3-margin-bottom">
			Mês/Ano:
			<select id= "qual_data" name="qual_data">
			<?php
				$pasta = '../contingenciamento/arquivos/';
				$arquivos = scandir($pasta);
				$tamanho_vetor = count($arquivos);				
				asort ($arquivos);
				
				for($x=2;$x<$tamanho_vetor;$x++){
					$ano = substr($arquivos[$x], 0, 4);
					$mes = substr($arquivos[$x], 4, 2);
					
					$value_data = "$ano"."$mes";
					
					switch ($mes) { // TRADUZ MÊS
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
					$mes_ano = "$mes"."/$ano";
					echo "<option value='$value_data'>$mes_ano</option>";
				}				
			?>
			</select>
						
		</div>
		
		<div class="div_dia w3-margin-top w3-margin-bottom" style="margin-left: 8px;">			
				<img src="delete.png" style="cursor: pointer;padding-bottom:1px;" onclick="apagar();">
		</div>
		
		<div class="div_dia w3-margin-top w3-margin-bottom" style="margin-left: 8px;">			
				<img src="refresh.png" style="cursor: pointer;" onclick="location.reload();">
		</div>
		
		<div class="div_dia w3-margin-left w3-margin-top w3-margin-bottom">			
				<button id="btn_gerar" class="w3-tinny w3-btn w3-indigo-dark w3-round-small" name="btn_gerar">Gerar Relatórios</button>
		</div>
		
		</form>
		
		<div class="div_dia w3-margin-left w3-margin-top w3-margin-bottom">			
				<button id="" class="w3-tinny w3-btn w3-indigo-dark w3-round-small" onclick="document.getElementById('id01').style.display='block'">Enviar Arquivo</button>
		</div>		
		
	</div>
	
	<div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-center w3-round">
      <header class="w3-container w3-indigo-dark w3-padding"> 
        <span onclick="document.getElementById('id01').style.display='none'" 
        class="w3-button w3-display-topright w3-large" style="margin-top:4px;margin-right:10px;cursor:pointer"><b> &times; </b></span>
        <b class="w3-tiny w3-wide">ENVIAR ARQUIVO</b>
      </header>
	  
      <div class="w3-container w3-white w3-tiny" style="padding:20px;">
        <form enctype="multipart/form-data" action="upload.php" method="POST" target="_blank">
			<!-- MAX_FILE_SIZE deve preceder o campo input -->
			<input type="hidden" name="MAX_FILE_SIZE" value="990000000"/>
			<!-- O Nome do elemento input determina o nome da array $_FILES -->
			<b>Arquivo:</b> <input class="w3-small" name="userfile" type="file"/>
			
			<b class="w3-margin-left">Mês:</b>
			<select id= "mes_arquivo" name="mes_arquivo">
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
			
			<b class="w3-margin-left">Ano:</b>
			<input size='10' id="ano_arquivo" type='text' name="ano_arquivo" value='' onkeypress='return SomenteNumero(event, this, 4)'>
			
			<input class="w3-btn w3-indigo-dark w3-round w3-tiny w3-margin-left" type="submit" value="Enviar" onclick="document.getElementById('id01').style.display='none'" style="margin-bottom:1px;"/>
			
			
		</form>
      </div>
    </div>
  </div>
<!-- DIV 1 - FIM -->

<div class="w3-container w3-center w3-tiny w3-margin-8">Caixa Econômica Federal - CERAT Fortaleza / ceratfo@caixa.gov.br</div>

</div>
</body>
</html>