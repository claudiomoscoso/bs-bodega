<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$codigo = $_GET["codigo"];
$cantidad = $_GET["cantidad"];

switch($modulo){
	case 0:
		mssql_query("EXEC sp_setTMPDetalleOrdenCompra 1, '$usuario', NULL, NULL, '$codigo', NULL, NULL, NULL, $cantidad", $cnx);
		break;
	case 1:
		mssql_query("EXEC sp_setTMPDetalleOrdenCompra 2, '$usuario'", $cnx);
		break;
	case 2:
		mssql_query("EXEC sp_setTMPDetalleOrdenCompra 4, '$usuario', NULL, NULL, '$codigo', NULL, NULL, NULL, $cantidad", $cnx);
		break;
	case 3:
		mssql_query("EXEC sp_setTMPDetalleOrdenCompra 11, '$usuario'", $cnx);
		break;
}
mssql_close($cnx);
?>