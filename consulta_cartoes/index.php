<!DOCTYPE html>
<html>

<head>
<title>CONSULTA CARTÕES DE CRÉDITO CAIXA - Contrato INDRA Maracanaú</title>
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/glyphicon.css">

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<link rel="stylesheet" href="css/bootstrap-datepicker3.standalone.css">
<script src='js/bootstrap-datepicker.js'></script>
<script src='locales/bootstrap-datepicker.pt-BR.min.js'></script>
<script src="js/mascara_data.js"></script>


<script>
function loadDoc() {
	document.getElementById("demo").innerHTML = 'A consulta pode demorar alguns minutos, aguarde...<br><b>Obs:</b> Caso ocorra algum erro, esse texto irá sumir.';
  var xhttp = new XMLHttpRequest();
  var data_inicial = document.getElementById("data_inicial").value;
  var data_final = document.getElementById("data_final").value;
  var tipo_do_cartao = document.getElementById("tipo_do_cartao").value;
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("demo").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "imprime_tabela.php?data_inicial="+data_inicial+"&data_final="+data_final+"&tipo_do_cartao="+tipo_do_cartao, true);
  xhttp.send();
}
</script>

</head>

<body>

<!-- Topo - Início -->
<table id='topo'>
	<tr>	
		<td style='text-align: left;'>
			<img src="logo_caixa.png" alt="Caixa Econômica Federal" style="width: 100px;">
		</td>
		
		<td>
			<b style='letter-spacing: 2px;'>CONSULTA CARTÕES DE CRÉDITO CAIXA</b>
		</td>
		
		<td style='text-align: right;'>	
			Contrato INDRA Maracanaú
			<br>
			Atendimento em Telesserviços Fortaleza/CE
			<br>
			ceratfo@caixa.gov.br
			</div>
		</td>		
	</tr>
</table>
<!-- Topo - Fim -->

<!-- Bloco de Opções - Início -->
<div id='bloco_opcoes' style=''>

	<div class='opcao'>
		Tipo do Cartão: <input id='tipo_do_cartao' size='11' placeholder='Ex: Tigre'>
	</div>

	<div id='div_datas'>
		<div class='opcao' style='cursor: pointer'>
			Data Inicial:
			<input id='data_inicial' size='11' placeholder='dd-mm-aaaa' onkeyup="mascara_data(this, '-')">
		</div>
		
		<div class='opcao' style='cursor: pointer'>
			Data Final:
			<input id='data_final' size='11' placeholder='dd-mm-aaaa' onkeyup="mascara_data(this, '-')">
		</div>
	</div>
	
	<div class='opcao'>
		<input class='botao_clique' type='button' value='Consultar' onclick='loadDoc();'>
	</div>

</div>
​<!-- Bloco de Opções - Fim -->

<div id='demo' style='text-align: center;'></div>

<!-- DATAPICKER - Início -->
<script>
    $('#data_inicial').datepicker({
        todayBtn: "linked",
        language: "pt-BR",
		format: "dd-mm-yyyy",
		autoclose: false,
		orientation: "bottom auto",
		todayHighlight: true,
		multidate: false
    });
	
	$('#data_final').datepicker({
        todayBtn: "linked",
        language: "pt-BR",
		format: "dd-mm-yyyy",
		autoclose: false,
		orientation: "bottom auto",
		todayHighlight: true,
		multidate: false
    });
</script>
<!-- DATAPICKER - Fim -->


</body>

</html>

​

