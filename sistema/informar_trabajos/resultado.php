<?php
include '../conexion.inc.php';

$contrato=$_GET["contrato"];
$movil=$_GET["movil"]!= 'none' ? "'".$_GET["movil"]."'" : 'NULL';
$orden=$_GET["orden"] != '' ? $_GET["orden"] : 'NULL';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Informae Trabajos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo=0;

function Load(){
	var totfil = document.getElementById('totfil').value;
	parent.setDeshabilita(false);
	for(i=1; i<=totfil; i++) document.getElementById('chk' + i).disabled = false;
}

function setSelecciona(idChk, valor){
	var totfil=document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){ document.getElementById('chk'+i).checked=false;}
	document.getElementById(idChk).checked=valor;
}
-->
</script>
<body marginwidth="0" marginheight="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo 5, '', '$contrato', $movil, $orden", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="10%" align="center"><?php echo $rst["strOrden"];?></td>
		<td width="8%" align="center"><?php echo $rst["dtmFchOrden"];?></td>
		<td width="5%" align="center"><?php echo $rst["strMovil"];?></td>
		<td width="8%" align="center"><?php echo $rst["dtmFchVcto"];?></td>
		<td width="19%" align="left">
			<input type="hidden" name="hdnDireccion<?php echo $cont;?>" id="hdnDireccion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDireccion"]));?>" />
			<input name="txtDireccion<?php echo $cont;?>" id="txtDireccion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDireccion"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDireccion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDireccion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="16%" align="left">
			<input type="hidden" name="hdnComuna<?php echo $cont;?>" id="hdnComuna<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDescComuna"]));?>" />
			<input name="txtComuna<?php echo $cont;?>" id="txtComuna<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDescComuna"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnComuna<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnComuna<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="19%" align="left">
			<input type="hidden" name="hdnMotivo<?php echo $cont;?>" id="hdnMotivo<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strMotivo"]));?>" />
			<input name="txtMotivo<?php echo $cont;?>" id="txtMotivo<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strMotivo"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnMotivo<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnMotivo<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="center">
			<input type="hidden" id="hdnNumOT<?php echo $cont;?>" value="<?php echo $rst["dblCorrelativo"];?>" />
			<input type="checkbox" id="chk<?php echo $cont;?>" disabled="disabled" 
				onclick="javascript: setSelecciona(this.id, this.checked);"
			/>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>