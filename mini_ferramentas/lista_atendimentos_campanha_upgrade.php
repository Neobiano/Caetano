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
    
    $data_inicial = $_GET['pData'];    
    $valor_dado = $_GET['pGrupo'];
    
    $cabecalho = '<td><b>CALLID</b></td>
                  <td><b>DATA/HORA</b></td>
                  <td><b>TEMPO DE ESPERA</b></td>
                  <td><b>TEMPO DE ATENDIMENTO</b></td>
                  <td><b>ID OPERADOR</b></td>
                  <td><b>NOME OPERADOR</b></td>
                  <td><b>MOTIVO</b></td>
                  <td><b>SUBMOTIVO</b></td>';
   
                   

    //Conversão Data Texto - Início
    $t_inicial = strtotime($data_inicial);
    $data_inicial_texto = date('d/m/Y',$t_inicial);

    $valor_dado = trim($valor_dado);
    switch ($valor_dado) 
    {			
    	case 'recebidas':
    	    $sql ="	 set nocount on; 
       
                     declare @T TABLE(callid varchar(50),
                                        data_hora datetime,
                                        cod_fila int,
                                        tempo_espera int,
                                        tempo_consulta_mudo int,
                                        tempo_atend int,
                                        id_operador int,
                                        desc_operador varchar(50),
                                        ds_motivo varchar(50),
                                        ds_submotivo varchar(50),
                    					CPF varchar(20),
                    					BASE char(3)                					
                                    ); 
                     insert @T exec sp_CERATFO_radar_cartoes_query30b '$data_inicial',1,64
                                                                                    
                     select callid ,
                            data_hora ,
                            cod_fila ,
                            tempo_espera ,
                            tempo_consulta_mudo,
                            tempo_atend,
                            id_operador,
                            desc_operador,
                            ds_motivo,
                            ds_submotivo,
                    		CPF,
                    		BASE
                    		from @T 
        		";
    	    
    	    $cabecalho = '<td><b>CALLID</b></td>
                          <td><b>DATA/HORA</b></td>
                          <td><b>TEMPO DE ESPERA</b></td>
                          <td><b>TEMPO DE ATENDIMENTO</b></td>
                          <td><b>ID OPERADOR</b></td>
                          <td><b>NOME OPERADOR</b></td>
                          <td><b>MOTIVO</b></td>
                          <td><b>SUBMOTIVO</b></td>
                          <td><b>CPF/CLIENTE</b></td>
                          <td><b>BASE(S/N)</b></td>';
    		break;
    	
    	case 'recebidas_categorizadas':
    	    $sql ="	 
                    select 
                    distinct t3.*, t1.ds_motivo, t1.ds_submotivo
                    from tb_log_categorizacao t1
                    inner join tb_eventos_dac t3 on (t3.callid = t1.callid)
                    where cast(t1.data_hora as date) = '$data_inicial'
                    and cast(t3.data_hora as date) = '$data_inicial'
                    and t3.cod_fila = 64
                    and t3.id_operador is not null
                    order by t3.callid, t3.data_hora
        		";
    		break;
    		
    	case 'recebidas_nao_categorizadas':
    	    $sql ="	 select 
                        distinct t3.*
                        from tb_eventos_dac t3 
                        where cast(t3.data_hora as date) = '$data_inicial'
                        and t3.cod_fila = 64
                        and t3.id_operador is not null
                        and t3.callid not in (
                        						select distinct callid from tb_log_categorizacao t1 
                        						where (cast(t1.data_hora as date) = '$data_inicial')	
                        					) 
                        order by t3.callid, t3.data_hora
        		";
    		break;	
    		
    	case 'categorizadas_nao_campanha':
    	    $sql ="	 select distinct t3.*, t1.ds_motivo, t1.ds_submotivo
                 from tb_log_categorizacao t1
                 inner join tb_eventos_dac t3 on (t3.callid = t1.callid)
                 where cast(t1.data_hora as date) = '$data_inicial'
                 and cast(t3.data_hora as date) = '$data_inicial'
                 and t3.cod_fila = 64
                 and t3.id_operador is not null
                 and t1.callid not in (
                                       select distinct t1.callid
                                       from tb_log_categorizacao t1
                                       inner join tb_eventos_dac t4 on (t4.callid = t1.callid)
                                       where cast(t1.data_hora as date) = '$data_inicial'
                                       and cast(t4.data_hora as date) = '$data_inicial'
                                       and t4.cod_fila = 64
                                       and (
                                            (t1.ds_submotivo like '%MASTERCARD%')
                                           )
                                       )
                 order by t3.callid, t3.data_hora
        		";
    	    break;	
    	    
    	case 'categorizadas_nao_campanha_BASE':
    	    $sql ="	 set nocount on; 
       
                    declare @T TABLE(callid varchar(50),
                                        data_hora datetime,
                                    	cod_fila int,
                                    	tempo_espera int,
                    					tempo_consulta_mudo int,
                    					tempo_atend int,
                    					id_operador int,
                                    	desc_operador varchar(50),
                    					ds_motivo varchar(50),
                    					ds_submotivo varchar(50)                					
                                    ); 
                    insert @T EXEC sp_CERATFO_radar_cartoes_query30a '$data_inicial', 1, 64
                                                                
                    select callid ,
                            data_hora ,
                            cod_fila ,
                            tempo_espera ,
                    		tempo_consulta_mudo,
                    		tempo_atend,
                    		id_operador,
                            desc_operador,
                    		ds_motivo,
                    		ds_submotivo
                    from @T 
        		";
    	   
    	    break;
    	    
    	case 'categorizadas_campanha':
    	    $sql ="	 select distinct
                    --count(distinct t1.callid),
                    t4.*,
                    t1.ds_motivo,
                    t1.ds_submotivo
                    from tb_log_categorizacao t1 
                    inner join tb_eventos_dac t4 on (t4.callid = t1.callid)
                    where (cast(t1.data_hora as date) = '$data_inicial')
                    and (cast(t4.data_hora as date) = '$data_inicial')	
                    and t4.cod_fila =64
                    and (
                    		(t1.ds_submotivo like '%MASTERCARD%') 
                    	)
                    and t4.id_operador is not null 
        		";
    	    
    	    break;
    	
    	case 'campanha_aceitou':
    	    $sql ="	 select distinct
                    --count(distinct t1.callid),
                    t4.*,
                    t1.ds_motivo,
                    t1.ds_submotivo
                    from tb_log_categorizacao t1
                    inner join tb_eventos_dac t4 on (t4.callid = t1.callid)
                    where (cast(t1.data_hora as date) = '$data_inicial')
                    and (cast(t4.data_hora as date) = '$data_inicial')
                    and t4.cod_fila =64
                    and (
                    		(t1.ds_submotivo like '%MASTERCARD UPGRADE – ACEITOU%')
                    	)
                    and t4.id_operador is not null
        		";
    	    
    	    break;	
    	    
    	case 'campanha_nao_aceito_anui_alta':
    	    $sql ="	 select distinct
                    --count(distinct t1.callid),
                    t4.*,
                    t1.ds_motivo,
                    t1.ds_submotivo
                    from tb_log_categorizacao t1
                    inner join tb_eventos_dac t4 on (t4.callid = t1.callid)
                    where (cast(t1.data_hora as date) = '$data_inicial')
                    and (cast(t4.data_hora as date) = '$data_inicial')
                    and t4.cod_fila =64
                    and (
                    		(t1.ds_submotivo like '%MASTERCARD UPGRADE– NÃO ACEITOU – Anuidade alta%')
                    	)
                    and t4.id_operador is not null
        		";
    	    
    	    break;	
    	    
    	case 'campanha_nao_aceito_nao_int_pontos':
    	    $sql ="	 select distinct
                    --count(distinct t1.callid),
                    t4.*,
                    t1.ds_motivo,
                    t1.ds_submotivo
                    from tb_log_categorizacao t1
                    inner join tb_eventos_dac t4 on (t4.callid = t1.callid)
                    where (cast(t1.data_hora as date) = '$data_inicial')
                    and (cast(t4.data_hora as date) = '$data_inicial')
                    and t4.cod_fila =64
                    and (
                    		(t1.ds_submotivo like '%MASTERCARD UPGRADE– NÃO ACEITOU – Não interessa Bonificação Pontos%')
                    	)
                    and t4.id_operador is not null
        		";
    	    
    	    break;
    	    
    	case 'campanha_nao_aceito_nao_int_variant':
    	    $sql ="	 select distinct
                    --count(distinct t1.callid),
                    t4.*,
                    t1.ds_motivo,
                    t1.ds_submotivo
                    from tb_log_categorizacao t1
                    inner join tb_eventos_dac t4 on (t4.callid = t1.callid)
                    where (cast(t1.data_hora as date) = '$data_inicial')
                    and (cast(t4.data_hora as date) = '$data_inicial')
                    and t4.cod_fila =64
                    and (
                    		(t1.ds_submotivo like '%MASTERCARD UPGRADE– NÃO ACEITOU – Não interessa Benefícios Variante%')
                    	)
                    and t4.id_operador is not null
        		";
    	    
    	    break;
    	    
    	case 'campanha_nao_aceito_nao_informou':
    	    $sql ="	 select distinct
                    --count(distinct t1.callid),
                    t4.*,
                    t1.ds_motivo,
                    t1.ds_submotivo
                    from tb_log_categorizacao t1
                    inner join tb_eventos_dac t4 on (t4.callid = t1.callid)
                    where (cast(t1.data_hora as date) = '$data_inicial')
                    and (cast(t4.data_hora as date) = '$data_inicial')
                    and t4.cod_fila =64
                    and (
                    		(t1.ds_submotivo like '%MASTERCARD UPGRADE– NÃO ACEITOU – Não informou%')
                    	)
                    and t4.id_operador is not null
        		";
    	    
    	    break;
    }

    echo '<div class="w3-margin w3-tiny w3-center">';
    echo "<b>Rastreio de Atendimentos - Capanha de Upgrade Mastercard</b>";
    echo "<br><br><b><i>Data:</i></b> $data_inicial_texto";
    echo "<br><br><b><i>Listando:</i></b> $valor_dado";
    echo "<br><br>";
    
    echo '<div class="w3-border" style="padding:16px 16px;">';
    echo '<table id = "tabela" class="w3-table w3-bordered w3-striped w3-border w3-hoverable w3-tiny w3-card-4">';
    echo '<thead><tr class="w3-indigo w3-tiny">';
    echo $cabecalho;
    echo '</tr></thead><tbody>';

        //echo $sql;			
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
        	
        	$ds_motivo = $row['ds_motivo'];
        	$ds_submotivo = $row['ds_submotivo'];
        	
        	
        	    
        	echo '<tr>';
        		echo "<td>$callid</td>";
        		echo "<td>$data_hora</td>";        		
        		echo "<td>$tempo_espera</td>";
        		echo "<td>$tempo_atend</td>";
        		echo "<td>$id_operador</td>";
        		echo "<td>$desc_operador</td>";
        		echo "<td>$ds_motivo</td>";
        		echo "<td>$ds_submotivo</td>";
                if ($valor_dado == 'recebidas')
                {    
                  $cpf = $row['CPF'];
                  $base = $row['BASE'];
                  
                  if ($base == 'Sim')
                  {   
                      echo "<td><b><font color='red'>$cpf</font></b></td>";
                      echo "<td><b><font color='red'>$base</font></b></td>";
                  }
                  else
                  {                          
                      echo "<td>$cpf</td>";
                      echo "<td>$base</td>";
                  }
                }
        		
        		
        		
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