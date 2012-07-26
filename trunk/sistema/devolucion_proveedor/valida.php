<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$bodega = $_GET["bodega"];
$usuario = $_GET["usuario"];
switch($modulo){
	case 0:
		$gdevolucion = $_GET["gdevolucion"];
		$stmt = mssql_query("SELECT dblNumero FROM Despacho WHERE dblNumero = $gdevolucion", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
			<script language="javascript">
			alert('El número de guía de despacho ya existe.');
			parent.document.getElementById('txtDevolucion').value = '';
			</script>
		<?php
		}else{
			$stmt1 = mssql_query("EXEC Bodega..sp_getUltimaGD '$bodega', $gdevolucion", $cnx);
			if($rst1 = mssql_fetch_array($stmt1)) $UDevolucion = $rst1["dblNumero"];
			mssql_free_result($stmt1);
			if($gdevolucion > $UDevolucion + 6){?>
			<script language="javascript">alert('Está seguro que el número de Devolción ingresado es correcto.');</script>
		<?php	
			}
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$codigo = $_GET["codigo"];
		$cantidad = $_GET["cantidad"];
		mssql_query("EXEC Bodega..sp_setTMPDetalleDevolucionProveedor 0, '$bodega', '$usuario', '$codigo', $cantidad", $cnx);
		break;
	case 2:
		mssql_query("EXEC Bodega..sp_setTMPDetalleDevolucionProveedor 1, '$bodega', '$usuario'", $cnx);
		break;
	case 3:
		mssql_query("EXEC Bodega..sp_setTMPDetalleDevolucionProveedor 2, '$bodega', '$usuario'", $cnx);
		break;
}
mssql_close($cnx);
?>