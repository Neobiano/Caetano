<!DOCTYPE html>
<html>
<head>
<title>CAIXA - MINI FERRAMENTAS - Contrato INDRA Maracanaú</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabela').DataTable( {
        "order": [[ 5, "desc" ]]
    } );
} );
</script>

</head>
<body>
<?php 
include "conecta.php";
include "funcoes.php";
set_time_limit(9999);
ini_set('max_execution_time', 9999);

$data = $_GET['data'];
$hora_inicial = $_GET['horainicial'];
$hora_final = $_GET['horafinal'];
$coluna = $_GET['coluna'];



//Conversão Data Texto - Início
$t_inicial = strtotime($data);
$data_inicial_texto = date('d/m/Y',$t_inicial);

$t_final= strtotime($data);
$data_final_texto = date('d/m/Y',$t_final);
//Conversão Data Texto - Fim

//VARIÁVEIS TOTALIZADORAS
$SOMA_total_atendimentos = 0;
$SOMA_TMA = 0;
$in_semana = date('w', strtotime($data)) + 1;
$txt_dias_semana = diaSemana($in_semana);
$in_semana = '('.$in_semana.')';


echo '<div class="w3-margin w3-tiny w3-center">';
echo "<b>Lista de Operadores</b>";
echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto $hora_inicial à $data_final_texto $hora_final";
echo "<br><br>";

echo '<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4" id="tabela"><thead>';
echo '<tr class="w3-indigo w3-tiny">';
echo '<td><b>ID</b></td>';
echo '<td><b>NOME OPERADOR</b></td>';
echo '<td><b>MATRÍCULA</b></td>';
if ($coluna == 5){
    echo '<td><b>Hr. Inicial</b></td>';
    echo '<td><b>Hr. Final</b></td>';
    echo '<td><b>Diferença</b></td>';
}
echo '<td><b>TOTAL DE ATENDIMENTOS</b></td>';
echo '<td><b>TMA</b></td>';
echo '</tr></thead><tbody>';

switch ($coluna) {
    //-------------------------------Atendentes em Geral--------------------------//
    case 2: $sql = "select max(matricula) matricula, id_operador , desc_operador nome,  count (*) total_atendimentos, avg(tempo_atend) TMA 
                    from tb_eventos_dac as a
							left join tb_colaboradores_indra as b
							on a.id_operador = b.login_dac
							where data_hora between '$data $hora_inicial' and '$data $hora_final' and tempo_atend > 0
							group by  id_operador, desc_operador
							order by count(*) desc"; 
    break;
    
    //-------------------------------Somente Operadores--------------------------//
    case 3: $sql = "select max(matricula) matricula, id_operador , desc_operador nome, count (*) total_atendimentos, avg(tempo_atend) TMA from tb_eventos_dac as a
							left join tb_colaboradores_indra as b
							on a.id_operador = b.login_dac
							where data_hora between '$data $hora_inicial' and '$data $hora_final' and tempo_atend > 0
                            and id_operador not in (30103,
                                            30673,30912,30914,30686,30104,30459,31243,30463,31251,30100,31923,30480,30480,31649,31287,30960,30500,30075,30997,30076,31611,
                                            30077,31007,30539,31010,30395,31355,30667,31925,31367,31373,30800,31552,30566,31064,31387,31089,31090,31099,30414,30416,30589,
                                            30591,30083,31926,31440,31139,30857,30102,30069,32005)                                            
							group by  id_operador, desc_operador
							order by count(*) desc"; 
    break;
    
    //-------------------------------Prepostos--------------------------//
    case 4: $sql = "select max(matricula) matricula, id_operador , desc_operador nome,  count (*) total_atendimentos, avg(tempo_atend) TMA from tb_eventos_dac as a
							left join tb_colaboradores_indra as b
							on a.id_operador = b.login_dac
							where data_hora between '$data $hora_inicial' and '$data $hora_final' 
                            and tempo_atend > 0
                            and id_operador  in (30103,
                                            30673,30912,30914,30686,30104,30459,31243,30463,31251,30100,31923,30480,30480,31649,31287,30960,30500,30075,30997,30076,31611,
                                            30077,31007,30539,31010,30395,31355,30667,31925,31367,31373,30800,31552,30566,31064,31387,31089,31090,31099,30414,30416,30589,
                                            30591,30083,31926,31440,31139,30857,30102,30069,32005)
							group by  id_operador, desc_operador
							order by count(*) desc";
    break;
    //-------------------------------Operadores com Horas Extra--------------------------//
    case 5: $sql = "select 
            				t.id_operador,
            				max(t2.matricula) matricula,
            				t.desc_operador nome,
            				min(data_hora) hr_min,
            				max(data_hora) hr_maxima,
            			   (DATEDIFF(minute, min(t.data_hora),max(t.data_hora))/60.00) hr_dif,
            			    count (*) total_atendimentos, 
            				avg(tempo_atend) TMA 
            		from tb_eventos_dac t
            		left join tb_colaboradores_indra as t2 on (t.id_operador = t2.login_dac)
                    where t.data_hora between '$data $hora_inicial' and '$data $hora_final'            		
            		and t.id_operador is not null
            		group by t.id_operador, 
            				t.desc_operador
            		having
            		(
            		((DATEDIFF(minute, min(t.data_hora),max(t.data_hora))/60.00) > 7.25)
            		and
            		((DATEDIFF(minute, min(t.data_hora),max(t.data_hora))/60.00) < 15.00)
            		)
            		order by (DATEDIFF(minute, min(t.data_hora),max(t.data_hora))/60.00), t.id_operador,  t.desc_operador"; //operadores com HE
    break;
       
    //-------------------------------Operadores >= 90 Ligações --------------------------//
    case 7: $sql = "select     
                    	t.id_operador,
                    				max(matricula) matricula,
                    				t.desc_operador nome,							   
                    			    count (*) total_atendimentos, 
                    				avg(tempo_atend) TMA 
                    	from tb_eventos_dac t
                    	left join tb_colaboradores_indra as t2 on (t.id_operador = t2.login_dac)
                    	where t.data_hora between '$data $hora_inicial' and '$data $hora_final'
                    	and t.tempo_atend > 0						
                    	group by id_operador, t.desc_operador	
                    	having count(distinct callid) >= 90
                    	order by count(*) desc	";
    break;
}


