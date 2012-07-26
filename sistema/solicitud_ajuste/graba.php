<?php
include '../conexion.inc.php';

$usuario = $_POST["usuario"];
$bodega = $_POST["cmbBodega"];
$numero = $_POST["txtNumero"];
$fecha = $_POST["txtFecha"];
$cargo = $_POST["cmbCargos"];
$observacion = $_POST["txtObservacion"];

$stmt = mssql_query("EXEC Bodega..sp_setSolicitudDevolucion 0, '$usuario', $numero, '".formato_fecha($fecha, false, true)."', '$bodega', '$cargo', '$observacion'", $cnx);
if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
mssql_free_result($stmt);
?>
<script language="javascript">
<!--
if(parseInt('<?php echo $error;?>') == 1){
	alert('El número de guía de despacho ya existe.');
	parent.Deshabilita(false);
	parent.document.getElementById('txtNumero').value = '';
}else{
	parent.document.getElementById('btnGrabar').disabled = false;
	parent.document.getElementById('txtNumero').value = '';
	parent.document.getElementById('txtFecha').value = '';
	parent.document.getElementById('cmbBodega').selectedIndex = 0;
	parent.document.getElementById('cmbCargos').selectedIndex = 0;
	parent.document.getElementById('txtObservacion').value = '';
	parent.document.getElementById('frmDetalle').src = '../blank.html';
}
-->
</script>
<?php
mssql_close($cnx);
?>