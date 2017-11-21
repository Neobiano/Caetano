<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>
</head>

<body>
<div class="w3-container w3-center w3-margin">
	<img src="logo.png" style="width:9%">
	<hr>
</div>	
<?php

include "conecta.php";
include "funcoes.php";

//tempo limite consultas sql
set_time_limit(99999);
ini_set('max_execution_time', 99999);

//---------------------iniciando contador de tempo de execução da consulta---------------------//
list($usec, $sec) = explode(' ', microtime());
$script_start = (float) $sec + (float) $usec;

//recebe variáveis do formulário
$qual_mes = $_POST['qual_mes'];
$qual_ano = $_POST['qual_ano'];
$shortcall_porcentagem = $_POST['shortcall_porcentagem'];
$shortcall_tempo = $_POST['shortcall_tempo'];
$nsr = ($_POST['nsr']/100);
$nsr_premium = ($_POST['nsr_premium']/100);
$ns_normal = $_POST['ns_normal'];
$ns_diferenciado = $_POST['ns_diferenciado'];
$valor_atendimento = $_POST['valor_atendimento'];
$valor_atendimento_ura = $_POST['valor_atendimento_ura'];

$acp_retencao = '25';//$_POST['acp_retencao'];
$acp_triagem = '25';//$_POST['acp_triagem'];
$acp_aviso_viagem = '00';//$_POST['acp_triagem'];
$acp_app = '00';//$_POST['acp_triagem'];
$acp_parcelamento = '25';//$_POST['acp_parcelamento'];
$acp_perda_roubo = '25';//$_POST['acp_parcelamento'];
$acp_contestacao = '25';//$_POST['acp_contestacao'];
$acp_pontos = '00';//$_POST['acp_pontos'];
$acp_geral_normal = '00';//$_POST['acp_geral_normal'];
$acp_todas_premium = '05';//$_POST['acp_geral_premium'];
$acp_pj = '00';//$_POST['acp_pj'];
$acp_caixa_empregado = '00';//$_POST['acp_caixa_empregado'];
$acp_deficiente_auditivo = '00';//$_POST['acp_deficiente_auditivo'];
$acp_mala_direta = '00';//$_POST['acp_mala_direta'];

$ansm1 = $_POST['ansm1'];
$ansm2 = $_POST['ansm2'];
$ansm3 = $_POST['ansm3'];
$ansm4 = $_POST['ansm4'];
$ansm5 = $_POST['ansm5'];
$ansm6 = $_POST['ansm6'];
$ansm7 = $_POST['ansm7'];
$ansm8 = $_POST['ansm8'];
$ansm9 = $_POST['ansm9'];
$ansm10 = $_POST['ansm10'];
$ansm11 = $_POST['ansm11'];
$ansm12 = $_POST['ansm12'];
$ansm13 = $_POST['ansm13'];
$ansm14 = $_POST['ansm14'];
$ansm15 = $_POST['ansm15'];
$ansm16 = $_POST['ansm16'];
$ansm17 = $_POST['ansm17'];
$ansm18 = $_POST['ansm18'];
$ansm19 = $_POST['ansm19'];
$ansm20 = $_POST['ansm20'];
$ansm21 = $_POST['ansm21'];
$ansm22 = $_POST['ansm22'];
$ansm23 = $_POST['ansm23'];
$ansm24 = $_POST['ansm24'];
$ansm25 = $_POST['ansm25'];
$ansm26 = $_POST['ansm26'];
$ansm27 = $_POST['ansm27'];
$ansm28 = $_POST['ansm28'];
$ansm29 = $_POST['ansm29'];
$ansm30 = $_POST['ansm30'];
$ansm31 = $_POST['ansm31'];

$glosa1 = $_POST['glosa1'];
$iqf = $_POST['iqf'];

$dns_automatico = $_POST['dns_automatico'];
$qual_dns = $_POST['qual_dns'];
$sel_eventos_ura = $_POST['sel_eventos_ura'];

$acertos_acre = $_POST['acertos_acre'];
$acertos_decre = $_POST['acertos_decre'];

// define valores NSR
$nsr_premium_valor = $nsr_premium;
$nsr_valor = $nsr;

// define variável $mes por extenso
if ($qual_mes == '01') $mes = 'Janeiro';
if ($qual_mes == '02') $mes = 'Fevereiro';
if ($qual_mes == '03') $mes = 'Março';
if ($qual_mes == '04') $mes = 'Abril';
if ($qual_mes == '05') $mes = 'Maio';
if ($qual_mes == '06') $mes = 'Junho';
if ($qual_mes == '07') $mes = 'Julho';
if ($qual_mes == '08') $mes = 'Agosto';
if ($qual_mes == '09') $mes = 'Setembro';
if ($qual_mes == '10') $mes = 'Outubro';
if ($qual_mes == '11') $mes = 'Novembro';
if ($qual_mes == '12') $mes = 'Dezembro';

//define quantidade de dias de cada mês
if($qual_mes=='01') $qtd_dias = 31;
if($qual_mes=='02') {
	if ($qual_ano%4 == 0) $qtd_dias = 28;
	else $qtd_dias = 29;
}
if($qual_mes=='03') $qtd_dias = 31;
if($qual_mes=='04') $qtd_dias = 30;
if($qual_mes=='05') $qtd_dias = 31;
if($qual_mes=='06') $qtd_dias = 30;
if($qual_mes=='07') $qtd_dias = 31;
if($qual_mes=='08') $qtd_dias = 31;
if($qual_mes=='09') $qtd_dias = 30;
if($qual_mes=='10') $qtd_dias = 31;
if($qual_mes=='11') $qtd_dias = 30;
if($qual_mes=='12') $qtd_dias = 31;

//define variáveis final total geral - mensal
$pg_total_mes; // pagamento total mês - sem adicionais (acp, etc)
$mensal_qtde_ca = 0;
$mensal_total_qtd_ura = 0;
$mensal_total_bruto = 0;
$mensal_total_desc_ansm = 0;
$mensal_total_acre_acp = 0;

//$mensal_retido = 0;
$mensal_ura = 0;
$mensal_humano = 0;
$mensal_total = 0;


//define vetores das ilhas 
$vet_retencao = array('73','77','81'/*,'85'*/,'116');
$vet_aviso_viagem = array('125');
$vet_triagem = array('150');
$vet_parcelamento = array('72','76','80',/*'84',*/'111');//tirei
$vet_contestacao = array('60','88','90','93'/*,'96'*/);//tirei
$vet_pontos = array('87','91','94',/*'97',*/'120'); //tirei
$vet_geral_normal = array('70','71','74','75','78','79','86','58','89','92','95','103','114','118'/*,'57'*/);//tirei 57 é perda e roubo
//$vet_geral_premium = array('82','83','98'); tirei, pois já esta na vet_todas_premium

$vet_pj = array('99','101','110');/*,'100'*/
$vet_caixa_empregado = array('63');
$vet_deficiente_auditivo = array('61');
$vet_mala_direta = array('64');
$vet_perda_roubo = array('57');
$vet_bloqueio_cobranca = array('117','106',/*'107',*/'108','109');//tirei

$vet_app = array('102');
$vet_todas_premium = array('82','83','84','85','96','97','98','107');

$vet_todas_filas = array_merge($vet_todas_premium, $vet_app, $vet_bloqueio_cobranca, 
							   $vet_retencao, $vet_triagem, $vet_aviso_viagem, 
							   $vet_parcelamento, $vet_contestacao, $vet_pontos, 
							   $vet_geral_normal,$vet_pj, 
							   $vet_caixa_empregado, $vet_deficiente_auditivo, 
							   $vet_mala_direta,$vet_perda_roubo);

//conta tamanho vet_todas_filas
$num_filas = count($vet_todas_filas);

//define todas as filas nsr maior

//define 'in' das ilhas
$ilha_retencao = "'73','77','81','85','116'";
$ilha_triagem = "'150'";
$ilha_aviso_viagem = "'125'";
$ilha_parcelamento = "'72','76','80','84','111'";
$ilha_contestacao = "'60','88','90','93','96'";
$ilha_pontos = "'87','91','94','97','120'";
$ilha_pj = "'99','101','110'";/*'100',*/
$ilha_caixa_empregado = "'63'";
$ilha_deficiente_auditivo = "'61'";
$ilha_mala_direta = "'64'";
$ilha_geral_normal = "'70','71','74','75','78','79','86','58','89','92','95','103','114','118','57'";/*'102',*/
$ilha_geral_premium = "'82','83','98'";
$ilha_bloqueio_cobranca = "'117','106','107','108','109'";
$ilha_app = "'102'";

