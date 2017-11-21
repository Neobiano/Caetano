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

echo "<p style='margin-top: 0px;'><b>Lista Clientes</b></p>";
echo "<p>Mês/Ano: $MES_IMPRIME/$ANO</p>";
echo "<p>Tipo do Cartão: $TIPO</p>";
echo "<p><b style='color: #f30;'>Dica:</b> Clique em cima de uma linha para listar os atendimentos referentes ao mês/ano e ao CPF.</p>";
echo "<br>";


$query = $pdo->prepare("select valor_dado CPF, count(distinct callid) as TOTAL_LIGACOES from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and cod_dado = 2 and callid in
						(
						select callid from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final' and valor_dado = '$TIPO'
						)
						group by valor_dado
						order by TOTAL_LIGACOES desc");
						
	$query->execute();
	
	echo "<div style='width: 100%;'><div style='padding: 0px 32px;'>";
	echo "<table class='tabela_dados' style='margin-bottom: 32px;'>";
	
	echo "<tr>";
		echo "<td>CPF</td>";
		echo "<td>TOTAL DE LIGAÇÕES</td>";
	echo "</tr>";

	for($i=0; $row = $query->fetch(); $i++){		
		$CPF = utf8_encode($row['CPF']);
		$TOTAL_LIGACOES = utf8_encode($row['TOTAL_LIGACOES']);
		
		echo "<tr onclick='window.open(\"detalha_atendimentos.php?ANO=$ANO&MES=$MES&TIPO=$TIPO&CPF=$CPF\", \"_blank\");' title='Clique para Detalhar os Atendimentos'>";
			
			
			echo "<td>$CPF</td>";
			echo "<td>$TOTAL_LIGACOES</td>";
				
		echo "</tr>";
	}
	
	echo "</table>";
	echo "</div></div>";	

?>
</body>
</html>