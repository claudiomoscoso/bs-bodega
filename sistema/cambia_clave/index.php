<?php
include '../autentica.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cambiar Clave</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	document.getElementById('clave_ant').focus();
}

function Envia(){
	if(document.getElementById('clave_ant').value==''){
		alert('Debe ingresar la clave antigua.');
		document.getElementById('clave_ant').focus();
	}else if(document.getElementById('clave_nva').value==''){
		alert('Debe ingresar la nueva clave.');
		document.getElementById('clave_nva').focus();
	}else if(document.getElementById('clave_conf').value==''){
		alert('Debe ingresar la confirmación de la nueva clave.');
		document.getElementById('clave_conf').focus();
	}else{
		if(document.getElementById('clave_ant').value==document.getElementById('clave_nva').value){
			alert('La nueva clave debe ser distinta a la clave antigua.');
			document.getElementById('clave_nva').focus();
		}else if(document.getElementById('clave_nva').value!=document.getElementById('clave_conf').value){
			alert('La confirmación de la clave no es correcta.');
			document.getElementById('clave_conf').focus();
		}else
			document.getElementById('frm').submit();
	}
}
-->
</script>
<body topmargin="7">
<form name="frm" id="frm" method="post" action="grabar.php<?php echo $parametros;?>">
<table border="0" bordercolor="#CCCCCC" width="100%" align="center" cellpadding="0" cellspacing="1">
	<tr>
		<td width="10%" align="left" nowrap="nowrap">&nbsp;Clave Antigua</td>
		<td width="1%">:</td>
		<td><input type="password" name="clave_ant" id="clave_ant" class="txt-plano" style="width:100%; text-align:center" /></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%" align="left" nowrap="nowrap">&nbsp;Nueva Clave</td>
		<td width="1%">:</td>
		<td><input type="password" name="clave_nva" id="clave_nva" class="txt-plano" style="width:100%; text-align:center" /></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%" align="left" nowrap="nowrap">&nbsp;Confirmar Clave</td>
		<td width="1%">:</td>
		<td><input type="password" name="clave_conf" id="clave_conf" class="txt-plano" style="width:100%; text-align:center" /></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr><td height="5px" colspan="4"><hr /></td></tr>
	<tr>
		<td height="5px" colspan="4" align="right">
			<input type="button" name="btnOk" id="btnOk" class="boton" style="width:80px" value="Aceptar" 
				onclick="javascript: Envia();"
			/>
		</td>
	</tr>
	<tr><td height="5px" colspan="4"></td></tr>
</table>
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
</form>
</body>
</html>
