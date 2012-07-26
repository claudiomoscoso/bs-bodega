<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edita T&eacute;rmino de Bodega</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('documento').setAttribute('height', window.innerHeight - 35);
}

function Buscar(){
	if(document.getElementById('txtTermino').value == '')
		alert('Debe ingresar el número del término de bodega.');
	else{
		document.getElementById('frm').setAttribute('target', 'documento');
		document.getElementById('frm').setAttribute('action', 'termino_bodega.php?usuario=<?php echo $usuario;?>');
		document.getElementById('frm').submit();
		Deshabilita(true);
	}
}

function Deshabilita(sw){
	document.getElementById('txtTermino').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
}
-->
</script>
<body onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<form name="frm" id="frm" method="post">
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="7%">&nbsp;N&deg;T&eacute;rmino</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtTermino" id="txtTermino" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="0%">
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: Buscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
		</form>
	</tr>
	<tr><td><hr /></td></tr>
	<tr><td><iframe name="documento" id="documento" width="100%" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="../blank.html"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>