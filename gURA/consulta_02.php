<?php
	$data_inicial = $_GET['data_inicial'];
	$data_inicial_txt = $data_inicial;
	$data_inicial = date('Y/m/d',strtotime($data_inicial));
	
	$data_final = $_GET['data_final'];
	$data_final_txt = $data_final;
	$data_final = date('Y/m/d',strtotime($data_final));
	
	include "conecta.php";
	
	echo "<div class='titulo'><div class='tituloConteudo'>";
	echo "<b>Serviços X Quantidade</b><br><br>";
	echo "<b>Data Inicial:</b> $data_inicial_txt<br>";
	echo "<b>Data Final:</b> $data_final_txt<br>";
	echo "</div></div>";
	
	$query = $pdo->prepare("select a.cod_evento, b.desc_evento, count(*) qtd from tb_eventos_ura_2 as a
							left join tb_eventos_novaura as b on a.cod_evento = b.cod_evento
							where data_hora between '$data_inicial' and '$data_final 23:59:59'
							group by a.cod_evento, b.desc_evento
							order by qtd desc");
	$query->execute();
	echo "<div class='divTabela'><table>";
	echo "<tr>";
		echo "<td>CÓDIGO</td>";
		echo "<td>EVENTO</td>";
		echo "<td>QUANTIDADE</td>";
	echo "</tr>";
	for($i=0; $row = $query->fetch(); $i++){
		$cod_evento = utf8_encode($row['cod_evento']);
		$desc_evento = utf8_encode($row['desc_evento']);
		$qtd = utf8_encode($row['qtd']);
		
		if($cod_evento == '') continue;
		
		$qtd = number_format($qtd, 0, ',', '.');
		
		echo "<tr>";
		
			if($desc_evento == '') echo "<td style='color: #f10;'><b>$cod_evento</b></td>";
			else echo "<td>$cod_evento</td>";
			
			echo "<td>$desc_evento</td>";
			
			if($desc_evento == '') echo "<td style='color: #f10;'><b>$qtd</b></td>";
			else echo "<td>$qtd</td>";
		
		echo "</tr>";
	}
	echo "</table></div>";

	
?>