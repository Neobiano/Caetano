<?php 
$query = $pdo->prepare("select cod_evento, desc_evento from tb_eventos_novaura");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	
    $cod_evento = utf8_encode($row['cod_evento']);
	$desc_evento = utf8_encode($row['desc_evento']);
	
	$palavra_evento = "evento_$cod_evento";	
	$$palavra_evento = $desc_evento;	
}

?>