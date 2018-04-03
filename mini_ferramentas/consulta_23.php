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
        "order": [[ 1, "desc" ]]
    } );
} );
</script>
</head>
<body>

<?php
$nome_relatorio = "reicindencia_insatisfacao"; // NOME DO RELATàƒâ€œRIO (UTILIZAR UNDERLINE, POIS àƒâ€° PARTE DO NOME DO ARQUIVO EXCEL)
$titulo = "Relatório - Reincidência de Insatisfação "; // MESMO NOME DO INDEX
$nao_gerar_excel = 1; // DEFINIR 1 PARA NàƒO IMPRIMIR BOTàƒO EXCEL
switch ($select_origem_reicidencia) 
{			
	case '03':
		$s_select_origem_reicidencia = 'Tefone Originador';
		$sql_tipo_dado = " and t2.cod_dado = '3' ";
		break;
	
	case '02':
		$s_select_origem_reicidencia = 'CPF Demandante';
		$sql_tipo_dado = " and t2.cod_dado = '2' ";
		break;
		
	case '01':
		$s_select_origem_reicidencia = 'Cartão Demandante';
		$sql_tipo_dado = " and t2.cod_dado = '1' ";
		break;	
}

    if ($pesq_satisf_perg1 > 0)
    {
        $swhere .= " and t.perg1 = '$pesq_satisf_perg1'";
        switch ($pesq_satisf_perg1)
        {
            case 1:
                $criterios .= "  </br> <b>Perg. 1:</b> Satisfeito";
                break;
                
            case 2:
                $criterios .= "  </br> <b>Perg. 1:</b> Indiferente";
                break;
            case 3:
                $criterios .= "  </br> <b>Perg. 1:</b> Insatisfeito";
                break;
        }
        
    }
    else
        $criterios .= " </br>  <b>Perg. 1:</b> Todas";
        
    if ($pesq_satisf_perg2 > 0)
    {
        $swhere .= " and t.perg2 = '$pesq_satisf_perg2'";
        switch ($pesq_satisf_perg2)
        {
            case 1:
                $criterios .= "  <b>Perg. 2:</b> Satisfeito";
                break;
                
            case 2:
                $criterios .= "  <b>Perg. 2:</b> Indiferente";
                break;
            case 3:
                $criterios .= "  <b>Perg. 2:</b> Insatisfeito";
                break;
        }
    }
    else
        $criterios .= "  <b>Perg. 2:</b> Todas";
        
        if ($pesq_satisf_perg3 > 0)
        {
            $swhere .= " and t.perg3 = '$pesq_satisf_perg3'";
            
            switch ($pesq_satisf_perg3)
            {
                case 1:
                    $criterios .= "  <b>Perg. 3:</b> Satisfeito";
                    break;
                    
                case 2:
                    $criterios .= "  <b>Perg. 3:</b> Indiferente";
                    break;
                case 3:
                    $criterios .= "  <b>Perg. 3:</b> Insatisfeito";
                    break;
            }
        }
        else
            $criterios .= "  <b>Perg. 3:</b> Todas";
            
            if ($pesq_satisf_perg4 > 0)
            {
                $swhere .= " and t.perg4 = '$pesq_satisf_perg4'";
                switch ($pesq_satisf_perg4)
                {
                    case 1:
                        $criterios .= "  <b>Perg. 4:</b> Sim";
                        break;
                        
                    case 2:
                        $criterios .= "  <b>Perg. 4:</b> Parcialmente";
                        break;
                    case 3:
                        $criterios .= "  <b>Perg. 4:</b> Não";
                        break;
                }
            }
            else
                $criterios .= "  <b>Perg. 4:</b> Todas";
	
//IMPRIME Tà�TULO DA CONSULTA
echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
echo "<b>$titulo por $s_select_origem_reicidencia</b>";
echo "<br><br><b>Perìodo de Consulta:</b> $data_inicial_texto à  $data_final_texto";
echo "<br><br><b><i>Critérios de Filtro:</i></b> $criterios";
echo '</div>';


echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important; ">';

echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";

echo "<thead><tr class='w3-indigo'>";

	echo "<td><b>".$s_select_origem_reicidencia."</b></td>";
	echo "<td><b>Qtde de Ligaçções</b></td>";
	
	echo "</tr></thead><tdoby>";
	
	

	$sql = "select t2.valor_dado , count(distinct t.callid) qtde_ligacoes from tb_pesq_satisfacao t
					inner join tb_dados_cadastrais t2 on (t2.callid = t.callid)
					where 
					t.data_hora between '$data_inicial' and '$data_final 23:59:59.999'
					and t2.data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                    
					$sql_tipo_dado				 
					$swhere
					group by t2.valor_dado
					order by count(distinct t.callid) desc";
					
	//echo $sql;
	$query = $pdo->prepare($sql);
	$query->execute(); // EXECUTA A CONSULTA

	for($i=0; $row = $query->fetch(); $i++){

		$dado = utf8_encode($row['valor_dado']);
		$qtde_ligacoes = utf8_encode($row['qtde_ligacoes']);
		
		echo "<tr>";	
			echo "<td><a class='w3-text-indigo' title='Rastrear Atendimentos' href= \"lista_atendimentos_reicindencia_isatisfacao.php?perg4=$pesq_satisf_perg4&perg3=$pesq_satisf_perg3&perg2=$pesq_satisf_perg2&perg1=$pesq_satisf_perg1&valor_dado=$dado&select_origem_reicidencia=$select_origem_reicidencia&data_inicial=$data_inicial&data_final=$data_final\" target=\"_blank\">$dado</a></td>";			
			echo "<td>$qtde_ligacoes</td>";
		echo "</tr>";
	}	


	echo "</tbody></table></div>";
?>


</body>
</html>

