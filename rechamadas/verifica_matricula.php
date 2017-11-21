<?php
	$usuario = $_SERVER['LOGON_USER'];
	$verifica = '0';
	$usuario = strtoupper($usuario);
	if ($usuario=='CORPCAIXA\C069593') $verifica = '1';
	if ($usuario=='CORPCAIXA\C119814') $verifica = '1';
	if ($usuario=='CORPCAIXA\C107138') $verifica = '1';
	if ($usuario=='CORPCAIXA\C090509') $verifica = '1';
	if ($usuario=='CORPCAIXA\C026003') $verifica = '1';
	if ($usuario=='CORPCAIXA\C001849') $verifica = '1';
	if ($usuario=='CORPCAIXA\C049373') $verifica = '1';
	if ($usuario=='CORPCAIXA\C111928') $verifica = '1';
	if ($usuario=='CORPCAIXA\C084206') $verifica = '1';
	if ($usuario=='CORPCAIXA\C117516') $verifica = '1';
	if ($usuario=='CORPCAIXA\C064803') $verifica = '1';
	if ($usuario=='CORPCAIXA\C048819') $verifica = '1';
	if ($usuario=='CORPCAIXA\C037163') $verifica = '1';
	if ($usuario=='CORPCAIXA\C037297') $verifica = '1';
	if ($usuario=='CORPCAIXA\C122399') $verifica = '1';
	if ($usuario=='CORPCAIXA\C112568') $verifica = '1';
	if ($usuario=='CORPCAIXA\C037226') $verifica = '1';
	if ($usuario=='CORPCAIXA\C087727') $verifica = '1';
	if ($usuario=='CORPCAIXA\C129761') $verifica = '1';
	if ($usuario=='CORPCAIXA\C086788') $verifica = '1';
	if ($usuario=='CORPCAIXA\C062547') $verifica = '1';

	if ($verifica != '1'){
		echo "Acesso Negado!";
		exit;
	}
?>