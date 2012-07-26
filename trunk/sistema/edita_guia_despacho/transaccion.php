<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
switch($modulo){
	case 0:
		$codigo=$_GET["codigo"];
		$cantidad=$_GET["cantidad"];
		mssql_query("EXEC Bodega..sp_setTMPDetalleGuiaDespacho 2, NULL, '$usuario', '$codigo', $cantidad", $cnx);
		break;
	case 1:
		$bodega = $_POST["hdnBodega"];
		$despant = $_POST["hdnDespacho"];
		$despacho = $_POST["txtDespacho"];
		$fecha = formato_fecha($_POST["txtFecha"], false, true);
		
		mssql_query("EXEC Bodega..sp_setGuiaDespacho 1, '$usuario', $despacho, '$fecha', '$bodega', $despant", $cnx);
		break;
	case 2:
		$despacho = $_GET["despacho"];
		$stmt = mssql_query("SELECT dblNumero FROM Bodega..Despacho WHERE dblNumero = $despacho", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			alert('El número de guía ingresado ya existe.');
			parent.document.getElementById('txtDespacho').value = parent.document.getElementById('hdnDespacho').value;
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>