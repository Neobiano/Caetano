<!DOCTYPE html>
<html>
<title>CAIXA - Faturamento URA Contrato INDRA Maracanaú</title>
<head>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>
</head>

<body style='overflow: auto; width:100%;'>
<div class="w3-container w3-center w3-margin-top">
	<img src="logo.png" style="width:9%">
	<hr>
</div>

<?php
// CONECTA BANCO DE DADOS
include "conecta.php";
// TEMPO LIMITE CONSULTAS SQL
set_time_limit(99999);
ini_set('max_execution_time', 99999);

ini_set('memory_limit','512M');

//RECEBE VARIÁVEIS DO FORMULÁRIO
$qual_mes = $_POST['qual_mes'];
$qual_ano = $_POST['qual_ano'];
$valor_atendimento_ura = $_POST['valor_atendimento_ura'];

// DEFINE VARIÁVEL $MES POR EXTENSO
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

//DEFINE QUANTIDADE DE DIAS DE CADA MÊS
if($qual_mes=='01') $qtd_dias = 31;
if($qual_mes=='02') {
	if ($qual_ano%4 != 0) $qtd_dias = 28;
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

$dia_atual = $today = date("d");
$mes_atual = $today = date("m");
if ($qual_mes == $mes_atual) $qtd_dias = $dia_atual - 3;

// PREPARA LIKE_EVENTOS - INÍCIO
$array_like_eventos = array();
$in_like_eventos = "cod_evento like '%K0000%'";
$imprime_eventos_faturados = "";
$contador = 0;

$array_desc_eventos = array();
$array_tabela = array();

$query = $pdo->prepare("select * from tb_eventos_novaura");
$query->execute();

for($i=0; $row = $query->fetch(); $i++)
{
	$cod_evento = $row['cod_evento'];
	$desc_evento = utf8_encode($row['desc_evento']);
	
	$array_desc_eventos[$cod_evento] = $desc_evento;
	
	if(isset($_POST["evento_ura_$cod_evento"])){
		
		$totalizador_tabela[$cod_evento] = 0;
		for($pos_dia=1;$pos_dia <= $qtd_dias;$pos_dia++){ // CRIA O ARRAY $array_tabela .. $array_tabela[$cod_evento][$pos_dia]
			$array_tabela[$cod_evento][$pos_dia] = 0;
		}
		
		
		array_push($array_like_eventos,$cod_evento);
		$in_like_eventos = $in_like_eventos." or cod_evento like '%$cod_evento%'";
		
		if($contador==0) 
		{
			$imprime_eventos_faturados = "$cod_evento";
			$contador++;
		} else $imprime_eventos_faturados = $imprime_eventos_faturados.", $cod_evento";
	}
}
sort($array_like_eventos);
// PREPARA LIKE_EVENTOS - FIM
$array_eventos_periodo = array();

//VARIÁVEIS TOTALIZADORAS - INÍCIO
	$soma_total_faturados = 0;
	$soma_total_lig_ura = 0;
//VARIÁVEIS TOTALIZADORAS - FIM

echo "<div class='w3-container w3-center w3-tiny w3-margin-bottom'>";
	echo "<b>Faturamento URA - $mes / $qual_ano</b>";
	echo "<br><br><b>Eventos Faturados:</b> $imprime_eventos_faturados";
echo "</div>";



//$qtd_dias = 1;
for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++)
{
	$total_faturados = 0;
	$query = $pdo->prepare("select * from tb_eventos_ura (nolock)
							where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59.999' and ($in_like_eventos)");
	$query->execute();
	
	for($i=0; $row = $query->fetch(); $i++)
	{
		$cod_evento = $row['cod_evento'];
		
		$array_cod_linha = explode(";", $cod_evento);
		
		//evento posterior a idpos
		$passou_pelo_014 = 0; // PARA EXCLUIR EVENTOS INDEVIDOS - 014;xx;031 ou 014;031 (031 não é para contar aqui) **
		$passou_pelo_022 = 0;
		/*$arrlength=count($array_cod_linha);		
		for($cont=0; $cont<$arrlength ;$cont++)
		{
		    $fatura = true;
		    $cod_evento = $array_cod_linha[$cont];
		    
		    if($cod_evento == '031')
		    {
		        if (($array_cod_linha[$cont-1] == '014') or  (($array_cod_linha[$cont-2] == '014') && ($array_cod_linha[$cont-1] != '022')))
		           $fatura = false;		        		              
		    }    
		    
		    if ($fatura)
		    {		        
    		    if(in_array($cod_evento,$array_like_eventos))
    		    {
    		        
    		        $array_tabela[$cod_evento][$pos_dia]++; // INCREMENTA $array_tabela
    		        $total_faturados++;
    		        $soma_total_faturados++;
    		        if(isset($array_eventos_periodo[$cod_evento]))
    		            $array_eventos_periodo[$cod_evento]++;
    		            else
    		                $array_eventos_periodo[$cod_evento] = 1;
    		                
    		    }
		    }
		}*/
		
		foreach($array_cod_linha as $cod_evento)
		{	
			$nao_fatura = 0; 
			if($cod_evento == '031')
			{    
    			//se passou pelo evento 014 a 'uma' ou 'duas' posições atraz..
    			if (($passou_pelo_014 == 2) and ($passou_pelo_022 != 1))						        
    			   $nao_fatura = 1;								 			
    			else if ($passou_pelo_014 == 1)			
    			   $nao_fatura = 1;
			}
			
			
			if($passou_pelo_014 > 0) 
			   $passou_pelo_014++; 
			
			if($passou_pelo_022 > 0)
			   $passou_pelo_022++; 
			
			if($cod_evento == '014') 
			    $passou_pelo_014 = 1;
			
			if($cod_evento == '022')
			   $passou_pelo_022 = 1;
			
			if($nao_fatura == 0)
			{
				if(in_array($cod_evento,$array_like_eventos))
				{
								
				    $array_tabela[$cod_evento][$pos_dia]++; // INCREMENTA $array_tabela				
				    $total_faturados++;
				    $soma_total_faturados++;
				    if(isset($array_eventos_periodo[$cod_evento])) 
				        $array_eventos_periodo[$cod_evento]++;
				    else 
				        $array_eventos_periodo[$cod_evento] = 1;
				
				}
			}
		}
	}
}


for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){
	$totalizador_diario[$pos_dia] = 0;
}

