<!DOCTYPE html>
<html>
<body>

<?php
$tabela = array();
for ($i=1; $i < 3; $i++)  
{		//Dados totalização diário
		$tabela[$i] = array(							  
							  "DIA" => $i,
							  "ANSM" => 0.00,
							  "QTDE_AT_ELETRONICO" => 0.00,
							  "QTDE_AT_HUMANO" => 0.00,
							  "QTDE_AT_TOTAL" => 0.00,
							  "REM_AT_H_BRUTO" => 0.00,
							  "DESC_ANSM_DIARIO" => 0.00,
							  "AD_ACP_DIARIO" => 0.00,						  
							  "REM_AT_ELETRONICO" => 0.00,
							  "REM_AT_HUMANO" => 0.00,
							  "REM_AT_TOTAL" => 0.00
							  );
							  
		for ($x=1; $x < 10; $x++)
		{	
			
			//Dados totalização por fila - diário tambem
			$tabela[$i][$x] =   array(
									"COD_FILA" => $x,
									"NOME_FILA" => 'NOME FILA - '.$x,
									"VALOR_A" => $i,
									"APL_MULT_ANSM"=> 0.00,// a partir daqui dados da 'segunda' tabela, estes serao calculados posteriormente
									"AD_ACP"=> 0.00,
									"APLI_MULT_ACP"=> 0.00
									);																											
							
							
		}		
							
}

//$tabela[2]["REM_AT_TOTAL"] = 300;
$count = count($tabela[1])-11;
//$tabela[2][6]["NOME_FILA"] = 'fala doido';
//print_r($tabela[2]);
print_r($count);

var_dump($tabela[1]);

/*$cars = array
  (
  array("Volvo",22,18),
  array("BMW",15,13),
  array("Saab",5,2),
  array("Land Rover",17,15)
  );
  
echo $cars[0][0].": In stock: ".$cars[0][1].", sold: ".$cars[0][2].".<br>";
echo $cars[1][0].": In stock: ".$cars[1][1].", sold: ".$cars[1][2].".<br>";
echo $cars[2][0].": In stock: ".$cars[2][1].", sold: ".$cars[2][2].".<br>";
echo $cars[3][0].": In stock: ".$cars[3][1].", sold: ".$cars[3][2].".<br>";*/
?>

</body>
</html>