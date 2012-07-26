<?php
include '../conexion.inc.php';
$modulo = $_GET["modulo"];
$valor = $_GET["valor"];

switch($modulo){
	case 0:?>
		<script language="javascript">
		var cargo = parent.document.getElementById('cmbCargo');
		for(i = cargo.length; i >=0; i--) cargo.remove(i);
		</script>
		<?php
		$stmt = mssql_query("EXEC General..sp_getCargos 2, '$valor'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
			cargo.options[cargo.length] = new Option('<?php echo trim($rst["strCargo"]);?>', '<?php echo trim($rst["strCodigo"]);?>');
		</script>
		<?php
		}
		mssql_free_result($stmt);?>
		<script language="javascript">
		cargo.disabled = false;
		</script>
		<?php
		break;
	case 1:
		if($bcentral == 1)
			$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor', 'E', NULL, 'BC', 'SM'",$cnx);
		else
			$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor', 'E', NULL, NULL, 'DC'",$cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
			parent.document.getElementById('txtDescripcion').value = '&nbsp;<?php echo $rst["strDescripcion"];?>';
			parent.document.getElementById('txtUnidad').value = '<?php echo $rst["strUnidad"];?>';
			parent.document.getElementById('hdnStock').value = '<?php echo $rst["dblStock"];?>';
			parent.document.getElementById('txtCantidad').focus();
		</script>
		<?php
		}else{?>
		<script language="javascript">
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('txtUnidad').value = '';
			parent.document.getElementById('hdnStock').value = 0;
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>