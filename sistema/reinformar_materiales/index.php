<?php
include '../autentica.php';
include '../conexion.inc.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reinformar Trabajos y Materiales</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
//EVENTOS
function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 88);
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	switch(tecla){
		case 8:
		case 46:
			ctrl.value = '';
		default:
			return false;
	}
}

function Siguiente(){
	if(!resultado.document.getElementById('totfil')) return 0;
	Deshabilita(true);
	var totfil = resultado.document.getElementById('totfil').value;
	var sw = false;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('chk' + i).checked){
			var numero = resultado.document.getElementById('hdnNumOT' + i).value;
			break;
		}
	}
	if(numero) 
		self.location.href='orden_trabajo.php<?php echo $parametros;?>&numero=' + numero;
	else
		alert('Debe seleccionar una orden de trabajo.');
	Deshabilita(false);
}

//FUNCIONES GET
function getBuscar(){
	Deshabilita(true);
	var contrato = document.getElementById('cmbContrato').value;
	var numero = document.getElementById('txtNumOT').value;
	var fecha = document.getElementById('txtFecha').value
	if(numero == '' && fecha == ''){
		alert('Debe ingresar el número de orden o la fecha para buscar.');
		Deshabilita(false);
		return false;
	}
	document.getElementById('resultado').src='resultado.php?contrato=' + contrato + '&numero=' + numero + '&fecha=' + fecha;
}

//FUNCIONES SET
function setCargaMovil(valor){
	if(document.getElementById('cmbMovil')) document.getElementById('transaccion').src='transaccion.php?modulo=0&contrato='+valor;
}

function Deshabilita(sw){
	document.getElementById('cmbContrato').disabled = sw;
	document.getElementById('txtNumOT').disabled = sw;
	document.getElementById('txtFecha').disabled = sw;
	document.getElementById('imgFecha').style.visibility = (sw ? 'hidden' : 'visible');
	document.getElementById('btnBuscar').disabled = sw;	
	document.getElementById('btnSiguiente').disabled = sw;
}
-->
</script>
<body onload="javascript: Load()">
<div id="divCalendario" style="position:absolute; top:20px; left:10%; width:20%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra calendario"
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra calendario.'; return true"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="color:#000000; font-size:12px">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="0" style="border:thin" scrolling="no" width="100%" height="125px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%">&nbsp;Contrato</td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%"
							onchange="javascript: setCargaMovil(this.value);"
						>
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 1, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							if($bodsel == '') $bodsel = $rst["strContrato"];
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>					
					<td width="7%">&nbsp;N&deg; Orden</td>
					<td width="1%">:</td>
					<td width="10%">
						<input id="txtNumOT" class="txt-plano" style="width: 99%; text-align:center"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Fecha</td>
					<td width="1%">:</td>
					<td width="10%">
						<input name="txtFecha" id="txtFecha" class="txt-plano" style="width: 99%; text-align:center"
							onblur="javascript: CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript: return KeyPress(event, this);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre calendario."
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtFecha&fecha='+document.getElementById('txtFecha').value, false, '50%');
							"
							onmouseover="javascript: window.status='Abre calendario.'; return true;"
						><img id="imgFecha" border="0" align="middle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td >
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
					<th width="4%">&nbsp;</th>
					<th width="8%">Fch.Orden</th>
					<th width="10%">N&deg; Orden</th>
					<th width="15%" align="left">&nbsp;Inspector</th>
					<th width="15%" align="left">&nbsp;Movil</th>
					<th width="8%">Fch.Vcto.</th>
					<th width="25%" align="left">&nbsp;Motivo</th>
					<th width="10%">Seleccionar</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="auto" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnSiguiente" id="btnSiguiente" class="boton" style="width:90px" value="Siguiente &gt;&gt;" 
				onclick="javascript: Siguiente();"
			/>
		</td>
	</tr>
</table>
<iframe id="transaccion" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>