<?php
    function retornaIlha($fila) 
    {
        $ilha_retencao = "'73','77','81','85','116'";
        $ilha_triagem = "'150'";
        $ilha_aviso_viagem = "'125'";
        $ilha_parcelamento = "'72','76','80','84','111'";
        $ilha_contestacao = "'60','88','90','93','96'";
        $ilha_pontos = "'87','91','94','97','120'";
        $ilha_pj = "'99','101','110'";
        $ilha_caixa_empregado = "'63'";
        $ilha_deficiente_auditivo = "'61'";
        $ilha_mala_direta = "'64'";
        $ilha_geral_normal = "'70','71','74','75','78','79','86','58','89','92','95','103','114','118','57'";
        $ilha_geral_premium = "'82','83','98'";
        $ilha_bloqueio_cobranca = "'117','106','107','108','109'";
        $ilha_app = "'102'";
        
        
        if (substr_count($ilha_retencao,$fila) > 0) 
            return $ilha_retencao;
        else if (substr_count($ilha_triagem,$fila) > 0) 
            return $ilha_triagem;
        else if (substr_count($ilha_aviso_viagem,$fila) > 0) 
            return $ilha_aviso_viagem;
        else if (substr_count($ilha_parcelamento,$fila) > 0) 
            return $ilha_parcelamento;
        else if (substr_count($ilha_contestacao,$fila) > 0) 
            return $ilha_contestacao;
        else if (substr_count($ilha_pontos,$fila) > 0) 
            return $ilha_pontos;
        else if (substr_count($ilha_pj,$fila) > 0) 
            return $ilha_pj;
        else if (substr_count($ilha_caixa_empregado,$fila) > 0) 
            return $ilha_caixa_empregado;
       else if (substr_count($ilha_deficiente_auditivo,$fila) > 0) 
            return $ilha_deficiente_auditivo;
       else if (substr_count($ilha_mala_direta,$fila) > 0) 
            return $ilha_mala_direta;       
       else if (substr_count($ilha_geral_normal,$fila) > 0) 
            return $ilha_geral_normal;
       else if (substr_count($ilha_geral_premium,$fila) > 0) 
            return $ilha_geral_premium;
       else if (substr_count($ilha_bloqueio_cobranca,$fila) > 0) 
            return $ilha_bloqueio_cobranca;       
       else if (substr_count($ilha_app,$fila) > 0) 
            return $ilha_app;
       else 
            return '';
    }

    function retornaIlhaIndice($indice) 
    {   
        switch ($indice) 
        {
            case 0:
                return "'73','77','81','85','116'";//$ilha_retencao  
                break;
            case 1:
                return "'150'";//$ilha_triagem;                
                break;
            case 2:
                return "'125'";//$ilha_aviso_viagem;                
                break;
            case 3:
                return "'72','76','80','84','111'";//$ilha_parcelamento;                
                break;
            case 4:
                return "'60','88','90','93','96'";;//$ilha_contestacao;                
                break;
            case 5:
                return "'87','91','94','97','120'";//$ilha_pontos;                
                break;
            case 6:
                return "'99','101','110'";//$ilha_pj;                
                break;
            case 7:
                return "'63'";//$ilha_caixa_empregado;                
                break;
            case 8:
                return "'61'";//$ilha_deficiente_auditivo;                
                break;
            case 9:
               return  "'64'";//$ilha_mala_direta;                
               break;
            case 10:
               return  "'82','83','98','70','71','74','75','78','79','86','58','89','92','95','103','114','118','57'";//$ilha_geral_normal +  $ilha_geral_premium                
               break;
            case 11:
               return   "'117','106','107','108','109'";//$ilha_bloqueio_cobranca                
               break;
            case 12:
               return   "'102'";//$ilha_app                 
               break;      
            default:
                return '';   
                break;
        }            
    }
?>
