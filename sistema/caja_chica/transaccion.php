<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$bodega = $_GET["bodega"];
$numero = $_GET["numero"];
switch($modulo){
	case 0:?>
		<script language="javascript">
		<!--
		var responsables = parent.document.getElementById('cmbResponsable');
		for(i = responsables.length; i >= 0; i--) responsables.remove(i);
		-->
		</script>
		<?php
		$bodega = $_GET["bodega"];
		$stmt = mssql_query("EXEC General..sp_getEncargadoFondoFijo 0, '$bodega'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			responsables.options[responsables.length] = new Option('<?php echo $rst["strNombre"];?>', '<?php echo trim($rst["strUsuario"]);?>')
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);?>
		<script language="javascript">
		<!--
		responsables.disabled = false;
		-->
		</script>
		<?php
		break;
	case 1:
		$usuario = $_GET["usuario"];
		$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleCajaChica 1, '$bodega', '$usuario', '$numero'", $cnx);
		if($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtOCompra').value = '<?php echo $rst["dblUltima"];?>';
			parent.document.getElementById('txtOCompra').readOnly = true;
			parent.document.getElementById('txtCantidad').focus();
			parent.document.getElementById('txtCantidad').select();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtOCompra').readOnly = false;
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 2:
		$texto = $_GET["texto"];
		$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$texto', 'E', NULL, '$bodega', 'DC'",$cnx);
		if($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtCodigo').value = '<?php echo trim($rst["strCodigo"]);?>';
			parent.document.getElementById('txtDescripcion').value = ' <?php echo trim($rst["strDescripcion"]);?>';
			parent.document.getElementById('hdnUnidad').value = '<?php echo trim($rst["strUnidad"]);?>';
			parent.document.getElementById('cmbDocumento').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtCodigo').value = '';
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('hdnUnidad').value = '';
			alert('El código ingresado no existe.')
			parent.document.getElementById('txtCodigo').focus();
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>