<script language="javascript">
var responsable = parent.document.getElementById('cmbResponsable');
for(i = responsable.length; i >= 0; i--) responsable.remove(i);
</script>
<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$stmt = mssql_query("EXEC General..sp_getUsuarios 3, '$bodega'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{?>
	<script language="javascript">
	<!--
		responsable.options[responsable.length] = new Option('<?php echo $rst["strNombre"];?>', '<?php echo $rst["strUsuario"];?>');
	-->
	</script>
	<?php
	}while($rst = mssql_fetch_array($stmt));
}else{?>
<script language="javascript">
<!--
	responsable.options[responsable.length] = new Option('--', 'none');
-->
</script>
<?php
}
mssql_free_result($stmt);
mssql_close($cnx);
?>