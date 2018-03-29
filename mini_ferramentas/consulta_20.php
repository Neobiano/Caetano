<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

</head>
<body>

<?php
$nome_relatorio = "monitora_desb_cartao_ura"; // NOME DO RELATÓRIO (UTILIZAR UNDERLINE, POIS É PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Monitora Desbloqueio de Cartão via URA"; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
include "inicia_variaveis_grafico.php";

//VARIÁVEIS TOTALIZADORAS
$TOTAL_ACESSOU_OP_DESB = 0;
$TOTAL_ACESSOU_OP_DESB_E_CARTAO_BLOQ = 0;
$TOTAL_DESB_VIA_URA = 0;
$TOTAL_ACESSOU_OP_DESB_ERROU_IDPOS = 0;
$TOTAL_CARTAO_BLOQ_N_AC_DESB = 0;

	//IMPRIME TÍTULO DA CONSULTA
	echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
	echo '</div>';

	include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
	include "inicia_tabela_organizada.php"; // INICIA A TABELA
	
	// IMPRIME COLUNAS DA TABELA - INÍCIO
	$texto = "<td><b>DATA &nbsp</b></td>";
	echo incrementa_tabela($texto);

	$texto = "<td><b>ACESSOU OPÇÃO DESBLOQUEIO &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ACESSOU OPÇÃO DESBLOQUEIO E CARTÃO ESTAVA BLOQUEADO &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>DESBLOQUEIOS VIA URA &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>ACESSOU OPÇÃO DESBLOQUEIO E ERROU IDPOS &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b>CARTÃO BLOQUEADO QUE NÃO ACESSOU MENU DESBLOQUEIO &nbsp</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "</tr></thead><tbody>";
	echo incrementa_tabela($texto);
	// IMPRIME COLUNAS DA TABELA - FIM
	
	echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
	
	// INFORMA A CONSULTA
	$query = $pdo->prepare("select a.DATA, acessou_op_desb, acessou_op_desb_e_cartao_bloq, desb_via_ura, acessou_op_desb_errou_idpos, cartao_bloq_n_ac_desb from

							-- Acessou Opção Desbloqueio
							(select convert(date,data_hora,11) DATA, count(*) acessou_op_desb from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and cod_evento like '%005%'
							group by convert(date,data_hora,11)) as a

							inner join
							-- Acessou Opção Desbloqueio e Cartão Estava Bloqueado
							(select convert(date,data_hora,11) DATA, count(*) acessou_op_desb_e_cartao_bloq from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and cod_evento like '%005%016%'
							group by convert(date,data_hora,11)) as b on a.DATA = b.DATA

							inner join
							-- Cartões Desbloqueados via URA
							(select convert(date,data_hora,11) DATA, count(*) desb_via_ura from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and cod_evento like '%020%'
							group by convert(date,data_hora,11)) as c on a.DATA = c.DATA

							inner join
							-- Acessou Opção Desbloqueio e Errou IDPOS
							(select convert(date,data_hora,11) DATA, count(*) acessou_op_desb_errou_idpos from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and (cod_evento like '%005%016%090;002%' or cod_evento like '%005%016%091;002%')
							group by convert(date,data_hora,11)) as d on a.DATA = d.DATA

							inner join
							-- Cartão Bloquedo Porém Não Acessou Menu Desbloqueio
							(select convert(date,data_hora,11) DATA, count(*) cartao_bloq_n_ac_desb from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' and cod_evento like '%016%' and cod_evento not like '%005%'
							group by convert(date,data_hora,11)) as e on a.DATA = e.DATA

							order by DATA");
	$query->execute(); // EXECUTA A CONSULTA
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++){
		$var_graf = 0; // VARIÁVEL UTILIZADA PARA VERIFICAR SE JÁ FOI INCLUÍDO ALGUM DADO NO $incrementa_grafico PARA A LINHA ATUAL DO RESULTADO DA CONSULTA
		$qtd_linhas_consulta++; // INCREMENTA QUANTIDADE DE LINHAS DA TABELA
		
		// RECEBE RESULTADOS DA CONSULTA - INÍCIO
		$DATA = utf8_encode($row['DATA']);
		
		$acessou_op_desb = utf8_encode($row['acessou_op_desb']);
		$TOTAL_ACESSOU_OP_DESB = $TOTAL_ACESSOU_OP_DESB + $acessou_op_desb;
		
		$acessou_op_desb_e_cartao_bloq = utf8_encode($row['acessou_op_desb_e_cartao_bloq']);
		$TOTAL_ACESSOU_OP_DESB_E_CARTAO_BLOQ = $TOTAL_ACESSOU_OP_DESB_E_CARTAO_BLOQ + $acessou_op_desb_e_cartao_bloq;
		
		$desb_via_ura = utf8_encode($row['desb_via_ura']);
		$TOTAL_DESB_VIA_URA = $TOTAL_DESB_VIA_URA + $desb_via_ura;
		
		$acessou_op_desb_errou_idpos = utf8_encode($row['acessou_op_desb_errou_idpos']);
		$TOTAL_ACESSOU_OP_DESB_ERROU_IDPOS = $TOTAL_ACESSOU_OP_DESB_ERROU_IDPOS + $acessou_op_desb_errou_idpos;
		
		$cartao_bloq_n_ac_desb = utf8_encode($row['cartao_bloq_n_ac_desb']);
		$TOTAL_CARTAO_BLOQ_N_AC_DESB = $TOTAL_CARTAO_BLOQ_N_AC_DESB + $cartao_bloq_n_ac_desb;

		// RECEBE RESULTADOS DA CONSULTA - FIM
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - INÍCIO
		$texto = '<tr>';
		echo incrementa_tabela($texto);
		
			$DATA = date("d-m-Y", strtotime($DATA));   			
			$texto = "<td>$DATA</td>";
			echo incrementa_tabela($texto);
			
			$acessou_op_desb = number_format($acessou_op_desb, 0, ',', '.');
			$texto = "<td>$acessou_op_desb</td>";
			echo incrementa_tabela($texto);
			
			$acessou_op_desb_e_cartao_bloq = number_format($acessou_op_desb_e_cartao_bloq, 0, ',', '.');
			$texto = "<td>$acessou_op_desb_e_cartao_bloq</td>";
			echo incrementa_tabela($texto);
			
			$desb_via_ura = number_format($desb_via_ura, 0, ',', '.');
			$texto = "<td>$desb_via_ura</td>";
			echo incrementa_tabela($texto);
			
			$acessou_op_desb_errou_idpos = number_format($acessou_op_desb_errou_idpos, 0, ',', '.');
			$texto = "<td>$acessou_op_desb_errou_idpos</td>";
			echo incrementa_tabela($texto);
			
			$cartao_bloq_n_ac_desb = number_format($cartao_bloq_n_ac_desb, 0, ',', '.');
			$texto = "<td>$cartao_bloq_n_ac_desb</td>";
			echo incrementa_tabela($texto);
			
		$texto = '</tr>';
		echo incrementa_tabela($texto);
		
		// IMPRIME O RESULTADO DA LINHA DA CONSULTA NA TABELA - FIM		
	}
	// IMPRIME O RESULTADO DA CONSULTA - FIM

