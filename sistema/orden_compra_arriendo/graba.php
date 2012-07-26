<?php
include '../autentica.php';
include '../conexion.inc.php';

$usuario = $_POST["usuario"];
$bodega = $_POST["bodega"];
if($_POST["hdnInterna"] == 0) $interna = $_POST["chkInterna"] == 'on' ? 1 : 0; else $interna = 1;
if($interna == 0){
	$fpago = $_POST["cmbFPago"];
	$dpago = $_POST["cmbDPago"];
}else{
	$fpago = '12021';
	$dpago = '0';
}
$fecha = $_POST["txtFecha"];
$proveedor = $_POST["hdnProveedor"];
$cargo = $_POST["cmbCargo"];
$ccosto = $_POST["txtCCosto"];
$ccosto = $_POST["hdnCCosto"];
$bodegaoc = $_POST["cmbBodega"];
$nota = $_POST["txtNota"];
$solicitante = $_POST["solicitante"];

if($bodega == 12000) $tcorrelativo = 'OCO'; else $tcorrelativo = ($bodegaoc == '12000' ? 'OCO' : 'OCA');
$stmt = mssql_query("EXEC Bodega..sp_setOrdenCompra 1, NULL, '$proveedor', '$cargo', '$ccosto', '$bodegaoc', '$nota', '$usuario', $fpago, NULL, '$tcorrelativo', $dpago, '$bodegaoc', $interna", $cnx);
/*$stmt = mssql_query("EXEC Bodega..sp_setOrdenCompra 1, NULL, '$proveedor', '$cargo', '$ccosto', '$bodegaoc', '$nota', '$usuario', $fpago, NULL, '$tcorrelativo', $dpago, '$bodega', $interna", $cnx);*/
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