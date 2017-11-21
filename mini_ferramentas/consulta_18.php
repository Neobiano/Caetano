<?php
$nome_relatorio = "monitora_desconexoes_ura"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Monitora Desconexões URA"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//IMPRIME TÍTULO DA CONSULTA
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
echo "</div>";

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>CÓDIGO</b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td><b>EVENTO</b></td>";
	echo incrementa_tabela($texto);	
	
	$texto = "<td><b>QUANTIDADE</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$query = $pdo->prepare("select a.callid, b.cod_evento, b.desc_evento from tb_eventos_ura_2 as a
						inner join tb_eventos_novaura as b on a.cod_evento = b.cod_evento
						where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
						and callid in (select callid from tb_eventos_ura_2 where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and cod_evento = 57)
						order by callid, data_hora");
	$query->execute(); // EXECUTA A CONSULTA
	
	$callid_anterior = "";
	$cod_evento_anterior = "";
	$desc_evento_anterior = "";
	$ja_verificou = 0;
	$cod_desc = array();
	$eventos_precedentes = array();
	$eventos_x_quantidade = array();
	
	for($i=0; $row = $query->fetch(); $i++){
		$qtd_linhas_consulta++;
		$callid = utf8_encode($row['callid']);
		$cod_evento = utf8_encode($row['cod_evento']);
		$desc_evento = utf8_encode($row['desc_evento']);
	
		if($callid != $callid_anterior){
			$callid_anterior = $callid;
			$cod_evento_anterior = $cod_evento;
			$desc_evento_anterior = $desc_evento;
			continue;
		} else{
			if($cod_evento == 57){
				if(!isset($cod_desc[$cod_evento_anterior])) $cod_desc[$cod_evento_anterior] = $desc_evento_anterior;
				if(!isset($eventos_x_quantidade[$cod_evento_anterior])) $eventos_x_quantidade[$cod_evento_anterior] = 1;
				if(!in_array($cod_evento_anterior, $eventos_precedentes)) array_push($eventos_precedentes, $cod_evento_anterior);
				else $eventos_x_quantidade[$cod_evento_anterior]++;
			}
			$callid_anterior = $callid;
			$cod_evento_anterior = $cod_evento;
			$desc_evento_anterior = $desc_evento;
		}
	}
		
	// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
	foreach($eventos_precedentes as $cod_evento){
		$texto = '<tr>';
		echo incrementa_tabela($texto);
		
		$imprime = $cod_evento;
		$texto = "<td>$imprime</td>";
		echo incrementa_tabela($texto);
		
		$imprime = $cod_desc[$cod_evento];
		$texto = "<td>$imprime</td>";
		echo incrementa_tabela($texto);
		
		$imprime = $eventos_x_quantidade[$cod_evento];
		$texto = "<td>$imprime</td>";
		echo incrementa_tabela($texto);		
		
		$texto = '</tr></tbody>';
		echo incrementa_tabela($texto);
	}	
	$texto = '</tbody>';
	echo incrementa_tabela($texto);
	
	// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php";// IMPRIME O GRÁFICO
?>

<script>  
</script>