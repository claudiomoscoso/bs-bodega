<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administraci&oacute;n de Moviles</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $movil;?>'!='') alert('El movil se ha guardado con el código: <?php echo $movil;?>.');
	Bloquea(false);
	document.getElementById('frmPersonal').setAttribute('height', window.innerHeight - 133);
	document.getElementById('cmbContrato').focus();
}

function Buscar(){
	var contrato = document.getElementById('cmbContrato').value;
	document.getElementById('frmPersonal').src = 'personal.php?contrato=' + contrato;
}

function BusquedaRapida(modulo, valor){
	if(valor!='')
		document.getElementById('transaccion').src='busqueda.php?modulo='+modulo+'&valor='+valor+'&contrato='+document.getElementById('cmbContrato').value;
}

function Bloquea(sw){
	if(sw){ 
		document.getElementById('hdnTMov').value='O'; 
		document.getElementById('txtCodigo').disabled=true;
		document.getElementById('txtCI').disabled=true;
		document.getElementById('txtNombre').readOnly=false;
	}else{ 
		document.getElementById('hdnTMov').value='P';
		document.getElementById('txtCodigo').disabled=false;
		document.getElementById('txtCI').disabled=false;
		document.getElementById('txtNombre').readOnly=true;
	}
	document.getElementById('txtCodigo').value='';
	document.getElementById('txtCI').value='';
	document.getElementById('txtDig').value='';
	document.getElementById('txtNombre').value='';
}

function Guarda(){
	if(document.getElementById('txtNombre').value==''){
		alert('Debe ingresar el nombre de la Persona.');
		document.getElementById('txtNombre').focus();
		document.getElementById('txtNombre').select();
	}else if(document.getElementById('txtCI').value==''){
		alert('Debe ingresar el Rut de la Persona.');
		document.getElementById('txtRut').focus();
		document.getElementById('txtRut').select();
	}else{
		document.getElementById('frm').target='transaccion';
		document.getElementById('frm').action="transaccion.php";
		document.getElementById('frm').submit();
	}
}
-->
</script>
<body onload="javascript: Load()">
<form name="frm" id="frm" method="post" >
<table border="0" width="100%" cellpadding="0" cellspacing="0" height="90%">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td width="5%">&nbsp;Contrato</td>
					<td width="1%" align="center">:</td>
					<td width="25%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 0, '$bodega'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>					
					</td>
					<td width="0%"><input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:80px" value="Buscar" onclick="javascript: Buscar();" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">Rut</th>
					<th width="40%" align="left">&nbsp;Nombre</th>
					<th width="25%" align="left">&nbsp;Contrato</th>
					<th width="6%">Vigente</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmPersonal" id="frmPersonal" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="5%" align=right>&nbsp;Contrato</td>
								<td width="1%">:</td>
								<td width="14%">
									<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%">
									<?php
									$stmt = mssql_query("EXEC General..sp_getContratos 0, '$bodega'", $cnx);
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strContrato"].'" '.($rst["dblMatriz"] == 1 ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="5%" align=right>&nbsp;R.U.T.</td>
								<td width="1%">:</td>
								<td width="9%">
									<input name="txtCI" id="txtCI" class="txt-plano" style="width:100%; text-align:right" maxlength="8" 
										onblur="javascript: 
											CambiaColor(this.id, false);
											if(this.value!='')
												BusquedaRapida(1, this.value+'-'+document.getElementById('txtDig').value);
											document.getElementById('txtNombre').focus();
										"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return ValNumeros(event, this.id, false);"
									 	onkeyup="javascript: ValRut(this.value, 'txtDig');"
									/>
								</td>
								<td width="1%" align="center">&ndash;</td>
								<td width="3%"><input name="txtDig" id="txtDig" class="txt-plano" style="width:100%; text-align:center" readonly="true" /></td>
								<td width="1%">&nbsp;</td>
								<td width="8%" align=right>&nbsp;Nombre</td>
								<td width="1%">:</td>
								<td width="40%">
									<input name="txtNombre" id="txtNombre" class="txt-plano" style="width:100%" maxlength="100" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="hdnTMov" id="hdnTMov" />
			<input type="hidden" name="hdnAccion" id="hdnAccion" value="G" />
			<input type="hidden" name="hdnBodega" id="hdnBodega" value="<?php echo $bodega;?>" />
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: Guarda()"
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
