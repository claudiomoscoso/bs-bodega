<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
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
	<tr><th align="right"><a href="../proveedor/index.php?modulo=genera_orden_compra&consulta=<?php echo $texto;?>">Agregar Proveedor</a>&nbsp;</th></tr>
	<tr>
		<td>
			<select name="lista" id="lista" class="sel-plano" style="width:100%;" size="15" 
				onkeypress="javascript: return Saltar(event, this.selectedIndex);"
			>
			<?php
			$stmt = mssql_query("EXEC sp_getProveedor 4, 'C', NULL, '$texto'",$cnx);
			if($rst = mssql_fetch_array($stmt)){
				do{
					$arrDatos .= "'".trim($rst["strCodigo"]).'&&&'.trim($rst["strNombre"]).'&&&'.trim($rst["strDireccion"]).'&&&'.trim($rst["strDetalle"]).'&&&'.trim($rst["strTelefono"]).'&&&'.trim($rst["strFax"]).'&&&'.trim($rst["strContacto"]).'&&&'.trim($rst["intFormaPago"]).'&&&'.trim($rst["strCorreo"])."',";
					echo '<option value="'.htmlentities($rst["strCodigo"]).'">['.trim($rst["strRut"]).'] '.htmlentities(trim($rst["strNombre"])).'</option>';
				}while($rst = mssql_fetch_array($stmt));
				$arrDatos = substr($arrDatos, 0, -1);
			}else{
				echo '<option value="none">C&oacute;digo o Descripci&oacute;n inexistente</option>';
			}
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

function Saltar(evento, ind){
	var tecla = getCodigoTecla(evento);
	if(tecla == 27){
		parent.Deshabilita(false)
		parent.CierraDialogo('BProveedor','ifrmP');
		parent.document.getElementById('proveedor').focus();
	}else if(tecla == 13){
		parent.Deshabilita(false)
		parent.CierraDialogo('BProveedor','ifrmP');
		if(document.getElementById('lista').value != 'none' && document.getElementById('lista').value != ''){
			var arrPaso = arrDatos[ind].split('&&&');
			parent.document.getElementById('codigo_proveedor').value = arrPaso[0];
			parent.document.getElementById('proveedor').value = ' ' + Reemplazar(arrPaso[1]);
			parent.document.getElementById('direccion').value = ' ' + Reemplazar(arrPaso[2]);
			parent.document.getElementById('comuna').value = ' ' + arrPaso[3];
			parent.document.getElementById('telefono').value = ' ' + arrPaso[4];
			parent.document.getElementById('fax').value = ' ' + arrPaso[5];
			parent.document.getElementById('atencion').value = ' ' + arrPaso[6];
			for(i = 0; i < parent.document.getElementById('forma_pago').options.length; i++){
				if(parseInt(parent.document.getElementById('forma_pago').options[i].value) == parseInt(arrPaso[7])){
					parent.document.getElementById('forma_pago').options[i].selected = true;
					break;
				}
			}
			parent.document.getElementById('email').value = arrPaso[8];
			if('<?php echo $modulo;?>' == 'OCA')
				parent.document.getElementById('ccosto').focus();
			else
				parent.document.getElementById('cargo').focus();
		}else{
			parent.LimpiaDatosProveedor();
			parent.document.getElementById('proveedor').focus();
			parent.document.getElementById('proveedor').select();
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