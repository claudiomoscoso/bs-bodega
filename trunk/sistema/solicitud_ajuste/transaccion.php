<script language="javascript">
<!--
var moviles = parent.document.getElementById('cmbMovil');
for(i = moviles.length; i > 0; i--) moviles.remove(i);
-->
</script>
<?php
include '../conexion.inc.php';
$modulo = $_GET["modulo"];
$bodega = $_GET["bodega"];
if($modulo==0){
	$stmt = mssql_query("EXEC General..sp_getMoviles 12, '$bodega'", $cnx);
	while($rst=mssql_fetch_array($stmt)){?>
	<script language="javascript">
	<!--
	moviles.options[moviles.length] = new Option('<?php echo '['.trim($rst["strMovil"]).'] '.$rst["strNombre"];?>', '<?php echo trim($rst["strMovil"]);?>');
	-->
	</script>
	<?php
	}
	mssql_free_result($stmt);?>
	<script language="javascript">
	moviles.disabled = false;
	</script>
	<?php
}
mssql_close($cnx);
?>
