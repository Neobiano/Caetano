<!DOCTYPE html>
<html>

<head>
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
$data_inicial = $_GET['data_inicial'];
$data_final = $_GET['data_final'];
$tipo_do_cartao = $_GET['tipo_do_cartao'];

$data_inicial_txt = $data_inicial;
$data_final_txt = $data_final;

$data_inicial = date('m/d/Y',strtotime($data_inicial));
$data_final = date('m/d/Y',strtotime($data_final));

include "conecta.php";
set_time_limit(30000);
ini_set('max_execution_time', 30000);

echo "<p style='margin-top: 0px;'><b>Dados da Consulta</b></p>";
echo "<p>Tipo do Cartão: $tipo_do_cartao</p>";
echo "<p>Data Inicial: $data_inicial</p>";
echo "<p>Data Final: $data_final</p>";
echo "<p><b style='color: #f30;'>Dica:</b> Clique em cima de uma linha para consultar o CPF x quantidade de ligações dos clientes atendidos referentes ao mês/ano e ao tipo de cartão.</p>";
echo "<br>";

$query = $pdo->prepare("select year(data_hora) ANO, month(data_hora) MES, valor_dado TIPO, count(*) TOTAL from tb_dados_cadastrais
						where data_hora between '$data_inicial' and '$data_final 23:59:59' and valor_dado like '%$tipo_do_cartao%' and cod_dado = 5
						group by year(data_hora), month(data_hora), valor_dado
						order by year(data_hora), month(data_hora)");
						
	$query->execute();
	
	echo "<div style='width: 100%;'><div style='padding: 0px 32px;'>";
	echo "<table class='tabela_dados' style='margin-bottom: 32px;'>";
	
	echo "<tr>";
		echo "<td>MÊS/ANO</td>";
		echo "<td>TIPO</td>";
		echo "<td>TOTAL DE ATENDIMENTOS</td>";
	echo "</tr>";

	for($i=0; $row = $query->fetch(); $i++){		
		$ANO = utf8_encode($row['ANO']);
		$MES = utf8_encode($row['MES']);
		$TIPO = utf8_encode($row['TIPO']);
		$TOTAL = utf8_encode($row['TOTAL']);
		
		echo "<tr onclick='window.open(\"consulta_clientes.php?ANO=$ANO&MES=$MES&TIPO=$TIPO&TOTAL=$TOTAL\", \"_blank\");' title='Clique para Listar os Clientes'>";
			
			if($MES == 1) $MES = 'JANEIRO';
			if($MES == 2) $MES = 'FEVEREIRO';
			if($MES == 3) $MES = 'MARÇO';
			if($MES == 4) $MES = 'ABRIL';
			if($MES == 5) $MES = 'MAIO';
			if($MES == 6) $MES = 'JUNHO';
			if($MES == 7) $MES = 'JULHO';
			if($MES == 8) $MES = 'AGOSTO';
			if($MES == 9) $MES = 'SETEMBRO';
			if($MES == 10) $MES = 'OUTUBRO';
			if($MES == 11) $MES = 'NOVEMBRO';
			if($MES == 12) $MES = 'DEZEMBRO';
			
			echo "<td>$MES / $ANO</td>";
			echo "<td>$TIPO</td>";
			echo "<td>$TOTAL</td>";					
		echo "</tr>";
	}
	
	echo "</table>";
	echo "</div></div>";
	

?>
</body>
</html>