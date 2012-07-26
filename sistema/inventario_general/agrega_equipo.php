<?php
include '../conexion.inc.php';
$ctrl=$_GET["ctrl"]!='' ? $_GET["ctrl"] : $_POST["ctrl"];
$foco=$_GET["foco"]!='' ? $_GET["foco"] : $_POST["foco"];
$consulta=$_GET["consulta"]!='' ? $_GET["consulta"] : $_POST["consulta"];
$accion=$_POST["accion"];
$equipo=$_POST["txtEquipo"];

if($accion=='G'){
	$stmt = mssql_query("SELECT Id FROM Operaciones..Equipo WHERE strDescripcion='$equipo'", $cnx);
	if($rst=mssql_fetch_array($stmt)) $estado=1;
	mssql_free_result($stmt);
	if($estado!=1){
		mssql_query("EXEC Operaciones..sp_setAgregaEquipo '$equipo'", $cnx);
		header("Location: buscar_equipos.php?texto=$consulta&ctrl=$ctrl&foco=$foco");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Agrega Equipo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $existe;?>'=='1') alert('El nombre del equipo que intenta ingresar ya existe')
}

function Guardar(){
	if(document.getElementById('txtEquipo').value==''){
		alert('Debe ingresar la descripción del equipo');
		document.getElementById('txtEquipo').focus();
	}else
		document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load()">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr><th align="left">&nbsp;:: Agrega Equipo</th></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;Equipo:</td></tr>
	<tr>
		<td>
			<input name="txtEquipo" id="txtEquipo" class="txt-plano" style="width:99%" value="<?php echo $equipo;?>" 
				onblur="javascript: CambiaColor(this.id, false);"
				onfocus="javascript: CambiaColor(this.id, true);"
			/>
		</td>
	</tr>
	<tr><td><br /><br /><br /><br /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:80px" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
			<input type="button" name="btnCancel" id="btnCancel" class="boton" style="width:80px" value="Cancelar" 
				onclick="javascript: self.location.href='buscar_equipos.php?texto=<?php echo $consulta;?>&ctrl=<?php echo $ctrl;?>&foco=<?php echo $foco;?>'"
			/>
		</td>
	</tr>
</table>
<input type="hidden" name="accion" id="accion" value="G" />
<input type="hidden" name="ctrl" id="ctrl" value="<?php echo $ctrl;?>" />
<input type="hidden" name="foco" id="foco" value="<?php echo $foco;?>" />
<input type="hidden" name="consulta" id="consulta" value="<?php echo $consulta;?>" />
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>