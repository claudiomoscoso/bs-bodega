<?php
include '../conexion.inc.php';
$valor=$_GET["valor"];
$existe = 0;
$stmt = mssql_query("EXEC Bodega..sp_getProveedor 4, 'E', NULL, '$valor'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$nombre=$rst["strNombre"];
	$direccion=$rst["strDireccion"];
	$comuna=$rst["strComuna"];
	$telefono=$rst["strTelefono"];
	$fax=$rst["strFax"];
	$email=$rst["strCorreo"];
	$vendor=$rst["strContacto"];
	$existe = 1;
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<script language="javascript">
<!--
function Load(){
	parent.document.getElementById('nombre').value='<?php echo $nombre;?>';
	parent.document.getElementById('direccion').value='<?php echo $direccion;?>';
	var totelem=parent.document.getElementById('comuna').options.length;
	for(ind=0;ind<totelem; ind++){
		if(parent.document.getElementById('comuna').options[ind].value=='<?php echo $comuna;?>'){
			parent.document.getElementById('comuna').selectedIndex=ind;
			break;
		}
	}
	parent.document.getElementById('telefono').value='<?php echo $telefono;?>';
	parent.document.getElementById('fax').value='<?php echo $fax;?>';
	parent.document.getElementById('email').value='<?php echo $email;?>';
	parent.document.getElementById('vendedor').value='<?php echo $vendor;?>';
	if(parseInt('<?php echo $existe;?>') == 1) alert('El proveedor ya existe!!!');
}
-->
</script>
<body onload="javascript: Load();">
</body>
</html>
<?php
mssql_close($cnx);
?>