<?php
include '../conexion.inc.php';
$usuario = $_GET["usuario"];
$perfil = $_GET["perfil"];
$bodega = $_GET["bodega"];
$texto = $_GET["texto"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Moviles</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<select name="lista" id="lista" class="sel-plano" style="width:100%;" size="17" 
				onkeypress="javascript: return KeyPress(event, this.selectedIndex);"
			>
			<?php
			$stmt = mssql_query("EXEC General..sp_getMoviles 9, '$bodega', '$texto', 'C', '$perfil'", $cnx);
			if($rst = mssql_fetch_array($stmt)){
				do{
					$arrDatos .= "'".trim($rst["strMovil"]).'&&&'.trim($rst["strNombre"]).'&&&'.trim($rst["strTipo"])."',";
					echo '<option value="'.trim($rst["strMovil"]).'">['.trim($rst["strMovil"]).'] '.trim($rst["strNombre"]).'</option>';
				}while($rst = mssql_fetch_array($stmt));
				$arrDatos = substr($arrDatos, 0, -1);
			}else{
				echo '<option value="none">C&oacute;digo o Descripci&oacute;n inexistente</option>';
			}	
			mssql_free_result($stmt);
			mssql_close($cnx);
			?>
			</select>
		</td>
	</tr>
</table>
</body>
</html>
<script language="javascript">
<!--
var arrDatos = new Array(<?php echo $arrDatos;?>);

function KeyPress(evento, ind){
	var tecla = getCodigoTecla(evento);
	if(tecla == 27){
		parent.Deshabilita(false);
		parent.CierraDialogo('divMoviles', 'frmMoviles');
		parent.document.getElementById('txtCargo').focus();
	}else if(tecla == 13){
		parent.Deshabilita(false);
		parent.CierraDialogo('divMoviles', 'frmMoviles');
		if(document.getElementById('lista').value != 'none'){
			var arrPaso = arrDatos[ind].split('&&&');
			parent.document.getElementById('hdnCargo').value = arrPaso[0];
			parent.document.getElementById('txtCargo').value = arrPaso[1];
			parent.document.getElementById('hdnTipo').value = arrPaso[2];
			parent.document.getElementById('txtObservacion').focus();
		}else{
			parent.document.getElementById('txtCargo').focus();
			parent.document.getElementById('txtCargo').select();
		}
	}
}

document.getElementById('lista').focus();
document.getElementById('lista').selectedIndex = 0;
-->
</script>