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
    $ns_normal = $_POST['ns_normal']; //Tempo de Espera Padrão(segundos):
    
    //-------------------DIA NORMAL-------------------//
    $nsr = ($_POST['nsr']/100); //nivel de serviço referencial - Filas Convencionais
    $nsr_premium = ($_POST['nsr_premium']/100); //nivel de serviço referencial - Filas Premium
    
    //----------------------DMM---------------------//
    $dmm_nsr = ($_POST['dmm_nsr']/100); //nivel de serviço referencial - Filas Convencionais
    $dmm_nsr_premium = ($_POST['dmm_nsr_premium']/100); //nivel de serviço referencial - Filas Premium
    
    
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
    $acp_pontos = '25';//$_POST['acp_pontos'];
    //$acp_geral_normal = '00';//$_POST['acp_geral_normal']-ORIGINAL;
    $acp_geral_normal = '00';//$_POST['acp_geral_normal'];
    //$acp_todas_premium = '05';//$_POST['acp_geral_premium']-ORIGINAL;
    $acp_todas_premium = '25';//$_POST['acp_geral_premium'];
    $acp_pj = '00';//$_POST['acp_pj'];
    $acp_100 = '00';//$_POST['acp_pj'];
    $acp_130 = '00';//$_POST['acp_pj'];
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
    /* NSR irá variar de acordo com o DMM
    $nsr_premium_valor = $nsr_premium;
    $nsr_valor = $nsr;
    */

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
    if($qual_mes=='02') $qtd_dias = 28;
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
    $mensal_total_ca = 0;
    $mensal_saldocb = 0;
    $mensal_total_qtd_ura = 0;
    $mensal_total_bruto = 0;
    $mensal_total_desc_ansm = 0;
    $mensal_total_acre_acp = 0;
    
    //$mensal_retido = 0;
    $mensal_ura = 0;
    $mensal_humano = 0;
    $mensal_total = 0;



    //define vetores das ilhas 
    $vet_retencao = array('73','77','81','116');
    $vet_aviso_viagem = array('125');
    $vet_triagem = array('150','138');
    $vet_parcelamento = array('72','76','80','111');
    $vet_contestacao = array('60','88','90','93');//tirei
    $vet_pontos = array('87','91','94','139'); //tirei '120'
    $vet_geral_normal = array('70','71','74','75','78','79','86','58','89','92','95','114','118','137','126');//tirei 57 é perda e roubo
    $vet_geral_premium = array('82','83','98','107','85','84','96','97'); //Reagregando as filas premium 

    $vet_pj = array('99','101','110');/*,'100' ,'100','130'*/
    $vet_130 = array('130');
    $vet_100 = array('100');
    $vet_caixa_empregado = array('63');
    $vet_deficiente_auditivo = array('61');
    $vet_mala_direta = array('64');
    $vet_perda_roubo = array('57');
    $vet_bloqueio_cobranca = array('117','106','108','109');//tirei A 107 pois categorizei como geral premium por conta do ACP

    $vet_app = array('102');
    $vet_todas_premium = array('82','83','98','84','96','97','85','107');

    $vet_todas_filas = array_merge(/*$vet_todas_premium*/$vet_geral_premium, $vet_app, $vet_bloqueio_cobranca, 
    							   $vet_retencao, $vet_triagem, $vet_aviso_viagem, 
    							   $vet_parcelamento, $vet_contestacao, $vet_pontos, 
    							   $vet_geral_normal,$vet_pj, 
    							   $vet_caixa_empregado, $vet_deficiente_auditivo, 
    							   $vet_mala_direta,$vet_perda_roubo,$vet_100,$vet_130);

    //conta tamanho vet_todas_filas
    $num_filas = count($vet_todas_filas);

    //define todas as filas nsr maior


    //define 'in' das ilhas
    $ilha_retencao = "'73','77','81','85','116'";
    $ilha_triagem = "'150','138'";
    $ilha_aviso_viagem = "'125'";
    $ilha_parcelamento = "'72','76','80','84','111'";
    $ilha_contestacao = "'60','88','90','93','96'";
    $ilha_pontos = "'87','91','94','97','139'"; //'120',
    $ilha_pj = "'99','101','110'";/*'100', ,'100','130'*/
    $ilha_100 = "'100'";
    $ilha_130 = "'130'";
    $ilha_caixa_empregado = "'63'";
    $ilha_deficiente_auditivo = "'61'";
    $ilha_mala_direta = "'64'";
    $ilha_geral_normal = "'70','71','74','75','78','79','86','58','89','92','95','114','118','57','137','126'";/*'102',*/
    $ilha_geral_premium = "'82','83','98'";
    $ilha_bloqueio_cobranca = "'117','106','107','108','109'";
    $ilha_app = "'102'";

    //31/10/2016 (Fabiano) adicionado a fila 125, retirada fila '100', '100','130', ,'120'
    $in_todas_filas = "'73','77','81','85','116','150','72','76','80','84','111','60','88','90','93','96','87','91','94','97','70','71','74','75','78','79','86','58','89','92','95','102','106','108','109','114','118','57','82','83','98','107','99','101','110','63','61','64','117','125','137','126','100','130'";
    
    $in_filas_premium = "'82','83','84','85','96','97','98','107'";

    // inclui variáveis
    for($cont=0; $cont<$num_filas; $cont++)
    {
    	$fila_atual = $vet_todas_filas[$cont];
    	
    	$var_b = "b_$fila_atual"; // Total de Ligações
    	$$var_b = 0;
    
    	$var_a = "a_$fila_atual"; // Atendidas até o Tempo Limite (NS)
    	$$var_a = 0;
    
    	$var_c = "c_$fila_atual"; // Ligações Abandonadas Após Tempo Limite (NS)
    	$$var_c = 0;
    
    	$var_ca = "ca_$fila_atual"; // Chamadas Pagas
    	$$var_ca = 0;
    
    	$var_tma = "tma_$fila_atual"; // TMA
    	$$var_tma = 0;
    
    	$var_sc = "sc_$fila_atual"; // SHORTCALL
    	$$var_sc = 0;
    	
    	$var_pg = "pg_$fila_atual"; // PG por fila
    	$$var_pg = 0;
    	
    	$var_nsa = "nsa_$fila_atual";
    	$$var_nsa = 0;
    	
    	$var_ns = "ns_$fila_atual";
    	$$var_ns = 0;
    	
    	$pg_total_dia = 0; // PG total do dia, sem adicionais (ACP, etc)
    
    	$soma_ansm = 0;
    	$cont_ansm = 0;
    
    	$qtd_ura = 0;
    
    	$perc_sc = 0;
    
    	$total_ca = 0;
    	$total_saldocb = 0;
    	
    	$valor_bruto_diario = 0;
    	
    	$total_ansm = 0;
    
    	$total_ura = 0;
    
    	$qtd_ura = 0;
    
    	$total_faturamento = 0;
    }//FINAL FOR

    // calcula iqm
    if ($iqf < 90)
    {
    	$iqf_perc = $iqf / 100;
    	$iqm = ($iqf_perc + ( (1-$iqf_perc)*0.8) );
    } 
    else 
    	$iqm = 1;

     
 $dns = $qual_dns; 

