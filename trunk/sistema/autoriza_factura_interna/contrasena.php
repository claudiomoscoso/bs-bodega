<?php
include '../conexion.inc.php';

$usuario = $_POST["user"];
$clave = $_POST["clave"];
$numero = $_GET["numero"];
$estado = $_GET["estado"];
if($clave != ''){
	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$usuario' AND clave=General.dbo.fn_md5('$clave')", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$error = 0;
		mssql_query("EXEC Bodega..sp_setFacturaInterna 1, '$usuario', NULL, NULL,  NULL, $numero, $estado", $cnx);
	}else
		$error = 1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contraseña</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $clave;?>' != ''){
		if(parseInt('<?php echo $error;?>') == 0){
			parent.CierraDialogo('divContrasena', 'frmContrasena');
			parent.CierraDialogo('divVPrevia', 'frmVPrevia');
			var cargo = parent.document.getElementById('hdnCargo').value;
			var estado = parent.document.getElementById('cmbEstado').value;
			var resultado = parent.document.getElementById('divResultado');
			resultado.innerHTML = '<table border="0" width="100%" height="100%"><tr><td align="center"><img src="../images/cargando2.gif"></td></tr></table>';
			var ajax = new XMLHttpRequest();
			ajax.open('GET', 'resultado.php?cargo=' + cargo + '&estado=' + estado, true);
			ajax.onreadystatechange = function (){
				if(ajax.readyState == 4){
					resultado.innerHTML = ajax.responseText;
					Deshabilita(false);
				}
			}
			ajax.send(null);
		}else if(parseInt('<?php echo $error;?>') == 1)
			alert('El usuario o contraseña ingresada no es valida.')
	}else
		document.getElementById('user').focus();
}
-->
</script>
<body topmargin="5px" onLoad="javascript: Load();">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF']."?numero=$numero&estado=$estado";?>">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
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
	<tr><td colspan="4" valign="bottom" style="height:65px"><hr /></td></tr>
	<tr>
		<td colspan="4" align="right">
			<input type="submit" name="btnOk" id="btnOk" class="boton" style="width: 80px" value="Aceptar" />
			<input type="button" name="btnCancel" id="btnCancel" class="boton" style="width: 80px" value="Cancelar" 
				onclick="javascript: 
					parent.CierraDialogo('divContrasena', 'frmContrasena');
					parent.frmVPrevia.Deshabilita(false);
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