<?php
include '../conexion.inc.php';
include '../globalvar.inc.php';

$usuario = $_GET["usuario"];
$perfil = $_GET["perfil"];
$mes = $_GET["mes"];
$ano = $_GET["ano"];
$numdoc = $_GET["numdoc"] != '' ? $_GET["numdoc"] : 'NULL';
$numoc = $_GET["numoc"] != '' ? $_GET["numoc"] : 'NULL';
$sing = $_GET["sing"];
$proveedor = $_GET["proveedor"];
$cargo = $_GET["cargo"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recepci&oacute;n de Facturas</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo=0;

function Load(){
	parent.Deshabilita(false);
}

function MuestraArchivo(url){
	parent.Deshabilita(true);
	AbreDialogo('divPreview', 'frmPreview', '<?php echo $dtn_documento;?>/' + url, true);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getFacturas 0, $mes, $ano, $numdoc, '$proveedor', $numoc, 0, $sing, '$cargo'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr bgcolor="<?php echo $cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE';?>">
		<td width="2%" align="center">
		<?php 
		if(trim($rst["strArchivo"]) != ''){
			echo '<a href="#" title="Abre archivo adjunto..." ';
			echo 'onclick="javascript: MuestraArchivo(\''.$rst["strArchivo"].'\')"';
			echo 'onmouseover="window.status=\'Abre archivo adjunto...\'; return true;"';
			echo 'onmouseout="window.status=\'\'; return true;"';
			echo '><img border="0" align="absmiddle" src="../images/archivo.gif" /></a>';
		}else
			echo '&nbsp;';
		?>
		</td>
		<td width="5%" align="center"><?php echo $rst["dblNumero"];?></td>
		<td width="8%" align="center"><?php echo $rst["dtmFecha"];?></td>
		<td width="9%" align="center"><?php echo $rst["dblUltima"]!='' ? $rst["dblUltima"] : '--';?></td>
		<td width="25%" align="left">
			<input type="hidden" name="hdnNota<?php echo $cont;?>" id="hdnNota<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strNombProv"]));?>" />
			<input name="txtNota<?php echo $cont;?>" id="txtNota<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strNombProv"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNota<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnNota<?php echo $cont;?>');"
			/>
		</td>
		<td width="10%" align="center">
			<input type="hidden" name="hdnTipoDoc<?php echo $cont;?>" id="hdnTipoDoc<?php echo $cont;?>" value="&nbsp;<?php echo (trim($rst["TipoDoc"]));?>" />
			<input name="txtTipoDoc<?php echo $cont;?>" id="txtTipoDoc<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo (trim($rst["TipoDoc"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnTipoDoc<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnTipoDoc<?php echo $cont;?>');"
			/>
		</td>
		<td width="9%" align="center">
			<a href="#" title="Abre cuadro de dialogo."
				onclick="javascript:
					parent.Deshabilita(true);
					AbreDialogo('divFactura','frmFactura','<?php echo $rst["dblUltima"] != '' ? 'factura.php' : 'factura_sin_oc.php';?>?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&numero=<?php echo $rst["dblNumero"];?>&fecha=<?php echo $rst["dtmFecha"];?>', true);
				"
			><?php echo $rst["dblNumDoc"];?></a>
		</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblMonto"], 0,'','.');?>&nbsp;</td>
		<td width="10%" align="left">
			<input type="hidden" name="hdnNombre<?php echo $cont;?>" id="hdnNombre<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["nombre"]));?>" />
			<input name="txtNombre<?php echo $cont;?>" id="txtNombre<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["nombre"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombre<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnNombre<?php echo $cont;?>');"
			/>
		</td>
		<td width="10%">
			<input type="hidden" name="hdnEstado<?php echo $cont;?>" id="hdnEstado<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDescEstado"]));?>" />
			<input name="txtEstado<?php echo $cont;?>" id="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDescEstado"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnEstado<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnEstado<?php echo $cont;?>');"
			/>
		</td>
	</tr>
<?php
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>