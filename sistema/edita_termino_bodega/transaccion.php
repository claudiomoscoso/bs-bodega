<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
switch($modulo){
	case 0:
		$codigo = $_GET["codigo"];
		$cantidad = $_GET["cantidad"];
		mssql_query("EXEC Bodega..sp_setTMPTerminoBodega 4, '$usuario', '$codigo', '$cantidad'", $cnx);
		break;
	case 1:
		$bodega = $_POST["hdnBodega"];
		$numero = $_POST["hdnTermino"];

		mssql_query("EXEC Bodega..sp_setTerminoBodega 1, '$usuario', $numero", $cnx);
		break;
}
mssql_close($cnx);
?>