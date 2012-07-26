<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$numero   = $_GET["numero"] != '' ? "'".$_GET["numero"]."'" : 'NULL';
$fecha    = $_GET["fecha"]!= '' ? "'".$_GET["fecha"]."'" : 'NULL';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reinformar Trabajos y Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo=0;

function Load(){
	var totfil = document.getElementById('totfil').value;
	parent.Deshabilita(false);
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
$stmt = mssql_query("EXEC Orden..sp_getOrdenTrabajo 4, '', '$contrato', NULL, $numero, $fecha", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="3%" align="center"><?php echo $cont;?></td>
		<td width="4%" align="center">
		<?php 
		switch($rst["strEstado"]){
			case '07001':
				$imagen = 'sin_envio.gif';
				break;
			case '07003':
			case '07004':
				$imagen = 'error.gif';
				break;
			case '07005':
			case '37007':
				$imagen = 'ok.gif';
				break;
			case '07009':
			case '07090':
				$imagen = 'nula.gif';
				break;
		}
		?>
			<img border="0" align="middle" src="../images/<?php echo $imagen;?>" />
		</td>
		<td width="8%" align="center"><?php echo $rst["dtmFchOrden"];?></td>
		<td width="10%" align="center"><?php echo $rst["strOrden"];?></td>
		<td width="15%" align="left">
			<input type="hidden" name="hdnInspector<?php echo $cont;?>" id="hdnInspector<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strNombInsp"]));?>" />
			<input name="txtInspector<?php echo $cont;?>" id="txtInspector<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strNombInsp"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnInspector<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnInspector<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="15%" align="left">
			<input type="hidden" name="hdnNombre<?php echo $cont;?>" id="hdnNombre<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim(($rst["strNombre"] != '' ? $rst["strNombre"] : $rst["strMovil"])));?>" />
			<input name="txtNombre<?php echo $cont;?>" id="txtNombre<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim(($rst["strNombre"] != '' ? $rst["strNombre"] : $rst["strMovil"])));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombre<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnNombre<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="8%" align="center"><?php echo $rst["dtmFchVcto"];?></td>
		<td width="25%" align="left">
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
		<td width="12%" align="center">
			<input type="hidden" id="hdnNumOT<?php echo $cont;?>" value="<?php echo $rst["dblCorrelativo"];?>" />
			<input type="checkbox" id="chk<?php echo $cont;?>" disabled="disabled" 
				onclick="javascript: setSelecciona(this.id, this.checked);"
			/>
		</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>