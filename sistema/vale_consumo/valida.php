<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$bodega = $_GET["bodega"];

$valor = $_GET["valor"];
switch($modulo){
	case 0:?>
	<script language="javascript">
		<!--
		var responsable = parent.document.getElementById('cmbResponsable');
		for(i = responsable.length; i >=0; i--) responsable.remove(i);
		-->
	</script>
	<?php
		$stmt = mssql_query("EXEC General..sp_getPersonalObra 5, '$bodega'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			responsable.options[responsable.length] = new Option('<?php echo $rst["strNombre"];?>', '<?php echo $rst["strRut"];?>');
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);?>
		<script language="javascript">
		responsable.disabled = false;
		</script>
		<?php
		break;
	case 1:
		$stmt = mssql_query("EXEC Bodega..sp_BuscarMatEnBodega '$valor', '$bodega'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		parent.document.getElementById('txtDescripcion').value='<?php echo trim($rst["strDescripcion"]);?>';
		parent.document.getElementById('txtUnidad').value='<?php echo trim($rst["strUnidad"]);?>';
		parent.document.getElementById('hdnStock').value='<?php echo $rst["dblStock"];?>';
		parent.document.getElementById('txtCCosto').focus();
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 2:
		$stmt = mssql_query("SELECT strCodigo FROM General..Partida WHERE strBodega='$bodega' AND strCodigo='$valor'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		parent.document.getElementById('txtCCosto').value='<?php echo trim($rst["strCodigo"]);?>';
		parent.document.getElementById('hdnCCosto').value='<?php echo trim($rst["strCodigo"]);?>';
		parent.document.getElementById('txtCantidad').focus();
		parent.document.getElementById('txtCantidad').select();
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>