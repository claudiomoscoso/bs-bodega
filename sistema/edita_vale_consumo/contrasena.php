<?php
include '../conexion.inc.php';
$usuario=$_GET["usuario"] !='' ? $_GET["usuario"] : $_POST["usuario"];
$numgi=$_GET["numgi"] !='' ? $_GET["numgi"] : $_POST["numgi"];
$numoc=$_GET["numoc"] !='' ? $_GET["numoc"] : $_POST["numoc"];
$obs=$_GET["observacion"] !='' ? $_GET["observacion"] : $_POST["observacion"];
$clave=$_POST["clave"];

if($clave!=''){ 
	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$usuario' AND clave=General.dbo.fn_md5('$clave')", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		mssql_query("EXEC Bodega..sp_CambiaEstado 'GI', $numgi, NULL, '$usuario', NULL, $numoc", $cnx);
		if($obs!='') mssql_query("EXEC sp_GrabaObservacion $numero,'OC','".Reemplaza($obs)."','$usuario'", $cnx);
		$estado=0;
	}else
		$estado=1;
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado Maestro de Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $estado;?>'=='0'){
		parent.Deshabilita(false);
		parent.parent.document.getElementById('frmGuiaIngreso').src='../blank.html';
		parent.parent.document.getElementById('txtNumGI').value='';
	}else if('<?php echo $estado;?>'=='1'){
		alert('El usuario y/o contraseñas no son correctos.');
	}
	document.getElementById('user').focus();
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
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="numgi" id="numgi" value="<?php echo $numgi;?>" />
<input type="hidden" name="numoc" id="numoc" value="<?php echo $numoc;?>" />
<input type="hidden" name="observacion" id="observacion" value="<?php echo $observacion;?>" />
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>