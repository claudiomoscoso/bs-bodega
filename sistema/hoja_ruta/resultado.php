<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$contrato = $_GET["contrato"];
$imprime = $_GET["imprime"];
$orden = $_GET["orden"] != '' ? $_GET["orden"] : 'NULL';
$finicio = $_GET["finicio"] != '' ? "'".formato_fecha($_GET["finicio"], false, true)."'" : 'NULL';
$ftermino = $_GET["ftermino"] != '' ? "'".formato_fecha($_GET["ftermino"], false, true)."'" : 'NULL';
$cc = $_GET["cc"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hoja de Ruta</title>
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
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo $imprime, '$usuario', '$contrato', NULL, $orden, NULL, NULL, NULL, NULL, NULL, $finicio, $ftermino", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#FFFFE5';?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="2%" align="center">
		<?php 
		if($rst["intHP"] == 1){?>
			<img border="0" align="middle" src="../images/urgente.gif" />
		<?php
		}
		?>
		</td>
		<td width="10%" align="center"><?php echo $rst["dblNumero"];?></td>
		<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
		<td width="10%" align="center"><?php echo $rst["strOrden"];?></td>
		<td width="20%" >
			<input type="hidden" name="hdnComuna<?php echo $cont;?>" id="hdnComuna<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDescComuna"]));?>" />
			<input name="txtComuna<?php echo $cont;?>" id="txtComuna<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#FFFFE5';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDescComuna"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnComuna<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnComuna<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="38%" align="left">
			<input type="hidden" name="hdnNombre<?php echo $cont;?>" id="hdnNombre<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strMotivo"]));?>" />
			<input name="txtNombre<?php echo $cont;?>" id="txtNombre<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#FFFFE5';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strMotivo"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombre<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnNombre<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="2%" align="center">
			<input type="hidden" name="txtCorr<?php echo $cont;?>" id="txtCorr<?php echo $cont;?>" class="txt-sborde" style="width:100%; background:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#FFFFE5';?>; text-align:center" readonly="true" value="<?php echo $rst["dblCorrelativo"];?>" />
			<input type="checkbox" name="chk<?php echo $cont;?>" id="chk<?php echo $cont;?>" 
				onclick="javascript: parent.setSelecciona(this, <?php echo $cont;?>);"
			/>
		</td>
	</tr>
<?php
	}while($rst = mssql_fetch_array($stmt));
}else{
	 echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
