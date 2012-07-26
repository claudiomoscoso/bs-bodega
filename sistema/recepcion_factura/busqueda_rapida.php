<?php
include '../conexion.inc.php';
$sw=0;
$modulo=$_GET["modulo"];
if($modulo == 'OC'){
	$cargo = $_GET["cargo"];
	$numoc = $_GET["numoc"];

	$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'RF', $numoc, '%', '$cargo'", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		$intoc = $rst["dblNumero"];
		$codprov = $rst["strProveedor"];
		$nombprov = $rst["strNombre"];
		$monto = $rst["dblMonto"];
		$sw = 1;
	}
	mssql_free_result($stmt);
}elseif($modulo == 'PRV'){
	$prov = trim($_GET["prov"]);
	$stmt = mssql_query("EXEC Bodega..sp_getProveedor 4, 'E', NULL, '$prov'", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$codprov = $rst["strCodigo"];
		$proveedor = $rst["strProveedor"];
		$nombprov = $rst["strNombre"];
		$sw = 1;
	}
	mssql_free_result($stmt);
}elseif($modulo == 'DOC'){
	$numdoc=$_GET["numdoc"];
	$proveedor=$_GET["proveedor"];
	$tipodoc=$_GET["tipodoc"];
	$stmt = mssql_query("EXEC Bodega..sp_getFacturas 3, NULL, NULL, $numdoc, '$proveedor', NULL, $tipodoc", $cnx);
	if($rst=mssql_fetch_array($stmt)) $sw=1;
	mssql_free_result($stmt);
}elseif($modulo == 'CRG'){
	$texto = trim($_GET["texto"]);
	$stmt = mssql_query("EXEC General..sp_getCargos 3, NULL, '$texto'", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$codcargo = trim($rst["strCodigo"]);
		$desccargo = trim($rst["strDetalle"]);
		$sw = 1;
	}
	mssql_free_result($stmt);
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $sw;?>'=='1' && '<?php echo $modulo;?>'=='OC'){
		parent.document.getElementById('txtProveedor').value=' <?php echo $nombprov;?>';
		parent.document.getElementById('hdnProveedor').value='<?php echo $codprov;?>';
		parent.document.getElementById('txtMonto').value='<?php echo number_format($monto, 0, '', '');?>';

		var ordenes = '';
		var ocompras = parent.document.getElementById('lstOCompras');
		ocompras.options[ocompras.length] = new Option('<?php echo $numoc.' ['.$intoc.']';?>', '<?php echo $intoc;?>');
		for(i = 0; i < ocompras.length; i++){
			ordenes += ocompras.options[i].value + ',';
		}
		ordenes = ordenes.substr(0, ordenes.length - 1);
		parent.document.getElementById('frmDetalleOC').src = 'detalle_orden_compra.php?ordenes=' + ordenes;
	}else if('<?php echo $sw;?>'=='1' && '<?php echo $modulo;?>'=='PRV'){
		parent.document.getElementById('hdnProveedor').value='<?php echo $codprov;?>';
		parent.document.getElementById('txtProveedor').value=' <?php echo $nombprov;?>';
	}else if('<?php echo $sw;?>'=='1' && '<?php echo $modulo;?>'=='DOC'){
		alert('El número de documento ingresado ya existe.');
		parent.document.getElementById('txtNumDoc').value='';
	}else if('<?php echo $sw;?>'=='1' && '<?php echo $modulo;?>'=='CRG'){
		parent.document.getElementById('hdnCargo').value='<?php echo $codcargo;?>';
		parent.document.getElementById('txtCargo').value=' <?php echo $desccargo;?>';
	}else{
		if('<?php echo $modulo;?>'=='OC'){
			alert('El número de orden de compra ingresando no existe.');
			//parent.document.getElementById('hdnIntOC').value='';
			parent.document.getElementById('txtNumOC').value='';
		}else if('<?php echo $modulo;?>'=='PRV'){
			alert('El proveedor ingresado no existe.');
			parent.document.getElementById('hdnProveedor').value='';
			parent.document.getElementById('txtProveedor').value='';
		}
	}
}
-->
</script>
<body onLoad="javascript: Load()">
</body>
</html>
<?php
mssql_close($cnx);
?>