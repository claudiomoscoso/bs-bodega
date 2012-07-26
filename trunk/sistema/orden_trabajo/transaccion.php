<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$contrato = $_GET["contrato"];
switch($modulo){
	case 0:?>
	<script language="javascript">
	<!--
		var ttrabajo = parent.document.getElementById('cmbTTrabajo');
		for(i = ttrabajo.length; i >=0; i--) ttrabajo.remove(i);
		
		var prioridad = parent.document.getElementById('cmbPrioridad');
		for(i = prioridad.length; i >=0; i--) prioridad.remove(i);
		
		var comuna = parent.document.getElementById('cmbComuna');
		for(i = comuna.length; i >=0; i--) comuna.remove(i);
		
		var zona = parent.document.getElementById('cmbZona');
		for(i = zona.length; i >=0; i--) zona.remove(i);
		
		var inspector = parent.document.getElementById('cmbInspector');
		for(i = inspector.length; i >=0; i--) inspector.remove(i);
	-->
	</script>
	<?php
		$stmt = mssql_query("EXEC Orden..sp_getTipoTrabajo 0, '$contrato'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			ttrabajo.options[ttrabajo.length] = new Option('<?php echo $rst["strDetalle"];?>', '<?php echo $rst["strCodigo"];?>');
		-->
		</script>
	<?php
		}
		mssql_free_result($stmt);
		
		$stmt = mssql_query("EXEC Orden..sp_getPrioridad 0, '$contrato'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			prioridad.options[prioridad.length] = new Option('<?php echo $rst["strDetalle"];?>', '<?php echo $rst["strCodigo"];?>');
		-->
		</script>
	<?php
		}
		mssql_free_result($stmt);
		
		$stmt = mssql_query("EXEC General..sp_getComunas 0, '$contrato'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			comuna.options[comuna.length] = new Option('<?php echo $rst["strDetalle"];?>', '<?php echo $rst["strCodigo"];?>');
		-->
		</script>
	<?php
		}
		mssql_free_result($stmt);
		
		$stmt = mssql_query("EXEC Orden..sp_getSector 0, '$contrato'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			zona.options[zona.length] = new Option('<?php echo $rst["strDetalle"];?>', '<?php echo $rst["strCodigo"];?>');
		-->
		</script>
	<?php
		}
		mssql_free_result($stmt);
		
		$stmt = mssql_query("EXEC Orden..sp_getInspector 0, '$contrato'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			inspector.options[inspector.length] = new Option('<?php echo $rst["strNombre"];?>', '<?php echo $rst["strCodigo"];?>');
		-->
		</script>
	<?php
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$texto = $_GET["texto"];
		$stmt = mssql_query("EXEC General..sp_getMoviles 10, '$contrato', '$texto', 'C'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtMovil').value = '<?php echo $rst["strNombre"];?>';
			parent.document.getElementById('hdnMovil').value = '<?php echo $rst["strMovil"];?>';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 2:
		$orden = $_GET["orden"];
		$stmt = mssql_query("SELECT * FROM Orden..CaratulaOrden WHERE strContrato = '$contrato' AND strOrden LIKE '__$orden'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			alert('El número de orden de trabajo ya existe.');
			parent.document.getElementById('txtOrden').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
}
mssql_close($cnx);
?>