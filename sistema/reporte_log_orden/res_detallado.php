<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
//$fchdsd = $_GET["fchdsd"];
//$fchhst = $_GET["fchhst"];
$fchdsd = $_GET["fchdsd"] != '' ? "'".formato_fecha($_GET["fchdsd"], false, true)."'" : 'NULL';
$fchhst = $_GET["fchhst"] != '' ? "'".formato_fecha($_GET["fchhst"], false, true)."'" : 'NULL';
$epago = $_GET["epago"];
$certificado = $_GET["certificado"];
$cerradas = $_GET["cerradas"];
$diferencias = $_GET["diferencias"];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pendientes de Pago</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Scroll(ctrl){
	document.getElementById('divCabecera').scrollLeft = ctrl.scrollLeft;
	return true;
}

function Load(){ 
	if(document.getElementById('divDetalle')) document.getElementById('divDetalle').style.height = (window.innerHeight - 25) + 'px';
	parent.Deshabilita(false);
	parent.document.getElementById('txtTInformado').value = document.getElementById('hdnTotInf').value;
	parent.document.getElementById('txtTPagado').value = document.getElementById('hdnTotPag').value;
}
-->
</script>
<body marginheight="0" marginwidth="0" onLoad="javascript: Load();">
<?php
$stmt = mssql_query("EXEC Orden..sp_getProduccionMoviles 0, '$contrato', $fchdsd, $fchhst, '$epago', '$certificado', '$cerradas', '$diferencias'", $cnx);
if($rst = mssql_fetch_array($stmt)){?>
	<div id="divCabecera" style="position:relative;width:100%;height:22px;overflow:hidden">
	<?php
	$totinf = 0;
	$totpag = 0;
	echo '<table border="0" width="2345px" cellpadding="0" cellspacing="0">';
	echo '<tr >';
	echo '<th width="15px">N&deg;</th>';
	echo '<th width="50px" align="center">&nbsp;Movil</th>';
	echo '<th width="90px" align="left">&nbsp;Localidad</th>';
	echo '<th width="90px" align="left">&nbsp;Inspector</th>';
	echo '<th width="50px" >N&deg; ODT</th>';
	echo '<th width="50px" >Fecha</th>';
	echo '<th width="50px" >&Iacute;tem</th>';
	echo '<th width="150px" align="left">&nbsp;Descripci&oacute;n</th>';
	echo '<th width="70px" align="right">Cantidad I.&nbsp;</th>';
	echo '<th width="70px" align="right">Cantidad P.&nbsp;</th>';
	echo '<th width="50px" >Unidad</th>';
	echo '<th width="70px" align="right">Precio&nbsp;</th>';
	echo '<th width="70px" align="right">Total I.&nbsp;</th>';
	echo '<th width="70px" align="right">Total P.&nbsp;</th>';
	echo '<th width="150px" align="left">&nbsp;Estado</th>';
	echo '<th width="150px" align="left">&nbsp;Direcci&oacute;n</th>';
	echo '<th width="70px" >50% en&nbsp;</th>';
	echo '<th width="70px" >E.Pago</th>';
	echo '<th width="70px" >Cerrada</th>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';
	echo '<div id="divDetalle" style="position:relative; width:100%; height:100px; overflow:scroll"';
	echo 'onscroll="javascript: Scroll(this);"';
	echo '>';
	echo '<table border="0" width="2340px" cellpadding="0" cellspacing="1">';
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" valign="top">';
		echo '<td width="15px" align="center">'.$cont.'</td>';
		echo '<td width="50px" align="center">'.htmlentities(trim($rst["strMovil"])).'</td>';
		echo '<td width="90px">'.htmlentities(trim($rst["Localidad"])).'</td>';
		echo '<td width="90px" align="left">'.htmlentities(trim($rst["Inspector"])).'</td>';
		echo '<td width="50px" align="center">'.$rst["ODT"].'</td>';
		echo '<td width="50px" align="center">'.$rst["Fecha"].'</td>';
		echo '<td width="50px" align="center">'.$rst["Item"].'</td>';
		echo '<td width="150px" align="left">'.htmlentities(trim($rst["Detalle"])).'</td>';
		echo '<td width="70px" align="right">'.number_format($rst["Cantidad_I"], 2, ',', '.').'</td>';
		echo '<td width="70px" align="right">'.number_format($rst["Cantidad_P"], 2, ',', '.').'</td>';
		echo '<td width="50px" align="center">'.$rst["Unidad"].'</td>';
		echo '<td width="70px" align="right">'.number_format($rst["Precio"], 0, '', '.').'</td>';
		echo '<td width="70px" align="right">'.number_format($rst["Total_I"], 0, '', '.').'</td>';
		echo '<td width="70px" align="right">'.number_format($rst["Total_P"], 0, '', '.').'</td>';
		echo '<td width="150px" align="left">'.htmlentities(trim($rst["Estado"])).'</td>';
		echo '<td width="150px" >'.htmlentities(trim($rst["Direccion"])).'</td>';
		echo '<td width="70px" align="center">'.$rst["dblCertificado"].'</td>';
		echo '<td width="70px" align="center">'.$rst["dblEP"].'</td>';
		echo '<td width="70px" align="center">'.$rst["intCerrada"].'</td>';
		echo '</tr>';
		$totinf+=$rst["Total_I"];
		$totpag+=$rst["Total_P"];
	}while($rst = mssql_fetch_array($stmt));
	echo '</table>';
	echo '</div>';
}else{
	echo '<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td align="center" style="color:#FF0000"><b>No se han encontrado registros.</b></td></tr></table>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="hdnTotInf" id="hdnTotInf" value="<?php echo number_format($totinf, 0, '', '.');?>">
<input type="hidden" name="hdnTotPag" id="hdnTotPag" value="<?php echo number_format($totpag, 0, '', '.');?>">
</body>
</html>