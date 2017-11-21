<?php
<?php
	$usuario = $_SERVER['LOGON_USER'];
	$usuario = strtoupper($usuario);
	$usuario = substr($usuario, 10, 16);
	echo $usuario; //pega somente C112568
?>
?>