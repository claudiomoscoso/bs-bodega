<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"] !='' ? $_GET["usuario"] : $_POST["usuario"];
$numero = $_GET["numero"] !='' ? $_GET["numero"] : $_POST["numero"];
$estado = $_GET["estado"] !='' ? $_GET["estado"] : $_POST["estado"];
$clave = $_POST["clave"];

if($clave != ''){ 
	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario = '$usuario' AND clave = General.dbo.fn_md5('$clave')", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		mssql_query("EXEC Bodega..sp_CambiaEstado 'GD', $numero, $estado, '$usuario'", $cnx);
		?>
		<script language="javascript">
			parent.Deshabilita(false);
			parent.frmSolicitud.Deshabilita(false);
			parent.CierraDialogo('divContrasena', 'frmContrasena');
			parent.CierraDialogo('divSolicitud', 'frmSolicitud');
			
			parent.document.getElementById('detalle').src = parent.document.getElementById('detalle').src;
		</script>
		<?php
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Autoriza Solicitud de Devoluci&oacute;n</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
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
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="estado" id="estado" value="<?php echo $estado;?>" />
			<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>" />
			
			<input type="submit" name="btnOk" id="btnOk" class="boton" style="width: 80px"  value="Aceptar" />
			<input type="button" name="btnCancel" id="btnCancel" class="boton" style="width: 80px" value="Cancelar" 
				onclick="javascript: 
					parent.frmSolicitud.Deshabilita(false);
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
