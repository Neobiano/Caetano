<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/radar.css">
<script src="js/jquery.min.js"></script>
</head>

<body>


<!-- CARREGANDO... INÍCIO -->
<div id="div_loading" class="w3-modal">
<div class="w3-modal-content" style="width:100%;height:100%;position:absolute;top:0;right:0;padding:0;margin:0;">
  <div class="w3-container w3-center w3-margin w3-padding-64">
	<img src="loading.gif" style="width:100px;">
	<p class="w3-text-black w3-center" style="margin-left:1px;"><font class="w3-small w3-wide"><b>CARREGANDOs</b></font></p>
  </div>
</div>
</div>
<script>
	document.getElementById('div_loading').style.display='block';
</script>
<!-- CARREGANDO... FIM -->

<?php
include "conecta.php"; // CONECTA AO BANCO DE DADOS
set_time_limit(30000); // DEFINE TEMPO DE CONSULTA
ini_set('max_execution_time', 30000); // DEFINE TEMPO DE CONSULTA
include "prepara_variaveis.php"; // RECEBE AS VARIÁVEIS DO FORMULARIO E EFETUA TRATAMENTOS


$tabela = ""; // INICIA A VARIÁVEL TABELA
include "funcoes.php";

$data_final_definida = strtotime($data_final);
$data_final_definida = date('Y-m-d',$data_final_definida);

$data_inicial_definida = strtotime($data_inicial);
$data_inicial_definida = date('Y-m-d',$data_inicial_definida);
//-------------------data 1---------------------------//
$data_final_definida1 = strtotime($data_final1);
$data_final_definida1 = date('Y-m-d',$data_final_definida1);

$data_inicial_definida1 = strtotime($data_inicial1);
$data_inicial_definida1 = date('Y-m-d',$data_inicial_definida1);

//-------------------data 2---------------------------//
$data_final_definida2 = strtotime($data_final2);
$data_final_definida2 = date('Y-m-d',$data_final_definida2);

$data_inicial_definida2 = strtotime($data_inicial2);
$data_inicial_definida2 = date('Y-m-d',$data_inicial_definida2);

$data_atual = date('Y-m-d');

switch ($tipo_consulta) { // VERIFICA QUAL A CONSULTA A SER REALIZADA
    case '01':
		switch ($select_filas) {			
			case '00':
			include "consulta_01_01.php"; // Percentual de Transferências - Todas
			break;
			
			case '01':
			include "consulta_01_02.php"; // Percentual de Transferências - Individual
			break;
		}	
        break;
		
	case '02':
        include "consulta_02b.php"; // Percentual de Retenção URA
        break;
	
	case '03':
        switch ($select_intervalo) {
			
			case '00':
			include "consulta_03_01.php"; // Quantidade de Operadores - 30 minutos
			break;
			
			case '01':
			include "consulta_03_02.php"; // Quantidade de Operadores - Diário
			break;
		}
        break;
	
	case '04':
        include "consulta_04.php"; // Ligações Multitransferências
        break;
		
	case '05':
        include "consulta_05.php"; // Categorização de Chamadas
        break;
		
	case '06':
	    if ($ckniveldia == '1')
            include "consulta_06b.php"; // Total de Ligações / TMA / NSA 45 / NSA 90
        else
            include "consulta_06.php"; // Total de Ligações / TMA / NSA 45 / NSA 90
            
        break;
		
	case '07': // TMA - Operador / Supervisor
		switch ($select_operador_supervisor) {
			
			case '00':
			include "consulta_07_operador.php"; // TMA - Operador
			break;
			
			case '01':
			include "consulta_07_supervisor.php"; // TMA - Supervisor
			break;
		}		
        break;
	
	case '08':
        include "consulta_08.php"; // Percentual de Atendimentos não Categorizados
        break;
		
	case '09':
        include "consulta_09.php"; // Tradutor de Eventos URA / FRONTEND
        break;
		
	case '12':
        include "consulta_12b.php"; // DNS - Dispersão do Nível de Serviço
        break;
		
	case '13':
        include "consulta_13.php"; // Comparativo tb_eventos_DAC X tb_fila_acumulado
        break;
        
	case '14':		
		if((strtotime($data_final_definida)<strtotime($data_atual)) && (strtotime($data_inicial_definida)<strtotime($data_atual))){
		
			switch ($qual_rechamadas) {	
				case '00':
					include "consulta_14_01.php"; // Total de Rechamadas (URA + ATC)
					break;
						
				case '01':
					include "consulta_14_02.php"; // Total de Rechamadas (ATC)
					break;
					
				case '02':
					include "consulta_14_03.php"; // Atendimentos em Rechamadas (ATC)
					break;
			}
			break;
		} else{
			echo "<div class = 'w3-container w3-center w3-margin w3-padding w3-tiny w3-deep-orange w3-card-4'><b>O período de consulta deve ser inferior à data atual.</b></div>";
			break;
		}
	
	case '15':
		include "consulta_15.php"; // Comparativo tb_eventos_DAC X tb_fila_acumulado
		break;
		
	case '16':
		include "consulta_16.php"; // Transferências Recorrentes
		break;
		
	case '17':
		include "consulta_17b.php"; // Transferências Recorrentes
		break;
		
	case '18':
		include "consulta_18.php"; // Monitora Desconexões URA
		break;
		
	case '19':
		include "consulta_19.php"; // Monitora Erros de Webservice URA
		break;

	case '20':
		include "consulta_20.php"; // Monitora Desbl. Cartão via URA
		break;
		
	case '21':
		include "consulta_21.php"; // Transferências para Mesma Fila
		break;
		
	case '22':
		include "consulta_22.php"; // Verifica Alimentação BD
		break;
	
	case '23':
		include "consulta_23.php"; // reicidencia de insatisfação
		break;
	
	case '24':
	    include "consulta_24.php"; // Analise de Retenção URA
	    break;
	    
	case '25':
	    include "consulta_25.php"; // Pesquisa de Satisfação - Motivo/Submotivo
	    break;
	    
    case '26':
	    include "consulta_26.php"; // Pesquisa de Satisfação - Motivo/Submotivo
	    break;
	    
    case '27':
        include "consulta_27.php"; // Pesquisa de Satisfação - Motivo/Submotivo
        break;
    case '28':
        
        echo '<script>
                function myFunction() {
                    window.open("PainelSincronia.php");
                }
                myFunction();
                </script>';
        //include "consulta_28.php"; // Pesquisa de Satisfação - Motivo/Submotivo
        
        break;	
    case '29':
        switch ($select_retencao) {            
            case '00':
                include "consulta_29.php"; // Retenção por bandeira
                break;               
            case '01':
                include "consulta_29b.php"; // Operador
                break;
            case '02':
                include "consulta_29c.php"; // Supervisor
                break;
        }		
        
        break;
        
    case '30':
        include "consulta_30.php"; // Retenção
        break;
        
    case '31':
        include "consulta_31.php"; // Pesquisa de Satisfação - Campanha
        break;
        
    case '32':
        include "consulta_32.php"; // Atendimentos - SAC
        break;
}
include "desconecta.php"; // DESCONECTA DO BANCO DE DADOS
?>

<!-- CARREGANDO... fadeOut - INÍCIO -->
<script>
	$("#div_loading").fadeOut('slow');
</script>
<!-- CARREGANDO... fadeOut - FIM -->

</body>
</html>