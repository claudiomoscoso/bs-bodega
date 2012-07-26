<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$idanexo = $_GET["idanexo"];
switch($modulo){
	case 0:
		$contrato = $_GET["contrato"];?>
		<script language="javascript">
		<!--
		var moviles = parent.document.getElementById('cmbMovil');
		for(i = moviles.length; i > 0; i--) moviles.remove(i);
		-->
		</script>
		<?php
		$stmt = mssql_query("EXEC General..sp_getMoviles 6, NULL, '$contrato'", $cnx);
		while($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			moviles.options[moviles.length] = new Option('<?php echo '['.trim($rst["strMovil"]).'] '.$rst["strNombre"];?>', '<?php echo trim($rst["strMovil"]);?>');
		-->
		</script>
	<?php	
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$codigo = $_GET["codigo"];
		$contrato = $_GET["contrato"];
		$ind = $_GET["ind"];
		
		$stmt = mssql_query("EXEC Orden..sp_getItems 0, 0, '$codigo', '$contrato'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtDescripcion<?php echo $ind;?>').value = '<?php echo $rst["strDescripcion"];?>';
			parent.document.getElementById('txtUnidad<?php echo $ind;?>').value = '<?php echo $rst["strUnidad"];?>';
			parent.document.getElementById('hdnCantidades<?php echo $ind;?>').value = '<?php echo $rst["intCantidades"];?>';
			parent.document.getElementById('txtCantInf<?php echo $ind;?>').value = '';
		-->
		</script>
<?php	}else{?>
		<script language="javascript">
		<!--
			alert('El código ingresa no existe.');
			parent.document.getElementById('txtCodigo').value = '';
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('txtUnidad').value = '';
			parent.document.getElementById('hdnCantidades').value = '';
		-->
		</script>
<?php	}
		mssql_free_result($stmt);
		break;
	case 2:
		$usuario = $_GET["usuario"];
		$codigo = $_GET["codigo"];
		$ind = $_GET["ind"];
		$valor_ant = $_GET["valor_ant"];
		$valor_act = $_GET["valor_act"];
		
		$stmt = mssql_query("EXEC Orden..sp_setTMPDetalleInformaTrabajos 1, '$usuario', '$valor_ant', '$codigo', NULL, NULL, '$valor_act', NULL, NULL, $idanexo", $cnx);
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
		$usuario = $_GET["usuario"];
		$codigo = $_GET["codigo"];
		$idanexo = $_GET["idanexo"];
		$movil = $_GET["movil"];
		$ind = $_GET["ind"];
		$cubicacion = $_GET["cubicacion"];
		$cantidad = $_GET["valor_act"];
		mssql_query("EXEC Orden..sp_setTMPDetalleInformaTrabajos 2, '$usuario', '$movil', '$codigo', '$cubicacion', $cantidad, NULL, NULL, NULL, $idanexo", $cnx);
		?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnCubicacion<?php echo $ind;?>').value = '<?php echo $cubicacion;?>';
			parent.document.getElementById('hdnCantInf<?php echo $ind;?>').value = '<?php echo $cantidad;?>';
		-->
		</script>
<?php	break;
	case 4:
		include '../autentica.php';
		$numero = $_GET["numero"];
		mssql_query("EXEC Orden..sp_setDetalleInformaTrabajos 0, '$usuario', $numero", $cnx);?>
		<script language="javascript">
		parent.parent.CambiaPestana(1);
		</script>
<?php	break;
	case 5:
		$usuario = $_GET["usuario"];
		$movil = $_GET["movil"];
		$codigo = $_GET["codigo"];
		$cerrar = $_GET["cerrar"];

		mssql_query("EXEC Orden..sp_setTMPDetalleInformaTrabajos 4, '$usuario', '$movil', '$codigo', NULL, NULL, NULL, $cerrar, NULL, $idanexo", $cnx);
		break;
	case 6:
		$usuario = $_GET["usuario"];
		$perfil = $_GET["perfil"];
		$numero = $_GET["numero"];
		$contrato = $_GET["contrato"];
		$codigo = $_GET["codigo"];
		$idanexo = $_GET["idanexo"];
		$movil = $_GET["movil"];
		$ind = $_GET["ind"];
		$valor_act = $_GET["valor_act"];
		
		mssql_query("EXEC Orden..sp_setTMPDetalleInformaTrabajos 5, '$usuario', '$movil', '$codigo', NULL, '$valor_act', NULL, NULL, NULL, $idanexo", $cnx);
		$stmt = mssql_query("EXEC Orden..sp_getTMPDetalleInformaTrabajos 2, '$usuario', '$contrato', $numero", $cnx);
		if($rst = mssql_fetch_array($stmt)) $total = $rst["dblTotal"];
		mssql_free_result($stmt);?>
		<script language="javascript">
		<!--
		parent.document.getElementById('hdnCantPag<?php echo $ind;?>').value = '<?php echo $valor_act;?>';
		if('<?php echo $perfil;?>' == 'j.cobranza' || '<?php echo $perfil;?>' == 'informatica')
			parent.parent.document.getElementById('divTotal').innerHTML = '<b>TOTAL:</b> <?php echo number_format($total, 0, '', '.');?>.-';
		-->
		</script>
<?php	break;
	case 7:
		$numero = $_GET["numero"];
		$usuario = $_GET["usuario"];
		mssql_query("EXEC Orden..sp_setEstadoPago 2, '$numero', NULL, NULL, NULL, '$usuario'", $cnx);
		break;
}

mssql_close($cnx);
?>