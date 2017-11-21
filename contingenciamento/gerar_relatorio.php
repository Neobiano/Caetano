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

<script language='JavaScript'>
	function SomenteNumero(e){	
    	var tecla=(window.event)?event.keyCode:e.which;   
    	if((tecla>47 && tecla<58)||(tecla==44)) 
    	  		return true;
    	else{
    		if (tecla==8 || tecla==0)
    			return true;
    		else  return false;
    	}
	}
</script>

<script>
$(document).ready( function () {
    $('#tabela').DataTable();
} );
</script>

<script>
$(document).ready( function () {
    $('#tabela2').DataTable();
} );
</script>

<script>
$(document).ready( function () {
    $('#tabela3').DataTable();
} );
</script>

<script>
$(document).ready( function () {
    $('#tabela4').DataTable();
} );
</script>

<script>
$(document).ready( function () {
    $('#tabela5').DataTable();
} );
</script>

<script>
$(document).ready( function () {
    $('#tabela6').DataTable();
} );
</script>

<script>
function float2moeda(num) {

   x = 0;

   if(num<0) {
      num = Math.abs(num);
      x = 1;
   }

   if(isNaN(num)) num = "0";
      cents = Math.floor((num*100+0.5)%100);

   num = Math.floor((num*100+0.5)/100).toString();

   if(cents < 10) cents = "0" + cents;
      for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
         num = num.substring(0,num.length-(4*i+3))+'.'
               +num.substring(num.length-(4*i+3));

   ret = num + ',' + cents;

   if (x == 1) ret = ' - ' + ret;return ret;

}
</script>

</head>
<body class="w3-indigo-dark">
<!-- CARREGANDO... INÍCIO -->
<div id="div_loading" class="w3-modal">
<div class="w3-modal-content" style="width:100%;height:100%;position:absolute;top:0;right:0;padding:0;margin:0;">
  <div class="w3-container w3-center w3-margin w3-padding-64">
	<img src="loading.gif" style="width:100px;">
	<p class="w3-text-black w3-center"><font class="w3-small w3-wide"><b>CARREGANDO</b></font></p>
  </div>
</div>
</div>
<script>
	document.getElementById('div_loading').style.display='block';
</script>
<!-- CARREGANDO... FIM -->

<!-- LOGO CAIXA -->
<br>
<div class="w3-container w3-center">
	<img src="bg_caixa.png" style="width:7%">
</div>

<div class="w3-container w3-azulzinho w3-padding w3-margin w3-round">

<?php
// RECEBE DADOS DO FORM - INÍCIO
$qual_data = $_POST['qual_data'];
$nome_arquivo = "$qual_data".".csv";
// RECEBE DADOS DO FORM - FIM

// DEFINE DATA ANTEIOR - INÍCIO
$ano = substr($qual_data, 0, 4);
$mes = substr($qual_data, 4, 2);
if($mes == "01"){
	$mes_anterior = "12";
	$ano_anterior = $ano - 1;
} else {
	$mes_anterior = $mes - 1;
	if ($mes_anterior < 10) $mes_anterior = "0$mes_anterior";
	$ano_anterior = $ano;
}
$data_anterior = "$ano_anterior"."$mes_anterior".".csv";
// DEFINE DATA ANTEIOR - FIM

// TRADUZ MÊS - INÍCIO
switch ($mes) {
						case '01':
							$mes_txt = 'Janeiro';
							break;
							
						case '02':
							$mes_txt = 'Fevereiro';
							break;
							
						case '03':
							$mes_txt = 'Março';
							break;
							
						case '04':
							$mes_txt = 'Abril';
							break;
							
						case '05':
							$mes_txt = 'Maio';
							break;
							
						case '06':
							$mes_txt = 'Junho';
							break;
							
						case '07':
							$mes_txt = 'Julho';
							break;
							
						case '08':
							$mes_txt = 'Agosto';
							break;
							
						case '09':
							$mes_txt = 'Setembro';
							break;
							
						case '10':
							$mes_txt = 'Outubro';
							break;
							
						case '11':
							$mes_txt = 'Novembro';
							break;
							
						case '12':
							$mes_txt = 'Dezembro';
							break;
}
// TRADUZ MÊS - FIM

switch ($mes_anterior) {
						case '01':
							$mes_anterior_txt = 'Janeiro';
							break;
							
						case '02':
							$mes_anterior_txt = 'Fevereiro';
							break;
							
						case '03':
							$mes_anterior_txt = 'Março';
							break;
							
						case '04':
							$mes_anterior_txt = 'Abril';
							break;
							
						case '05':
							$mes_anterior_txt = 'Maio';
							break;
							
						case '06':
							$mes_anterior_txt = 'Junho';
							break;
							
						case '07':
							$mes_anterior_txt = 'Julho';
							break;
							
						case '08':
							$mes_anterior_txt = 'Agosto';
							break;
							
						case '09':
							$mes_anterior_txt = 'Setembro';
							break;
							
						case '10':
							$mes_anterior_txt = 'Outubro';
							break;
							
						case '11':
							$mes_anterior_txt = 'Novembro';
							break;
							
						case '12':
							$mes_anterior_txt = 'Dezembro';
							break;
}
// TRADUZ MÊS - FIM


