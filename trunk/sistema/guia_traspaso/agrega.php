<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$accion = $_GET["accion"];
$codigo=$_GET["codigo"];
if($accion=='G'){
	$cantidad=$_GET["cantidad"];
	mssql_query("EXEC sp_AgregaLineaDetalleTMP '$usuario', 'GT', '$codigo', NULL, $cantidad", $cnx);
}elseif($accion=='E'){
	mssql_query("DELETE FROM Detalle_TMP WHERE strUsuario='$usuario' AND strTipoDoc='GT' AND strCodigo='$codigo'", $cnx);
}elseif($accion=='B')
	mssql_query("DELETE FROM Detalle_TMP WHERE strUsuario='$usuario' AND strTipoDoc='GT'", $cnx);
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
function Elimina(codigo){
	if(confirm('¿Está seguro que desea eliminar esta línea?'))
		self.location.href='agrega.php?usuario=<?php echo $usuario;?>&accion=E&codigo='+codigo;
}
-->
</script>
<body marginheight="0" marginwidth="0">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$cont=0;
$stmt = mssql_query("EXEC sp_getDetalleTMP '$usuario', 'GT'", $cnx);
while($rst=mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont%2)==0 ? '#EBF1FF' : '#FFFFFF' ?>" >
		<td width="10%" align="center"><?php echo $rst["strCodigo"];?></td>
		<td width="68%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
		<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
		<td width="10%" align="right"><?php echo number_format($rst["dblCantidad"],2,',','.');?>&nbsp;</td>
		<td width="2%" align="center">
			<a href="#" title="Elimina l&iacute;nea N&deg;<?php echo "$cont: ".htmlentities(trim($rst["strDescripcion"]));?>"
				onclick="javascript: Elimina('<?php echo $rst["strCodigo"];?>');" 
				onmouseover="javascript: window.status='Elimina l&iacute;nea N&deg;<?php echo "$cont: ".htmlentities(trim($rst["strDescripcion"]));?>'; return true;"
			><img border="0" src="../images/borrar<?php echo ($cont % 2 == 0 ? 0 : 1);?>.gif" /></a>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />

<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>" />
<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
<input type="hidden" name="login" id="login" value="<?php echo $login;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>