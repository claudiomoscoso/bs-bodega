<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$estado = $_GET["estado"];

if($modulo == 0)
	mssql_query("UPDATE AutorizaOCompraInterna SET dblSeleccion = $estado WHERE dblModulo = 0 AND strUsuario = '$usuario'", $cnx);
elseif($modulo == 1){
	$numero = $_GET["numero"];
	mssql_query("UPDATE AutorizaOCompraInterna SET dblSeleccion = $estado WHERE dblModulo = 0 AND strUsuario = '$usuario' AND dblNumero = $numero", $cnx);
}
?>