//echo($sql);
//echo($data);
$total = 0;
$query = $pdo->prepare($sql);
$query->execute();

for($i=0; $row = $query->fetch(); $i++)
{
	// RECEBE RESULTADOS DA CONSULTA - INÍCIO
	$NOME = utf8_encode($row['nome']);
	$matricula = utf8_encode($row['matricula']);
	$ID = utf8_encode($row['id_operador']);		
	$total_atendimentos = utf8_encode($row['total_atendimentos']);			
	$TMA = utf8_encode($row['TMA']);	
	
	if ($coluna == 5)
	{	    
	    $hr_maxima = $row['hr_maxima'];	    	    
	    $hr_min = $row['hr_min'];
	    $hr_dif = $row['hr_dif'];
	}
	
	//totalizadores
	$SOMA_total_atendimentos = $SOMA_total_atendimentos + $total_atendimentos;
	$SOMA_TMA = $SOMA_TMA + ($TMA * $total_atendimentos);
		
	
	echo '<tr>';
	   // $total_atendimentos = number_format($total_atendimentos,2,',','.');
	   // $TMA = number_format($TMA,2,',','.');	    
	    echo "<td><a class='w3-text-indigo' title='Rastrear Atendimentos' href= \"lista_atendimentos_operador.php?ID=$ID&data_inicial=$data&data_final=$data&txt_dias_semana=$txt_dias_semana&in_semana=$in_semana\" target=\"_blank\">$ID</a></td>";
	    echo "<td>$NOME</td>";
		echo "<td>$matricula</td>";		
		if ($coluna == 5){
		    echo "<td>$hr_min</td>";
		    echo "<td>$hr_maxima</td>";
		    echo "<td>$hr_dif</td>";
		}
		echo "<td>$total_atendimentos</td>";				
		echo "<td>$TMA</td>";
	echo '</tr>';
	$total++;
}

// IMPRIME <TR> FINALIZADORA - INÍCIO

//$SOMA_TMA = $SOMA_TMA / $SOMA_total_atendimentos;
$SOMA_total_atendimentos = number_format($SOMA_total_atendimentos, 0, ',', '.');
$SOMA_TMA = number_format($SOMA_TMA, 0, ',', '.');

echo "</tbody><tr class='w3-indigo'>";
    echo "<td><b>Total: </b></td>";
    echo "<td><b>$total</b></td>";	
	echo "<td></td>";		
	if ($coluna == 5){
	    echo "<td></td>";
	    echo "<td></td>";
	    echo "<td></td>";
	}
	echo "<td></td>";	
	echo "<td></td>";
		
	
echo "</tr>";
// IMPRIME <TR> FINALIZADORA - FIM


echo "</div>";
echo "</table>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>