<?php
include '../conexion.inc.php';

$numero = $_GET["numero"];
$codigo = $_GET["codigo"];

$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'ARR', $numero, '%', '$codigo'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$codigo = $rst["strCodigo"];
	$numoc = $rst["dblUltima"];
	$observacion = $rst["strObservacion"];
	$fchdsd = $rst["dtmFchDsd"];
	$fchhst = $rst["dtmFchHst"];
	$proveedor = $rst["strNombre"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Arriendos Vigentes</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<body>
<table border="0" width="100%" cellpadding="0" cellspacing="1">
	<tr><td colspan="3" style="height:5px"></td></tr>
	<tr>
		<td ><b>&nbsp;N&deg;O.Compra</b></td>
		<td width="1%"><b>:</b></td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td >&nbsp;<?php echo $numoc;?></td>
					<td nowrap="nowrap"><b>&nbsp;Fecha Inicio</b></td>
					<td width="1%"><b>:</b></td>
					<td ><?php echo $fchdsd;?></td>
					<td width="1%">&nbsp;</td>
					<td nowrap="nowrap"><b>&nbsp;Fecha T&eacute;rmino</b></td>
					<td width="1%"><b>:</b></td>
					<td ><?php echo $fchhst;?></td>
				</tr>
			</table>
		</td>
	<tr>
	<tr valign="top">
		<td width="10%"><b>&nbsp;Descripci&oacute;n</b></td>
		<td width="1%"><b>:</b></td>
		<td><textarea readonly="readonly" class="txt-plano" style="width:99%" rows="5"><?php echo trim($observacion);?></textarea></td>
	</tr>
	<tr>
		<td><b>&nbsp;Proveedor</b></td>
		<td><b>:</b></td>
		<td><?php echo $proveedor;?></td>
	</tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>