<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Seguimiento de Solicitud</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	document.getElementById('detalle').setAttribute('height', window.innerHeight - 52);
}

function Consulta(bodega, estado){
	Deshabilita(true);
	document.getElementById('detalle').src='detalle.php?usuario=<?php echo $usuario;?>&bodega='+bodega+'&estado='+estado;
}

function Deshabilita(sw){
	document.getElementById('bodega').disabled=sw;
	document.getElementById('estado').disabled=sw;
	document.getElementById('btnOk').disabled=sw;
}

function Finalizar(){
	if(confirm('¿Está seguro que desea dar por completada esta solicitud de material?')){
		var estado=document.getElementById('estado').value;
		var numero=detalle.document.getElementById('numero').value;
		CierraDialogo('DocVinc', 'ifrmD');
		document.getElementById('detalle').src='detalle.php?accion=F&usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&estado='+estado+'&numero='+numero;
	}
}
-->
</script>
<body onload="javascript: Load();">
<div id="DocVinc" style="z-index:100; position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td colspan="2">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr style="background-image:url(../images/borde_med.png)">
								<td align="right" valign="middle" width="15px">
									<a href="#" onClick="javascript: 
										CierraDialogo('DocVinc', 'ifrmD');
										CierraDialogo('Ordenes', 'ifrmOrd');
										Deshabilita(false);
									"><img border="0" src="../images/close.png"></a>
								</td>								
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Detalle</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<table border="0" width="100%" cellspacing="0" cellpadding="0">
										<tr>
											<td width="14%" nowrap="nowrap"><b>&nbsp;N&deg; Solicitud</b></td>
											<td width="1%"><b>:</b></td>
											<td width="55%" >
												<input type="hidden" name="txtSolicitud" id="txtSolicitud" />
												<input name="txtNumSM" id="txtNumSM" class="txt-sborde" style="width:98%" readonly="true" />
											</td>
											<td width="30%" align="right">
												<a href="#" title="Ver ordenes de compra..." 
													onclick="javascript:
														AbreDialogo('Ordenes', 'ifrmOrd', 'ordenes_compra.php?solicitud='+document.getElementById('txtSolicitud').value);
													"
												>
												<b>Ordenes de Compras</b>
												</a>
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<th width="3%">N&deg;</th>
											<th width="35%" align="left">&nbsp;Material</th>
											<th width="20%">Cant.Solicitada</th>
											<th width="20%">Cant.Autorizada</th>
											<th width="20%">Cant.Comprada</th>
											<th width="2%">&nbsp;</th>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top" colspan="2"><iframe name="ifrmD" id="ifrmD" frameborder="0" scrolling="yes" width="100%" height="200px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td align="left" style="color:#FF0000" width="50%">
						<b>(*) Anula materiales pendientes para cerrar la solicitud.</b>
					</td>
					<td align="right">
						<input type="button" name="btnFinalizar" id="btnFinalizar" class="boton" style="width:90px" value="Anular (*)"
							onclick="javascript: 
								Deshabilita(false);
								Finalizar();
							"
						 />
						 <input type="button" name="btnCerrar" id="btnCerrar" class="boton" style="width:90px" value="Cerrar" 
						 	onclick="javascript:
								CierraDialogo('DocVinc', 'ifrmD');
								CierraDialogo('Ordenes', 'ifrmOrd');
								Deshabilita(false);
							" 
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="Ordenes" style="z-index:100; position:absolute; top:5px; left:15%; width:75%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr style="background-image:url(../images/borde_med.png)">
								<td align="right" valign="middle" width="15px">
									<a href="#" onClick="javascript: 
										CierraDialogo('Ordenes', 'ifrmOrd');
									"><img border="0" src="../images/close.png"></a>
								</td>								
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Ordenes de Compra</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<th width="3%">&nbsp;</th>
								<th width="10%">Fecha</th>
								<th width="10%" >N&deg;OC</th>
								<th width="20%" align="left">&nbsp;Proveedor</th>
								<th width="20%" align="left">&nbsp;Material</th>
								<th width="10%">Cantidad</th>
								<th width="10%">Precio</th>
								<th width="15%">Estado</th>
								<th width="2%">&nbsp;</th>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="ifrmOrd" id="ifrmOrd" frameborder="0" scrolling="yes" width="100%" height="245px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="1%">&nbsp;</td>
					<td width="4%"><b>Bodega</b></td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="bodega" id="bodega" class="sel-plano" style="width:100%">
						<?php
						$stmt = mssql_query("EXEC General..sp_getBodega 3, '$usuario'", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strBodega"].'" '.($bodega == $rst["strBodega"] ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);
						?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="4%"><b>Estado</b></td>
					<td width="1%">:</td>
					<td width="25%">
						<select name="estado" id="estado" class="sel-plano" style="width:100%">
							<option value="T">Todos</option>
						<?php
						$stmt = mssql_query("SELECT strCodigo, strDetalle FROM General..Tablon WHERE strTabla='estoc' AND strVigente='1' AND strCodigo<10 ORDER BY strCodigo", $cnx);
						while($rst=mssql_fetch_array($stmt)){
							echo '<option value="'.$rst["strCodigo"].'">'.$rst["strDetalle"].'</option>';
						}
						mssql_free_result($stmt);?>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td>
						<input type="button" name="btnOk" id="btnOk" class="boton" width="width: 95px" value="Listar" 
							onclick="javascript: Consulta(document.getElementById('bodega').value, document.getElementById('estado').value);"
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
					<th width="10%">Fecha</th>
					<th width="10%">N&uacute;mero</th>
					<th width="10%" align="left">&nbsp;Solicitante</th>
					<th width="30%" align="left">&nbsp;Observaci&oacute;n</th>
					<th width="10%">&nbsp;Ult.Evento</th>
					<th width="25%" align="left">&nbsp;Estado</th>
					<th width="2%">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td valign="top"><iframe name="detalle" id="detalle" frameborder="0" marginheight="0" marginwidth="0" scrolling="yes" src="../blank.html" width="100%"></iframe></td></tr>
</table>
</body>
</html>
<?php
mssql_close($cnx);
?>