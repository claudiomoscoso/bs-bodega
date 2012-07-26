<?php
include '../conexion.inc.php';

$accion = $_GET["accion"];
$bodega = $_GET["bodega"];
$usuario = $_GET["usuario"];
$numero = $_GET["numero"];
$codigo = $_GET["codigo"];
$cantidad = $_GET["cantidad"];
if($accion == 'C')
	mssql_query("EXEC Bodega..sp_getTMPDetalleSolicitud 1, '$bodega', '$usuario', $numero", $cnx);
elseif($accion == 'G')
	mssql_query("EXEC Bodega..sp_setTMPDetalleSolicitud 0, '$bodega', '$usuario', '$codigo', $cantidad", $cnx);
elseif($accion == 'E')
	mssql_query("EXEC Bodega..sp_setTMPDetalleSolicitud 1, '$bodega', '$usuario', '$codigo'", $cnx);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Solicitud</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript">
<!--
function Load(){
	if('<?php echo $accion;?>' == 'C'){ 
		parent.document.getElementById('txtNota').value = '[Traspaso]';
		parent.document.getElementById('txtNota').readOnly = true;
		parent.document.getElementById('btnCargar').disabled = false;
	}
	if(document.getElementById('totfil').value != 0) document.getElementById('msj').style.display = 'none';
}

function Elimina(codigo){
	if(confirm('¿Está seguro que desea eliminar esta línea?')) 
		self.location.href = '<?php echo $_SERVER['PHP_SELF'];?>?accion=E&usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&codigo=' + codigo;
}

-->
</script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" onload="javascript: Load();">
<div id="msj" style="position:absolute; width:30%; height:10%; left:35%; top:20%; display:none">
<table border="0" width="100%" cellpadding="0" cellspacing="0">	
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</div>

<table id="tbl" width="100%" border="0" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleSolicitud 0, '$bodega', '$usuario'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;
	echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
	echo '<td width="3%" align="center">'.$cont.'</td>';
	echo '<td width="10%" align="center">'.$rst["strCodigo"].'</td>';
	echo '<td width="65%">&nbsp;'.htmlentities($rst["strDescripcion"]).'</td>';
	echo '<td width="10%" align="center">'.$rst["strUnidad"].'</td>';
	echo '<td width="10%" align="right">'.number_format($rst["dblCantidad"], 2, ',', '.').'&nbsp;</td>';
	echo '<td width="2%" align="center">';
	echo '<a href="#" title="Elimina l&iacute;nea N&deg;'.$cont.': '.htmlentities(trim($rst["strDescripcion"])).'"';
	echo 'onclick="javascript: Elimina(\''.$rst["strCodigo"].'\');" ';
	echo 'onmouseover="javascript: window.status=\'Elimina l&iacute;nea N&deg;'.$cont.': '.htmlentities(trim($rst["strDescripcion"])).'\'; return true;"';
	echo '><img border="0" src="../images/borrar'.($cont % 2 == 0 ? 0 : 1).'.gif" /></a>';
	echo '</td>';
	echo '</tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
