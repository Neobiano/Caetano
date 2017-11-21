<?php
// VERIFICA SE A DATA DO PERÍODO É INFERIOR À DATA ATUAL - INÍCIO
$data_final_definida = strtotime($data_final);
$data_final_definida = date('Y-m-d',$data_final_definida);

$data_inicial_definida = strtotime($data_inicial);
$data_inicial_definida = date('Y-m-d',$data_inicial_definida);

$data_atual = date('Y-m-d');

if((strtotime($data_final_definida)<strtotime($data_atual)) && (strtotime($data_inicial_definida)<strtotime($data_atual))){ // VERIFICA SE A DATA DO PERÍODO É INFERIOR À DATA ATUAL - FIM

$nome_relatorio = "percentual_transferencias_por_fila"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Percentual de Transferências - Por Fila / Ilha"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$SOMA_TOTAL_DE_ATENDIMENTOS = 0;
$SOMA_TMA = 0;

$UM_TOTAL_ATENDIMENTOS = 0;
$UM_TRANSF_EFETUADAS = 0;
$UM_TRANSF_RECEBIDAS = 0;
$UM_RECEBIDAS_URA = 0;

$num_pizza = 0;

// GERA RELACAO COD_FILA - DESC_FILA - INÍCIO
$query = $pdo->prepare("select * from tb_filas");
$query->execute();	
for($i=0; $row = $query->fetch(); $i++){
	$cod_fila = utf8_encode($row['cod_fila']);
		$cod_fila = number_format($cod_fila, 0, ',', '.');
	$desc_fila = utf8_encode($row['desc_fila']);
	$var = "fila_$cod_fila";
	$$var = $desc_fila;
}
// GERA RELACAO COD_FILA - DESC_FILA - FIM


// GERA RELAÇÃO DE TRANSFERÊNCIAS - INÍCIO *****
$CALLID_ANTERIOR = "";
$COD_FILA_DERIVA = "";
$COD_FILA_RECEBE = "";
$CALLID_ANTERIOR_2 = "";
$TRANSF_EFETUADAS = array();
$TRANSF_RECEBIDAS = array();

$query = $pdo->prepare("select a.callid CALLID, a.cod_fila COD_FILA, ROW_NUMBER() OVER(order by a.callid, a.data_hora asc) as NUM_LINHA from tb_eventos_dac as a
						inner join
						(
						select callid, count(*) TOTAL from tb_eventos_dac
						where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and tempo_atend > 0
						group by callid
						having count(*) > 1
						) as b on a.callid = b.callid
						where a.data_hora between '$data_inicial' and '$data_final 23:59:59.999' and a.tempo_atend > 0");
$query->execute();	

for($i=0; $row = $query->fetch(); $i++){
		
		$CALLID = utf8_encode($row['CALLID']);
		$COD_FILA = utf8_encode($row['COD_FILA']);
			$COD_FILA = number_format($COD_FILA, 0, ',', '.');
		$NUM_LINHA = utf8_encode($row['NUM_LINHA']);
		
		// TRATA $TRANSF_RECEBIDAS - INÍCIO
				if ($CALLID != $CALLID_ANTERIOR){ // ($CALLID != $CALLID_ANTERIOR)
						$COD_FILA_RECEBE = $COD_FILA;
				}
				ELSE { // ($CALLID == $CALLID_ANTERIOR)
					if(!isset($TRANSF_RECEBIDAS["$COD_FILA"]["$COD_FILA_DERIVA"])) $TRANSF_RECEBIDAS["$COD_FILA"]["$COD_FILA_DERIVA"] = 0;
					$TRANSF_RECEBIDAS["$COD_FILA"]["$COD_FILA_DERIVA"]++;
					$COD_FILA_RECEBE = $COD_FILA;
					// PIZZA - INÍCIO
					//$desc_ilha = $array_fila_ilha["$COD_FILA"]; 
					//$array_ilhas["$desc_ilha"]["RECEBIDAS"]++;
					// PIZZA - FIM
					
		}
		// TRATA $TRANSF_RECEBIDAS - FIM
		
		// TRATA $TRANSF_EFETUADAS - INÍCIO
				if ($CALLID != $CALLID_ANTERIOR){ // ($CALLID != $CALLID_ANTERIOR)
					$COD_FILA_DERIVA = $COD_FILA;
				}
				
				else { // ($CALLID == $CALLID_ANTERIOR)
					// PIZZA - INÍCIO
					//$desc_ilha = $array_fila_ilha["$COD_FILA_DERIVA"];
					//$array_ilhas["$desc_ilha"]["TRANSFERIDAS"]++;
					// PIZZA - FIM
					if(!isset($TRANSF_EFETUADAS["$COD_FILA_DERIVA"]["$COD_FILA"])) $TRANSF_EFETUADAS["$COD_FILA_DERIVA"]["$COD_FILA"] = 0;
					$TRANSF_EFETUADAS["$COD_FILA_DERIVA"]["$COD_FILA"]++;
					$COD_FILA_DERIVA = $COD_FILA;
				}
		// TRATA $TRANSF_EFETUADAS - FIM
		
		$CALLID_ANTERIOR = $CALLID;
}
// GERA RELAÇÃO DE TRANSFERÊNCIAS - FIM *****

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>CÓDIGO DA FILA</b></td>";
	echo incrementa_tabela($texto);	
	
	$texto = "<td><b>NOME DA FILA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TOTAL DE ATENDIMENTOS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TRANSFERÊNCIAS EFETUADAS</b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td><b>TRANSFERÊNCIAS EFETUADAS(%)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TRANSFERÊNCIAS RECEBIDAS</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>TRANSFERÊNCIAS RECEBIDAS(%)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>RECEBIDAS DA URA</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>RECEBIDAS DA URA(%)</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$query = $pdo->prepare("select E.cod_fila E_COD_FILA, fi.desc_fila R_COD_FILA, fi.desc_fila E_DESC_FILA, R.desc_fila R_DESC_FILA, E.TOTAL_LIGACOES E_TOTAL_LIGACOES, R.TOTAL_LIGACOES R_TOTAL_LIGACOES, E.TRANSF_PARA_OUTRAS_FILAS, R.RECEBIDAS_DE_TRANSF, E.PERC_TRANSF PERC_TRANSF_PARA_OUTRAS_FILAS, R.PERC_TRANSF PERC_RECEBIDAS_DE_TRANSF from
(
-- PERCENTUAL DE TRANSFERÊNCIAS PARA OUTRAS FILAS
SELECT M.cod_fila, F.desc_fila, TOTAL_TRANSF TRANSF_PARA_OUTRAS_FILAS, TOTAL_LIGACOES, cast(TOTAL_TRANSF as float)/cast(TOTAL_LIGACOES as float)*100 PERC_TRANSF FROM
(
select cod_fila, count(*) TOTAL_TRANSF from
(
select * from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0 and callid in (select CALLID_TRANSF from
(
select callid as CALLID_TRANSF, count(*) TOTAL from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0
group by callid
having count(*) > 1
) as T)
) as O
left join
(
select callid, max(data_hora) data_hora from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0 and callid in (select CALLID_TRANSF from
(
select callid as CALLID_TRANSF, count(*) TOTAL from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0
group by callid
having count(*) > 1
) as T)
group by callid
) as P
on (O.callid = P.callid and O.data_hora = P.data_hora)
where P.callid is NULL and P.data_hora is NULL
group by cod_fila
) as N
full outer join
(
select cod_fila, count (*) TOTAL_LIGACOES from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0
group by cod_fila
) as M
on N.cod_fila = M.cod_fila
left join tb_filas as F
on N.cod_fila = F.cod_fila
) as E

inner JOIN
(
-- PERCENTUAL DE RECEBIDAS DE TRANSFERÊNCIAS
SELECT M.cod_fila, F.desc_fila, TOTAL_TRANSF RECEBIDAS_DE_TRANSF, TOTAL_LIGACOES, cast(TOTAL_TRANSF as float)/cast(TOTAL_LIGACOES as float)*100 PERC_TRANSF FROM
(
select cod_fila, count(*) TOTAL_TRANSF from
(
select * from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0 and callid in (select CALLID_TRANSF from
(
select callid as CALLID_TRANSF, count(*) TOTAL from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0
group by callid
having count(*) > 1
) as T)
) as O
left join
(
select callid, min(data_hora) data_hora from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0 and callid in (select CALLID_TRANSF from
(
select callid as CALLID_TRANSF, count(*) TOTAL from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0
group by callid
having count(*) > 1
) as T)
group by callid
) as P
on (O.callid = P.callid and O.data_hora = P.data_hora)
where P.callid is NULL and P.data_hora is NULL
group by cod_fila
) as N
full outer join
(
select cod_fila, count (*) TOTAL_LIGACOES from tb_eventos_dac
where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and datepart(dw,data_hora) in $in_semana and tempo_atend > 0
group by cod_fila
) as M
on N.cod_fila = M.cod_fila
left join tb_filas as F
on N.cod_fila = F.cod_fila
) as R
on E.cod_fila = R.cod_fila
inner join
(select * from tb_filas where desc_fila like 'CXA%') as fi
on E.cod_fila = fi.cod_fila");
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO	
		$E_COD_FILA = utf8_encode($row['E_COD_FILA']);
		$R_COD_FILA = utf8_encode($row['R_COD_FILA']);		
			if($E_COD_FILA == "") $COD_FILA = $R_COD_FILA; // $COD_FILA
			else $COD_FILA = $E_COD_FILA;
		
		$E_DESC_FILA = utf8_encode($row['E_DESC_FILA']);
		$R_DESC_FILA = utf8_encode($row['R_DESC_FILA']);		
			if($E_DESC_FILA == "") $DESC_FILA = $R_DESC_FILA; // $DESC_FILA
			else $DESC_FILA = $E_DESC_FILA;
			
			$valida_fila = substr($DESC_FILA, 0, 3);
			if($valida_fila != "CXA") continue;
			
		$E_TOTAL_LIGACOES = utf8_encode($row['E_TOTAL_LIGACOES']);
		$R_TOTAL_LIGACOES = utf8_encode($row['R_TOTAL_LIGACOES']);		
			if($E_TOTAL_LIGACOES == "") $TOTAL_LIGACOES = $R_TOTAL_LIGACOES; // $TOTAL_LIGACOES
			else $TOTAL_LIGACOES = $E_TOTAL_LIGACOES;
			
			$UM_TOTAL_ATENDIMENTOS = $UM_TOTAL_ATENDIMENTOS + $TOTAL_LIGACOES;
		
		$TRANSF_PARA_OUTRAS_FILAS = utf8_encode($row['TRANSF_PARA_OUTRAS_FILAS']);
			if($TRANSF_PARA_OUTRAS_FILAS == "") $TRANSF_PARA_OUTRAS_FILAS = 0; // $TRANSF_PARA_OUTRAS_FILAS
			
			$UM_TRANSF_EFETUADAS = $UM_TRANSF_EFETUADAS + $TRANSF_PARA_OUTRAS_FILAS;
			
		$PERC_TRANSF_PARA_OUTRAS_FILAS = utf8_encode($row['PERC_TRANSF_PARA_OUTRAS_FILAS']);
			if($PERC_TRANSF_PARA_OUTRAS_FILAS == "") $PERC_TRANSF_PARA_OUTRAS_FILAS = 0; // $PERC_TRANSF_PARA_OUTRAS_FILAS
			
		$RECEBIDAS_DE_TRANSF = utf8_encode($row['RECEBIDAS_DE_TRANSF']);
			if($RECEBIDAS_DE_TRANSF == "") $RECEBIDAS_DE_TRANSF = 0; // $RECEBIDAS_DE_TRANSF
			
			$UM_TRANSF_RECEBIDAS = $UM_TRANSF_RECEBIDAS + $RECEBIDAS_DE_TRANSF;
			
		$PERC_RECEBIDAS_DE_TRANSF = utf8_encode($row['PERC_RECEBIDAS_DE_TRANSF']);
			if($PERC_RECEBIDAS_DE_TRANSF == "") $PERC_RECEBIDAS_DE_TRANSF = 0; // $PERC_RECEBIDAS_DE_TRANSF
			
		$RECEBIDAS_DA_URA = $TOTAL_LIGACOES - $RECEBIDAS_DE_TRANSF; // $RECEBIDAS_DA_URA
		
		$UM_RECEBIDAS_URA = $UM_RECEBIDAS_URA + $RECEBIDAS_DA_URA;
		
		$PERC_RECEBIDAS_DA_URA = 100 - $PERC_RECEBIDAS_DE_TRANSF; // $PERC_RECEBIDAS_DA_URA
		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		
		/*
		// PIZZA - INÍCIO
		$COD_FILA = number_format($COD_FILA, 0, ',', '.');
		$desc_ilha = $array_fila_ilha["$COD_FILA"];
		$array_ilhas["$desc_ilha"]["TOTAL_LIGACOES"] = $array_ilhas["$desc_ilha"]["TOTAL_LIGACOES"] + $TOTAL_LIGACOES;
		$array_ilhas["$desc_ilha"]["RECEBIDAS_URA"] = $array_ilhas["$desc_ilha"]["RECEBIDAS_URA"] + $RECEBIDAS_DA_URA;
		$array_ilhas["$desc_ilha"]["RECEBIDAS"] = $array_ilhas["$desc_ilha"]["RECEBIDAS"] + $RECEBIDAS_DE_TRANSF;
		$array_ilhas["$desc_ilha"]["TRANSFERIDAS"] = $array_ilhas["$desc_ilha"]["TRANSFERIDAS"] + $TRANSF_PARA_OUTRAS_FILAS;
		// PIZZA - FIM
		*/
		
		$texto = '<tr>';
		echo incrementa_tabela($texto);
			
			$COD_FILA = number_format($COD_FILA, 0, ',', '.');
			$texto = "<td>$COD_FILA</td>";
			echo incrementa_tabela($texto);
			
			$texto = "<td>$DESC_FILA</td>";
			echo incrementa_tabela($texto);
			
			
			$TOTAL_LIGACOES = number_format($TOTAL_LIGACOES, 0, ',', '.');
			$texto = "<td>$TOTAL_LIGACOES</td>";
			echo incrementa_tabela($texto);
			
			$PERC_TRANSF_PARA_OUTRAS_FILAS = number_format($PERC_TRANSF_PARA_OUTRAS_FILAS, 2, ',', '.');
			
			$imp_modal = $TRANSF_PARA_OUTRAS_FILAS;
			if($TRANSF_PARA_OUTRAS_FILAS > 0){
				$TRANSF_PARA_OUTRAS_FILAS = number_format($TRANSF_PARA_OUTRAS_FILAS, 0, ',', '.');
				echo "<td onclick=\"document.getElementById('modal_$COD_FILA').style.display='block'\" style='cursor:pointer;'><b><u class='w3-text-indigo'>$TRANSF_PARA_OUTRAS_FILAS</u></b></td>";
				
				echo "
				<div id='modal_$COD_FILA' class='w3-modal'>
					<div class='w3-modal-content w3-card-4 w3-center w3-round'>
						<header class='w3-container w3-indigo w3-padding'>
							<span onclick=\"document.getElementById('modal_$COD_FILA').style.display='none'\" class=\"w3-button w3-display-topright 	w3-large\" style=\"margin-top:4px;margin-right:10px;cursor:pointer\">&times;</span>
							<b class='w3-small'>TRANSFERÊNCIAS EFETUADAS - Fila $COD_FILA ($DESC_FILA)</b>
						</header>
					<div class='w3-container w3-white w3-tiny' style='padding:20px;'>
					<p>TOTAL DE ATENDIMENTOS: $TOTAL_LIGACOES</p>
					<p>TRANSFERÊNCIAS EFETUADAS: $TRANSF_PARA_OUTRAS_FILAS ($PERC_TRANSF_PARA_OUTRAS_FILAS%)</p>
					<p><b class='w3-text-indigo'>FILA X QUANTIDADE</b></p>
				";
				for($x=0;$x<=150;$x++){
					if(isset($TRANSF_EFETUADAS["$COD_FILA"]["$x"])){
						$var = "fila_$x";
						$nome_da_fila = $$var;
						$valor = $TRANSF_EFETUADAS["$COD_FILA"]["$x"];
						$percen = $valor/$imp_modal*100;
						$percen = number_format($percen, 2, ',', '.');
						$valor_imprimir = number_format($valor, 0, ',', '.');
						echo "<p>$x - $nome_da_fila: <b class='w3-text-indigo'>$valor</b> ($percen%)</p>";
					}
				}				
				echo "
					</div>
				</div>
				";
			}	
			else{
				$TRANSF_PARA_OUTRAS_FILAS = number_format($TRANSF_PARA_OUTRAS_FILAS, 0, ',', '.');
				$texto = "<td><b><u class='w3-text-indigo'>$TRANSF_PARA_OUTRAS_FILAS</u></b></td>";
				echo incrementa_tabela($texto);				
			}
			
			$texto = "<td>$PERC_TRANSF_PARA_OUTRAS_FILAS%</td>";
			echo incrementa_tabela($texto);

			$PERC_RECEBIDAS_DE_TRANSF = number_format($PERC_RECEBIDAS_DE_TRANSF, 2, ',', '.');
	
			$imp_modal = $RECEBIDAS_DE_TRANSF;
			if($RECEBIDAS_DE_TRANSF > 0){
				$RECEBIDAS_DE_TRANSF = number_format($RECEBIDAS_DE_TRANSF, 0, ',', '.');
				echo "<td onclick=\"document.getElementById('modal_r_$COD_FILA').style.display='block'\" style='cursor:pointer;'><b><u class='w3-text-indigo'>$RECEBIDAS_DE_TRANSF</u></b></td>";
				
				echo "
				<div id='modal_r_$COD_FILA' class='w3-modal'>
					<div class='w3-modal-content w3-card-4 w3-center w3-round'>
						<header class='w3-container w3-indigo w3-padding'>
							<span onclick=\"document.getElementById('modal_r_$COD_FILA').style.display='none'\" class=\"w3-button w3-display-topright 	w3-large\" style=\"margin-top:4px;margin-right:10px;cursor:pointer\">&times;</span>
							<b class='w3-small'>TRANSFERÊNCIAS RECEBIDAS - Fila $COD_FILA ($DESC_FILA)</b>
						</header>
					<div class='w3-container w3-white w3-tiny' style='padding:20px;'>
					<p>TOTAL DE ATENDIMENTOS: $TOTAL_LIGACOES</p>
					<p>TRANSFERÊNCIAS RECEBIDAS: $RECEBIDAS_DE_TRANSF ($PERC_RECEBIDAS_DE_TRANSF%)</p>
					<p><b class='w3-text-indigo'>FILA X QUANTIDADE</b></p>
				";
				for($x=0;$x<=150;$x++){
					if(isset($TRANSF_RECEBIDAS["$COD_FILA"]["$x"])){
						$var = "fila_$x";
						$nome_da_fila = $$var;
						$valor = $TRANSF_RECEBIDAS["$COD_FILA"]["$x"];
						$percen = $valor/$imp_modal*100;
						$percen = number_format($percen, 2, ',', '.');
						$valor_imprimir = number_format($valor, 0, ',', '.');
						echo "<p>$x - $nome_da_fila: <b class='w3-text-indigo'>$valor</b> ($percen%)</p>";
					}
				}				
				echo "
					</div>
				</div>
				";
			}	
			else{
				$RECEBIDAS_DE_TRANSF = number_format($RECEBIDAS_DE_TRANSF, 0, ',', '.');
				$texto = "<td><b><u class='w3-text-indigo'>$RECEBIDAS_DE_TRANSF</u></b></td>";
				echo incrementa_tabela($texto);				
			}
			
			$texto = "<td>$PERC_RECEBIDAS_DE_TRANSF%</td>";
			echo incrementa_tabela($texto);
			
			$RECEBIDAS_DA_URA = number_format($RECEBIDAS_DA_URA, 0, ',', '.');
			$texto = "<td>$RECEBIDAS_DA_URA</td>";
			echo incrementa_tabela($texto);
			
			$PERC_RECEBIDAS_DA_URA = number_format($PERC_RECEBIDAS_DA_URA, 2, ',', '.');
			$texto = "<td>$PERC_RECEBIDAS_DA_URA%</td>";
			echo incrementa_tabela($texto);

		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM

$texto = "</tbody>";
echo incrementa_tabela($texto);

// IMPRIME <TR> FINALIZADORA - INÍCIO

$texto = "<tr class='w3-indigo'>";
echo incrementa_tabela($texto);

	$texto = "<td><b></b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);	
	
	$imprime = $UM_TOTAL_ATENDIMENTOS;
	$imprime = number_format($imprime, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = $UM_TRANSF_EFETUADAS;
	$imprime = number_format($imprime, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = $UM_TRANSF_EFETUADAS / $UM_TOTAL_ATENDIMENTOS * 100;
	$imprime = number_format($imprime, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = $UM_TRANSF_RECEBIDAS;
	$imprime = number_format($imprime, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = $UM_TRANSF_RECEBIDAS / $UM_TOTAL_ATENDIMENTOS * 100;
	$imprime = number_format($imprime, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = $UM_RECEBIDAS_URA;
	$imprime = number_format($imprime, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	$imprime = $UM_RECEBIDAS_URA / $UM_TOTAL_ATENDIMENTOS * 100;
	$imprime = number_format($imprime, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);

$texto = "</tr>";
echo incrementa_tabela($texto);

// IMPRIME <TR> FINALIZADORA - FIM

$texto = "</table>";
echo incrementa_tabela($texto);

include "finaliza_tabela.php"; // FINALIZA A TABELA
// include"imprime_grafico.php";// IMPRIME O GRÁFICO
} else echo "<div class = 'w3-container w3-center w3-margin w3-padding w3-tiny w3-deep-orange w3-card-4'><b>O período de consulta deve ser inferior à data atual.</b></div>";
?>

<script>  
$('#tabela').DataTable( {
	"order": [[ 1, "asc" ]],
	 "iDisplayLength": -1,
	 "columnDefs": [ {
      "targets": [ 2, 3, 5, 7 ],
      "orderable": false
    } ]
} );
</script>

<script>  
$('#tabela2').DataTable( {
	"order": [[ 0, "asc" ]],
	 "iDisplayLength": -1,
	 "columnDefs": [ {
      "targets": [ 1, 2, 4, 6 ],
      "orderable": false
    } ]
} );
</script>