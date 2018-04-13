<?php
	$usuario = $_SERVER['LOGON_USER'];
	$verifica = '0';
	$usuario = strtoupper($usuario);
	
	// CERATFO
	if ($usuario=='CORPCAIXA\C069593') $verifica = '1'; // Evandro Nascimento Almeida Junior
	if ($usuario=='CORPCAIXA\C107138') $verifica = '1'; // Thiago Oliveira Barros
	if ($usuario=='CORPCAIXA\C084206') $verifica = '1'; // Melissa Abreu Sena
	if ($usuario=='CORPCAIXA\C117516') $verifica = '1'; // Leandro Arruda Leal
	if ($usuario=='CORPCAIXA\C037297') $verifica = '1'; // Marilandia Mota Holanda
	if ($usuario=='CORPCAIXA\C112568') $verifica = '1'; // Leandro Caetano de Faria
	if ($usuario=='CORPCAIXA\C124607') $verifica = '1'; // Fabiano Leal Lisboas
	if ($usuario=='CORPCAIXA\C037226') $verifica = '1'; // Marcia Helena Marinho de Farias
	if ($usuario=='CORPCAIXA\C059045') $verifica = '1'; // Alair Antonio Rizello
	
	// GEATE
	if ($usuario=='CORPCAIXA\C062547') $verifica = '1'; // Matheus Rodrigues de Oliveira
	if ($usuario=='CORPCAIXA\C098122') $verifica = '1'; // LEONARDO
	
	// CECAC
	if ($usuario=='CORPCAIXA\C113269') $verifica = '1'; // BRUNA FONTENELE AMORIM MONTES
	if ($usuario=='CORPCAIXA\C098106') $verifica = '1'; // DANIEL BARBOSA DIAS
	if ($usuario=='CORPCAIXA\C028571') $verifica = '1'; // FLAVIA CRUZ SCHNEIDER
	if ($usuario=='CORPCAIXA\C098150') $verifica = '1'; // HUDSON DOS SANTOS ARAUJO
	if ($usuario=='CORPCAIXA\C094083') $verifica = '1'; // JOAO GUSTAVO SILVA DE OLIVEIRA
	if ($usuario=='CORPCAIXA\C025413') $verifica = '1'; // KARLA REGINA PRINCE PINTO
	if ($usuario=='CORPCAIXA\C076788') $verifica = '1'; // PATRICIA VIEIRA LIMA
	if ($usuario=='CORPCAIXA\C123614') $verifica = '1'; // SHIRLEY DE OLIVEIRA BORGES FIUZA
	if ($usuario=='CORPCAIXA\C094083') $verifica = '1'; // JAIRO DE FARIA PAVAO
	if ($usuario=='CORPCAIXA\C118117') $verifica = '1'; // Alessandra Maria Gomes Sukiyama de Brito <alessandra.brito@caixa.gov.br> SUPERVISORA	
	if ($usuario=='CORPCAIXA\C137132') $verifica = '1'; // Andre Souto Neves ***** DESENVOLVEDOR DA FERRAMENTA CECAC *****	
	if ($usuario=='CORPCAIXA\C012356') $verifica = '1'; // PAULO EDUARDO TIBANA
	if ($usuario=='CORPCAIXA\C133333') $verifica = '1'; // Ester Machado Guedes
	

	//GEFEM
	if ($usuario=='CORPCAIXA\C092748') $verifica = '1'; // Roberta Veiga dos Santos Araujo
	if ($usuario=='CORPCAIXA\C073994') $verifica = '1'; // Paulo Helou Netto
	if ($usuario=='CORPCAIXA\C099185') $verifica = '1'; // ADRIANO NASCIMENTO ASSUNCAO
	if ($usuario=='CORPCAIXA\C114385') $verifica = '1'; // ELIZANGELA ALVES DE OLIVEIRA
	if ($usuario=='CORPCAIXA\C126464') $verifica = '1'; // SHIRLEY GERALDA DA SILVA MENDONCA
	if ($usuario=='CORPCAIXA\C113249') $verifica = '1'; // LAURINDO CAMILO DE CASTRO JUNIOR
	if ($usuario=='CORPCAIXA\C060378') $verifica = '1'; // ELTON NILLO MENEZES ALMEIDA
	if ($usuario=='CORPCAIXA\C063853') $verifica = '1'; // DANIELLE COSTA DE AGUIAR	
	if ($usuario=='CORPCAIXA\C028158') $verifica = '1'; // ADMA BENINCA
	if ($usuario=='CORPCAIXA\C118117') $verifica = '1'; // ALESSANDRA MARIA GOMES SUKIYAMA DE BRITO
	if ($usuario=='CORPCAIXA\C082485') $verifica = '1'; // CAROLINA MOTTA CAMARINHA
	if ($usuario=='CORPCAIXA\C087950') $verifica = '1'; // LUIZA LUCIO PEREIRA
	
	
	if ($verifica != '1'){
		echo "<div class='w3-container w3-tiny w3-text-deep-blue w3-padding'>Acesso Negado. Por favor, envie requisição de autorização à GEATE09 / CERATFO, listando nome(s) do(s) empregado(s) / matrícula / motivo de uso.</div>";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		exit;
	}
?>