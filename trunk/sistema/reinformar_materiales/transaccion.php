<?php
include '../conexion.inc.php';

$usuario=$_GET["usuario"];
$modulo=$_GET["modulo"];

switch($modulo){
	case 0:?>
	<script language="javascript">
	<!--
	var moviles = parent.document.getElementById('cmbMovil');
	for(i = moviles.length; i >= 0; i--) moviles.remove(i);
	-->
	</script>
	<?php
		$contrato = $_GET["contrato"];
		$stmt = mssql_query("EXEC General..sp_getMoviles 6, NULL, '$contrato'", $cnx);
		while($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
			moviles.options[moviles.length] = new Option('<?php echo '['.trim($rst["strMovil"]).'] '.$rst["strNombre"];?>', '<?php echo trim($rst["strMovil"]);?>')
		</script>
	<?php
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$numero = $_GET["numero"];
		$orden = $_GET["orden"];
		$perfil = $_GET["perfil"];
		$bodega = $_GET["bodega"];
		$nivel = $_GET["nivel"];
		$login = $_GET["login"];
		
		mssql_query("EXEC Orden..sp_setDetalleOrdenTrabajo 1, '$usuario', $numero", $cnx);?>
	<script language="javascript">
		parent.location.href='index.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&bodega=<?php echo $bodega;?>&nivel=<?php echo $nivel;?>&login=<?php echo $login;?>';
	</script>
	<?php
		break;
	case 2:
		$numero = $_GET["numero"];
		$perfil = $_GET["perfil"];
		$bodega = $_GET["bodega"];
		$nivel = $_GET["nivel"];
		$login = $_GET["login"];
		
		mssql_query("EXEC Orden..sp_setDetalleOrdenTrabajo 2, '$usuario', $numero", $cnx);?>
	<script language="javascript">
		parent.location.href='index.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&bodega=<?php echo $bodega;?>&nivel=<?php echo $nivel;?>&login=<?php echo $login;?>';
	</script>
	<?php
		break;
	case 3:
		$numero = $_GET["numero"];
		$perfil = $_GET["perfil"];
		$bodega = $_GET["bodega"];
		$nivel = $_GET["nivel"];
		$login = $_GET["login"];
		
		mssql_query("EXEC Orden..sp_setOrdenTrabajo 2, '$usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $numero", $cnx);?>
		<script language="javascript">
			parent.location.href='index.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&bodega=<?php echo $bodega;?>&nivel=<?php echo $nivel;?>&login=<?php echo $login;?>';
		</script>
		<?php
		break;
}
mssql_close($cnx);
?>