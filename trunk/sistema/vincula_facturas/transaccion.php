<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$facturas =  split(',', $_GET["facturas"]);
$proveedores = split(',', $_GET["proveedores"]);
$epago = ($_GET["epago"] != '' ? $_GET["epago"] : 'NULL');
$clasificacion = ($_GET["clasificacion"] != '' ? $_GET["clasificacion"] : 'NULL');
for($i = 0; $i < count($facturas); $i++){
	mssql_query("EXEC Bodega..sp_setVinculaFactura 0, $facturas[$i], '$proveedores[$i]', $epago, '$clasificacion'", $cnx);
}
mssql_close($cnx);
?>