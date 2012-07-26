<?php
include '../autentica.php';
include '../conexion.inc.php';

$contrato = $_POST["cmbContratos"];
$finicio = $_POST["txtFInicio"] != '' ? "'".formato_fecha($_POST["txtFInicio"], false, true)."'" : 'NULL';
$ftermino = $_POST["txtFTermino"] != '' ? "'".formato_fecha($_POST["txtFTermino"], false, true)."'" : 'NULL';
$estado = $_POST["cmbEstado"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Volumen de Obra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" >
<!--
var Intervalo = 0;

function Load(){
	parent.Deshabilita(false);
	parent.document.getElementById('TotalI').innerHTML = document.getElementById('hdnTotalI').value;
	parent.document.getElementById('TotalP').innerHTML = document.getElementById('hdnTotalP').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$sql="EXEC Orden..sp_getDetalleInformeOrden 2, '$contrato', NULL, $finicio, $ftermino, '$estado', '$usuario'";
$stmt = mssql_query($sql, $cnx);
if($rst = mssql_fetch_array($stmt)){
	$TotalInforme=0;
	$TotalPagado=0;
	do{
		$cont++;
		echo '<tr bgcolor='.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'>';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="10%" align="center">'.trim($rst["strItem"]).'</td>';
		echo '<td width="35%" align="left">';
		echo '<input type="hidden" name="hdnDescripcion'.$cont.'" id="hdnDescripcion'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strDescripcion"])).'" />';
		echo '<input name="txtDireccion'.$cont.'" id="txtDireccion'.$cont.'" class="txt-sborde" style="width:99%; background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.htmlentities(trim($rst["strDescripcion"])).'" ';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion".$cont."\')', 250);";
		echo '" ';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnDescripcion".$cont."');";
		echo '"';
		echo '/>';
		echo '</td>';
		echo '<td width="10%" align="center">'.trim($rst["strUnidad"]).'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblCantidadEmos"], 2, ',', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblTInformado"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblTPagado"], 0, '', '.').'&nbsp;</td>';
		echo '</tr>';
		$TotalInforme+=$rst["dblTInformado"];
		$TotalPagado+=$rst["dblTPagado"];
	}while($rst = mssql_fetch_array($stmt));	
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="hdnTotalI" id="hdnTotalI" value="<?php echo number_format($TotalInforme, 0, '', '.');?>">
<input type="hidden" name="hdnTotalP" id="hdnTotalP" value="<?php echo number_format($TotalPagado, 0, '', '.');?>">
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
