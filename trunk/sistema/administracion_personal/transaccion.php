<?php
include '../conexion.inc.php';
$accion=$_POST["hdnAccion"]!='' ? $_POST["hdnAccion"] : $_GET["accion"];

$error = 0;
if($accion == 'G'){
	$bodega = $_POST["hdnBodega"];
	$contrato = $_POST["cmbContrato"];
	$rut = $_POST["txtCI"]."-".$_POST["txtDig"];
	$nombre = strtoupper($_POST["txtNombre"]);

	$stmt = mssql_query("EXEC General..sp_setPersonalObras 0, '$contrato', '$bodega', '$rut', '$nombre', 1", $cnx);
	if($rst = mssql_fetch_array($stmt)){ 
		$error = $rst["dblError"];
		$rut = $rst["strRut"];
	}
	mssql_free_result($stmt);
}elseif($accion == 'V'){
	$contrato = $_GET["contrato"];
	$rut = $_GET["rut"];
	$vigente = $_GET["vigente"];
	$str = "EXEC General..sp_setPersonalObras 1, '$contrato', NULL, '$rut', NULL, $vigente";
	mssql_query("EXEC General..sp_setPersonalObras 1, '$contrato', NULL, '$rut', NULL, $vigente", $cnx);
}
mssql_close($cnx);
?>
<script language="javascript">
<!--

if('<?php echo $accion;?>' == 'G'){
	if(parseInt('<?php echo $error;?>') == 0){
		alert('La Persona ha sido guardada con el rut <?php echo $rut;?>.');
		parent.self.location.href = parent.self.location.href;
	}else if(parseInt('<?php echo $error;?>') == 2)
		alert('El R.U.T. ingresado ya existe.');
	else if(parseInt('<?php echo $error;?>') == 3)
		alert('No es posible obtener la bodega del contrato.');
}

-->
</script>
<body>
Grabando:<?php echo $accion;?><br>
Datos:<?php echo $contrato;?><br>
Rut:<?php echo $rut;?><br>
</body>
