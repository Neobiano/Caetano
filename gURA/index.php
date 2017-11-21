<!DOCTYPE html>
<?php
	$hostname = '10.195.192.168';
	$dbname = "BD_ATF";
	$username = "adminCaixa";
	$pw = "u7&iok9(!2jdu$#jdhunbb";

	$pdo = new PDO ("sqlsrv:server=$hostname;database=$dbname",$username,$pw);
?>
<html>

<head>
<title>Mini-Ferramentas</title>
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/glyphicon.css">

<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,400i,700,700i" rel="stylesheet">

<link rel="stylesheet" href="css/bootstrap-datepicker3.standalone.css">
<script src='js/bootstrap-datepicker.js'></script>
<script src='locales/bootstrap-datepicker.pt-BR.min.js'></script>
<script src="js/mascara_data.js"></script>

<script>
codEvento = [];
<?php
	$query = $pdo->prepare("select * from tb_eventos_novaura");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_evento = utf8_encode($row['cod_evento']);
		$desc_evento = utf8_encode($row['desc_evento']);
		echo "codEvento[$cod_evento] = '$desc_evento';";				
	}
?>

function escondeTudo(){	
	$("#div_datas").hide();
	$("#div_modeloErrosWebservice").hide();
	$("#div_btnConsultar").hide();
	$("#div_btnTraduzir").hide();
	$("#div_sequenciaEventos").hide();

	$("#div_recolhe").hide();
	document.getElementById("bloco_opcoes").style.padding = '0px';
	
	$("#modalLoading").hide();
}

function selecionaConsulta(){
	escondeTudo();
	var qualConsulta = $("#qualConsulta").val();
	if(qualConsulta != '00'){
		$("#div_recolhe").show();
		document.getElementById("bloco_opcoes").style.padding = '32px';
	}
	
	switch(qualConsulta){				
				case '01':
					$("#div_datas").show();
					$("#div_modeloErrosWebservice").show();
					$("#div_btnConsultar").show();
					break;
				
				case '02':
					$("#div_datas").show();
					$("#div_btnConsultar").show();
					break;
					
				case '03':
					$("#div_datas").show();
					$("#div_sequenciaEventos").show();
					$("#div_btnConsultar").show();
					break;
					
				case '04':
					$("#div_sequenciaEventos").show();
					$("#div_btnTraduzir").show();
					break;
			}
}

function consulta(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("divCarregaConsulta").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "carregando.txt", true);
	xhttp.send();
	
	var qualConsulta = $("#qualConsulta").val();
	var nome_arquivo = "consulta_" + qualConsulta + ".php";
	
	var data_inicial = $("#data_inicial").val();
	var data_final = $("#data_final").val();
	var modeloErrosWebservice = $("#modeloErrosWebservice").val();
	var sequenciaEventos = $("#sequenciaEventos").val();

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("divCarregaConsulta").innerHTML = this.responseText;
		}
	}
	if(qualConsulta == '01') xhttp.open("GET", nome_arquivo + "?data_inicial=" + data_inicial + "&data_final=" + data_final + "&modeloErrosWebservice=" + modeloErrosWebservice, true);
	if(qualConsulta == '02') xhttp.open("GET", nome_arquivo + "?data_inicial=" + data_inicial + "&data_final=" + data_final, true);
	if(qualConsulta == '03') xhttp.open("GET", nome_arquivo + "?data_inicial=" + data_inicial + "&data_final=" + data_final + "&sequenciaEventos=" + sequenciaEventos, true);
	xhttp.send();
}

function traduzEventos(id){	
	if(id=='btnTraduz') var conteudo = $("#sequenciaEventos").val().split(";");
	else var conteudo = id.innerHTML.split(";");
	var qtdEventos = conteudo.length;
	var txtTraduzido = '';
	
	for(var i=0;i<qtdEventos;i++){
		var cod = conteudo[i];
		codFormatado = Number(cod);
		descEvento = codEvento[codFormatado];
		
		if(i == 0) txtTraduzido = txtTraduzido + '<b>' + cod + ' </b>(' + descEvento + ')';
		else txtTraduzido = txtTraduzido + '; <b>' + cod + '</b> (' + descEvento + ')';
	}	
	
	$("#modal_salvar_acp").fadeIn(200);
	document.getElementById("txtTraducao").innerHTML = txtTraduzido;	
}
</script>


</head>

<body>

