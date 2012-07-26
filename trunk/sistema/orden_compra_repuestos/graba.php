<?php
include '../autentica.php';
include '../conexion.inc.php';

$fpago = $_POST["cmbFPago"];
$fecha = $_POST["txtFecha"];
$proveedor = $_POST["hdnProveedor"];
$ccosto = $_POST["hdnCCosto"];
$nota = $_POST["txtNota"];

$stmt = mssql_query("EXEC sp_setOrdenCompra 5, '".formato_fecha($fecha, false, true)."', '$proveedor', 'M2101', '$ccosto', '12000', '$nota', '$usuario', $fpago, NULL, 'OCO', 1, '$bodega'", $cnx);
if($rst=mssql_fetch_array($stmt)){ 
	$error = $rst["dblError"];
	$interno = $rst["dblInterno"];
	$correlativo = $rst["dblCorrelativo"];
}
mssql_free_result($stmt);
?>
<script language="javascript">
<!--
if(parseInt('<?php echo $error;?>') == 0){
	alert('La orden de compra se ha creado con el número: <?php echo $correlativo;?>');
	parent.location.href = 'index.php<?php echo $parametros;?>';
}else if(parseInt('<?php echo $error;?>') == 1){
	alert('No ha sido posible obtener el factor de impuesto.');
}else if(parseInt('<?php echo $error;?>') == 2){
	alert('No ha sido posible obtener el número correlativo interno.');
}else if(parseInt('<?php echo $error;?>') == 4){
	alert('No ha sido posible obtener información del cargo.');
}
-->
</script>
<?php
mssql_close($cnx);
?>