echo "<div style='padding: 16px !important;'><table id='tabela3' name='tabela2' class='w3-table w3-striped w3-hoverable w3-tiny w3-card-4' style=''>";
	echo "<thead><tr class='w3-indigo'>";
		echo "<td><b>CÓDIGO</b></td>";
		echo "<td><b>EVENTO</b></td>";
		
		for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){
			$pos_dia_imprime = $pos_dia;
			if($pos_dia < 10) $pos_dia_imprime = "0$pos_dia";
			echo "<td><b>$pos_dia_imprime</b></td>";
		}
		
		echo "<td><b>TOTAL</b></td>";
	echo "</tr></thead><tbody>";
	

foreach($array_like_eventos as $cod_evento){
	echo "<tr>";
	echo "<td><b>$cod_evento</b></td>";
	$evento = $array_desc_eventos[$cod_evento];
	echo "<td><b>$evento</b></td>";

	
	for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){
		$qtd = $array_tabela[$cod_evento][$pos_dia];
		$imprime = number_format($qtd, 0, ',', '.');
		echo "<td>$imprime</td>";
		$totalizador_tabela[$cod_evento] = $totalizador_tabela[$cod_evento] + $qtd;
		$totalizador_diario[$pos_dia] = $totalizador_diario[$pos_dia] + $qtd;
	}
	$total = $totalizador_tabela[$cod_evento];
	$imprime = number_format($total, 0, ',', '.');
	echo "<td><b>$imprime</b></td>";
	
	echo "</tr>";
}


// PESQUISA DE SATISFAÇÃO
$qtd_pesquisa = 0;

echo "<tr>";
echo "<td><b></b></td>";
echo "<td><b>PESQUISA DE SATISFAÇÃO</b></td>";

for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){


	$query = $pdo->prepare("select count(distinct callid) qtd_ura from tb_pesq_satisfacao (nolock)
								where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'");
		$query->execute();
		
		for($i=0; $row = $query->fetch(); $i++){
			$qtd_ura = $row['qtd_ura'];
			$totalizador_diario[$pos_dia] = $totalizador_diario[$pos_dia] + $qtd_ura;
			$qtd_pesquisa = $qtd_pesquisa + $qtd_ura;
			$imprime = number_format($qtd_ura, 0, ',', '.');
			echo "<td>$imprime</td>";
			
		}
}

$imprime = number_format($qtd_pesquisa, 0, ',', '.');
echo "<td><b>$imprime</b></td>";
echo "</tr>";


// TOTALIZADOR - TOTAL POR SERVIÇO
echo "</tbody><tr style='color: white; background: #433;'>";
	echo "<td><b>TOTAL POR SERVIÇO:</b></td>";
	echo "<td></td>";
	
	$total_periodo = 0;
	
	for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){
		$total_dia = $totalizador_diario[$pos_dia];
		$total_periodo = $total_periodo + $total_dia;
		$imprime = number_format($total_dia, 0, ',', '.');
		echo "<td>$imprime</td>";
	}
	$imprime = number_format($total_periodo, 0, ',', '.');
	echo "<td><b>$imprime</b></td>";
	
