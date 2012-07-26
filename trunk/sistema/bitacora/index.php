<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bitacora</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('resultado').setAttribute('height', window.innerHeight - 88);
}

function getBuscar(){
	Deshabilita(true);
	var contrato = document.getElementById('cmbContrato').value;
	src='resultado.php?contrato=' + contrato + '&usuario=<?php echo $usuario;?>';
//	alert(src);
	document.getElementById('resultado').src = src;
}

function Deshabilita(sw){
	document.getElementById('cmbContrato').disabled = sw;
	document.getElementById('btnBuscar').disabled = sw;	
	document.getElementById('btnVPrevia').disabled = sw;
	document.getElementById('btnExportar').disabled = sw;
}

function Imprimir(){
	/*Deshabilita(true);
	var contrato = document.getElementById('cmbContrato').value;
	var totfil = resultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('chkElige' + i).checked){
			document.getElementById('transaccion').src = 'bitacora.php?contrato=' + contrato + '&movil=' + resultado.document.getElementById('txtMovil' + i).value;
			break;
		}
	}*/
}

function VistaPrevia(){
	Deshabilita(true);
	var url = '';
	var contrato = document.getElementById('cmbContrato').value;
	var totfil = resultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('chkElige' + i).checked){
			url = 'bitacora.php?contrato=' + contrato + '&movil=' + resultado.document.getElementById('txtMovil' + i).value + '&usuario=<?php echo $usuario;?>';
			break;
		}
	}
	if(url != '') 
		AbreDialogo('divVPrevia', 'frmVPrevia', url); 
	else{ 
		alert('Debe seleccionar un movil para imprimir.')
		Deshabilita(false);
	}
}

function Exportar(){
	//Deshabilita(true);
	var url = '';
	var contrato = document.getElementById('cmbContrato').value;
	var totfil = resultado.document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(resultado.document.getElementById('chkElige' + i).checked){
			url = 'exportar.php?contrato=' + contrato + '&movil=' + resultado.document.getElementById('txtMovil' + i).value + '&usuario=<?php echo $usuario;?>';
			break;
		}
	}
	if(url != '') 
		//AbreDialogo('divExportar', 'frmExportar', url);
		document.getElementById('transaccion').src = url;  
	else{ 
		alert('Debe seleccionar un movil para exportar datos.')
		Deshabilita(false);
	}
}

-->
</script>
<body onload="javascript: Load()">
<div id="divVPrevia" style="position:absolute; top:5px; left:1%; width:98%; visibility:hidden">
<table border="1" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											Deshabilita(false);
											CierraDialogo('divVPrevia','frmVPrevia');
										"
										onmouseover="javascript: window.status='Cierra el cuadro de vista previa.'; return true"
									title="Cierra el cuadro de vista previa.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Vista Previa</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmVPrevia" id="frmVPrevia" frameborder="0" scrolling="yes" width="100%" height="235px" marginheight="1" marginwidth="1" src="../cargando.php"></iframe></td></tr>
				<tr><td><hr /></td></tr>
				<tr>
					<td align="right">
						<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:90px" value="Imprimir" 
							onclick="javascript: frmVPrevia.Imprime();"
						/>
					</td>
				</tr>
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
					<td width="30%">
						<select name="cmbContrato" id="cmbContrato" class="sel-plano" style="width:100%" >
						<?php
						$stmt = mssql_query("EXEC General..sp_getContratos 1, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strContrato"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
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
					<th width="10%" >&nbsp;Movil</th>
					<th width="55%" align="left">&nbsp;Nombre</th>
					<th width="10%" align="center">Trab.Pend.&nbsp;</th>
					<th width="10%">Fch.Pend.</th>
					<th width="10%">Seleccionar</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="resultado" id="resultado" frameborder="0" width="100%" scrolling="yes" src="../blank.html"></iframe></td></tr>
	<tr><td><hr /></td></tr>
	<tr>
		<td align="right">
			<input type="button" name="btnVPrevia" id="btnVPrevia" class="boton" style="width:90px" value="Vista Previa..." 
				onclick="javascript: VistaPrevia();"
			/>
			<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" value="Exportar..." 
				onclick="javascript: Exportar();"
			/>
		</td>
	</tr>
</table>
<iframe id="transaccion" frameborder="0" width="100px" height="50px"></iframe>
<div id="divExportar" style="position:absolute; top:5px; left:0px; width:0px; visibility:hidden">
<iframe name="frmExportar" id="frmExportar" frameborder="0" scrolling="yes" width="0px" height="0px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe>
</div>
</body>