$salario_op = 0;
$salario_mq = 0;
$salario_rh = 0;
$salario_sa = 0;
$salario_aso = 0;
$salario_ap = 0;
$salario_mult = 0;
$salario_sp = 0;
$salario_sm = 0;
$salario_sos = 0;
$salario_cq = 0;
$salario_cra = 0;
$salario_ca = 0;
$salario_cp = 0;
$salario_gc  = 0;

// DEFINE VETORES COM OS CARGOS E RELAÇÕES - INÍCIO
$cargos = array("AGENTE DE PLAN E AN TRAFEGO","ANALISTA DE RECURSOS HUMANOS JR","ANALISTA DE SUPORTE A OPERAÇÃO","ASSISTENTE ADMINISTRATIVO","ASSISTENTE DE RECURSOS HUMANOS","COORDENADOR DE PLANEJAMENTO","COORDENADOR DE QUALIDADE","COORDENADOR DE RELACIONAMENTO E ABORDAGEM","GERENTE DE CONTRATO","MONITOR DE QUALIDADE","MULTIPLICADOR","OPERADOR DE RELACIONAMENTO E ABORDAGEM","SUPERVISOR DE MONITORIA","SUPERVISOR DE PRODUÇÃO","SUPORTE ADMINISTRATIVO","SUPORTE OPERACIONAL A SISTEMAS JR","TECNICO DE SUPORTE PL");

$lista_cargos = array("AGENTE DE PLAN E AN TRAFEGO","ANALISTA DE RECURSOS HUMANOS JR","ANALISTA DE SUPORTE A OPERAÇÃO","ASSISTENTE ADMINISTRATIVO","ASSISTENTE DE RECURSOS HUMANOS","COORDENADOR DE PLANEJAMENTO","COORDENADOR DE QUALIDADE","COORDENADOR DE RELACIONAMENTO E ABORDAGEM","GERENTE DE CONTRATO","MONITOR DE QUALIDADE","MULTIPLICADOR","OPERADOR DE RELACIONAMENTO E ABORDAGEM","SUPERVISOR DE MONITORIA","SUPERVISOR DE PRODUÇÃO","SUPORTE ADMINISTRATIVO","SUPORTE OPERACIONAL A SISTEMAS JR","TECNICO DE SUPORTE PL");

$count_cargos = count($cargos);

$cargos["OPERADOR DE RELACIONAMENTO E ABORDAGEM"]["Relacao"] = array("Operação de Relacionamento e Abordagem");
$cargos["SUPERVISOR DE PRODUÇÃO"]["Relacao"] = array("Supervisão da Operação");
$cargos["ANALISTA DE SUPORTE A OPERAÇÃO"]["Relacao"] = array("Analista de Suporte a Operação");
$cargos["AGENTE DE PLAN E AN TRAFEGO"]["Relacao"] = array("Agente de Planejamento, Acompanhamento da Operação e Análise de Tráfego");
$cargos["COORDENADOR DE PLANEJAMENTO"]["Relacao"] = array("Coordenação de Planejamento, Acompanhamento da Operação e Análise de Tráfego");
$cargos["COORDENADOR DE RELACIONAMENTO E ABORDAGEM"]["Relacao"] = array("Coordenação de Relacionamento e Abordagem");
$cargos["MONITOR DE QUALIDADE"]["Relacao"] = array("Monitoria da Qualidade");
$cargos["SUPERVISOR DE MONITORIA"]["Relacao"] = array("Supervisão da Monitoria");
$cargos["COORDENADOR DE QUALIDADE"]["Relacao"] = array("Coordenação da Qualidade");
$cargos["MULTIPLICADOR"]["Relacao"] = array("Multiplicador");
$cargos["SUPORTE ADMINISTRATIVO"]["Relacao"] = array("Suporte Administrativo");
$cargos["ASSISTENTE ADMINISTRATIVO"]["Relacao"] = array("Recursos Humanos");
$cargos["SUPORTE OPERACIONAL A SISTEMAS JR"]["Relacao"] = array("Suporte Operacional a Sistemas");
$cargos["TECNICO DE SUPORTE PL"]["Relacao"] = array("Suporte Operacional a Sistemas");
$cargos["ASSISTENTE DE RECURSOS HUMANOS"]["Relacao"] = array("Recursos Humanos");
$cargos["ANALISTA DE RECURSOS HUMANOS JR"]["Relacao"] = array("Recursos Humanos");
$cargos["GERENTE DE CONTRATO"]["Relacao"] = array("Gerência do Contrato");
$cargos["COORDENADOR ADMINISTRATIVO"]["Relacao"] = array("Coordenação Administrativa");


