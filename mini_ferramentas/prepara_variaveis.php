<?php
//Recebe Variáveis do Formulário - Início
$data_inicial = $_POST['data_inicial'];
$data_final = $_POST['data_final'];

$data_inicial1 = $_POST['data_inicial1'];
$data_final1 = $_POST['data_final1'];

$data_inicial2 = $_POST['data_inicial2'];
$data_final2 = $_POST['data_final2'];

$tipo_consulta = $_POST['tipo_consulta'];
$select_dias_semana = $_POST['select_dias_semana'];
$qtd_transf = $_POST['qtd_transf'];
$select_operador_supervisor = $_POST['select_operador_supervisor'];
$select_fonte = $_POST['select_fonte'];
$codigo_evento = $_POST['codigo_evento'];
$codigo_evento = strtoupper($codigo_evento);
$codigo_evento = str_replace(" ","",$codigo_evento);
$select_intervalo = $_POST['select_intervalo'];
$select_filas = $_POST['select_filas'];
$qual_mes = $_POST['qual_mes'];
$qual_ano = $_POST['qual_ano'];
$qual_ano = $_POST['qual_ano'];
$qual_rechamadas = $_POST['qual_rechamadas'];
$select_ilhas = $_POST['select_ilhas'];
$select_origem_reicidencia = $_POST['select_origem_reicidencia']; //AQUI MEU FI
$pesq_satisf_perg1 = $_POST['perg1'];
$pesq_satisf_perg2 = $_POST['perg2'];
$pesq_satisf_perg3 = $_POST['perg3'];
$pesq_satisf_perg4 = $_POST['perg4'];

$in_filas = $_POST['in_filas'];
$in_filas = str_replace(" ","",$in_filas);

$dmm = $_POST['dmm'];
$dmm = str_replace(" ","",$dmm);

$dias_excluir = $_POST['dias_excluir'];
$dias_excluir = str_replace(" ","",$dias_excluir);

$codigo_evento = str_replace(" ","",$codigo_evento);
$codigo_evento = str_replace(",",";",$codigo_evento);

$parametros_adicionais = "";
//Recebe Variáveis do Formulário - Início

// DEFINE O IN E O TEXTO DIAS DA SEMANA - INÍCIO
$txt_dias_semana = "";
$qtd_dias_semana = 0;
$in_semana = "(0";
if(isset($_POST["chk_1"])){
		$in_semana = $in_semana.",1";
		if($qtd_dias_semana==0) $txt_dias_semana = $txt_dias_semana."Domingo";
		else $txt_dias_semana = $txt_dias_semana.", Domingo";
		$qtd_dias_semana = $qtd_dias_semana + 1;
}
if(isset($_POST["chk_2"])){
		$in_semana = $in_semana.",2";
		if($qtd_dias_semana==0) $txt_dias_semana = $txt_dias_semana."Segunda-Feira";
		else $txt_dias_semana = $txt_dias_semana.", Segunda-Feira";
		$qtd_dias_semana = $qtd_dias_semana + 1;
}
if(isset($_POST["chk_3"])){
		$in_semana = $in_semana.",3";
		if($qtd_dias_semana==0) $txt_dias_semana = $txt_dias_semana."Terça-Feira";
		else $txt_dias_semana = $txt_dias_semana.", Terça-Feira";
		$qtd_dias_semana = $qtd_dias_semana + 1;
}
if(isset($_POST["chk_4"])){
		$in_semana = $in_semana.",4";
		if($qtd_dias_semana==0) $txt_dias_semana = $txt_dias_semana."Quarta-Feira";
		else $txt_dias_semana = $txt_dias_semana.", Quarta-Feira";
		$qtd_dias_semana = $qtd_dias_semana + 1;
}
if(isset($_POST["chk_5"])){
		$in_semana = $in_semana.",5";
		if($qtd_dias_semana==0) $txt_dias_semana = $txt_dias_semana."Quinta-Feira";
		else $txt_dias_semana = $txt_dias_semana.", Quinta-Feira";
		$qtd_dias_semana = $qtd_dias_semana + 1;
}
if(isset($_POST["chk_6"])){
		$in_semana = $in_semana.",6";
		if($qtd_dias_semana==0) $txt_dias_semana = $txt_dias_semana."Sexta-Feira";
		else $txt_dias_semana = $txt_dias_semana.", Sexta-Feira";
		$qtd_dias_semana = $qtd_dias_semana + 1;
}
if(isset($_POST["chk_7"])){
		$in_semana = $in_semana.",7";
		if($qtd_dias_semana==0) $txt_dias_semana = $txt_dias_semana."Sábado";
		else $txt_dias_semana = $txt_dias_semana.", Sábado";
		$qtd_dias_semana = $qtd_dias_semana + 1;
}
$in_semana = $in_semana.")";
if($select_dias_semana == '00'){
	$in_semana = "(1,2,3,4,5,6,7)";
	$txt_dias_semana = "Todos";
}
if ($txt_dias_semana  == "Domingo, Segunda-Feira, Terça-Feira, Quarta-Feira, Quinta-Feira, Sexta-Feira, Sábado") $txt_dias_semana = "Todos";
// DEFINE O IN E O TEXTO DIAS DA SEMANA - FIM


