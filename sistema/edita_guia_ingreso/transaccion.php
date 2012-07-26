<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
switch($modulo){
	case 0:
		$bodega = $_GET["bodega"];
		$numero = $_GET["numoc"];
		$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'GI', $numero, '%', '$bodega'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtProveedor').value = '<?php echo $rst["strNombre"];?>';
			parent.document.getElementById('txtDireccion').value = '<?php echo $rst["strDireccion"];?>';
			parent.document.getElementById('txtComuna').value = '<?php echo $rst["Comuna"];?>';
			parent.document.getElementById('txtTelefono').value = '<?php echo $rst["strTelefono"];?>';
			parent.document.getElementById('txtFax').value = '<?php echo $rst["strFax"];?>';
			parent.document.getElementById('txtAtencion').value = '<?php echo $rst["strContacto"];?>';
			parent.document.getElementById('hdnOCompra').value = '<?php echo $rst["dblNumero"];?>';
			parent.document.getElementById('txtObservacion').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtOCompra').value='';
			parent.document.getElementById('hdnOCompra').value='';
			parent.document.getElementById('txtOCompra').focus();
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$bodega = $_GET["bodega"];
		$usuario = $_GET["usuario"];
		$codigo = $_GET["codigo"];
		$cantidad = $_GET["cantidad"];
		$valor = $_GET["valor"];
		$cantant = $_GET["cantant"];
		$id = $_GET["id"];

		$stmt = mssql_query("EXEC Bodega..sp_setTMPDetalleGuiaIngreso 2, '$usuario', '$codigo', $cantidad, $valor, '$bodega', $cantant", $cnx);
		if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
		mssql_free_result($stmt);?>
		<script language="javascript">
		if(parseInt('<?php echo $error;?>') == 1){
			alert('No es posible cambiar la cantidad ya que el Kardex quedaría negativo.');
			parent.detalle.document.getElementById('txtCantidad<?php echo $id;?>').value = '<?php echo $cantant;?>';
		}else
			parent.detalle.document.getElementById('hdnCantidad<?php echo $id;?>').value = '<?php echo $cantidad;?>';
		</script>
		<?php
		break;
}
mssql_close($cnx);
?>