//31/10/2016 (Fabiano) adicionado a fila 125, retirada fila '100',
$in_todas_filas = "'73','77','81','85','116','150','72','76','80','84','111','60','88','90','93','96','87','91','94','97','120','70','71','74','75','78','79','86','58','89','92','95','102','103','106','108','109','114','118','57','82','83','98','107','99','101','110','63','61','64','117','125'";

$in_filas_premium = "'82','83','84','85','96','97','98','107'";

// calcula iqm
if ($iqf < 90)
{
	$iqf_perc = $iqf / 100;
	$iqm = ($iqf_perc + ( (1-$iqf_perc)*0.8) );
} 
else 
	$iqm = 1;

//todo o bloco abaixo, é para calcular o DNS Automático, no caso utilizamos o fixo 1.0, FECHAR 
if ($dns_automatico == 'sim')
{ // verifica se valor de dns automático ou manual

	// calcula dns
	$a_mes = 0;
	$b_mes = 0;
	$c_mes = 0;
	$soma_nsa = 0;

	//rotina executada para efetuar o calculo do nivel de servico, considerando o intervalo de 30 minutos, vide linha 264
	for ($contador=1; $contador<49; $contador++)
	{
			//'{' adicionada apenas para agrupar e 'esconder' no IDE, código redundante 	
			if(1==1)
			{
				if ($contador == 1){
					$periodo_inicial = '00:00:00.000';
					$periodo_final = '00:29:59.999';
				}
				
				if ($contador == 2){
					$periodo_inicial = '00:30:00.000';
					$periodo_final = '00:59:59.999';
				}
				
				if ($contador == 3){
					$periodo_inicial = '01:00:00.000';
					$periodo_final = '01:29:59.999';
				}
				
				if ($contador == 4){
					$periodo_inicial = '01:30:00.000';
					$periodo_final = '01:59:59.999';
				}
				
				if ($contador == 5){
					$periodo_inicial = '02:00:00.000';
					$periodo_final = '02:29:59.999';
				}
				
				if ($contador == 6){
					$periodo_inicial = '02:30:00.000';
					$periodo_final = '02:59:59.999';
				}
				
				if ($contador == 7){
					$periodo_inicial = '03:00:00.000';
					$periodo_final = '03:29:59.999';
				}
				
				if ($contador == 8){
					$periodo_inicial = '03:30:00.000';
					$periodo_final = '03:59:59.999';
				}
				
				if ($contador == 9){
					$periodo_inicial = '04:00:00.000';
					$periodo_final = '04:29:59.999';
				}
				
				if ($contador == 10){
					$periodo_inicial = '04:30:00.000';
					$periodo_final = '04:59:59.999';
				}
				
				if ($contador == 11){
					$periodo_inicial = '05:00:00.000';
					$periodo_final = '05:29:59.999';
				}
				
				if ($contador == 12){
					$periodo_inicial = '05:30:00.000';
					$periodo_final = '05:59:59.999';
				}
				
				if ($contador == 13){
					$periodo_inicial = '06:00:00.000';
					$periodo_final = '06:29:59.999';
				}
				
				if ($contador == 14){
					$periodo_inicial = '06:30:00.000';
					$periodo_final = '06:59:59.999';
				}
				
				if ($contador == 15){
					$periodo_inicial = '07:00:00.000';
					$periodo_final = '07:29:59.999';
				}
				
				if ($contador == 16){
					$periodo_inicial = '07:30:00.000';
					$periodo_final = '07:59:59.999';
				}
				
				if ($contador == 17){
					$periodo_inicial = '08:00:00.000';
					$periodo_final = '08:29:59.999';
				}
				
				if ($contador == 18){
					$periodo_inicial = '08:30:00.000';
					$periodo_final = '08:59:59.999';
				}
				
				if ($contador == 19){
					$periodo_inicial = '09:00:00.000';
					$periodo_final = '09:29:59.999';
				}
				
				if ($contador == 20){
					$periodo_inicial = '09:30:00.000';
					$periodo_final = '09:59:59.999';
				}
				
				if ($contador == 21){
					$periodo_inicial = '10:00:00.000';
					$periodo_final = '10:29:59.999';
				}
				
				if ($contador == 22){
					$periodo_inicial = '10:30:00.000';
					$periodo_final = '10:59:59.999';
				}
				
				if ($contador == 23){
					$periodo_inicial = '11:00:00.000';
					$periodo_final = '11:29:59.999';
				}
				
				if ($contador == 24){
					$periodo_inicial = '11:30:00.000';
					$periodo_final = '11:59:59.999';
				}
				
				if ($contador == 25){
					$periodo_inicial = '12:00:00.000';
					$periodo_final = '12:29:59.999';
				}
				
				if ($contador == 26){
					$periodo_inicial = '12:30:00.000';
					$periodo_final = '12:59:59.999';
				}
				
				if ($contador == 27){
					$periodo_inicial = '13:00:00.000';
					$periodo_final = '13:29:59.999';
				}
				
				if ($contador == 28){
					$periodo_inicial = '13:30:00.000';
					$periodo_final = '13:59:59.999';
				}
				
				if ($contador == 29){
					$periodo_inicial = '14:00:00.000';
					$periodo_final = '14:29:59.999';
				}
				
				if ($contador == 30){
					$periodo_inicial = '14:30:00.000';
					$periodo_final = '14:59:59.999';
				}
				
				if ($contador == 31){
					$periodo_inicial = '15:00:00.000';
					$periodo_final = '15:29:59.999';
				}
				
				if ($contador == 32){
					$periodo_inicial = '15:30:00.000';
					$periodo_final = '15:59:59.999';
				}
				
				if ($contador == 33){
					$periodo_inicial = '16:00:00.000';
					$periodo_final = '16:29:59.999';
				}
				
				if ($contador == 34){
					$periodo_inicial = '16:30:00.000';
					$periodo_final = '16:59:59.999';
				}
				
				if ($contador == 35){
					$periodo_inicial = '17:00:00.000';
					$periodo_final = '17:29:59.999';
				}
				
				if ($contador == 36){
					$periodo_inicial = '17:30:00.000';
					$periodo_final = '17:59:59.999';
				}
				
				if ($contador == 37){
					$periodo_inicial = '18:00:00.000';
					$periodo_final = '18:29:59.999';
				}
				
				if ($contador == 38){
					$periodo_inicial = '18:30:00.000';
					$periodo_final = '18:59:59.999';
				}
				
				if ($contador == 39){
					$periodo_inicial = '19:00:00.000';
					$periodo_final = '19:29:59.999';
				}
				
				if ($contador == 40){
					$periodo_inicial = '19:30:00.000';
					$periodo_final = '19:59:59.999';
				}
				
				if ($contador == 41){
					$periodo_inicial = '20:00:00.000';
					$periodo_final = '20:29:59.999';
				}
				
				if ($contador == 42){
					$periodo_inicial = '20:30:00.000';
					$periodo_final = '20:59:59.999';
				}
				
				if ($contador == 43){
					$periodo_inicial = '21:00:00.000';
					$periodo_final = '21:29:59.999';
				}
				
				if ($contador == 44){
					$periodo_inicial = '21:30:00.000';
					$periodo_final = '21:59:59.999';
				}
				
				if ($contador == 45){
					$periodo_inicial = '22:00:00.000';
					$periodo_final = '22:29:59.999';
				}
				
				if ($contador == 46){
					$periodo_inicial = '22:30:00.000';
					$periodo_final = '22:59:59.999';
				}
				
				if ($contador == 47){
					$periodo_inicial = '23:00:00.000';
					$periodo_final = '23:29:59.999';
				}
				
				if ($contador == 48){
					$periodo_inicial = '23:30:00.000';
					$periodo_final = '23:59:59.999';
				}
			}
		
			$a_per = 0;
			$b_per = 0;
			$c_per = 0;
		
			//percorrendo todo o intervalo de dadas a cada subintervalo de 30 minutos, ou seja, sumarizando a cada subintervalo
			//for($pos_dia=01; ( $pos_dia<($qtd_dias+1) ); $pos_dia++) //aqui
		  	for($pos_dia=01; ( $pos_dia<(01+1) ); $pos_dia++)
		  	{			
			
				// verifica ns (tempo de espera) 45s ou 90s
				if(isset($_POST["chk_$pos_dia"]))
				{
					$ns = $ns_diferenciado;
				
				}
				else
				{
					$ns = $ns_normal;
				
				}		
				
				// A - Quantidade de atendimentos em que tiveram tempo de espera MENOR do que o determinado para o dia (45 ou 90) 
				$query = $pdo->prepare("SELECT COUNT (*) TOTAL
										FROM TB_EVENTOS_DAC
										WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano $periodo_inicial' AND '$qual_mes/$pos_dia/$qual_ano $periodo_final' 
										AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND TEMPO_ESPERA <= '$ns'");
				$query->execute();
				for($q=0; $row = $query->fetch(); $q++)
				{
					$a_per = $a_per + $row['TOTAL'];
				}
			
				// B - Totais de atendimento no dia
				$query = $pdo->prepare("SELECT COUNT (*) TOTAL
										FROM TB_EVENTOS_DAC
										WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano $periodo_inicial' AND '$qual_mes/$pos_dia/$qual_ano $periodo_final' 
										AND CALLID IS NOT NULL AND TEMPO_ATEND > '0'");
				$query->execute();
				for($q=0; $row = $query->fetch(); $q++)
				{
					$b_per = $b_per + $row['TOTAL'];
				}
			
				// C - Quantidade de atendimentos em que tiveram tempo de espera MAIOR do que o determinado para o dia (45 ou 90)
				$query = $pdo->prepare("SELECT COUNT (*) TOTAL
										FROM TB_EVENTOS_DAC
										WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano $periodo_inicial' AND '$qual_mes/$pos_dia/$qual_ano $periodo_final' 
										AND CALLID IS NOT NULL AND TEMPO_ATEND = '0' AND TEMPO_ESPERA > '$ns'");
				$query->execute();
				for($q=0; $row = $query->fetch(); $q++)
				{
					$c_per = $c_per + $row['TOTAL'];
				}	
			}//final FOR - Intervalo de datas
	
			$a_mes = $a_mes + $a_per;
			$b_mes = $b_mes + $b_per;
			$c_mes = $c_mes + $c_per;
			
			//NSA - Nivel de Servico apurado no período
			$nsa_periodo = ($a_per/($b_per+$c_per));
			
			$soma_nsa = $soma_nsa + $nsa_periodo;
			
	}//final FOR - Subintervalo de 30 minutos

	/*NSA - Nivel de Servico apurado no Mês	
	 * Divide-se a quantidade de chamados que atingiram o NS (Valor de A) 
	 * pela soma do Total de Chamados(Total de B) + Total de Chamados que Estouraram o NS (Total de C)	
	*/
	$nsa_mes = ($a_mes/($b_mes+$c_mes));
	
	//somatório das NSA dos periodos, dividido pelo total de periodos (média aritimetica simples)
	$nsh_mes = $soma_nsa/48;

	//Calculo DNS - Dispersão de Nível de Serviço por Faixa de Horário
	$dif_nsa_nsh = $nsa_mes - $nsh_mes;

	if ($dif_nsa_nsh < 0) 
		$dif_nsa_nsh = $dif_nsa_nsh * (-1);

	if ($dif_nsa_nsh > 0.05) 
		$dns = 1 - ( $dif_nsa_nsh - 0.05 );
	else 
		$dns = 1;
}// FINAL if ($dns_automatico == 'sim')
else 
	$dns = $qual_dns; 

// imprime parâmetros - início
echo "<div class = 'w3-leftbar w3-border-black w3-margin-left'><div class='w3-margin-left w3-tiny'><b>Parâmetros Utilizados:</b></div>";
echo "<br>";
echo "<div class='w3-margin-left w3-tiny'>Período da Pesquisa: $mes / $qual_ano</div>";
echo "<div class='w3-margin-left w3-tiny'>Limite para pagamento de Shortcall: $shortcall_porcentagem%</div>";
echo "<div class='w3-margin-left w3-tiny'>Tempo Shortcall: $shortcall_tempo segundos</div>";

$imp_nsr = $nsr * 100;
$imp_nsr_premium = $nsr_premium * 100;

echo "<div class='w3-margin-left w3-tiny'>NSR Normal: $imp_nsr%</div>";
echo "<div class='w3-margin-left w3-tiny'>NSR Diferenciado: $imp_nsr_premium%</div>";
echo "<div class='w3-margin-left w3-tiny'>Tempo de espera padrão: $ns_normal segundos</div>";
echo "<div class='w3-margin-left w3-tiny'>Tempo de espera para dias de maior movimento: $ns_diferenciado segundos</div>";
echo "<div class='w3-margin-left w3-tiny'>Tempo de espera para dias de maior movimento: $ns_diferenciado segundos</div>";
echo "<div class='w3-margin-left w3-tiny'>Preço do minutos (Atendimento Humano): R$ $valor_atendimento</div>";
echo "<div class='w3-margin-left w3-tiny'>Preço do minutos (Atendimento Eletrônico): R$ $valor_atendimento_ura</div>";

//verifica se é DMN
$dias_tempo_dif = '';
$cont_dias = 0;
for ($o=1; $o<32; $o++)
{
		if(isset($_POST["chk_$o"]))
		{
				if ($cont_dias == 0)
				{
					$dias_tempo_dif = "$o";
					$cont_dias++;
				}
				else
				{
					$dias_tempo_dif = $dias_tempo_dif.", $o";
				}
				
		}
}

$cont_dias = 0;

if ($dias_tempo_dif == '') 
	echo "<div class='w3-margin-left w3-tiny'>Dias com tempo de espera diferenciado: Nenhum dia selecionado</div>";
else 
	echo "<div class='w3-margin-left w3-tiny'>Dias com tempo de espera diferenciado: $dias_tempo_dif</div>";
	
for ($o=1; $o<32; $o++)
{
	$palavra = "ansm$o";
	$impri_ansm = $$palavra;
	if ($$palavra != 0)
	{
		if($cont_dias == 0)
		{
			echo "<div class='w3-margin-left w3-tiny'><br><b>Dias com Revisão de Nível:</b></div>";
			echo "<div class='w3-margin-left w3-tiny'>Dia $o: $impri_ansm</div>";
			$cont_dias++;
		}
		else
		{
			echo "<div class='w3-margin-left w3-tiny'>Dia $o: $impri_ansm</div>";
		}
		
	}
		
}

echo "<br>";
echo "<div class='w3-margin-left w3-tiny'>Quantidade de glossas 0,1%: $glosa1</div>";
echo "<div class='w3-margin-left w3-tiny'>IQF: $iqf%</div>";

if ($dns_automatico == 'nao')
{
	echo "<div class='w3-margin-left w3-tiny'>DNS Informado: $dns</div>";
} 
else
{
	echo "<div class='w3-margin-left w3-tiny'>Cálculo automático de DNS: $dns</div>";	
}

echo "</div>";
// IMPRIME PARÂMETROS - FIM

echo "<br>";

// IMPRIME LEGENDA
echo "<div class = 'w3-leftbar w3-border-black w3-margin-left'>";
echo "<div class='w3-margin-left w3-tiny'><b>Legenda:</b></div><br>";
echo "<div class='w3-margin-left w3-tiny'><b>A:</b> Chamadas atendidas em até <b>xx</b> segundos, sendo xx o tempo de espera definido para o dia ($ns_normal ou $ns_diferenciado segundos)</div>";
echo "<div class='w3-margin-left w3-tiny'><b>B:</b> Total de Atendimentos</div>";
echo "<div class='w3-margin-left w3-tiny'><b>C:</b> Chamadas abandonadas após <b>xx</b> segundos, sendo xx o tempo de espera definido para o dia ($ns_normal ou $ns_diferenciado segundos)</div>";
echo "<div class='w3-margin-left w3-tiny'><b>NSA:</b> Nível de Serviço Apurado</div>";
echo "<div class='w3-margin-left w3-tiny'><b>NSR:</b> Nível de Serviço de Referência</div>";
echo "<div class='w3-margin-left w3-tiny'><b>NS:</b> Nível de Serviço</div>";
echo "<div class='w3-margin-left w3-tiny'><b>TMA:</b> Tempo Médio de Atendimento</div>";
echo "<div class='w3-margin-left w3-tiny'><b>SHORTCALL:</b> Total de chamadas com duração menor ou igual a $shortcall_tempo segundos</div>";
echo "<div class='w3-margin-left w3-tiny'><b>SHORTCALL(%):</b> Percentual de SHORTCALL em relação ao Total de Atendimentos (B)</div>";
echo "<div class='w3-margin-left w3-tiny'><b>CA:</b> Total de Chamadas Faturadas</div>";
echo "<div class='w3-margin-left w3-tiny'><b>DNS:</b> Dispersão de Nível de Serviço por Faixa de Horário</div>";
echo "<div class='w3-margin-left w3-tiny'><b>IQM:</b> Índice de Qualidade Mensal</div></div>";
echo "<br>";
// echo "<div class = 'w3-leftbar w3-border-black w3-margin-left'><div class='w3-margin-left w3-tiny'><b>Regra ACP:</b> Para a FILAS de ATENDIMENTO HUMANO de RETENÇÃO, será aplicado ACP de 25% unicamente para os atendimentos desta fila que resultarem  em retenção do contrato do cliente com a CAIXA, conforme definido no item 7.14.2.1 do Anexo do Contrato.<br>Para as FILAS de ATENDIMENTO HUMANO PJ e TRIAGEM PREVENTIVA, será concedido ACP conforme definido abaixo:<br>- Caso o NS seja superior a 95% será concedido ACP de 15%;<br>- Caso o NS seja superior a 98% será concedido ACP de 20%.</div></div>";
echo "<hr>";


	//Novo começo
	for($pos_dia=01; ( $pos_dia<(01+1) ); $pos_dia++)
	//for($pos_dia=01; ( $pos_dia<($qtd_dias+1) ); $pos_dia++) //aqui
	{
		if(isset($_POST["chk_$pos_dia"]))		
			$ns = $ns_diferenciado;			
		else
			$ns = $ns_normal;
			
		//-------------------------------------------------Calculando a quantidade de atendimentos ELETRONICOS--------------------------------------//
        // as outras opções de filtro Tipo de Faturamento URA, foram suprimidas, pois não sao usadas. ira permancecer nas versões anteriores do front
        $qtd_ura = 0;
        $sql = "select count (distinct callid) TOTAL
                                from tb_eventos_ura
                                where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' and callid is not null";
        $query = $pdo->prepare($sql);
        $query->execute();
        for($q=0; $row = $query->fetch(); $q++)
        {
            $qtd_ura = $row['TOTAL'];
        }	
			
		//---------------totalizadores diários-------------//	
		$qtde_ca_diario = 0; 							
		$valor_bruto_diario = 0;
		
		//Inicializando array	
		$tabela[$pos_dia] = array(						  
							  "DIA" => $pos_dia,
							  "ANSM" => 0.00,
							  "QTDE_AT_ELETRONICO" => 0.00,
							  "QTDE_AT_HUMANO" => 0.00,
							  "QTDE_AT_TOTAL" => 0.00,
							  "REM_AT_H_BRUTO" => 0.00,
							  "DESC_ANSM_DIARIO" => 0.00,
							  "AD_ACP_DIARIO" => 0.00,						  
							  "REM_AT_ELETRONICO" => 0.00,
							  "REM_AT_HUMANO" => 0.00,
							  "REM_AT_TOTAL" => 0.00);			   	
				
		//percorrendo o array $vet_todas_filas
		$soma_ansm = 0;
		$cont_ansm = 0;
		$count = count($vet_todas_filas);
		for ($i = 0; $i < $count; $i++)
		{
			$qtde_ca = 0;    
			$cod_fila = $vet_todas_filas[$i];		
			$query = $pdo->prepare("SELECT desc_fila FROM TB_FILAS where cod_fila = $cod_fila"); //aqui boy
			$query->execute();
			
			for($q=0; $row = $query->fetch(); $q++)
			{			
				$nome_fila = $row['desc_fila'];
			}
														
			//Valor CA			
			$sql = "SELECT  COUNT (*) TOTAL  FROM  TB_EVENTOS_DAC ted
                                    inner join  (
                                                    SELECT distinct  CALLID, min(data_hora) d_hora
                                                    FROM TB_EVENTOS_DAC
                                                    WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
                                                    AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' and COD_FILA in ( ".retornaIlha($cod_fila)." ) 
                                                   GROUP BY CALLID
                                               ) AS A on (A.CALLID = ted.callid and A.d_hora = ted.data_hora) 
                                    WHERE ted.DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
                                    AND ted.CALLID IS NOT NULL AND ted.TEMPO_ATEND > '0' AND ted.COD_FILA = $cod_fila";
                                    
			
                                    
			$query = $pdo->prepare($sql);
			$query->execute();
			
			for($q=0; $row = $query->fetch(); $q++)
			{
				$qtde_ca = $row['TOTAL'];
			}															
			
			
			$imp_acp_aplicado = 0;	
			$qtde_acp = 0; 
			$fator = 1;
			$acp_aut = false; //recebe acp automaticamente
			
			if( ($qtde_ca > 0) )
			{
				//---------------------------------------DADOS DA PRIMEIRA TABELA--------------------------------//
                //Valor de A
                $query = $pdo->prepare("SELECT COUNT (*) TOTAL
                                        FROM TB_EVENTOS_DAC
                                        WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
                                        AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND TEMPO_ESPERA <= '$ns'
                                        and COD_FILA = $cod_fila 
                                      ");
                $query->execute();
                
                for($q=0; $row = $query->fetch(); $q++)
                {           
                    
                    $valor_a = $row['TOTAL'];
                }
                    
                //Valor de B e TMA
                $query = $pdo->prepare("SELECT COUNT (*) TOTAL, AVG (TEMPO_ATEND) TMA
                                        FROM TB_EVENTOS_DAC
                                        WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
                                        AND CALLID IS NOT NULL AND TEMPO_ATEND > '0'
                                        and COD_FILA = $cod_fila
                                        ORDER BY TOTAL DESC");
                $query->execute();
                
                for($q=0; $row = $query->fetch(); $q++)
                {       
                    $valor_b = $row['TOTAL'];
                    $valor_tma = $row['TMA'];
                }
                    
                //Valor de C
                $query = $pdo->prepare("SELECT  COUNT (*) TOTAL
                                        FROM TB_EVENTOS_DAC
                                        WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
                                        AND CALLID IS NOT NULL AND TEMPO_ATEND = '0' AND TEMPO_ESPERA > '$ns'
                                        and COD_FILA = $cod_fila ");
                $query->execute();
                
                for($q=0; $row = $query->fetch(); $q++)
                {
                    $valor_c = $row['TOTAL'];
                }
                    
                //Valor shortcall (SC) 
                $query = $pdo->prepare("SELECT COUNT (*) TOTAL
                                        FROM TB_EVENTOS_DAC
                                        WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
                                        AND CALLID IS NOT NULL AND TEMPO_ATEND BETWEEN '1' AND '$shortcall_tempo'
                                        and COD_FILA = $cod_fila ");
                $query->execute();
                for($q=0; $row = $query->fetch(); $q++)
                {           
                    $valor_sc = $row['TOTAL'];
                }
                
                // percentual shortcall
                if ($valor_sc == '0') 
                    $perc_sc = 0;
                else 
                    $perc_sc = $valor_sc / $valor_b * 100;
                                
                // finaliza/ imprime valor de ca (retirado o shortcall)
                $tirar_max = $valor_b * 0.05;
                if ($valor_sc > $tirar_max)
                {
                    $tirar = $valor_sc - $tirar_max;
                    if ( ($qtde_ca - $tirar) >= 0 )
                        $qtde_ca = $qtde_ca - $tirar;
                    else 
                        $qtde_ca = 0;
                }
        
                //arrendondamento - início
                $arr = intval($qtde_ca);
                if ($qtde_ca - $arr >= 0.5) 
                    $va_novo = $arr+1;
                else 
                    $va_novo = $arr;
                
                $qtde_ca = $va_novo;
                //arredondamento - fim
                    
				//-------------Iniciando construção dos parametros para a SQL--------------//
				/*
                if ($ns == '45') //tolerancia de 45 segundos para o dia
				{
					$sql_a_faixa_horario = ' cast(ATENDIDAS_1 as float) + cast(ATENDIDAS_2 as float) '; 
					$sql_b_faixa_horario = ' cast(ABANDONADAS as float) - cast(abandonadas_1 as float) - cast(ABANDONADAS_2 as float)  '; 	
					
					$sql_a_diario = ' sum(cast(ATENDIDAS_1 as float)) + sum(cast(ATENDIDAS_2 as float))   '; 
					$sql_b_diario = ' sum(cast(ABANDONADAS as float)) - sum(cast(abandonadas_1 as float)) - sum(cast(ABANDONADAS_2 as float)) ';
				}
				else //tolerancia de 90 segundos para o dia
				{
					$sql_a_faixa_horario = ' cast(ATENDIDAS_1 as float) + cast(ATENDIDAS_2 as float) + cast(ATENDIDAS_3 as float) '; 
					$sql_b_faixa_horario = ' cast(ABANDONADAS as float) - cast(abandonadas_1 as float) - cast(ABANDONADAS_2 as float) - cast(ABANDONADAS_3 as float) '; 	
					
					$sql_a_diario = ' sum(cast(ATENDIDAS_1 as float)) + sum(cast(ATENDIDAS_2 as float)) + sum(cast(ATENDIDAS_3 as float))  '; 
					$sql_b_diario = ' sum(cast(ABANDONADAS as float)) - sum(cast(abandonadas_1 as float)) - sum(cast(ABANDONADAS_2 as float)) - sum(cast(ABANDONADAS_3 as float)) ';
				}					 			 
						
					
				//Fila Premium
				if (in_array($cod_fila, $vet_todas_premium))
				{
					$swhere =  ' cod_fila in ('.$in_filas_premium.') ';
					$nsr_divisor = $nsr_premium_valor;		
				}
				else //Fila Normal 
				{
					$swhere =  ' cod_fila not in ('.$in_filas_premium.') ';				
					$nsr_divisor = $nsr_valor;
				}
				
				//---------------Calculo por Faixa de horário--------------------//	
				//Pegando a 'menor' correlação, ou seja o menor NS
				$n_nsa = 0;
				$n_ns = 0;
				//erro aqui			
				$sql = "select top 1 
													(
														$sql_a_faixa_horario
													) 
													/ 
													(
														cast(ATENDIDAS as float) + 
																					(
																						$sql_b_faixa_horario 
																					) 
													) as n_nsa
										from tb_fila_acumulado
										where data = '$qual_ano-$qual_mes-$pos_dia' and atendidas > 0 and cod_fila in ($in_todas_filas) and $swhere
										order by n_nsa";
                $query = $pdo->prepare($sql);                                        
				$query->execute();
				for($q=0; $row = $query->fetch(); $q++)
				{
					$n_nsa = $row['n_nsa'];
					$n_ns = $n_nsa / $nsr_divisor; 
				}
					
				//Nível de serviço por faixa de horário			
				if ($n_ns >= '0.90') 
					$ns_faixa_horario = 1;
				else 
					$ns_faixa_horario = 0;
				
				//---------------Calculo Diário---------------------------------//	
				//Pegando a 'menor' correlação, ou seja o menor NS
				$nd_nsa = 0;
				$nd_ns = 0;
				
				$query = $pdo->prepare("select top 1 cod_fila, 
											( 
												$sql_a_diario 
											) 
											/ 
											( 
												sum(cast(ATENDIDAS as float)) + (
																					$sql_b_diario
																				) 
											) as nd_nsa
										from tb_fila_acumulado
										where data = '$qual_ano-$qual_mes-$pos_dia' and atendidas > 0 and 
										cod_fila in ($in_todas_filas) and $swhere
										group by cod_fila
										order by nd_nsa");
				$query->execute();
				
				for($q=0; $row = $query->fetch(); $q++)
				{
					$nd_nsa = $row['nd_nsa'];
					$nd_ns = $nd_nsa / $nsr_divisor;
				}
											
				//Nível de serviço todas as filas no DIA
				if ($nd_ns >= '0.95') 
					$ns_todas_filas = 1;
				else 
					$ns_todas_filas = 0; 						 																				    															
				*/
				
				//Valor NSA
				$valor_nsa = $valor_a / ($valor_b + $valor_c);					
					
				//Calculando o Nível de Serviço
				if (in_array($cod_fila, $vet_todas_premium))
					$valor_nsr = $nsr_premium;
				else 
					$valor_nsr = $nsr;	
					
				if($valor_nsa >= $valor_nsr) 
					$valor_nivel_de_servico = 1;
				else 
					$valor_nivel_de_servico = $valor_nsa/$valor_nsr;
						
				if ($valor_nivel_de_servico < 0.90) 
					$ns_todas_filas_2 = 0; // regra mensal																
						
				$valor_pg = $qtde_ca * $valor_atendimento;
				
				
				//recebem 25% de ACP em todas os atendimentos	
				if (in_array($cod_fila, $vet_retencao) or in_array($cod_fila, $vet_triagem) or in_array($cod_fila, $vet_parcelamento) or in_array($cod_fila, $vet_perda_roubo))  
				{
					//percentual de aplicado de ACP para a fila, definido pela CERAT	
					$imp_acp_aplicado = '25';
						
					//apenas para printar a linha com formato diferenciado	
					$acp_aut = true;	 	
					
					$qtde_acp = $qtde_ca;	
					$fator = 1.25;
								
				}//recebem 25% de ACP para atendimentos de transferência interna
				else if (in_array($cod_fila, $vet_contestacao) or in_array($cod_fila, $vet_pontos) )
				{
					//percentual de aplicado de ACP para a fila, definido pela CERAT	
					$imp_acp_aplicado = '25';	
					
					if (in_array($cod_fila, $vet_contestacao))				
					  $ilha_filtro = $ilha_contestacao;				
					else if (in_array($cod_fila, $vet_pontos))	
					  $ilha_filtro = $ilha_pontos;
									 
					 $query = $pdo->prepare("select count(*) cont from (
																		select distinct  callid from tb_eventos_dac ted 
																		where ted.data_hora between '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																		AND ted.CALLID IS NOT NULL AND ted.TEMPO_ATEND > '0' 						
																		AND ted.COD_FILA IN ('$cod_fila')
																		and ted.callid in ( --codigo seleciona todas as chamadas com o mesmo callid e iniciada 'antes' 
																							select ted2.callid from tb_eventos_dac ted2 
																							where ted2.data_hora between '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																							AND ted2.CALLID IS NOT NULL AND ted2.TEMPO_ATEND > '0'
																							and ted2.CALLID = ted.CALLID
																							and ted2.data_hora < ted.data_hora
																							)
																		and ted.callid not in ( --codigo seleciona as chamadas com o mesmo callid oriundas da mesma ilha iniciada 'antes' 
																							select ted3.callid from tb_eventos_dac ted3 
																							where ted3.data_hora between '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																							AND ted3.CALLID IS NOT NULL AND ted3.TEMPO_ATEND > '0'
																							and ted3.CALLID = ted.CALLID
																							and ted3.data_hora < ted.data_hora
																							and ted3.cod_fila in ($ilha_filtro)
																							)
																		
																							
																	  ) as A
					  						");
															 			
					$query->execute();
					for($tt=0; $row = $query->fetch(); $tt++)
					{
						$qtde_acp = $row['cont']; //aqui
						$fator = 1.25;
					}	
				} //recebem 05% de ACP para atendimentos direto da ura, por isso a clausula NOT IN
				else if (in_array($cod_fila, $vet_todas_premium))
				{
					//percentual de aplicado de ACP para a fila, definido pela CERAT	
					$imp_acp_aplicado = '05';	
					
					if (($cod_fila == 84) or ($cod_fila == 85) or ($cod_fila == 96) or ($cod_fila == 97) or ($cod_fila == 82) or ($cod_fila == 83))
					{
						$qtde_acp = 0;
						$fator = 1;
					}    
					else
					{
						$query = $pdo->prepare(" select count(*) cont from (
																				select  distinct callid from tb_eventos_dac ted 
																				where ted.data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
															  					and ted.callid is not null and ted.tempo_atend > '0' 
															  					and ted.cod_fila in ('$cod_fila')
															  					and ted.callid NOT in ( 
															  										select ted2.callid from tb_eventos_dac ted2 
																									where ted2.data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																									and ted2.callid is not null and ted2.tempo_atend > '0'
																									and ted2.callid = ted.callid
																									and ted2.data_hora < ted.data_hora
																								  )
																			) as A
							  						 ");
													 
						
						$query->execute();
						for($tt=0; $row = $query->fetch(); $tt++)
						{
							$qtde_acp = $row['cont']; //aqui
							$fator = 1.05;
						}
					}
				}																		
									
				$tabela[$pos_dia][$i] =	array(
												"COD_FILA" => $cod_fila,
												"NOME_FILA" => $nome_fila,
												"VALOR_A" => $valor_a,
												"VALOR_B" => $valor_b,
												"VALOR_C" => $valor_c,
												"VALOR_NSA" => $valor_nsa,
												"VALOR_NSR" => $valor_nsr,
												"VALOR_NS" => $valor_nivel_de_servico,
												"VALOR_TMA" => $valor_tma,
												"VALOR_SC" => $valor_sc,
												"VALOR_PCT_SC" => $perc_sc,
												"QTDE_CA" => $qtde_ca,
												"VALOR_BRUTO" => $valor_pg,
												"VALOR_PCT_ACP" => $imp_acp_aplicado,
												"QTDE_ACP" => $qtde_acp,
												"AUT_ACP"=>$acp_aut, 
												"FATOR_ACP"=>$fator,
												"APL_MULT_ANSM"=> 0.00,// a partir daqui dados da 'segunda' tabela, estes serao calculados posteriormente
												"AD_ACP"=> 0.00,
												"APLI_MULT_ACP"=> 0.00																											
											);
												
				
				//-----------------totalizadores diários-------------//								
				$qtde_ca_diario = $qtde_ca_diario + $qtde_ca; 							
				$valor_bruto_diario = $valor_bruto_diario + $valor_pg;
				
				// soma do nível de serviço para gerar valor ansm para dedução
				$soma_ansm = $soma_ansm + $valor_nivel_de_servico;
				$cont_ansm++;
				
			}//final if ($qtde_ca >0)								  
			 
		}// final percorrendo o array $vet_todas_filas
		
		//----calculando a média de ansm do dia --------//			
		$dia_ansm = "ansm$pos_dia";
		
		if ($$dia_ansm != '0.00') 
			$media_ansm = $$dia_ansm;
		else 
			$media_ansm = $soma_ansm/$cont_ansm; //calcula valor ansm
			
		//--------------Adicionando os valores de ANSM e ACP nas filas---------------//
		$total_ansm = 0;
		$pg_total_dia = 0;
		$count = count($vet_todas_filas);
		for ($i = 0; $i < $count; $i++)
		{
			if (isset($tabela[$pos_dia][$i]["QTDE_CA"]))    			                           				
			{
			    $qtde_ca = $tabela[$pos_dia][$i]["QTDE_CA"];   
				$valor_pg = $tabela[$pos_dia][$i]["VALOR_BRUTO"];			
				$qtde_acp = $tabela[$pos_dia][$i]["QTDE_ACP"];			
				$aplicacao_ansm = $valor_pg * $media_ansm;			
				
				//--------------calculando adicional ACP-----------aqui mano
				//$qtde_acp = 0; //código para anular a implementação de adição de retidos
				//$fator = 1;
				 
				$p_acp = 0;
				$p_acp =  (($aplicacao_ansm/$qtde_ca)*$qtde_acp); //parte de retidos em R$
				$ad_acp = (($p_acp*$fator) - $p_acp); 		
				
				//------------------calculo segunda tabela---------------//			
				$apli_mult_acp =  $aplicacao_ansm + $ad_acp;
				
				$tabela[$pos_dia][$i]["APL_MULT_ANSM"]	= $aplicacao_ansm;
				$tabela[$pos_dia][$i]["AD_ACP"]	= $ad_acp;
				$tabela[$pos_dia][$i]["APLI_MULT_ACP"]	= $apli_mult_acp;  
				
				//------------------totalizadores diarios--------------//  
				$total_ansm = $total_ansm + $aplicacao_ansm;
				$pg_total_dia = $pg_total_dia + $apli_mult_acp;
			} 			
		}		
								
			 
		
		//--------------------TOTAIS DO DIA (Cabeçalho do Array)----------------------//									  
		$tabela[$pos_dia]["ANSM"] = $media_ansm;
		$tabela[$pos_dia]["QTDE_AT_ELETRONICO"] = $qtd_ura;
		$tabela[$pos_dia]["QTDE_AT_HUMANO"] = $qtde_ca_diario;
		$tabela[$pos_dia]["QTDE_AT_TOTAL"] = ($qtde_ca_diario + $qtd_ura);
		$tabela[$pos_dia]["REM_AT_H_BRUTO"] = $valor_bruto_diario;		 
		$tabela[$pos_dia]["DESC_ANSM_DIARIO"] = ($valor_bruto_diario - $total_ansm);
		$tabela[$pos_dia]["AD_ACP_DIARIO"] = ($pg_total_dia - $total_ansm);				
		$tabela[$pos_dia]["REM_AT_ELETRONICO"] = ($qtd_ura * $valor_atendimento_ura);
		$tabela[$pos_dia]["REM_AT_HUMANO"] = $tabela[$pos_dia]["REM_AT_H_BRUTO"] - $tabela[$pos_dia]["DESC_ANSM_DIARIO"] + $tabela[$pos_dia]["AD_ACP_DIARIO"];
		$tabela[$pos_dia]["REM_AT_TOTAL"] = $tabela[$pos_dia]["REM_AT_HUMANO"] + $tabela[$pos_dia]["REM_AT_ELETRONICO"];
		  							
		$mensal_total_qtd_ura = $mensal_total_qtd_ura + $qtd_ura;		
		$mensal_total_bruto = $mensal_total_bruto + $valor_bruto_diario;
		$mensal_qtde_ca = $mensal_qtde_ca + $qtde_ca_diario;
		$mensal_total = $mensal_total + $tabela[$pos_dia]["REM_AT_TOTAL"]; // 
		$mensal_humano = $mensal_humano + $tabela[$pos_dia]["REM_AT_HUMANO"]; 
		$mensal_ura = $mensal_ura + $tabela[$pos_dia]["REM_AT_ELETRONICO"];
		$mensal_total_desc_ansm = $mensal_total_desc_ansm + $tabela[$pos_dia]["DESC_ANSM_DIARIO"];
		$mensal_total_acre_acp = $mensal_total_acre_acp + $tabela[$pos_dia]["AD_ACP_DIARIO"];
		
		//------------------------------------------------------IMPRESSÕES------------------------------------------------------------------//						
		//Cabeçalho do Dia 
		echo "<div class='w3-container'>";	
		echo "<div class='w3-padding w3-margin-bottom w3-tiny w3-center w3-dark-grey w3-wide w3-card-4'><b>$pos_dia de $mes de $qual_ano</b></div>";
		
		//PRIMEIRA TABELA 
		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-dark-grey w3-tiny">';
		echo '<td><b>FILA</b></td>';
		echo '<td><b>A</b></td>';
		echo "<td><b>B</b></td>";
		echo "<td><b>C</b></td>";
		echo "<td><b>NSA</b></td>";
		echo "<td><b>NSR</b></td>";
		echo "<td><b>NS</b></td>";
		echo '<td><b>TMA</b></td>';
		echo "<td><b>SHORTCALL</b></td>";
		echo "<td><b>SHORTCALL(%)</b></td>";
		echo "<td><b>CA</b></td>";
		echo "<td><b>VALOR BRUTO</b></td>";
		echo '</tr>';					
											
		$count = count($vet_todas_filas);
		for ($i = 0; $i < $count; $i++)
		{
		    if (isset($tabela[$pos_dia][$i]["QTDE_CA"]))    			          
			{
			    $qtde_ca = $tabela[$pos_dia][$i]["QTDE_CA"];
				$cod_fila = $tabela[$pos_dia][$i]["COD_FILA"];
				$nome_fila = $tabela[$pos_dia][$i]["NOME_FILA"];
				$valor_a = $tabela[$pos_dia][$i]["VALOR_A"];
				$valor_b = $tabela[$pos_dia][$i]["VALOR_B"];  
				$valor_c = $tabela[$pos_dia][$i]["VALOR_C"];										
				$valor_nsa = number_format($tabela[$pos_dia][$i]["VALOR_NSA"], 10, ',', '.');					
				$valor_nsr = number_format($tabela[$pos_dia][$i]["VALOR_NSR"], 2, ',', '.');					  
				$valor_nivel_de_servico = number_format($tabela[$pos_dia][$i]["VALOR_NS"], 10, ',', '.');					
				$valor_tma = $tabela[$pos_dia][$i]["VALOR_TMA"];
				$valor_sc = $tabela[$pos_dia][$i]["VALOR_SC"];  
				$perc_sc = number_format($tabela[$pos_dia][$i]["VALOR_PCT_SC"], 2, ',', '.');
				$valor_pg = number_format($tabela[$pos_dia][$i]["VALOR_BRUTO"], 2, ',', '.'); 
				
				echo "<tr>";									
					echo "<td>$cod_fila <i>$nome_fila</i></td>"; 
					echo "<td>$valor_a</td>";
					echo "<td>$valor_b</td>";
					echo "<td>$valor_c</td>";
					echo "<td>$valor_nsa</td>";
					echo "<td>$valor_nsr</td>";
					echo "<td>$valor_nivel_de_servico";		
					echo "<td>$valor_tma</td>";				
					echo "<td>$valor_sc</td>";
					echo "<td>$perc_sc</td>";
					echo "<td>$qtde_ca</td>";
					echo "<td>R$ $valor_pg</td>";
					
				echo "</tr>";
			}
		}
		echo '</table>';
		echo "</div>";
		
		// SEGUNDA TABELA
		echo "<br>";
		echo "<div class='w3-container'>";
		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
		echo '<tr class="w3-dark-grey w3-tiny">';
		echo '<td><b>FILA</b></td>';
		echo '<td><b>CA</b></td>';
		echo "<td><b>VALOR BRUTO</b></td>";
		echo "<td><b>APL. DE MULT. ANSM</b></td>";
		echo "<td><b>ACP APLICADO</b></td>";
		echo "<td><b>Q. ACP</b></td>";
		echo "<td><b>AD. ACP</b></td>";
		echo "<td><b>APLI. DE MULT. ACP</b></td>";
		echo '</tr>';
		
		$count = count($vet_todas_filas);
		for ($i = 0; $i < $count; $i++)
		{
			if (isset($tabela[$pos_dia][$i]["QTDE_CA"]))                              
			{
				$qtde_ca = $tabela[$pos_dia][$i]["QTDE_CA"];
				$acp_aut = $tabela[$pos_dia][$i]["AUT_ACP"];				
				$cod_fila = $tabela[$pos_dia][$i]["COD_FILA"];
				$nome_fila = $tabela[$pos_dia][$i]["NOME_FILA"];
				$valor_pg = number_format($tabela[$pos_dia][$i]["VALOR_BRUTO"], 2, ',', '.');
				$aplicacao_ansm = number_format($tabela[$pos_dia][$i]["APL_MULT_ANSM"], 2, ',', '.');
				$imp_acp_aplicado = $tabela[$pos_dia][$i]["VALOR_PCT_ACP"];
				$qtde_acp = $tabela[$pos_dia][$i]["QTDE_ACP"];
				$ad_acp	= number_format($tabela[$pos_dia][$i]["AD_ACP"], 2, ',', '.');									
				$apli_mult_acp = number_format($tabela[$pos_dia][$i]["APLI_MULT_ACP"], 2, ',', '.');	

									
				echo "<tr>";									
					if ($acp_aut) 
						echo "<td>$cod_fila<b><u><i>$nome_fila</i></u></b></td>"; //Destacando as filas com ACP automático	
					else
						echo "<td>$cod_fila <i>$nome_fila</i></td>"; 
					
					echo "<td>$qtde_ca</td>";
					echo "<td>R$ $valor_pg</td>";
					echo "<td>R$ $aplicacao_ansm</td>";					
					echo "<td>$imp_acp_aplicado%</td>";			
					echo "<td>$qtde_acp</td>";
					echo "<td>R$ $ad_acp</td>";
					echo "<td>R$ $apli_mult_acp</td>";					
				echo "</tr>";
			}
		}
		echo '</div>';
		echo '</table>';
		echo "</div>";
		
		
		//TOTALIZAÇÃO DIÁRIA		
		echo '<br>';	
		echo "<div class='w3-container'>";
		echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';			
		echo '<tr class="w3-black">';
		echo "<td><b>RESUMO DE FATURAMENTO - DIÁRIO</b></td>";
		echo "<td><b>$pos_dia de $mes de $qual_ano</b></td>";
		echo '</tr>';
	
		// imprime ansm
		$imprime_media_ansm = number_format($tabela[$pos_dia]["ANSM"], 10, ',', '.');
		if ($$dia_ansm != '0.00')
			$linha = "<td>ALCANCE DE NÍVEL DE SERVIÇO MÉDIO (ANSM) <b><i>- Dia com Aplicação de Revisão de Nível</i><b></td>";
		else 
			$linha = "<td>ALCANCE DE NÍVEL DE SERVIÇO MÉDIO (ANSM)</td>"; 
			
		echo '<tr>';
		echo $linha;
		echo "<td>$imprime_media_ansm</td>";
		echo '</tr>';
		
		// imprime qtde ura - diario		
		$imprime_qtd_ura = number_format($tabela[$pos_dia]["QTDE_AT_ELETRONICO"], 0, ',', '.');		
		echo '<tr">';
		echo "<td>QUANTIDADE DE ATENDIMENTOS ELETRÔNICOS</td>";
		echo "<td>$imprime_qtd_ura</td>";
		echo '</tr>';	
			
		// imprime total humano
		$imprime_total_ca = number_format($tabela[$pos_dia]["QTDE_AT_HUMANO"], 0, ',', '.');
		echo '<tr>';
		echo "<td>QUANTIDADE DE ATENDIMENTOS HUMANOS</td>";
		echo "<td>$imprime_total_ca</td>";
		echo '</tr>';	
			
		// imprime total eletrônico + humano
		$imprime_total_dia_hum_ura = number_format($tabela[$pos_dia]["QTDE_AT_TOTAL"], 0, ',', '.');
		echo '<tr>';
		echo "<td>QUANTIDADE DE ATENDIMENTOS TOTAL</td>";
		echo "<td>$imprime_total_dia_hum_ura</td>";
		echo '</tr>';
	
		// imprime bruto diário 
		$imprime_valor_bruto_diario = number_format($tabela[$pos_dia]["REM_AT_H_BRUTO"], 2, ',', '.');
		echo '<tr>';
		echo "<td><b>REMUNERAÇÃO ATENDIMENTO HUMANO BRUTO</b></td>";
		echo "<td><b>R$ $imprime_valor_bruto_diario</b></td>";
		echo '</tr>';		
	
		// imprime total desconto de ansm	
		$imprime_dif_bruto_ansm = number_format($tabela[$pos_dia]["DESC_ANSM_DIARIO"], 2, ',', '.');
		echo '<tr>';
		echo "<td>ALCANCE DE NÍVEL DE SERVIÇO MÉDIO (ANSM) - DESCONTOS</td>";
		echo "<td>R$ $imprime_dif_bruto_ansm</td>";
		echo '</tr>';	
			
		// imprime total adicional de acp
		$imprime_adc_acp = number_format($tabela[$pos_dia]["AD_ACP_DIARIO"], 2, ',', '.');
		echo '<tr>';
		echo "<td>ADICIONAL DE COMPLEXILIDADE E PRIORIDADE (ACP): ADICIONAIS</td>";
		echo "<td>R$ $imprime_adc_acp</td>";
		echo '</tr>';
		
			
		// imprime faturamento atendimento ura	
		$imprime_total_ura = number_format($tabela[$pos_dia]["REM_AT_ELETRONICO"], 2, ',', '.');
		echo '<tr">';
		echo "<td><b>REMUNERAÇÃO ATENDIMENTO ELETRÔNICO</b></td>";
		echo "<td><b>R$ $imprime_total_ura</b></td>";
		echo '</tr>';
			
	
		// imprime pg total dia
		$imprime_pg_total_dia = number_format($tabela[$pos_dia]["REM_AT_HUMANO"], 2, ',', '.');
		echo '<tr>';
		echo "<td><b>REMUNERAÇÃO ATENDIMENTO HUMANO</b></td>";
		echo "<td><b>R$ $imprime_pg_total_dia</b></td>";
		echo '</tr>';		
			
		$imprime_total_geral = number_format($tabela[$pos_dia]["REM_AT_TOTAL"], 2, ',', '.');
		echo '<tr">';
		echo "<td><b>REMUNERAÇÃO TOTAL</b></td>";
		echo "<td><b>R$ $imprime_total_geral</b></td>";
		echo '</tr>';		
			
		// finaliza nova tabela
		echo '</table>';
		echo '</div>';
		echo '<hr>';
						
	} //final FOR diário... 


// imprime tabela final
echo "<div class='w3-container'>";
echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';

echo '<tr class="w3-black">';
echo "<td><b>RESUMO DE FATURAMENTO - MENSAL</b></td>";
echo "<td><b>$mes de $qual_ano</b></td>";
echo '</tr>';

$imprime_mensal_total_ca = number_format($mensal_qtde_ca, 0, ',', '.');
echo '<tr">';
echo "<td>QUANTIDADE DE ATENDIMENTOS HUMANOS</td>";
echo "<td>$imprime_mensal_total_ca</td>";
echo '</tr>';

$imprime_mensal_total_qtd_ura = number_format($mensal_total_qtd_ura, 0, ',', '.');
echo '<tr">';
echo "<td>QUANTIDADE DE ATENDIMENTOS ELETRÔNICOS</td>";
echo "<td>$imprime_mensal_total_qtd_ura</td>";
echo '</tr>';

$atd_atend_total = $mensal_qtde_ca + $mensal_total_qtd_ura;
$imprime_atd_atend_total = number_format($atd_atend_total, 0, ',', '.');
echo '<tr">';
echo "<td>QUANTIDADE DE ATENDIMENTOS TOTAL</td>";
echo "<td>$imprime_atd_atend_total</td>";
echo '</tr>';

$imprime_mensal_total_bruto = number_format($mensal_total_bruto, 2, ',', '.');
echo '<tr">';
echo "<td><b>REMUNERAÇÃO ATENDIMENTO HUMANO BRUTO</b></td>";
echo "<td><b>R$ $imprime_mensal_total_bruto</b></td>";
echo '</tr>';

$imprime_mensal_total_desc_ansm = number_format($mensal_total_desc_ansm, 2, ',', '.');
echo '<tr">';
echo "<td>ALCANCE DE NÍVEL DE SERVIÇO MÉDIO (ANSM) - DESCONTOS</td>";
echo "<td>R$ $imprime_mensal_total_desc_ansm</td>";
echo '</tr>';

$imprime_mensal_total_acre_acp = number_format($mensal_total_acre_acp, 2, ',', '.');
echo '<tr">';
echo "<td>ADICIONAL DE COMPLEXIDADE E PRIORIDADE (ACP) - ADICIONAIS</td>";
echo "<td>R$ $imprime_mensal_total_acre_acp</td>";
echo '</tr>';

$imprime_mensal_humano = number_format($mensal_humano, 2, ',', '.');
echo '<tr">';
echo "<td><b>REMUNERAÇÃO ATENDIMENTO HUMANO (Contabilizado ANSM + ACP)</b></td>";
echo "<td><b>R$ $imprime_mensal_humano</b></td>";
echo '</tr>';

$imprime_dns = number_format($dns, 10, ',', '.');
echo '<tr">';
echo "<td>DISPERSÃO DE NÍVEL DE SERVIÇO POR FAIXA DE HORÁRIO (DNS)</td>";
echo "<td>$imprime_dns</td>";
echo '</tr>';

$desc_dns = $mensal_humano - ($mensal_humano * $dns);
$imprime_desc_dns = number_format($desc_dns, 2, ',', '.');
echo '<tr">';
echo "<td>DNS - DESCONTOS</td>";
echo "<td>R$ $imprime_desc_dns</td>";
echo '</tr>';

$imprime_iqm = number_format($iqm, 10, ',', '.');
echo '<tr">';
echo "<td>ÍNDICE DE QUALIDADE MENSAL (IQM)</td>";
echo "<td>$imprime_iqm</td>";
echo '</tr>';

$desc_iqm = $mensal_humano - ($mensal_humano * $iqm);
$imprime_desc_iqm = number_format($desc_iqm, 2, ',', '.');
echo '<tr">';
echo "<td>IQM - DESCONTOS</td>";
echo "<td>R$ $imprime_desc_iqm</td>";
echo '</tr>';

$hum_mensal = $mensal_humano * $iqm * $dns;
$imprime_hum_mensal = number_format($hum_mensal, 2, ',', '.');
echo '<tr">';
echo "<td><b>REMUNERAÇÃO ATENDIMENTO HUMANO (Contabilizado ANSM + ACP + DNS + IQM)</b></td>";
echo "<td><b>R$ $imprime_hum_mensal</b></td>";
echo '</tr>';


$imprime_mensal_ura = number_format($mensal_ura, 2, ',', '.');
echo '<tr">';
echo "<td><b>REMUNERAÇÃO ATENDIMENTO ELETRÔNICO</b></td>";
echo "<td><b>R$ $imprime_mensal_ura</b></td>";
echo '</tr>';


//$mensal_total = $mensal_retido + $mensal_ura + $mensal_humano; // DEFINE MENSAL TOTAL COM RETIDOS
$mensal_total = ($mensal_ura + ($mensal_humano * $iqm))*$dns; // DEFINE MENSAL TOTAL SEM RETIDOS
$imprime_mensal_total = number_format($mensal_total, 2, ',', '.');

echo '<tr">';
echo "<td><b>REMUNERAÇÃO TOTAL (SEM GLOSA)<b></td>";
echo "<td><b>R$ $imprime_mensal_total</b></td>";
echo '</tr>';

	// CÁLCULO GLOSAS
	$aplicacao_glosas = 0;
	
	if($glosa1 != 0){
		$aplicacao_glosas = $glosa1 * 0.1 * $mensal_total / 100;
	}

$imprime_aplicacao_glosas = number_format($aplicacao_glosas, 2, ',', '.');
echo '<tr">';
echo "<td>GLOSAS - QUANTIDADE / DESCONTOS</td>";
echo "<td>$glosa1 / R$ $imprime_aplicacao_glosas</td>";
echo '</tr>';

$imprime_acertos_acre = number_format($acertos_acre, 2, ',', '.');
echo '<tr">';
echo "<td>Acertos - Acréscimos</td>";
echo "<td>R$ $imprime_acertos_acre</td>";
echo '</tr>';

$imprime_acertos_decre = number_format($acertos_decre, 2, ',', '.');
echo '<tr">';
echo "<td>Acertos - Decréscimos</td>";
echo "<td>R$ $imprime_acertos_decre</td>";
echo '</tr>';

$mensal_total = $mensal_total - $aplicacao_glosas + $acertos_acre - $acertos_decre;// FATURAMENTO TOTAL - GLOSAS - DNS
$imprime_mensal_total = number_format($mensal_total, 2, ',', '.');
echo '<tr">';
echo "<td><b>REMUNERAÇÃO TOTAL (COM GLOSA E ACERTOS)</b></td>";
echo "<td class='w3-font-red'><b>R$ $imprime_mensal_total</b></td>";
echo '</tr>';

echo '</table>';
echo '<hr>';


    // -----------------------------------------FINALIZANDO O CONTADOR DE TEMPO DA CONSULTA ------------------------------------------//
    list($usec, $sec) = explode(' ', microtime());
    $script_end = (float) $sec + (float) $usec;
    $elapsed_time = round($script_end - $script_start, 5);
   
    $elapsed_time = intval($elapsed_time);
    if ($elapsed_time >= 60)
    {
        $minutos = intval($elapsed_time/60);
        $segundos = ((($elapsed_time/60) - $minutos)*60);
    }
    else
    {
        $minutos = 0;
        $segundos = $elapsed_time;
    }    
    
    
    if ($minutos == 1)
      $texto_tempo = "$minutos minuto ";
    else if ($minutos > 1) 
      $texto_tempo = "$minutos minutos ";
    else
      $texto_tempo = "";
    
    if (($texto_tempo != "") and ($segundos >0))
      $texto_tempo = $texto_tempo." e ";
    
    if ($segundos == 1)
      $texto_tempo = $texto_tempo."$segundos segundo";
    else if ($segundos > 1)  
      $texto_tempo = $texto_tempo."$segundos segundos"; 
   
    echo '<div class="w3-margin w3-tiny w3-center">';
    echo "<b>Tempo de Execução: </b><i>$texto_tempo</i><br>";
    echo '<br><br>';

// DESCONECTA BANCO DE DADOS
include "desconecta.php";
?>

</body>
</html>