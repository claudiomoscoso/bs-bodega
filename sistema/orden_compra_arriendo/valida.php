<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$valor = trim($_GET["valor"]);
$foco = $_GET["foco"];
switch($modulo){
	case 0:
		$stmt = mssql_query("EXEC Bodega..sp_getProveedor 4, 'E', NULL, '$valor'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnProveedor').value ='<?php echo $rst["strCodigo"];?>';
			parent.document.getElementById('txtProveedor').value =' <?php echo $rst["strNombre"];?>';
			parent.document.getElementById('txtDireccion').value =' <?php echo $rst["strDireccion"];?>';
			parent.document.getElementById('txtComuna').value =' <?php echo $rst["strDetalle"];?>';
			parent.document.getElementById('txtTelefono').value =' <?php echo $rst["strTelefono"];?>';
			parent.document.getElementById('txtFax').value =' <?php echo $rst["strFax"];?>';
			parent.document.getElementById('txtAtencion').value =' <?php echo $rst["strContacto"];?>';
			var fpago = parent.document.getElementById('cmbFPago');
			for(i = 0; i < fpago.length; i++){
				if(parseInt(fpago.options[i].value) == parseInt('<?php echo $rst["intFormaPago"];?>')){
					fpago.selectedIndex = i;
					break;
				}
			}			
			parent.document.getElementById('<?php echo $foco;?>').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.LimpiaDatosProveedor();
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor', 'E', 'T', NULL, 'OC'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtDescripcion').value='<?php echo trim($rst["strDescripcion"]);?>';
			parent.document.getElementById('txtUnidad').value='<?php echo trim(strtoupper($rst["strUnidad"]));?>';
			parent.document.getElementById('hdnStock').value='<?php echo $rst["dblStock"];?>';
			parent.document.getElementById('<?php echo $foco;?>').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.LimpiaDetalle();
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 2:
		$valor = $valor != '' ? $valor : 0;
		$cargo = $_GET["cargo"];
		$stmt = mssql_query("EXEC Operaciones..sp_getSolicitudes 'SP', NULL, '$cargo', $valor", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtSolicitud').value='<?php echo $rst["dblNumero"];?>';
			parent.document.getElementById('<?php echo $foco;?>').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtSolicitud').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 3:?>
		<script language="javascript">
		var cargo = parent.document.getElementById('cmbCargo');
		for(i = cargo.length; i >= 0; i--) cargo.remove(i);
		</script>
		<?php
		$stmt = mssql_query("EXEC General..sp_getCargos 1, '$valor'", $cnx);
		while($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			cargo.options[cargo.length] = new Option('<?php echo $rst["strCargo"];?>', '<?php echo $rst["strCodigo"];?>');
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);?>
		<script language="javascript">
		<!--
		cargo.disabled = false;
		-->
		</script>
		<?php
	case 4:
		$stmt = mssql_query("EXEC Operaciones..sp_getCentroCosto 5, '$valor'",$cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnCCosto').value = '<?php echo trim($rst["strCCosto"]);?>';
			parent.document.getElementById('txtCCosto').value = ' <?php echo trim($rst["strCCosto"]);?>';
			parent.document.getElementById('txtValor').value = ' <?php echo number_format($rst["dblPrecio"], 0, '', '');?>';
			parent.document.getElementById('cmbDPago').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnCCosto').value = '';
			parent.document.getElementById('txtCCosto').value = '';
			parent.document.getElementById('cmbDPago').focus();
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}

mssql_close($cnx);
?>