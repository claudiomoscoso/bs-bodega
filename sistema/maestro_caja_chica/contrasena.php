<?php
include '../conexion.inc.php';

$modulo  = $_GET["modulo"] != '' ? $_GET["modulo"] : $_POST["modulo"];
$bodega = $_GET["bodega"] !='' ? $_GET["bodega"] : $_POST["bodega"];
$usuario = $_GET["usuario"] !='' ? $_GET["usuario"] : $_POST["usuario"];
$numero  = $_GET["numero"] !='' ? $_GET["numero"] : $_POST["numero"];
$nota    = $_GET["nota"] !='' ? $_GET["nota"] : $_POST["nota"];
$clave   = $_POST["clave"];
$user    = $_POST["user"];
$error=0;
if($clave!=''){ 
	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$user' AND clave=General.dbo.fn_md5('$clave')", $cnx);
	if($rst=mssql_fetch_array($stmt)){ 
		$error=1;	
		if($modulo==1)
			$sql = "EXEC Bodega..sp_setAgregaCajaChica 2, '$usuario', '$bodega', NULL, '$user', '$nota', NULL, $numero";
		elseif($modulo==2)
			$sql = "EXEC Bodega..sp_setAgregaCajaChica 3, '$usuario', '$bodega', NULL, '$user', '$nota', NULL, $numero";
		mssql_query($sql, $cnx);
	}else 
		$error=2;
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Maestro Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $error;?>'=='0')
		document.getElementById('usuario').focus();
	else if('<?php echo $error;?>'=='1'){
		parent.Deshabilita(false);
		parent.CierraDialogo('divContrasena', 'frmContrasena');
		parent.parent.Deshabilitar(false);
		parent.parent.CierraDialogo('divCajaChica', 'frmCajaChica');
		parent.parent.document.getElementById('frmResultado').src = parent.parent.document.getElementById('frmResultado').src;
	}else if('<?php echo $error;?>'=='2'){
		alert('La contraseña ingresada no es valida.');
		document.getElementById('usuario').focus();
	}
}
-->
</script>
<body topmargin="5px" onLoad="javascript: Load();">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td width="7%" align="left">&nbsp;Usuario</td>
		<td width="1%">:</td>
		<td align="left"><input name="user" id="user" class="txt-plano" style="width:99%" /></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr>
		<td width="7%" align="left">&nbsp;Contrase&ntilde;a</td>
		<td width="1%">:</td>
		<td align="left"><input type="password" name="clave" id="clave" class="txt-plano" style="width:99%" /></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr><td colspan="4"><hr /></td></tr>
	<tr>
		<td colspan="4" align="right">
			<input type="hidden" name="modulo" id="modulo" value="<?php echo $modulo;?>" />
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>" />
			<input type="hidden" name="nota" id="nota" value="<?php echo $nota;?>" />
			<input type="submit" name="btnOk" id="btnOk" class="boton" style="width: 80px"  value="Aceptar" />
			<input type="button" name="btnCancel" id="btnCancel" class="boton" style="width: 80px" value="Cancelar" 
				onclick="javascript: 
					parent.Deshabilita(false);
					parent.CierraDialogo('divContrasena', 'frmContrasena');
				"
			/>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>