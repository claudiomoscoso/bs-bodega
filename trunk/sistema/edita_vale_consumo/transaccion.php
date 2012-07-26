<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
if($modulo == 0){
	$bodega = $_GET["bodega"];
	$codigo = $_GET["codigo"];
	$cantidad = $_GET["cantidad"];
	$partida = $_GET["partida"];
	$stmt = mssql_query("EXEC Bodega..sp_setTMPDetalleValeConsumo 1, '$usuario', '$bodega', NULL, '$codigo', $cantidad, '$partida'", $cnx);
	if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
	mssql_free_result($stmt);
	?>
	<script language="javascript">
	<!--
	if(parseInt('<?php echo $error;?>') == 1){
		alert('La cantidad ingresada excede el stock existente en bodega.');
	}
	-->
	</script>
<?php
}elseif($modulo == 1){
	$interno = $_GET["interno"];
	mssql_query("EXEC Bodega..sp_setDetalleValeConsumo 0, '$usuario', $interno", $cnx);
	?>
	<script language="javascript">
	<!--
	parent.parent.location.href = '../blank.html';
	parent.parent.parent.document.getElementById('cmbBodega').selectedIndex = 0;
	parent.parent.parent.document.getElementById('txtNumero').value = '';
	-->
	</script>
<?php
}
mssql_close($cnx);
?>