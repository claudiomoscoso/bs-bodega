<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$codigo = $_GET["codigo"];
$finicio = $_GET["finicio"];
$ftermino = $_GET["ftermino"];

mssql_query("EXEC Bodega..sp_setTMPDetalleOrdenCompra 10, '$usuario', NULL, NULL, '$codigo', '".formato_fecha($finicio, false, true)."', '".formato_fecha($ftermino, false, true)."'", $cnx);
mssql_close($cnx);
?>