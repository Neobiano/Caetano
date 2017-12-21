<?php
	
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/xml; charset="utf-8"', true );
    include "conecta.php";
    
	$cd_motivo = ($_REQUEST['cd_motivo']);

	$sub_motivos = array();

	$sql = "select distinct  cd_submotivo, ds_submotivo from tb_log_categorizacao
            where data_hora between (GETDATE() - 5) and (GETDATE() - 3) and cd_motivo = $cd_motivo
            order by ds_submotivo";
	$query = $pdo->prepare($sql);
    $query->execute();
    
    for($i=0; $row = $query->fetch(); $i++)
    {
        $sub_motivos[] = array(    
                            'cd_submotivo'   => $row['cd_submotivo'],
                            'ds_submotivo'          => $row['ds_submotivo'],
                           );
                                                   
    }
	echo( json_encode( $sub_motivos ) );