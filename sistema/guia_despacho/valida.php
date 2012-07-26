<?php
include '../conexion.inc.php';
$modulo = $_GET["modulo"];
$bodega = $_GET["bodega"];
$valor = $_GET["valor"];
$tcargo = $_GET["tcargo"];
$usuario = $_GET["usuario"];
$perfil = $_GET["perfil"];
switch($modulo){
	case 0:
		if($valor){
			$stmt = mssql_query("SELECT Despacho.dblNumero, Bodegas.strDetalle FROM Despacho INNER JOIN General..Tablon AS Bodegas ON (Despacho.strBodega = Bodegas.strCodigo AND Bodegas.strTabla = 'bodeg') WHERE Despacho.dblNumero = $valor UNION SELECT CaratulaGuiaCargo.dblNumero, Bodegas.strDetalle FROM CaratulaGuiaCargo INNER JOIN General..Tablon AS Bodegas ON (CaratulaGuiaCargo.strBodega = Bodegas.strCodigo AND Bodegas.strTabla = 'bodeg') WHERE CaratulaGuiaCargo.dblNumero =$valor", $cnx);
			if($rst = mssql_fetch_array($stmt)){?>
			<script language="javascript">
				alert('El numero de guía de despacho ya existe en la bodega <?php echo $rst["strDetalle"];?>.');
				parent.document.getElementById('txtNumero').value='';
				parent.document.getElementById('txtNumero').focus();
			</script>
			<?php
			}else{
				$stmt1 = mssql_query("EXEC Bodega..sp_getUltimaGD '$bodega', 0", $cnx);
				if($rst1 = mssql_fetch_array($stmt1)) $UltGD = $rst1["dblNumero"];
				mssql_free_result($stmt1);
				if($valor > ($UltGD + 6)){?>
				<script language="javascript">alert('Está seguro que el número de Guía de Despacho ingresado es correcto.');</script>
			<?php
				}
			}
			mssql_free_result($stmt);
		}
		break;
	case 1:
		$stmt = mssql_query("EXEC General..sp_getMoviles 9, '$bodega', '$valor', 'E', '$perfil'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		parent.document.getElementById('hdnTipo').value = '<?php echo trim($rst["strTipo"]);?>';
		parent.document.getElementById('hdnCargo').value = '<?php echo trim($rst["strMovil"]);?>';
		parent.document.getElementById('txtCargo').value = '<?php echo trim($rst["strNombre"]);?>';
		parent.document.getElementById('txtObservacion').focus();
		</script>
		<?php
		}else{?>
		<script language="javascript">
		parent.document.getElementById('hdnTipo').value = '';
		parent.document.getElementById('hdnCargo').value = '';
		parent.document.getElementById('txtCargo').value = '';
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 2:
		$tcargo = $_GET["tcargo"]!='' ? $_GET["tcargo"] : 'NULL';
		$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor', 'E', 'M', '$bodega', 'GD', $tcargo", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
			parent.document.getElementById('txtDescripcion').value = '<?php echo trim($rst["strDescripcion"]);?>';
			parent.document.getElementById('txtUnidad').value = '<?php echo trim($rst["strUnidad"]);?>';
			parent.document.getElementById('hdnStock').value = '<?php echo trim($rst["dblStock"]);?>';
			parent.document.getElementById('txtCantidad').focus();
		</script>
		<?php
		}else{?>
		<script language="javascript">
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('txtUnidad').value = '';
			parent.document.getElementById('hdnStock').value = '';
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>