<?php
include '../conexion.inc.php';
$modulo=$_GET["modulo"];
$contrato=$_GET["contrato"];
$valor=$_GET["valor"];

$sql="EXEC General..sp_getPersonalObra 7, $contrato, '$valor'";

$existe=0;
$stmt = mssql_query($sql, $cnx);
if($rst=mssql_fetch_array($stmt)){
	$nombre=$rst["strNombre"];
	$existe=1;
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script language="javascript">
<!--
if('<?php echo $existe;?>'=='0'){
	parent.document.getElementById('btnGuardar').disabled=false;
}else {
	parent.document.getElementById('txtNombre').value='<?php echo $nombre;?>';
	parent.document.getElementById('btnGuardar').disabled=true;
	alert('El R.U.T. ingresado ya existe');
}
-->
</script>
<?php
mssql_close($cnx);
?>
