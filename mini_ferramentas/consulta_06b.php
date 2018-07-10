<?php
    $nome_relatorio = "tma_ns"; 
    $titulo = "TMA e Nível de Serviço"; 

    include "inicia_variaveis_grafico.php";
    //$dados_grafico = "['Data', 'NSA', 'Operadores', 'TMA']";       
    $dados_grafico = "['Data', 'NSA', 'Operadores']";
    $inicio = defineTime();

	
	echo '<div id="divtitulo" class="w3-margin w3-tiny w3-center">';
	echo "<b>$titulo</b>";
	echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto";
	echo "<br><br><b>Dias da Semana Selecionados:</b> $txt_dias_semana";
	echo "<br>";
	echo '</div>';
	
	echo '<div class="w3-border" style="padding:16px 16px;">';
	echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
	echo '<thead>
            <tr class="w3-indigo w3-tiny">';
            	echo "<td><b>DIA</b></td>";            	
            	echo "<td class='tooltip'><b>REFERÊNCIA*</b>
            	         <span class='tooltiptext'>Tempo de referência 45s (Normal) e 90s (DMM)</span>
            	      </td>";
            	echo "<td class='tooltip'><b>A*</b>
            	         <span class='tooltiptext'>Atendidas até 45s (Normal) ou 90s (DMM)</span>
            	      </td>";
                        	
            	echo "<td class='tooltip'><b>B*</b>
            	         <span class='tooltiptext'>Todas as Atendidas</span>
            	      </td>";
            
            	echo "<td class='tooltip'><b>C*</b>
            	         <span class='tooltiptext'>Abandonadas após 45s (Normal) ou 90s (DMM)</span>
            	      </td>";
            
            	echo "<td class='tooltip'><b>NSA* = A/(B+C)</b>
            	         <span class='tooltiptext'>Nível de Serviço Alcançado</span>
            	      </td>";
            
            	echo '<td><b>OPERADORES</b></td>';
            	echo '<td><b>TMA</b></td>';        	
        echo '</tr>
          </thead>
          <tbody>'; 
                
                $tempo_de_corte = intval($tempo_de_corte);
                
                $sql = " 
                            set nocount on; 
    
                            declare @T TABLE(dia date,
                            				 sdia_semana varchar(20), 
                            				 dia_semana int, 
                            				 tempo_referencia int, 
                            				 a int, 
                            				 b int, 
                            				 c int, 
                            				 nsa float,
                            				 qtde_operador int,
                            				 tma int
                            				); 
                            insert @T EXEC sp_CERATFO_radar_cartoes_query6b '$data_inicial_u 00:00:00', '$data_final_u 23:59:59', $tempo_de_corte
                            
                            select * from @T                        
                         ";
       // echo $sql;
        $query = $pdo->prepare($sql);
        $query->execute();
        
        for($i=0; $row = $query->fetch(); $i++){
            $var_graf = 0;
            $dia = $row['dia'];
            $dia = date("d/m/Y", strtotime($dia)); 
            $dia_semana = $row['dia_semana'];
            $tempo_referencia = $row['tempo_referencia'].'s';
            $a = $row['a'];
            $b = $row['b'];
            $c = $row['c'];
            $nsa = $row['nsa'];
            $nsa = ($nsa * 100.00);
            $nsa = number_format($nsa, 2, ',', '.');
            $sdia_semana = diaSemana($dia_semana);
            $qtde_operador = $row['qtde_operador'];
            $tma = $row['tma'];
            
            echo '<tr>';            
            echo "<td>$dia ( $sdia_semana )</td>";            
            echo "<td align='center'>$tempo_referencia</td>";
            echo "<td>$a</td>";
            echo "<td>$b</td>";
            echo "<td>$c</td>";
            echo "<td><a class='w3-text-indigo' title='Detalhar Nível de Serviço' href= \"lista_detalhe_nivel_servico.php?&data_inicial=$dia&tempo_corte=$tempo_de_corte\" target=\"_blank\">$nsa</a></td>";
            //echo "<td>$nsa</td>";
            echo "<td>$qtde_operador</td>";
            echo "<td>$tma</td>";
            echo '</tr>';     
            
            /*Dados do gráfico*/
            $incrementa_grafico = $incrementa_grafico.",['$dia ($sdia_semana)'"; // INCREMENTA OS DADOS DO GRÁFICO          
            $nsa = str_replace(",",".",$nsa);
            //$incrementa_grafico = $incrementa_grafico.",$nsa,$qtde_operador,$tma]";
            $incrementa_grafico = $incrementa_grafico.",$nsa,$qtde_operador]";
        }
        echo "</tbody>
                    </table>";
        echo "</div>";
        echo "</div>";
        echo "<br><br>";
        
        $parametros_adicionais = " pointSize: 2, 
         series: {
                    0: {targetAxisIndex: 0},
                    1: {targetAxisIndex: 1}
                  },
                  vAxes: {
                    // Adds titles to each axis.
                    0: {title: 'NSA'},
                    1: {title: 'Operadores'}
                  },
        ";
        include "imprime_grafico.php"; // IMPRIME O GRÁFICO
        $fim = defineTime();
        echo tempoDecorrido($inicio,$fim);
        
        include "desconecta.php";
?>
<script>  
	document.getElementById("divtitulo").appendChild(document.getElementById("tmp")); 
</script>