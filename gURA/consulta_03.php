<?php
	$data_inicial = $_GET['data_inicial'];
	$data_inicial_txt = $data_inicial;
	$data_inicial = date('Y/m/d',strtotime($data_inicial));
	
	$data_final = $_GET['data_final'];
	$data_final_txt = $data_final;
	$data_final = date('Y/m/d',strtotime($data_final));
	
	$sequenciaEventos = $_GET['sequenciaEventos'];
	
	include "conecta.php";
	
	echo "<div class='titulo'><div class='tituloConteudo'>";
	echo "<b>Procura Sequência de Eventos</b><br><br>";
	echo "<b>Data Inicial:</b> $data_inicial_txt<br>";
	echo "<b>Data Final:</b> $data_final_txt<br><br>";
	echo "<b>Sequência:</b> $sequenciaEventos<br><br>";
	
	echo "<b style='color: #f10;'>Dica:</b> Clique em cima de uma sequência de eventos para traduzir.";
	echo "</div></div>";
	
	$query = $pdo->prepare("select cod_evento, count(*) qtd from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59' and cod_evento like '%$sequenciaEventos%'
							group by cod_evento
							order by qtd desc");
	$query->execute();
	echo "<div class='divTabela'><table>";
	echo "<tr>";
		echo "<td>SEQUÊNCIA DE EVENTOS</td>";
		echo "<td>QUANTIDADE</td>";
	echo "</tr>";
	for($i=0; $row = $query->fetch(); $i++){
		$cod_evento = utf8_encode($row['cod_evento']);
		$qtd = utf8_encode($row['qtd']);
		
		$qtd = number_format($qtd, 0, ',', '.');
		
		echo "<tr>";
		
			echo "<td onclick='traduzEventos(this);' title='Clique para traduzir'>$cod_evento</td>";
			echo "<td>$qtd</td>";
		
		echo "</tr>";
	}
	echo "</table></div>";
?>