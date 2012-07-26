<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$inmobil = $_GET["inmobil"];
switch($modulo){
	case 0:?>
	<script language="javascript">
	<!--
		var proyecto = parent.document.getElementById('cmbProyecto');
		for(i = proyecto.length; i >=0; i--) proyecto.remove(i);	-->
	</script>
	<?php
		$stmt = mssql_query("EXEC Inmobiliaria..sp_getProyectos 0, '$inmobil'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			proyecto.options[proyecto.length] = new Option('<?php echo $rst["Detalle"];?>', '<?php echo $rst["Codigo"];?>');
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
		
		$stmt = mssql_query("EXEC Orden..sp_getZonas 0, '$contrato'", $cnx);
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
		$proyecto = $_GET["proyecto"];
		$lote = $_GET["lote"];
		$stmt = mssql_query("EXEC Inmobiliaria..sp_getLote 0, '$proyecto', '$lote'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtLote').value = '<?php echo $rst["Lote"];?>';
			parent.document.getElementById('txtPiso').value = '<?php echo $rst["Piso"];?>';
			parent.document.getElementById('txtCoordenadas').value = '<?php echo $rst["Coordenadas"];?>';
			parent.document.getElementById('txtDireccion').value = '<?php echo $rst["Direccion"];?>';
			parent.document.getElementById('txtComuna').value = '<?php echo $rst["Comuna"];?>';
			parent.document.getElementById('txtNombre').value = '<?php echo $rst["Nombre"];?>';
			parent.document.getElementById('txtApellido').value = '<?php echo $rst["Apellido"];?>';
			parent.document.getElementById('txtFono').value = '<?php echo $rst["Fono"];?>';
			parent.document.getElementById('txtCelular').value = '<?php echo $rst["Celular"];?>';
			parent.document.getElementById('txtEmail').value = '<?php echo $rst["Email"];?>';
			parent.document.getElementById('txtArrendatario').value = '<?php echo $rst["Arrendatario"];?>';
			parent.document.getElementById('txtFonoArrendatario').value = '<?php echo $rst["FonoArrendatario"];?>';
			parent.document.getElementById('txtObservacion').value = '<?php echo $rst["Observacion"];?>';
			parent.document.getElementById('cmbInmobil').disabled = true;			
			parent.document.getElementById('cmbProyecto').disabled = true;			
		-->
		</script>
		<?php
		}
		else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtPiso').value = '';
			parent.document.getElementById('txtCoordenadas').value = '';
			parent.document.getElementById('txtDireccion').value = '';
			parent.document.getElementById('txtComuna').value = '';
			parent.document.getElementById('txtNombre').value = '';
			parent.document.getElementById('txtApellido').value = '';
			parent.document.getElementById('txtFono').value = '';
			parent.document.getElementById('txtCelular').value = '';
			parent.document.getElementById('txtEmail').value = '';
			parent.document.getElementById('txtArrendatario').value = '';
			parent.document.getElementById('txtFonoArrendatario').value = '';
			parent.document.getElementById('txtObservacion').value = '';
			parent.document.getElementById('cmbInmobil').disabled = false;			
			parent.document.getElementById('cmbProyecto').disabled = false;			
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 2:
		$orden = $_GET["lote"];
		$stmt = mssql_query("SELECT * FROM Inmobiliaria..Lote WHERE Proyecto = '$proyecto' AND lote = $lote", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			alert('Este Lote ya está ingresado.');
			parent.document.getElementById('txtLote').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
}
mssql_close($cnx);
?>