// DEFINE IN ILHAS - INÍCIO
$tem_inc = 0;
$in_ilhas = "";
$ilhas_selecionadas_txt = "";
$query = $pdo->prepare("select * from tb_ilhas");
$query->execute();
for($i=0; $row = $query->fetch(); $i++){
	$nome_ilha = utf8_encode($row['nome_ilha']);
	$cod_filas = utf8_encode($row['cod_filas']);
	$desc_ilha = utf8_encode($row['desc_ilha']);
	
	if(isset($_POST["chk_$nome_ilha"])){
		if($tem_inc == 0){
			$in_ilhas = $cod_filas;
			$ilhas_selecionadas_txt = $desc_ilha;
		}
		else{
			$in_ilhas = $in_ilhas.",$cod_filas";
			$ilhas_selecionadas_txt = $ilhas_selecionadas_txt.", $desc_ilha";
		}
		$tem_inc = 1;
	}
				
}
// DEFINE IN ILHAS - FIM


//Conversão Data Texto - Início
$t_inicial = strtotime($data_inicial);
$data_inicial = date('m/d/Y',$t_inicial);
$data_inicial_texto = date('d/m/Y',$t_inicial);
$data_inicial_arquivo = date('d_m_Y',$t_inicial);

$t_final = strtotime($data_final);
$data_final = date('m/d/Y',$t_final);
$data_final_texto = date('d/m/Y',$t_final);
$data_final_arquivo = date('d_m_Y',$t_final);
//Conversão Data Texto - Fim


//Conversão Data Texto - Início - 1
$t_inicial1 = strtotime($data_inicial1);
$data_inicial1 = date('m/d/Y',$t_inicial1);
$data_inicial_texto1 = date('d/m/Y',$t_inicial1);
$data_inicial_arquivo1 = date('d_m_Y',$t_inicial1);

$t_final1 = strtotime($data_final1);
$data_final1 = date('m/d/Y',$t_final1);
$data_final_texto1 = date('d/m/Y',$t_final1);
$data_final_arquivo1 = date('d_m_Y',$t_final1);
//Conversão Data Texto - Fim

//Conversão Data Texto - Início - 2
$t_inicial2 = strtotime($data_inicial2);
$data_inicial2 = date('m/d/Y',$t_inicial2);
$data_inicial_texto2 = date('d/m/Y',$t_inicial2);
$data_inicial_arquivo2 = date('d_m_Y',$t_inicial2);

$t_final2 = strtotime($data_final2);
$data_final2 = date('m/d/Y',$t_final2);
$data_final_texto2 = date('d/m/Y',$t_final2);
$data_final_arquivo2 = date('d_m_Y',$t_final2);
//Conversão Data Texto - Fim

// VETOR SUPERVISORES - INÍCIO
$array_supervisores = array();
$query = $pdo->prepare("select matricula, nome from tb_colaboradores_indra where codfuncao = 2");
$query->execute(); // EXECUTA A CONSULTA
for($i=0; $row = $query->fetch(); $i++){
	$matricula = utf8_encode($row['matricula']);
	$nome = utf8_encode($row['nome']);
	$array_supervisores[$matricula] = $nome; 
}
// VETOR SUPERVISORES - FIM