echo "</tr>";


// TOTALIZADOR - VALOR POR SERVIÇO
echo "<tr style='color: #fff; background: #655;'>";
	echo "<td><b>VALOR POR SERVIÇO (R$):</b></td>";
	echo "<td></td>";
	
	$valor_periodo = 0;
	
	for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){
		$valor_dia = $totalizador_diario[$pos_dia] * $valor_atendimento_ura;
		$valor_periodo = $valor_periodo + $valor_dia;
		$imprime = number_format($valor_dia, 2, ',', '.');
		echo "<td>$imprime</td>";
	}
	
	$imprime = number_format($valor_periodo, 2, ',', '.');
	echo "<td><b>$imprime</b></td>";
	
echo "</tr>";


// TOTAL LIGACOES NA URA
$totalizador_ura = 0;
for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){
	$total_na_ura[$pos_dia] = 0;
}

echo "<tr style='color: white; background: #334;'>";
echo "<td><b>TOTAL LIGAÇÕES NA URA</b></td>";
echo "<td></td>";

for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){


	$query = $pdo->prepare("select count(distinct callid) qtd_ura from tb_eventos_ura (nolock)
								where data_hora between '$qual_mes/$pos_dia/$qual_ano' and '$qual_mes/$pos_dia/$qual_ano 23:59:59.999'");
		$query->execute();
		
		for($i=0; $row = $query->fetch(); $i++){
			$qtd_ura = $row['qtd_ura'];
			$total_na_ura[$pos_dia] = $total_na_ura[$pos_dia] + $qtd_ura;
			$totalizador_ura = $totalizador_ura + $qtd_ura;
			$imprime = number_format($qtd_ura, 0, ',', '.');
			echo "<td>$imprime</td>";
			
		}
}

$imprime = number_format($totalizador_ura, 0, ',', '.');
echo "<td><b>$imprime</b></td>";
echo "</tr>";


// TOTALIZADOR - VALOR POR LIGAÇÃO
echo "<tr style='color: #fff; background: #556;'>";
	echo "<td><b>VALOR LIGAÇÕES NA URA (R$):</b></td>";
	echo "<td></td>";
	
	$valor_lig_periodo = 0;
	
	for($pos_dia=1;$pos_dia<=$qtd_dias;$pos_dia++){
		$valor_dia = $total_na_ura[$pos_dia] * $valor_atendimento_ura;
		$valor_lig_periodo = $valor_lig_periodo + $valor_dia;
		$imprime = number_format($valor_dia, 2, ',', '.');
		echo "<td>$imprime</td>";
	}
	
	$imprime = number_format($valor_lig_periodo, 2, ',', '.');
	echo "<td><b>$imprime</b></td>";
	
echo "</tr>";

echo "</table></div>";

// TABELA DIFERENÇA
echo "<div style='padding: 16px !important;'><table id='tabela3' name='tabela2' class='w3-table w3-striped w3-hoverable w3-tiny w3-card-4' style=''>";
	echo "<tr class='w3-indigo'>";
		echo "<td><b>TOTAL MÊS POR SERVIÇO:</b></td>";
		echo "<td><b>TOTAL MÊS POR LIGAÇÃO:</b></td>";
		echo "<td><b>DIFERENÇA:</b></td>";
	echo "</tr>";
	
	echo "<tr>";
		$imprime = number_format($valor_periodo, 2, ',', '.');
		echo "<td>R$ $imprime</td>";
		
		$imprime = number_format($valor_lig_periodo, 2, ',', '.');
		echo "<td>R$ $imprime</td>";
		
		$dif_final = $valor_periodo - $valor_lig_periodo;
		$imprime = number_format($dif_final, 2, ',', '.');
		
		if($dif_final >= 0) echo "<td style='color: green;'><b>R$ $imprime</b></td>";
		else echo "<td style='color: red;'><b>R$ $imprime</b></td>";
	echo "</tr>";	
echo "</table></div>";


include "desconecta.php";
?>
</body>

</html>