<div id='menu_total'>
<!-- Topo - Início -->
<table id='topo'>
	<tr>	
		<td style='text-align: left;'>
			<img src="logo_caixa.png" alt="Caixa Econômica Federal" style="width: 100px;">
		</td>
		
		<td>
			Consulta:
			<select id='qualConsulta'onchange='selecionaConsulta();'>
				<option value="00"></option>
				<option value="01">Erros WebService</option>
				<option value="02">Serviços x Quantidade</option>
				<option value="03">Procura Sequência de Eventos</option>
				<option value="04">Tradutor de Eventos</option>
			</select>
		</td>
		
		<td style='text-align: right;'>
			<div style='display: inline-block; text-align: center;'>
				Gerenciador URA<br>
				Contrato INDRA Maracanaú<br>
				ceratfo@caixa.gov.br
			</div>
		</td>		
	</tr>
</table>
<!-- Topo - Fim -->

<!-- Bloco de Opções - Início -->
<div id='bloco_opcoes' style='margin-bottom: -20px;'>

	<div id='div_datas'>
		<div class='opcao' style='cursor: pointer'>
			Data Inicial:
			<input id='data_inicial' name='data_inicial' size='11' placeholder='dd-mm-aaaa' onkeyup="mascara_data(this, '-')">
		</div>
		
		<div class='opcao' style='cursor: pointer'>
			Data Final:
			<input id='data_final' name='data_final' size='11' placeholder='dd-mm-aaaa' onkeyup="mascara_data(this, '-')">
		</div>
	</div>
	
	<div class='opcao' id='div_sequenciaEventos'>
		Sequência de Eventos:
		<input id='sequenciaEventos' name='sequenciaEventos' size='50' placeholder='Ex: 001;002;003;004;005'></input>
	</div>
	
	<div class='opcao' id='div_modeloErrosWebservice'>
		Modelo de Consulta:
		<select id='modeloErrosWebservice' name='modeloErrosWebservice'>
			<option value='01'>Agrupar por sequência de eventos</option>
			<option value='02'>Listar evento imediatamente anterior ao erro</option>
		</select>
	</div>
	
	<div class='opcao' id='div_btnConsultar'>
		<input class='botao_clique' type='button' value='Consultar' onclick='consulta();'>
	</div>
	
	<div class='opcao' id='div_btnTraduzir'>
		<input class='botao_clique' type='button' value='Traduzir' onclick='traduzEventos("btnTraduz");'>
	</div>
</div>
​<!-- Bloco de Opções - Fim -->
</div>

<!-- Div Recolher/Expandir - Início -->
<div id='div_recolhe' onclick='$("#menu_total").slideToggle(300); $("#gly_menu_up").toggleClass("glyphicon-menu-hamburger"); $("#gly_menu_up").toggleClass("glyphicon-menu-up"); $("#div_recolhe").toggleClass("recolhido");'>
	<span id='gly_menu_up' class="glyphicon glyphicon-menu-up"></span> <span id='txt_titulo'>Gerenciador URA - Contrato INDRA Maracanaú - ceratfo@caixa.gov.br</span>
</div>
<!-- Div Recolher/Expandir - Fim -->


<div id='divCarregaConsulta'>
</div>


<div id='modal_salvar_acp' style='position: fixed; top: 0; color: white; bottom: 0; left: 0; right: 0; margin: auto; width: 100%; height: 100%; background: rgb(0,0,0); background: rgba(0,0,0,0.9); display: none;'>
	<div id='modal_salvar_acp_conteudo' style='position: fixed; top: 0; bottom: 0; left: 0; right: 0; margin: auto; background: red; width: 100%; height: 0px; text-align: center; display: table-cell; vertical-align: middle;'>
		<div style='border: solid 2px #fff; border-radius: 8px; display: inline-block; padding: 0px; background: #014a7f; transform: translate(-0%, -50%); margin-left: 5%; margin-right: 5%'>
			<div class='x_sair' onclick='$("#modal_salvar_acp").fadeOut(200);'>x</div>
			<div style='padding: 32px 64px 0px 32px; font-size: 18px;'><b>Tradutor de Eventos</b></div>
			<br>
			<div id='txtTraducao' style='padding: 0px 64px 32px 64px;'></div>
		</div>
	</div>
</div>


<!-- DATAPICKER - Início -->
<script>
    $('#data_inicial').datepicker({
        todayBtn: "linked",
        language: "pt-BR",
		format: "dd-mm-yyyy",
		autoclose: true,
		orientation: "bottom",
		todayHighlight: true,
		multidate: false
    });
	
	$('#data_final').datepicker({
        todayBtn: "linked",
        language: "pt-BR",
		format: "dd-mm-yyyy",
		autoclose: true,
		orientation: "bottom",
		todayHighlight: true,
		multidate: false
    });
</script>
<!-- DATAPICKER - Fim -->

<script>
	$("#qualConsulta").val('00');
	escondeTudo();
</script>

</body>

</html>