<script language="javascript">
var responsable = parent.document.getElementById('cmbResponsable');
for(i = responsable.length; i > 0; i--) responsable.remove(i);
</script>
<?php
include '../conexion.inc.php';
$bodega = $_GET["bodega"];
$stmt = mssql_query("EXEC General..sp_getUsuarios 3, '$bodega'", $cnx);
while($rst = mssql_fetch_array($stmt)){?>
<script language="javascript">
	responsable.options[responsable.length] = new Option('<?php echo $rst["strNombre"];?>', '<?php echo $rst["strUsuario"];?>');
</script>
<?php
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
<script language="javascript">
responsable.disabled = false;
</script>