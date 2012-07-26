<?php
include '../conexion.inc.php';

$stmt = mssql_query("EXEC Bodega..sp_getProveedor 4, 'E', NULL, '".$_GET["valor"]."'", $cnx);
if($rst=mssql_fetch_array($stmt)){?>
	<script language="javascript">
		parent.document.getElementById('strCodigo').value ='<?php echo $rst["strCodigo"];?>';
		parent.document.getElementById('proveedor').value =' <?php echo ReemplazaInv($rst["strNombre"]);?>';
		parent.document.getElementById('direccion').value =' <?php echo ReemplazaInv($rst["strDireccion"]);?>';
		parent.document.getElementById('comuna').value =' <?php echo $rst["strDetalle"];?>';
		parent.document.getElementById('telefono').value =' <?php echo $rst["strTelefono"];?>';
		parent.document.getElementById('fax').value =' <?php echo $rst["strFax"];?>';
		parent.document.getElementById('atencion').value =' <?php echo $rst["strContacto"];?>';
		for(i = 0; i < parent.document.getElementById('forma_pago').options.length; i++){
			if(parseInt(parent.document.getElementById('forma_pago').options[i].value) == parseInt('<?php echo $rst["intFormaPago"];?>')){
				parent.document.getElementById('forma_pago').selectedIndex = i;
				break;
			}
		}			
		parent.document.getElementById('email').value ='<?php echo $rst["strCorreo"];?>';
		parent.document.getElementById('email').focus();
	</script>
<?php
}else{?>
	<script language="javascript">
	parent.LimpiaDatosProveedor();
	</script>
<?php
}
mssql_free_result($stmt);
?>