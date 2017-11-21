<link rel="stylesheet" type="text/css" href="css/dataTables.css">  
<script type="text/javascript" charset="utf8" src="js/dataTables.js"></script>

<script>
$(document).ready( function () {
    $('#tabela').DataTable();
} );
</script>

<?php
	echo "<table id='tabela' name='tabela' class='w3-table w3-striped w3-hoverable w3-tiny'>";
	echo "<thead><tr class='w3-indigo'>";
	$tabela = $tabela."<table><tr>"; // INCREMENTA TABELA A SER IMPRESSA
?>