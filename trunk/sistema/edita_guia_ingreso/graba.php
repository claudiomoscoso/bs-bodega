<?php
include '../conexion.inc.php';

$usuario = $_POST["usuario"];
$bodega = $_POST["hdnBodega"];
$numero = $_POST["GIngreso"];
$tdocumento = $_POST["cmbTDocumento"];
$ndocumento = $_POST["txtNDocumento"];
$ocompra = $_POST["hdnOCompra"];
$observacion = $_POST["txtObservacion"];

mssql_query("EXEC Bodega..sp_setGuiaIngreso 1, '$usuario', '$bodega', '".date('m/d/Y')."', $tdocumento, '$ndocumento', '$ocompra', '$observacion', $numero");
mssql_close($cnx);
?>