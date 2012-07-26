<?php
include '../conexion.inc.php';
$userlog = $_GET["userlog"];
$tipo = $_GET["tipo"];
$usuario = $_GET["usuario"];
$estado = $_GET["estado"];

switch($tipo){
	case 'VGT':
		$sql = "EXEC General..sp_setEditaUsuario 1, '$usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $estado";
		mssql_query($sql, $cnx);
	case 'CNX':
		$sql = "EXEC General..sp_setEditaUsuario 2, '$usuario'";
		mssql_query($sql, $cnx);?>
		<script language="javascript">
		<!--
		parent.document.getElementById('frmUsuarios').src=parent.document.getElementById('frmUsuarios').src;
		-->
		</script>
		<?php
		break;
	case 'USR':
		$stmt = mssql_query("SELECT id FROM General..Usuarios WHERE usuario='$usuario'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			if(!confirm('El usuario ingresado ya existe. Si continua los datos de este serán reemplazados. ¿Está seguro que desea continuar?'))
				parent.document.getElementById('txtUsuario').value = '';
		-->
		</script>
		<?php
		}
		break;
}
mssql_close($cnx);
?>
