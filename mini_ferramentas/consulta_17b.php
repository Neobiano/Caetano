<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>

<script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>

</head>
<body>

<?php   
    $nome_relatorio = "pesquisa_de_satisfacao"; // NOME DO RELATÃâ€œRIO (UTILIZAR UNDERLINE, POIS Ãâ€° PARTE DO NOME DO ARQUIVO EXCEL)
    $titulo = "Pesquisa de Satisfação"; // MESMO NOME DO INDEX
    $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃÆ’O IMPRIMIR BOTÃÆ’O EXCEL
    include "inicia_variaveis_grafico.php";
    $inicio = defineTime();
    
    //IMPRIME TÍTULO DA CONSULTA
    echo '<div id="divtitulo" class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
    echo "<b>$titulo</b>";
    echo "<br><br><b>Obs:</b> À partir de 01/03/2017 a pesquisa de satisfação considera somente as perguntas e respostas 3 e 4 para realização dos calculos";
    echo "<br><br><b>Período de Consulta:</b> $data_inicial_texto à  $data_final_texto";
    echo '</div>';
    
    include "inicia_div_tabela_organizada.php"; // INICIA A <DIV> DA TABELA **
    include "inicia_tabela_organizada.php"; // INICIA A TABELA
    
    // IMPRIME COLUNAS DA TABELA - INÍCIO
    $texto = "<td><b>CÓDIGO &nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>FILA &nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>QTDE LIGAÇÕES &nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>PESQ. REALIZADAS &nbsp</b></td>";
    echo incrementa_tabela($texto);   
   
    $texto = "<td><b>REALIZ. (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>SATISFEITO &nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>SATISFEITO (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>INSATISFEITO &nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>INSATISFEITO (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>INDIFERENTE &nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "<td><b>INDIFERENTE (%)&nbsp</b></td>";
    echo incrementa_tabela($texto);
    
    $texto = "</tr></thead><tbody>";
    echo incrementa_tabela($texto);
    // IMPRIME COLUNAS DA TABELA - FIM
    
    echo "<script>$('#tabela').hide();</script>"; // ESCONDE A TABELA
    
  
    $j = ($data_inicial >='03/01/2018') ? 3 : 1;
	
	$sql =  "select cod_fila, count(callid) qtde_ligacoes,
                	/*Pequisas realizadas*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
                				and t.cod_fila = a.cod_fila
                			) as b
                		where (b.perg3 > 0 or b.perg4 >0 )	
                	) qtde_pesq,
                    
                    /*Pequisas realizadas TOTAL*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999'  				
                			) as b
                		where (b.perg3 > 0 or b.perg4 >0)	
                	) qtde_pesq_geral,
                	
                    /*Qtde Insatisfeito*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
                				and t.cod_fila = a.cod_fila
                			) as b
                		where (   b.perg3 = 3 and b.perg4 =3  )	
                	) qtde_insatisfeito,
                	/*Qtde Insatisfeito*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
                				and t.cod_fila = a.cod_fila
                			) as b
                		where (b.perg3 = 1 and b.perg4 =1)	
                	) qtde_satisfeito,
	
                	/*Qtde Indiferente*/	
                	(
                		select count(distinct callid) 
                		from 
                			(
                				select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao t
                				where data_hora between '$data_inicial' and '$data_final 23:59:59.999' 
                				and t.cod_fila = a.cod_fila
                			) as b
                		where (b.perg3 = 2 and b.perg4 =2)	
                	) qtde_indiferente
                	
                from 
                		(
                			select distinct callid, data_hora, cod_fila, perg1, perg2, perg3, perg4, perg5 from tb_pesq_satisfacao 
                			where data_hora between '$data_inicial' and '$data_final 23:59:59.999'
                			
                		) as  a
            group by cod_fila
            order by cod_fila";
	
	//echo $sql;
	$query = $pdo->prepare($sql);
	$query->execute(); // EXECUTA A CONSULTA
	
	$soma_qtde_insatisfeito = 0;
	$soma_qtde_ligacoes = 0;
	$soma_qtde_indiferente = 0;
	$soma_qtde_satisfeito = 0;
	
	// IMPRIME O RESULTADO DA CONSULTA - INÍCIO
	for($i=0; $row = $query->fetch(); $i++)
	{
	    $qtde_pesq_geral = utf8_encode($row['qtde_pesq_geral']);
	    $qtde_pesq = utf8_encode($row['qtde_pesq']);
	    $qtde_ligacoes = utf8_encode($row['qtde_ligacoes']);
	    $qtde_satisfeito = utf8_encode($row['qtde_satisfeito']);
	    $qtde_insatisfeito = utf8_encode($row['qtde_insatisfeito']);
	    $qtde_indiferente = utf8_encode($row['qtde_indiferente']);
	    
	    if ($qtde_pesq > 0)
	    { 	        
    	    $texto = '<tr>';
    	    echo incrementa_tabela($texto);
    	    
    	    $vdn_atual = utf8_encode($row['cod_fila']);    
    	    $vdn_nome = "vdn_$vdn_atual";
    	    $desc_fila = $$vdn_nome;
    	    if(isset($desc_cod[$desc_fila]))
    	       $cod_fila = $desc_cod[$desc_fila];
    	    else
    	       $cod_fila = $vdn_atual;
    	        
    	    
    	    $texto = "<td>$cod_fila</td>";
    	    echo incrementa_tabela($texto);
    	       	       	    
    	    $texto = "<td>$desc_fila</td>";
    	    echo incrementa_tabela($texto);
    	        	    
    	    $texto = "<td>$qtde_ligacoes</td>";
    	    $soma_qtde_ligacoes += $qtde_ligacoes;
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$qtde_pesq</td>";
    	    echo incrementa_tabela($texto);
    	       
    	    $pct_pesq_realizada = (($qtde_pesq / $qtde_ligacoes) * 100.00);
    	    $imprime = number_format($pct_pesq_realizada, 2, ',', '.');
    	    $texto = "<td><b>$imprime%</b></td>";
    	    echo incrementa_tabela($texto);
    	                	       	           	        	        	       	        	        	    
    	    $texto = "<td>$qtde_satisfeito</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $pct_satisfeito = (($qtde_satisfeito/$qtde_pesq) * 100.00);
    	    $imprime = number_format($pct_satisfeito, 2, ',', '.');
    	    $texto = "<td><b>$imprime%</b></td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$qtde_insatisfeito</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $pct_isatisfeito = (($qtde_insatisfeito/$qtde_pesq) * 100.00);
    	    $imprime = number_format($pct_isatisfeito, 2, ',', '.');
    	    $texto = "<td><b>$imprime%</b></td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = "<td>$qtde_indiferente</td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $pct_indiferente = (($qtde_indiferente/$qtde_pesq) * 100.00);
    	    $imprime = number_format($pct_indiferente, 2, ',', '.');
    	    $texto = "<td><b>$imprime%</b></td>";
    	    echo incrementa_tabela($texto);
    	    
    	    $texto = '</tr>';
    	    echo incrementa_tabela($texto);
    	    
    	    $soma_qtde_insatisfeito += $qtde_insatisfeito;
    	    $soma_qtde_satisfeito += $qtde_satisfeito;
    	    $soma_qtde_indiferente += $qtde_indiferente;
    	    $qtd_linhas_consulta++;
	    }
	}
	
	echo "</tbody><tr class='w3-indigo'>";
	$tabela = $tabela."<tr>";

	$texto = "<td><b>TOTALIZADOR</b></td>";
	echo incrementa_tabela($texto);
	
	$texto = "<td><b></b></td>";
	echo incrementa_tabela($texto);
	//-----------QTDE LIGAÇÕES-------------//
	$imprime = number_format($soma_qtde_ligacoes, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE PESQUISADA-------------//
	$imprime = number_format($qtde_pesq_geral, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------PCT(%) PESQUISADA-------------//
	$pct_realizado_geral = (($qtde_pesq_geral/$soma_qtde_ligacoes) * 100.00);	
	$imprime = number_format($pct_realizado_geral, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE SATISFEITO-------------//
	$imprime = number_format($soma_qtde_satisfeito, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------PCT(%) SATISFEITO-------------//
	$pct_satisfeito_geral = (($soma_qtde_satisfeito/$qtde_pesq_geral) * 100.00);	
	$imprime = number_format($pct_satisfeito_geral, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE  INSATISFEITO-------------//
	$imprime = number_format($soma_qtde_insatisfeito, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------PCT  INSATISFEITO-------------//
	$pct_isatisfeito_geral = (($soma_qtde_insatisfeito/$qtde_pesq_geral) * 100.00);	
	$imprime = number_format($pct_isatisfeito_geral, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------QTDE  INDIFERENTE-------------//
	$imprime = number_format($soma_qtde_indiferente, 0, ',', '.');
	$texto = "<td><b>$imprime</b></td>";
	echo incrementa_tabela($texto);
	
	//-----------PCT  INDIFERENTE-------------//
	$pct_indiferente_geral = (($soma_qtde_indiferente/$qtde_pesq_geral) * 100.00);
	$imprime = number_format($pct_indiferente_geral, 2, ',', '.');
	$texto = "<td><b>$imprime%</b></td>";
	echo incrementa_tabela($texto);

    $texto = '</tr>';
    echo incrementa_tabela($texto);
       
    include "finaliza_tabela.php"; // FINALIZA A TABELA
    //include"imprime_grafico.php"; // IMPRIME O GRÁFICO
    $fim = defineTime();
    echo tempoDecorrido($inicio,$fim);
?>

</body>
</html>

<script>  
document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 
$('#tabela').DataTable( {
	 "columnDefs": [ {
     "targets": [ 0 ],
     "orderable": false
   } ]
} );
</script>