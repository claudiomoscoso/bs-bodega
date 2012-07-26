<?php
include '../conexion.inc.php';

$numOC=$_GET["numOC"];
$usuario=$_GET["usuario"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Renovaci&oacute;n Ordenes de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Deshabilita(sw){
	var tot = document.getElementById('totfil').value;
	parent.document.getElementById('observacion').disabled = sw;
	parent.document.getElementById('cmbCargo').disabled = sw;
	parent.document.getElementById('btnAtras').disabled = sw;
	parent.document.getElementById('btnRenueva').disabled = sw;
	for(i = 1; i <= tot; i++){
		document.getElementById('imgFchIni' + i).style.visibility = (sw ? 'hidden' : 'visible');
		document.getElementById('imgFchTer' + i).style.visibility = (sw ? 'hidden' : 'visible');
		
	}
	if(!sw){
		var ind = document.getElementById('ind').value;
		var codigo = document.getElementById('txtCodigo' + ind).value;
		var finicio = document.getElementById('txtFInicio' + ind).value;
		var ftermino = document.getElementById('txtFTermino' + ind).value;
		parent.document.getElementById('transaccion').src = 'transaccion.php?usuario=<?php echo $usuario;?>&codigo=' + codigo + '&finicio=' + finicio + '&ftermino=' + ftermino;
	}
}
-->
</script>
<body marginheight="0" marginwidth="0">
<div id="divCalendario" style="position:absolute;width:20%;visibility:hidden;left:40%;top:77px;">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									title="Cierra calendario.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="1" style="border:thin" scrolling="no" width="100%" height="130px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="1">
<?php
$fil=0;
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleOrdenCompra 3, '$usuario'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$fil++;?>
	<tr bgcolor="<?php echo ($fil % 2 ==0 ? '#FFFFFF' : '#EBF3FE');?>">
		<td width="3%" align="center"><?php echo $fil;?></td>
		<td width="10%" align="center"><input name="txtCodigo<?php echo $fil;?>" id="txtCodigo<?php echo $fil;?>" class="txt-sborde" style="width:99%; text-align:center; background-color:<?php echo ($fil % 2 ==0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="<?php echo $rst["strCodigo"];?>" /></td>
		<td width="41%" align="left">&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?></td>
		<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="10%" align="center"><input name="txtFInicio<?php echo $fil;?>" id="txtFInicio<?php echo $fil;?>" class="txt-plano" style="width:99%; text-align:center" readonly="true" value="<?php echo $rst["dtmFchIni"];?>" /></td>
		<td width="2%" align="center">
			<a href="#" title="Abre cuadro calendario."
				onblur="javascript: CambiaImagen('imgFchIni<?php echo $fil;?>', false);"
				onclick="javascript:
					document.getElementById('ind').value = '<?php echo $fil;?>'; 
					Deshabilita(true);
					AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFInicio<?php echo $fil;?>&fecha=' + document.getElementById('txtFInicio<?php echo $fil;?>').value, false, '56%', '22px');
				"
				onfocus="javascript: CambiaImagen('imgFchIni<?php echo $fil;?>', true);"
			><img id="imgFchIni<?php echo $fil;?>" border="0" align="middle" src="../images/aba.gif" /></a>
		</td>
		<td width="10%" align="center"><input name="txtFTermino<?php echo $fil;?>" id="txtFTermino<?php echo $fil;?>" class="txt-plano" style="width:99%; text-align:center" readonly="true" value="<?php echo $rst["dtmFchTer"];?>" /></td>
		<td width="2%" align="center">
			<a href="#" title="Abre cuadro calendario."
				onblur="javascript: CambiaImagen('imgFchTer<?php echo $fil;?>', false);"
				onclick="javascript: 
					document.getElementById('ind').value = '<?php echo $fil;?>'; 
					Deshabilita(true);
					AbreDialogo('divCalendario', 'frmCalendario','../calendario/index.php?ctrl=txtFTermino<?php echo $fil;?>&fecha=' + document.getElementById('txtFTermino<?php echo $fil;?>').value, false, '68%', '22px');
				"
				onfocus="javascript: CambiaImagen('imgFchTer<?php echo $fil;?>', true);"
			><img id="imgFchTer<?php echo $fil;?>" border="0" align="middle" src="../images/aba.gif" /></a>
		</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCAutorizada"], 2, ',', '.');?>&nbsp;</td>
	</tr>
<?php	
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $fil;?>">
<input type="hidden" name="ind" id="ind">
</body>
</html>