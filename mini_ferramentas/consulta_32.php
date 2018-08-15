<!DOCTYPE html>
<html>
<head>
	 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/radar.css">
    
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>    
    <script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>    
    <link rel="stylesheet" type="text/css" href="css/dataTables.css">  
    <script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>
    

    <script>
        $(document).ready( function () {
            $('#tabela').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
</head>

<body>

       <?php
        $inicio = defineTime();               
        
        $sFiltro = '';
        $filtro = '';
        $stma = '';
        $sligacoes = '';
        $ssac = '';
        $stma1 = '';
        $sligacoes1 = '';
        $ssac1 = '';
        if (($sac_operador_32) > 0)
        {
            $sFiltro = " Operador: $sac_operador_32, ";
            $filtro = " and t.id_operador = $sac_operador_32 ";                       
        }
        
       if (($sac_fila_32) > 0)
       {    
           $sFiltro = ("$sFiltro  Fila: $sac_fila_32, ");
           $filtro = "$filtro and t.cod_fila = $sac_fila_32 ";                      
       }
       
       if (($cd_motivo_32) > 0)
       {
           $sFiltro = ("$sFiltro  Motivo: $cd_motivo_32, ");
           $filtro = "$filtro and l.cd_motivo = $cd_motivo_32 ";
       }
       
       if (($cd_submotivo_32) > 0)
       {
           $sFiltro = ("$sFiltro  Motivo: $cd_submotivo_32, ");
           $filtro = "$filtro and l.cd_submotivo = $cd_submotivo_32 ";
       }
        
       $sqla = "            set nocount on;
       
                            select cast(callid as varchar(30)) callid,
                    	    data_hora,
                    	    cd_motivo,
                    	    cd_submotivo,
                    	    ds_motivo,
                    	    cast(ds_submotivo as varchar(50)) ds_submotivo,
                    	    login_front
                            into #temp_log
                            from tb_log_categorizacao t1
                            where t1.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                            
                            CREATE  INDEX Idx1 ON [#temp_log] (data_hora);
                            CREATE CLUSTERED INDEX Idx2 ON [#temp_log] (callid);
                            CREATE INDEX Idx3 ON [#temp_log] (ds_submotivo);
                            
                            select callid, max(data_hora) data_hora
                            into #temp_ultimo
                            from tb_eventos_dac t
                            where t.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                            and t.tempo_atend > 10
                            and coalesce(t.desc_operador,'NULL') <> 'NULL'
                            group by callid 

                            select * 
                        	into #temp_dac 
                        	from tb_eventos_dac t1
                        	where t1.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999' 
                        
                        	CREATE  INDEX Idx4 ON [#temp_dac] (data_hora);
                        	CREATE CLUSTERED INDEX Idx5 ON [#temp_dac] (callid);";
       
        switch ($select_tipo_32) 
        {
            case 0: //POR OPERADOR
                
                if (($sac_fila_32) > 0)
                { 
                    $stma = " and y.cod_fila = $sac_fila_32 ";
                    $sligacoes = " and x.cod_fila = $sac_fila_32 ";
                    $ssac = " and z.cod_fila = $sac_fila_32 ";
                }
                
                if ($cd_motivo_32 > 0)
                {
                    $sligacoes = " $sligacoes and lx.cd_motivo = $cd_motivo_32 ";
                    $ssac = " $ssac and lz.cd_motivo = $cd_motivo_32 ";
                }
                
                if ($cd_submotivo_32 > 0)
                {
                    $sligacoes = " $sligacoes and lx.cd_submotivo = $cd_submotivo_32 ";
                    $ssac = " $ssac and lz.cd_submotivo = $cd_submotivo_32 ";
                }
                
                $sFiltro = ("Agrupado: Por Operador, $sFiltro ");
                                
                $shead =' <td><b>Operador</b></td> <td><b>TMA</b></td> <td><b>Qtde Ligações</b></td> <td><b>Qtde SAC</b></td> <td><b>(%)</b></td>';                 
                
               
                $sqlb = " 
                            select t.id_operador, coalesce(t.desc_operador,f.nome) desc_operador, 
                                                           ( select AVG(y.tempo_atend)
                                                              from tb_eventos_dac y                                                                   
                                                              where y.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                                                              and y.tempo_atend > 10
                                                              and y.id_operador = t.id_operador
                                                              $stma
                                                              and coalesce(y.desc_operador,'NULL') <> 'NULL'
                                                              ) TMA,
                                      (  select count(distinct x.callid)
                                         from tb_eventos_dac x      
                                         left join #temp_log lx on (x.callid = lx.callid)                                  
                                         where x.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                                         and x.tempo_atend > 10
                                         and x.id_operador = t.id_operador
                                         $sligacoes
                                         and coalesce(x.desc_operador,'NULL') <> 'NULL'
                                      ) ligacoes,
                                      (  select count(distinct z.callid)
                                         from tb_eventos_dac z
                                         left join #temp_log lz on (z.callid = lz.callid) 
                                         inner join #temp_ultimo t2 on (t2.callid = z.callid and z.data_hora = t2.data_hora)
                                         where z.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                                         and z.tempo_atend > 10
                                         and z.id_operador = t.id_operador
                                         $ssac
                                         and coalesce(z.desc_operador,'NULL') <> 'NULL'
                                         and z.callid in (select callid from tb_eventos_sac s where s.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999')
                                      ) qtde_sac
                                      
                            from tb_eventos_dac t
                            left join tb_colaboradores_indra f on (t.id_operador = f.login_dac)                            
                            left join #temp_log l on (l.callid = t.callid) 
                            where t.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                            and t.tempo_atend > 10
                            $filtro
                            and coalesce(t.desc_operador,'NULL') <> 'NULL'
                            group by t.id_operador, coalesce(t.desc_operador,f.nome)
                            order by t.id_operador, coalesce(t.desc_operador,f.nome) ";
                
                break;
            case 1: //POR FILA
                $sFiltro = ("Agrupado: Por Fila, $sFiltro ");   
                if (($sac_operador_32) > 0)
                { 
                    $stma = " and y.id_operador = $sac_operador_32 ";
                    $sligacoes = " and x.id_operador = $sac_operador_32 ";
                    $ssac = " and z.id_operador = $sac_operador_32 ";
                }
                
                if ($cd_motivo_32 > 0)
                {
                    $sligacoes = " $sligacoes and lx.cd_motivo = $cd_motivo_32 ";
                    $ssac = " $ssac and lz.cd_motivo = $cd_motivo_32 ";
                }
                
                if ($cd_submotivo_32 > 0)
                {
                    $sligacoes = " $sligacoes and lx.cd_submotivo = $cd_submotivo_32 ";
                    $ssac = " $ssac and lz.cd_submotivo = $cd_submotivo_32 ";
                }
            
                $shead =' <td><b>Fila</b></td> <td><b>TMA</b></td> <td><b>Qtde Ligações</b></td> <td><b>Qtde SAC</b></td> <td><b>(%)</b></td>';                      
                
                $sqlb = "                                                                                       
                            select t.cod_fila, f.desc_fila, ( select AVG(y.tempo_atend) 
                                                              from tb_eventos_dac y                                 
                                                              where y.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                                                              and y.tempo_atend > 10
                                                              and y.cod_fila = t.cod_fila
                                                              $stma
                                                              and coalesce(y.desc_operador,'NULL') <> 'NULL'
                                                              ) TMA,
                                      (  select count(distinct x.callid) 
                                         from tb_eventos_dac x   
                                         left join tb_colaboradores_indra i on (i.login_dac = x.id_operador)         
                                         left join #temp_log lx on (lx.callid = x.callid and lx.login_front = i.matricula)                         
                                         where x.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                                         and x.tempo_atend > 10
                                         and x.cod_fila = t.cod_fila
                                         $sligacoes
                                         and coalesce(x.desc_operador,'NULL') <> 'NULL'
                                      ) ligacoes,
                                      (  select count(distinct z.callid) 
                                         from tb_eventos_dac z  
                                         inner join #temp_ultimo t2 on (t2.callid = z.callid and z.data_hora = t2.data_hora)
                                         left join tb_colaboradores_indra i on (i.login_dac = z.id_operador)
                                         left join #temp_log lz on (lz.callid = z.callid  and lz.login_front = i.matricula)                               
                                         where z.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                                         and z.tempo_atend > 10
                                         and z.cod_fila = t.cod_fila
                                         $ssac
                                         and coalesce(z.desc_operador,'NULL') <> 'NULL'
                                         and z.callid in (select callid from tb_eventos_sac s where s.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999')
                                      ) qtde_sac          
                            
                            from tb_eventos_dac t 
                            left join tb_filas f on (t.cod_fila = f.cod_fila)
                            left join #temp_log l on (l.callid = t.callid)                          
                            where t.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'
                            and t.tempo_atend > 10
                            $filtro
                            and coalesce(t.desc_operador,'NULL') <> 'NULL'
                            group by t.cod_fila, f.desc_fila
                            order by t.cod_fila, f.desc_fila ";
                break;
            case 2:
                $sFiltro = ("Agrupado: Por Motivo/SubMotivo, $sFiltro ");
                if (($sac_operador_32) > 0)
                {
                    //$stma = " and y.id_operador = $sac_operador_32 ";
                    $sligacoes = " and x.id_operador = $sac_operador_32 ";
                    $ssac = " and z.id_operador = $sac_operador_32 ";
                }
                
                if (($sac_fila_32) > 0)
                {
                    //$stma1 = " and y.cod_fila = $sac_fila_32 ";
                    $sligacoes = " $sligacoes and x.cod_fila = $sac_fila_32 ";
                    $ssac = " $ssac and z.cod_fila = $sac_fila_32 ";
                }
                
                if ($cd_motivo_32 > 0)
                {
                    $sligacoes = " $sligacoes and lx.cd_motivo = $cd_motivo_32 ";
                    $ssac = " $ssac and lz.cd_motivo = $cd_motivo_32 ";
                }
                
                if ($cd_submotivo_32 > 0)
                {
                    $sligacoes = " $sligacoes and lx.cd_submotivo = $cd_submotivo_32 ";
                    $ssac = " $ssac and lz.cd_submotivo = $cd_submotivo_32 ";
                }
               
                
                $shead =' <td><b>Motivo</b><td><b>Submotivo</b></td> <td><b>Qtde Ligações</b></td> <td><b>Qtde SAC</b></td> <td><b>(%)</b></td>';
                
                $sqlb = "    
                                                                                                                                                  
                            select l.cd_motivo, l.ds_motivo,  l.cd_submotivo, l.ds_submotivo,
                                        (  select count(distinct x.callid)
                                            from #temp_dac x
                        					inner join #temp_log lx on (x.callid = lx.callid)
                                            where x.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999' 
                                            and x.tempo_atend > 10	
                                             $sligacoes                                    
                                            and coalesce(x.desc_operador,'NULL') <> 'NULL'
                        					and lx.cd_submotivo = l.cd_submotivo 
                                        ) ligacoes,
                                        (  select count(distinct z.callid)
                                            from #temp_dac z
                                            inner join #temp_ultimo t2 on (t2.callid = z.callid and z.data_hora = t2.data_hora)
                        					inner join #temp_log lz on (z.callid = lz.callid)
                                            where z.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999' 
                                            and z.tempo_atend > 10
                                            and lz.cd_submotivo = l.cd_submotivo               
                                            and coalesce(z.desc_operador,'NULL') <> 'NULL'
                                            $ssac                                            
                                            and z.callid in (select callid from tb_eventos_sac s where s.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999' )
                                        ) qtde_sac
                                                              
                            from #temp_dac t
                            inner join #temp_log l on (t.callid = l.callid)                            
                            where t.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999' 
                            and t.tempo_atend > 10
                            $filtro
                            and coalesce(t.desc_operador,'NULL') <> 'NULL'
                            group by l.ds_motivo, l.ds_submotivo, l.cd_motivo, l.cd_submotivo
                            order by l.ds_motivo, l.ds_submotivo ";
                break;            
        }

                                                     
        $nome_relatorio = "atendimentos_sac"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Atendimentos - SAC "; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
                	                                                  
        echo '<div class="w3-margin w3-tiny w3-center">'; 
        echo '<div id="divtitulo" class="w3-margin w3-tiny w3-center">';
            echo "<b>$titulo</b>";
            echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto ";
            echo "<br><b><i>Filtros:</i></b> $sFiltro ";            
            echo "<br>";
        echo "</div>";            
        
            echo '<div class="w3-border" style="padding:16px 16px;">';
                    
                echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
                
                    echo '<thead>
                                <tr class="w3-indigo w3-tiny"> ';
                                   echo $shead;                                                                
                   echo '       </tr>
                          </thead>
                          <tbody>';
                                                                                                                                                                                                                 
                                $dadosgrafico1 = '';
                                $dadosgrafico2 = '';
                                $sql = $sqla.' '.$sqlb;
                                //echo $sql;                                
                                $query = $pdo->prepare($sql);
                                $query->execute();                                
                                for($i=0; $row = $query->fetch(); $i++)
                                {
                                    echo '<tr>';
                                   
                                    $tma = utf8_encode($row['TMA']);
                                    $ligacoes = utf8_encode($row['ligacoes']);
                                    $qtde_sac = utf8_encode($row['qtde_sac']);
                                    if ($ligacoes > 0)
                                       $pct_sac = ($qtde_sac / $ligacoes) * 100;
                                    else
                                       $pct_sac = 0;
                                    
                                    $pct_sac = number_format($pct_sac, 2, ',', '.');
                                                                                                           
                                    switch ($select_tipo_32) 
                                    {
                                        
                                        case 0:
                                            if ($sac_fila_32 > 0)
                                                $cod_fila = $sac_fila_32;
                                            else
                                                $cod_fila = 0;
                                            
                                            $cod_operador = utf8_encode($row['id_operador']);
                                            $desc_operador = utf8_encode($row['desc_operador']);
                                            
                                            echo "<td>$cod_operador ($desc_operador)</td>";                                           
                                            echo "<td>$tma</td>";
                                                                                               
                                            if ($ligacoes > 0)
                                               $texto_ligacoes =  "<td><b><a class='w3-text-indigo' title='Rastrear Atendimentos' href= \"lista_atendimentos_c32.php?pGrupo=LIGACOES&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pMotivo=$cd_motivo_32&pSubmotivo=$cd_submotivo_32\" target=\"_blank\">$ligacoes</a></b></td>";
                                            else
                                               $texto_ligacoes = "<td>$qtde_sac</td>";
                                                    
                                            if ($qtde_sac > 0)
                                               $texto_sac =  "<td><b><a class='w3-text-indigo' title='Rastrear Atendimentos' href= \"lista_atendimentos_c32.php?pGrupo=SAC&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pMotivo=$cd_motivo_32&pSubmotivo=$cd_submotivo_32\" target=\"_blank\">$qtde_sac</a></b></td>";
                                            else
                                               $texto_sac = "<td>$qtde_sac</td>";
                                           
                                            break;
                                        case 1:
                                           
                                            
                                            if ($sac_operador_32 > 0)
                                                $cod_operador = $sac_operador_32;
                                            else
                                                $cod_operador = 0;
                                                                                            
                                    
                                            $cod_fila = utf8_encode($row['cod_fila']);
                                            $desc_fila = utf8_encode($row['desc_fila']);
                                           
                                                                                       
                                            echo "<td>$cod_fila ($desc_fila)</td>";
                                            echo "<td>$tma</td>";
                                                                                      
                                            
                                            if ($ligacoes > 0)
                                                $texto_ligacoes =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c32.php?pGrupo=LIGACOES&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pMotivo=$cd_motivo_32&pSubmotivo=$cd_submotivo_32\" target=\"_blank\">$ligacoes</a></b></td>";
                                            else
                                               $texto_ligacoes = "<td>$qtde_sac</td>";
                                                
                                            if ($qtde_sac > 0)                                             
                                               $texto_sac =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c32.php?pGrupo=SAC&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pMotivo=$cd_motivo_32&pSubmotivo=$cd_submotivo_32\" target=\"_blank\">$qtde_sac</a></b></td>";
                                            else 
                                                $texto_sac = "<td>$qtde_sac</td>";
                                            
                                            break;
                                            
                                        case 2:
                                            if ($sac_operador_32 > 0)
                                                $cod_operador = $sac_operador_32;
                                            else
                                                $cod_operador = 0;
                                            
                                            if ($sac_fila_32 > 0)
                                                $cod_fila = $sac_fila_32;
                                            else
                                                $cod_fila = 0;
                                                
                                            $cd_motivo = utf8_encode($row['cd_motivo']);
                                            $cd_submotivo = utf8_encode($row['cd_submotivo']);
                                            
                                            $motivo = utf8_encode($row['ds_motivo']);
                                            $submotivo = utf8_encode($row['ds_submotivo']);
                                            $motivo = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $motivo);
                                            $submotivo = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $submotivo);
                                            echo "<td>$cd_motivo ($motivo)</td>";
                                            echo "<td>$cd_submotivo ($submotivo)</td>";                                           
                                            
                                            
                                            if ($ligacoes > 0)
                                                $texto_ligacoes =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c32.php?pGrupo=LIGACOES&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pMotivo=$cd_motivo&pSubmotivo=$cd_submotivo&psMotivo=$motivo&psSubmotivo=$submotivo\" target=\"_blank\">$ligacoes</a></b></td>";
                                            else
                                               $texto_ligacoes = "<td>$qtde_sac</td>";
                                                    
                                             if ($qtde_sac > 0)
                                                 $texto_sac =  "<td><b><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c32.php?pGrupo=SAC&pData1=$data_inicial_u&pData2=$data_final_u&pOperador=$cod_operador&pFila=$cod_fila&pMotivo=$cd_motivo&pSubmotivo=$cd_submotivo&psMotivo=$motivo&psSubmotivo=$submotivo\" target=\"_blank\">$qtde_sac</a></b></td>";
                                             else
                                                 $texto_sac = "<td>$qtde_sac</td>";
                                            break;
                                    }
                                    
                                                                                                                                              
                                    $total_ligacoes = $total_ligacoes + $ligacoes;
                                    $total_sac = $total_sac + $qtde_sac;                                                                                                             
                                                          
                                    //imprimindo resultados                                                                        
                                   
                                    echo $texto_ligacoes;
                                    echo $texto_sac;                                                                                                                                         
                                    echo "<td><b>$pct_sac%</b></td>";                                                                                                               
                                    echo '</tr>';
                                } // final FOR
                                
                                if ($total_ligacoes > 0)                                 
                                   $pct_total_sac = $total_sac / $total_ligacoes * 100;
                                else 
                                    $pct_total_sac = 0;
                                                                                                     
                                 $pct_total_sac =  number_format($pct_total_sac, 2, ',', '.');                                  
                                                               
                                 $sfooter =  " <td><b>TOTAL</b></td>
                                                  <td><b></b></td>
                                                  <td><b>$total_ligacoes</b></td>
                                                  <td><b>$total_sac</b></td>
                                                  <td><b>$pct_total_sac%</b></td>
                                                 ";                                  
                                                        
                  
                       echo "</tbody>
                       <tr class='w3-indigo'>                                                                          	                        	                        
                        	$sfooter                                                                               
                        </tr> 
                    </table>";
		     echo "</div>";
		echo "</div>";
		
	
	
		$fim = defineTime();
		echo tempoDecorrido($inicio,$fim);
		include "desconecta.php";
		
?>

<script>  
    document.getElementById("divtitulo").appendChild(document.getElementById("tmp"));    
</script>
</body>
</html>

