<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ingreso Datos Lotes</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Change(inmobil){
	document.getElementById('transaccion').src = 'transaccion.php?modulo=0&inmobil=' + inmobil;
}
function BuscaLote(proyecto,lote){
	document.getElementById('transaccion').src = 'transaccion.php?modulo=1&proyecto=' + proyecto + '&lote=' + lote;
}
function Blur(ctrl){
	var proyecto = document.getElementById('cmbProyecto').value;
	CambiaColor(ctrl.id, false);
	switch(ctrl.id){
		case 'txtLote':
			document.getElementById('transaccion').src = 'transaccion.php?modulo=2&proyecto=' + proyecto + '&lote=' + ctrl.value;
			break;
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		var inmobil = document.getElementById('cmbInmobil').value;
		switch(ctrl.id){
			case 'cmbInmobil':
				document.getElementById('cmbProyecto').focus();
				break;
			case 'cmbProyecto':
				document.getElementById('txtLote').focus();
				break;
			case 'txtLote':
				document.getElementById('txtPiso').focus();
				break;
			case 'txtPiso':
				document.getElementById('txtCoordenadas').focus();
				break;
			case 'txtCoordenadas':
				document.getElementById('txtDireccion').focus();
				break;
			case 'txtDireccion':
				document.getElementById('txtComuna').focus();
				break;
			case 'txtComuna':
				document.getElementById('txtNombre').focus();
				break;
			case 'txtNombre':
				document.getElementById('txtApellido').focus();
				break;
			case 'txtApellido':
				document.getElementById('txtArrendatario').focus();
				break;
			case 'txtArrendatario':
				document.getElementById('txtFono').focus();
				break;
			case 'txtFono':
				document.getElementById('txtCelular').focus();
				break;
			case 'txtCelular':
				document.getElementById('txtEmail').focus();
				break;
			case 'txtEmail':
				document.getElementById('txtFonoArrendatario').focus();
				break;
			case 'txtFonoArrendatario':
				document.getElementById('txtObservacion').focus();
				break;
			case 'txtObservacion':
				document.getElementById('btnGuardar').focus();
				break;
		}
	}
}

function Load(){

}

function Deshabilita(sw){
	document.getElementById('cmbInmobil').disabled = sw;
	document.getElementById('cmbProyecto').disabled = sw;
	document.getElementById('txtLote').disabled = sw;
	document.getElementById('txtPiso').disabled = sw;
	document.getElementById('txtCoordenadas').disabled = sw;
	document.getElementById('txtDireccion').disabled = sw;
	document.getElementById('txtComuna').disabled = sw;
	document.getElementById('txtNombre').disabled = sw;
	document.getElementById('txtApellido').disabled = sw;
	document.getElementById('txtArrendatario').disabled = sw;
	document.getElementById('txtFono').disabled = sw;
	document.getElementById('txtCelular').disabled = sw;
	document.getElementById('txtEmail').disabled = sw;
	document.getElementById('txtFonoArrendatario').disabled = sw;
	document.getElementById('txtObservacion').disabled = sw;
	
	document.getElementById('btnGuardar').disabled = sw;
}

function setGuardar(){
	if(document.getElementById('txtLote').value == ''){
		alert('Debe ingresar el Nº de Lote.');
	}else if(document.getElementById('txtNombre').value == ''){
		alert('Debe ingresar el Nombre del Propietario.');
	}else if(document.getElementById('txtApellido').value == '  :  '){
		alert('Debe ingresar el Apellido del Propietario.');
	}else if(document.getElementById('txtCoordenadas').value == 0){
		alert('Debe ingresar las Coordenadas.');
	}else if(document.getElementById('txtDireccion').value == 0){
		alert('Debe ingresar la Dirección.');
	}else if(document.getElementById('txtComuna').value == 0){
		alert('Debe ingresar la Comuna.');
	}else{
		document.getElementById('frm').submit();
	}
}
-->
</script>
<body onload="javascript: Load();">

<form name="frm" id="frm" method="post" action="grabar.php<?php echo $parametros;?>" target="transaccion">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td width="8%">&nbsp;Inmobiliaria</td>
		<td width="1%">:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="46%">
						<select name="cmbInmobil" id="cmbInmobil" class="sel-plano" style="width:100%"
							onchange="javascript: Change(this.value)"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC Inmobiliaria..sp_getInmobiliarias 0, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							if($inmobil == '') $inmobil = $rst["Codigo"];
							echo '<option value="'.$rst["Codigo"].'">'.$rst["Detalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Proyecto</td>
					<td width="1%">:</td>
					<td width="47%">
						<select name="cmbProyecto" id="cmbProyecto" class="sel-plano" style="width:100%"
							onkeypress="javascript: return KeyPress(event, this);"
						>
						<?php
						$stmt = mssql_query("EXEC Inmobiliaria..sp_getProyectos 0, '$inmobil'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["Codigo"].'">'.$rst["Detalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;Lote/Depto.</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="8%">
						<input name="txtLote" id="txtLote" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: BuscaLote(cmbProyecto.value, this.value);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%">&nbsp;Piso</td>
					<td width="1%">:</td>
					<td width="7%">
						<input name="txtPiso" id="txtPiso" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="8%">&nbsp;Coordenadas</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtCoordenadas" id="txtCoordenadas" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Dirección</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtDireccion" id="txtDireccion" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Comuna</td>
					<td width="1%">:</td>
					<td width="15%">
						<input name="txtComuna" id="txtComuna" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;Propietario.</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="25%">
						<input name="txtNombre" id="txtNombre" class="txt-plano" style="width:99%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="15%">&nbsp;Propietario (Apellido)</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtApellido" id="txtApellido" class="txt-plano" style="width:100%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="8%">&nbsp;Arrendatario</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtArrendatario" id="txtArrendatario" class="txt-plano" style="width:100%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;Fono Cliente</td>
		<td>:</td> = NULL
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="15%">
						<input name="txtFono" id="txtFono" class="txt-plano" style="width:100%"
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Celular</td>
					<td width="1%">:</td>
					<td width="15%">
						<input name="txtCelular" id="txtCelular" class="txt-plano" style="width:100%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Email</td>
					<td width="1%">:</td>
					<td width="25%">
						<input name="txtEmail" id="txtEmail" class="txt-plano" style="width:100%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="15%">&nbsp;Fono Arrendatario</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtFonoArrendatario" id="txtFonoArrendatario" class="txt-plano" style="width:100%" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;Observaci&oacute;n</td>
		<td>:</td>
		<td>
			<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:100%" 
				onblur="javascript: Blur(this);"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this);"
			/>
		</td>
	</tr>
	<tr><td colspan="3" valign="bottom" style="height:207px"><hr /></td></tr>
	<tr>
		<td colspan="3" align="right">
			<input type="hidden" name="hdnMovil" id="hdnMovil" />
			<input type="hidden" name="hdnFVence" id="hdnFVence" />
			
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: setGuardar()"
			/>
		</td>
	</tr>
</table>
</form>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>