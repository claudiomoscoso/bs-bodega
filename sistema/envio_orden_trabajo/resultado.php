<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$contrato = $_GET["contrato"];
$inspector = $_GET["inspector"];
$zona = $_GET["zona"];
$nenvio = $_GET["nenvio"] != '' ? $_GET["nenvio"] : 'NULL';
$orden = $_GET["orden"] != '' ? $_GET["orden"] : 'NULL';
if($modulo == 0){
	mssql_query("EXEC Orden..sp_getTMPEnvioOrdenTrabajo 0, '$usuario', '$contrato', $nenvio, $orden", $cnx);
	$sql = "EXEC Orden..sp_getTMPEnvioOrdenTrabajo 1, '$usuario'";
}elseif($modulo == 1){
	mssql_query("EXEC Orden..sp_getTMPEnvioOrdenTrabajo 2, '$usuario', '$contrato', NULL, NULL, '$inspector', '$zona'", $cnx);
	$sql = "EXEC Orden..sp_getTMPEnvioOrdenTrabajo 3, '$usuario'";
}elseif($modulo == 2){
	mssql_query("EXEC Orden..sp_getTMPEnvioOrdenTrabajo 4, '$usuario', '$contrato'", $cnx);
	$sql = "EXEC Orden..sp_getTMPEnvioOrdenTrabajo 5, '$usuario'";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Envio Orden de Trabajo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
	if(parseInt('<?php echo $modulo;?>') == 1) 
		parent.document.getElementById('txtEPago').focus();
	else if(parseInt('<?php echo $modulo;?>') == 2){ 
		//parent.document.getElementById('txtEPago').value = document.getElementById('hdnEPago').value;
		parent.document.getElementById('txtEPago').disabled = true;
		parent.document.getElementById('cmbInspector').disabled = true;
	}
	if(parseInt(document.getElementById('hdnChkAll').value) == 1) 
		parent.document.getElementById('chkAll').style.visibility = 'visible';
	else
		parent.document.getElementById('chkAll').style.visibility = 'hidden';
}

function Elige(correlativo, checked){
	if(parseInt('<?php echo $modulo;?>') == 0)
		parent.document.getElementById('transaccion').src = 'transaccion.php?modulo=1&usuario=<?php echo $usuario;?>&correlativo=' + correlativo + '&envia=' + (checked ? 1 : 0);
	else if(parseInt('<?php echo $modulo;?>') == 1)
		parent.document.getElementById('transaccion').src = 'transaccion.php?modulo=3&usuario=<?php echo $usuario;?>&correlativo=' + correlativo + '&envia=' + (checked ? 1 : 0);
	else if(parseInt('<?php echo $modulo;?>') == 2)
		parent.document.getElementById('transaccion').src = 'transaccion.php?modulo=6&usuario=<?php echo $usuario;?>&correlativo=' + correlativo + '&envia=' + (checked ? 1 : 0);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$chkall = 0;
$stmt = mssql_query($sql, $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr style="background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="1%" align="center">';
		if($rst["dblCerrada"] == 0){
			$chkall = 1;
			echo '<input type="checkbox" name="chkOrden'.$cont.'" id="chkOrden'.$cont.'" disabled="disabled" '.($modulo == 0 ? 'checked="checked"' : '');
			echo 'onclick="javascript: Elige('.$rst["dblCorrelativo"].', this.checked);"';
			echo '/>';
			echo '</td>';
		}
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="9%" align="center">'.trim($rst["strOrden"]).'</td>';
		echo '<td width="15%" align="center">'.formato_fecha($rst["dtmOrden"], true, false).'</td>';
		echo '<td width="23%" align="left">';
		echo '<input type="hidden" name="hdnDireccion'.$cont.'" id="hdnDireccion'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strDireccion"])).'" />';
		echo '<input name="txtDireccion'.$cont.'" id="txtDireccion'.$cont.'" class="txt-sborde" style="width:99%; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.htmlentities(trim($rst["strDireccion"])).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnDireccion".$cont."\')', 250);";
		echo '" ';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnDireccion".$cont."');";
		echo '"';
		echo '/>';
		echo '</td>';
		echo '<td width="23%" align="left">';
		echo '<input type="hidden" name="hdnComuna'.$cont.'" id="hdnComuna'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strComuna"])).'" />';
		echo '<input name="txtComuna'.$cont.'" id="txtComuna'.$cont.'" class="txt-sborde" style="width:99%; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.htmlentities(trim($rst["strComuna"])).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnComuna".$cont."\')', 250);";
		echo '" ';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnComuna".$cont."');";
		echo '"';
		echo '/>';
		echo '</td>';
		echo '<td width="23%" align="left">';
		echo '<input type="hidden" name="hdnInspector'.$cont.'" id="hdnInspector'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strInspector"])).'" />';
		echo '<input name="txtInspector'.$cont.'" id="txtInspector'.$cont.'" class="txt-sborde" style="width:99%; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.htmlentities(trim($rst["strInspector"])).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnInspector".$cont."\')', 250);";
		echo '" ';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnInspector".$cont."');";
		echo '"';
		echo '/>';
		echo '</td>';
		if($modulo == 0){ 
			echo '<td width="9%" align="right">'.number_format($rst["dblTotal"], 0, '', '.').'&nbsp;</td>';
			echo '<td width="10%" align="center">'.$rst["dblEPago"].'</td>';
			echo '<td width="10%" align="center">'.$rst["dblNEnvio"].'</td>';
		}
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n</b></td></tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="hdnEPago" id="hdnEPago" value="<?php echo $epago;?>" />
<input type="hidden" name="hdnChkAll" id="hdnChkAll" value="<?php echo $chkall;?>" />
</body>
</html>
