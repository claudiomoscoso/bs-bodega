<?php
include '../conexion.inc.php';

$obra = $_GET["obra"];
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
var Intervalo = 0;

function Selecciona(idChk, valor){
	var totfil = document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++) document.getElementById('op' + i).checked = false;
	document.getElementById(idChk).checked = valor;
}

function Load(){
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'ROC', NULL, '%', '$obra'", $cnx);
if($rst=mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr <?php echo ($cont % 2)==0 ? 'bgcolor="#FFFFFF"' : 'bgcolor="#EBF3FE"';?>>
    	<td width="3%" align="center"><?php echo $cont;?></td>
      	<td width="25%" align="left">
			<input type="hidden" name="hdnNombre<?php echo $cont;?>" id="hdnNombre<?php echo $cont;?>" value="<?php echo htmlentities($rst["strNombre"]);?>" />
			<input name="txtNombre<?php echo $cont;?>" id="txtNombre<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo htmlentities($rst["strNombre"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNombre<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnNombre<?php echo $cont;?>');
				"
			/>
		</td>
      	<td width="10%" align="center">
			<a href="#" title="Ver detalle de la orden de compra..."
				onmouseover="javascript: 
					AbreDialogo('divDetalleOC', 'frmDetalleOC', 'resumen_detalle_orden_compra.php?numero=<?php echo $rst["dblNumero"];?>', true);
					window.status='Ver detalle de la orden de compra...'; return true;
				"
				onmouseout="javascript: parent.CierraDialogo('divDetalleOC', 'frmDetalleOC');"
			><?php echo $rst["dblUltima"];?></a>
		</td>
      	<td width="10%" align="center"><?php echo $rst["dtmInicio"];?></td>
		<td width="10%" align="center"><?php echo $rst["dtmTermino"];?></td>
      	<td width="33%" align="left">
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
      	<td width="7%" align="center"><input type="hidden" name="numOC<?php echo $cont;?>" id="numOC<?php echo $cont;?>" value="<?php echo $rst["dblNumero"];?>" />
          	<input type="checkbox" name="op<?php echo $cont;?>" id="op<?php echo $cont;?>" 
				onclick="javascript: Selecciona(this.id, this.checked);"
			/>
      	</td>
	</tr>
    <?php	
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n</b></td></tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>