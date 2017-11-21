<?php
	$usuario = $_SERVER['LOGON_USER'];
	$verifica = '0';
	$usuario = strtoupper($usuario);
	
	// CERATFO
	if ($usuario=='CORPCAIXA\C037226') $verifica = '1'; // Marcia Helena Marinho de Farias
	if ($usuario=='CORPCAIXA\C069593') $verifica = '1'; // Evandro Nascimento Almeida Junior
	if ($usuario=='CORPCAIXA\C087727') $verifica = '1'; // Leirton Pinto de Almeida
	
	if ($usuario=='CORPCAIXA\C084206') $verifica = '1'; // Melissa Abreu Sena
	if ($usuario=='CORPCAIXA\C119814') $verifica = '1'; // Jayme Gilberto Amatnecks Junior
	if ($usuario=='CORPCAIXA\C111928') $verifica = '1'; // Cristiane Souza Carvalho de Farias
	if ($usuario=='CORPCAIXA\C064803') $verifica = '1'; // Danilo Bezerra de Barros
	if ($usuario=='CORPCAIXA\C001849') $verifica = '1'; // Mary Anne Marques de Paiva
	if ($usuario=='CORPCAIXA\C049373') $verifica = '1'; // Silvia Helena Catunda Brito
	if ($usuario=='CORPCAIXA\C026003') $verifica = '1'; // Maria Helena Coelho Honorio
	if ($usuario=='CORPCAIXA\C048819') $verifica = '1'; // Moises Peixoto Alves
	
	if ($usuario=='CORPCAIXA\C117516') $verifica = '1'; // Leandro Arruda Leal
	if ($usuario=='CORPCAIXA\C112568') $verifica = '1'; // Leandro Caetano de Faria
	if ($usuario=='CORPCAIXA\C124607') $verifica = '1'; // Fabiano Leal Lisboas
	
	if ($usuario=='CORPCAIXA\C037297') $verifica = '1'; // Marilandia Mota Holanda
	if ($usuario=='CORPCAIXA\C107138') $verifica = '1'; // Thiago Oliveira Barros
	if ($usuario=='CORPCAIXA\C122399') $verifica = '1'; // Givanildo da Silva
	
	if ($usuario=='CORPCAIXA\C059045') $verifica = '1'; // Alair Antonio Rizello
	
	if ($usuario=='CORPCAIXA\C087789') $verifica = '1'; // Carlos
	
	// GEATE
	if ($usuario=='CORPCAIXA\C062547') $verifica = '1'; // Matheus Rodrigues de Oliveira
	if ($usuario=='CORPCAIXA\C077254') $verifica = '1'; // Mayra Henyra Minari Borowski
	if ($usuario=='CORPCAIXA\C098122') $verifica = '1'; // Leonardo Fernandes dos Santos
	if ($usuario=='CORPCAIXA\C095088') $verifica = '1'; // Rudinick Bezerra de Aguiar
	
	/*if ($verifica != '1'){
		echo "<div class='w3-container w3-tiny w3-text-deep-blue w3-padding'>Acesso Negado. Por favor, envie requisição de autorização à CERATFO, listando nome(s) do(s) empregado(s) / matrícula / motivo de uso.</div>";
		echo "<script>$('#div_loading').fadeOut('slow');</script>";
		exit;
	}*/
?>