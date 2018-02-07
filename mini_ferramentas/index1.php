﻿<?php
	include "conecta.php";
	//preenchendo o select de filas
	$iniciou = 0;
	$in_filas = "108,109,";
	$query = $pdo->prepare("select cod_fila from tb_filas where desc_fila liKe '%CXA%'");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++){
		$cod_fila = utf8_encode($row['cod_fila']);
		$cod_fila = number_format($cod_fila, 0, ',', '.');
		if($iniciou == 0){
			$iniciou = 1;
			$in_filas = "$in_filas"."$cod_fila";
		}
		else $in_filas = "$in_filas".",$cod_fila";
	}
	
	//preenchendo o select de motivo 
	$in_motivos = "";
	//carregando o motivo das categorizações, pois não temos uma tabela de cadastros destes..
	$query = $pdo->prepare("select distinct  cd_motivo, ds_motivo from tb_log_categorizacao
                            where data_hora between (GETDATE() - 5) and (GETDATE() - 3)
                            order by ds_motivo");
	$query->execute();
	for($i=0; $row = $query->fetch(); $i++)
	{	    	    
	    $in_motivos .= '<option value="'.$row['cd_motivo'].'">'.$row['cd_motivo'].' - '.$row['ds_motivo'].'</option>';	   
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<link rel="stylesheet" href="css/jquery-ui.css" />


<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="js/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
<script src="js/jquery-ui.js"></script>

<script>
	function SomenteNumero(e, dado, limite){
		var numero = dado.value;
    	var tecla=(window.event)?event.keyCode:e.which;   
    	if((tecla>47 && tecla<58 && numero.length<limite)) 
    	  		return true;
    	else{
    		if (tecla==8 || tecla==0)
    			return true;
    		else  return false;
    	}
	}
</script>

<script>
    function mascaraHora_Inicial(campoHora, e){
    
        var tecla=(window.event)?event.keyCode:e.which;   
        if((tecla == 8)) return true;   
        var hora = campoHora.value;
        
            if (data.length == 2){
                hora = hora + ':';
                document.forms[0].hora_inicial.value = hora;
                return true;              
            }
            if (data.length == 5){
                hora = hora + ':';
                document.forms[0].hora_inicial.value = hora;
                return true;
            }
    }

</script>

<script>
    function mascaraHora_Final(campoHora, e){
    
        var tecla=(window.event)?event.keyCode:e.which;   
        if((tecla == 8)) return true;   
        var hora = campoHora.value;
        
            if (data.length == 2){
                hora = hora + ':';
                document.forms[0].hora_final.value = hora;
                return true;              
            }
            if (data.length == 5){
                hora = hora + ':';
                document.forms[0].hora_final.value = hora;
                return true;
            }
    }
</script>

<script>
function mascaraData_inicial(campoData, e,tipodata=0){

	var tecla=(window.event)?event.keyCode:e.which;   
	if((tecla == 8)) return true;	
	var data = campoData.value;
    
	    if (data.length == 2)
		{
	        data = data + '-';

	        switch (tipodata) 
	        {
	        	case 0:
	        		document.forms[0].data_inicial.value = data;
	            break;
	        	case 1:
	        		document.forms[0].data_inicial1.value = data;
	            break;
	        	case 2:
	        		document.forms[0].data_inicial2.value = data;
	            break;
	        }   	
	        
			return true;              
	    }
	    if (data.length == 5){
	        data = data + '-';

	        switch (tipodata) 
	        {
	        	case 0:
	        		document.forms[0].data_inicial.value = data;
	            break;
	        	case 1:
	        		document.forms[0].data_inicial1.value = data;
	            break;
	        	case 2:
	        		document.forms[0].data_inicial2.value = data;
	            break;
	        }   	
	        
	        return true;
	    }
}

function mascaraData_final(campoData, e,tipodata=0){

	var tecla=(window.event)?event.keyCode:e.which;   
	if((tecla == 8)) return true;	
	var data = campoData.value;
    
	    if (data.length == 2){
	        data = data + '-';

	        switch (tipodata) 
	        {
	        	case 0:
	        		document.forms[0].data_final.value = data;
	            break;
	        	case 1:
	        		document.forms[0].data_final1.value = data;
	            break;
	        	case 2:
	        		document.forms[0].data_final2.value = data;
	            break;
	        }   		        	        
        	
			return true;              
	    }
	    if (data.length == 5){
	        data = data + '-';
	        switch (tipodata) 
	        {
	        	case 0:
	        		document.forms[0].data_final.value = data;
	            break;
	        	case 1:
	        		document.forms[0].data_final1.value = data;
	            break;
	        	case 2:
	        		document.forms[0].data_final2.value = data;
	            break;
	        }   	
        	
	        return true;
	    }
}




</script>

<script>
    function hideAll(){
    	$("#div_datas").hide();
    	$("#div_horas").hide();
    	$("#div_datas1").hide();
    	$("#div_datas2").hide();
    	$("#div_button").hide();
    	$("#div_button2").hide();    	
    	$("#div_tex_detalhes").hide();
    	$("#div_select_dias_semana").hide();
    	$("#div_dia_semana").hide();
    	$("#div_qtd_transf").hide();
    	$("#div_select_operador_supervisor").hide();
    	$("#div_select_intervalo").hide();
    	$("#div_codigo_eventos").hide();
    	$("#div_fonte").hide();
    	$("#div_localiza_atendimentos").hide();
    	$("#div_select_filas").hide();
    	$("#div_qual_mes").hide();
    	$("#div_qual_ano").hide();
    	$("#div_qual_rechamadas").hide();
    	$("#div_reicidencia_pesq_satisfacao").hide();
    	$("#div_motivo_submtivo_pesq_satisfacao").hide();
    	$("#div_motivo_submotivo_det_resposta").hide();
    	
    	$("#div_dmm").hide();
    	$("#div_filas").hide();
    	$("#div_dias_excluir").hide();
    	$("#div_select_ilhas").hide();
    	$("#div_ilhas").hide();
    	$("#div_tempo_de_corte").hide();
    	$("#div_perg_satisfacao").hide();
    	$("#div_parametros_retencao_ura_c24").hide();
    
    	return true;		
    }
    
$(document).ready(function(){
	
	$("#tipo_consulta").change(function(){
			switch($("#tipo_consulta").val()){
				
				case '00':
					hideAll();	
    				
				break;
				
				case '01':
			    hideAll();	
			    $("#div_datas").show();	
			    $("#div_button").show();
				$("#div_tex_detalhes").show();
				$("#div_select_dias_semana").show();
				$("#txt_data_final").show();
				$("#data_final").show();
				$("#div_select_filas").show();
				$("#txt_data_inicial").html("Data Inicial:");
			    $("#txt_detalhes").text("Exibe os percentuais de transferências.");
			    $("#btn_pesquisar").html("Consultar");

				switch($("#select_dias_semana").val()){				
					case '00':
        				$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
        				$("#div_dia_semana").hide();
					break;					
    				case '01':
        				$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
        				$("#div_dia_semana").show();
    				break;
				}			    				
				
				
				break;
				
				case '02':
					hideAll();	
					$("#div_datas").show();
					$("#div_button").show();
    				$("#div_tex_detalhes").show();
    				$("#txt_data_final").show();
    				$("#data_final").show();
    				$("#txt_data_inicial").html("Data Inicial:");
					$("#txt_detalhes").text("Exibe o percentual de retenção na URA dia a dia.");
					$("#btn_pesquisar").html("Consultar");					    				
				break;
				
				case '03':
					hideAll();
					$("#div_datas").show();
					$("#div_button").show();
    				$("#div_tex_detalhes").show();
    				$("#txt_data_final").show();
    				$("#data_final").show();
    				$("#div_select_intervalo").show();    			
    				$("#div_select_ilhas").show();
					$("#txt_detalhes").text("Exibe a quantidade de operadores.");
					$("#txt_data_inicial").html("Data Inicial:");
					$("#btn_pesquisar").html("Consultar");
					switch($("#select_ilhas").val()){				
					case '00':
    					$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    					$("#div_ilhas").hide();
    					break;					
    					case '01':
    					$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
    					$("#div_ilhas").show();
    					break;
    				}
    				break;				    				    				
				
				case '04':	
					hideAll();				    				
    				$("#div_datas").show();
    				$("#div_button").show();
    				$("#div_tex_detalhes").show();
    				$("#txt_data_final").show();
    				$("#data_final").show();
    				$("#div_qtd_transf").show();
    				$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    				$("#txt_detalhes").text("Pesquisa ligações multitransferências. A quantidade mínima de transferências a serem listadas deve ser informada.");
    				$("#btn_pesquisar").html("Consultar");
    				    				
    				break;
    				
				case '05':
					hideAll();
					$("#div_button").show();
    				$("#div_tex_detalhes").show();
    				$("#div_select_dias_semana").show();
    				$("#txt_data_final").show();
    				$("#data_final").show();    				
    				$("#div_datas").show();
    				$("#btn_pesquisar").html("Consultar");
    				$("#txt_data_inicial").html("Data Inicial:");
					$("#txt_detalhes").text("Exibe a categorização de chamadas referente ao período informado.");
					
    				switch($("#select_dias_semana").val()){				
    					case '00':
    					$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    					$("#div_dia_semana").hide();
    					break;					
    					case '01':
    					$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
    					$("#div_dia_semana").show();
    					break;
    				}    				    				    				
					break;
				
				case '06': //TMA e Nível de Serviço
					hideAll();
										
					$("#div_datas").show();
					$("#div_button").show();
					$("#div_tex_detalhes").show();
					$("#txt_data_final").show();
					$("#data_final").show();
					$("#div_tempo_de_corte").show();
					$("#txt_detalhes").text("Exibe o total de ligações / TMA / NSA 45 e NSA 90 referente ao período informado.");
					$("#txt_data_inicial").html("Data Inicial:");
					$("#btn_pesquisar").html("Consultar");															
				break;
				
				case '07':
					hideAll();			
					$("#div_datas").show();
					$("#div_select_operador_supervisor").show();
					$("#div_button").show();
					$("#div_tex_detalhes").show();
					$("#div_select_dias_semana").show();
					$("#txt_data_final").show();
					$("#data_final").show();
					$("#txt_data_inicial").html("Data Inicial:");
					$("#btn_pesquisar").html("Consultar");
					$("#txt_detalhes").text("Exibe o TMA dos operadores/supervisores referente ao período informado.");		
					
					switch($("#select_dias_semana").val()){				
					case '00':
    					$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    					$("#div_dia_semana").hide();
    					break;					
    					case '01':
    					$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
    					$("#div_dia_semana").show();
    					break;
    				}
    				break;																				
				
				case '08': //Perc. Atend. não Categorizados		
					hideAll();						
    				$("#div_datas").show();
    				$("#div_button").show();
    				$("#div_tex_detalhes").show();
    				$("#div_select_dias_semana").show();
    				$("#txt_data_final").show();
    				$("#data_final").show();    				
    				$("#btn_pesquisar").html("Consultar");
    				$("#txt_detalhes").text("Exibe o percentual de não categorização dos atendimentos pelos operadores referente ao período informado.");
					$("#txt_data_inicial").html("Data Inicial:");
    				switch($("#select_dias_semana").val()){				
    					case '00':
        						$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
        						$("#div_dia_semana").hide();
        					break;					
        				case '01':
        						$('#frame_230', top.document).eq(0).attr ('rows', '290,*');
        						$("#div_dia_semana").show();
        					break;
    				}
    				break;    	
    							    				    								
				case '09': //URA / FRONTEND - Tradutor de Evento
					hideAll();
					$("#div_codigo_eventos").show();
    				$("#div_fonte").show();
					$("#div_button").show();
    				$("#div_tex_detalhes").show();
					$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    				$("#txt_detalhes").text("Traduz sequência de eventos.");
    				$("#txt_data_inicial").html("Data Inicial:");
    				$("#btn_pesquisar").html("Traduzir");
    				switch($("#select_fonte").val()){				
    					case '00':
    						$("#codigo_evento").attr("placeholder", "Exemplo: MENU001;MENU005;MENU007;MENU009");
    					break;					
    					case '01':
    						$("#codigo_evento").attr("placeholder", "Exemplo: 001;002;003;004");
    					break;
    					case '02':
    						$("#codigo_evento").attr("placeholder", "Exemplo: LOG_10;LOG_11;LOG_12;LOG_13");
    					break;
    				}
    				break;
    				    			    								
				case '11':
					hideAll();
    				$("#txt_detalhes").text("Localiza atendimentos a partir de alguns dados.");
    				$("#txt_data_inicial").html("Data Inicial:");
    				$("#btn_pesquisar").html("Consultar");
    				$("#div_localiza_atendimentos").show();
    				$("#div_datas").show();
    				$("#div_button").show();
    				$("#div_tex_detalhes").show();
    				$("#txt_data_final").show();
    				$("#data_final").show();
    				switch($("#select_dias_semana").val()){				
    					case '00':
        					$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
        					$("#div_dia_semana").hide();
    					break;					
    					case '01':
        					$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
        					$("#div_dia_semana").show();
    					break;
        			}
        			break;				    				    			
				
				case '12': //DNS - Dispersão de Nível de Serviço
					hideAll();
					$("#div_dmm").show();
    				$("#div_dias_excluir").show();
    				$("#div_filas").show();    				
    				$("#div_qual_mes").show();
    				$("#div_qual_ano").show();
					$("#div_button").show();
    				$("#div_tex_detalhes").show();
    				
					$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    				$("#txt_detalhes").text("Calcula o DNS - Dispersão de Nível de Serviço por Faixa de Horário.");
    				$("#txt_data_inicial").html("Data:");
    				$("#btn_pesquisar").html("Consultar");
    				    				    				
				break;
				
				case '13': //Comparativo tb_eventos_DAC X tb_fila_acumulado
					hideAll();
					$("#div_datas").show();
					$("#div_button").show();
					$("#div_tex_detalhes").show();
					$("#txt_data_final").show();
					$("#data_final").show();
					$("#div_select_filas").show();
					$("#txt_data_inicial").html("Data Inicial:");
					$("#btn_pesquisar").html("Consultar");
					$("#txt_detalhes").text("Comparativo tb_eventos_DAC X tb_fila_acumulado");										
				break;

				case '14': //Incidência de Rechamadas
					hideAll();
					$("#div_datas").show();
					$("#div_button").show();
					$("#div_tex_detalhes").show();
					$("#div_qual_rechamadas").show();
					$("#txt_data_final").show();
					$("#data_final").show();
					$("#txt_data_inicial").html("Data Inicial:");
					$("#btn_pesquisar").html("Consultar");
					$("#txt_detalhes").text("Exibe a incidência de rechamadas dia a dia.");

					break;

				case '15': //URA - Eventos x Quantidade
					hideAll();
					$("#div_datas").show();
					$("#div_button").show();
					$("#div_tex_detalhes").show();
					$("#div_select_dias_semana").show();
					$("#txt_data_final").show();
					$("#data_final").show();
					
					$("#txt_detalhes").text("Exibe os eventos acessados na URA e a quantidade em um determinado período.");
					$("#txt_data_inicial").html("Data Inicial:");
					$("#btn_pesquisar").html("Consultar");

					switch($("#select_dias_semana").val()){				
    					case '00':
        					$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
        					$("#div_dia_semana").hide();
    					break;					
    					case '01':
        					$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
        					$("#div_dia_semana").show();
    					break;
    				}

					break;

				case '16': //Transferências Recorrentes
					hideAll();
					$("#div_button").show();
					$("#div_tex_detalhes").show();
					$("#div_datas").show();
					$("#txt_data_final").show();
					$("#data_final").show();
					
					$("#btn_pesquisar").html("Consultar");
					$('#frame_230', top.document).eq(0).attr ('rows', '260,*');					
					$("#txt_detalhes").text("Pesquisa transferências recorrentes.");
					
					break;

				case '17': //Pesquisa de Satisfação
					hideAll();
					
					$("#div_datas").show();
					$("#div_button").show();
					$("#div_tex_detalhes").show();
					$("#txt_data_final").show();
					$("#data_final").show();
					$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
					$("#txt_detalhes").text("Exibe os totalizadores da Pesquisa de Satisfação.");
					$("#btn_pesquisar").html("Consultar");
				
					break;
					
					case '18': //URA - Monitora Desconexões
						hideAll();
						$("#txt_data_final").show();
    					$("#data_final").show();
						$("#div_button").show();
    					$("#div_tex_detalhes").show();
						$("#div_datas").show();
						$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    					$("#txt_detalhes").text("Monitora as Desconexões da URA");  
    					$("#btn_pesquisar").html("Consultar");  					
    				
    					break;
					
					case '19': //URA - Monitora Erros de Webservice
						hideAll();
						$("#div_datas").show();
						$("#div_button").show();
						$("#txt_data_final").show();
    					$("#data_final").show();
    					$("#div_tex_detalhes").show();
    					$("#btn_pesquisar").html("Consultar");
						$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    					$("#txt_detalhes").text("Monitora erros de Webservice URA");
    					    					    					
    					break;
					
					case '20': //URA - Monitora Desbl. Cartão via URA	
						hideAll();   
						$("#div_button").show();
        				$("#div_tex_detalhes").show();     				
        				$("#div_datas").show();
        				$("#txt_data_final").show();
        				$("#data_final").show();
        				$("#btn_pesquisar").html("Consultar");
        				$("#txt_detalhes").text("Monitora desbloqueio de cartão via URA");
        				$('#frame_230',top.document).eq(0).attr ('rows', '260,*');        				        									        			
        				break;
					
					case '21': //Transferências para Mesma Fila
						hideAll();  
										
    					$("#div_datas").show();
    					$("#div_button").show();
    					$("#div_tex_detalhes").show();
    					$("#txt_data_final").show();
    					$("#data_final").show();

    					$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    					$("#txt_detalhes").text("Pesquisa transferências pra mesma fila.");   
    					$("#btn_pesquisar").html("Consultar"); 	
    					    					
    					break;
					
					case '22': //BD - Verifica Alimentação BD
						hideAll();  									
    					$("#div_datas").show();					
    					$("#div_button").show();
    					$("#div_tex_detalhes").show();					
    					$("#txt_data_final").show();
    					$("#data_final").show();
    					    					    					
    					$("#btn_pesquisar").html("Consultar");
    					$('#frame_230', top.document).eq(0).attr ('rows', '260,*');
    					$("#txt_detalhes").text("Verifica se as tabelas no Banco de Dados foram alimentadas corretamente.");
					
					break;
					
					
					case '23': //Pesquisa de Satisfação - Detalhamento
						hideAll();
						$("#div_perg_satisfacao").show();
						$("#div_datas").show();
						$("#div_button2").show();
						$("#div_tex_detalhes").show();
						$("#txt_data_final").show();
						$("#data_final").show();
						$("#div_reicidencia_pesq_satisfacao").show();
						$('#frame_230', top.document).eq(0).attr ('rows', '320,*');
						$("#txt_detalhes").text("Exibe os registros de reicidência de clientes insatisfeitos");
						$("#btn_pesquisar2").html("Consultar");
																											
					break;

					case '24': //URA - Análise de Retenção/Desconexão
						hideAll();
						$("#txt_detalhes").text("Exibe a análise de retenção da URA");						
						$("#div_datas1").show();
						$("#div_datas2").show();
						$("#data_final").show();
						$("#div_button").show();
						$("#div_tex_detalhes").show();
						$("#div_parametros_retencao_ura_c24").show();						
						$('#frame_230', top.document).eq(0).attr ('rows', '320,*');																		
						$("#txt_data_inicial").html("Data Inicial:");
						$("#btn_pesquisar").html("Consultar");						
						
						break;
						
					case '25': //Pesquisa de Satisfação - Motivo/SubMotivo
						hideAll();
						
						$("#div_perg_satisfacao").show();																		
						$("#div_datas1").show();
						$("#div_motivo_submtivo_pesq_satisfacao").show();						
						$("#div_button2").show();				
						$("#div_tex_detalhes").show();																		
						$('#frame_230', top.document).eq(0).attr ('rows', '360,*');
						$("#txt_detalhes").text("Exibe os registros de reicidência de clientes insatisfeitos");
						$("#btn_pesquisar2").html("Consultar");
							
												
						break;

					case '26': //Pesquisa de Satisfação - Monitoramento de Respostas
						hideAll();																								
						$("#div_datas1").show();
						$("#div_motivo_submotivo_det_resposta").show();																		
						$("#div_button").show();				
						$("#div_tex_detalhes").show();
						$("#txt_detalhes").text("Exibe o quantitativo de resposta de acordo com os filtros aplicados");																										
						$('#frame_230', top.document).eq(0).attr ('rows', '360,*');		
						$("#btn_pesquisar").html("Consultar");																															
						break;

					case '27': //Quantidade de Operadores - Detalhamento
						hideAll();
						$("#div_horas").show();
						$("#div_datas").show();
						$("#div_button").show();
	    				$("#div_tex_detalhes").show();
	    				$("#txt_data_final").show();
	    				$("#data_final").show();	    				    				    			
						$("#txt_detalhes").text("Exibe a quantidade de operadores atuantes de forma detalhada.");
						$("#txt_data_inicial").html("Data Inicial:");
						$("#btn_pesquisar").html("Consultar");						
	    				break;																																	
						
		}
	});
});

$(document).ready(function(){
	$("#select_dias_semana").change(function(){
			switch($("#select_dias_semana").val()){
				case '00':
				$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
				$("#div_dia_semana").hide();
				break;
				case '01':
				$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
				$("#div_dia_semana").show();
				break;
		}
	});
});

$(document).ready(function(){
	$("#select_ilhas").change(function(){
			switch($("#select_ilhas").val()){
				case '00':
				$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
				$("#div_ilhas").hide();
				break;
				case '01':
				$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
				$("#div_ilhas").show();
				break;
		}
	});
});

$(document).ready(function(){
	$("#select_fonte").change(function(){
			$("#codigo_evento").val("");
			switch($("#select_fonte").val()){
				case '00':
					$("#codigo_evento").attr("placeholder", "Exemplo: MENU001;MENU005;MENU007;MENU009");
					break;					
					case '01':
					$("#codigo_evento").attr("placeholder", "Exemplo: 001;002;003;004");
					break;
					case '02':
					$("#codigo_evento").attr("placeholder", "Exemplo: LOG_10;LOG_11;LOG_12;LOG_13");
					break;
		}
	});
});
</script>

<script>
    $(function() {
        $( "#data_inicial" ).datepicker({
    		prevText: 'Anterior',
    		nextText: 'Próximo',
    		currentText: 'Hoje',
            dateFormat: 'dd-mm-yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    		beforeShow: aumentaFrame,
    		onClose: diminuiFrame
        });
    });

    $(function() {
        $( "#data_inicial1" ).datepicker({
    		prevText: 'Anterior',
    		nextText: 'Próximo',
    		currentText: 'Hoje',
            dateFormat: 'dd-mm-yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    		//beforeShow: aumentaFrame,
    		//onClose: diminuiFrame
        });
    });

    $(function() {
        $( "#data_inicial2" ).datepicker({
    		prevText: 'Anterior',
    		nextText: 'Próximo',
    		currentText: 'Hoje',
            dateFormat: 'dd-mm-yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    		beforeShow: aumentaFrame,
    		onClose: diminuiFrame
        });
    });
    
    $(function() {
        $( "#data_final" ).datepicker({
    		prevText: 'Anterior',
    		nextText: 'Próximo',
    		currentText: 'Hoje',
            dateFormat: 'dd-mm-yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
            dayNamesMin: ['D','S','T','Q','Q','S','S'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    		beforeShow: aumentaFrame,
    		onClose: diminuiFrame
        });
    });

    $(function() {
        $( "#data_final1" ).datepicker({
    		prevText: 'Anterior',
    		nextText: 'Próximo',
    		currentText: 'Hoje',
            dateFormat: 'dd-mm-yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
            dayNamesMin: ['D','S','T','Q','Q','S','S'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    		//beforeShow: aumentaFrame,
    		//onClose: diminuiFrame
        });
    });

    $(function() {
        $( "#data_final2" ).datepicker({
    		prevText: 'Anterior',
    		nextText: 'Próximo',
    		currentText: 'Hoje',
            dateFormat: 'dd-mm-yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
            dayNamesMin: ['D','S','T','Q','Q','S','S'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    		beforeShow: aumentaFrame,
    		onClose: diminuiFrame
        });
    });
</script>


<script>
function aumentaFrame(){  
     $ ('#frame_230', top.document).eq(0).attr ('rows', '400,*');
};
</script>

<script>
function diminuiFrame(){  
     switch($("#select_dias_semana").val()){
				case '00':
				$ ('#frame_230', top.document).eq(0).attr ('rows', '260,*');
				break;
				case '01':
				$ ('#frame_230', top.document).eq(0).attr ('rows', '290,*');
				break;
	 }
	 
};
</script>

<style>
.ui-datepicker{
	font-family:Verdana,sans-serif;
	font-size: 12px;
	padding-left:3px;
	padding-right:3px;
}

.ui-datepicker-header{
		margin-top:1px;
}

</style>

</head>

<body>

<!-- LOGO CAIXA -->
<br>
<div class="w3-container w3-center">
	<img src="logo.png" style="width:140px">
</div>			
<hr>

<!-- TÍTULO -->
<div class='w3-container w3-padding w3-margin w3-tiny w3-center w3-indigo w3-wide w3-card-4'><b>MINI FERRAMENTAS - CONTRATO INDRA MARACANAÚ</b></div>

<!-- DIV DO FORMULÁRIO - INÍCIO -->
<div class="w3-tiny w3-container w3-light-grey w3-bottombar w3-border-indigo w3-margin w3-padding-0 w3-card-4 w3-round">

	<!-- FORMULÁRIO - INÍCIO -->
	<form action="imprime_tabela.php" method="post" class="w3-container" target="frame_secundario">
		<div class="w3-container">

    		<!-- CAIXA DE SELEÇÃO "CONSULTA" -->
    		<div class="w3-left w3-margin-top w3-margin-bottom">
    			<b>Consulta:</b>
    			<select id= "tipo_consulta" name="tipo_consulta"> <!-- 15 -->
    				<option value="00"></option>
    				<option value="01">Percentual de Transferências</option>
    				<option value="04">Ligações Multitransferências</option>
    				<option value="16">Transferências Recorrentes</option>
    				<option value="21">Transferências para Mesma Fila</option>
    				<option value="17">Pesquisa de Satisfação</option>
    				<option value="23">Pesquisa de Satisfação - Detalhamento</option>
    				<option value="25">Pesquisa de Satisfação - Motivo/SubMotivo</option>
    				<option value="26">Pesquisa de Satisfação - Monitoramento de Respostas</option>
    				
    				<option value="" class='w3-border-top w3-margin-top' style='padding-top: 16px;'disabled></option>
    				<option value="02">Percentual de Retenção URA</option>
    				<option value="14">Incidência de Rechamadas</option>
    				<option value="03">Quantidade de Operadores</option>
    				<option value="27">Quantitativo de Operadores - Detalhamento</option>
    				<option value="06">TMA e Nível de Serviço</option> <!-- Incluir Consulta por Faixa de Horário / Geral (Dia-a-Dia / Faixa Horário) / Por Ilha -->
    				<option value="07">TMA - Operador / Supervisor</option>
    				<option value="08">Perc. Atend. não Categorizados</option> <!-- Por Skill -->
    				<option value="05">Categorização de Chamadas</option>
    				<option value="12">DNS - Dispersão de Nível de Serviço</option>
    				
    				<option value="" class='w3-border-top w3-margin-top' style='padding-top: 16px;'disabled></option>
    				<option value="15">URA - Eventos x Quantidade</option>
    				<option value="24">URA - Análise de Retenção/Desconexão</option>
    				<option value="18">URA - Monitora Desconexões</option>
    				<option value="19">URA - Monitora Erros de Webservice</option>
    				<option value="20">URA - Monitora Desbl. Cartão via URA</option>
    				<option value="09">URA / FRONTEND - Tradutor de Evento</option>
    				<option value="22">BD - Verifica Alimentação BD</option>
    				
    				<!-- <option value="10">Pesquisa de Padrões em Eventos URA</option> -->
    				<!-- <option value="11">Localiza Atendimentos</option> -->
    				<!-- VDN / Pesquisa de Satisfação / Retenção de Ligações URA/ATC / 06 - Incluir Consulta por Faixa de Horário -->
    				<!-- <option value="13">Comparativo tb_eventos_DAC X tb_fila_acumulado</option>  -->
    			</select>
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO "MES" -->
    		<div id="div_qual_mes" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Mês:</b>
    			<select id= "qual_mes" name="qual_mes">
    				<option value="00"></option>
    		    	<option value="01">Janeiro</option>	
    		    	<option value="02">Fevereiro</option>
    		    	<option value="03">Março</option>
    		    	<option value="04">Abril</option>
    		    	<option value="05">Maio</option>
    		    	<option value="06">Junho</option>
    		    	<option value="07">Julho</option>
    		    	<option value="08">Agosto</option>
    		    	<option value="09">Setembro</option>
    		    	<option value="10">Outubro</option>
    		    	<option value="11">Novembro</option>
    		    	<option value="12">Dezembro</option>	    	
    			</select>
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO "RECHAMADAS" -->
    		<div id="div_qual_rechamadas" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Modelo:</b>
    			<select id= "qual_rechamadas" name="qual_rechamadas">
    				<option value="00">Total de Rechamadas (URA + ATC)</option>
    		    	<option value="01">Total de Rechamadas (ATC)</option>
    		    	<!-- <option value="02">Atendimentos em Rechamadas (ATC)</option> -->	    	
    			</select>
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO "REICIDENCIA PESQUISA SATISFAÇÃO" -->
    		<div id="div_reicidencia_pesq_satisfacao" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Origem:</b>
    			<select id= "select_origem_reicidencia" name="select_origem_reicidencia">
    				<option value="03">Telefone Originador</option>
    		    	<option value="02">CPF Demandante</option>
    		    	<option value="01">Cartão Demandante</option>		    	    	
    			</select>
    		</div>
    		
    		<!-- CAIXA DE SELEÇÃO "REICIDENCIA PESQUISA SATISFAÇÃO" --> 
    		<div id="div_motivo_submtivo_pesq_satisfacao" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<fieldset style="display:block !important;">
    				<legend>Motivo/SubMotivo</legend> 
        			<div id="div_motivo" class="w3-left w3-margin-bottom w3-margin-left">
                       <b><label for="cd_motivo" style="display:block !important;">Motivos</label></b> 
                        <select name="cd_motivo" id="cd_motivo">
                            <option value=""></option>
                            <?php echo $in_motivos;?>
                        </select>
                    </div>  
           
                    <div id="div_submotivo" class="w3-left w3-margin-bottom w3-margin-left" >
                        <b><label for="cd_submotivo" style="display:block !important;">SubMotivos</label></b>       
                        <select name="cd_submotivo" id="cd_submotivo">
                            <option value="">-- Escolha um submotivo --</option>
                        </select>       
                        <span class="carregando" >Aguarde, carregando...</span>                                                     
                    </div>
                </fieldset>
    		</div>
    		
    		<!-- caixa multiseletora para motivo/submotivo no detalhamento de resposta - pesquisa de satisfação --> 
    		<div id="div_motivo_submotivo_det_resposta" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<fieldset style="display:block !important;">
    				<div id='div_motivos' class='w3-left w3-margin-top w3-margin-bottom w3-margin-left'>	
    					<b id='txt_motivos'>Motivos :</b>
    					<input id='cb_motivos' type='text' size='20' name='cb_motivos'  placeholder='19,12,13,25'>
    				</div>
    				<div id='div_submotivos' class='w3-left w3-margin-top w3-margin-bottom w3-margin-left'>	
    					<b id='txt_submotivos'>Submotivos :</b>
    					<input id='cb_submotivos' type='text' size='25' name='cb_submotivos'  placeholder='150,138,240'>
    				</div>
                </fieldset>
    		</div>
		
		
    		<!-- INPUT ANO -->
    		<div id="div_qual_ano" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Ano:</b>
    			<input size='10' id="qual_ano" type='text' name="qual_ano" value='' onkeypress='return SomenteNumero(event, this, 4)'>
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO "FILAS" -->
    		<div id="div_select_filas" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Modelo:</b>
    			<select id="select_filas" name="select_filas">
    				<option value="01">Por Fila / Ilha</option>
    				<option value="00">Por Período / Dia a Dia</option>
    			</select>		
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO "OPERADOR / SUPERVISOR" -->
    		<div id="div_select_operador_supervisor" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Relacionar por:</b>
    			<select id="select_operador_supervisor" name="select_operador_supervisor">
    				<option value="00">Operador</option>
    				<option value="01">Supervisor</option>
    			</select>		
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO "POR DIA / FAIXA DE HORÁRIO" -->
    		<div id="div_select_intervalo" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Intervalo:</b>
    			<select id="select_intervalo" name="select_intervalo">
    				<option value="00">30 Minutos</option>
    				<option value="01">Diário</option>
    			</select>		
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO ILHAS -->
    		<div id="div_select_ilhas" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Ilhas:</b>
    			<select id="select_ilhas" name="select_ilhas">
    				<option value="00">Todas</option>
    				<option value="01">Selecionar</option>
    			</select>
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO "OPERADOR / SUPERVISOR" -->
    		<div id="div_fonte" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Fonte:</b>
    			<select id="select_fonte" name="select_fonte">
    				<option value="00">URA - Produção</option>
    				<option value="01">URA - Nova</option>
    				<option value="02">FRONTEND</option>
    			</select>		
    		</div>
		
    		<!-- DIV DATAS - div_datas -->
    		<div id="div_codigo_eventos" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">	
    			<b id="txt_codigo_evento">Sequêcia de eventos (separados por ;):</b>
    			<input id="codigo_evento" type='text' size='40' name="codigo_evento" value='' onkeypress="mascaraData_final(this, event);" placeholder="Exemplo: MENU001;MENU005;MENU007">
    		</div>
		
		
		
    		<!-- DIV DMM -->
    		<div id="div_dmm" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">	
    			<b id="txt_dmm">DMM:</b>
    			<input id="dmm" type='text' size='10' name="dmm" value='' onkeypress="mascaraData_final(this, event);" placeholder="">
    		</div>
		
    		<!-- DIV DESCONSIDERAR -->
    		<div id="div_dias_excluir" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">	
    			<b id="txt_dias_excluir">Excluir Dias:</b>
    			<input id="dias_excluir" type='text' size='10' name="dias_excluir" value='' onkeypress="mascaraData_final(this, event);" placeholder="">
    		</div>
		
    		<!-- DIV FILAS -->
    		<?php
    			echo "<div id='div_filas' class='w3-left w3-margin-top w3-margin-bottom w3-margin-left'>	
    					<b id='txt_filas'>Filas :</b>
    					<input id='in_filas' type='text' size='10' name='in_filas' value='$in_filas' onkeypress='mascaraData_final(this, event);' placeholder=''>
    				</div>";
    		?>

    		<!-- DIV DATAS - div_datas -->
    		<div id="div_datas" class="w3-left w3-margin">										
    			<b id="txt_data_inicial">Data Inicial:</b>
    			<input id="data_inicial" type='text' size='10' name="data_inicial" value='' onkeypress="mascaraData_inicial(this, event);" maxlength="10">
    			
    			<b id="txt_data_final" class='w3-margin-left'>Data Final:</b>
    			<input id="data_final" type='text' size='10' name="data_final" value='' onkeypress="mascaraData_final(this, event);" maxlength="10">        					
    		</div>
    		
    		<!-- DIV Horas - div_horas -->
    		<div id="div_horas" class="w3-left w3-margin">										
    			<b id="txt_hr_inicial">Hora Inicial:</b>
    			<input id="hora_inicial" type='text' size='10' name="hora_inicial" value='00:00:00' onkeypress="mascaraHora_Inicial(this, event);" maxlength="10">
    			
    			<b id="txt_hora_final" class='w3-margin-left'>Hora Final:</b>
    			<input id="hora_final" type='text' size='10' name="hora_final" value='23:59:59' onkeypress="mascaraHora_Final(this, event);" maxlength="10">        					
    		</div>
		
    		<!-- DIV DATAS - div_datas -->
    		<div id="div_datas1" class="w3-left w3-margin">	
    			<fieldset style="display: block; height: 75px; padding: 10px;">	
    			 	<legend>Intervalo</legend>		
    			 		 <div class="w3-left w3-margin-bottom w3-margin-left" >
        			 		 <b><label for="data_inicial1" style="display:block !important;">Data Inicial:</label></b>             			
                			 <input id="data_inicial1" type='text' size='13' name="data_inicial1" value='' onkeypress="mascaraData_inicial(this, event, 1);" maxlength="10">             			            			
     					 </div>       			
	           			 <div class="w3-left w3-margin-bottom w3-margin-left">
    	           			 <b><label for="data_final1" style="display:block !important;">Data Final:</label></b>
                			 <input id="data_final1" type='text' size='14' name="data_final1" value='' onkeypress="mascaraData_final(this, event, 1);" maxlength="10">
						 </div>            			 
            	</fieldset>				
    		</div>
    		<!-- DIV DATAS 2 - Para usar como comparativo (benchmark) -->
    		<div disabled id="div_datas2" class="w3-left w3-margin">		
    			<fieldset style="display: block; height: 75px; padding: 10px;">	
    			 	<legend>Benchmark</legend>	
    			 	<div class="w3-left w3-margin-bottom w3-margin-left" >
    			 		 <b><label for="data_inicial1" style="display:block !important;">Data Inicial:</label></b>             			
            			 <input disabled id="data_inicial2" type='text' size='13' name="data_inicial1" value='' onkeypress="mascaraData_inicial(this, event, 1);" maxlength="10">             			            			
 					 </div>       			
           			 <div class="w3-left w3-margin-bottom w3-margin-left">
	           			 <b><label for="data_final1" style="display:block !important;">Data Final:</label></b>
            			 <input disabled id="data_final2" type='text' size='14' name="data_final1" value='' onkeypress="mascaraData_final(this, event, 1);" maxlength="10">
					 </div>
    			 	
    			 	
            		
        		</fieldset>			
    		</div>
		
    		<div id="div_tempo_de_corte" class="w3-left w3-margin-top w3-margin-bottom">		
    			<b>Ligações a partir de (segundos):</b>
    			<input id="tempo_de_corte" type='text' size='1' name="tempo_de_corte" value='20' onkeypress='return SomenteNumero(event, this, 2)' maxlength="2">
    		</div>
		
    		<!-- CAIXA DE SELEÇÃO "OPCAO_DIA_SEMANA" -->
    		<div id="div_select_dias_semana" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">
    			<b>Dias da Semana:</b>
    			<select id="select_dias_semana" name="select_dias_semana">
    				<option value="00">Todos</option>
    				<option value="01">Selecionar</option>
    			</select>		
    		</div>
		
    		<div id="div_qtd_transf" class="w3-left w3-margin-top w3-margin-bottom w3-margin-left">		
    			<b id="txt_qtd_transf">Quantidade Mínima de Transferências:</b>
    			<input id="qtd_transf" type='text' size='1' name="qtd_transf" value='3' onkeypress='return SomenteNumero(event, this, 2)' maxlength="2">
    		</div>
		
    		<!-- DIV BOTÃO CONSULTAR - div_button -->		
    		<div id="div_button" class="w3-left w3-margin">
    			<button id="btn_pesquisar" class="w3-btn w3-deep-orange w3-round w3-tiny" type="submit" name="btn_pesquisar" value="Gerar">Consultar</button>
    		</div>
		
		</div><!-- final"w3-container"-->
	
    	<!-- DIV DIAS DA SEMANA - div_dia_semana -->		
    	<div id="div_dia_semana" class="w3-container">
    		<div class="w3-left" style="margin-top: 8px; margin-bottom: 16px;"> <b>Dias da Semana:</b> </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_1" name="chk_1" value = "1" checked>Domingo &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_2" name="chk_2" value = "2" checked>Segunda-Feira &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_3" name="chk_3" value = "3" checked>Terça-Feira &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_4" name="chk_4" value = "4" checked>Quarta-Feira &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_5" name="chk_5" value = "5" checked>Quinta-Feira &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_6" name="chk_6" value = "6" checked>Sexta-Feira &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_7" name="chk_7" value = "7" checked>Sábado</div>
    	</div>
	
    	<!-- DIV Ilhas - div_ilhas -->		
    	<div id="div_ilhas" class="w3-container">
    		<div class="w3-left" style="margin-top: 8px; margin-bottom: 16px;"> <b>Ilhas:</b> </div>
    		<?php
    		$query = $pdo->prepare("select * from tb_ilhas");
    		$query->execute();
    		for($i=0; $row = $query->fetch(); $i++){
    			$nome_ilha = utf8_encode($row['nome_ilha']);
    			$cod_filas = utf8_encode($row['cod_filas']);
    			$desc_ilha = utf8_encode($row['desc_ilha']);			
    			echo "<div class='w3-left'> <input class='w3-margin-8' type='checkbox' id='chk_$nome_ilha' name='chk_$nome_ilha' value = '$cod_filas' checked>$desc_ilha &nbsp &nbsp </div>";			
    		}
    		?>
    	</div>
	
    	<div id="div_perg_satisfacao" class="w3-container">  
    		<div>
    	    	<fieldset style="display: inline-block; height: 70px; padding: 10px;">
        			<legend>Perguntas - Pesquisa de Satisfação</legend>          
            		<div id="div_perg_satisfacao_perg1" class="w3-left w3-margin-bottom ">
            		   <b><label for="perg1" style="display:block !important;">No Geral, qual seu grau de satisfação?</label></b> 
            			<select name="perg1" id="perg1" style="width:240px">
            				<option value="0" selected>Todas</option>  
            				<option value="1">Satisfeito</option>
            				<option value="2">Indiferente</option>
            				<option value="3">Insatisfeito</option>                   
            			</select>
            		</div>             		                  
                                    
            		<div id="div_perg_satisfacao_perg2" class="w3-left w3-margin-bottom w3-margin-left">
            		   <b><label for="perg2" style="display:block !important;" >Quanto ao tempo de espera, você se considera:</label></b> 
            			<select name="perg2" id="perg2" style="width:300px">
            				<option value="0" selected>Todas</option>
            				<option value="1">Satisfeito</option>
            				<option value="2">Indiferente</option>
            				<option value="3" >Insatisfeito</option>   
            			</select>
            		</div>                                     
            		
            		<div id="div_perg_satisfacao_perg3" class="w3-left w3-margin-bottom w3-margin-left">
            		   <b><label for="perg3" style="display:block !important;">Quanto à cordialidade do atendente, você se considera:</label></b> 
            			<select name="perg3" id="perg3" style="width:310px">
            				<option value="0" selected>Todas</option>
            				<option value="1">Satisfeito</option> 
            				<option value="2">Indiferente</option>
            				<option value="3" >Insatisfeito</option>                      
            			</select>
            		</div>  
            
            		<div id="div_perg_satisfacao_perg4" class="w3-left w3-margin-bottom w3-margin-left">
            		   <b><label for="perg4" style="display:block !important;">A solicitação foi atendida ao final do atendimento?</label></b> 
            			<select name="perg4" id="perg4" style="width:300px">
            				<option value="0" selected>Todas</option>
            				<option value="1">Sim</option>
            				<option value="2">Parcialmente</option>
            				<option value="3">Não</option>                        
            			</select>
            		</div> 
            		<!-- DIV BOTÃO CONSULTAR - div_button -->		
            		
            	</fieldset>
            	<div class="w3-right ">
        			<br>
        			<br>
        			<br>
    				<button id="btn_pesquisar2" class="w3-btn w3-deep-orange w3-round w3-tiny" type="submit" name="btn_pesquisar2" value="Gerar">Consultar</button>
    			</div>
            </div>	    
        	
        	             
        </div> <!-- final div pesq satisfacao -->	
    
        <!-- DIV CONSULTA 24 - Dados de comparação de retenção -->		
    	<div  id="div_parametros_retencao_ura_c24" class="w3-container">
    		<div class="w3-left" style="margin-top: 8px; margin-bottom: 16px;"> <b>Parâmetros de Análise:</b> </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_1" name="chk_1" value = "1" checked>Total de Ligações &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_2" name="chk_2" value = "2" checked>Com Derivação / Sem Serviço &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_3" name="chk_3" value = "3" checked>Com Derivação / Com Serviço </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_4" name="chk_4" value = "4" checked>Sem Derivação / Com Serviço &nbsp &nbsp </div>
    		<div class="w3-left"> <input class="w3-margin-8" type="checkbox" id="chk_5" name="chk_5" value = "5" checked>Sem Derivação / Sem Serviço &nbsp &nbsp </div>		
    	</div>
	</form>
	<!-- FORMULÁRIO - FIM -->
	
	<!-- DIV LOCALIZA ATENDIMENTOS - div_localiza_atendimentos -->		
	<div id="div_localiza_atendimentos" class="w3-container">
	
		<!-- DIV NOME CLIENTE - div_nome_cliente -->
		<div id="div_nome_cliente" class="w3-left w3-margin-bottom w3-margin-left">	
			<b id="txt_nome_cliente">Nome:</b>
			<input id="nome_cliente" type='text' size='30' name="nome_cliente" value=''">
		</div>
		
		<!-- DIV CPF - div_cpf -->
		<div id="div_cpf" class="w3-left w3-margin-bottom w3-margin-left">	
			<b id="txt_cpf">CPF/CNPJ:</b>
			<input id="cpf" type='text' size='14' name="cpf" value=''" onkeypress='return SomenteNumero(event, this, 14)'>
		</div>
		
		<!-- DIV TELEFONE - div_telefone -->
		<div id="div_telefone" class="w3-left w3-margin-bottom w3-margin-left">	
			<b id="txt_telefone">Telefone:</b>
			<input id="telefone" type='text' size='11' name="telefone" value=''" onkeypress='return SomenteNumero(event, this, 11)'>
		</div>
		
		<!-- DIV BOTÃO ADICIONAR TELEFONE - div_btn_add_telefone -->		
		<div id="div_btn_add_telefone" class="w3-left" style="margin-left:4px;">
			<button id="btn_add_telefone" class="w3-btn w3-indigo w3-round w3-tiny" type="submit" name="btn_add_telefone" value="Gerar">+</button>
		</div>
		
		<!-- DIV CARTAO INICIO - div_cartao_inicio -->
		<div id="div_cartao_inicio" class="w3-left w3-margin-bottom w3-margin-left">	
			<b id="txt_cartao_inicio">Início Cartão:</b>
			<input id="cartao_inicio" type='text' size='4' name="cartao_inicio" value=''" placeholder="XXXX" onkeypress='return SomenteNumero(event, this, 4)'>
		</div>
		
		<!-- DIV CARTAO FIM - div_cartao_fim -->
		<div id="div_cartao_fim" class="w3-left w3-margin-bottom w3-margin-left">	
			<b id="txt_cartao_fim">Final Cartão:</b>
			<input id="cartao_fim" type='text' size='4' name="cartao_fim" value=''" placeholder="XXXX" onkeypress='return SomenteNumero(event, this, 4)'>
		</div>
		
		<!-- DIV BOTÃO ADICIONAR CARTÃO - div_btn_add_cartao -->		
		<div id="div_btn_add_cartao" class="w3-left" style="margin-left:4px;">
			<button id="btn_add_cartao" class="w3-btn w3-indigo w3-round w3-tiny" type="submit" name="btn_add_cartao" value="Gerar">+</button>
		</div>

	</div>
	
	
		
	<!-- DIV TEXTO DESCRIÇÃO - div_tex_detalhes -->
	<div id="div_tex_detalhes" class="w3-container w3-margin-left w3-margin-bottom">
		<i id="txt_detalhes"></i>
	</div>

</div>
<!-- DIV DO FORMULÁRIO - FIM -->

<hr>

<!-- OCULTAR CAMPOS DO FORMULÁRIO -->
<script>

	hideAll();

	//função que carrega os submotivos dinamicamente 
    $(function(){
        $('#cd_motivo').change(function()
        {
            if( $(this).val() ) 
            {
                $('#cd_submotivo').hide();
                $('.carregando').show();
                $.getJSON('sub_motivos.ajax.php?search=',{cd_motivo: $(this).val(), ajax: 'true'}, 
                function(j)
                {
                    var options = '<option value=""></option>'; 
                    for (var i = 0; i < j.length; i++) {
                        options += '<option value="' + j[i].cd_submotivo + '">' + j[i].cd_submotivo +' - '+ j[i].ds_submotivo + '</option>';
                    }   
                    $('#cd_submotivo').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#cd_submotivo').html('<option value="">– Escolha um Motivo –</option>');
            }
        });
    });
    

</script>

</body>
</html>