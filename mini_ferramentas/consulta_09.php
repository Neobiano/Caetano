<?php
if($select_fonte == '00'){
	include "def_var_ura.php";
	
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Sequência de eventos URA:</i></b> $codigo_evento";
	echo "<br><br>";
	
	$fluxo_ura_array = explode(";", $codigo_evento);
	$count = count($fluxo_ura_array);
		
	$texto = "";
	
	for($i=0; $i<$count; $i++){
		
		$cod = $fluxo_ura_array[$i];
		if($cod != ""){
			$palavra = "evento_$cod";
				
			if (isset($$palavra)) $txt_inc = $$palavra;
			else $txt_inc = "EVENTO SEM DESCRIÇÃO NA TABELA TB_EVENTOS"; 
				
			if ($i == 0)$texto = $texto."$cod($txt_inc)";
			if ($i > 0)$texto = $texto.";$cod($txt_inc)";
		}
	}
	
	echo "<b><i>Tradução:</i></b> $texto";
}

if($select_fonte == '01'){
	include "def_var_ura_nova.php";
	
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Sequência de eventos URA:</i></b> $codigo_evento";
	echo "<br><br>";
	
	$fluxo_ura_array = explode(";", $codigo_evento);
	$count = count($fluxo_ura_array);
		
	$texto = "";
	
	for($i=0; $i<$count; $i++){
		
		$cod = $fluxo_ura_array[$i];
		if($cod != ""){
			$palavra = "evento_$cod";
				
			if (isset($$palavra)) $txt_inc = $$palavra;
			else $txt_inc = "EVENTO SEM DESCRIÇÃO NA TABELA TB_EVENTOS";
				
			if ($i == 0)$texto = $texto."$cod($txt_inc)";
			if ($i > 0)$texto = $texto.";$cod($txt_inc)";
		}
	}
	
	echo "<b><i>Tradução:</i></b> $texto";
}

if($select_fonte == '02'){
	include "def_var_ura.php";
	
	echo '<div class="w3-margin w3-tiny w3-center">';
	echo "<b><i>Sequência de eventos FRONTEND:</i></b> $codigo_evento";
	echo "<br><br>";
	
	$fluxo_ura_array = explode(";", $codigo_evento);
	$count = count($fluxo_ura_array);
		
	$texto = "";
	
	for($i=0; $i<$count; $i++){
		
		$cod = $fluxo_ura_array[$i];
		if($cod != ""){
			$palavra = "evento_$cod";
				
			if (isset($$palavra)) $txt_inc = $$palavra;
			else $txt_inc = "EVENTO SEM DESCRIÇÃO NA TABELA TB_EVENTOS";
				
			if ($i == 0)$texto = $texto."$cod($txt_inc)";
			if ($i > 0)$texto = $texto.";$cod($txt_inc)";
		}
	}
	
	echo "<b><i>Tradução:</i></b> $texto";
}
?>