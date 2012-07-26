<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$bodega = $_GET["bodega"];
$accion = $_GET["accion"];
$codigo = $_GET["codigo"];
$cantidad = $_GET["cantidad"];
if($accion == 'C'){
	mssql_query("EXEC Bodega..sp_setTMPSolicitudDevolucion 2, '$usuario', '$bodega'", $cnx);
}elseif($accion == 'G')
	mssql_query("EXEC Bodega..sp_setTMPSolicitudDevolucion 0, '$usuario', '$bodega', '$codigo', $cantidad", $cnx);
elseif($accion == 'E')
	mssql_query("EXEC Bodega..sp_setTMPSolicitudDevolucion 1, '$usuario', '$bodega', '$codigo'", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gu&iacute;a de Despacho</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if('<?php echo $accion;?>' == 'C') parent.document.getElementById('btnCargar').disabled = false;
}

function Elimina(codigo){
	if(confirm('¿Está seguro que desea eliminar esta línea?'))
		self.location.href = 'agrega.php?usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&accion=E&codigo=' + codigo;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getTMPSolicitudDevolucion 0, '$usuario', '$bodega'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'" >';
	echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
	echo '<td width="68%" align="left">&nbsp;'.$rst["strDescripcion"].'</td>';
	echo '<td width="10%" align="center">'.$rst["strUnidad"].'</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
	echo '<td width="2%" align="center">';
	echo '<a href="#" title="Elimina l&iacute;nea N&deg;'.$cont.': '.htmlentities(trim($rst["strDescripcion"])).'" ';
	echo 'onclick="javascript: Elimina(\''.$rst["strCodigo"].'\');" ';
	echo 'onmouseover="javascript: window.status=\'Elimina l&iacute;nea N&deg;'.$cont.': '.htmlentities(trim($rst["strDescripcion"])).'\'; return true;"';
	echo '><img border="0" src="../images/borrar'.($cont % 2 == 0 ? 0 : 1).'.gif" /></a>';
	echo '</td>';
	echo '</tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>