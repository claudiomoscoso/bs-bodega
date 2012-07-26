<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$codigo = $_GET["codigo"];
$modulo = $_GET["modulo"];
$valor = $_GET["valor"];

switch($modulo){
	case 0:
	case 1:
		mssql_query("EXEC Bodega..sp_setTMPTerminoBodega $modulo, '$usuario', '$codigo', '$valor'", $cnx);
		break;
	case 3:
		$stmt = mssql_query("EXEC Bodega..sp_getMateriales '$valor', 'E', 'M', NULL, 'ALL'",$cnx);
		if($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtCodigo').value = '<?php echo trim($rst["strCodigo"]);?>';
			parent.document.getElementById('txtDescripcion').value = ' <?php echo $rst["strDescripcion"];?>';
			parent.document.getElementById('txtDevuelve').value = 0;
			parent.document.getElementById('txtDevuelve').focus();
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtCodigo').value = '';
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('txtDevuelve').value = 0;
			parent.document.getElementById('txtCodigo').focus();
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>