// VETOR FILA/ILHA - INÍCIO
$array_fila_ilha = array();
$array_ilhas = array();
$array_ilhas_relacao = array();
$query = $pdo->prepare("select desc_ilha, cod_filas from tb_ilhas_qualidade");
$query->execute(); // EXECUTA A CONSULTA
for($i=0; $row = $query->fetch(); $i++){
	$desc_ilha = utf8_encode($row['desc_ilha']);
	$cod_filas = utf8_encode($row['cod_filas']);
	$vet_filas = explode(",", $cod_filas);	
	foreach($vet_filas as $fila){
		$fila = number_format($fila, 0, ',', '.');
		$array_fila_ilha["$fila"] = "$desc_ilha";		
	}
	array_push($array_ilhas_relacao,"$desc_ilha");
	$array_ilhas["$desc_ilha"]["RECEBIDAS"] = 0;
	$array_ilhas["$desc_ilha"]["TRANSFERIDAS"] = 0;
	$array_ilhas["$desc_ilha"]["TOTAL_LIGACOES"] = 0;
	$array_ilhas["$desc_ilha"]["RECEBIDAS_URA"] = 0;
}
// VETOR FILA/ILHA - FIM

// EVENTOS URA - INÍCIO
$array_eventos_desc_cod= array();
$array_eventos_cod_desc= array();
$query = $pdo->prepare("select cod_evento, desc_evento from tb_eventos");
$query->execute(); // EXECUTA A CONSULTA
for($i=0; $row = $query->fetch(); $i++){
	$desc_evento = utf8_encode($row['desc_evento']);
	$cod_evento = utf8_encode($row['cod_evento']);
	
	$array_eventos_desc_cod["$desc_evento"] = "$cod_evento";
	$array_eventos_cod_desc["$cod_evento"] = "$desc_evento";
}
// EVENTOS URA - FIM

// EVENTOS URA NOVA - INÍCIO

	$query = $pdo->prepare("select cod_evento, desc_evento from tb_eventos");
	$query->execute(); // EXECUTA A CONSULTA
	for($i=0; $row = $query->fetch(); $i++){
		$desc_evento = utf8_encode($row['desc_evento']);
		$cod_evento = utf8_encode($row['cod_evento']);
		
		$array_eventos_desc_cod["$desc_evento"] = "$cod_evento";
		$array_eventos_cod_desc["$cod_evento"] = "$desc_evento";
	}

// EVENTOS URA NOVA - FIM


// VETOR COD_FILA / DESC_FILA - INÍCIO
$query = $pdo->prepare("select * from tb_filas");

$query->execute(); // EXECUTA A CONSULTA

$cod_desc = array();
$desc_cod = array();

for($i=0; $row = $query->fetch(); $i++){
	$cod_fila = utf8_encode($row['cod_fila']);
	$cod_fila = number_format($cod_fila, 0, ',', '.');
	$desc_fila = utf8_encode($row['desc_fila']);
	
	$cod_desc[$cod_fila] = $desc_fila;
	$desc_cod[$desc_fila] = $cod_fila;
}
// VETOR COD_FILA / DESC_FILA - FIM

