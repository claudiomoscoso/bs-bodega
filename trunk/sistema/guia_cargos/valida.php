<?php
include '../conexion.inc.php';
$tipo=$_GET["tipo"];
$bodega=$_GET["bodega"];
$usuario=$_GET["usuario"];
$valor=$_GET["valor"];

if($tipo=='M')
	$sql="EXEC sp_getMateriales '$valor', 'E', NULL, '$bodega', 'GCAR'";
elseif($tipo=='CRG')
	$sql="EXEC General..sp_getPersonalObra 3, '$usuario', '$valor'";
elseif($tipo=='NGD'){
	$valor=$valor!='' ? $valor : 'NULL';
	$sql="EXEC sp_getNumeroDespacho $valor";
}

$stmt = mssql_query($sql, $cnx);
if($rst=mssql_fetch_array($stmt)){
	if($tipo=='M'){?>
	<script language="javascript">
	parent.document.getElementById('descripcion').value='<?php echo trim($rst["strDescripcion"]);?>';
	parent.document.getElementById('unidad').value='<?php echo trim($rst["strUnidad"]);?>';
	parent.document.getElementById('stock').value='<?php echo trim($rst["dblStock"]);?>';
	parent.document.getElementById('cantidad').focus();
	</script>
<?php
	}elseif($tipo=='CRG'){?>
	<script language="javascript">
	parent.document.getElementById('cargo').value='<?php echo trim($rst["strRut"]);?>';
	parent.document.getElementById('desc_cargo').value='<?php echo trim($rst["strNombre"]);?>';
	parent.document.getElementById('observacion').focus();
	</script>
<?php
	}elseif($tipo=='NGD' && $rst["dblExiste"]==1){?>
	<script language="javascript">
	alert('El numero de Guia de Cargos ya existe.');
	parent.document.getElementById('numGD').value='';
	parent.document.getElementById('numGD').focus();
	</script>
<?php
	}
}else{
	if($tipo=='M'){?>
	<script language="javascript">
	parent.document.getElementById('descripcion').value='';
	parent.document.getElementById('unidad').value='';
	parent.document.getElementById('stock').value='';
	</script>
<?php
	}elseif($tipo=='MVL'){?>
	<script language="javascript">
	parent.document.getElementById('cargo').value='';
	parent.document.getElementById('desc_cargo').value=' -- Ingrese el código o la descripción y presione ENTER --';
	</script>
<?php
	}elseif($tipo=='NGD'){
		$stmt1 = mssql_query("EXEC sp_getUltimaGD '$bodega', $valor", $cnx);
		if($rst1 = mssql_fetch_array($stmt1)) $UltGD = $rst1["dblNumero"];
		mssql_free_result($stmt1);
		if($valor > ($UltGD + 6)){?>
		<script language="javascript">
		alert('¿Está seguro que el número de Guía de Despacho ingresado es correcto?.');
		</script>
<?php	}
	}
}
mssql_free_result($stmt);
mssql_close($cnx);
?>