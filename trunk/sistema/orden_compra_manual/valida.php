<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$bodega = $_GET["bodega"];
$valor = trim($_GET["valor"]);
switch($modulo){
	case 0:
		$stmt = mssql_query("EXEC Bodega..sp_getProveedor 4, 'E', NULL, '$valor'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnProveedor').value ='<?php echo $rst["strCodigo"];?>';
			parent.document.getElementById('txtProveedor').value =' <?php echo $rst["strNombre"];?>';
			parent.document.getElementById('txtDireccion').value =' <?php echo $rst["strDireccion"];?>';
			parent.document.getElementById('txtComuna').value =' <?php echo $rst["strDetalle"];?>';
			parent.document.getElementById('txtTelefono').value =' <?php echo $rst["strTelefono"];?>';
			parent.document.getElementById('txtFax').value =' <?php echo $rst["strFax"];?>';
			parent.document.getElementById('txtAtencion').value =' <?php echo $rst["strContacto"];?>';
			var fpago = parent.document.getElementById('cmbFPago');
			for(i = 0; i < fpago.length; i++){
				if(parseInt(fpago.options[i].value) == parseInt('<?php echo $rst["intFormaPago"];?>')){
					fpago.selectedIndex = i;
					break;
				}
			}		
			parent.document.getElementById('cmbCargo').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.LimpiaDatosProveedor();
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor','E', 'M', '$bodega','OC'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtDescripcion').value='<?php echo $rst["strDescripcion"];?>';
			parent.document.getElementById('txtUnidad').value='<?php echo trim(strtoupper($rst["strUnidad"]));?>';
			parent.document.getElementById('hdnStock').value='<?php echo $rst["dblStock"];?>';
			parent.document.getElementById('txtCantidad').value = 0;
			parent.document.getElementById('txtCantidad').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('txtUnidad').value = '';
			parent.document.getElementById('hdnStock').value = 0;
			parent.document.getElementById('txtCantidad').value = 0;
			parent.document.getElementById('txtCantidad').focus();
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 2:?>
	<script language="javascript">
	<!--
		var cargo = parent.document.getElementById('cmbCargo');
		for(i = cargo.length; i>=0; i--) cargo.remove(i);
	-->
	</script>
	<?php
		$stmt = mssql_query("EXEC General..sp_getCargos 1, '".$_GET["bodega"]."'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			cargo.options[cargo.length] = new Option('<?php echo $rst["strCargo"];?>', '<?php echo trim($rst["strCodigo"]);?>');
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
}

mssql_close($cnx);
?>