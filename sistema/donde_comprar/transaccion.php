<?php
include '../conexion.inc.php';

$texto = $_GET["texto"];

$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$texto', 'E', NULL, NULL, 'CC'",$cnx);
if($rst=mssql_fetch_array($stmt)){
	$codigo = $rst["strCodigo"];
	$descripcion = $rst["strDescripcion"];
}
mssql_free_result($stmt);
?>
<script language="javascript">
<!--
parent.document.getElementById('hdnMaterial').value='<?php echo $codigo;?>';
parent.document.getElementById('txtMaterial').value='<?php echo '['.$codigo.'] '.$descripcion;?>';
-->
</script>
<?php
mssql_close($cnx);
?>