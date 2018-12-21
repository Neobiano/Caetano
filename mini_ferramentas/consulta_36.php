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
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
</head>

<body>

       <?php
      // include "funcoes.php";
        /*filtros*/                   
       
        $sql = '';                                                   
        $nome_relatorio = "Lista_CPF_TELEFONE"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Listagem de Atendimentos - CPF/TELEFONE"; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
                	                                                  
        echo '<div class="w3-margin w3-tiny w3-center">'; 
        echo "<b>$titulo</b>";
        echo "<br><br><b><i>Período de Consulta:</i></b> $data_inicial_texto à $data_final_texto ";                
        echo "<br><br>";
        
            echo '<div class="w3-border" style="padding:16px 16px;">';
                echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4 w3-centered">';
                    echo '<thead>
                                <tr class="w3-indigo w3-tiny">';
                                echo '<td><b>Data</b></td>';
                                echo '<td><b>Callid</b></td>';                               
                                echo '<td><b>Tipo Dado</b></td>';
                                echo '<td><b>Valor</b></td>';                                
                                echo '<td><b>ATC</b></td>';
                                echo '<td><b>URA</b></td>';
                                echo '<td><b>Pesquisa</b></td>';
                        echo '</tr>
                          </thead>
                            <tbody>';
                      
                                $sql ="	
                                        	set nocount on;

                                        	select 
                                        	cast(t1.callid as varchar(30)) callid,
                                        	cod_fonte,
                                        	cod_dado,
                                        	cast(data_hora as date) data_hora,
                                        	cast(t1.valor_dado as varchar(30)) valor_dado
                                        	into #temp_dados
                                        	from tb_dados_cadastrais t1 (nolock)
                                        	where t1.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'	
                                        	and cod_dado = $select_tipo_dado
                                        
                                        	select *
                                        	into #temp_dac
                                        	from tb_eventos_dac t1 (nolock)
                                        	where t1.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'	
                                        
                                        	select *
                                        	into #temp_pesq
                                        	from tb_pesq_satisfacao t1 (nolock)
                                        	where t1.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'	
                                        
                                        	select 
                                        		cast(t1.callid as varchar(30)) callid,
                                        		data_hora,
                                        		cod_evento
                                        	into #temp_ura
                                        	from tb_eventos_ura t1 (nolock)
                                        	where t1.data_hora between '$data_inicial_u 00:00:00' and '$data_final_u 23:59:59.999'	
                                        
                                                                              
                                        	CREATE  INDEX Idx1 ON [#temp_ura] (callid);
                                        
                                        	CREATE  INDEX Idx6 ON [#temp_dados] (data_hora);
                                        	CREATE  INDEX Idx7 ON [#temp_dados] (callid);
                                        	CREATE  INDEX Idx8 ON [#temp_dados] (valor_dado);
                                        
                                        	select distinct callid, case 
                                        					when cod_dado = 2 then 'CPF'
                                        					else 'TELEFONE'
                                        				end cod_dado,
                                        	data_hora, 
                                        	valor_dado ,
                                        	case 
                                        		when 
                                        		(
                                        			select count(*) from #temp_dac t1
                                        			where t1.callid = t.callid
                                        			and cast(t1.data_hora as date) = cast(t.data_hora as date)
                                        			) > 0 then 'Sim'
                                        			else 'Não'
                                        	end at_humano,
                                        	case 
                                        		when 
                                        		(
                                        			select count(*) from #temp_ura t1
                                        			where t1.callid = t.callid
                                        			and cast(t1.data_hora as date) = cast(t.data_hora as date)
                                        			) > 0 then 'Sim'
                                        			else 'Não'
                                        	end at_ura,
                                        	case 
                                        		when 
                                        		(
                                        			select count(*) from #temp_pesq t1
                                        			where t1.callid = t.callid
                                        			and cast(t1.data_hora as date) = cast(t.data_hora as date)
                                        			) > 0 then 'Sim'
                                        			else 'Não'
                                        	end at_pesquisa
                                        	from #temp_dados t
                                            where t.valor_dado like '%$valor_dado%'
                                        	order by callid, data_hora";                                                                         
                                
                                //echo $sql;
                                $qtde = 0;
                               
                                
                                $query = $pdo->prepare($sql);
                                $query->execute();                                
                                for($i=0; $row = $query->fetch(); $i++)
                                {                                    
                                   
                                    $data = ($row['data_hora']);	                                                                       
                                    //$data = date("Y-m-d", strtotime($data));                                                                                                                                               
                                    $callid = ($row['callid']);
                                    $cod_dado = ($row['cod_dado']);
                                    $valor_dado = ($row['valor_dado']);
                                    $at_pesquisa = ($row['at_pesquisa']);
                                    $at_ura = ($row['at_ura']);
                                    $at_humano = ($row['at_humano']);
                                    
                                    $qtde++;    
                                    
                                    //imprimindo resultados
                                    echo '<tr>';
                                    echo "<td>$data</td>";
                                    echo "<td>$callid</td>";    
                                    echo "<td>$cod_dado</td>";
                                    echo "<td>$valor_dado</td>";
                                    
                                    if ($at_humano == 'Sim') 
                                        echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c36.php?pData=$data&pValor=$valor_dado&pCallid=$callid&pTipoDado=$cod_dado&pGrupo=ATC\" target=\"_blank\">$at_humano</a></td>";                                   
                                    else 
                                      echo "<td><b>$at_humano</b></td>";
                                    
                                    if ($at_ura == 'Sim')
                                        echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c36.php?pData=$data&pValor=$valor_dado&pCallid=$callid&pTipoDado=$cod_dado&pGrupo=URA\" target=\"_blank\">$at_ura</a></td>";
                                    else
                                        echo "<td><b>$at_ura</b></td>";
                                    
                                    if ($at_pesquisa == 'Sim')
                                        echo "<td><a class='w3-text-indigo' title='Rastrear Ligações' href= \"lista_atendimentos_c36.php?pData=$data&pValor=$valor_dado&pCallid=$callid&pTipoDado=$cod_dado&pGrupo=Pesquisa\" target=\"_blank\">$at_pesquisa</a></td>";
                                    else
                                      echo "<td><b>$at_pesquisa</b></td>";
                                    
                                                                                            
                                    echo '</tr>';
                                }
                                                                
                       echo "</tbody>
                       <tr class='w3-indigo'>                                              	                        	                        
                        	<td><b>TOTAL</b></td>
                        	<td></td>
                        	<td><b>$qtde</b></td>
                        	<td><b></b></td>                        	                        
                        	<td><b></b></td>                            
                            <td><b></b></td>
                            <td><b></b></td>                                                                                  
                        </tr>  
                    </table>";
		     echo "</div>";
		echo "</div>";
		
		
		include "desconecta.php";
?>


</body>
</html>

