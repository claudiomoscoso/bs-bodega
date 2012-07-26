<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
switch($modulo){
	case 0:
		$codigo=$_GET["codigo"];
		$cantidad=$_GET["cantidad"];
		mssql_query("EXEC Bodega..sp_setTMPDetalleGuiaDevolucion 2, NULL, '$usuario', '$codigo', $cantidad", $cnx);
		break;
	case 1:
		$bodega = $_POST["hdnBodega"];
		$devolucion = $_POST["hdnDevolucion"];
		$fecha = formato_fecha($_POST["txtFecha"], false, true);
		
		mssql_query("EXEC Bodega..sp_setGuiaDevolucion 1, '$usuario', '$bodega', '$fecha', NULL, NULL, $devolucion", $cnx);
		break;
}
mssql_close($cnx);
?>