<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$bodega = $_GET["bodega"];
$usuario = $_GET["usuario"];
$valor = $_GET["valor"];

switch($modulo){
	case 0:
		$stmt = mssql_query("EXEC Bodega..sp_getNumeroDespacho $valor", $cnx);
		if($rst = mssql_fetch_array($stmt)) $existe = $rst["dblExiste"];
		mssql_free_result($stmt);
		if($existe == 1){?>
		<script language="javascript">
			alert('El número de guía de cargos que intenta ingresar ya existe.');
			parent.document.getElementById('txtNumero').value = '';
			parent.document.getElementById('txtNumero').focus();
		</script>
		<?php
		}else{
			$stmt1 = mssql_query("EXEC Bodega..sp_getUltimaGD '$bodega', 1", $cnx);
			if($rst1 = mssql_fetch_array($stmt1)) $UltGD = $rst1["dblNumero"];
			mssql_free_result($stmt1);
			if($valor > ($UltGD + 6)){?>
			<script language="javascript">alert('ATENCIÓN: Revise que el número de guía de cargo ingresado es correcto.');</script>
			<?php
			}
		}
		break;
	case 1:
		$stmt = mssql_query("EXEC General..sp_getPersonalObra 2, '$bodega', '$valor'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
			parent.document.getElementById('hdnCargo').value = '<?php echo trim($rst["strRut"]);?>';
			parent.document.getElementById('txtCargo').value = '<?php echo trim($rst["strNombre"]);?>';
			parent.document.getElementById('txtObservacion').focus();
		</script>
		<?php
		}else{?>
		<script language="javascript">
			parent.document.getElementById('hdnCargo').value = '';
			parent.document.getElementById('txtCargo').value = ' -- Ingrese el código o la descripción y presione ENTER --';
		</script>
		<?php		
		}
		mssql_free_result($stmt);
		break;
	case 2:
		$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor', 'E', NULL, '$bodega', 'GCAR'", $cnx);
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