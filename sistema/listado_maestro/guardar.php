<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$accion = $_GET["accion"];
if($accion == 'G'){
	$codigo = $_POST["hdnCodigo"] != '' ? $_POST["hdnCodigo"] : 'NULL';
	$modulo = $codigo == 'NULL' ? 0 : 1;
	$nombre = $_POST["txtNombre"];
	$campos = $_POST["hdnCampos"];
	$criterio = $_POST["hdnCriterio"];
	$orden = $_POST["hdnOrden"];
	
	mssql_query("EXEC Orden..sp_setFormatosInforme $modulo, '$usuario', '$nombre', $codigo, '$campos', '$criterio', '$orden'", $cnx);
	
	$stmt = mssql_query("EXEC Orden..sp_getFormatosInforme 0, '$usuario'", $cnx);
	while($rst = mssql_fetch_array($stmt)){
		$arrFormatos .= "'".$rst["dblCodigo"].'&&&'.$rst["strNombre"]."',";
	}
	mssql_free_result($stmt);
	$arrFormatos = substr($arrFormatos, 0, -1);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado Maestro</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Change(ctrl){
	document.getElementById('hdnCodigo').value = ctrl.value;
	document.getElementById('txtNombre').value = ctrl.options[ctrl.selectedIndex].text;
}

function Load(){
	if('<?php echo $accion;?>' == 'G'){		
		var formatos = parent.document.getElementById('cmbFormatos');
		var arrFormatos = new Array(<?php echo $arrFormatos;?>);
		
		for(i = formatos.length; i >= 0; i--) formatos.remove(i);
		formatos.options[formatos.length] = new Option('-- Seleccione --', '0');
		for(i = 0; i < arrFormatos.length; i++){
			var arrPaso = arrFormatos[i].split('&&&');
			formatos.options[formatos.length] = new Option(arrPaso[1], arrPaso[0]);
		}
		
		parent.Deshabilita(false);
		parent.CierraDialogo('divGuardar', 'frmGuardar');
	}
}

function Guardar(){
	var codigo = document.getElementById('hdnCodigo').value;
	if(document.getElementById('txtNombre').value == '')
		alert('Debe ingresar un nombre para el formato.');
	else if(codigo != ''){
		if(!confirm('¿Esta seguro que desea reemplazar este formato?')) return false;
	}
	document.getElementById('btnGuardar').disabled = true;
	document.getElementById('btnCancelar').disabled = true;
	var seleccion = parent.document.getElementById('cmbCampos');
	var orden = parent.document.getElementById('cmbOrdenado');
	var campos = '', ordenado = '', paso = '';
	if(seleccion.length > 0){
		for(i = 0; i < seleccion.length; i++){
			paso = seleccion.options[i].value;
			campos += ',' + paso.substring(1);
		}
		campos = campos.substring(1);
	}
	if(orden.length > 0){
		for(i = 0; i < orden.length; i++){
			paso = orden.options[i].value;
			ordenado += ',' + paso.substring(1);
		}
		ordenado = ordenado.substring(1);
	}
	document.getElementById('hdnCampos').value = campos;
	document.getElementById('hdnCriterio').value = parent.document.getElementById('cmbOCriterios').value;
	document.getElementById('hdnOrden').value = ordenado;
	document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load();">
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?accion=G&usuario='.$usuario;?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td>
			<select name="cmbFormatos" id="cmbFormatos" class="sel-plano" style="width:100%" size="6" 
				onchange="javascript: Change(this);"
			>
			<?php
			$stmt = mssql_query("EXEC Orden..sp_getFormatosInforme 0, '$usuario'", $cnx);
			while($rst = mssql_fetch_array($stmt)){
				echo '<option value="'.$rst["dblCodigo"].'">'.$rst["strNombre"].'</option>';
			}
			mssql_free_result($stmt);
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%">Nombre</td>
					<td width="1%" align="center">:</td>
					<td width="94%">
						<input name="txtNombre" id="txtNombre" class="txt-plano" style="width:99%" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right">
			<input type="hidden" name="hdnCodigo" id="hdnCodigo" />
			<input type="hidden" name="hdnCampos" id="hdnCampos" />
			<input type="hidden" name="hdnCriterio" id="hdnCriterio" />
			<input type="hidden" name="hdnOrden" id="hdnOrden" />
			
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
			<input type="button" name="btnCancelar" id="btnCancelar" class="boton" style="width:90px" value="Cancelar" 
				onclick="javascript:
					parent.Deshabilita(false);
					parent.CierraDialogo('divGuardar', 'frmGuardar');		
				"
			/>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?php
mssql_close($cnx);
?>