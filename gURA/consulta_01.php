<?php
	$data_inicial = $_GET['data_inicial'];
	$data_inicial_txt = $data_inicial;
	$data_inicial = date('Y/m/d',strtotime($data_inicial));
	
	$data_final = $_GET['data_final'];
	$data_final_txt = $data_final;
	$data_final = date('Y/m/d',strtotime($data_final));
	
	$modeloErrosWebservice = $_GET['modeloErrosWebservice'];
	
	include "conecta.php";
	
	if($modeloErrosWebservice == '01'){
	
		echo "<div class='titulo'><div class='tituloConteudo'>";
		echo "<b>Erros Webservice (Evento 002)</b><br><br>";
		echo "<b>Data Inicial:</b> $data_inicial_txt<br>";
		echo "<b>Data Final:</b> $data_final_txt<br><br>";
		echo "<b style='color: #f10;'>Dica:</b> Clique em cima de uma sequência de eventos para traduzir.";
		echo "</div></div>";
		
		$query = $pdo->prepare("select cod_evento, count(*) qtd from tb_eventos_ura
								where data_hora between '$data_inicial' and '$data_final 23:59:59' and cod_evento like '%002%'
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
	}
	
	if($modeloErrosWebservice == '02'){
		$eventos_x_descricao = array();	
		$query = $pdo->prepare("select * from tb_eventos_novaura");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++){
				$cod_evento = utf8_encode($row['cod_evento']);
				$desc_evento = utf8_encode($row['desc_evento']);
				
				$eventos_x_descricao[$cod_evento] = $desc_evento;
		}
	
		echo "<div class='titulo'><div class='tituloConteudo'>";
		echo "<b>Erros Webservice (Evento 002)</b><br><br>";
		echo "<b>Data Inicial:</b> $data_inicial_txt<br>";
		echo "<b>Data Final:</b> $data_final_txt<br><br>";
		echo "<b style='color: #f10;'>Observação:</b> O resultado informa a quantidade por evento imediatamente anterior aos erros webservice.";
		echo "</div></div>";
		
		$query = $pdo->prepare("select * from tb_eventos_ura as a
								where data_hora between '$data_inicial' and '$data_final 23:59:59' and cod_evento like '%002%'");
		$query->execute();
		
		$tabelaEventosListados = array();
		$listaEventosListados = array();
		for($i=0; $row = $query->fetch(); $i++){
			$cod_evento = utf8_encode($row['cod_evento']);
			$cod_evento = explode(";",$cod_evento);
			
			$eventoAnterior = '';
			foreach($cod_evento as $cod){
				if($cod == '002'){
					if(!isset($tabelaEventosListados[$eventoAnterior])){
						$tabelaEventosListados[$eventoAnterior] = 1;
						array_push($listaEventosListados,$eventoAnterior);
					}
					else $tabelaEventosListados[$eventoAnterior] = $tabelaEventosListados[$eventoAnterior] + 1;
				}				
				$eventoAnterior = $cod;
			}
		}
		
		arsort($tabelaEventosListados);
		
		
		echo "<div class='divTabela'><table>";
			
		echo "<tr>";
			echo "<td>CÓDIGO</td>";
			echo "<td>EVENTO</td>";
			echo "<td>QUANTIDADE</td>";
		echo "</tr>";
		
		foreach($listaEventosListados as $cod){
			if($cod == '') continue;
			$quantidade = $tabelaEventosListados[$cod];
			$quantidade = number_format($quantidade, 0, ',', '.');
			
			$desc = $eventos_x_descricao[$cod];
			echo "<tr>";
				echo "<td>$cod</td>";
				echo "<td>$desc</td>";
				echo "<td>$quantidade</td>";
				
			echo "</tr>";
		}
			
		echo "</table></div>";
	}
	
?>