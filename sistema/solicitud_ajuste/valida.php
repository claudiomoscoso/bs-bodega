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
			$stmt = mssql_query("SELECT dblNumero FROM Despacho WHERE dblNumero = $valor", $cnx);
			if($rst = mssql_fetch_array($stmt)){?>
			<script language="javascript">
				alert('El numero de guía de despacho ya existe.');
				parent.document.getElementById('txtNumero').value='';
				parent.document.getElementById('txtNumero').focus();
			</script>
			<?php
			}else{
				$stmt1 = mssql_query("EXEC Bodega..sp_getUltimaGD '$bodega', $valor", $cnx);
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
	case 2:
		$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor', 'E', 'M', '$bodega', 'GD'", $cnx);
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