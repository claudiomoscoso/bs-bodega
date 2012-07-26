<?php
include '../autentica.php';
include '../conexion.inc.php';

$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$usuario' AND clave='".$_POST["clave_ant"]."'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	$msj='La contrase&ntilde;a fue cambiada correctamente.';
	mssql_query("UPDATE General..Usuarios SET clave='".$_POST["clave_nva"]."' WHERE id=".$rst["id"], $cnx);
}else
	$msj='Usuario y/o contraseña incorrecta.';
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cambiar Clave</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
function Load(){
	alert('<?php echo $msj;?>');
}
</script>
<body onload="javascript: Load()">
</body>
</html>
<?php
mssql_close($cnx);
?>