// imprime parâmetros - início
echo "<div class = 'w3-leftbar w3-border-black w3-margin-left'><div class='w3-margin-left w3-tiny'><b>Parâmetros Utilizados:</b></div>";
echo "<br>";
echo "<div class='w3-margin-left w3-tiny'>Período da Pesquisa: $mes / $qual_ano</div>";
echo "<div class='w3-margin-left w3-tiny'>Limite para pagamento de Shortcall: $shortcall_porcentagem%</div>";
echo "<div class='w3-margin-left w3-tiny'>Tempo Shortcall: $shortcall_tempo segundos</div>";

$imp_nsr = $nsr * 100;
$imp_nsr_premium = $nsr_premium * 100;

$dmm_imp_nsr = $dmm_nsr * 100;
$dmm_imp_nsr_premium = $dmm_nsr_premium * 100;

echo "<div class='w3-margin-left w3-tiny'>NSR (S/DMM) - Filas Normais: $imp_nsr%</div>";
echo "<div class='w3-margin-left w3-tiny'>NSR (S/DMM) - Filas Premium: $imp_nsr_premium%</div>";
echo "<div class='w3-margin-left w3-tiny'>NSR (C/DMM) - Filas Normais: $dmm_imp_nsr%</div>";
echo "<div class='w3-margin-left w3-tiny'>NSR (c/DMM) - Filas Premium: $dmm_imp_nsr_premium%</div>";
echo "<div class='w3-margin-left w3-tiny'>Tempo de espera padrão: $ns_normal segundos</div>";
echo "<div class='w3-margin-left w3-tiny'>Tempo de espera para dias de maior movimento: $ns_diferenciado segundos</div>";
echo "<div class='w3-margin-left w3-tiny'>Preço do minutos (Atendimento Humano): R$ $valor_atendimento</div>";
echo "<div class='w3-margin-left w3-tiny'>Preço do minutos (Atendimento Eletrônico): R$ $valor_atendimento_ura</div>";

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
echo "<hr>";

// imprime dia a dia - início


