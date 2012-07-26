<?php
include '../conexion.inc.php';

$userlog = $_GET["userlog"];
$bodega = $_GET["bodega"];
$estado = $_GET["estado"];
$vigente = $_GET["vigente"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administraci&oacute;n de Usuarios</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var idInt = 0;

function Load(){ idInt = setInterval("Recargar()", 10000);}

function UnLoad(){ clearInterval(idInt);}

function Recargar(){ self.location.reload();}

function CambiaEstado(tipo, usuario, estado){
	if(tipo=='VGT'){
		estado = estado ? 1 : 0;
		parent.document.getElementById('transaccion').src='transaccion.php?userlog=<?php echo $userlog;?>&tipo='+tipo+'&usuario='+usuario+'&estado='+estado;
	}else if(tipo=='CNX')
		parent.document.getElementById('transaccion').src='transaccion.php?userlog=<?php echo $userlog;?>&tipo='+tipo+'&usuario='+usuario;
		
}
-->
</script>

<body marginheight="0" marginwidth="0" 
	onload="javascript: Load();" 
	onunload="javascript: UnLoad();"
>
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC General..sp_getUsuarios 0, '$bodega', '$estado', '$vigente'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="left">&nbsp;
			<a href="#" title="Ver la &uacute;ltima actividad del usuario."
				onclick="javascript:
					parent.Deshabilitar(true);
					AbreDialogo('divUsuario', 'frmUsuario', 'usuarios.php?usuario=<?php echo trim($rst["usuario"]);?>', true);
				"
				onmouseover="javascript: AbreDialogo('divActividad', 'frmActividad', 'ultima_actividad.php?usuario=<?php echo trim($rst["usuario"]);?>', true);"
				onmouseout="javascript: parent.CierraDialogo('divActividad', 'frmActividad');"
			><?php echo trim($rst["usuario"]);?></a>
		</td>
		<td width="20%" align="left">&nbsp;<?php echo $rst["nombre"];?></td>
		<td width="10%" align="left">&nbsp;<?php echo $rst["perfil"];?></td>
		<td width="25%" align="left">&nbsp;<?php echo $rst["strDescBodega"];?></td>
		<td width="10%" align="center"><?php if($rst["dtmFch"]!='') echo formato_fecha($rst["dtmFch"], false, false); else echo '--';?></td>
		<td width="8%" align="center"><?php if($rst["login"]=='' || $rst["dblSesion"]>2) echo 'Desconectado'; else echo 'Conectado';?></td>
		<td width="5%" align="center">
			<input type="checkbox" name="chkVigente<?php echo $cont;?>" id="chkVigente<?php echo $cont;?>" class="boton" style="width:100%" 
			<?php echo $rst["vigente"]==1 ? 'checked="checked"' : '';?> 
				onclick="javascript: CambiaEstado('VGT', '<?php echo trim($rst["usuario"]);?>', this.checked);"
			/>
		</td>
		<td width="7%" align="right">
			<input type="button" name="btnLogOut<?php echo $cont;?>" id="btnLogOut<?php echo $cont;?>" class="boton" style="width:60px" value="Logout" 
			<?php echo ($rst["login"]=='' || $rst["dblSesion"]>2) ? ' disabled="disabled"' : '';?>
				onclick="javascript: CambiaEstado('CNX', '<?php echo trim($rst["usuario"]);?>');"
			/>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
