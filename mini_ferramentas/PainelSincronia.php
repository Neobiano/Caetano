
<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" href="css/radar.css">

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
  <link rel="stylesheet" href="css/jquery-ui.css" />


  <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
  <script src="js/jquery-1.8.2.js"></script>
  <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
  <script src="js/jquery-ui.js"></script>
  
  <script src="http://cdn.datatables.net/plug-ins/1.10.13/sorting/date-eu.js"></script>    
  <link rel="stylesheet" type="text/css" href="css/dataTables.css">  
  <script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>


    

    	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">    

		//---------------------RELOAD DA PÁGINA-------------------
    	 var time = new Date().getTime();
         $(document.body).bind("mousemove keypress", function(e) {
             time = new Date().getTime();
         });
    
         function refresh() {
             if(new Date().getTime() - time >= 60000) 
                 window.location.reload(true);
             else 
                 setTimeout(refresh, 10000);
         }
    
         setTimeout(refresh, 10000);
       //---------------------FIM RELOAD-------------------
      <?php
    	   include_once "conecta.php";    
    	   $sql = "select datepart(hh,t.data_hora_verif) hora,
                	datepart(mi,t.data_hora_verif) minuto,
                	datepart(ss,t.data_hora_verif) segundo,
                	t.dif_minutos,
                	t.tabela,
                	case
                	  when dif_minutos > 40 then 'red'
                	  else 'green'
                	end cor,
                	t.data_hora_ult_sinc,
                	t.id,
                	t.data_hora_verif,
                	t2.data_hora_verif data_hora_verif_grupo,
                	t2.id id_grupo,
                    t.callid_ult_sinc
                	from tb_verif_sincronia_item t
                	inner join tb_verif_sincronia t2 on (t2.id = t.id_tb_verif_sincronia)
                    where cast(t.data_hora_verif as date) = cast (CURRENT_TIMESTAMP as date)
                	order by id";
	       //echo $sql;
	       $query = $pdo->prepare($sql);
	       $query->execute();
	       $linha = '';
	       $lista = '';
	       for($i=0; $row = $query->fetch(); $i++)
	       {
	           $data = utf8_encode($row['data_hora_verif']);
	           $data = date("Y-m-d H:i:s", strtotime($data));
	           
	           $data2 = utf8_encode($row['data_hora_ult_sinc']);
	           $data2 = date("Y-m-d H:i:s", strtotime($data2));
	           
	           $data_grupo = utf8_encode($row['data_hora_verif_grupo']);
	           $data_grupo = date("Y-m-d H:i:s", strtotime($data_grupo));
	           
	          
	           $id_grupo = $row['id_grupo'];
	           $id = $row['id'];
	           $callid = $row['callid_ult_sinc'];
	           $hora = $row['hora'];
	           $minuto = $row['minuto'];
	           $segundo = $row['segundo'];
	           $dif_minutos = $row['dif_minutos'];
	           $tabela = $row['tabela'];
	           $cor = $row['cor'];
	           if ($linha <>'')
	               $linha.=',';
	           
	           $tooltip = '<div><b>id:</b> '.$id.'<br><b>Tabela:</b> '.$tabela.' <br><b>Delay:</b> '.$dif_minutos.' Minutos</div>';
	           
	           $linha .= "[[$hora, $minuto, $segundo],$dif_minutos, 'point {size: 6; fill-color: $cor','$tooltip']";
	           
	           //preenchendo os dados para a a tabela
	           $lista .= "<tr>";
	               $lista .= "<td>$data_grupo</td>";
    	           $lista .= "<td>$id</td>";
    	           $lista .= "<td>$tabela</td>";
    	           $lista .= "<td>$data</td>";
    	           $lista .= "<td>$data2</td>";
    	           if ($cor == 'red')
    	           {
    	               $lista .= "<td bgcolor='#f2c4c4'>$dif_minutos</td>";
    	           }
    	           else
    	           {
    	             $lista .= "<td>$dif_minutos</td>";
    	           }
    	           
    	           $lista .= "<td>$callid</td>";
	           $lista .= "</tr>";
	       }
    	   //echo $linha;    
      ?>      

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);	
      function drawChart() {
    	    var data = new google.visualization.DataTable();
    	    data.addColumn('timeofday', 'Hora');
    	    data.addColumn('number', 'Delay de Sincronia');
    	    data.addColumn( {'type': 'string', 'role': 'style'} );
    	    data.addColumn( {'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
    	    data.addRows([
    	                   <?php echo $linha?>
    	                 ]);
    	    
    	    var options = {

    	    		tooltip: { isHtml: true },

    	    		vAxes: {
    	                0: {title: 'Minutos'},    	                
    	              }
    	    }
    	    
    	    var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
    	    chart.draw(data, options);
    	    
    	    
    	}	
	
     
  	
      $(document).ready( function (){
          var table = $('#tabela').DataTable( {
                                        	  "columnDefs": [
                                                  { "visible": false, "targets": 0 }
                                              ],
      											"order": [[ 0, "asc" ]],
      		 									"iDisplayLength": -1,
      		 									"drawCallback": function ( settings ) {
      		 							            var api = this.api();
      		 							            var rows = api.rows( {page:'current'} ).nodes();
      		 							            var last=null;
      		 							 
      		 							            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
      		 							                if ( last !== group ) {
      		 							                    $(rows).eq( i ).before(
      		 							                        '<tr style="background-color: #b6b6ba"><td colspan="5"><b>'+group+'</b></td></tr>'
      		 							                    );
      		 							 
      		 							                    last = group;
      		 							                }
      		 							            } );
      		 							        }
      		 									
          		  				
      										} );

          // Order by the grouping
          $('#tabela tbody').on( 'click', 'tr.group', function () {
              var currentOrder = table.order()[0];
              if ( currentOrder[0] === 0 && currentOrder[0] === 'asc' ) {
                  table.order( [ 0, 'desc' ] ).draw();
              }
              else {
                  table.order( [ 0, 'asc' ] ).draw();
              }
          } );	
      });
          
    </script>
  </head>
  <body>

    <!-- LOGO CAIXA -->
    <br>
    <div class="w3-container w3-center">
    	<img src="logo.png" style="width:140px">
    </div>			
    <hr>
    
    <!-- TÍTULO -->
    <div class='w3-container w3-padding w3-margin w3-tiny w3-center w3-indigo w3-wide w3-card-4'><b>RADAR CARTÕES - Painel de verificação de Sincronia BD</b></div>
      <?php 
      // IMPRIME TÍTULO DA CONSULTA
    	echo '<div id="divtitulo" class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center">';
    	echo "<b>Banco de Dados - DB_ATF</b>";
    	echo "<br><br><b>Data:</b> ".date("d/m/Y");
    	echo "<br><br><b>Obs:</b> Em caso de inatividade, a página irá se atualizar a cada 60 segundos.";
    	echo '</div>';
    	//echo $linha;
    	//echo $lista;
    	//echo $sql;
    	?>
      <div class="w3-border w3-margin w3-padding-bottom w3-card-4" style="margin-top:0; !important;">
        <div id="chart_div" style="height: 300px;" ></div>    
      </div>  
    
  
  <div class="w3-margin-left w3-margin-right w3-margin-bottom w3-tiny w3-center w3-border w3-padding w3-card-4" style="padding-bottom:16px !important;">
    <table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>
  	
      	<thead>
            <tr class="w3-indigo">
            	<td><b>Grupo de Sincronia</b></td>                                      
                <td><b>ID</b></td>                
                <td><b>Tabela</b></td>               
                <td><b>Dt. Verificação</b></td>
                <td><b>Dt. Ult. Sincronia</b></td>
                <td><b>Dif. Minutos</b></td>
                <td><b>Ult. CallID</b></td>
           </tr>
        </thead>
        <tbody>
          <?php echo $lista?>
         </tbody>
    </table>     
  </div>
  </body>
</html>





