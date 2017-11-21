window.onscroll = function() {altera_menu();};

function altera_menu(){
	var distancia_do_topo = $(document).scrollTop();
	var widthTela = window.innerWidth;
	
	if(distancia_do_topo >= 50 && widthTela > 993){
		document.getElementById('div_img').style.display = 'none';
		
		document.getElementById('titulo').innerHTML = 'CAIXA - SISTEMA DE GERENCIAMENTO E IDENTIFICAÇÃO DE ERROS NA URA - CONTRATO INDRA MARACANAÚ';
		document.getElementById('titulo').style.background = '#f2f5ff';
		document.getElementById('titulo').style.color = '#000';
	}
	
	else{
		document.getElementById('div_img').style.display = 'block';
		
		document.getElementById('titulo').innerHTML = 'SISTEMA DE GERENCIAMENTO E IDENTIFICAÇÃO DE ERROS NA URA - CONTRATO INDRA MARACANAÚ';
		document.getElementById('titulo').style.background = '#26a';
		document.getElementById('titulo').style.color = '#fff';
	}
}