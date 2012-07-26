<?php
include '../autentica.php';
include '../conexion.inc.php';

$proyecto = $_POST["cmbProyecto"];
$lote = $_POST["txtLote"];
$piso = strtoupper($_POST["txtPiso"]);
$coordenadas = strtoupper($_POST["txtCoordenadas"]);
$direccion = strtoupper($_POST["txtDireccion"]);
$comuna = strtoupper($_POST["txtComuna"]);
$nombre = strtoupper($_POST["txtNombre"]);
$apellido = strtoupper($_POST["txtApellido"]);
$fono = $_POST["txtFono"];
$celular = $_POST["txtCelular"];
$email = $_POST["txtEmail"];
$arrendatario = $_POST["txtArrendatario"];
$fonoarrendatario = $_POST["txtFonoArrendatario"];
$observacion = $_POST["txtObservacion"];
$stmt = mssql_query("EXEC Inmobiliaria..sp_setLote 1, '$usuario', '$proyecto', '$lote', '$piso', '$coordenadas', '$direccion', '$comuna', '$nombre', '$apellido', '$fono', '$celular', '$email', '$arrendatario', '$fonoarrendatario', '$observacion'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$error = $rst["dblError"];
}
mssql_free_result($stmt);
?>
<script language="javascript">
<!--
switch(parseInt('<?php echo $error;?>')){
	case 0:
		alert('Los datos han sido guardados');
		parent.location.href = "index.php<?php echo $parametros;?>";
		break;
	case 1:
		alert('La Información se ha actualizado correctamente');
		parent.location.href = "index.php<?php echo $parametros;?>";
		break;
}
-->
</script>
<?php
mssql_close($cnx);
?>