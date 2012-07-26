<?php
include '../conexion.inc.php';
$usuario=$_GET["usuario"];
$bodega=$_GET["bodega"];

$estado=$_GET["estado"];
$numero=$_GET["numero"];
if($_GET["accion"]=='F') mssql_query("EXEC sp_CambiaEstado 'FSM', NULL, NULL, '$usuario', NULL,  $numero", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Seguimiento de Solicitud</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getSolicitudMaterial 1, '$bodega', '$estado'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="center"><?php echo $rst["dtmSolicitud"];?></td>
		<td width="10%" align="center"><?php echo $rst["dblNumUsu"];?></td>
		<td width="10%">&nbsp;<?php echo $rst["strUsuario"];?></td>
		<td width="30%">
			<input type="hidden" name="hdnObservacion<?php echo $cont;?>" id="hdnObservacion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strObservacion"]));?>" />
			<input name="txtObservacion<?php echo $cont;?>" id="txtObservacion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strObservacion"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnObservacion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnObservacion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="center">&nbsp;<?php echo $rst["dtmFecha"];?></td>
		<td width="20%">
			<input type="hidden" name="hdnEstado<?php echo $cont;?>" id="hdnEstado<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDescEst"]));?>" />
			<input name="txtEstado<?php echo $cont;?>" id="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDescEst"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnEstado<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnEstado<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="5%">
			<a href="#" title="Para ver el detalle, pinche aquí..."
				onclick="javascript: 
					parent.Deshabilita(true);
					document.getElementById('estado').value=<?php echo $rst["strEstado"];?>;
					document.getElementById('numero').value=<?php echo $rst["dblNumero"];?>;
					parent.document.getElementById('txtNumSM').value='<?php echo $rst["dblNumUsu"];?>';
					parent.document.getElementById('txtSolicitud').value='<?php echo $rst["dblNumero"];?>';
					AbreDialogo('DocVinc', 'ifrmD', 'documentos_vinculados.php?solicitud=<?php echo $rst["dblNumero"];?>&numusu=<?php echo $rst["dblNumUsu"];?>&estado=<?php echo $rst["strEstado"];?>', true);
				"
				onmouseover="javascript: 
					window.status='Para ver el detalle, pinche aquí...'; 
					return true;
				" 
			>Detalle...</a>
		</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="estado" id="estado"/>
<input type="hidden" name="numero" id="numero"/>
</body>
</html>
<?php
mssql_close($cnx);
?>