<!DOCTYPE html>
<html>
<head>
<title>RADAR CARTÕES - Painel de Monitoramento - Cartão de Crédito</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<script src="js/jquery.min.js"></script>
</head>
<body>
<?php 
    include "conecta.php";
    $dmm_imprime = $dmm;
    $dmm = explode(",", $dmm);
    set_time_limit(9999);
    ini_set('max_execution_time', 9999);
    
    $NSH = $_GET['NSH'];
    $pos_dia = $_GET['pos_dia'];
    $qual_mes = $_GET['qual_mes'];
    $qual_ano = $_GET['qual_ano'];
    $mes = $_GET['mes'];
    $ns = $_GET['ns'];
    $in_filas = $_GET['in_filas'];
    
    $contador_de_faixas = 0;
    $SOMA_NSA = 0;
    
    echo '<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
        echo "<b class='w3-tiny'>Cálculo NSA - Revisão de NS: $pos_dia/$qual_mes/$qual_ano</b><br>";
        echo "<b class='w3-tiny'>Tempo de Espera Padrão: $ns segundos</b><br><br>";    
    echo '</div>';    
      
    //resgatando as 3 datas utilizadas na revisão de nível
    $sql = "select t.*,
                        datepart(dd,data_1) d_1,
                        datepart(mm,data_1) m_1,
                        datepart(yyyy,data_1) a_1,
                        datepart(dd,data_2) d_2,
                        datepart(mm,data_2) m_2,
                        datepart(yyyy,data_2) a_2,
                        datepart(dd,data_3) d_3,
                        datepart(mm,data_3) m_3,
                        datepart(yyyy,data_3) a_3,
                        TME_1,
                        TME_2,
                        TME_3
                    from tb_fat_revisao_nivel_DNS t
                    where t.data_revista = '$qual_ano-$qual_mes-$pos_dia'";
    
    $query = $pdo->prepare($sql);
    $query->execute();
    for($x=0; $row = $query->fetch(); $x++)
    {
        $tme_1 =  $row['TME_1'];
        $tme_2 =  $row['TME_2'];
        $tme_3 =  $row['TME_3'];
        
        $a_1 =  $row['a_1'];        
        $a_2 =  $row['a_2'];
        $a_3 =  $row['a_3'];
        
        $d_1 =  $row['d_1'];        
        $d_2 =  $row['d_2'];
        $d_3 =  $row['d_3'];
    
        $m_1 =  $row['m_1']; 
        
        $m_2 =  $row['m_2'];
        $m_3 =  $row['m_3'];
        
        
        $d_1 = ($d_1 <= 9) ? ('0'.$d_1) : $d_1;
        $d_2 = ($d_2 <= 9) ? ('0'.$d_2) : $d_2;
        $d_3 = ($d_3 <= 9) ? ('0'.$d_3) : $d_3;
        
        $m_1 = ($m_1 <= 9) ? ('0'.$m_1) : $m_1;
        $m_2 = ($m_2 <= 9) ? ('0'.$m_2) : $m_2;
        $m_3 = ($m_3 <= 9) ? ('0'.$m_3) : $m_3;    
                                       
    }
    
    $sql = "select A, B, C,
                                        ISNULL(
                                                cast(ISNULL(A, 0) as float)
                                                /
                                                nullif(
                                                        cast(ISNULL(B, 0) as float)
                                                        +
                                                        cast(ISNULL(C, 0) as float)
                                                      ,0)
                                               ,1) NSA,
                                         ISNULL(B, 0) TOTAL_ATEND,
                                         ISNULL(
                                                  cast(ISNULL(A, 0) as float)
                                                  /
                                                  nullif(
                                                          cast(ISNULL(B, 0) as float)
                                                          +
                                                          cast(ISNULL(C, 0) as float)
                                                         ,0)
                                                ,1) * ISNULL(B, 0) MULT
                                from
            				    (
            				        select
                            				(
                                				select coalesce(count(*),0) A from tb_eventos_dac
                                				where data_hora between 'ano-mes-dia' and 'ano-mes-dia 23:59:59' and cod_fila in ($in_filas)
                                				and tempo_espera <= ns_dia and tempo_atend > 0
                                                and id_operador <> 'NULL'
                                               
                            				) as A,
                            				(
                                				select coalesce(count(*),0) B from tb_eventos_dac
                                				where data_hora between 'ano-mes-dia' and 'ano-mes-dia 23:59:59' and cod_fila in ($in_filas)
                                				and tempo_atend > 0
                                                  and id_operador <> 'NULL'
                            				) as B,
                            				(
                                				select coalesce(count(*),0) C from tb_eventos_dac
                                				where data_hora between 'ano-mes-dia' and 'ano-mes-dia 23:59:59' and cod_fila in ($in_filas)
                                				and tempo_espera > ns_dia and tempo_atend = 0
                            				) as C
            				    ) as NSA";
    

    $A = 0;
    $B = 0;
    $C = 0;
    $TOTAL_ATEND = 0;
    $MULT = 0;
    
    /*-------data 1-------------*/   
    $sqlaux = str_replace('ano-mes-dia', $a_1.'-'.$m_1.'-'.$d_1,$sql);
    $sqlaux = str_replace('ns_dia', $tme_1,$sqlaux);
   // echo $sqlaux;
    $query = $pdo->prepare($sqlaux);
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $TOTAL_ATEND = $row['TOTAL_ATEND'];
        $MULT = $row['MULT'];
        $A = $row['A'];
        $B = $row['B'];
        $C = $row['C'];
        $NSA = $row['NSA'];
    }
                      
    echo "<div class='w3-container w3-padding w3-margin w3-center'>";
    echo "<table class='w3-table w3-striped w3-hoverable w3-tiny w3-card-4'>";
    echo "<b class='w3-tiny'>Data Revisão 1: $d_1/$m_1/$a_1</b><br>";
    echo "<thead>";
        echo "<tr class='w3-indigo'>";
            echo "<td><b>DIA &nbsp</b></td>";
            echo "<td><b>TOTAL DE ATENDIMENTOS &nbsp</b></td>";
            echo "<td><b>A &nbsp</b></td>";
            echo "<td><b>B &nbsp</b></td>";
            echo "<td><b>C &nbsp</b></td>";
            echo "<td><b>TEMPO DE ESPERA PADRÃO &nbsp</b></td>";
            //echo "<td><b>NSA = A / (B + C) &nbsp</b></td>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
        echo "<tr>";
            echo "<td>$d_1/$m_1/$a_1</td>";
            echo "<td>$TOTAL_ATEND</td>";
            echo "<td>$A</td>";
            echo "<td>$B</td>";
            echo "<td>$C</td>";
            echo "<td>$tme_3</td>";
            //echo "<td>$NSA</td>";
       echo "</tr>";
   echo "</tbody>"; 
   echo "</table>";
   echo "</div>";
   
    /*-------data 2-------------*/
    $sqlaux = str_replace('ano-mes-dia', $a_2.'-'.$m_2.'-'.$d_2,$sql);
    $sqlaux = str_replace('ns_dia', $tme_2,$sqlaux);
    //echo $sqlaux;
    $query = $pdo->prepare($sqlaux);
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $TOTAL_ATEND = $row['TOTAL_ATEND'];
        $MULT = $row['MULT'];
        $A = $row['A'];
        $B = $row['B'];
        $C = $row['C'];
        $NSA = $row['NSA'];
    }
    
    echo "<div class='w3-container w3-padding w3-margin w3-center'>";
        echo "<table class='w3-table w3-striped w3-hoverable w3-tiny w3-card-4'>";
            echo "<b class='w3-tiny'>Data Revisão 2: $d_2/$m_2/$a_2</b><br>";
            echo "<thead>";
                echo "<tr class='w3-indigo'>";
                    echo "<td><b>DIA &nbsp</b></td>";
                    echo "<td><b>TOTAL DE ATENDIMENTOS &nbsp</b></td>";
                    echo "<td><b>A &nbsp</b></td>";
                    echo "<td><b>B &nbsp</b></td>";
                    echo "<td><b>C &nbsp</b></td>";
                    echo "<td><b>TEMPO DE ESPERA PADRÃO &nbsp</b></td>";
                    //echo "<td><b>NSA = A / (B + C) &nbsp</b></td>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td>$d_2/$m_2/$a_2</td>";
                    echo "<td>$TOTAL_ATEND</td>";
                    echo "<td>$A</td>";
                    echo "<td>$B</td>";
                    echo "<td>$C</td>";
                    echo "<td>$tme_3</td>";
                    //echo "<td>$NSA</td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    echo "</div>";
    
    /*-------data 3-------------*/
    $sqlaux = str_replace('ano-mes-dia', $a_3.'-'.$m_3.'-'.$d_3,$sql);
    $sqlaux = str_replace('ns_dia', $tme_3,$sqlaux);
    //echo $sqlaux;
    $query = $pdo->prepare($sqlaux);
    $query->execute();
    for($i=0; $row = $query->fetch(); $i++)
    {
        $TOTAL_ATEND = $row['TOTAL_ATEND'];
        $MULT = $row['MULT'];
        $A = $row['A'];
        $B = $row['B'];
        $C = $row['C'];
        $NSA = $row['NSA'];
    }
       
    
    echo "<div class='w3-container w3-padding w3-margin w3-center'>";
        echo "<table class='w3-table w3-striped w3-hoverable w3-tiny w3-card-4'>";
            echo "<b class='w3-tiny'>Data Revisão 3: $d_3/$m_3/$a_3</b><br>";
            echo "<thead>";
                echo "<tr class='w3-indigo'>";
                    echo "<td><b>DIA &nbsp</b></td>";
                    echo "<td><b>TOTAL DE ATENDIMENTOS &nbsp</b></td>";
                    echo "<td><b>A &nbsp</b></td>";
                    echo "<td><b>B &nbsp</b></td>";
                    echo "<td><b>C &nbsp</b></td>";
                    echo "<td><b>TEMPO DE ESPERA PADRÃO &nbsp</b></td>";
                   // echo "<td><b>NSA = A / (B + C) &nbsp</b></td>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td>$d_3/$m_3/$a_3</td>";
                    echo "<td>$TOTAL_ATEND</td>";
                    echo "<td>$A</td>";
                    echo "<td>$B</td>";
                    echo "<td>$C</td>";
                    echo "<td>$tme_3</td>";
                   // echo "<td>$NSA</td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    echo "</div>";

include "desconecta.php";
?>
</body>
</html>