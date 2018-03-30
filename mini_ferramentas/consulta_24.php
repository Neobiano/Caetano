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

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready( function () {
    $('#tabela').DataTable( {
        "order": [[ 1, "asc" ]]
    } );
} );
</script>
</head>
<body>

<?php
//include_once "funcoes.php";
$inicio = defineTime();

$sqlnotlike = " and (cod_evento not like '%020%')
                  and (cod_evento not like '%031%')
                  and (cod_evento not like '%037%')
                  and (cod_evento not like '%039%')
                  and (cod_evento not like '%042%')
                  and (cod_evento not like '%045%')
                  and (cod_evento not like '%047%')
                  and (cod_evento not like '%050%')
                  and (cod_evento not like '%051%')
                  and (cod_evento not like '%061%')
                  and (cod_evento not like '%062%')
                  and (cod_evento not like '%076%')
                  and (cod_evento not like '%078%')
                  and (cod_evento not like '%136%')
                  and (cod_evento not like '%137%')
                  and (cod_evento not like '%138%')
                  and (cod_evento not like '%139%')
                  and (cod_evento not like '%140%') 
              ";

$sqllike = " and 
                 (
                     (cod_evento like '%020%') 
                     or (cod_evento like '%031%') 
                     or (cod_evento like '%037%') 
                     or (cod_evento like '%039%') 
                     or (cod_evento like '%042%') 
                     or (cod_evento like '%045%') 
                     or (cod_evento like '%047%') 
                     or (cod_evento like '%050%') 
                     or (cod_evento like '%051%') 
                     or (cod_evento like '%061%') 
                     or (cod_evento like '%062%') 
                     or (cod_evento like '%076%') 
                     or (cod_evento like '%078%') 
                     or (cod_evento like '%136%') 
                     or (cod_evento like '%137%') 
                     or (cod_evento like '%138%') 
                     or (cod_evento like '%139%') 
                     or (cod_evento like '%140%') 
                 ) ";     

$nome_relatorio = "analise_retencao_desconexao"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Relatório - URA - Análise de Retenção/Desconexão "; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL

//IMPRIME TÍTULO DA CONSULTA
echo '<div id="divtitulo" class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
echo "<b>$titulo</b>";// por $s_select_origem_reicidencia
echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à $data_final_texto";
echo '</div>';
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important; ">';

echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";

echo "<thead><tr class='w3-indigo'>";        
	echo "<td><b>Data</b></td>";
	echo "<td><b>Total de Ligações</b></td>";
	echo "<td><b>Com Derivação / Sem Serviço</b></td>";
	echo "<td><b>Com Derivação / Com Serviço</b></td>";
	echo "<td><b>Sem Derivação / Com Serviço</b></td>";
	echo "<td><b>Sem Derivação / Sem Serviço</b></td>";
	echo "<td><b>Sem Derivação/Sem Serviço (%)</b></td>";
	
	echo "</tr></thead><tbody>";

	$sql1 = " select a.* from (

						(
							select count(distinct callid) qtde, cast(data_hora as date) dia, 'TOTAL_CHAMADAS' tipo_reg ,  '0' indice
							from tb_eventos_ura
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999'   
							group by cast(data_hora as date)  
						)  
						UNION ALL
						
						(
							select count(distinct callid) qtde, cast(data_hora as date) dia, 'COM_DERIV_SEM_SERV' tipo_reg , '1' indice from tb_eventos_ura 
							where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
							and callid in (select callid from tb_eventos_dac where data_hora between '$data_inicial' and '$data_final 23:59:59.999' )
							".$sqlnotlike."	
							group by cast(data_hora as date) 
						) 
						UNION ALL
						
						(
							 select count(distinct callid) qtde, cast(data_hora as date) dia, 'C_DERIV_C_SERV' tipo_reg , '2' indice   from tb_eventos_ura
							 where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
							 and callid in (select callid from tb_eventos_dac where data_hora between '$data_inicial' and '$data_final 23:59:59.999' )
							 ".$sqllike."
							 group by cast(data_hora as date) 
						) 
						UNION ALL
						
						(
							 select count(distinct callid) qtde, cast(data_hora as date) dia, 'S_DERIV_C_SERV' tipo_reg , '3' indice   from tb_eventos_ura
							 where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
							 and callid not in (select callid from tb_eventos_dac where data_hora between '$data_inicial' and '$data_final 23:59:59.999' )
							 ".$sqllike."
							 group by cast(data_hora as date) 
						) 
						UNION ALL
						
						(
							 select count(distinct callid) qtde, cast(data_hora as date) dia, 'S_DERIV_S_SERV' tipo_reg , '4' indice   from tb_eventos_ura
							 where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
							 and callid not in (select callid from tb_eventos_dac where data_hora between '$data_inicial' and '$data_final 23:59:59.999' )
							  ".$sqlnotlike."
							 group by cast(data_hora as date) 
						) 
                   ) a
                   order by a.dia, a.indice";
					
	//echo $sql1;
	$query = $pdo->prepare($sql1);
	$query->execute(); // EXECUTA A CONSULTA
    $linha = 0;
    $dia = null;
    $tabela = array
                (
                    //array("DIA","TOTAL_CHAMADAS","COM_DERIV_SEM_SERV","C_DERIV_C_SERV", "S_DERIV_C_SERV","S_DERIV_S_SERV")                    
                );
    
    //registros são trazidos na vertical, procedimento usado somente pra alinhar por 'linha' na horizontal
    for($i=0; $row = $query->fetch(); $i++)
    {
        if ($dia != $row['dia'])
        {	                           
           $linha = $linha + 1;                
           $dia = $row['dia'];    
           $tabela[$linha][0] = $dia;
        }
        
        switch ($row['indice']) 
        {
            case '0':
                $tabela[$linha][1] = $row['qtde'];
            break;
            
            case '1':
                $tabela[$linha][2] = $row['qtde'];
                break;
                
            case '2':
                $tabela[$linha][3] = $row['qtde'];
                break;
            
            case '3':
                $tabela[$linha][4] = $row['qtde'];
                break;
            
            case '4':
                $tabela[$linha][5] = $row['qtde'];
                break;
            case '5':
                $tabela[$linha][6] = $row['qtde'];
                break;
        }
        
    }
    
    foreach ($tabela as $row) 
    {
        
        $pct = number_format(($row[5]/$row[1] * 100), 2, ',', '.');               
        
        echo "<tr>";
        echo "<td>$row[0]</td>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        echo "<td>$row[3]</td>";
        echo "<td>$row[4]</td>";
        echo "<td>$row[5]</td>";
        //echo "<td><a class='w3-text-indigo' title='Detalhas Eventos' href= \"lista_atendimentos_reicindencia_isatisfacao.php?perg4=$pesq_satisf_perg4&perg3=$pesq_satisf_perg3&perg2=$pesq_satisf_perg2&perg1=$pesq_satisf_perg1&valor_dado=$dado&select_origem_reicidencia=$select_origem_reicidencia&data_inicial=$data_inicial&data_final=$data_final\" target=\"_blank\">$dado</a></td>";
        echo "<td><a class='w3-text-indigo' title='Detalhar Eventos' href= \"lista_eventos_desconexao_c24.php?valor_dado=$row[0]&qtde_total=$row[5]\" target=\"_blank\">$pct</a></td>";
        echo "</tr>";                              
    }
	
	echo "</tbody></table></div>";
	$fim = defineTime();
	echo tempoDecorrido($inicio,$fim);
?>
</body>
</html>

<script>  
document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 
</script>

