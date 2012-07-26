<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$problema = $_GET["problema"];
$fecha = $_GET["fecha"];
$solucionado = $_GET["solucionado"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Soporte a Usuarios</title>
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
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC General..sp_getSoporte 0, '$usuario', '$problema', '$fecha', $solucionado", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="15%" align="center"><?php echo formato_fecha($rst["dtmFch"], true, false);?></td>
		<td width="20%" align="left">
			&nbsp;
			<a href="#" title="Editar suceso..."
				onclick="javascript: 
					parent.Deshabilita(true);
					AbreDialogo('divSoporte', 'frmSoporte', 'soporte.php?modulo=1&numero=<?php echo $rst["dblNumero"];?>', true);
				"
				onmouseover="javascript: window.status='Editar suceso...'; return true;"
			><?php echo ($rst["strNombre"] != '' ? $rst["strNombre"] : $rst["strSolicitante"]);?></a>
		</td>
		<td width="20%" align="left">
			<input type="hidden" name="hdnMotivo<?php echo $cont;?>" id="hdnMotivo<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strMotivo"]));?>" />
			<input name="txtProblema<?php echo $cont;?>" id="txtProblema<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strMotivo"]));?>"
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnMotivo<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnMotivo<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="25%" align="left">
			<input type="hidden" name="hdnSolucion<?php echo $cont;?>" id="hdnSolucion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strSolucion"]));?>" />
			<input name="txtSolucion<?php echo $cont;?>" id="txtSolucion<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strSolucion"]));?>"
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnSolucion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnSolucion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="15%" align="center"><?php echo ($rst["dtmFSolucion"] != '' ? formato_fecha($rst["dtmFSolucion"], true, false) : '');?></td>
	</tr>
<?php
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>	
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>