<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$texto = $_GET["texto"];
switch($modulo){
	case 0:
		$usuario = $_GET["usuario"];
		$stmt = mssql_query("EXEC General..sp_getCargos 10, NULL, '$texto', '$usuario', 'E'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnObra').value = '<?php echo $rst["strCodigo"];?>';
			parent.document.getElementById('txtObra').value = '<?php echo $rst["strDetalle"];?>';
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnObra').value = '';
			parent.document.getElementById('txtObra').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 1:
		$stmt = mssql_query("EXEC Operaciones..sp_getEquipos 1, '$texto', 'E'",$cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnEquipo').value = '<?php echo $rst["Id"];?>';
			parent.document.getElementById('txtEquipo').value = '<?php echo $rst["strDescripcion"];?>';
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnOperador').value = '';
			parent.document.getElementById('txtOperador').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 2:
		$stmt = mssql_query("EXEC General..sp_getPersonalObra 4, NULL, '$texto'",$cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnOperador').value = '<?php echo $rst["strRut"];?>';
			parent.document.getElementById('txtOperador').value = '<?php echo $rst["strNombre"];?>';
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnOperador').value = '';
			parent.document.getElementById('txtOperador').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>