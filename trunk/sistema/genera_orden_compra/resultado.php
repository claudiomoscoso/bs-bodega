<?php
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Genera Orden de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo=0;

function Load(){
	parent.Deshabilita(false);
}

function Selecciona(idChk, valor){
	var totfil=document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){ document.getElementById('op'+i).checked=false;}
	document.getElementById(idChk).checked=valor;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="1">
<?php	
$stmt = mssql_query("EXEC sp_getSolicitudMaterial 0", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>	
	<tr <?php echo ($cont % 2)==0 ? 'bgcolor="#FFFFFF"' : 'bgcolor="#EBF3FE"';?>>
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="20%" >
			<input type="hidden" name="hdnDetalle<?php echo $cont;?>" id="hdnDetalle<?php echo $cont;?>" value="<?php echo htmlentities($rst["strDetalle"]);?>" />
			<input name="txtDetalle<?php echo $cont;?>" id="txtDetalle<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities($rst["strDetalle"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDetalle<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDetalle<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="center"><?php echo $rst["dblNumUsu"];?></td>
		<td width="10%" align="center"><?php echo $rst["dtmSolicitud"];?></td>
		<td width="35%">
			<input type="hidden" name="hdnNota<?php echo $cont;?>" id="hdnNota<?php echo $cont;?>" value="<?php echo htmlentities($rst["strObservacion"]);?>" />
			<input name="txtNota<?php echo $cont;?>" id="txtNota<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities($rst["strObservacion"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNota<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnNota<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="center"><?php echo $rst["dtmFecha"];?></td>
		<td width="10%" align="center">
			<input type="hidden" name="bodSM<?php echo $cont;?>" id="bodSM<?php echo $cont;?>" value="<?php echo $rst["strBodega"];?>" />
			<input type="hidden" name="numSM<?php echo $cont;?>" id="numSM<?php echo $cont;?>" value="<?php echo $rst["dblNumero"];?>" />
			<input type="checkbox" name="op<?php echo $cont;?>" id="op<?php echo $cont;?>" 
				onclick="javascript: Selecciona(this.id, this.checked);"
			/>
		</td>
	</tr>
<?php	
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>