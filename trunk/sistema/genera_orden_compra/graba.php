<?php
include '../autentica.php';
include '../conexion.inc.php';

$numSM = $_POST["numSM"];
$numusuSM=$_POST["numusuSM"];
$bodSM = $_POST["bodSM"];

$forma_pago = $_POST["forma_pago"];
$codigo_proveedor = $_POST["codigo_proveedor"];
$comuna = $_POST["comuna"];
$nota = $_POST["nota"];
$cargo = $_POST["cargo"];
$ccosto = $_POST["ccosto"];
$atencion = $_POST["atencion"];
$email = $_POST["email"];
$tipodoc = $_POST["tipodoc"];

if($perfil=='adquisiciones'){
  $stmt = mssql_query("EXEC sp_setOrdenCompra 5, NULL, '$codigo_proveedor', '$cargo', '$ccosto', '$bodSM', '".Reemplaza($nota)."', '$usuario', $forma_pago, $numSM, 'OCA', $tipodoc", $cnx);
}else{
  $stmt = mssql_query("EXEC sp_setOrdenCompra 0, NULL, '$codigo_proveedor', '$cargo', '$ccosto', '$bodSM', '".Reemplaza($nota)."', '$usuario', $forma_pago, $numSM, 'OCA', $tipodoc", $cnx);
}
if($rst=mssql_fetch_array($stmt)){ 
	$error = $rst["dblError"];
	$interno = $rst["dblInterno"];
	$correlativo = $rst["dblCorrelativo"];
}
mssql_free_result($stmt);

mssql_query("UPDATE Proveedor SET strContacto='".Reemplaza($atencion)."', strCorreo='".Reemplaza($email)."' WHERE strCodigo='$codigo_proveedor'", $cnx);

$stmt = mssql_query("EXEC sp_getTotalCantidadProducto 'SM', $numSM, 1", $cnx);
if($rst=mssql_fetch_array($stmt)) $totcantsm=$rst["dblCant"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC sp_getTotalCantidadProducto 'SM_OC', $numSM, 1", $cnx);
if($rst=mssql_fetch_array($stmt)) $totcantoc=$rst["dblCant"];
mssql_free_result($stmt);

if($totcantsm==$totcantoc) 
	$pag="index.php$parametros";
else
	$pag="solicitud_material.php$parametros&numSM=$numSM&bodSM=$bodSM";
?>
<script language="javascript">
<!--
if(parseInt('<?php echo $error;?>') == 0){
	alert('La orden de compra se ha creado con el número: <?php echo $correlativo;?>');
	parent.location.href = '<?php echo $pag;?>';
}else if(parseInt('<?php echo $error;?>') == 1){
	alert('No ha sido posible obtener el factor de impuesto.');
}else if(parseInt('<?php echo $error;?>') == 2){
	alert('No ha sido posible obtener el número correlativo interno.');
}else if(parseInt('<?php echo $error;?>') == 3){
	alert('No ha sido posible obtener el número correlativo del documento.');
}else if(parseInt('<?php echo $error;?>') == 4){
	alert('No ha sido posible obtener información del cargo.');
}else if(parseInt('<?php echo $error;?>') == 6){
	alert('ERROR CRITICO, no ha sido posible guardar el detalle de la orden de compra (N&deg; Interno <?php echo $interno;?>). Comuniquese con el administrador');
}
-->
</script>
<?php
mssql_close($cnx);
?>