$cargos_contrato = array("Operação de Relacionamento e Abordagem","Supervisão da Operação","Analista de Suporte a Operação","Agente de Planejamento, Acompanhamento da Operação e Análise de Tráfego","Coordenação de Planejamento, Acompanhamento da Operação e Análise de Tráfego","Coordenação de Relacionamento e Abordagem","Monitoria da Qualidade","Supervisão da Monitoria","Coordenação da Qualidade","Multiplicador","Suporte Administrativo","Suporte Operacional a Sistemas","Recursos Humanos","Coordenação Administrativa","Gerência do Contrato");

$lista_cargos_contrato = array("Operação de Relacionamento e Abordagem","Supervisão da Operação","Analista de Suporte a Operação","Agente de Planejamento, Acompanhamento da Operação e Análise de Tráfego","Coordenação de Planejamento, Acompanhamento da Operação e Análise de Tráfego","Coordenação de Relacionamento e Abordagem","Monitoria da Qualidade","Supervisão da Monitoria","Coordenação da Qualidade","Multiplicador","Suporte Administrativo","Suporte Operacional a Sistemas","Recursos Humanos","Coordenação Administrativa","Gerência do Contrato");

$count_cargos_contrato = count($cargos_contrato);

$cargos_contrato["Operação de Relacionamento e Abordagem"]["Relacao"] = array("OPERADOR DE RELACIONAMENTO E ABORDAGEM");
$cargos_contrato["Supervisão da Operação"]["Relacao"] = array("SUPERVISOR DE PRODUÇÃO");
$cargos_contrato["Analista de Suporte a Operação"]["Relacao"] = array("ANALISTA DE SUPORTE A OPERAÇÃO");
$cargos_contrato["Agente de Planejamento, Acompanhamento da Operação e Análise de Tráfego"]["Relacao"] = array("AGENTE DE PLAN E AN TRAFEGO");
$cargos_contrato["Coordenação de Planejamento, Acompanhamento da Operação e Análise de Tráfego"]["Relacao"] = array("COORDENADOR DE PLANEJAMENTO");
$cargos_contrato["Coordenação de Relacionamento e Abordagem"]["Relacao"] = array("COORDENADOR DE RELACIONAMENTO E ABORDAGEM");
$cargos_contrato["Monitoria da Qualidade"]["Relacao"] = array("MONITOR DE QUALIDADE");
$cargos_contrato["Supervisão da Monitoria"]["Relacao"] = array("SUPERVISOR DE MONITORIA");
$cargos_contrato["Coordenação da Qualidade"]["Relacao"] = array("COORDENADOR DE QUALIDADE");
$cargos_contrato["Multiplicador"]["Relacao"] = array("MULTIPLICADOR");
$cargos_contrato["Suporte Administrativo"]["Relacao"] = array("SUPORTE ADMINISTRATIVO");
$cargos_contrato["Suporte Operacional a Sistemas"]["Relacao"] = array("SUPORTE OPERACIONAL A SISTEMAS JR","TECNICO DE SUPORTE PL");
$cargos_contrato["Recursos Humanos"]["Relacao"] = array("ASSISTENTE DE RECURSOS HUMANOS","ANALISTA DE RECURSOS HUMANOS JR","ASSISTENTE ADMINISTRATIVO");
$cargos_contrato["Coordenação Administrativa"]["Relacao"] = array("COORDENADOR ADMINISTRATIVO");
$cargos_contrato["Gerência do Contrato"]["Relacao"] = array("GERENTE DE CONTRATO");

$cargos_contrato["Operação de Relacionamento e Abordagem"]["Indice"] = 0;
$cargos_contrato["Supervisão da Operação"]["Indice"] = 20;
$cargos_contrato["Analista de Suporte a Operação"]["Indice"] = 30;
$cargos_contrato["Agente de Planejamento, Acompanhamento da Operação e Análise de Tráfego"]["Indice"] = 150;
$cargos_contrato["Coordenação de Planejamento, Acompanhamento da Operação e Análise de Tráfego"]["Indice"] = 1;
$cargos_contrato["Coordenação de Relacionamento e Abordagem"]["Indice"] = 200;
$cargos_contrato["Monitoria da Qualidade"]["Indice"] = 30;
$cargos_contrato["Supervisão da Monitoria"]["Indice"] = 15;
$cargos_contrato["Coordenação da Qualidade"]["Indice"] = 1;
$cargos_contrato["Multiplicador"]["Indice"] = 100;
$cargos_contrato["Suporte Administrativo"]["Indice"] = 200;
$cargos_contrato["Suporte Operacional a Sistemas"]["Indice"] = 150;
$cargos_contrato["Recursos Humanos"]["Indice"] = 150;
$cargos_contrato["Coordenação Administrativa"]["Indice"] = 1;
$cargos_contrato["Gerência do Contrato"]["Indice"] = 1;
// DEFINE VETORES COM OS CARGOS E RELAÇÕES - FIM

echo "<div class='w3-center w3-container w3-margin w3-padding w3-card-8 w3-indigo-dark w3-wide w3-small'>";
echo "<b>CONTINGENCIAMENTO: <font class='w3-text-orange'>$mes_txt/$ano</font> - CONTRATO INDRA MARACANAÚ</b>";
echo "</div>";

