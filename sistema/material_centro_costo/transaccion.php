<?php
include '../conexion.inc.php';

$modulo=$_GET["modulo"];
switch($modulo){
	case 0:?>
	<script language="javascript">
	<!--
	var movil = parent.document.getElementById('cmbMovil');
	for(i=movil.length; i>0; i--) movil.remove(i);
	-->
	</script>
	<?php
		$bodega = $_GET["bodega"];
		$stmt = mssql_query("EXEC General..sp_getMoviles 12, '$bodega'", $cnx);
		while($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			movil.options[movil.length] = new Option('<?php echo '['.trim($rst["strMovil"]).'] '.$rst["strNombre"];?>', '<?php echo $rst["strMovil"];?>');
		-->
		</script>
	<?php
		}
		mssql_free_result($stmt);
		break;
}

mssql_close($cnx);
?>