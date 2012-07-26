<?php
include '../conexion.inc.php';

//$fchdsd = $_GET["fchdsd"];
//$fchhst = $_GET["fchhst"];
$fchdsd = formato_fecha($_GET["fchdsd"], false, true);
$fchhst = formato_fecha($_GET["fchhst"], false, true);
$contrato = $_GET["contrato"];
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
var Intervalo = 0;
function Load(){ 
	parent.document.getElementById('txtRecibidas').value = document.getElementById('hdnRecibidas').value;
	parent.document.getElementById('txtInformadas').value = document.getElementById('hdnInformadas').value;
	parent.document.getElementById('txtNoInformadas').value = document.getElementById('hdnNoInformadas').value;
	parent.document.getElementById('txtProduccion').value = document.getElementById('hdnProduccion').value;
	parent.Deshabilita(false);
}
-->
</script>
<body marginheight="0" marginwidth="0" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getResumenProduccion 0, '$fchdsd', '$fchhst', '$contrato'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" valign="top">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="35%" align="left">&nbsp;'.htmlentities(trim($rst["strDescComuna"])).'</td>';
		echo '<td width="15%" align="right">'.number_format($rst["dblRecibidas"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="15%" align="right">'.number_format($rst["dblInformadas"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="15%" align="right">'.number_format($rst["dblRecibidas"] - $rst["dblInformadas"], 0, '', '.').'&nbsp;</td>';
		echo '<td width="15%" align="right">'.number_format($rst["dblProduccion"], 0, '', '.').'&nbsp;</td>';
		echo '</tr>';
		$recibidas+=$rst["dblRecibidas"];
		$informadas+=$rst["dblInformadas"];
		$noinformadas+=($rst["dblRecibidas"] - $rst["dblInformadas"]);
		$produccion+=$rst["dblProduccion"];
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se han encontrado registros.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="hdnRecibidas" id="hdnRecibidas" value="<?php echo number_format($recibidas, 0, '', '.');?>" />
<input type="hidden" name="hdnInformadas" id="hdnInformadas" value="<?php echo number_format($informadas, 0, '', '.');?>" />
<input type="hidden" name="hdnNoInformadas" id="hdnNoInformadas" value="<?php echo number_format($noinformadas, 0, '', '.');?>" />
<input type="hidden" name="hdnProduccion" id="hdnProduccion" value="<?php echo number_format($produccion, 0, '', '.');?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>