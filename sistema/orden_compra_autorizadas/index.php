<?php
include '../conexion.inc.php';
$usuario=$_GET["strID"];
$bodega=$_GET["strBodega"];
$numero=$_POST["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ordenes de Compra Autorizadas</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function DialogoDetalle(numero){
	document.getElementById('numero').value=numero;
	document.getElementById('detalle').style.visibility='visible';
	document.getElementById('ifrm').src='orden_compra.php?numero='+numero;
}

function CierraDialogoDetalle(){
	document.getElementById('ifrm').src='../cargando.php';
	document.getElementById('detalle').style.visibility='hidden';
	document.getElementById('numero').value='';
}

function Imprimir(){
	var numero=document.getElementById('numero').value;
	document.getElementById('imprime').src='imprime.php?usuario=<?php echo $usuario;?>&numero='+numero;
}

function CambiaColor(ctrl, foco){
	if(foco){ 
		document.getElementById(ctrl).style.backgroundColor = '#EBF1FF';
		document.getElementById(ctrl).select();
	}else
		document.getElementById(ctrl).style.backgroundColor = '#FFFFFF';
}

function Load(){
	var totfil=document.getElementById('totfil').value;
	if(totfil>30)
		document.getElementById('tbl').width='99%';
	else
		document.getElementById('tbl').width='100%';
	var height=screen.height-400;
	document.getElementById('divDet').style.height=height;
}
-->
</script>
<body onLoad="javascript: Load()">

<div id="detalle" style="z-index:500; position:absolute; top:1%; left:5%; width:90%; visibility:hidden">
<table border="1" width="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0" background="../images/borde_med.png">
							<tr>
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: CierraDialogoDetalle();"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>								</td>
								<td align="center"><b>Orden de Compra</b></td>
							</tr>
						</table>					</td>
				</tr>
				<tr><td><iframe name="ifrm" id="ifrm" frameborder="0" scrolling="no" width="100%" height="335px" marginwidth="0" marginheight="0" src="../cargando.php"></iframe></td></tr>
				<tr><td><hr></td></tr>
				<tr>
					<td align="right"><input type="button" name="btnImpr" id="btnImpr" value="Imprime" class="boton" style="width:90px" 
							onclick="javascript: Imprimir();" />
					  <input type="button" name="btnCerrar" id="btnCerrar" value="Cerrar" class="boton" style="width:100px" 
							onClick="javascript: CierraDialogoDetalle();"
						>				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<th width="5%">N&deg;</th>
		<th width="25%" align="left">&nbsp;Cargo</th>
		<th width="5%">N&uacute;mero</th>
		<th width="5%">Fecha</th>
		<th width="35%" align="left">&nbsp;Observaci&oacute;n</th>
		<th width="10%">Fecha Sol.</th>
		<th width="10%">Autoriza</th>
	</tr>
</table>

<div id="divDet" style="z-index: 0;position:relative; width:100%; height:330px; overflow:auto">
<table id="tbl" border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
<?php
if($bodega==12000) $strTipo='O'; else $strTipo='A';
$stmt = mssql_query("EXEC sp_getOrdenCompra 'OCA', NULL, '$strTipo'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="5%" align="center">
			<a href="#" 
				onclick="javascript: DialogoDetalle('<?php echo $rst["dblNumero"];?>');"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			><?php echo $cont;?></a></td>
		<td width="25%" align="left">&nbsp;
			<a href="#" 
				onclick="javascript: DialogoDetalle('<?php echo $rst["dblNumero"];?>');"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			><?php echo $rst["Cargo"];?></a>
		</td>
		<td width="5%" align="center">
			<a href="#" 
				onclick="javascript: DialogoDetalle('<?php echo $rst["dblNumero"];?>');"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			><?php echo $rst["dblUltima"];?></a>
		</td>
		<td width="5%" align="center">
			<a href="#" 
				onclick="javascript: DialogoDetalle('<?php echo $rst["dblNumero"];?>');"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			><?php echo $rst["dtmFch"];?></a>
		</td>
		<td width="35%" align="left">&nbsp;
			<a href="#" 
				onclick="javascript: DialogoDetalle('<?php echo $rst["dblNumero"];?>');"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			><?php echo $rst["strObservacion"];?></a>
		</td>
		<td width="10%" align="center">&nbsp;
			<a href="#" 
				onclick="javascript: DialogoDetalle('<?php echo $rst["dblNumero"];?>');"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			><?php echo $rst["dtmSolicitud"];?></a>
		</td>
		<td width="10%" align="center">&nbsp;
			<a href="#" 
				onclick="javascript: DialogoDetalle('<?php echo $rst["dblNumero"];?>');"
				onmouseover="javascript: window.status='Orden de Compra Nº <?php echo $rst["dblUltima"];?>'; return true"
			><?php echo $rst["strAutoriza"];?></a>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);?>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
<input type="hidden" name="numero" id="numero">
</table>
</div>

<iframe name="imprime" id="imprime" width="0px" height="0px" frameborder="0"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>