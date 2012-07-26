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
<title>Material Informado</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){ parent.Deshabilita(false);}
-->
</script>
<body marginheight="0" marginwidth="0" onLoad="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getMaterialInformado '$contrato', '$fchdsd', '$fchhst'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		$linea='<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" valign="top">';
		$linea.='<td width="3%" align="center">'.$cont.'</td>';
		$linea.='<td width="10%" >';
		$linea.=htmlentities(trim($rst["strLocalidad"]));
		$linea.='</td>';
		$linea.='<td width="10%" >';
		$linea.=substr(htmlentities(trim($rst["strITO"])), 0, 12).'...';
		$linea.='</td>';
		$linea.='<td width="9%" align="center">'.$rst["strODT"].'</td>';
		$linea.='<td width="15%" >';
		$linea.=substr(htmlentities(trim($rst["strMotivo"])), 0, 18).'...';
		$linea.='</td>';
		$linea.='<td width="7%" align="center">';
		$linea.=substr(htmlentities(trim($rst["dtmInforme"])), 0, 10).'';
		$linea.='</td>';
		$linea.='<td width="7%" align="center">'.$rst["strCodigo"].'</td>';
		$linea.='<td width="15%">';
		$linea.=substr(htmlentities(trim(strtolower($rst["strDescripcion"]))), 0, 18).'...';
		$linea.='</td>';
		$linea.='<td width="5%" align="center">'.$rst["strUnidad"].'</td>';
		$linea.='<td width="7%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'</td>';
		$linea.='<td width="5%" align="center">'.$rst["strMovil"].'</td>';
		$linea.='<td width="5%" align="right">'.number_format($rst["dblPrecio"], 0, ',', '.').'</td>';
		$linea.='</tr>';
		echo $linea;
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>