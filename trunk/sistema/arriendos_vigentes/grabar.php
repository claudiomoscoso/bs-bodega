<?php
include '../autentica.php';
include '../conexion.inc.php';

$numOC = $_POST["numOC"];
$cargo = $_POST["cmbCargo"];
$observacion = $_POST["observacion"];
$stmt = mssql_query("EXEC Bodega..sp_setOrdenCompra 3, NULL, NULL, '$cargo', NULL, NULL, '$observacion', '$usuario', NULL, NULL, 'OCO', 0, '12000', NULL, $numOC", $cnx);
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
	alert('El tiempo de arriendo no puede ser mayor al de la orden de compra original.');
}else if(parseInt('<?php echo $error;?>') == 2){
	alert('No ha sido posible obtener el número correlativo interno.');
}else if(parseInt('<?php echo $error;?>') == 4){
	alert('No ha sido posible obtener información del cargo.');
}else if(parseInt('<?php echo $error;?>') == 5){
	alert('No ha sido posible guardar la orden de compra.');
}
-->
</script>
<?php
mssql_close($cnx);
?>