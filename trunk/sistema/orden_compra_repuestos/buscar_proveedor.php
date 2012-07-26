<?php
include '../conexion.inc.php';

$texto = trim($_GET["texto"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Proveedores</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<select name="lista" id="lista" class="sel-plano" style="width:100%;" size="15" 
				onkeypress="javascript: return KeyPress(event, this.selectedIndex);"
			>
			<?php
			$stmt = mssql_query("EXEC Bodega..sp_getProveedor '$texto', 'C'",$cnx);
			if($rst = mssql_fetch_array($stmt)){
				do{
					$arrDatos .= "'".trim($rst["strCodigo"]).'&&&'.trim($rst["strNombre"]).'&&&'.trim($rst["strRut"]).'&&&'.trim($rst["strDireccion"]).'&&&'.trim($rst["strComuna"]).'&&&'.trim($rst["strDetalle"]).'&&&'.trim($rst["strTelefono"]).'&&&'.trim($rst["strFax"]).'&&&'.trim($rst["strContacto"]).'&&&'.trim($rst["intFormaPago"])."',";
					echo '<option value="'.$rst["strCodigo"].'">['.trim($rst["strRut"]).'] '.trim(substr($rst["strNombre"], 0, 55)).'</option>';
				}while($rst = mssql_fetch_array($stmt));
				$arrDatos = substr($arrDatos, 0, -1);
			}else
				echo '<option value="none">C&oacute;digo o Descripci&oacute;n inexistente</option>';
			mssql_free_result($stmt);
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
		parent.CierraDialogo('divBuscador', 'frmBuscador');
		parent.document.getElementById('txtProveedor').focus();
	}else if(tecla == 13){
		parent.Deshabilita(false);
		parent.CierraDialogo('divBuscador', 'frmBuscador');
		if(document.getElementById('lista').value != 'none' && document.getElementById('lista').value != ''){
			var arrPaso = arrDatos[ind].split('&&&');
			parent.document.getElementById('hdnProveedor').value = arrPaso[0];
			parent.document.getElementById('txtProveedor').value = ' ' + Reemplazar(arrPaso[1]);
			parent.document.getElementById('txtDireccion').value = ' ' + Reemplazar(arrPaso[3]);
			parent.document.getElementById('txtComuna').value = ' ' + arrPaso[5];
			parent.document.getElementById('txtTelefono').value = ' ' + arrPaso[6];
			parent.document.getElementById('txtFax').value = ' ' + arrPaso[7];
			parent.document.getElementById('txtAtencion').value = ' ' + arrPaso[8];
			for(i = 0; i < parent.document.getElementById('cmbFPago').options.length; i++){
				if(parseInt(parent.document.getElementById('cmbFPago').options[i].value) == parseInt(arrPaso[9])){
					parent.document.getElementById('cmbFPago').selectedIndex = i;
					break;
				}
			}
			parent.document.getElementById('txtCCosto').focus();
		}else{
			parent.LimpiaDatosProveedor();
			parent.document.getElementById('txtProveedor').focus();
			parent.document.getElementById('txtProveedor').select();
		}
	}
}
document.getElementById('lista').focus();
document.getElementById('lista').selectedIndex = 0;
-->
</script>
<?php
mssql_close($cnx);
?>