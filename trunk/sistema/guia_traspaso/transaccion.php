<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$valor = $_GET["valor"];
switch($modulo){
	case 0:
		$sql = "SELECT dblNumero FROM Despacho WHERE dblNumero = $valor";
		break;
	case 1:
		$sql = "EXEC General..sp_getMoviles 7, NULL, '$valor', 'E'";
		break;
	case 2:
		$sql = "EXEC sp_getMateriales '$valor', 'E', 'M', NULL, 'GT'";
		break;	
}

$stmt = mssql_query($sql, $cnx);
if($rst = mssql_fetch_array($stmt)){
	switch($modulo){
		case 0:
		?>
			<script language="javascript">
			alert('El número de guía de traspaso ya existe.');
			parent.document.getElementById('txtNumero').value = '';
			parent.document.getElementById('txtNumero').focus();
			</script>
		<?php
			break;
		case 1:
		?>
			<script language="javascript">
			parent.document.getElementById('hdnCargo').value='<?php echo trim($rst["strMovil"]);?>';
			parent.document.getElementById('txtCargo').value='<?php echo trim($rst["strNombre"]);?>';
			parent.document.getElementById('txtObservacion').focus();
			</script>
		<?php
			break;
		case 2:
		?>
			<script language="javascript">
			parent.document.getElementById('txtDescripcion').value='<?php echo trim($rst["strDescripcion"]);?>';
			parent.document.getElementById('txtUnidad').value='<?php echo trim($rst["strUnidad"]);?>';
			parent.document.getElementById('hdnStock').value='<?php echo number_format($rst["dblStock"], 2, '.', '');?>';
			parent.document.getElementById('txtCantidad').focus();
			</script>
		<?php
			break;
	}
}else{
	switch($modulo){
		case 0:
		?>
			<script language="javascript">
			parent.document.getElementById('imgFecha').focus();
			</script>
		<?php
			break;
		case 1:
		?>
			<script language="javascript">
			//alert("El cargo que ha ingresado no existe.");
			parent.document.getElementById('hdnCargo').value = '';
			parent.document.getElementById('txtCargo').value = '';
			</script>
		<?php
			break;
		case 2:
		?>
			<script language="javascript">
			//alert("El material que ha ingresado no existe.");
			parent.document.getElementById('txtDescripcion').value = '';
			parent.document.getElementById('txtUnidad').value = '';
			parent.document.getElementById('hdnStock').value = 0;
			</script>
		<?php
			break;
	}
}
mssql_free_result($stmt);
mssql_close($cnx);
?>