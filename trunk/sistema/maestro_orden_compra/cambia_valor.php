<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$codigo = $_GET["codigo"];
$valor = $_GET["valor"];
/*mssql_query("UPDATE TMPDetalleOrdenCompra SET dblValor = $valor WHERE dblModulo = 5 AND strUsuario = '$usuario' AND strCodigo='$codigo'", $cnx);
mssql_close($cnx);*/
?>