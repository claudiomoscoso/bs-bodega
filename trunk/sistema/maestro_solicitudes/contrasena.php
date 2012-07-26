<?php
include '../conexion.inc.php';

$usuario=$_GET["usuario"] !='' ? $_GET["usuario"] : $_POST["usuario"];
$estado = $_GET["estado"] !='' ? $_GET["estado"] : $_POST["estado"];
$solicitud = $_GET["solicitud"] !='' ? $_GET["solicitud"] : $_POST["solicitud"];
$desde = $_GET["desde"] !='' ? $_GET["desde"] : $_POST["desde"];
$comentario = $_GET["comentario"] !='' ? $_GET["comentario"] : $_POST["comentario"];
$clave = $_POST["clave"];
$error = -1;
if($clave != ''){
	$error = 0;
	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario = '$usuario' AND clave = General.dbo.fn_md5('$clave')", $cnx);
	if(!$rst=mssql_fetch_array($stmt)) $error = 2;
	mssql_free_result($stmt);
	
	if($error == 0){
		if($estado == 1){
			if(ComparaFechas($desde, '<', date('d/m/Y'), '')) $error = 3;
		}
		
		if($error == 0){
			$stmt = mssql_query("EXEC Operaciones..sp_setCambiaEstado 0, '$usuario', $solicitud, '$estado', NULL, '".Reemplaza($comentario)."'", $cnx);
			if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
			mssql_free_result($stmt);
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud de Maquinaria y Equipos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if(parseInt('<?php echo $error;?>') == -1){
		document.getElementById('user').focus();
	}else if(parseInt('<?php echo $error;?>') == 0){
		parent.Deshabilita(false);
		parent.CierraDialogo('divContrasena', 'frmContrasena');
		parent.CierraDialogo('divSolicitud', 'frmSolicitud');
		parent.document.getElementById('pendientes').src = parent.document.getElementById('pendientes').src;
	}else if(parseInt('<?php echo $error;?>') == 2){
		alert('El usuario o la contraseña ingresada no son validos.');
		document.getElementById('user').value = '';
		document.getElementById('clave').value = '';
		document.getElementById('user').focus();
	}else if(parseInt('<?php echo $error;?>') == 1 || parseInt('<?php echo $error;?>') == 3){
		alert('No es posible dar VºBº fuera de plazo. Comuniquese con el jefe de operaciones.');
		parent.Deshabilita(false);
		parent.CierraDialogo('divContrasena', 'frmContrasena');
		parent.CierraDialogo('divSolicitud', 'frmSolicitud');
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
	</tr>
	<tr>
		<td width="7%" align="left">&nbsp;Contrase&ntilde;a</td>
		<td width="1%">:</td>
		<td align="left"><input type="password" name="clave" id="clave" class="txt-plano" style="width:99%" /></td>
	</tr>
	<tr><td colspan="3"><hr /></td></tr>
	<tr>
		<td colspan="3" align="right">
		<?php
		$stmt = mssql_query("SELECT dtmDesde FROM Operaciones..Solicitudes WHERE dblNumero = $solicitud AND ((CONVERT(VARCHAR, dtmDesde, 103) = CONVERT(VARCHAR, GETDATE(), 103) AND DATEPART(dw, dtmDesde) = 6 AND  DATEPART(dw, GETDATE()) = 6
		AND CONVERT(VARCHAR, GETDATE(), 108) >= '17:00') OR dtmDesde < CONVERT(DATETIME, CONVERT(VARCHAR, GETDATE(), 103)))", $cnx);
		if($rst = mssql_fetch_array($stmt)) echo '1'; else echo '0';
		mssql_free_result($stmt);
		?>
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="estado" id="estado" value="<?php echo $estado;?>" />
			<input type="hidden" name="solicitud" id="solicitud" value="<?php echo $solicitud;?>" />
			<input type="hidden" name="desde" id="desde" value="<?php echo $desde;?>" />
			<input type="hidden" name="comentario" id="comentario" value="<?php echo $comentario;?>" />
			
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