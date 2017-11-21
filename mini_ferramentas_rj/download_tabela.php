<?php
$tabela = $_GET['tabela'];
$nome_arquivo = $_GET['nome_arquivo'];

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nome_arquivo");

echo $tabela;
?>

