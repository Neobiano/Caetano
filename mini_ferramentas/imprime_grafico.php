<div class="w3-border w3-margin w3-padding-bottom w3-card-4" style="margin-top:0; !important;">
<?php
$dados_grafico = $dados_grafico."$incrementa_grafico";
$max = $max * 1.2;
$min = $min * 0.8;
    if(!isset($tipo)) $tipo = 'LineChart';
    echo imprimeGraficoLinha($dados_grafico,$titulo,$largura,$altura,$max,$min,$tipo,$parametros_adicionais,$dadoscolunas);
?>
</div>