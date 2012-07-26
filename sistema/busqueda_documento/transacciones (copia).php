<?php
include '../conexion.inc.php';

$tipo = $_GET["tipo"];
$bodega = $_GET["bodega"];
$valor = $_GET["valor"];
$ctrl = $_GET["ctrl"];
$hdn = $_GET["hdn"];
$doc = $_GET["doc"];
switch($tipo){
	case 0:
		$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor', 'E', NULL, '$bodega', 'ALL'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('<?php echo $hdn;?>').value = '<?php echo $rst["strCodigo"];?>';
			parent.document.getElementById('<?php echo $ctrl;?>').value = '<?php echo $rst["strDescripcion"];?>';
		-->
		</script>
		<?php
		}mssql_free_result($stmt);
		break;
	case 1:
		switch($doc){
			case 0:
				$stmt = mssql_query("EXEC General..sp_getCargos 0, '$bodega', '$valor'", $cnx);
				if($rst = mssql_fetch_array($stmt)){?>
				<script language="javascript">
				<!--
					parent.document.getElementById('<?php echo $hdn;?>').value = '<?php echo $rst["strCodigo"];?>';
					parent.document.getElementById('<?php echo $ctrl;?>').value = '<?php echo $rst["strCargo"];?>';
				-->
				</script>
				<?php
				}
				mssql_free_result($stmt);
				break;
			case 1:
			case 2:
				$stmt = mssql_query("EXEC Bodega..sp_getProveedor 4, 'E', NULL, '$valor'", $cnx);
				if($rst = mssql_fetch_array($stmt)){?>
				<script language="javascript">
				<!--
					parent.document.getElementById('<?php echo $hdn;?>').value = '<?php echo $rst["strCodigo"];?>';
					parent.document.getElementById('<?php echo $ctrl;?>').value = '<?php echo $rst["strNombre"];?>';
				-->
				</script>
				<?php
				}
				mssql_free_result($stmt);
				break;
			case 3:
			case 4:
				$stmt = mssql_query("EXEC General..sp_getMoviles 0, '$bodega', '$valor', 'E'", $cnx);
				if($rst = mssql_fetch_array($stmt)){?>
				<script language="javascript">
				<!--
					parent.document.getElementById('<?php echo $hdn;?>').value = '<?php echo $rst["strMovil"];?>';
					parent.document.getElementById('<?php echo $ctrl;?>').value = '<?php echo $rst["strNombre"];?>';
				-->
				</script>
				<?php
				}
				mssql_free_result($stmt);
				break;
			case 5:
				$stmt = mssql_query("EXEC General..sp_getPersonalObra 2, '$bodega', '$valor'", $cnx);
				if($rst = mssql_fetch_array($stmt)){?>
				<script language="javascript">
				<!--
					parent.document.getElementById('<?php echo $hdn;?>').value = '<?php echo $rst["strRut"];?>';
					parent.document.getElementById('<?php echo $ctrl;?>').value = '<?php echo $rst["strNombre"];?>';
				-->
				</script>
				<?php
				}
				mssql_free_result($stmt);
				break;
		}
		break;
	case 2:?>
		<script language="javascript">
		<!--
		var bodega = parent.document.getElementById('cmbBodega');
		for(i = bodega.length; i >= 0; i--) bodega.remove(i);
		-->
		</script>
		<?php
		$usuario = $_GET["usuario"];
		switch($doc){
			case 6:?>
				<script language="javascript">
				<!--
					bodega.options[bodega.length] = new Option('Todos', 'all');
				-->
				</script>
				<?php
				$stmt = mssql_query("EXEC General..sp_getCargos 5, NULL, '$usuario'", $cnx);
				while($rst = mssql_fetch_array($stmt)){?>
				<script language="javascript">
				<!--
					bodega.options[bodega.length] = new Option('<?php echo $rst["strCargo"];?>', '<?php echo trim($rst["strCodigo"]);?>');
				-->
				</script>
				<?php			
				}
				mssql_free_result($stmt);
			case 11:
				$stmt = mssql_query("EXEC General..sp_getCargos 12", $cnx);
				while($rst = mssql_fetch_array($stmt)){?>
				<script language="javascript">
				<!--
					bodega.options[bodega.length] = new Option('<?php echo $rst["strDetalle"];?>', '<?php echo trim($rst["strCodigo"]);?>');
				-->
				</script>
				<?php			
				}
				mssql_free_result($stmt);

			default:
				$stmt = mssql_query("EXEC General..sp_getBodega 4, '$usuario'", $cnx);
				while($rst = mssql_fetch_array($stmt)){?>
				<script language="javascript">
				<!--
					bodega.options[bodega.length] = new Option('<?php echo $rst["strDetalle"];?>', '<?php echo $rst["strBodega"];?>');
				-->
				</script>
				<?php
				}
				mssql_free_result($stmt);
				break;
		}
		?>
		<script language="javascript">
		<!--
		bodega.disabled = false;
		-->
		</script>
		<?php
		break;

}
mssql_close($cnx);
?>
