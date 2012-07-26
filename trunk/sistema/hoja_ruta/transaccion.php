<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
if($modulo == 0){
	$usuario = $_GET["usuario"];
	$correlativo = $_GET["correlativo"];
	$valor = $_GET["valor"];
	mssql_query("EXEC Orden..sp_setTMPOrdenTrabajo 0, '$usuario', '$correlativo', '$valor'", $cnx);
}elseif($modulo == 1){
	$contrato = $_GET["contrato"];
	$stmt = mssql_query("SELECT strDetalle, CONVERT(VARCHAR, dtmActividad, 120) AS dtmFecha FROM Orden..RegistroAutomata WHERE strContrato='$contrato'", $cnx);
	if($rst = mssql_fetch_array($stmt)){?>
	<script language="javascript">
	<!--
		parent.document.getElementById('txtEstadoRecepcion').value = ' Último archivo recibido: <?php echo formato_fecha($rst["dtmFecha"], false, false);?>.';
	-->
	</script>
	<?php
	}else{?>
	<script language="javascript">
	<!--
		parent.document.getElementById('txtEstadoRecepcion').value = '';
	-->
	</script>
	<?php
	}
	mssql_free_result($stmt);
}

mssql_close($cnx);
?>