<!DOCTYPE html>
<html>
<head>
<title>RADAR CARTÕES - Painel de Monitoramento - Cartão de Crédito</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabela').DataTable();
} );
</script>

</head>
<body>
<?php 
    include "conecta.php";
     
    set_time_limit(9999);
    ini_set('max_execution_time', 9999);
        
    $valor_dado = $_GET['pGrupo'];
    $data1 = $_GET['pData1'];
    $data2 = $_GET['pData2'];
    $operador = $_GET['pOperador'];
    $fila = $_GET['pFila'];
    $motivo = $_GET['pMotivo'];
    $submotivo = $_GET['pSubmotivo'];
    $smotivo = $_GET['psMotivo'];
    $ssubmotivo = $_GET['psSubmotivo'];
  
    $sFiltro = '';
    $sinner = '';
    if ($operador > 0)
    {    
        $sFiltro = " Operador: $operador, ";
        $filtro = " and t.id_operador = $operador ";
    }
        
    if ($fila > 0)    
    {    
        $sFiltro = ("$sFiltro  Fila: $fila, ");
        $filtro = "$filtro and t.cod_fila = $fila ";
    }
        
    if ($motivo > 0)
    {    
        $sFiltro = ("$sFiltro  Motivo: $smotivo,  ");
        $filtro = "$filtro and l.cd_motivo = $motivo ";
    }
            
    if ($submotivo > 0)
    {
        $sFiltro = ("$sFiltro  SubMotivo: $ssubmotivo,  ");
        $filtro = "$filtro and l.cd_submotivo = $submotivo ";
    }
    
    $valor_dado = trim($valor_dado);
    if ($valor_dado == 'SAC')
    {
      $sinner = 'inner join #temp_ultimo t2 on (t2.callid = t.callid and t.data_hora = t2.data_hora)';
      $filtro = "$filtro and t.callid in (select callid from tb_eventos_sac s where s.data_hora between '$data1 00:00:00' and '$data2 23:59:59.999' )";
    }
                
    $t_inicial = strtotime($data1);
    $data_inicial_texto = date('d/m/Y',$t_inicial);
    
    $t_final = strtotime($data2);
    $data_final_texto = date('d/m/Y',$t_final);
    
    $cabecalho = '<td><b>CALLID</b></td>
                  <td><b>DATA/HORA</b></td>
                  <td><b>TEMPO DE ESPERA</b></td>
                  <td><b>TEMPO DE ATENDIMENTO</b></td>
                  <td><b>ID OPERADOR</b></td>
                  <td><b>NOME OPERADOR</b></td>
                  <td><b>FILA</b></td>
                  <td><b>DESC_FILA</b></td>';
                      
    
    $sql ="	 set nocount on; 
                        
             select callid, max(data_hora) data_hora 
                            into #temp_ultimo
                            from tb_eventos_dac t 
                            where t.data_hora between '$data1 00:00:00' and '$data2 23:59:59.999'
                            and t.tempo_atend > 10
                            and coalesce(t.desc_operador,'NULL') <> 'NULL'
                            group by callid
   
             select cast(callid as varchar(30)) callid,
            	    data_hora,
            	    cd_motivo,
            	    cd_submotivo,
            	    ds_motivo,
            	    cast(ds_submotivo as varchar(50)) ds_submotivo,
            	    login_front
            into #temp_log
            from tb_log_categorizacao t1
            where t1.data_hora between '$data1 00:00:00' and '$data2 23:59:59.999'
            
            CREATE  INDEX Idx1 ON [#temp_log] (data_hora);
            CREATE CLUSTERED INDEX Idx2 ON [#temp_log] (callid);
            CREATE INDEX Idx3 ON [#temp_log] (ds_submotivo);
                       
            select * 
            into #temp_dac 
            from tb_eventos_dac t1
            where t1.data_hora between '$data1 00:00:00' and '$data2 23:59:59.999'
            
            CREATE  INDEX Idx9 ON [#temp_dac] (data_hora);
            CREATE CLUSTERED INDEX Idx10 ON [#temp_dac] (callid);            
            
            select distinct    
            		t.callid, 
            		t.data_hora, 
            		t.tempo_espera, 
            		t.tempo_atend,
            		t.id_operador, 
            		coalesce(t.desc_operador,i.nome) desc_operador,
            		t.cod_fila, f.desc_fila
            from #temp_dac t
            $sinner	                			
            left join tb_filas f on (f.cod_fila = t.cod_fila)                        
            left join tb_colaboradores_indra i on (i.login_dac = t.id_operador)
            left join #temp_log l on (l.callid = t.callid and l.login_front = i.matricula)
                         
            where t.data_hora between '$data1 00:00:00' and '$data2 23:59:59.999'
            $filtro
            and t.tempo_atend > 10
            and coalesce(t.desc_operador,'NULL') <> 'NULL'
            
            order by t.callid, t.data_hora
		";
    	    
    	    

    echo '<div class="w3-margin w3-tiny w3-center">';
    echo "<b>Rastreio de Atendimentos - Atendimentos SAC</b>";
    echo "<br><br><b><i>Data:</i></b> $data_inicial_texto à $data_final_texto";
    echo "<br><b><i>Filtros:</i></b> $sFiltro ";       
    echo "<br><br><b><i>Listando: COLUNA</i> $valor_dado</b> ";
    echo "<br><br>";
    
    echo '<div class="w3-border" style="padding:16px 16px;">';
    echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
    echo '<thead><tr class="w3-indigo w3-tiny">';
    echo $cabecalho;
    echo '</tr></thead><tbody>';

       // echo $sql;			
        $query = $pdo->prepare($sql);
        $query->execute();
        for($i=0; $row = $query->fetch(); $i++)
        {
        	$callid = $row['callid'];
        	$data_hora = $row['data_hora'];        	
        	$tempo_espera = $row['tempo_espera'];
        	$tempo_atend = $row['tempo_atend'];
        	$id_operador = $row['id_operador'];
        	$desc_operador = $row['desc_operador'];	
        	if($desc_operador=='') $desc_operador = "OPERADOR SEM NOME CADASTRADO";
        	
        	$cod_fila = $row['cod_fila'];
        	$desc_fila = $row['desc_fila'];
        	        	        	    
        	echo '<tr>';
        		echo "<td>$callid</td>";
        		echo "<td>$data_hora</td>";        		
        		echo "<td>$tempo_espera</td>";
        		echo "<td>$tempo_atend</td>";
        		echo "<td>$id_operador</td>";
        		echo "<td>$desc_operador</td>";
        		echo "<td>$cod_fila</td>";
        		echo "<td>$desc_fila</td>";
        	echo '</tr>';
        }
echo "</tbody></table>";
echo "</div>";
echo "</div>";
echo "<br><br>";

include "desconecta.php";
?>
</body>
</html>