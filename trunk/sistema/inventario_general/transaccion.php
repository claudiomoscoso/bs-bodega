<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
switch($modulo){
	case 0:?>
		<script language="javascript">
		<!--
		var equipos = parent.document.getElementById('cmbEquipos');
		for(i = equipos.length; i > 0; i--) equipos.remove(i);
		-->
		</script>
		<?php
		$cargo = $_GET["cargo"];
		$stmt = mssql_query("EXEC Operaciones..sp_getEquipos 2, '$cargo'", $cnx);
		while($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
			equipos.options[equipos.length] = new Option('<?php echo $rst["strDescripcion"];?>', '<?php echo $rst["dblEquipo"];?>');
		</script>
		<?php
		}
		mssql_free_result($stmt);?>
		<script language="javascript">
		equipos.disabled = false;
		</script>
		<?php
		break;
	case 1:
		$texto = $_GET["texto"];
		$stmt = mssql_query("EXEC General..sp_getPersonalObra 4, NULL, '$texto'",$cnx);
		if($rst=mssql_fetch_array($stmt)){?>
		<script language="javascript">
			parent.document.getElementById('hdnChofer').value = '<?php echo $rst["strRut"];?>';
			parent.document.getElementById('txtChofer').value = '<?php echo $rst["strNombre"];?>';
		</script>
		<?php
		}else{?>
		<script language="javascript">
			parent.document.getElementById('hdnChofer').value = '';
			parent.document.getElementById('txtChofer').value = '';
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>
