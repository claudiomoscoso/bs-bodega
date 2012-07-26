<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$responsable = $_GET["responsable"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Ingreso por Caja Chica</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
}

function Selecciona(idChk, valor){
	var totfil = document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++) document.getElementById('op' + i).checked = false;
	document.getElementById(idChk).checked = valor;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table id="tbl" border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getCajaChica 3, NULL, '$bodega', '$responsable'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;?>
	<tr <?php echo ($cont % 2)==0 ? 'bgcolor="#FFFFFF"' : 'bgcolor="#EBF3FE"';?>>
    	<td width="3%" align="center"><?php echo $cont;?></td>
      	<td width="25%" align="left">&nbsp;<?php echo $rst["strNombre"] != '' ? $rst["strNombre"] : $rst["strCargo"];?></td>
      	<td width="10%" align="center">
			<a href="#" title="Ver detalle de la caja chica..."
				onmouseover="javascript: 
					AbreDialogo('divDetalleCC', 'frmDetalleCC', 'resumen_detalle_caja_chica.php?numero=<?php echo $rst["dblNumero"];?>&factor=<?php echo $rst["dblFactor"];?>', true);
					window.status='Ver detalle de la caja chica...'; return true;
				"
				onmouseout="javascript: parent.CierraDialogo('divDetalleCC', 'frmDetalleCC');"
			><?php echo $rst["dblNum"];?></a>
		</td>
      	<td width="10%" align="center"><?php echo $rst["dtmFch"];?></td>
      	<td width="20%" align="left">
	  		<input type="hidden" name="hdnNota<?php echo $cont;?>" id="hdnNota<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strNota"]);?>" />
			<input name="txtNota<?php echo $cont;?>" id="txtNota<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strNota"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNota<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnNota<?php echo $cont;?>');
				"
			/>
		</td>
      	<td width="10%" align="center"><?php echo $rst["dtmFchEnv"] != '' ? $rst["dtmFchEnv"] : 'S.Registro';?></td>
      	<td width="10%" align="center">
			<input type="hidden" name="hdnEstado<?php echo $cont;?>" id="hdnEstado<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strDescEstado"]);?>" />
			<input name="txtEstado<?php echo $cont;?>" id="txtEstado<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strDescEstado"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnEstado<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnEstado<?php echo $cont;?>');
				"
			/>
		</td>
      	<td width="10%" align="center">
			<input type="hidden" name="numCC<?php echo $cont;?>" id="numCC<?php echo $cont;?>" value="<?php echo $rst["dblNumero"];?>" />
          	<input type="checkbox" name="op<?php echo $cont;?>" id="op<?php echo $cont;?>" 
				onclick="javascript: Selecciona(this.id, this.checked);"
			/>
      	</td>
	</tr>
    <?php	
	}while($rst=mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>