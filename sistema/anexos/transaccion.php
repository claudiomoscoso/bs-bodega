<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$contrato = $_GET["contrato"];
$usuario = $_GET["usuario"];
switch($modulo){
	case 0:?>
	<script language="javascript">
	<!--
		var moviles = parent.document.getElementById('cmbMovil');
		for(i = moviles.length; i > 0; i--) moviles.remove(i);
	-->
	</script>
<?php
		if($contrato == '13045')
			$stmt = mssql_query("SELECT DISTINCT Moviles.strMovil, Personal.strNombre FROM General..Movil AS Moviles INNER JOIN General..PersonalObras AS Personal ON (Moviles.strRut = Personal.strRut) WHERE Moviles.strContrato = '13045' ORDER BY Personal.strNombre", $cnx);
		else
			$stmt = mssql_query("EXEC General..sp_getMoviles 6, NULL, '$contrato'", $cnx);
		while($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			moviles.options[moviles.length] = new Option('<?php echo '['.trim($rst["strMovil"]).'] '.$rst["strNombre"];?>', '<?php echo trim($rst["strMovil"]);?>');
		-->
		</script>
<?php	}
		mssql_free_result($stmt);
		break;
	case 1:
		$codigo = $_GET["codigo"];
		$ind = $_GET["ind"];
		
		$stmt = mssql_query("EXEC Orden..sp_getItems 0, 0, '$codigo', '$contrato'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtDescripcion<?php echo $ind;?>').value = '<?php echo $rst["strDescripcion"];?>';
			parent.document.getElementById('txtUnidad<?php echo $ind;?>').value = '<?php echo $rst["strUnidad"];?>';
			parent.document.getElementById('hdnCantidad<?php echo $ind;?>').value = '<?php echo $rst["intCantidades"];?>';
			parent.document.getElementById('txtCantidad<?php echo $ind;?>').value = '';
		-->
		</script>
<?php	}else{?>
		<script language="javascript">
		<!--
			alert('El código ingresa no existe.');
			parent.document.getElementById('txtCodigo').value = '';
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('txtUnidad').value = '';
			parent.document.getElementById('hdnCantidad').value = '';
		-->
		</script>
<?php	}
		mssql_free_result($stmt);
		break;
	case 2:
		$codigo = $_GET["codigo"];
		$ind = $_GET["ind"];
		$valor_ant = $_GET["valor_ant"];
		$valor_act = $_GET["valor_act"];
		
		$stmt = mssql_query("EXEC Orden..sp_setTMPDetalleInformaTrabajos 1, '$usuario', '$valor_ant', '$codigo', NULL, NULL, '$valor_act'", $cnx);
		if($rst=mssql_fetch_array($stmt)){
			if($rst["dblError"] == 1){?>
			<script language="javascript">
			<!--
				alert('Este ítem ya se encuentra informado.');
			-->
			</script>
		<?php
			}else{?>
			<script language="javascript">
			<!--
				parent.document.getElementById('hdnMovil<?php echo $ind;?>').value = '<?php echo $valor_act;?>';
			-->
			</script>
		<?php
			}
		}
		mssql_free_result($stmt);
		break;
	case 3:
		$codigo = $_GET["codigo"];
		$movil = $_GET["movil"];
		$ind = $_GET["ind"];
		$valor_act = $_GET["valor_act"];
		list($cant1, $cant2, $cant3) = split('x', $valor_act);
		if($cant3 != '')
			$cantidad = $cant1 * $cant2 * $cant3;
		elseif($cant2 != '')
			$cantidad = $cant1 * $cant2;
		else
			$cantidad = $cant1;

		mssql_query("EXEC Orden..sp_setTMPDetalleInformaTrabajos 2, '$usuario', '$movil', '$codigo', '$valor_act', $cantidad", $cnx);
		?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnCubicacion<?php echo $ind;?>').value = '<?php echo $valor_act;?>';
		-->
		</script>
<?php	break;
	case 4:
		include '../autentica.php';
		$numero = $_GET["numero"];

		mssql_query("EXEC Orden..sp_setDetalleInformaTrabajos 0, '$usuario', $numero", $cnx);
		?>
		<script language="javascript">
		<!--
			parent.location.href = 'index.php<?php echo $parametros;?>';
		-->
		</script>
<?php	break;
	case 5:
		$numero = $_POST["numero"];
		$movil = $_POST["cmbMovil"];		
		$anexo = $_POST["cmbTAnexo"];
		$cerrada = $_GET["cerrada"];
		$estado = $_GET["estado"];
		if($cerrada == 1 || $cerrada == 2)
			$observacion = 'Posterior a cierre ODT: '.$_POST["txtTrabajo"];
		else
			$observacion = $_POST["hdnTAnexo"].': '.$_POST["txtTrabajo"];

		$stmt = mssql_query("EXEC Orden..sp_setAnexo 0, '$usuario', $numero, '$contrato', '$movil', '$observacion', '$anexo', $cerrada", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
			switch(parseInt('<?php echo $rst["dblError"];?>')){
				case 0:
					parent.parent.CambiaPestana(1);
					break;
				case 1:
					alert('Ya existe el movil anexo para esta orden de trabajo.');
					break;
				case 2:
					alert('No es posible obtener el correlativo.');
					break;
				case 3:
					alert('Debe haber una reposicion para crear un retiro de exced. pav.');
					break;
				case 4:
					alert('No es posible obtener las propiedades de tipo de anexo.')
			}
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 6:
		$correlativo = $_GET["correlativo"];
		$anexo = $_GET["anexo"];
		$valor = $_GET["valor"];

		mssql_query("EXEC Orden..sp_setAnexo 1, '$usuario', $correlativo, '$contrato', '$anexo', NULL, NULL, $valor", $cnx);
		break;
}
mssql_close($cnx);
?>