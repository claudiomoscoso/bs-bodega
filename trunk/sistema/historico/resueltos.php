<?php
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Solicitud(valor){
	self.location.href='solicitud.php?solicitud='+valor;
}

function AbreDialogo(idTxt){
	document.getElementById('observacion').value=document.getElementById(idTxt).value;
	DlgObs.style.visibility='visible';
}

function CierraDialogo(){
	DlgObs.style.visibility='hidden';
}

-->
</script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div id="DlgObs" style="z-index:1000; position:absolute; top:5%; left:30%; width:40%; height:150px; visibility:hidden">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFCC">
	<tr>
		<td>
			<textarea name="observacion" id="observacion" class="txt-plano" style="width:99%; height:150px; background-color:#FFFFCC" readonly="readonly"></textarea>
		</td>
	</tr>
</table>
</div>

<div id="resueltos" style="position:absolute; left:0px; top:0px; width:100%; overflow:scroll">
<table border="0" cellpadding="1" cellspacing="0">
<?php
$stmt = mssql_query("EXEC Operaciones..sp_getSolicitudes 'LSR'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#EBF1FF' : '#FFFFFF' ?>">
		<td width="2%" align="center"><?php echo $cont;?></td>
		<td width="5%" align="center">
			<a style="color:#0033FF; cursor: hand"
				onmouseover="javascript: AbreDialogo('observ<?php echo $cont;?>');"
				onmouseout="javascript: CierraDialogo();"
			><?php echo $rst["dblNumero"];?></a>
			<input type="hidden" name="observ<?php echo $cont;?>" id="observ<?php echo $cont;?>" value="<?php echo "Observacion:\r".$rst["strObservacion"]."\r\rComentarios:\r".$rst["strComentarios"];?>" />
		</td>
		<td width="5%" align="center"><?php echo $rst["dtmFecha"];?></td>
		<td width="15%" align="left">&nbsp;<?php echo $rst["strDetalle"];?></td>
		<td width="15%" align="left">&nbsp;<?php echo $rst["strSolicitante"];?></td>
		<td width="15%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
		<td width="5%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="8%" align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
		<td width="5%" align="center"><?php echo $rst["dtmDesde"];?></td>
		<td width="5%" align="center"><?php echo $rst["dtmHasta"];?></td>
		<td width="8%" align="center">
		<?php 
		if($rst["strEstado"]=='3')
			echo '<a style="color:#0033FF; cursor: hand" onmouseover="javascript: AbreDialogo(\'nula'.$cont.'\');" onmouseout="javascript: CierraDialogo();">'.$rst["Estado"].'</a>';
		else
			echo $rst["Estado"];?>
			&nbsp;		
			<input type="hidden" name="nula<?php echo $cont;?>" id="nula<?php echo $cont;?>" value="<?php echo "Anulada por:\r".$rst["nombre"];?>" />
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);
?>
</table>
</div>
</body>
</html>
<script language="javascript">
<!--
resueltos.style.height=(parent.document.getElementById('resueltos').height-95)+'px';
-->
</script>
<?php
mssql_close($cnx);
?>