<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$correlativo = $_GET["correlativo"];
$envia = $_GET["envia"];
switch($modulo){
	case 0:
		mssql_query("EXEC Orden..sp_setTMPEnvioOrdenTrabajo 0, '$usuario', NULL, $envia", $cnx);
		break;
	case 1:
		mssql_query("EXEC Orden..sp_setTMPEnvioOrdenTrabajo 1, '$usuario', $correlativo, $envia", $cnx);
		break;
	case 2:
		mssql_query("EXEC Orden..sp_setTMPEnvioOrdenTrabajo 2, '$usuario', NULL, $envia", $cnx);
		break;
	case 3:
		mssql_query("EXEC Orden..sp_setTMPEnvioOrdenTrabajo 3, '$usuario', $correlativo, $envia", $cnx);
		break;
	case 4:
		mssql_query("EXEC Orden..sp_setEnviaOrdenTrabajo 1, '$usuario'", $cnx);?>
		<script language="javascript">
		<!--
		parent.document.getElementById('resultado').src = '../blank.html';
		parent.document.getElementById('txtNEnvio').value = '';
		parent.Deshabilita(false);
		-->
		</script>
	<?php
		break;
	case 5:
		mssql_query("EXEC Orden..sp_setTMPEnvioOrdenTrabajo 4, '$usuario', NULL, $envia", $cnx);
		break;
	case 6:
		mssql_query("EXEC Orden..sp_setTMPEnvioOrdenTrabajo 5, '$usuario', $correlativo, $envia", $cnx);
		break;
}
mssql_close($cnx);
?>