<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"] !='' ? $_GET["usuario"] : $_POST["usuario"];
$estado = $_GET["estado"] !='' ? $_GET["estado"] : $_POST["estado"];
$numero = $_GET["numero"] !='' ? $_GET["numero"] : $_POST["numero"];
$numsol = $_GET["numsol"] != '' ? $_GET["numsol"] : $_POST["numsol"];
$observacion = $_GET["observacion"] !='' ? $_GET["observacion"] : $_POST["observacion"];
$clave = $_POST["clave"];
$error = 0;
if($clave != ''){ 
//	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$usuario' AND clave=General.dbo.fn_md5('$clave') AND NOT firma IS NULL", $cnx);
	$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$usuario' AND NOT firma IS NULL", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		if($estado == 'A')
			$est_oc = 2;
		elseif($estado == 'R')
			$est_oc = 4;
		elseif($estado == 'N')
			$est_oc = 5;
		
		mssql_query("EXEC sp_CambiaEstado 'OC', $numero, $est_oc, '$usuario', '', $numsol", $cnx);
		mssql_query("EXEC sp_GrabaObservacion $numero,'OC','".Reemplaza($observacion)."','$usuario',$est_oc", $cnx);
		$error = 1;
	}else{
		$error = 2;
	}
	mssql_free_result($stmt);
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
	if(parseInt('<?php echo $error;?>') == 0){
		document.getElementById('user').focus();
		document.getElementById('user').value='<?php echo $usuario;?>';
		document.getElementById('clave').value='1234567890';
	}else if(parseInt('<?php echo $error;?>') == 1){
		parent.CierraDialogo('divOCompra', 'frmOCompra');
		parent.CierraDialogo('divContrasena', 'frmContrasena');
		parent.document.getElementById('frmAdquisiciones').src = parent.document.getElementById('frmAdquisiciones').src;
		parent.document.getElementById('frmOperaciones').src = parent.document.getElementById('frmOperaciones').src;
		parent.Deshabilita(false);
	}else if(parseInt('<?php echo $error;?>') == 2){
		alert('El usuario o contraseña ingresada no es valida.')
		document.getElementById('user').focus();
	}
}
-->
</script>
<body topmargin="5px" onLoad="javascript: Load();">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
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
	<tr><td colspan="4"><hr /></td></tr>
	<tr>
		<td colspan="4" align="right">
			<input type="submit" name="btnOk" id="btnOk" class="boton" style="width: 80px" value="Aceptar" />
			<input type="button" name="btnCancel" id="btnCancel" class="boton" style="width: 80px" value="Cancelar" 
				onclick="javascript: 
					parent.CierraDialogo('divContrasena', 'frmContrasena');
					parent.Deshabilita(false);
				"
			/>
		</td>
	</tr>
</table>
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="estado" id="estado" value="<?php echo $estado;?>" />
<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>" />
<input type="hidden" name="numsol" id="numsol" value="<?php echo $numsol;?>" />
<input type="hidden" name="observacion" id="observacion" value="<?php echo $observacion;?>" />

</form>
</body>
</html>
<?php
mssql_close($cnx);
?>