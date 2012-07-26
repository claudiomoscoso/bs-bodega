<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<script language="javascript">
<!--
function Load(){
	document.getElementById('frmDetalle').setAttribute('height', window.innerHeight - 53);
	document.getElementById('frmDetalle').src="resultado.php";
}

function Siguiente(){
	var totfil=frmDetalle.document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){
		if(frmDetalle.document.getElementById('op'+i).checked){
			var numero=frmDetalle.document.getElementById('numSM'+i).value;
			var bodega=frmDetalle.document.getElementById('bodSM'+i).value;
			break;
		}
	}
	if(numero){
		document.getElementById('numSM').value=numero;
		document.getElementById('bodSM').value=bodega;
		document.getElementById('frm').submit();
	}else
		alert('Debe seleccionar una solicitud de materiales.');
}

function Imprime(){
	var totfil=frmDetalle.document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){
		if(frmDetalle.document.getElementById('op'+i).checked){
			var numero=frmDetalle.document.getElementById('numSM'+i).value;
			break;
		}
	}
	if(numero){
		document.getElementById('imprime').src='imprime_sm.php<?php echo $parametros;?>&numero='+numero;
	}else
		alert('Debe seleccionar una solicitud de materiales.');
}

function Deshabilita(sw){
	document.getElementById('btnImpr').disabled = sw;
	document.getElementById('btnSgte').disabled = sw;
}
-->
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<title>Orden de Compra Autom&aacute;tica</title>
</head>
<body onLoad="javascript: Load();">
<form name="frm" id="frm" method="post" action="solicitud_material.php">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%" align="center">N&deg;</th>
					<th width="20%" align="left">&nbsp;Cargo</th>
					<th width="10%">Numero</th>
					<th width="10%">Fecha</th>
					<th width="35%" align="left">&nbsp;Observaci&oacute;n</th>
					<th width="10%">Fecha Aut.</th>
					<th width="10%" align="center">Seleccionar</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td ><iframe name="frmDetalle" id="frmDetalle" frameborder="0" width="100%" marginheight="0" marginwidth="0" scrolling="yes"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>">
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>">
			<input type="hidden" name="login" id="login" value="<?php echo $login;?>">
			<input type="hidden" name="numSM" id="numSM">
			<input type="hidden" name="bodSM" id="bodSM">
			
			<input type="button" name="btnImpr" id="btnImpr" class="boton" style="width:90px" disabled="disabled" value="Imprimir" 
				onclick="javascript: Imprime();"
			/>
			<input type="button" name="btnSgte" id="btnSgte" class="boton" style="width:90px" disabled="disabled" value="Siguiente &gt;&gt;" 
				onClick="javascript: Siguiente();"
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="imprime" id="imprime" frameborder="0" height="0px" width="0px"></iframe>
</body>
</html>
