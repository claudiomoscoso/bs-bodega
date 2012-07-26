<?php
include '../autentica.php';
include '../conexion.inc.php';

$contrato = $_POST["cmbContrato"];
$movil = $_POST["hdnMovil"];
$orden = strtoupper($_POST["txtOrden"]);

$dd = date('d');
$mm = date('m');
$yy = date('Y');
$hora = $_POST["txtHora"];
$fecha = $mm.'/'.$dd.'/'.$yy.' '.$hora;

$fch[] = substr($_POST["hdnFVence"], 0, 2);
$fch[] = substr($_POST["hdnFVence"], 3, 2);
$fch[] = substr($_POST["hdnFVence"], 6, 4);
$hr = trim(substr($_POST["hdnFVence"], 11, 5));
$vence = $fch[1].'/'.$fch[0].'/'.$fch[2].' '.$hr;

$ttrabajo = $_POST["cmbTTrabajo"];
$prioridad = $_POST["cmbPrioridad"];
$motivo = $_POST["txtMotivo"];
$cliente = $_POST["txtCliente"];
$objeto = $_POST["txtObjeto"];
$direccion = $_POST["txtDireccion"];
$entrecalle = $_POST["txtEntreCalle"];
$comuna = $_POST["cmbComuna"];
$zona = $_POST["cmbZona"];
$faxods = $_POST["txtFaxOds"];
$inspector = $_POST["cmbInspector"];
$observacion = $_POST["txtObservacion"];
$stmt = mssql_query("EXEC Orden..sp_setOrdenTrabajo 1, '$usuario', '$contrato', '$movil', '$orden', '$fecha', '$vence', '$ttrabajo', '$prioridad', '$motivo', '$cliente', '$direccion', '$entrecalle', '$comuna', '$zona', '$faxods', '$inspector', '$observacion',null, '$objeto'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$error = $rst["dblError"];
	$correlativo = $rst["dblCorrelativo"];
}
mssql_free_result($stmt);
?>
<script language="javascript">
<!--
switch(parseInt('<?php echo $error;?>')){
	case 0:
		alert('La orden de trabajo ha sido guardada con el número <?php echo $correlativo;?>.');
		parent.location.href = "index.php<?php echo $parametros;?>";
		break;
	case 1:
		alert('El número de orden ingresado ya existe.');
		break;
	case 2:
		alert('No ha sido posible obtener el número correlativo de la orden.');
		break;
	case 3:
		alert('No ha sido posible obtener el factor');
		break;
}
-->
</script>
<?php
mssql_close($cnx);
?>
