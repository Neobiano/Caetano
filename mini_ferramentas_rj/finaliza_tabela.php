<?php
// VERIFICA SE A CONSULTA RETORNOU RESULTADO
if ($qtd_linhas_consulta > 0){
	echo "<script>$('#tabela').show();</script>"; // MOSTRA A TABELA SE AO MENOS 1 LINHA NO RESULTADO DA CONSULTA
	
	$nome_arquivo = $nome_relatorio."_$data_inicial_arquivo"."_a_$data_final_arquivo"; // TRATAMENTO DO NOME DO ARQUIVO EXCEL	
	
	
	if($nao_gerar_excel != 1) echo "<div style='margin-right:-12px;'><a class='w3-right w3-padding-0 w3-margin-right w3-margin-bottom w3-tiny w3-text-indigo' style = 'font-family:verdana !important;font-size:11px !important;' id = '#meubtn' href= \"download_tabela.php?tabela=$tabela&nome_arquivo=$nome_arquivo\" target=\"_blank\"><i>Salvar Planilha</i><img src='file_indigo.png' style='padding-bottom:2px;'></a></div>"; // IMAGEM SALVAR PLANILHA
}
else echo "Consulta não retornou dados.";

// ENCERRA A TABELA E A DIV DA TABELA
$texto = "</table></div>";
echo incrementa_tabela($texto);
?>