// VDN's - INÍCIO
$vdn_92001 = 'ATD_CAIXA_NIG';
$vdn_92002 = 'ATD_CAIXA_PB';
$vdn_92003 = 'ATD_CAIXA_ECCB';
$vdn_92004 = 'ATD_CAIXA_PLH';
$vdn_92005 = 'ATD_CAIXA_AM';
$vdn_92007 = 'CXA_PREV_TRIAGEM_TRA';
$vdn_92008 = 'CXA_PERDA_ROUBO';
$vdn_92009 = 'ATD_DESBLOQUEIO';
$vdn_92010 = 'CXA_COBRANCA';
$vdn_92011 = 'ATD_CANCELAMENTO';
$vdn_92017 = 'ATD_CONTESTACAO';
$vdn_92019 = 'CXA_GERAL';
$vdn_92020 = 'CXA_GERAL_AMZ';
$vdn_92021 = 'CXA_FATURA_AMZ';
$vdn_92022 = 'CXA_PARCELAMENTO_AMZ';
$vdn_92023 = 'CXA_RETENÇÃO_AMZ';
$vdn_92024 = 'CXA_GERAL_INT';
$vdn_92025 = 'CXA_FATURA_INT';
$vdn_92026 = 'CXA_PARCELAMENTO_INT';
$vdn_92027 = 'CXA_RETENCAO_INT';
$vdn_92028 = 'CXA_GERAL_NAC';
$vdn_92029 = 'CXA_FATURA_NAC';
$vdn_92030 = 'CXA_PARCELAMENTO_NAC';
$vdn_92031 = 'CXA_RETENCAO_NAC';
$vdn_92032 = 'CXA_GERAL_PRE';
$vdn_92033 = 'CXA_FATURA_PRE';
$vdn_92034 = 'CXA_PARCELAMENTO_PRE';
$vdn_92035 = 'CXA_RETENCAO_PRE';
$vdn_92036 = 'CXA_PROGPONTOS_AMZ';
$vdn_92037 = 'CXA_CONTESTACAO_AMZ';
$vdn_92038 = 'CXA_DESBLOQUEIO_AMZ';
$vdn_92039 = 'CXA_CONTESTACAO_INT';
$vdn_92040 = 'CXA_PROGPONTOS_INT';
$vdn_92041 = 'CXA_DESBLOQUEIO_INT';
$vdn_92042 = 'CXA_CONTESTACAO_NAC';
$vdn_92043 = 'CXA_PROGPONTOS_NAC';
$vdn_92044 = 'CXA_DESBLOQUEIO_NAC';
$vdn_92045 = 'CXA_CONTESTACAO_PRE';
$vdn_92046 = 'CXA_PROGPONTOS_PRE';
$vdn_92047 = 'CXA_DESBLOQUEIO_PRE';
$vdn_92048 = 'CXA_GERAL_PJ';
$vdn_92049 = 'CXA_CONTESTACAO_PJ';
$vdn_92050 = 'CXA_DESBLOQUEIO_PJ';
$vdn_92052 = 'CXA_BLQ_COBR_PRE';
$vdn_92500 = 'TRANSF_PESQ_SATISF';
$vdn_92501 = 'TRANSF_CAIXA_GERAL';
$vdn_92502 = 'TRANSF_CAIXA_PBI';
$vdn_92503 = 'TRANSF_CAIXA_ECCB';
$vdn_92504 = 'CXA_CONTESTACAO';
$vdn_92505 = 'TRANSF_CAIXA_AM';
$vdn_92506 = 'TRANSF_URA';
$vdn_92507 = 'CXA_RETENCAO';
$vdn_92508 = 'CXA_PREV_TRIAGEM';
$vdn_92509 = 'TRANSF_COBRANÇA';
$vdn_92510 = 'TRANSF_ALGAR_9002';
$vdn_92511 = 'CXA_BLQ_COBR';
$vdn_92512 = 'TRANSF_PERDA_ROUBO';
$vdn_92513 = 'TRANSF_CANCELAMENTO';
$vdn_92514 = 'TRANSF_DTM_HUM';
$vdn_92515 = 'TRANSF_DTM_COB';
$vdn_92516 = 'TRANSF_URA_NAC';
$vdn_92517 = 'TRANSF_URA_PRE';
$vdn_92518 = 'TRANSF_URA_AMZ';
$vdn_92519 = 'TRANSF_URA_PJ';
$vdn_92520 = 'TRANSF_URA_INT';
$vdn_92521 = 'TRANSF_PREV_CXA_GERA';
$vdn_92522 = 'CXA_APP_CARTAO';
$vdn_92523 = 'TRANSF_PREV_MAR';
$vdn_92524 = 'TRANSF_PREV_TIVIT';
$vdn_92527 = 'CXA_ATEND_EXTERIOR';
$vdn_92528 = 'CXA_PARCELAMENTO';
$vdn_92529 = 'CXA_PROGPONTOS';
$vdn_92531 = 'CXA_PREV_TRIAGEM';
$vdn_92532 = 'CXA_AVISO_VIAGEM';
$vdn_92533 = 'CXA_FATURA';
$vdn_99011 = 'CXA_EMPREGADO';
$vdn_99012 = 'CXA_MALA_DIRETA';
// VDN's - FIM
?>