// ABRE O ARQUIVO DO MÊS ATUAL E GRAVA NO ARRAY $mes_selecionado - INÍCIO
$row = 0;
if (($handle = fopen("arquivos/$nome_arquivo", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		$mes_selecionado[$row] = $data;
        $row++;
    }
    fclose($handle);
}
$qtd_linhas_mes_selecionado = count($mes_selecionado);
// ABRE O ARQUIVO DO MÊS ATUAL E GRAVA NO ARRAY $mes_selecionado - FIM





if (file_exists("arquivos/$data_anterior")) {
// ABRE O ARQUIVO DO MÊS ANTERIOR E GRAVA NO ARRAY $mes_selecionado - INÍCIO
$row = 0;
if (($handle2 = fopen("arquivos/$data_anterior", "r")) !== FALSE) {
    while (($data2 = fgetcsv($handle2, 1000, ";")) !== FALSE) {
		$arq_mes_anterior[$row] = $data2;
        $row++;
    }
    fclose($handle2);
}
$qtd_linhas_mes_anterior = count($arq_mes_anterior);
// ABRE O ARQUIVO DO MÊS ANTERIOR E GRAVA NO ARRAY $mes_selecionado - FIM
}







// GERA QUANTIDADE FUNCIONÁRIOS X CARGOS MÊS SELECIONADO - INÍCIO
	for($b=0;$b<$qtd_linhas_mes_selecionado;$b++){
		$nome_cargo = trim(utf8_encode($mes_selecionado[$b][4]));
		
		if (!isset($cargos["$nome_cargo"]["quantidade_mes_selecionado"])) {
			$cargos["$nome_cargo"]["quantidade_mes_selecionado"] = 0;
		}
		
		$cargos["$nome_cargo"]["quantidade_mes_selecionado"]++;
				
		//**
		foreach ($lista_cargos_contrato as $cargo_caixa) {
			if (in_array("$nome_cargo", $cargos_contrato["$cargo_caixa"]["Relacao"])) { 
		
				if (!isset($cargos_contrato["$cargo_caixa"]["quantidade_mes_selecionado"])) {
					$cargos_contrato["$cargo_caixa"]["quantidade_mes_selecionado"] = 0;
				}		
				$cargos_contrato["$cargo_caixa"]["quantidade_mes_selecionado"]++;
			}
		}
		//**
	}
// GERA QUANTIDADE FUNCIONÁRIOS X CARGOS MÊS SELECIONADO - FIM


// DEFINE SALÁRIOS - INÍCIO
for($b=0;$b<$qtd_linhas_mes_selecionado;$b++){
	$nome_cargo = trim(utf8_encode($mes_selecionado[$b][4]));
	
	$salario = trim(utf8_encode($mes_selecionado[$b][9]));
	$salario = str_replace("R$", "", $salario);
	$salario = str_replace(".", "", $salario);
	
	if($nome_cargo == "OPERADOR DE RELACIONAMENTO E ABORDAGEM") $salario_op = $salario;
	if($nome_cargo == "MONITOR DE QUALIDADE") $salario_mq = $salario;
	if($nome_cargo == "ASSISTENTE DE RECURSOS HUMANOS") $salario_rh = $salario;
	if($nome_cargo == "SUPORTE ADMINISTRATIVO") $salario_sa = $salario;
	if($nome_cargo == "ANALISTA DE SUPORTE A OPERAÇÃO") $salario_aso = $salario;
	
	if($nome_cargo == "AGENTE DE PLAN E AN TRAFEGO") $salario_ap = $salario;
	if($nome_cargo == "MULTIPLICADOR") $salario_mult = $salario;
	if($nome_cargo == "SUPERVISOR DE PRODUÇÃO") $salario_sp = $salario;
	if($nome_cargo == "SUPERVISOR DE MONITORIA") $salario_sm = $salario;
	//if($nome_cargo == "") $salario_sos = $salario;
	
	if($nome_cargo == "COORDENADOR DE QUALIDADE") $salario_cq = $salario;
	if($nome_cargo == "COORDENADOR DE RELACIONAMENTO E ABORDAGEM"){
			$salario_cra = $salario;
			$salario_ca = $salario; //SOLUÇÃO PARA MESES QUE NÃO CONSTA "COORDENADOR ADMINISTRATIVO"
	}
	if($nome_cargo == "COORDENADOR ADMINISTRATIVO") $salario_ca = $salario;
	if($nome_cargo == "COORDENADOR DE PLANEJAMENTO") $salario_cp = $salario;
	if($nome_cargo == "GERENTE DE CONTRATO") $salario_gc = $salario;
}
// DEFINE SALÁRIOS - FIM


// GERA Quantidade de Empregados Necessários por Cargo - INÍCIO

$quantidade_operadores = $cargos_contrato["Operação de Relacionamento e Abordagem"]["quantidade_mes_selecionado"];
$quantidade_monitores = $cargos_contrato["Monitoria da Qualidade"]["quantidade_mes_selecionado"];
$cargos_contrato["Operação de Relacionamento e Abordagem"]["Dimensionado"] = $quantidade_operadores;

$qtd_sup_monitoria = $quantidade_monitores / 15;
$qtd_sup_monitoria = number_format($qtd_sup_monitoria, 0, ',', '.');
$cargos_contrato["Supervisão da Monitoria"]["Dimensionado"] = $qtd_sup_monitoria;

foreach ($lista_cargos_contrato as $cargo_caixa) {
	
	if(($cargo_caixa != "Operação de Relacionamento e Abordagem") && ($cargo_caixa != "Supervisão da Monitoria")){
		
			$cargos_contrato["$cargo_caixa"]["Dimensionado"] = $quantidade_operadores / $cargos_contrato["$cargo_caixa"]["Indice"];
			if($cargos_contrato["$cargo_caixa"]["Indice"] == 1) $cargos_contrato["$cargo_caixa"]["Dimensionado"] = 1;
	}
}

// GERA Quantidade de Empregados Necessários por Cargo - FIM





// GERA ARRAY EMPREGADOS MES ATUAL E EMPREGADOS MES ANTERIOR - INÍCIO
$empregados_mes_atual = array();
$empregados_mes_anterior = array();

	for($b=0;$b<$qtd_linhas_mes_selecionado;$b++){
			$matricula = trim(utf8_encode($mes_selecionado[$b][2]));
			array_push($empregados_mes_atual, "$matricula");
	}
	$qtd_mes_atual = count($empregados_mes_atual);

if (file_exists("arquivos/$data_anterior")) {
	for($b=0;$b<$qtd_linhas_mes_anterior;$b++){
			$matricula = trim(utf8_encode($arq_mes_anterior[$b][2]));
			array_push($empregados_mes_anterior, "$matricula");
	}
	$qtd_mes_anterior = count($empregados_mes_anterior);
}
// GERA ARRAY EMPREGADOS MES ATUAL E EMPREGADOS MES ANTERIOR - FIM

if (file_exists("arquivos/$data_anterior")) {
// GERA ARRAY $operadores_demitidos - INÍCIO
	$empregados_demitidos = array();
	
	for($b=0;$b<$qtd_mes_anterior;$b++){
		$empregado = $empregados_mes_anterior[$b];
		if (!in_array("$empregado", $empregados_mes_atual)){
			array_push($empregados_demitidos, "$empregado");
		}
	}
// GERA ARRAY $operadores_demitidos - FIM


// GERA ARRAY $operadores_admitidos - INÍCIO	
	$empregados_admitidos = array();
	
	for($b=0;$b<$qtd_mes_atual;$b++){
		$empregado = $empregados_mes_atual[$b];
		if (!in_array("$empregado", $empregados_mes_anterior)){
			array_push($empregados_admitidos, "$empregado");
		}
	}	
// GERA ARRAY $operadores_admitidos - FIM


//IMPRIME RELAÇÃO DE EMPREGADOS MÊS ANTERIOR - INÍCIO
echo "<div class='w3-white w3-border w3-border-indigo-dark w3-margin w3-card-8'><div class='w3-container w3-padding w3-tiny w3-margin w3-center'><table id='tabela2' name='tabela2' class='w3-table w3-striped w3-hoverable w3-tiny  '>";
echo "<b class='w3-small w3-text-indigo-dark w3-wide'>RELAÇÃO DE EMPREGADOS - ($mes_anterior_txt/$ano_anterior)</b>";
for($m=0;$m<$qtd_linhas_mes_anterior;$m++){
	$linha = $arq_mes_anterior[$m];
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
//IMPRIME RELAÇÃO DE EMPREGADOS MÊS ANTERIOR - FIM
}



//IMPRIME RELAÇÃO DE EMPREGADOS MÊS ATUAL - INÍCIO
echo "<div class='w3-white w3-border w3-border-indigo-dark w3-margin w3-card-8'><div class='w3-container w3-padding w3-tiny w3-margin w3-center'><table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny  '>";
echo "<b class='w3-small w3-text-indigo-dark w3-wide'>RELAÇÃO DE EMPREGADOS - ($mes_txt/$ano)</b>";
for($m=0;$m<$qtd_linhas_mes_selecionado;$m++){
	$linha = $mes_selecionado[$m];
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
//IMPRIME RELAÇÃO DE EMPREGADOS MÊS ATUAL - FIM

if (file_exists("arquivos/$data_anterior")) {
//IMPRIME EMPREGADOS DEMITIDOS - INÍCIO
echo "<div class='w3-white w3-border w3-border-indigo-dark w3-margin w3-card-8'><div class='w3-container w3-padding w3-tiny w3-margin w3-center'><table id='tabela3' name='tabela3' class='w3-table w3-striped w3-hoverable w3-tiny  '>";
echo "<b class='w3-small w3-text-indigo-dark w3-wide'>DEMISSÕES</b>";
for($m=0;$m<$qtd_linhas_mes_anterior;$m++){
	$linha = $arq_mes_anterior[$m];
	$tamanho = count($linha);	
	if ($linha[0]=="") continue;
	
		if ((in_array($linha[2], $empregados_demitidos))||($m==0)){
		if($m==0) echo"<thead><tr class='w3-indigo-dark'>";
		else echo "<tr>";
		
			for($n=0;$n<$tamanho;$n++){
				$imprime = utf8_encode($linha[$n]);			
				echo "<td>$imprime</td>";
			}
		echo "</tr>";
	}
		if($m==0) echo"</thead><tbody>";
}
echo "</tbody></table></div></div>";
//IMPRIME EMPREGADOS DEMITIDOS - FIM

//IMPRIME EMPREGADOS ADMITIDOS - INÍCIO
echo "<div class='w3-white w3-border w3-border-indigo-dark w3-margin w3-card-8'><div class='w3-container w3-padding w3-tiny w3-margin w3-center'><table id='tabela4' name='tabela4' class='w3-table w3-striped w3-hoverable w3-tiny  '>";
echo "<b class='w3-small w3-text-indigo-dark w3-wide'>CONTRATAÇÕES</b>";
for($m=0;$m<$qtd_linhas_mes_selecionado;$m++){
	$linha = $mes_selecionado[$m];
	$tamanho = count($linha);	
	if ($linha[0]=="") continue;
	
	if ((in_array($linha[2], $empregados_admitidos))||($m==0)){
		if($m==0) echo"<thead><tr class='w3-indigo-dark'>";
		else echo "<tr>";
		
			for($n=0;$n<$tamanho;$n++){
				$imprime = utf8_encode($linha[$n]);			
				echo "<td>$imprime</td>";
			}
		echo "</tr>";
	}
		if($m==0) echo"</thead><tbody>";
}
echo "</tbody></table></div></div>";
//IMPRIME EMPREGADOS ADMITIDOS - FIM
}

//IMPRIME QUADRO DE FUNCIONÁRIOS - INÍCIO
echo "<div class='w3-white w3-border w3-border-indigo-dark w3-margin w3-card-8'><div class='w3-container w3-padding w3-tiny w3-margin w3-center'><table id='tabela5' name='tabela5' class='w3-table w3-striped w3-hoverable w3-tiny  '>";
echo "<b class='w3-small w3-text-indigo-dark w3-wide'>QUADRO DE FUNCIONÁRIOS</b>";

echo"<thead><tr class='w3-indigo-dark'>";
	echo "<td>";
		echo "Cargo/Função";
	echo "</td>";
	
	echo "<td>";
		echo "Índice Contratual";
	echo "</td>";
	
	echo "<td>";
		echo "Quantidade Contratado";
	echo "</td>";
	
	echo "<td>";
		echo "Quantidade Dimensionado";
	echo "</td>";
	
	echo "<td>";
		echo "Diferença";
	echo "</td>";
echo"</thead><tbody>";
		
foreach ($lista_cargos_contrato as $value) {
	echo "<tr>";
		echo "<td>";
			echo $value;
		echo "</td>";
		
		if (isset($cargos_contrato["$value"]["Indice"])) $indice = $cargos_contrato["$value"]["Indice"];
		else $indice = "Não Informado";
		echo "<td>";
			echo $indice;
		echo "</td>";
		
		if (isset($cargos_contrato["$value"]["quantidade_mes_selecionado"])) $quantidade_mes_selecionado = $cargos_contrato["$value"]["quantidade_mes_selecionado"];
		else $quantidade_mes_selecionado = 0;
		echo "<td>";
			echo $quantidade_mes_selecionado;
		echo "</td>";
		
		if (isset($cargos_contrato["$value"]["Dimensionado"])) $dimensionado = $cargos_contrato["$value"]["Dimensionado"];
		else $dimensionado = 0;
		$dimensionado = number_format($dimensionado, 0, ',', '.');
		echo "<td>";
			echo $dimensionado;
		echo "</td>";
		
		echo "<td>";
			echo $dimensionado - $quantidade_mes_selecionado;
		echo "</td>";
	echo "</tr>";
}
echo "</tbody></table></div></div>";
//IMPRIME QUADRO DE FUNCIONÁRIOS - FIM


// IMPRIME CONTINGENCIAMENTO - INÍCIO
echo "<div class='w3-white w3-border w3-border-indigo-dark w3-margin w3-card-8'><div class='w3-container w3-padding w3-tiny w3-margin w3-center'><table id='tabela6' name='tabela6' class='w3-table w3-striped w3-hoverable w3-tiny'>";
echo "<b class='w3-small w3-text-indigo-dark w3-wide'>CONTINGENCIAMENTO</b>";

echo"<thead><tr class='w3-indigo-dark'>";
	echo "<td>";
		echo "Cargo/Função";
	echo "</td>";
	
	echo "<td>";
		echo "Salário (R$)";
	echo "</td>";
	
	echo "<td>";
		echo "QTD No Mês";
	echo "</td>";
	
	echo "<td>";
		echo "13º salário (R$) - 8,33%";
	echo "</td>";
	
	echo "<td>";
		echo "Férias + Abono de Férias (R$) - 11,11%";
	echo "</td>";
	
	echo "<td>";
		echo "Multa FGTS Rescisão s/ justa causa (R$) - 4,35%";
	echo "</td>";
	
	echo "<td>";
		echo "SUBTOTAL (R$) - 23,79%";
	echo "</td>";
	
	echo "<td>";
		echo "Incidência do Grupo A s/ férias e 13º salário (R$) - 3,70%";
	echo "</td>";
	
	echo "<td>";
		echo "Encargos a contingenciar (R$) - 27,49%";
	echo "</td>";
	
	echo "<td>";
		echo "TOTAL A CONTINGENCIAR (R$)";
	echo "</td>";

echo"</tr></thead><tbody>";

$total_final = 0;
$contador = 0;
foreach ($lista_cargos_contrato as $value) {
	echo "<tr>";
	
		echo "<td>";
			echo $value;
		echo "</td>";
		
		echo "<td>";
			if ($value == "Operação de Relacionamento e Abordagem") $sal = $salario_op;
			if ($value == "Monitoria da Qualidade") $sal = $salario_mq;
			if ($value == "Recursos Humanos") $sal = $salario_rh;
			if ($value == "Suporte Administrativo") $sal = $salario_sa;
			if ($value == "Analista de Suporte a Operação") $sal = $salario_aso;
			
			if ($value == "Agente de Planejamento, Acompanhamento da Operação e Análise de Tráfego") $sal = $salario_ap;
			if ($value == "Multiplicador") $sal = $salario_mult;
			if ($value == "Supervisão da Operação") $sal = $salario_sp;
			if ($value == "Supervisão da Monitoria") $sal = $salario_sm;
			if ($value == "Suporte Operacional a Sistemas") $sal = $salario_sos;
			
			if ($value == "Coordenação da Qualidade") $sal = $salario_cq;
			if ($value == "Coordenação de Relacionamento e Abordagem") $sal = $salario_cra;
			if ($value == "Coordenação Administrativa") $sal = $salario_ca;
			if ($value == "Coordenação de Planejamento, Acompanhamento da Operação e Análise de Tráfego") $sal = $salario_cp;
			if ($value == "Gerência do Contrato") $sal = $salario_gc ;
			
			echo "<input id='salario$contador' style='margin-top:1px; width:100%;' onkeypress='return SomenteNumero(event)' value='$sal'></input>";
		echo "</td>";
		
		if (isset($cargos_contrato["$value"]["quantidade_mes_selecionado"])) $quantidade_mes_selecionado = $cargos_contrato["$value"]["quantidade_mes_selecionado"];
		else $quantidade_mes_selecionado = 0;
		echo "<td id='qtd$contador'>";
			echo $quantidade_mes_selecionado;
		echo "</td>";
		
		$sal = str_replace(",", ".", $sal);
		
		echo "<td id='decimoterceiro$contador'>";
			$preencher = $sal * 8.33 / 100;
			$preencher = number_format($preencher, 2, ',', '.');
			echo "$preencher";
		echo "</td>";
		
		echo "<td id='ferias$contador'>";
			$preencher = $sal * 11.11 / 100;
			$preencher = number_format($preencher, 2, ',', '.');
			echo "$preencher";
		echo "</td>";
		
		echo "<td id='multa$contador'>";
			$preencher = $sal * 4.35 / 100;
			$preencher = number_format($preencher, 2, ',', '.');
			echo "$preencher";
		echo "</td>";
		
		echo "<td id='subtotal$contador'>";
			$preencher = $sal * 23.79 / 100;
			$preencher = number_format($preencher, 2, ',', '.');
			echo "$preencher";
		echo "</td>";
		
		echo "<td id='incidencia$contador'>";
			$preencher = $sal * 3.70 / 100;
			$preencher = number_format($preencher, 2, ',', '.');
			echo "$preencher";
		echo "</td>";
		
		echo "<td id='encargos$contador'>";
			$preencher = $sal * 27.49 / 100;
			$preencher = number_format($preencher, 2, ',', '.');
			echo "$preencher";
		echo "</td>";
		
		echo "<td id='total$contador'>";
			$preencher = $sal * 27.49 / 100 * $quantidade_mes_selecionado;
				$total_final = $total_final + $preencher;
			$preencher = number_format($preencher, 2, ',', '.');
			echo "$preencher";
		echo "</td>";
		
		
		echo "<script>
$(document).ready(function(){
	$('#salario$contador').change(function(){
		
		var decimoterceiro = $('#salario$contador').val().replace(',','.') * 8.33 / 100;
		var decimoterceiro = float2moeda(decimoterceiro.toFixed(2).replace(',','.'));
		$('#decimoterceiro$contador').html(decimoterceiro);
		
		var ferias = $('#salario$contador').val().replace(',','.') * 11.11 / 100;
		var ferias = float2moeda(ferias.toFixed(2).replace(',','.'));
		$('#ferias$contador').html(ferias);
		
		var multa = $('#salario$contador').val().replace(',','.') * 4.35 / 100;
		var multa = float2moeda(multa.toFixed(2).replace(',','.'));
		$('#multa$contador').html(multa);
		
		var subtotal = $('#salario$contador').val().replace(',','.') * 23.79 / 100;
		var subtotal = float2moeda(subtotal.toFixed(2).replace(',','.'));
		$('#subtotal$contador').html(subtotal);
		
		var incidencia = $('#salario$contador').val().replace(',','.') * 3.70 / 100;
		var incidencia = float2moeda(incidencia.toFixed(2).replace(',','.'));
		$('#incidencia$contador').html(incidencia);
		
		var encargos = $('#salario$contador').val().replace(',','.') * 27.49 / 100;
		var encargos = float2moeda(encargos.toFixed(2).replace(',','.'));
		$('#encargos$contador').html(encargos);
		
		var total = $('#qtd$contador').html() * $('#salario$contador').val().replace(',','.') * 27.49 / 100;
		var total = float2moeda(total.toFixed(2).replace(',','.'));
		$('#total$contador').html(total);

		var total_final_0 = parseFloat($('#total0').html().replace('.','').replace(',','.'));
		var total_final_1 = parseFloat($('#total1').html().replace('.','').replace(',','.'));
		var total_final_2 = parseFloat($('#total2').html().replace('.','').replace(',','.'));
		var total_final_3 = parseFloat($('#total3').html().replace('.','').replace(',','.'));
		var total_final_4 = parseFloat($('#total4').html().replace('.','').replace(',','.'));
		var total_final_5 = parseFloat($('#total5').html().replace('.','').replace(',','.'));
		var total_final_6 = parseFloat($('#total6').html().replace('.','').replace(',','.'));
		var total_final_7 = parseFloat($('#total7').html().replace('.','').replace(',','.'));
		var total_final_8 = parseFloat($('#total8').html().replace('.','').replace(',','.'));
		var total_final_9 = parseFloat($('#total9').html().replace('.','').replace(',','.'));
		var total_final_10 = parseFloat($('#total10').html().replace('.','').replace(',','.'));
		var total_final_11 = parseFloat($('#total11').html().replace('.','').replace(',','.'));
		var total_final_12 = parseFloat($('#total12').html().replace('.','').replace(',','.'));
		var total_final_13 = parseFloat($('#total13').html().replace('.','').replace(',','.'));
		var total_final_14 = parseFloat($('#total14').html().replace('.','').replace(',','.'));
		
		var total_final = total_final_0 + total_final_1 + total_final_2 + total_final_3 + total_final_4 + total_final_5 + total_final_6 + total_final_7 + total_final_8 + total_final_9 + total_final_10 + total_final_11 + total_final_12 + total_final_13 + total_final_14;
		
		var total_final = float2moeda(total_final);
		
		$('#final').html('<b>'+total_final+'</b>');
	});    
});
</script>";
		
		$contador++;
}
echo "</tbody>";

echo "<tr class='w3-indigo-dark'>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td><b>TOTAL FINAL:</b></td>";
		$total_final = number_format($total_final, 2, ',', '.');
	echo "<td id='final'><b>$total_final</b></td>";
echo "</tr>";

echo "</table></div></div>";
// IMPRIME CONTINGENCIAMENTO - FIM
?>
<div class="w3-center w3-tiny w3-margin-right w3-margin-4">Caixa Econômica Federal - CERAT Fortaleza / ceratfo@caixa.gov.br</div>
</div>


<script>  
$('#tabela2').DataTable( {
	"order": [[ 3, "asc" ]],
	 "iDisplayLength": 10,
	 "columnDefs": [ {
      "targets": [ 5, 6, 9, 10, 11, 12, 13, 14 ],
      "orderable": false
    } ]
} );
</script>

<script>  
$('#tabela').DataTable( {
	"order": [[ 3, "asc" ]],
	 "iDisplayLength": 10,
	 "columnDefs": [ {
      "targets": [ 5, 6, 9, 10, 11, 12, 13, 14 ],
      "orderable": false
    } ]
} );
</script>

<script>  
$('#tabela3').DataTable( {
	"order": [[ 3, "asc" ]],
	 "iDisplayLength": 10,
	 "columnDefs": [ {
      "targets": [ 5, 6, 9, 10, 11, 12, 13, 14 ],
      "orderable": false
    } ]
} );
</script>

<script>  
$('#tabela4').DataTable( {
	"order": [[ 3, "asc" ]],
	 "iDisplayLength": 10,
	 "columnDefs": [ {
      "targets": [ 5, 6, 9, 10, 11, 12, 13, 14 ],
      "orderable": false
    } ]
} );
</script>

<script>  
$('#tabela5').DataTable( {
	"order": [[ 1, "asc" ]],
	 "iDisplayLength": -1
} );
</script>

<script>  
$('#tabela6').DataTable( {
	"order": [[ 2, "asc" ]],
	 "iDisplayLength": -1,
	 "columnDefs": [ {
      "targets": [ 1, 3, 4, 5, 6, 7, 8, 9 ],
      "orderable": false
    } ]
} );
</script>

<!-- CARREGANDO... fadeOut - INÍCIO -->
<script>
	$("#div_loading").fadeOut('slow');
</script>
<!-- CARREGANDO... fadeOut - FIM -->

</body>
</html>