// IMPRIME <TR> FINALIZADORA - INÍCIO

echo "</tbody><tr class='w3-indigo'>";
$tabela = $tabela."<tr>";
	
	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$TOTAL_ACESSOU_OP_DESB = number_format($TOTAL_ACESSOU_OP_DESB, 0, ',', '.');
	$texto = "<td>$TOTAL_ACESSOU_OP_DESB</td>";
	echo incrementa_tabela($texto);
	
	$TOTAL_ACESSOU_OP_DESB_E_CARTAO_BLOQ = number_format($TOTAL_ACESSOU_OP_DESB_E_CARTAO_BLOQ, 0, ',', '.');
	$texto = "<td>$TOTAL_ACESSOU_OP_DESB_E_CARTAO_BLOQ</td>";
	echo incrementa_tabela($texto);
	
	$TOTAL_DESB_VIA_URA = number_format($TOTAL_DESB_VIA_URA, 0, ',', '.');
	$texto = "<td>$TOTAL_DESB_VIA_URA</td>";
	echo incrementa_tabela($texto);
	
	$TOTAL_ACESSOU_OP_DESB_ERROU_IDPOS = number_format($TOTAL_ACESSOU_OP_DESB_ERROU_IDPOS, 0, ',', '.');
	$texto = "<td>$TOTAL_ACESSOU_OP_DESB_ERROU_IDPOS</td>";
	echo incrementa_tabela($texto);
	
	$TOTAL_CARTAO_BLOQ_N_AC_DESB = number_format($TOTAL_CARTAO_BLOQ_N_AC_DESB, 0, ',', '.');
	$texto = "<td>$TOTAL_CARTAO_BLOQ_N_AC_DESB</td>";
	echo incrementa_tabela($texto);
	
$texto = "</tr>";
echo incrementa_tabela($texto);
// IMPRIME <TR> FINALIZADORA - FIM
	
include "finaliza_tabela.php"; // FINALIZA A TABELA
//include"imprime_grafico.php"; // IMPRIME O GRÁFICO
?>

</body>
</html>

<script>  
$('#tabela').DataTable( {
	"order": [[ 0, "asc" ]]
} );
</script>