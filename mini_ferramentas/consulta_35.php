<!DOCTYPE html>
<html>
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" href="css/radar.css">
  <link rel="stylesheet" href="css/jquery-ui.css" />
  <link rel="stylesheet" href="css/dataTables.css">  
  <link rel="stylesheet" href="css/jquery.datetimepicker.css"/>

  <script src="js/jquery-1.8.2.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script charset="utf8" src="js/dataTables.js"></script>
  <script src="js/jquery.datetimepicker.full.js"></script>      
</head>

<body>

       <?php
       include "conecta.php";
       //$inicio = defineTime();
       if (isset($_GET['pData1'])) 
          $data1 = $_GET['pData1'];
       
       if (isset($_GET['pData2']))
         $data2 = $_GET['pData2'];
       
       $act = $_GET['act'];
       
       if (isset($_GET['act'])) 
       {       
           $b_codigo = ($_POST['codigo']);            
           $b_titulo = ($_POST['titulo']);
           $b_requerente = ($_POST['requerente']);
           $b_dt_abertura = ($_POST['dt_abertura']);
           $b_status = ($_POST['status']);
           $b_impacto = ($_POST['impacto']);
           $b_descricao = ($_POST['descricao']);
           $b_a_impacto = ($_POST['a_impacto']);
           $b_a_causa = ($_POST['a_causa']);
           $b_a_sintomas = ($_POST['a_sintomas']);       
           $b_s_solucao = ($_POST['s_solucao']);
           $b_s_data_solucao = ($_POST['s_data_solucao']);
           $b_categoria = ($_POST['categoria']);
           $b_tecnico = ($_POST['tecnico']);
           
       
       
           switch ($act)
           {
               case 'new':
                   echo "Vai ID -->".$b_id;
                   if ($b_id <= 0)
                   {    
                       
                       $sql = " insert into tb_cscit (codigo,titulo, requerente, data_abertura,tecnico,
                                                      status,impacto,categoria,
                                                      descricao,
                                                      a_impacto,a_causa,a_sintomas,
                                                      s_solucao,s_data_solucao)     
                                           values(
                                               '$b_codigo','$b_titulo','$b_requerente','$b_dt_abertura','$b_tecnico',
                                               '$b_status','$b_impacto','$b_categoria',
                                               '$b_descricao',
                                                '$b_a_impacto','$b_a_causa','$b_a_sintomas',
                                               '$b_s_solucao','$b_s_data_solucao'                                           
                                               )";  
                       //echo $sql;
                       $query = $pdo->prepare($sql);
                       $query->execute();
                       
                       $sql = "select coalesce(max(id),0) id from tb_cscit";
                       //echo $sql;
                       $query = $pdo->prepare($sql);
                       $query->execute();
                       for($i=0; $row = $query->fetch(); $i++)
                       {
                           $b_id = ($row['id']);                          
                       }    
                   }
               break;
               case 'edit': 
                   $b_id = ($_POST['id']);
                   $sql = "UPDATE tb_cscit
                                       SET 
                                          titulo = '$b_titulo'
                                          ,codigo = '$b_codigo'
                                          ,requerente = '$b_requerente'
                                          ,data_abertura = '$b_dt_abertura'
                                          ,status = '$b_status'
                                          ,impacto = '$b_impacto'
                                          ,descricao = '$b_descricao'
                                          ,a_impacto = '$b_a_impacto'
                                          ,a_causa = '$b_a_causa'
                                          ,a_sintomas = '$b_a_sintomas'
                                          ,s_solucao = '$b_s_solucao'                                                                            
                                          ,s_data_solucao = case 
                                                                    when '$b_s_data_solucao' = '' then null
                                                                    else '$b_s_data_solucao'
                                                            end                                                               
                                          ,categoria = '$b_categoria'
                                          ,tecnico = '$b_tecnico'
                                     WHERE id = '$b_id'";
                   //echo $sql;
                   $query = $pdo->prepare($sql);
                   $query->execute();
                   
                break;   
                case 'del':
                    $b_id = ($_POST['id']);
                    $sql = "delete from tb_cscit                                       
                            WHERE id = '$b_id'";
                    
                    //echo $sql;
                    $query = $pdo->prepare($sql);
                    $query->execute();
                    
                break;
           }
           
        }
       
        $sql = '';                                                   
        $nome_relatorio = "Listagem de Incidentes - CSCIT"; // NOME DO RELATÃ“RIO (UTILIZAR UNDERLINE, POIS Ã‰ PARTE DO NOME DO ARQUIVO EXCEL)
        $titulo = "Listagem de Incidentes - CSCIT"; // MESMO NOME DO INDEX
        $nao_gerar_excel = 1; // DEFINIR 1 PARA NÃO IMPRIMIR BOTÃO EXCEL
                	                                                  
        echo '<div class="w3-margin w3-tiny w3-center">'; 
        echo '<div id="divtitulo" class="w3-margin w3-tiny w3-center">';
            echo "<b>$titulo</b>";
            echo "<br><br><b><i>Período de Consulta:</i></b> $data1 à $data2 ";                
            echo "<br>";
        echo "</div>";
                
            echo '<div class="w3-border" style="padding:16px 16px;">';
                echo "<table id='tabela' name='tabela' class='w3-table w3-striped  w3-tiny'> ";
                echo ' <thead>
                            <tr class="w3-indigo">
                                <tr class="w3-indigo">                                   
                                    <td colspan="9"><b>Descrição<b></td>
                                    <td colspan="3"><b>Análise</b></td>
                                    <td colspan="2"><b>Solução</b></td>
                                    <td colspan="3"><b>Ações<b></td>
                                </tr>   
                            	<tr class="w3-indigo">    
                                    <td><b>ID</b></td>                                
                                	<td><b>Código</b></td>                
                                    <td><b>Título</b></td>
                                    <td><b>Requerente</b></td>
                                    <td><b>Dt. Abertura</b></td>           	                                    
                                    <td><b>Técnico</b></td>
                                    <td><b>Status</b></td>
                                    <td><b>Impacto</b></td>
                                    <td><b>Categoria</b></td>
                                
                                	<td><b>Impacto</b></td>
                                    <td><b>Causas</b></td>
                                	<td><b>Sintomas</b></td>               
                                    
                                
                                	<td><b>Solução</b></td>
                                	<td><b>Dt. Solução</b></td>
                                    <td><b></b></td> 
                                    <td><b></b></td>
                                    <td><b></b></td>
                                </tr>           	
                            </tr>
                        </thead>
                        <tbody>';
                      
                        $sql = "set nocount on;
                        
                                select *,
                                datepart(dw,data_abertura) dia_semana,
                                DATEPART(dd,data_abertura) d_dia,
                                DATEPART(mm,data_abertura) d_mes,
                                DATEPART(YYYY,data_abertura) d_ano 
                                from tb_cscit (nolock)
                               -- where data_abertura between '$data1 00:00:00' and '$data2 23:59:59.999'";
                                                                                                        
                               
                                $total_incidentes = 0;
                                
                                //echo $sql;                                     
                                $query = $pdo->prepare($sql);
                                $query->execute();                                
                                for($i=0; $row = $query->fetch(); $i++)
                                {
                                    
                                    $total_incidentes++;
                                    $data = ($row['dia']);	
                                    $dia_semana = $row['dia_semana'];                                    
                                    $data = date("Y-m-d", strtotime($data));                                                                      
                                   // $dia_semana = diaSemana($dia_semana);   
                                    
                                    $id = ($row['id']);
                                    $codigo = ($row['codigo']);
                                    $titulo = ($row['titulo']);
                                    $requerente = ($row['requerente']);
                                    $data_abertura = ($row['data_abertura']);
                                    $tecnico = ($row['tecnico']);
                                    $status = ($row['status']);
                                    $impacto = ($row['impacto']);
                                    $categoria = ($row['categoria']);
                                    $descricao = ($row['descricao']);
                                    
                                    $a_impacto = ($row['a_impacto']);
                                    $a_causa = ($row['a_causa']);
                                    $a_sintomas = ($row['a_sintomas']);
                                    
                                    $s_solucao = ($row['s_solucao']);
                                    $s_data_solucao = ($row['s_data_solucao']);                                    
                                    echo '<tr>';
                                    echo "<td>$id</td>";
                                        echo "<td>$codigo</td>";
                                        echo "<td>$titulo</td>";
                                        echo "<td>$requerente</td>";
                                        echo "<td>$data_abertura</td>";                                                                       
                                        echo "<td>$tecnico</td>";
                                        echo "<td>$status</td>";
                                        echo "<td>$impacto</td>";
                                        echo "<td>$categoria</td>";
                                        
                                        echo "<td>$a_impacto</td>";
                                        echo "<td>$a_causa</td>";
                                        echo "<td>$a_sintomas</td>";
                                                                    
                                        echo "<td>$s_solucao</td>";
                                        echo "<td>$s_data_solucao</td>";                                        
                                     
                                        ?>  
                                        	<td> 
                                        		<a onclick="document.getElementById('id<?php echo $id ?>Novo').style.display='block'" title="Novo"><img src="imagens/add.png" alt="Novo" /> </a>                                                                                                                                                                          
                                                 <div id="id<?php echo $id ?>Novo" class="w3-modal">
                                                    <div class="w3-modal-content w3-card-6 w3-animate-zoom" style="width:1200px">	     
                                                      <form class="w3-container" action="consulta_35.php?pData1=<?php echo $data1; ?>&pData2=<?php echo $data2; ?>&act=new" method="POST">
                                                        <div class="w3-section">
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l1">
                                                				  <label class="w3-text-black"><b>ID</b> </label>
                                                				  <input disabled class="w3-input w3-border w3-margin-bottom w3-light-grey " type="text" name="codigo" value="" >			  			 
                                                			   </div> 	
                                                			   <div class="w3-col w3-small l2">
                                                				  <label class="w3-text-black"><b>Código</b> </label>
                                                				  <input class="w3-input w3-border w3-margin-bottom " type="text" name="codigo" value="" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l9">
                                                					<label class="w3-text-black"><b>Título</b></label>
                                                					<input autofocus class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Título" name="titulo" value="" >	
                                                			   </div>   			 		  
                                                			</div>
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				  <label class="w3-text-black"><b>Requerente</b></label>
                                                				  <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Usuário requerente" name="requerente" value="" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Dt. Abertura</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Data de Abertura" id = "dt_abertura_new" name="dt_abertura" value="" >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Técnico</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Técnico Responsável" name="tecnico" value="" >	
                                                			   </div>   			 		 
                                                			</div>
                                                			
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				  <label class="w3-text-black"><b>Status</b></label>
                                                				  <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Status do chamado" name="status" value="" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Impacto</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Impacto do problema" name="impacto" value="" >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Categoria</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Categoria do problema" name="categoria"  value="" >	
                                                			   </div>   			 		 
                                                			</div>
                                                			<div class="w3-row-padding">
                                                				<div class="w3-col w3-small l12">
                                                					<label class="w3-text-black"><b>Descrição</b></label>
                                                					<textarea class="w3-input w3-border" style="resize:none" name="descricao" ></textarea>
                                                				</div>   			 		 
                                                			</div>
                                                			<div class="w3-row-padding">
                                                				<label>.</label>
                                                			</div>  
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				 <label class="w3-text-blue"><b>Impacto</b></label>				 
                                                				  <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Impacto do problema (analise)" name="a_impacto"  value="" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">					
                                                					<label class="w3-text-blue"><b>Causa</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Causas do problema" name="a_causa" value=""  >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-blue"><b>Sintomas</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Sintomas do problema" name="a_sintomas" value=" " >	
                                                			   </div>   			 		 
                                                			</div>
                                                			
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l8">
                                                				 <label class="w3-text-green"><b>Solução</b></label>
                                                				 <textarea class="w3-input w3-border" style="resize:none" name="s_solucao" ></textarea>					 
                                                				  			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">					
                                                					<label class="w3-text-green"><b>Data Solução</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Data da solução" id="s_data_solucao_new" name="s_data_solucao" value="" >	
                                                			   </div>   			 		  			   			   
                                                			</div>
                                                			<div class="w3-row-padding">
                                                				<label>.</label>
                                                			</div>  
                                                			  			
                                                      </form>                                                
                                                       <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                                                        	<button onclick="document.getElementById('id<?php echo $id ?>Novo').style.display='none'" type="submit" class="w3-btn w3-green w3-border w3-large ">   OK   </button>        
                                                			<button onclick="document.getElementById('id<?php echo $id ?>Novo').style.display='none'" type="button" class="w3-btn w3-green w3-border w3-large ">Cancelar</button>
                                                      </div>                                                
                                                    </div>
                                                  </div>
                                                </div>   
                                            </td>
                                            
                                            <td> 
                                        		<a onclick="document.getElementById('id<?php echo $id ?>Editar').style.display='block'" title="Editar"><img src="imagens/edit.png" alt="Editar" /> </a>                                                                                                                                                                          
                                                 <div id="id<?php echo $id ?>Editar" class="w3-modal">
                                                    <div class="w3-modal-content w3-card-6 w3-animate-zoom" style="width:1200px">	     
                                                      <form class="w3-container" action="consulta_35.php?pData1=<?php echo $data1; ?>&pData2=<?php echo $data2; ?>&act=edit" method="POST">
                                                        <div class="w3-section">
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l1">
                                                				  <label class="w3-text-black"><b>ID</b> </label>
                                                				  <input class="w3-input w3-border w3-margin-bottom w3-light-grey " type="text" name="id" value="<?php echo $id ?>" >			  			 
                                                			   </div> 
                                                			   	 
                                                			   <div class="w3-col w3-small l2">
                                                				  <label class="w3-text-black"><b>Código</b> </label>
                                                				  <input class="w3-input w3-border w3-margin-bottom " type="text" name="codigo" value="<?php echo $codigo ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l9">
                                                					<label class="w3-text-black"><b>Título</b></label>
                                                					<input autofocus class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Título" name="titulo" value="<?php echo $titulo; ?>" >	
                                                			   </div>   			 		  
                                                			</div>
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				  <label class="w3-text-black"><b>Requerente</b></label>
                                                				  <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Usuário requerente" name="requerente" value="<?php echo $requerente; ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Dt. Abertura</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Data de Abertura" id="dt_abertura_edit" name="dt_abertura" value="<?php echo $data_abertura; ?>" >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Técnico</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Técnico Responsável" name="tecnico" value="<?php echo $tecnico ?>" >	
                                                			   </div>   			 		 
                                                			</div>
                                                			
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				  <label class="w3-text-black"><b>Status</b></label>
                                                				  <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Status do chamado" name="status" value="<?php echo $status; ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Impacto</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Impacto do problema" name="impacto" value="<?php echo $impacto; ?>" >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Categoria</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Categoria do problema" name="categoria"  value="<?php echo $categoria; ?>" >	
                                                			   </div>   			 		 
                                                			</div>
                                                			<div class="w3-row-padding">
                                                				<label>.</label>
                                                			</div>
                                                			
                                                			<div class="w3-row-padding">
                                                				<div class="w3-col w3-small l12">
                                                					<label class="w3-text-black"><b>Descrição</b></label>
                                                					<textarea class="w3-input w3-border" style="resize:none" name="descricao" ><?php echo $descricao; ?></textarea>
                                                				</div>   			 		 
                                                			</div>
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				 <label class="w3-text-blue"><b>Impacto</b></label>				 
                                                				  <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Impacto do problema (analise)" name="a_impacto"  value="<?php echo $a_impacto; ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">					
                                                					<label class="w3-text-blue"><b>Causa</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Causas do problema" name="a_causa" value="<?php echo $a_causa; ?>"  >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-blue"><b>Sintomas</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Sintomas do problema" name="a_sintomas" value="<?php echo $a_sintomas; ?> " >	
                                                			   </div>   			 		 
                                                			</div>
                                                			
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l8">
                                                				 <label class="w3-text-green"><b>Solução</b></label>	
                                                				 <textarea class="w3-input w3-border" style="resize:none" name="s_solucao" ><?php echo $s_solucao; ?></textarea>			                                                 				 			  			
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">					
                                                					<label class="w3-text-green"><b>Data Solução</b></label>
                                                					<input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Data da solução"  id="s_data_solucao_edit" name="s_data_solucao" value="<?php echo $s_data_solucao; ?>" >	
                                                			   </div>   			 		  			   			   
                                                			</div>
                                                			<div class="w3-row-padding">                                                				
                                                				<label>.</label>
                                                			</div>
                                                			      			
                                                      </form>                                                
                                                       <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                                                        	<button onclick="document.getElementById('id<?php echo $id ?>Editar').style.display='none'" type="submit" class="w3-btn w3-blue w3-border w3-large ">   OK   </button>        
                                                			<button onclick="document.getElementById('id<?php echo $id ?>Editar').style.display='none'" type="button" class="w3-btn w3-blue w3-border w3-large ">Cancelar</button>
                                                      </div>                                                
                                                    </div>
                                                  </div>
                                                </div>   
                                            </td>
                                            
                                            <td> 
                                        		<a onclick="document.getElementById('id<?php echo $id ?>Excluir').style.display='block'" title="Editar"><img src="imagens/delete.png" alt="Excluir" /> </a>                                                                                                                                                                          
                                                 <div id="id<?php echo $id ?>Excluir" class="w3-modal">
                                                    <div class="w3-modal-content w3-card-6 w3-animate-zoom" style="width:1200px">	     
                                                      <form class="w3-container" action="consulta_35.php?pData1=<?php echo $data1; ?>&pData2=<?php echo $data2; ?>&act=del" method="POST">
                                                        <div class="w3-section">
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l1">
                                                				  <label class="w3-text-black"><b>ID</b> </label>
                                                				  <input class="w3-input w3-border w3-margin-bottom w3-light-grey " type="text" name="id" value="<?php echo $id ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l2">
                                                				  <label class="w3-text-black"><b>Código</b> </label>
                                                				  <input disabled class="w3-input w3-border w3-margin-bottom w3-light-grey " type="text" name="codigo" value="<?php echo $codigo ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l9">
                                                					<label class="w3-text-black"><b>Título</b></label>
                                                					<input disabled autofocus class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Título" name="titulo" value="<?php echo $titulo; ?>" >	
                                                			   </div>   			 		  
                                                			</div>
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				  <label class="w3-text-black"><b>Requerente</b></label>
                                                				  <input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Usuário requerente" name="requerente" value="<?php echo $requerente; ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Dt. Abertura</b></label>
                                                					<input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Data de Abertura" name="dt_abertura" value="<?php echo $data_abertura; ?>" >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Técnico</b></label>
                                                					<input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Técnico Responsável" name="tecnico" value="<?php echo $tecnico ?>" >	
                                                			   </div>   			 		 
                                                			</div>
                                                			
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				  <label class="w3-text-black"><b>Status</b></label>
                                                				  <input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Status do chamado" name="status" value="<?php echo $status; ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Impacto</b></label>
                                                					<input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Impacto do problema" name="impacto" value="<?php echo $impacto; ?>" >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-black"><b>Categoria</b></label>
                                                					<input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Categoria do problema" name="categoria"  value="<?php echo $categoria; ?>" >	
                                                			   </div>   			 		 
                                                			</div>
                                                			<div class="w3-row-padding">
                                                				<div class="w3-col w3-small l12">
                                                					<label class="w3-text-black"><b>Descrição</b></label>
                                                					<textarea disabled  class="w3-input w3-border" style="resize:none" name="descricao" ><?php echo $descricao; ?></textarea>
                                                				</div>   			 		 
                                                			</div>
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l4">
                                                				 <label class="w3-text-blue"><b>Impacto</b></label>				 
                                                				  <input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Impacto do problema (analise)" name="a_impacto"  value="<?php echo $a_impacto; ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">					
                                                					<label class="w3-text-blue"><b>Causa</b></label>
                                                					<input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Causas do problema" name="a_causa" value="<?php echo $a_causa; ?>"  >	
                                                			   </div>   			 		  
                                                			   
                                                			   <div class="w3-col w3-small l4">
                                                					<label class="w3-text-blue"><b>Sintomas</b></label>
                                                					<input disabled  class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Sintomas do problema" name="a_sintomas" value="<?php echo $a_sintomas; ?> " >	
                                                			   </div>   			 		 
                                                			</div>
                                                			
                                                			<div class="w3-row-padding">
                                                			   <div class="w3-col w3-small l8">
                                                				 <label class="w3-text-green"><b>Solução</b></label>				 
                                                				  <input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Solução apresentada" name="s_solucao" value="<?php echo $s_solucao; ?>" >			  			 
                                                			   </div> 
                                                			   
                                                			   <div class="w3-col w3-small l4">					
                                                					<label class="w3-text-green"><b>Data Solução</b></label>
                                                					<input disabled class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Data da solução" id="s_data_solucao_del" name="s_data_solucao" value="<?php echo $s_data_solucao; ?>" >	
                                                			   </div>   			 		  			   			   
                                                			</div>
                                                			      			
                                                      </form>                                                
                                                       <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                                                        	<button onclick="document.getElementById('id<?php echo $id ?>Excluir').style.display='none'" type="submit" class="w3-btn w3-red w3-border w3-large ">   OK   </button>        
                                                			<button onclick="document.getElementById('id<?php echo $id ?>Excluir').style.display='none'" type="button" class="w3-btn w3-red w3-border w3-large">Cancelar</button>
                                                      </div>                                                
                                                    </div>
                                                  </div>
                                                </div>   
                                            </td>
                                        <?php                                              
                                      
                                    echo '</tr>';
                                }
            
                       echo "</tbody>
                       <tr class='w3-indigo'>                                              	                        	                        
                        	<td><b>TOTAL</b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                             <td><b></b></td>
                            <td><b></b></td> 
                            <td><b></b></td>
                            <td><b></b></td>    
                        	<td><b>$total_incidentes</b></td>                        
                        </tr> 
                    </table>";
		     echo "</div>";
		echo "</div>";
				
		//$fim = defineTime();
		//echo tempoDecorrido($inicio,$fim);
		include "desconecta.php";
		
?>

		<script>
    		 $(document).ready(function () {
                'use strict';
               
               $('#dt_abertura_new,#dt_abertura_edit, #s_data_solucao_edit, #s_data_solucao_new').datetimepicker();
            });

    		 $(document).ready( function () {
    	          $('#tabela').DataTable( {
    	        	  "lengthMenu": [[100, -1], [100, "All"]],
    	              "order": [[ 0, "asc" ]],
    	             "columnDefs": [
    	                  {"className": "dt-center", "targets": "_all"}
    	               ]
    	         } );
    	     } );     
        </script>
</body>
</html>

