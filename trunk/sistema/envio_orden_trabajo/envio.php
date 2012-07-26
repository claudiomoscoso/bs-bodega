<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$contrato = $_GET["contrato"];
if($modulo == 1){
	$stmt = mssql_query("SELECT TOP 1 dblEP FROM Orden..RegistroEnvio WHERE strTipo = 0 ORDER BY dtmEnvio DESC", $cnx);
	if($rst = mssql_fetch_array($stmt)) $epago = $rst["dblEP"];
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Envio Orden de Trabajo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Change(ctrl){
	if(parseInt('<?php echo $modulo;?>') == 0)
		document.getElementById('resultado').src = 'resultado.php?modulo=1&contrato=<?php echo $contrato;?>&inspector=' + ctrl.value + '&usuario=<?php echo $usuario;?>' + '&zona=' + document.getElementById('cmbZona').value;
}
function CambioZona(ctrl){
	if(parseInt('<?php echo $modulo;?>') == 0)
		document.getElementById('resultado').src = 'resultado.php?modulo=1&contrato=<?php echo $contrato;?>&inspector=' + document.getElementById('cmbInspector').value + '&usuario=<?php echo $usuario;?>' + '&zona=' + ctrl.value;
}


function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 90);
	if(parseInt('<?php echo $modulo;?>') == 0){
		var inspector = document.getElementById('cmbInspector').value;
		var zona = document.getElementById('cmbZona').value;
		document.getElementById('resultado').src = 'resultado.php?modulo=1&contrato=<?php echo $contrato;?>&inspector=' + inspector + '&usuario=<?php echo $usuario;?>' + '&zona=' + document.getElementById('cmbZona').value;
	}else if(parseInt('<?php echo $modulo;?>') == 1)
		document.getElementById('resultado').src = 'resultado.php?modulo=2&contrato=<?php echo $contrato;?>&usuario=<?php echo $usuario;?>';
}

function Bloquea(sw){
	document.getElementById('txtEPago').style.readOnly = sw;
	var totfil = resultado.document.getElementById('totfil').value;
	if(resultado.document.getElementById('totfil')){
		var totfil = resultado.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++){ 
			if(resultado.document.getElementById('chkOrden' + i)) resultado.document.getElementById('chkOrden' + i).disabled = sw;
		}
	}
	document.getElementById('btnGuardar').disabled = sw;
}

function Deshabilita(sw){
	document.getElementById('txtEPago').disabled = sw;
	document.getElementById('cmbInspector').disabled = sw;
	var totfil = resultado.document.getElementById('totfil').value;
	if(resultado.document.getElementById('totfil')){
		var totfil = resultado.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++){
			if(resultado.document.getElementById('chkOrden' + i)) resultado.document.getElementById('chkOrden' + i).disabled = sw;
		}
	}
	document.getElementById('btnGuardar').disabled = sw;
}

function EligeTodo(checked){
	var totfil = resultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++) resultado.document.getElementById('chkOrden' + i).checked = checked;
	if(parseInt('<?php echo $modulo;?>') == 0)
		parent.document.getElementById('transaccion').src = 'transaccion.php?modulo=2&usuario=<?php echo $usuario;?>&envia=' + (checked ? 1 : 0);
	else if(parseInt('<?php echo $modulo;?>') == 1)
		parent.document.getElementById('transaccion').src = 'transaccion.php?modulo=5&usuario=<?php echo $usuario;?>&envia=' + (checked ? 1 : 0);
}

function Guardar(){
	var epago = document.getElementById('txtEPago').value;
	var envia = 0;
	if(resultado.document.getElementById('totfil')){
		var totfil = resultado.document.getElementById('totfil').value;
		for(i = 1; i <= totfil; i++){
			if(resultado.document.getElementById('chkOrden' + i).checked){
				envia = 1;
				break
			}
		}
	}
	
	if(epago == '')
		alert('Debe ingresar el número de estado de pago.')
	else if(parseInt(envia) == 0){
		alert('No hay ordenes de trabajo que enviar.')
	}else{
		Bloquea(true);
		document.getElementById('frm').setAttribute('action', "grabar.php?modulo=<?php echo $modulo;?>&contrato=<?php echo $contrato;?>");
		document.getElementById('frm').submit();
	}
}
-->
</script>
<body onload="javascript: Load()">
<form name="frm" id="frm" method="post" target="transaccion" action="grabar.php?modulo=<?php echo $modulo;?>" >
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%" align="right">&nbsp;Zona</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<select name="cmbZona" id="cmbZona" class="sel-plano" style="width:100%" OnChange="javascript: CambioZona(this)";>
						<?php
							if($contrato!='13000')
								echo '<option value="all">Todas</option>';
							$stmt = mssql_query("EXEC Orden..sp_getZonas 0, '$contrato'", $cnx);
							while($rst = mssql_fetch_array($stmt)){
								echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
							}
							mssql_free_result($stmt);
						?>
						</select>
					<td width="7%" align="right">&nbsp;Inspector</td>
					<td width="1%" align="center">:</td>
					<td width="20%">
						<select name="cmbInspector" id="cmbInspector" class="sel-plano" disabled="disabled" style="width:100%"
							onchange="javascript: Change(this);"
						>
						<?php
							echo '<option value="all">Todos</option>';
							$stmt = mssql_query("EXEC Orden..sp_getInspector 1, '$contrato'", $cnx);
							while($rst = mssql_fetch_array($stmt)){
								echo '<option value="'.$rst["strCodigo"].'">'.$rst["strNombre"].'</option>';
							}
							mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%" align="right">&nbsp;E.Pago</td>
					<td width="1%" align="center">:</td>
					<td width="10%">
						<input name="txtEPago" id="txtEPago" class="txt-plano" style="width:99%;" disabled="disabled" value="<?php echo $epago;?>" 
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false)"
						/>
					</td>
					<td width="0%">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0" >
				<tr>
					<th width="1%">
						<input type="checkbox" name="chkAll" id="chkAll" 
							onclick="javascript: EligeTodo(this.checked);"
						/>
					</th>
					<th width="3%">N&deg;</th>
					<th width="9%">Orden</th>
					<th width="15%">Fecha</th>
					<th width="23%" align="left">&nbsp;Direcci&oacute;n</th>
					<th width="23%" align="left">&nbsp;Comuna</th>
					<th width="23%" align="left">&nbsp;Inspector</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="contrato" id="contrato" value="<?php echo $contrato;?>" />
			
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" disabled="disabled" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