//for($pos_dia=20; ($pos_dia <= ($pos_dia+1)); $pos_dia++)//aqui
//
//for($pos_dia=22; ($pos_dia <= 22); $pos_dia++)
$qtd_dias = 10;
for($pos_dia=01; ($pos_dia <= $qtd_dias); $pos_dia++)
{ 
    
    /*verificacao se o dia em questão possui revisao de nivel*/
    $sql = "select count(*) qtde from tb_fat_revisao_nivel_DNS where data_revista = '$qual_ano-$qual_mes-$pos_dia'";
    //echo $sqlpremium;
    $query = $pdo->prepare($sql);
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $dia_revisao_nivel = $row['qtde'];        
    } 
    
    //$pos_dia= 10;
	//utilizado para avaliar a concessão de ACP de filas com NS < 90%
	$ns_todas_filas_2 = 1; //regra mensal
	
	// verifica ns (tempo de espera) 45s ou 90s
	if(isset($_POST["chk_$pos_dia"]))
	{
		$ns = $ns_diferenciado;
				
		$nsr_valor = $dmm_nsr;				
		$nsr_premium_valor = $dmm_nsr_premium;
				
	}
	else
	{
		$ns = $ns_normal;			
		
		$nsr_valor = $nsr;
		$nsr_premium_valor = $nsr_premium;
	}	
	
	// imprime container da data	
	echo "<div class='w3-container'>";	
	echo "<div class='w3-padding w3-margin-bottom w3-tiny w3-center w3-dark-grey w3-wide w3-card-4'><b>$pos_dia de $mes de $qual_ano</b></div>";
	
	// consulta tabela tb_filas
	$query = $pdo->prepare("SELECT * FROM TB_FILAS");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['cod_fila'];
		$nome_variavel_sc = "nome_fila_$cod_fila";
		$$nome_variavel_sc = $row['desc_fila'];
	}	

	// inicia tabela e imprime a primeira linha
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
	echo "<td><b>CB*</b></td>";
	echo "<td><b>DCB*</b></td>";
	echo '</tr>';
	
	//regra mensal * consultas
	//variaveis a ser utilizada na regra do ACP, para verificar o NS de serviço das demais filas
	
	
	$menor_ns_faixa_horario = 1;
	$menor_ns_faixa_horario_premium = 1;
				
	//------------TODAS AS FILAS----------------//	
	$sql = "EXEC sp_CERATFO_fat_menor_ns_intervalo_revisao_2 '$qual_ano-$qual_mes-$pos_dia','TODAS','MENOR_NS'";
	//echo $sqltodas;	
	$query = $pdo->prepare($sql);
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $nd_ns = $row['avg_ns'];    
	}
	
	if ($nd_ns < $menor_ns_faixa_horario)
	    $menor_ns_faixa_horario = $nd_ns;
		    
    //------------SOMENTE FILAS PREMIUM----------------//
	$sql = "EXEC sp_CERATFO_fat_menor_ns_intervalo_revisao_2 '$qual_ano-$qual_mes-$pos_dia','PREMIUM','MENOR_NS'";
    //echo $sqlpremium;
	$query = $pdo->prepare($sql);	
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $nd_ns = $row['avg_ns'];        
    }    
    
    $menor_ns_faixa_horario_premium = $nd_ns;  
	       
	
    //-------------------MENOR NIVEL DE SERVICO DA FILA------------------//                
    $sql = "EXEC sp_CERATFO_fat_menor_ns_revisao_2 '$qual_ano-$qual_mes-$pos_dia','MENOR_NS',0";
    //echo $sqlpremium;
    $query = $pdo->prepare($sql);
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $nd_nsa = $row['ns'];
        $srv_cod_fila = $row['cod_fila'];                        
    }    
    $menor_ns_filas = $nd_nsa;
          
    
    
	//consulta sql (a) - [a_xx]
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
							FROM TB_EVENTOS_DAC
							WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
							AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND TEMPO_ESPERA <= '$ns'
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_a = "a_$cod_fila";
		$$nome_variavel_a = $row['TOTAL'];
	}
	
	//consulta sql (B + TMA) - [b_xx] [tma_xx]
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL, AVG (TEMPO_ATEND) TMA
							FROM TB_EVENTOS_DAC
							WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
							AND CALLID IS NOT NULL AND TEMPO_ATEND > '0'
							GROUP BY COD_FILA
							ORDER BY TOTAL DESC");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){		
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
	
		$nome_variavel_b = "b_$cod_fila";
		$nome_variavel_tma = "tma_$cod_fila";		
		$$nome_variavel_b = $row['TOTAL'];
		$$nome_variavel_tma = $row['TMA'];
	}
	
	//consulta sql (C) - [c_xx]
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
							FROM TB_EVENTOS_DAC
							WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
							AND CALLID IS NOT NULL AND TEMPO_ATEND = '0' AND TEMPO_ESPERA > '$ns'
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		$nome_variavel_c = "c_$cod_fila";
		$$nome_variavel_c = $row['TOTAL'];
	}
	
	//consulta sql shortcall (SC) - [sc_xx]
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL
							FROM TB_EVENTOS_DAC
							WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
							AND CALLID IS NOT NULL AND TEMPO_ATEND BETWEEN '1' AND '$shortcall_tempo'
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		$nome_variavel_sc = "sc_$cod_fila";
		$$nome_variavel_sc = $row['TOTAL'];
	}
	
	//consulta sql chamadas pagas (CA) - [ca_xx]

	//APP
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_app)
																	GROUP BY CALLID
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	
	//APP - BRUTO
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_app)																	
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	//verificando se a fila é de retenção
	
	//retencao
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_retencao)
																	GROUP BY CALLID
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	
	//retencao BRUTO
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL FROM (
																	SELECT CALLID,  (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_retencao)																	
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	
	// triagem preventiva
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_triagem)
																	GROUP BY CALLID
																 ) AS A
							GROUP BY COD_FILA");
		
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	
	// triagem preventiva - BRUTO
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_triagem)																	
																 ) AS A
							GROUP BY COD_FILA");
	
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	
	
	// aviso de viagem
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_aviso_viagem)
																	GROUP BY CALLID
																) AS A
							GROUP BY COD_FILA");
	
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	
	// aviso de viagem - bruto
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_aviso_viagem)																	
																) AS A
							GROUP BY COD_FILA");
	
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	
	// parcelamento
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_parcelamento)
																	GROUP BY CALLID
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	// parcelamento - bruto
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_parcelamento)																	
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	

	// contestacao	
	/*
	$query = $pdo->prepare("SELECT COD_FILA, COUNT (*) TOTAL	FROM  TB_EVENTOS_DAC ted
							inner join	(
											SELECT distinct  CALLID, min(data_hora) d_hora
											FROM TB_EVENTOS_DAC
											WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
											AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_contestacao)
											GROUP BY CALLID
									    ) AS A on (A.CALLID = ted.callid and A.d_hora = ted.data_hora)
							WHERE ted.DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
							AND ted.CALLID IS NOT NULL AND ted.TEMPO_ATEND > '0' AND ted.COD_FILA IN ($ilha_contestacao)
							GROUP BY ted.COD_FILA");*/
	
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_contestacao)
																	GROUP BY CALLID
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	// contestacao - BRUTO
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID,(COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_contestacao)																	
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
		
	// programa de pontos
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_pontos)
																	GROUP BY CALLID
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	
	// programa de pontos - bruto
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_pontos)
																	
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	
	// pj
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_pj)
																	GROUP BY CALLID
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	// pj - bruto
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
                                                                    AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_pj)																	
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
		
	
	// 130
    $query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL FROM (
                                                                    SELECT CALLID, MIN (COD_FILA) COD_FILA
                                                                    FROM TB_EVENTOS_DAC
                                                                    WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_130)
                                                                    GROUP BY CALLID
                                                                 ) AS A
                            GROUP BY COD_FILA");
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $cod_fila = $row['COD_FILA'];
        
        if (!in_array($cod_fila, $vet_todas_filas)) 
            continue; // verifica se é uma fila válida
        
        $nome_variavel_ca = "ca_$cod_fila";
        $$nome_variavel_ca = $row['TOTAL'];
    }
    
    // 130 - bruto
    $query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL FROM (
                                                                    SELECT CALLID, (COD_FILA) COD_FILA
                                                                    FROM TB_EVENTOS_DAC
                                                                    WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_130)
                                                                    
                                                                 ) AS A
                            GROUP BY COD_FILA");
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $cod_fila = $row['COD_FILA'];
        
        if (!in_array($cod_fila, $vet_todas_filas))
            continue; // verifica se é uma fila válida
            
            $nome_variavel_ca = "cabruto_$cod_fila";
            $$nome_variavel_ca = $row['TOTAL'];
    }
    
	
	// 100
    $query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL FROM (
                                                                    SELECT CALLID, MIN (COD_FILA) COD_FILA
                                                                    FROM TB_EVENTOS_DAC
                                                                    WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_100)
                                                                    GROUP BY CALLID
                                                                 ) AS A
                            GROUP BY COD_FILA");
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $cod_fila = $row['COD_FILA'];
        
        if (!in_array($cod_fila, $vet_todas_filas)) 
            continue; // verifica se é uma fila válida
        
        $nome_variavel_ca = "ca_$cod_fila";
        $$nome_variavel_ca = $row['TOTAL'];
    }
    
    // 100 - bruto
    $query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL FROM (
                                                                    SELECT CALLID, (COD_FILA) COD_FILA
                                                                    FROM TB_EVENTOS_DAC
                                                                    WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_100)
                                                                   
                                                                 ) AS A
                            GROUP BY COD_FILA");
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $cod_fila = $row['COD_FILA'];
        
        if (!in_array($cod_fila, $vet_todas_filas))
            continue; // verifica se é uma fila válida
            
            $nome_variavel_ca = "cabruto_$cod_fila";
            $$nome_variavel_ca = $row['TOTAL'];
    }
    
	// caixa empregado
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_caixa_empregado)
																	GROUP BY CALLID
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
		
	// caixa empregado - bruto
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_caixa_empregado)
																	
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	
	// deficiente auditivo
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_deficiente_auditivo)
																	GROUP BY CALLID
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	// deficiente auditivo - bruto
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_deficiente_auditivo)
																	
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	
	// mala direta
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_mala_direta)
																	GROUP BY CALLID
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
		
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
		
	// mala direta - bruto
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_mala_direta)
																	
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
	
	// bloqueio cobranca
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_bloqueio_cobranca)
																	GROUP BY CALLID
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
			
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}
	
	// bloqueio cobranca
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_bloqueio_cobranca)
																	
																 ) AS A
							GROUP BY COD_FILA");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}
		
	// geral (normal + premium)
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, MIN (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' 
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_geral_normal,$ilha_geral_premium)
																	GROUP BY CALLID
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_fila = $row['COD_FILA'];
		if (!in_array($cod_fila, $vet_todas_filas)) 
			continue; // verifica se é uma fila válida
		
		$nome_variavel_ca = "ca_$cod_fila";
		$$nome_variavel_ca = $row['TOTAL'];
	}	

	// geral (normal + premium) - BRUTO
	$query = $pdo->prepare("SELECT COD_FILA,COUNT (*) TOTAL	FROM (
																	SELECT CALLID, (COD_FILA) COD_FILA
																	FROM TB_EVENTOS_DAC
																	WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
																	AND CALLID IS NOT NULL AND TEMPO_ATEND > '0' AND COD_FILA IN ($ilha_geral_normal,$ilha_geral_premium)
																	
																) AS A
							GROUP BY COD_FILA");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $cod_fila = $row['COD_FILA'];
	    if (!in_array($cod_fila, $vet_todas_filas))
	        continue; // verifica se é uma fila válida
	        
	        $nome_variavel_ca = "cabruto_$cod_fila";
	        $$nome_variavel_ca = $row['TOTAL'];
	}	
	
	
	// PRIMEIRA TABELA		
	for($i=0; $i<$num_filas ; $i++)
	{
		$cont = $vet_todas_filas[$i];
		
		// define variáveis impressas, de acordo com o valor de cont
		$var_b = "b_$cont";
		$valor_b = $$var_b;
		
		$var_a = "a_$cont";
		$valor_a = $$var_a;
		
		$var_c = "c_$cont";
		$valor_c = $$var_c;
		
		$var_sc = "sc_$cont";
		$valor_sc = $$var_sc;
		
		$var_ca = "ca_$cont";
		$valor_ca = $$var_ca;
		
		$var_cabruto = "cabruto_$cont";
		$valor_cabruto = $$var_cabruto;
				
		
		$var_tma = "tma_$cont";
		$valor_tma = $$var_tma;
		
		$var_pg = "pg_$cont";
		$valor_pg = $$var_pg;
		
		$var_nsa = "nsa_$cont";
		$valor_nsa = $$var_nsa;
		
		$var_ns = "ns_$cont";
		$valor_ns = $$var_ns;	
		
		
				
		
		if( ($valor_b > 0) || ($valor_a > 0) || ($valor_tma > 0) || ($valor_c > 0) || ($valor_sc > 0) )
		{		
		    $saldocb = $valor_cabruto - $valor_ca;
		    $total_saldocb = $total_saldocb + $saldocb; // 
		    
		    echo "<tr>";
			
			$imprimir_fila_atual = "nome_fila_$cont"; //trocar cod_fila pelo nome
			$imp_fila = $$imprimir_fila_atual;
			echo "<td>$cont <i>$imp_fila</i></td>"; //imprime código da fila + nome da fila
			
			echo "<td>$valor_a</td>";
			echo "<td>$valor_b</td>";
			echo "<td>$valor_c</td>";
				
			// calcula/imprime nsa
			$nsa = $valor_a / ($valor_b + $valor_c);
			$$var_nsa = $nsa;
			$imprime_nsa = number_format($nsa, 10, ',', '.');
			
			echo "<td>$imprime_nsa</td>";

			// calcula/imprime nivel de serviço (ns)
			
			//verifica se é ou não fila premium
			if (in_array($cont, $vet_todas_premium))
			{
				if($nsa >= $nsr_premium_valor) 
					$nivel_de_servico = 1;
				else 
				    $nivel_de_servico = $nsa/$nsr_premium_valor;
				
				    $imprime_nsr_premium = number_format($nsr_premium_valor, 2, ',', '.');  
				echo "<td>$imprime_nsr_premium</td>";
				
			}
			else
			{    
			    if($nsa >= $nsr ) 
				   $nivel_de_servico = 1;
			    else 
			       $nivel_de_servico = $nsa/$nsr_valor;
			
			    $imprime_nsr = number_format($nsr_valor, 2, ',', '.');
			    echo "<td>$imprime_nsr</td>";											
			} 
			
			if ($nivel_de_servico > 1)
			    $nivel_de_servico = 1.00;
			
			if ($nivel_de_servico < 0.90) 
				$ns_todas_filas_2 = 0; // Segundo critério de 90% do NS - Segundo Grupo
				
			// soma o nível de serviço para gerar valor ansm
			$soma_ansm = $soma_ansm + $nivel_de_servico;
			$cont_ansm++;
			$imprime_nivel_de_servico = number_format($nivel_de_servico, 10, ',', '.');
			
			$$var_ns = $nivel_de_servico; // variável ns						
			
			echo "<td>$imprime_nivel_de_servico</td>";
			echo "<td>$valor_tma</td>";				
			echo "<td>$valor_sc</td>";
			
			
			
			// percentual shortcall
			if ($valor_sc == '0') 
				$perc_sc = 0;
			else 
				$perc_sc = $valor_sc / $valor_b * 100;
			
			$imprime_perc_sc = number_format($perc_sc, 2, ',', '.');
			echo "<td>$imprime_perc_sc</td>";
			
			// finaliza/ imprime valor de ca (retirado o shortcall)
			$tirar_max = $valor_b * 0.05;
			if ($valor_sc > $tirar_max)
			{
				$tirar = $valor_sc - $tirar_max;
				if ( ($valor_ca - $tirar) >= 0 )
					$valor_ca = $valor_ca - $tirar;
				else 
					$valor_ca = 0;
			}

			//arrendondamento - início
			$arr = intval($valor_ca);
			if ($valor_ca - $arr >= 0.5) 
				$va_novo = $arr+1;
			else 
				$va_novo = $arr;
			
			$valor_ca = $va_novo;
			//arredondamento - fim

			echo "<td>$valor_ca</td>";			
			$$var_ca = $valor_ca;
			$total_ca = $total_ca + $valor_ca; // $total_ca
			
			
			
			
			// imprime pg e soma ao pg_diário
			$valor_pg = $valor_ca * $valor_atendimento;
			$imprime_valor_pg = number_format($valor_pg, 2, ',', '.');
			echo "<td>R$ $imprime_valor_pg</td>";	
			echo "<td>$valor_cabruto</td>";
			echo "<td>$saldocb</td>";
			echo "</tr>";
		}// final if( ($valor_b > 0) || ($valor_a > 0) || ($valor_tma > 0) || ($valor_c > 0) || ($valor_sc > 0) )			
	}//final IMPRESSÃO - PRIMEIRA TABELA

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
	echo "<td><b>ACP APLICADO(RET)</b></td>";
	echo "<td><b>Q. ACP(RET)</b></td>";
	echo "<td><b>AD. ACP(RET)</b></td>";
	echo "<td><b>APLI. DE MULT. ACP</b></td>";
	echo '</tr>';
		
	for($i=0; $i<$num_filas ; $i++)
	{			
		$cont = $vet_todas_filas[$i];
			
		$var_ca = "ca_$cont";
		$valor_ca = $$var_ca;
		
		$var_pg = "pg_$cont";
		$valor_pg = $$var_pg;
		
		$var_nsa = "nsa_$cont";
		$valor_nsa = $$var_nsa;
		
		$var_ns = "ns_$cont";
		$valor_ns = $$var_ns;
		$qtde_acp = 0; 
		$fator = 1;
		//$acp_aut = false;
		
		$vet_parcelamento_com_130 = array_merge($vet_parcelamento,$vet_130);
		$vet_contestacao_com_100 = array_merge($vet_contestacao,$vet_100);
		
		//se há qtde de ligações a serem remuneradas
		if(($valor_ca > 0))
		{
		   if (in_array("$cont", $vet_retencao) or in_array("$cont", $vet_triagem) 
			           or in_array("$cont", $vet_parcelamento_com_130) or in_array("$cont", $vet_perda_roubo)
		                or in_array("$cont", $vet_aviso_viagem)
			           /*<<<<<<<<<<<<<<<<<<ATENÇÃO>>>>>>>>>>>>>>> linha adicionada apenas para compensar a desconexao embratel*/ 
			           or in_array("$cont", $vet_todas_premium)
			         )  
			{
				//apenas para printar a linha com formato diferenciado	
				//$acp_aut = true;	 	
				
				$qtde_acp = $valor_ca;	
				//$fator = 1.25;RETIRADO NOVO FORMATO ACP
							
			}//recebem 25% de ACP para atendimentos de transferência interna
			else if(in_array("$cont", $vet_pj)){
				//apenas para printar a linha com formato diferenciado	
				//$acp_aut = true;	 	
				
				$qtde_acp = $valor_ca;	
				//$fator = 1.1; RETIRADO NOVO FORMATO ACP
			}
			else if (in_array("$cont", $vet_contestacao_com_100) or in_array("$cont", $vet_pontos) )
			{			       				    				    			
    		   $qtde_acp = $valor_ca;    			
    		}
			
			$imp_acp_aplicado = 0.00; 
			
			/*se houve revisao de nivel, entao devo considerar nao mais o nivel de servico da fila, mas sim
			 o nivel de servico das 3 datas utilizadas na revisao PARA A FILA*/
			if ($dia_revisao_nivel > 0)
			{            		
			    			   
			    $sql = "EXEC sp_CERATFO_fat_menor_ns_revisao_2 '$qual_ano-$qual_mes-$pos_dia','TODAS',$cont";
        		
        		$query = $pdo->prepare($sql);
        		$query->execute(); 
        		for($y=0; $row = $query->fetch(); $y++)
        		{
        		    $valor_ns = $row['ns'];
        		            		            		           		   
        		    if ($valor_ns > 1)
        		      $valor_ns = 1.00;
        		}        		
			}
			
			//Agregando filas de acordo as particulares de remuneração de ACP
			if (in_array($cont, $vet_retencao)  or  in_array($cont, $vet_triagem)
			    or in_array($cont, $vet_contestacao_com_100)  or in_array($cont, $vet_pontos)
			    or in_array($cont, $vet_perda_roubo)  or in_array($cont, $vet_pj)
			    or in_array($cont, $vet_parcelamento_com_130) or  in_array($cont, $vet_aviso_viagem)
			)
			{
			    			   
			    if (($valor_ns < '0.98') or ($menor_ns_filas < '0.95')) //1º Critério - Se o NS da Fila < 98% ou NS das demais filas < 95% - !SEM ACP!
			         $imp_acp_aplicado = '00';
			    else if ($menor_ns_faixa_horario >= '0.85') //Validando a 3º Condição (mais dificil) se TODOS os intervalos de TODAS as filas ficaram com NS > 90%
			    {
		            $imp_acp_aplicado = '25';
		            $fator = 1.25;
			    }			   
		        else //Validando a 1º Condição (a mais fácil), FILA com NS >= 98% e as demais filas com NS >= 95%
		        {		            		            
                    $imp_acp_aplicado = '15';
                    $fator = 1.15;		            
		        }
			}			
			else if (in_array($cont, $vet_geral_premium)) //3º Grupo aqui
			{			    
			    if (($valor_ns < '0.95') ) //1º Critério - Se o NS da Fila < 95% ou NS das demais filas < 90% - !SEM ACP  - retirado or ($menor_ns_filas < '0.90') !
			        $imp_acp_aplicado = '00';
			    else if ($menor_ns_faixa_horario_premium >= '0.85')
			    {
			        $imp_acp_aplicado = '25';
			        $fator = 1.25;
			    }else
			    {
			        $imp_acp_aplicado = '20';
			        $fator = 1.20;
			    }			   			  
			}
			
			/*$imp_acp_aplicado = 0.00;//retirando o ACP
			$fator = 1;*/
			echo "<tr>";
			
			$dia_ansm = "ansm$pos_dia";
			if ($$dia_ansm != '0.00') 
				$media_ansm = $$dia_ansm;
			else 
				$media_ansm = $soma_ansm/$cont_ansm; //calcula valor ansm
			
			// imprime "fila"
			$imprimir_fila_atual = "nome_fila_$cont"; //trocar cod_fila pelo nome
			$imp_fila = $$imprimir_fila_atual;
			
			//apenas colocando negrito/sublinhando nas filas que 25% de acp automaticamente
			if ($acp_aut)
				echo "<td>$cont <b><u><i>$imp_fila</i></u></b></td>"; //imprime código da fila + nome da fila	
			else
				echo "<td>$cont <i>$imp_fila</i></td>";
			
			
			// imprime "ca"
			echo "<td>$valor_ca</td>";
			
			// imprime "valor bruto"
			$valor_pg = $valor_ca * $valor_atendimento;
			$imprime_valor_pg = number_format($valor_pg, 2, ',', '.');
			echo "<td>R$ $imprime_valor_pg</td>";
			
			$valor_bruto_diario = $valor_bruto_diario + $valor_pg; // $valor_bruto_diario
			
			// imprime "aplicação ansm"
			$aplicacao_ansm = $valor_pg * $media_ansm;
			$imprime_aplicacao_ansm = number_format($aplicacao_ansm, 2, ',', '.');
			echo "<td>R$ $imprime_aplicacao_ansm</td>";
			
			$total_ansm = $total_ansm + $aplicacao_ansm; // $total_ansm do dia
			
			//--------------calculando adicional ACP-----------------
			//$qtde_acp = 0; //código para anular a implementação de adição de retidos
			//$fator = 1;
			$ad_acp_ret = 0;
			$qtde_retacp = 0;
			$imp_ad_acp_ret = 0;
			$imp_acp_aplicado_ret = 0;
			
			//Agregando a FILA RETENÇÃO PREMIUM '85' para permitir a aplicação da regra das ligações retidas
			$vet_retencao_ret_premium = $vet_retencao;
			array_push($vet_retencao_ret_premium,'85');
			if (in_array($cont, $vet_retencao_ret_premium))
			{			    
			    $retquery = $pdo->prepare("
                                           select count(distinct t.callid) qtde 
                                           from tb_dados_retencao t 
                                           inner join tb_eventos_dac t2 on (t2.callid = t.callid) 
                                           where t.data_hora between '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
                                           and t2.data_hora between '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'
                                           and t.tipo_retencao not like  '%CARTÃO NÃO RETIDO%' 
                                            and t2.cod_fila = $cont                                                                           
                                         ");
			    $retquery->execute();
			    for($b=0; $rowret = $retquery->fetch(); $b++)
			    {
			        $qtde_retacp = $rowret['qtde'];
			    }
			    
			    			    
			    //retirando a quantidade de chamadas retidas do calculo de acp 'normal' 
			    $qtde_acp = $qtde_acp - $qtde_retacp;
			    
			    //calculando ACP de retenção
			    $p_acp_ret =  (($aplicacao_ansm/$valor_ca) * $qtde_retacp);
			    $ad_acp_ret = (($p_acp_ret * 1.25) - $p_acp_ret);
			    
			    if ($ad_acp_ret > 0) 		
			    {    
			      $imp_acp_aplicado_ret = 25;
			      $imp_ad_acp_ret = number_format($ad_acp_ret, 2, ',', '.');
			    }
			    else
			      $imp_acp_aplicado_ret = 0;
			}			    
			
			
			$p_acp =  (($aplicacao_ansm/$valor_ca) * $qtde_acp); 
			$ad_acp = (($p_acp* $fator) - $p_acp); 
			$imp_ad_acp = number_format($ad_acp, 2, ',', '.');
												
			
			if (!isset($imp_acp_aplicado)) 
			{
				$imp_acp_aplicado = 0;
			}
			
			echo "<td>$imp_acp_aplicado%</td>";			
			echo "<td>$qtde_acp</td>";
			echo "<td>R$ $imp_ad_acp</td>"; 
			
			/*ACP de Retenção apenas*/
			echo "<td>$imp_acp_aplicado_ret%</td>";			
			echo "<td>$qtde_retacp</td>";
			echo "<td>R$ $imp_ad_acp_ret</td>"; 
						
			
			$imp_aplicacao_acp =  $aplicacao_ansm + $ad_acp + $ad_acp_ret;
			$imprime_imp_aplicacao_acp = number_format($imp_aplicacao_acp, 2, ',', '.');
			echo "<td>R$ $imprime_imp_aplicacao_acp</td>";
			
			$pg_total_dia = $pg_total_dia + $imp_aplicacao_acp; // incrementa valor pg dia
			
			echo "</tr>";					
		}//final if ($valor_ca >0)
	}//final for
	
	echo '</div>';
	echo '</table>';
	echo "</div>";
	// segunda tabela - fim
	
	// nova tabela - início		
	echo '<br>';
	
	echo "<div class='w3-container'>";
	echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
			
	echo '<tr class="w3-black">';
	echo "<td><b>RESUMO DE FATURAMENTO - DIÁRIO</b></td>";
	echo "<td><b>$pos_dia de $mes de $qual_ano</b></td>";
	echo '</tr>';

	echo '<tr>';
	echo "<td>MENOR NÍVEL DE SERVIÇO IDENTIFICADO - FAIXA DE HORÁRIO</td>";
	echo "<td>$menor_ns_faixa_horario</td>";
	echo '</tr>';
	
	echo '<tr>';
	echo "<td>MENOR NÍVEL DE SERVIÇO PREMIUM IDENTIFICADO - FAIXA DE HORÁRIO</td>";
	echo "<td>$menor_ns_faixa_horario_premium</td>";
	echo '</tr>';
	
	echo '<tr>';
	echo "<td>MENOR NÍVEL DE SERVIÇO IDENTIFICADO - FILA</td>";
	echo "<td>$menor_ns_filas</td>";
	echo '</tr>';
	
	// imprime ansm		
	$imprime_media_ansm = number_format($media_ansm, 10, ',', '.');
	if ($$dia_ansm != '0.00')
	{
		echo '<tr>';
		echo "<td>ALCANCE DE NÍVEL DE SERVIÇO MÉDIO (ANSM) <b><i>- Dia com Aplicação de Revisão de Nível</i><b></td>";
		echo "<td>$imprime_media_ansm</td>";
		echo '</tr>';
	}
	else
	{
		echo '<tr>';
		echo "<td>ALCANCE DE NÍVEL DE SERVIÇO MÉDIO (ANSM)</td>";
		echo "<td>$imprime_media_ansm</td>";
		echo '</tr>';
	}

	//consulta/imprime sql quantidade de atendimentos ura (mês)
	if($sel_eventos_ura=='01')
	{
		$query = $pdo->prepare("SELECT COUNT (DISTINCT CALLID) TOTAL
				FROM TB_EVENTOS_URA
				WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$qtd_ura = $row['TOTAL'];
		}
	}
	
	//consulta/imprime sql quantidade de atendimentos ura (mês)
	if($sel_eventos_ura=='02')
	{
	    //atendimentos eletronico - TB_EVENTOS_URA
	    $query = $pdo->prepare("select count(*) TOTAL
                                from tb_eventos_ura_2 t
                                where t.data_hora between '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL
                                and t.cod_evento in  ('020','031','037','039','042','045','047','050','051','061','062','076','078','136','137','138','139','140','149','790')    
                                ");
	    $query->execute();
	    for($i=0; $row = $query->fetch(); $i++)
	    {
	        $qtd_ura = $row['TOTAL'];
	    }
	    
	    //PESQUISA DE SATISFAÇÃO
	    $query = $pdo->prepare("select count(distinct callid) qtd_pesquisa from tb_pesq_satisfacao (nolock)
	    where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'");
		$query->execute();
		
		for($i=0; $row = $query->fetch(); $i++)
		{
		    $qtd_pesquisa = $row['qtd_pesquisa'];
		}
		
		$qtd_ura = $qtd_ura + $qtd_pesquisa;
	}
		
	//obs, código retirado pois o calculo feito acima, somente com sql é mais simples e produz o mesmo resultado
	if($sel_eventos_ura=='02RETIRADO')
	{
		$qtd_ura = 0;
		$valida_callid = 0;
		
		//prepara o 'vet' dos eventos faturáveis - início
		$txt_eventos_faturaveis = "";
		/*
		$query = $pdo->prepare("select * from tb_eventos
					where cod_fonte = 1");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$cod_evento = $row['cod_evento'];
			if(isset($_POST["evento_ura_$cod_evento"]))
			{
				if($txt_eventos_faturaveis=="") 
					$txt_eventos_faturaveis = $txt_eventos_faturaveis . "$cod_evento";
				else 
					$txt_eventos_faturaveis = $txt_eventos_faturaveis . ",$cod_evento";
			}
		}
		$vet_eventos_faturaveis = explode(",",$txt_eventos_faturaveis); */
		$eventos_faturaveis = array('020','031','037','039','042','045','047','050','051','061','062','076','078','136','137','138','139','140');
		//prepara o 'vet' dos eventos faturáveis - FIM
					
					
		$query = $pdo->prepare("SELECT CALLID, COD_EVENTO
								FROM TB_EVENTOS_URA
								WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$valida_callid = 0;
			$cod_evento = $row['COD_EVENTO'];
			$vetor = explode(";", $cod_evento);
			
			$qtd_vetor = count($vetor);
			
			for($a=0;$a<$qtd_vetor;$a++)
			{
				if (in_array($vetor[$a], $vet_eventos_faturaveis)) 
					$valida_callid = 1;
			}
			
			if($valida_callid==1) 
				$qtd_ura = $qtd_ura + 1;
			
		}
		
		if ($txt_eventos_faturaveis=="") 
			$qtd_ura = 0;					
	}
		
	if($sel_eventos_ura=='03')
	{
		$qtd_ura = 0;
			
		//prepara o 'vet' dos eventos faturáveis - início
		$txt_eventos_faturaveis = "";
		$query = $pdo->prepare("select * from tb_eventos
					where cod_fonte = 1");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$cod_evento = $row['cod_evento'];
			if(isset($_POST["evento_ura_$cod_evento"]))
			{
				if($txt_eventos_faturaveis=="") 
					$txt_eventos_faturaveis = $txt_eventos_faturaveis . "$cod_evento";
				else 
					$txt_eventos_faturaveis = $txt_eventos_faturaveis . ",$cod_evento";
			}
		}
		$vet_eventos_faturaveis = explode(",",$txt_eventos_faturaveis);
		//prepara o 'vet' dos eventos faturáveis - fim
			
			
		$query = $pdo->prepare("SELECT CALLID, COD_EVENTO
								FROM TB_EVENTOS_URA
								WHERE DATA_HORA BETWEEN '$qual_mes/$pos_dia/$qual_ano' AND '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' AND CALLID IS NOT NULL");
		$query->execute();
		for($i=0; $row = $query->fetch(); $i++)
		{
			$cod_evento = $row['COD_EVENTO'];
			$vetor = explode(";", $cod_evento);
			
			$qtd_vetor = count($vetor);
			
			for($a=0;$a<$qtd_vetor;$a++){
				if (in_array($vetor[$a], $vet_eventos_faturaveis)) $qtd_ura = $qtd_ura+1;
			}
			
		}
		if ($txt_eventos_faturaveis=="") $qtd_ura = 0;
		
	}// FINAL if($sel_eventos_ura=='03')
		
		
	$imprime_qtd_ura = number_format($qtd_ura, 0, ',', '.');		
	echo '<tr">';
	echo "<td>QUANTIDADE DE ATENDIMENTOS ELETRÔNICOS</td>";
	echo "<td>$imprime_qtd_ura</td>";
	echo '</tr>';
	
	$mensal_total_qtd_ura = $mensal_total_qtd_ura + $qtd_ura;
		
	// imprime total humano
	$imprime_total_ca = number_format($total_ca, 0, ',', '.');	
	echo '<tr>';
	echo "<td>QUANTIDADE DE ATENDIMENTOS HUMANOS</td>";
	echo "<td>$imprime_total_ca</td>";
	echo '</tr>';
	
	$imprime_total_saldocb = number_format($total_saldocb, 0, ',', '.');
	echo '<tr>';
	echo "<td>ATENDIMENTOS RECORRENTES NÃO REMUNERADOS</td>";
	echo "<td>$imprime_total_saldocb</td>";
	echo '</tr>';
	
	$mensal_total_ca = $mensal_total_ca + $total_ca;
	$mensal_saldocb = $mensal_saldocb + $total_saldocb;
		
	// imprime total eletrônico + humano
	$total_dia_hum_ura = $qtd_ura + $total_ca;
	$imprime_total_dia_hum_ura = number_format($total_dia_hum_ura, 0, ',', '.');
	echo '<tr>';
	echo "<td>QUANTIDADE DE ATENDIMENTOS TOTAL</td>";
	echo "<td>$imprime_total_dia_hum_ura</td>";
	echo '</tr>';

	// imprime bruto diário 
	$imprime_valor_bruto_diario = number_format($valor_bruto_diario, 2, ',', '.');
	echo '<tr>';
	echo "<td><b>REMUNERAÇÃO ATENDIMENTO HUMANO BRUTO</b></td>";
	echo "<td><b>R$ $imprime_valor_bruto_diario</b></td>";
	echo '</tr>';
	
	$mensal_total_bruto = $mensal_total_bruto + $valor_bruto_diario;

	// imprime total desconto de ansm
	$dif_bruto_ansm = $valor_bruto_diario - $total_ansm;
	$imprime_dif_bruto_ansm = number_format($dif_bruto_ansm, 2, ',', '.');
	echo '<tr>';
	echo "<td>ALCANCE DE NÍVEL DE SERVIÇO MÉDIO (ANSM) - DESCONTOS</td>";
	echo "<td>R$ $imprime_dif_bruto_ansm</td>";
	echo '</tr>';
	
	$mensal_total_desc_ansm = $mensal_total_desc_ansm + $dif_bruto_ansm;
		
	// imprime total adicional de acp
	$adc_acp = $pg_total_dia - $total_ansm; 
	$imprime_adc_acp = number_format($adc_acp, 2, ',', '.');
	echo '<tr>';
	echo "<td>ADICIONAL DE COMPLEXILIDADE E PRIORIDADE (ACP): ADICIONAIS</td>";
	echo "<td>R$ $imprime_adc_acp</td>";
	echo '</tr>';
	
	$mensal_total_acre_acp = $mensal_total_acre_acp + $adc_acp;
	
	// imprime faturamento atendimento ura
	$total_ura = $qtd_ura * $valor_atendimento_ura;
	$imprime_total_ura = number_format($total_ura, 2, ',', '.');
	echo '<tr">';
	echo "<td><b>REMUNERAÇÃO ATENDIMENTO ELETRÔNICO</b></td>";
	echo "<td><b>R$ $imprime_total_ura</b></td>";
	echo '</tr>';
	$mensal_ura = $mensal_ura + $total_ura; // soma total ura mensal

	// imprime pg total dia
	$imprime_pg_total_dia = number_format($pg_total_dia, 2, ',', '.');
	echo '<tr>';
	echo "<td><b>REMUNERAÇÃO ATENDIMENTO HUMANO</b></td>";
	echo "<td><b>R$ $imprime_pg_total_dia</b></td>";
	echo '</tr>';		
	$mensal_humano = $mensal_humano + $pg_total_dia; // SOMA MENSAL HUMANO

	// imprime faturamento (ura + humano + retidos)	
	//$total_geral = $total_ura + $pg_total_dia + $pg_total_retidos; // COM OS RETIDOS
	$total_geral = $total_ura + $pg_total_dia; // SEM OS RETIDOS
	$imprime_total_geral = number_format($total_geral, 2, ',', '.');
	echo '<tr">';
	echo "<td><b>REMUNERAÇÃO TOTAL</b></td>";
	echo "<td><b>R$ $imprime_total_geral</b></td>";
	echo '</tr>';	
	$mensal_total = $mensal_total + $total_geral; // SOMA TOTAL FINAL MENSAL (URA + HUMANO)
		
	// finaliza nova tabela
	echo '</table>';
	echo '</div>';
	echo '<hr>';
		
	// zera variáveis	
	for($cont=0; $cont<$num_filas; $cont++)
	{			
		$fila_atual = $vet_todas_filas[$cont];
		
		$var_b = "b_$fila_atual"; // Total de Ligações
		$$var_b = 0;
		
		$var_a = "a_$fila_atual"; // Atendidas até o Tempo Limite (NS)
		$$var_a = 0;
		
		$var_c = "c_$fila_atual"; // Ligações Abandonadas Após Tempo Limite (NS)
		$$var_c = 0;
		
		$var_ca = "ca_$fila_atual"; // Chamadas Pagas
		$$var_ca = 0;
		
		$var_tma = "tma_$fila_atual"; // TMA
		$$var_tma = 0;
		
		$var_sc = "sc_$fila_atual"; // SHORTCALL
		$$var_sc = 0;
		
		$var_pg = "pg_$fila_atual"; // PG por fila
		$$var_pg = 0;
		
		$var_nsa = "nsa_$fila_atual";
		$$var_nsa = 0;
		
		$var_ns = "ns_$fila_atual";
		$$var_ns = 0;
		
		$pg_total_dia = 0; // PG total do dia, sem adicionais (ACP, etc)
		
		$soma_ansm = 0;
		$cont_ansm = 0;
		
		$qtd_ura = 0;
		
		$perc_sc = 0;
		
		$total_ca = 0;
		
		$total_saldocb = 0;
		
		$valor_bruto_diario = 0;
		
		$total_ansm = 0;
		
		$total_ura = 0;
		
		$qtd_ura = 0;
		
		$total_faturamento = 0;
	}
} //FINAL for imprime dia a dia  
 

// imprime tabela final
echo "<div class='w3-container'>";
echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';

echo '<tr class="w3-black">';
echo "<td><b>RESUMO DE FATURAMENTO - MENSAL</b></td>";
echo "<td><b>$mes de $qual_ano</b></td>";
echo '</tr>';

/*
$imprime_mensal_retido = number_format($mensal_retido, 2, ',', '.');
echo '<tr">';
echo "<td>FATURAMENTO RETIDOS</td>";
echo "<td>R$ $imprime_mensal_retido</td>";
echo '</tr>';
*/

$imprime_mensal_total_ca = number_format($mensal_total_ca, 0, ',', '.');
echo '<tr">';
echo "<td>QUANTIDADE DE ATENDIMENTOS HUMANOS</td>";
echo "<td>$imprime_mensal_total_ca</td>";
echo '</tr>';

$imprime_mensal_saldocb = number_format($mensal_saldocb, 0, ',', '.');
echo '<tr">';
echo "<td>ATENDIMENTOS RECORRENTES NÃO REMUNERADOS</td>";
echo "<td>$imprime_mensal_saldocb</td>";
echo '</tr>';

$imprime_mensal_total_qtd_ura = number_format($mensal_total_qtd_ura, 0, ',', '.');
echo '<tr">';
echo "<td>QUANTIDADE DE ATENDIMENTOS ELETRÔNICOS</td>";
echo "<td>$imprime_mensal_total_qtd_ura</td>";
echo '</tr>';

$atd_atend_total = $mensal_total_ca + $mensal_total_qtd_ura;
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
$mensal_total = ($mensal_ura + $hum_mensal); // DEFINE MENSAL TOTAL SEM RETIDOS
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