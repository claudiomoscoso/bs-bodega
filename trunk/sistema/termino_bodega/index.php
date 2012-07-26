<?php
include '../autentica.php';
include '../conexion.inc.php';

$accion = $_POST["hdnAccion"];
if($accion == 'G'){
	$borigen = $_POST["cmbBodega"];
	$stmt = mssql_query("EXEC Bodega..sp_setTerminoBodega 0, '$usuario', '$borigen'", $cnx);
	if($rst = mssql_fetch_array($stmt)){
		$error = $rst["dblError"];
		$numero = $rst["dblNumero"];
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>T&eacute;rmino Bodega</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $accion;?>' == 'G'){
		if(parseInt('<?php echo $error;?>') == 0)
			alert('El término de bodega se ha guardado con el número <?php echo $numero;?>.');
		else if(parseInt('<?php echo $error;?>') == 1)
			alert('No ha sido posible obtener el correlativo del término de bodega.');
	}
	document.getElementById('frmResultado').setAttribute('height', window.innerHeight - 105); 
}

function Blur(ctrl){
	CambiaColor(ctrl.id, false);
	if(ctrl.id == 'txtCodigo')
		document.getElementById('transaccion').src = 'transaccion.php?modulo=3&valor='+ctrl.value;
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla == 13){
		switch(ctrl.id){
			case 'txtCodigo':
				Deshabilita(true);
				AbreDialogo('divMateriales', 'frmMateriales', 'buscar_material.php?texto='+ctrl.value);
				break;
			case 'txtDevuelve':
				document.getElementById('txtObservacion').focus();
				document.getElementById('txtObservacion').select();
				break;
			case 'txtObservacion':
				if(document.getElementById('txtCodigo').value == ''){
					alert('Debe ingresar un material.');
				}else if(parseFloat(document.getElementById('txtDevuelve').value) <= 0){
					alert('Debe ingresar cantidades mayores a cero.');
				}else{
					Deshabilita(true);
					document.getElementById('frmResultado').src = 'resultado.php?modulo=1&usuario=<?php echo $usuario;?>&codigo='+document.getElementById('txtCodigo').value+'&valor='+document.getElementById('txtDevuelve').value+'&observacion='+document.getElementById('txtObservacion').value;
					LimpiaTexto();
					document.getElementById('txtCodigo').focus();
				}
				break;
		}
	}else{
		if(ctrl.id == 'txtCodigo') return ValNumeros(eventos, ctrl.id, true);
	}
}

function getBuscar(){
	if(confirm('Si realiza la busqueda y ha agregado materiales al detalle, estos serán eliminado. ¿Está seguro que desea continuar?')){
		Deshabilita(true);
		document.getElementById('frmResultado').src = 'resultado.php?modulo=0&usuario=<?php echo $usuario;?>&bodega='+document.getElementById('cmbBodega').value;
	}
}

function Deshabilita(sw){
	document.getElementById('cmbBodega').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;
	document.getElementById('btnGuardar').disabled = sw;
	document.getElementById('chkAll').disabled = sw;
	
	document.getElementById('txtCodigo').disabled = sw;
	document.getElementById('txtDevuelve').disabled = sw;
	document.getElementById('txtObservacion').disabled = sw;
}

function LimpiaTexto(){
	document.getElementById('txtCodigo').value = '';
	document.getElementById('txtDescripcion').value = '';
	document.getElementById('txtDevuelve').value = '';
	document.getElementById('txtObservacion').value = '';
}

function setSeleccionaTodo(ctrl){
	var totfil = frmResultado.document.getElementById('hdnTotLn').value;
	for(i=1; i<=totfil; i++){
		frmResultado.document.getElementById('chkDevolver' + i).checked = ctrl.checked;
		frmResultado.document.getElementById('txtDevolver' + i).disabled = !ctrl.checked;
		frmResultado.document.getElementById('txtObservacion' + i).disabled = !ctrl.checked;
	}
}
-->
</script>
<body onload="javascript: Load()">
<div id="divMateriales" style="z-index:1; position:absolute; top:5px; left:20%; width:60%; visibility:hidden;">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" class="ventana">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_sup.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" onClick="javascript:
										Deshabilita(false); 
										CierraDialogo('divMateriales', 'frmMateriales');
									"><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Materiales</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmMateriales" id="frmMateriales" frameborder="0" scrolling="no" width="100%" height="145px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%">&nbsp;Bodega</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbBodega" id="cmbBodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst = mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strBodega"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td>
						<input type="button" name="btnBuscar" id="btnBuscar" class="boton" style="width:90px" value="Buscar" 
							onclick="javascript: getBuscar();"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th width="3%">N&deg;</th>
					<th width="10%">C&oacute;digo</th>
					<th width="31%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%" align="right">Stock&nbsp;</th>
					<th width="2%">
						<input type="checkbox" name="chkAll" id="chkAll" 
							onclick="javascript: setSeleccionaTodo(this);"
						/>
					</th>
					<th width="10%" align="right">Devuelve&nbsp;</th>
					<th width="32%" align="left">&nbsp;Observaci&oacute;n</th>
					<th width="2%">&nbsp;</th>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input name="txtCodigo" id="txtCodigo" class="txt-plano" style="width:99%; text-align:center" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td><input name="txtDescripcion" id="txtDescripcion" class="txt-plano" style="width:99%; "  /></td>
					<td align="right">0,00&nbsp;</td>
					<td>&nbsp;</td>
					<td>
						<input name="txtDevuelve" id="txtDevuelve" class="txt-plano" style="width:99%; text-align:right" value="0" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td>
						<input name="txtObservacion" id="txtObservacion" class="txt-plano" style="width:99%;" maxlength="1000" 
							onblur="javascript: Blur(this);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmResultado" id="frmResultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden" name="perfil" id="perfil" value="<?php echo $perfil;?>" />
			<input type="hidden" name="bodega" id="bodega" value="<?php echo $bodega;?>" />
			<input type="hidden" name="login" id="login" value="<?php echo $login;?>" />
			<input type="hidden" name="nivel" id="nivel" value="<?php echo $nivel;?>" />
			<input type="hidden" name="hdnAccion" id="hdnAccion" value="G"/>
			
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar"
				onclick="javascript: 
					if(frmResultado.document.getElementById('hdnTotLn'))
						document.getElementById('frm').submit();
					else
						alert('No hay materiales que devolver.');
				"
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
