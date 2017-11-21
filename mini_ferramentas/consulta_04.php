<?php
$nome_relatorio = "ligacoes_multitransferencias"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Ligações Multitransferências"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$TOTAL_SEM_TRANSFERENCIA = 0;
$TOTAL_COM_TRANSFERENCIA = 0;
$PERCENTUAL_TOTAL = 0;

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b><i>Data Inicial:</i></b> $data_inicial_texto";
	echo "<br><b><i>Data Final:</i></b> $data_final_texto";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	
	echo "<div class='w3-container w3-margin-8 w3-small w3-center'><b class='w3-text-red'>TABELA 1<i class='w3-text-red'> - Detalhes das Ligações Multitransferências</i></b></div>";	
	echo "<table id='tabela1' name='tabela1' class='w3-table w3-tiny w3-striped w3-hoverable'>";	
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<tr class='w3-indigo'><td><b>CALLID</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>DATA/HORA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>FILA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>DESCRIÇÃO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TEMPO DE ESPERA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TEMPO DE ATENDIMENTO</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ID OPERADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>NOME OPERADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$query = $pdo->prepare("select a.callid, a.data_hora, a.cod_fila, c.desc_fila, a.tempo_espera, a.tempo_atend, a.id_operador, a.desc_operador, b.TOTAL from
							(
								select * from tb_eventos_dac
								where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0
							) as a
							inner join
							(
								select callid CALLID, count(*) TOTAL from tb_eventos_dac
								where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0
								group by callid
								having count(*) >= $qtd_transf+1
							) as b on a.callid = b.callid
							inner join tb_filas as c on a.cod_fila = c.cod_fila
							order by TOTAL desc, a.callid, a.data_hora");
	$query->execute(); // EXECUTA A CONSULTA
	
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	$callid_anterior = "";
	$set_max_transf = 0;
	$tabela_transf = array();
	$pos_transf = 0;
	$tabela_todos_callid = array();
	$tabela_qtd_transf = array();
	$var_nome = 0;
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO		
		$callid = utf8_encode($row['callid']);
		$data_hora = utf8_encode($row['data_hora']);
		$cod_fila = utf8_encode($row['cod_fila']);
		$cod_fila = number_format($cod_fila, 0, ',', '.');
		$desc_fila = utf8_encode($row['desc_fila']);
		$tempo_espera = utf8_encode($row['tempo_espera']);
		$tempo_atend = utf8_encode($row['tempo_atend']);
		$id_operador = utf8_encode($row['id_operador']);
		$desc_operador = utf8_encode($row['desc_operador']);
		$TOTAL = utf8_encode($row['TOTAL']);		
		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		if($callid != $callid_anterior){
			$var_nome++;
			$qtd_transf = $TOTAL - 1;
			if (isset($tabela_qtd_transf[$qtd_transf])) $tabela_qtd_transf[$qtd_transf]++;
			else $tabela_qtd_transf[$qtd_transf] = 1;
					
			if($set_max_transf == 0) $set_max_transf = $TOTAL;
			$pos_transf = 0;
			$tabela_transf[$callid] = array();
			array_push($tabela_todos_callid, $callid);
			
			$texto = "<tr onclick=\"$('.u_$var_nome').toggle();\" style='background-color: #333; color: white; cursor: pointer;' class='uu'>";
	
			echo incrementa_tabela($texto);
			
				$texto = "<td><b>$callid</b></td>";
				echo incrementa_tabela($texto);
				
				$texto = "<td></td>";
				echo incrementa_tabela($texto);
				
				$texto = "<td></td>";
				echo incrementa_tabela($texto);
				
				$texto = "<td></td>";
				echo incrementa_tabela($texto);				
				
				$texto = "<td></td>";
				echo incrementa_tabela($texto);
				
				$texto = "<td></td>";
				echo incrementa_tabela($texto);
				
				$texto = "<td></td>";
				echo incrementa_tabela($texto);
				
				$TOTAL_IMPRIME = $TOTAL-1;
				$texto = "<td><b>TRANSFERÊNCIAS: $TOTAL_IMPRIME</b></td>";
				echo incrementa_tabela($texto);
			
			$texto = "</tr>";
			echo incrementa_tabela($texto);
		}
		
			$texto = "<tr class='u_$var_nome' style='display: none;'>";
			echo incrementa_tabela($texto);
						
			$texto = "<td>$callid</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$data_hora</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$cod_fila</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$desc_fila</td>";
			echo incrementa_tabela($texto);
			
			array_push($tabela_transf[$callid], $desc_fila);
			
			$texto = "<td>$tempo_espera</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$tempo_atend</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$id_operador</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$desc_operador</td>";
			echo incrementa_tabela($texto);
			
		$texto = '</tr>';
		echo incrementa_tabela($texto);		
		
		$callid_anterior = $callid;
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM
	
echo "</tbody>";
include "finaliza_tabela.php"; // FINALIZA A TABELA

// TABELA 2 - INÍCIO
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important;">';
echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";

echo "<div class='w3-container w3-margin w3-small w3-center'><b class='w3-text-red'>TABELA 2<i class='w3-text-red'> - (Compilado: NÚMERO DE TRANSFERÊNCIAS x QUANTIDADE)</i></b></div>";

echo "<tr class='w3-indigo'>";
echo "<td><b>NÚMERO DE TRANSFERÊNCIAS</b></td>";

echo "<td><b>QUANTIDADE</b></td>";
echo "</tr>";

$u = $set_max_transf;

for($u;$u>0;$u--){
	if(isset($tabela_qtd_transf[$u])){
		echo "<tr>";
		echo "<td>$u</td>";
		echo "<td>$tabela_qtd_transf[$u]</td>";
		echo "</tr>";
	}
}

echo '</table>';
echo '</div>';
// TABELA 2 - FIM

// TABELA 3 - INÍCIO
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important;">';
echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";

	
	echo "<div class='w3-container w3-margin w3-small w3-center'><b class='w3-text-red'>TABELA 3<i class='w3-text-red'> - (Compilado: CALLID x TRANSFERÊNCIAS)</i></b></div>";

	echo "<tr class='w3-indigo'>";
		echo "<td><b>CALLID</b></td>";
		
		echo "<td><b>FILA DE ORIGEM</b></td>";
		
		for($pos=1;$pos<$set_max_transf;$pos++){
			echo "<td><b>$pos ª TRANSF.</b></td>";
		}		
	echo "</tr>";
	
	foreach($tabela_todos_callid as $callid){
		echo "<tr>";
		
			echo "<td>$callid</td>";
			
			for($y=0;$y<$set_max_transf;$y++){
				if(isset($tabela_transf[$callid][$y])) $imprime = $tabela_transf[$callid][$y];
				else $imprime = "";
				echo "<td>$imprime</td>";
			}
			
		echo "</tr>";
	}

echo '</table>';
echo '</div>';
// TABELA 3 - FIM

/*
// TABELA 4 - INÍCIO

echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important;">';
echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";
echo "<div class='w3-container w3-margin w3-small w3-center'><b class='w3-text-red'>TABELA 3<i class='w3-text-red'> - (Compilado: CALLID x TRANSFERÊNCIAS)</i></b></div>";

echo "<tr class='w3-indigo'>";
	echo "<td><b>DIA</b></td>";
	
	for($pos=$qtd_transf;$pos<$set_max_transf;$pos++){
		echo "<td><b>$pos TRANSF.</b></td>";
	}

$DIA_ANTERIOR = "";

$query = $pdo->prepare("select DIA, TOTAL NUMERO_TRANSF, count (CALLID) QUANTIDADE from
						(
						select CONVERT (VARCHAR, CONVERT(DATETIME, data_hora, 103), 105) as DIA, callid CALLID, count(*) TOTAL from tb_eventos_dac
						where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0
						group by CONVERT (VARCHAR, CONVERT(DATETIME, data_hora, 103), 105), callid
						having count(*) >= $qtd_transf+1
						) as a
						group by DIA, TOTAL
						order by DIA asc, TOTAL asc");
$query->execute();
$pos_td = $qtd_transf;

for($i=0; $row = $query->fetch(); $i++){
	$DIA = utf8_encode($row['DIA']);
	$NUMERO_TRANSF = utf8_encode($row['NUMERO_TRANSF']);
	$QUANTIDADE = utf8_encode($row['QUANTIDADE']);
	
	if($DIA != $DIA_ANTERIOR){
		
		if ($pos_td > $qtd_transf){
			for ($pos_td;$pos_td<=$set_max_transf;$pos_td++){
				echo "<td></td>";
			}
		}
	
		
		$pos_c = $qtd_transf;
		echo "</tr><tr>";
		echo "<td>$DIA</td>";
		for ($pos_td;$pos_td<$NUMERO_TRANSF-2;$pos_td++){
			echo "<td></td>";
		}
		echo "<td>$QUANTIDADE</td>";
		
	}
	else{
		
		for ($pos_td;$pos_td<$NUMERO_TRANSF-2;$pos_td++){
			echo "<td></td>";
		}
		echo "<td>$QUANTIDADE</td>";		
	}
	
	$DIA_ANTERIOR = $DIA;
}

if ($pos_td > $qtd_transf){
	for ($pos_td;$pos_td<=$set_max_transf;$pos_td++){
		echo "<td></td>";
	}
}
echo "</tr>";

echo '</table>';
echo '</div>';
// TABELA 4 - FIM
 */
?>