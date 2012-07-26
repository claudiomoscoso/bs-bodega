<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$texto = $_GET["texto"];
switch($modulo){
	case 0:
		$falla = '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
		$stmt = mssql_query("SELECT strDescripcion, CONVERT(VARCHAR, dtmFecha, 103) AS dtmFch FROM Operaciones..ReporteFalla WHERE strCCosto = '$texto' ORDER BY dtmFecha DESC", $cnx);
		if($rst = mssql_fetch_array($stmt)){
			do{
				$cont++;
				$falla .= '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'">';
				$falla .= '<td width="90%">&nbsp;'.$rst["strDescripcion"].'</td>';
				$falla .= '<td width="10%">'.$rst["dtmFch"].'</td>';
				$falla .= '</tr>';
			}while($rst = mssql_fetch_array($stmt));
		}else
			$falla .= '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
		$falla .= '</table>';
		mssql_free_result($stmt);
		mssql_close($cnx);
		
		$stmt = mssql_query("EXEC Operaciones..sp_getCentroCosto 3, '$texto', '$usuario', 'E'", $cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			var next = 1;
			if('<?php echo trim($rst["strObra"]);?>' == 'S/A'){
				if(!confirm('El centro de costo seleccionado no se encuentra asignado a su obra. ¿Esta seguro que desea continuar?')) next = 0;
			}
			if(next == 1){
				parent.document.getElementById('txtCCosto').value = '<?php echo $rst["strCCosto"];?>';
				parent.document.getElementById('txtEquipo').value = '<?php echo $rst["strDescripcion"];?>';
				parent.document.getElementById('txtUltLectura').value = '<?php echo number_format($rst["dblOdometro"], 2, '.', '');?>';
				parent.document.getElementById('divFallas').innerHTML = '<?php echo $falla;?>';
			}else{
				parent.document.getElementById('txtCCosto').value = '';
				parent.document.getElementById('txtEquipo').value = '';
				parent.document.getElementById('txtUltLectura').value = 0;
				parent.document.getElementById('divFallas').innerHTML = '';
			}
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('txtCCosto').value = '';
			parent.document.getElementById('txtEquipo').value = '';
			parent.document.getElementById('txtUltLectura').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
	case 1:
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
	case 2:
		$stmt = mssql_query("EXEC General..sp_getPersonalObra 4, NULL, '$texto'",$cnx);
		if($rst = mssql_fetch_array($stmt)){?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnChofer').value = '<?php echo $rst["strRut"];?>';
			parent.document.getElementById('txtChofer').value = '<?php echo $rst["strNombre"];?>';
		-->
		</script>
		<?php
		}else{?>
		<script language="javascript">
		<!--
			parent.document.getElementById('hdnChofer').value = '';
			parent.document.getElementById('txtChofer').value = '';
		-->
		</script>
		<?php
		}
		mssql_free_result($stmt);
		break;
}
mssql_close($cnx);
?>