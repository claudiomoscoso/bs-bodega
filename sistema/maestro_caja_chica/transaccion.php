<?php
include '../conexion.inc.php';
$bodega=$_GET["bodega"];
$usuario=$_GET["usuario"];
$rut=$_GET["rut"];
$factor=$_GET["factor"];
$codigo=$_GET["codigo"];
$tipodoc=$_GET["tipodoc"];
$numdoc=$_GET["numdoc"];
$cantidad=$_GET["cantidad"];
$precio=$_GET["precio"];
$estado=$_GET["estado"] != '' ? $_GET["estado"] : 'NULL';
$cantant=$_GET["cantant"];
$totant=$_GET["totant"];
$ind=$_GET["ind"];

if($tipodoc == 0) $total = round($cantidad * $precio * $factor); else $total = round($cantidad * $precio);

$stmt = mssql_query("EXEC Bodega..sp_setTMPDetalleCajaChica 3, '$bodega', '$usuario', '$codigo', $tipodoc, $numdoc, NULL, $cantidad, $precio, $total, '$rut', $cantant, $totant, $estado", $cnx);
if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
mssql_free_result($stmt);
?>
<script language="javascript">
<!--
if('<?php echo $error;?>' == '0'){
	parent.document.getElementById('hdnCantidad<?php echo $ind;?>').value = parent.document.getElementById('txtCantidad<?php echo $ind;?>').value;
	parent.document.getElementById('hdnPrecio<?php echo $ind;?>').value = parent.document.getElementById('txtPrecio<?php echo $ind;?>').value;
	parent.document.getElementById('hdnTotal<?php echo $ind;?>').value = parent.document.getElementById('txtTotal<?php echo $ind;?>').value;
	parent.document.getElementById('hdnTotGral').value = parent.parent.document.getElementById('txtTotGnral').value;
}else{
	parent.document.getElementById('txtCantidad<?php echo $ind;?>').value = parent.document.getElementById('hdnCantidad<?php echo $ind;?>').value;
	parent.document.getElementById('txtPrecio<?php echo $ind;?>').value = parent.document.getElementById('hdnPrecio<?php echo $ind;?>').value;
	parent.document.getElementById('txtTotal<?php echo $ind;?>').value = parent.document.getElementById('hdnTotal<?php echo $ind;?>').value;
	parent.parent.document.getElementById('txtTotGnral').value = parent.document.getElementById('hdnTotGral').value;
	if('<?php echo $error;?>' == '1')
		alert('Las facturas que excedan los $30000.- debe tener una orden de compra');
	else if('<?php echo $error;?>' == '2')
		alert('Las boletas por compubustible no pueden exceder los $7000.');
	else if('<?php echo $error;?>' == '3')
		alert('Las boletas no pueden exceder los $3000.');
	else if('<?php echo $error;?>' == '4')
		alert('La rendición está excediendo su fondo fijo.');
		
}
-->
</script>
<?php
mssql_close($cnx);
?>