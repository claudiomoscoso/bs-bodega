<?php
include '../conexion.inc.php';

$accion = $_GET["accion"];
$usuario = $_GET["usuario"];
$bodega = $_GET["bodega"];
$dpago = $_GET["dpago"];
$ccosto = ($_GET["ccosto"] != '' ? $_GET["ccosto"] : 'NULL');

$codigo = $_POST["txtCodigo"] != '' ? $_POST["txtCodigo"] : $_GET["codigo"];
$fchini = $_POST["txtFInicio"] != '' ? "'".formato_fecha($_POST["txtFInicio"], false, true)."'" : 'NULL';
$fchter = $_POST["txtFTermino"] != '' ? "'".formato_fecha($_POST["txtFTermino"], false, true)."'" : 'NULL';
$solicitud = $_POST["txtSolicitud"] != '' ? $_POST["txtSolicitud"] : 0;
$cantidad = $_POST["txtCantidad"];
$valor = $_POST["txtValor"];

$stmt = mssql_query("SELECT dblFactor FROM Bodega..Impuesto WHERE id = $dpago", $cnx);
if($rst=mssql_fetch_array($stmt)) $factor = $rst["dblFactor"];
mssql_free_result($stmt);

if($accion == 'A'){
	mssql_query("EXEC Bodega..sp_setTMPDetalleOrdenCompra 5, '$usuario', '$bodega', NULL, '$codigo', $fchini, $fchter, $solicitud, $cantidad, $valor, '$ccosto'", $cnx);
}elseif($accion=='E'){
	mssql_query("EXEC Bodega..sp_setTMPDetalleOrdenCompra 6, '$usuario', '$bodega', NULL, '$codigo'", $cnx);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Orden de Compra</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Elimina(codigo){
	if(confirm('�Est� seguro que desea eliminar esta l�nea?')) 
		self.location.href='<?php echo $_SERVER['PHP_SELF'];?>?accion=E&usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&dpago=<?php echo $dpago;?>&ccosto=<?php echo $ccosto;?>&codigo=' + codigo;
}
-->
</script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleOrdenCompra 1, '$usuario', '$bodega'", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#EBF1FF' : '#FFFFFF' ?>" >
		<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
		<td width="21%">
			<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities(trim($rst["strDescripcion"]));?>" />
			<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:100%; background-color:<?php echo ($cont % 2)==0 ? '#EBF1FF' : '#FFFFFF';?>" readonly="true" value="&nbsp;<?php echo htmlentities(trim($rst["strDescripcion"]));?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="5%" align="center"><?php echo trim($rst["strUnidad"]);?></td>
		<td width="10%" align="center"><?php echo $rst["dtmFchIni"];?></td>
		<td width="10%" align="center"><?php echo $rst["dtmFchTer"];?></td>
		<td width="10%" align="center"><?php echo $rst["dblSolicitud"];?></td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCAutorizada"], 2, ',', '.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblValor"], 0, ',', '.');?>&nbsp;</td>
		<td width="10%" align="right"><?php echo number_format($rst["dblValor"] * $rst["dblCAutorizada"], 0, ',', '.');?>&nbsp;</td>
		<td width="2%" align="center" >
			<a href="#" title="Elimina l&iacute;nea N&deg;<?php echo "$cont: ".htmlentities(trim($rst["strDescripcion"]));?>"
				onclick="javascript: Elimina('<?php echo $rst["strCodigo"];?>');" 
				onmouseover="javascript: window.status='Elimina l&iacute;nea N&deg;<?php echo "$cont: ".htmlentities(trim($rst["strDescripcion"]));?>'; return true;"
			><img border="0" src="../images/borrar<?php echo ($cont%2)==0 ? 0 : 1;?>.gif" /></a>
		</td>
	</tr>
<?php
	$neto+=$rst["dblValor"]*$rst["dblCAutorizada"];
}
mssql_free_result($stmt);
$impto = $neto * $factor;?>
</table>
</body>
</html>
<script language="javascript">
	parent.document.getElementById('neto').value='<?php echo number_format($neto, 0, ',', '.');?>';
	parent.document.getElementById('impto').value='<?php echo number_format($impto, 0, ',', '.');?>';
	parent.document.getElementById('totalOC').value='<?php echo number_format($neto + $impto, 0, ',', '.');?>';
</script>
<?php
mssql_close($cnx);
?>