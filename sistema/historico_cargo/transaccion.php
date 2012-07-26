<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$bodega = $_GET["bodega"];
$texto = $_GET["texto"];
switch($modulo){
	case 0:
		$stmt = mssql_query("EXEC General..sp_getPersonalObra 2, '$bodega', '$texto'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnCargo').value = '<?php echo trim($rst["strRut"]);?>';
			parent.document.getElementById('txtCargo').value = '<?php echo trim($rst["strNombre"]);?>';
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnCargo').value = '';
			parent.document.getElementById('txtCargo').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	
}
mssql_close($cnx);
?>