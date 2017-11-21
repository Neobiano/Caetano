<!DOCTYPE html>
<html>

<head>
<title>CONSULTA CARTÕES DE CRÉDITO CAIXA - Contrato INDRA Maracanaú</title>
<style>
.tabela_dados{width: 100%; padding: 16px;border-spacing:0; border: solid 1px #777; box-shadow:0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12)}
.tabela_dados tr:first-child{background: #26a !important; color: #fff !important; font-weight: bold; cursor: default !important;}
.tabela_dados tr{cursor: pointer;}
.tabela_dados td{padding: 4px 8px;}
.tabela_dados tr:first-child td{border-right: solid 2px #fff;}
.tabela_dados tr:first-child td:last-child{border-right: solid 0px #fff;}
.tabela_dados tr:nth-child(2n+1){background: #eee;}
.tabela_dados tr:hover{background: #333345; color: #fff;}
</style>
</head>

<body style='text-align: center;'>
<?php
$ANO = $_GET['ANO'];
$MES = $_GET['MES'];
$TIPO = $_GET['TIPO'];
$CPF = $_GET['CPF'];

$data_inicial = "$MES/01/$ANO";
if($MES == 12) $MES_NOVO = 1;
else $MES_NOVO = $MES + 1;
$data_final = "$MES_NOVO/01/$ANO";

include "conecta.php";
set_time_limit(3000);
ini_set('max_execution_time', 3000);

if($MES == 1) $MES_IMPRIME = 'Janeiro';
if($MES == 2) $MES_IMPRIME = 'Fevereiro';
if($MES == 3) $MES_IMPRIME = 'Março';
if($MES == 4) $MES_IMPRIME = 'Abril';
if($MES == 5) $MES_IMPRIME = 'Maio';
if($MES == 6) $MES_IMPRIME = 'Junho';
if($MES == 7) $MES_IMPRIME = 'Julho';
if($MES == 8) $MES_IMPRIME = 'Agosto';
if($MES == 9) $MES_IMPRIME = 'Setembro';
if($MES == 10) $MES_IMPRIME = 'Outubro';
if($MES == 11) $MES_IMPRIME = 'Novembro';
if($MES == 12) $MES_IMPRIME = 'Dezembro';

echo "<p style='margin-top: 0px;'><b>Detalha Atendimentos</b></p>";
echo "<p>Mês/Ano: $MES_IMPRIME/$ANO</p>";
echo "<p>Tipo do Cartão: $TIPO</p>";
echo "<p>CPF: <b>$CPF</b></p>";
echo "<br>";


$query = $pdo->prepare("select callid CALLID, min(data_hora) DATA_HORA, cod_dado COD_DADO, valor_dado VALOR_DADO from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and callid in
						(
						select callid from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and valor_dado = '$CPF'
						)
						and callid in
						(
						select callid from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and valor_dado = '$TIPO'
						)
						and cod_dado in (1,3,5)
						group by callid, cod_dado, valor_dado
						order by data_hora");
						
	$query->execute();
	
	echo "<div style='width: 100%;'><div style='padding: 0px 32px;'>";
	echo "<table class='tabela_dados' style='margin-bottom: 32px;'>";
	
	echo "<tr>";
		echo "<td>CALLID</td>";
		echo "<td>DATA/HORA</td>";
		echo "<td>TIPO DE DADO</td>";
		echo "<td>VALOR</td>";
	echo "</tr>";

	for($i=0; $row = $query->fetch(); $i++){		
		$CALLID = utf8_encode($row['CALLID']);
		$DATA_HORA = utf8_encode($row['DATA_HORA']);
		$COD_DADO = utf8_encode($row['COD_DADO']);
		$VALOR_DADO = utf8_encode($row['VALOR_DADO']);
				
		echo "<tr>";
			
			echo "<td>$CALLID</td>";
			echo "<td>$DATA_HORA</td>";
			
			if($COD_DADO == 1) $COD_DADO = 'NÚMERO DO CARTÃO';
			if($COD_DADO == 3) $COD_DADO = 'NÚMERO CHAMADOR';
			if($COD_DADO == 5) $COD_DADO = 'TIPO DO CARTÃO';
			
			echo "<td>$COD_DADO</td>";
			echo "<td>$VALOR_DADO</td>";
				
		echo "</tr>";
	}
	
	echo "</table>";
	echo "</div></div>";	

?>
</body>
</html>