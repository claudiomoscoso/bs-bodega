<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$perfil = $_GET["perfil"];
$bodega = $_GET["bodega"];
$numero = $_GET["numero"];

$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'OC', $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$interno = $rst["dblNumero"];
	$fpago = $rst["intFPago"];
	$forma_pago = $rst["Forma_Pago"];
	$idbodegaOC = $rst["strBodega"];
	$bodegaOC = $rst["Bodega"];
	$fecha = $rst["dtmFecha"];
	$proveedor = $rst["strProveedor"];
	$nombre = $rst["strNombre"];
	$direccion = $rst["strDireccion"];
	$strcomuna = $rst["strComuna"];
	$comuna = $rst["Comuna"];
	$telefono = $rst["strTelefono"];
	$fax = $rst["strFax"];
	$strCargo = $rst["strCargo"];
	$cargo = $rst["Cargo"];
	$ccosto = $rst["Centro_Costo"];
	$atencion = $rst["strContacto"];
	$nota = $rst["strObservacion"];
	$factor = $rst["dblIva"];
	$tipodoc = $rst["strTipoDoc"];
	$estado = $rst["strEstado"];
	$numsol = $rst["dblNumSol"];
	$docpago = $rst["dblDocPago"];
}
mssql_free_result($stmt);

if($factor == ''){
	$stmt = mssql_query("SELECT dblFactor FROM Impuesto WHERE id = $docpago", $cnx);
	if($rst = mssql_fetch_array($stmt)) $factor = $rst["dblFactor"];
	mssql_free_result($stmt);
}

mssql_query("EXEC Bodega..sp_setTMPDetalleOrdenCompra 14, '$usuario', NULL, $numero", $cnx);

$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'OCA', $numero", $cnx);
if($rst=mssql_fetch_array($stmt)){ 
	$usu_sol=$rst["usuario"];
	$nombsol=$rst["nombre"]; 
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'GNR', NULL, '$usuario'", $cnx);
if($rst=mssql_fetch_array($stmt)) $nivel=$rst["nivel"];
mssql_free_result($stmt);

$stmt = mssql_query("SELECT strDireccion FROM General..Contrato WHERE strCodigo='$strCargo'", $cnx);
if($rst=mssql_fetch_array($stmt)) $despachar_en=$rst["strDireccion"];
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Maestro de Ordenes de Compra</title>

</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var Intervalo = 0;

function Load(){
	var bloquea = true;
	if(document.getElementById('btnGraba')){
		if('<?php echo $tipodoc;?>' == 'A')
			bloquea = false;
		else{
			if('<?php echo trim($estado);?>' == '4'){
				if('<?php echo $tipodoc;?>' == 'O' && '<?php echo $nivel;?>' == '1')
					bloquea = false;
				else if('<?php echo $tipodoc;?>' == 'A')
					bloquea = false;			
			}
		}
		document.getElementById('btnGraba').disabled = bloquea;
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	if(tecla==13){
		if(ctrl=='proveedor'){
			Deshabilita(true);
			AbreDialogo('divProveedor','frmProveedor','buscar_proveedor.php?texto='+ document.getElementById('proveedor').value);
		}else if(ctrl.substr(0, 3)=='val'){
			if(document.getElementById(ctrl).value==0){
				alert('Debe ingresar el valor.');
				document.getElementById(ctrl).focus();
			}else{
				var fil=ctrl.substr(3);
				if(fil<document.getElementById('totfil').value){
					fil++;
					document.getElementById('val'+fil).focus();
				}
			}
		}
	}else
		if(ctrl.substr(0,3)=='val') return ValNumeros(evento, ctrl, true);
}

function BusqRapida(tipo, bodega, valor){
	document.getElementById('valido').src = 'valida.php?tipo=' + tipo + '&bodega=' + bodega + '&valor=' + valor;	
}

function generarPDF(numero){
	AbreDialogo('divGeneraPDF', 'frmGeneraPDF', 'generapdf.php?usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&numero=' + numero, true);
}

function Graba(){
	if(confirm('¿Está seguro que desea guardar los cambios?')){
		if(document.getElementById('strCodigo').value==''){
			alert('Debe seleccionar el proveedor.');
			document.getElementById('proveedor').focus();
		}else if(document.getElementById('neto').value==0){
			alert('Debe ingresar a lo menos una línea de detalle.');
		}else{
			document.getElementById('frm').setAttribute('target', 'valido');
			document.getElementById('frm').setAttribute('action', 'graba.php');
			document.getElementById('frm').submit();
		}
	}
}

function LimpiaDatosProveedor(){
	document.getElementById('strCodigo').value = '';
	document.getElementById('proveedor').value = '';
	document.getElementById('direccion').value = '';
	document.getElementById('comuna').value = '';
	document.getElementById('telefono').value = '';
	document.getElementById('fax').value = '';
	document.getElementById('atencion').value = '';
	document.getElementById('email').value='';
}

function Calcular(idCant, idVal, idTot){
	var impto = <?php echo $factor;?>, neto=0;
	var totfil = document.getElementById('totfil').value;
	var cantidad = document.getElementById(idCant).value;
	var valor = document.getElementById(idVal).value;
	document.getElementById(idTot).value = cantidad * valor;
	for(i = 1; i <= totfil; i++){
		neto += parseInt(document.getElementById('tot' + i).value);
	}
	document.getElementById('neto').value = neto;
	document.getElementById('impto').value = Math.round(document.getElementById('neto').value * impto);
	document.getElementById('totalOC').value = Math.round(document.getElementById('neto').value * (impto + 1));
}

function Blur(idCod, idVal){
	if(document.getElementById(idVal).value=='') document.getElementById(idVal).value=0;
	if(document.getElementById(idVal).value!=0)
		document.getElementById('valido').src='cambia_valor.php?usuario=<?php echo $usuario;?>&modulo=OC&codigo='+document.getElementById(idCod).value+'&valor='+document.getElementById(idVal).value;
}

function Imprimir(estado){
	var numero=document.getElementById('numero').value;
	var despachar=document.getElementById('despachar').checked;
	despachar=despachar ? 1 : 0;
	document.getElementById('valido').src='imprime.php?usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&bodega=<?php echo $bodega;?>&numero='+numero+'&estado='+estado+'&obs='+document.getElementById('obs').value+'&despachar='+despachar;
}

function CambiarEstado(estado){
	Deshabilita(true);
	var numero=document.getElementById('numero').value;
	var numsol=document.getElementById('numsol').value;
	var obs=document.getElementById('obs').value;
	AbreDialogo('divContrasena', 'frmContrasena', 'contrasena.php?usuario=<?php echo $usuario;?>&bodega=<?php echo $bodega;?>&estado='+estado+'&numero='+numero+'&numsol='+numsol+'&obs='+obs+'&nivel=<?php echo $nivel;?>&tipodoc=<?php echo $tipodoc;?>');
}

function Deshabilita(sw){
	document.getElementById('forma_pago').disabled=sw;
	document.getElementById('proveedor').disabled=sw;
	document.getElementById('atencion').disabled=sw;
	document.getElementById('despachar').disabled=sw;	
	document.getElementById('obs').disabled=sw;
	document.getElementById('email').disabled=sw;
	if(document.getElementById('btnAnula2')) document.getElementById('btnAnula2').disabled=sw;
	if(document.getElementById('btnGenerarPDF')) document.getElementById('btnGenerarPDF').disabled=sw;
	if(document.getElementById('btnImprimir')) document.getElementById('btnImprimir').disabled=sw;
	if(document.getElementById('btnVB')) document.getElementById('btnVB').disabled=sw;
	if(document.getElementById('btnAnula')) document.getElementById('btnAnula').disabled=sw;
	if(document.getElementById('btnGraba')) document.getElementById('btnGraba').disabled=sw;
	document.getElementById('btnCerrar').disabled=sw;
	var totfil=document.getElementById('totfil').value;
	for(i=1; i<=totfil; i++){
		document.getElementById('val'+i).disabled=sw;
	}
}
-->

</script>
<script type="text/javascript" language="Javascript">



<!-- Begin



document.oncontextmenu = function(){return false}



// End -->



</script>
<body topmargin="0" onload="javascript: Load();">
<div id="divProveedor" style="z-index:1000; position:absolute; top:5px; left:20%; width:60%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
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
											CierraDialogo('divProveedor','frmProveedor');
										"
										onmouseover="javascript: window.status='Cierra la lista de proveeedores.'; return true"
									title="Cierra la lista de proveeedores.">
										<img border="0" src="../images/close.png">
									</a>
								</td>
								<td align="center" style="color:#000000; font-size:12px"><b>&nbsp;Lista de Proveedores</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmProveedor" id="frmProveedor" frameborder="0" scrolling="no" width="100%" height="235px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divContrasena" style=" z-index: 1; position:absolute; top:20%; left:35%; width:30%; height:110px; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="right" valign="middle" width="15px">
									<a href="#" 
										onClick="javascript: 
											CierraDialogo('divContrasena', 'frmContrasena');
											Deshabilita(false);
										"
										onMouseOver="javascript: window.status='Cierra la ventana.'; return true;"
									><img border="0" src="../images/close.png"></a>
								</td>
								<td align="center" style="font-size:12px"><b>Contrase&ntilde;a</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td><iframe name="frmContrasena" id="frmContrasena" frameborder="0" scrolling="no" width="100%" height="90px" src="../blank.html"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<form name="frm" id="frm" method="post" target="detalle">
<table border="0" width="100%" cellpadding="0" cellspacing="0" >
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="7%" align="left" nowrap="nowrap">&nbsp;F.Pago</td>
					<td width="1%">:</td>
					<td width="92%" colspan="5">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
							  <td width="27%" >
									<select name="forma_pago" id="forma_pago" class="sel-plano" style="width:100%">
									<?php	
									$stmt = mssql_query("select strCodigo, strDetalle from General..Tablon where strTabla='tipop' and strVigente='1' order by strDetalle");
									while($rst=mssql_fetch_array($stmt)){
										echo '<option value="'.$rst["strCodigo"].'" '.($fpago == trim($rst["strCodigo"]) ? 'selected' : '').'>'.$rst["strDetalle"].'</option>';
									}
									mssql_free_result($stmt);
									?>
									</select>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left">&nbsp;Bodega</td>
								<td width="1%">:</td>
								<td width="29%" align="left"><input class="txt-sborde" style="width:100%;background-color:#ececec" readonly="true" value="<?php echo $bodegaOC;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left">&nbsp;Fecha</td>
								<td width="1%">:</td>
								<td width="10%" align="left">&nbsp;<?php echo $fecha;?></td>
								<td width="1%">&nbsp;</td>
								<td width="7%" align="left" nowrap="nowrap">&nbsp;N&deg; Interno</td>
								<td width="1%">:</td>
								<td width="10%" align="left">&nbsp;<?php echo $interno;?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left">&nbsp;Proveedor</td>
					<td>:</td>
					<td colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="34%" align="left">
									<input name="proveedor" id="proveedor" class="txt-plano" style="width:99%" value="<?php echo $nombre;?>" <?php echo $tipodoc=='I' ? 'readonly="true"' : '';?> 
										onblur="javascript: BusqRapida('P', '', this.value); CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);" 
										onkeypress="javascript: return KeyPress(event, this.id);"
										onkeyup="javascript: if(this.value == '') LimpiaDatosProveedor();"
									/>
									<input type="hidden" name="strCodigo" id="strCodigo" value="<?php echo $proveedor;?>" />
								</td>
								<td width="1%">&nbsp;</td>
								<td width="8%" align="left">&nbsp;Direcci&oacute;n</td>
								<td width="1%">:</td>
								<td width="34%" align="left"><input name="direccion" id="direccion" class="txt-plano" style="width:99%" readonly="true" value="<?php echo $direccion;?>"/></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left">&nbsp;Comuna</td>
								<td width="1%">:</td>
								<td width="15%" align="left"><input name="comuna" id="comuna" class="txt-plano" style="width:98%" readonly="true" value="<?php echo $comuna;?>"/></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td >&nbsp;Tel&eacute;fono</td>
					<td>:</td>
					<td colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%"><input name="telefono" id="telefono" class="txt-plano" style="width:98%" readonly="true" value="<?php echo $telefono;?>"/></td>
								<td width="1%">&nbsp;</td>
								<td width="4%" >&nbsp;Fax</td>
								<td width="1%">:</td>
								<td width="10%"><input name="fax" id="fax" class="txt-plano" style="width:97%" readonly="true" value="<?php echo $fax;?>"/></td>
								<td width="1%">&nbsp;</td>
								<td width="6%">&nbsp;Atenci&oacute;n</td>
								<td width="1%">:</td>
								<td width="30%" ><input name="atencion" id="atencion" class="txt-plano" style="width:99%" readonly="true" value="<?php echo $atencion;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="5%">&nbsp;E-Mail</td>
								<td width="1%">:</td>
								<td width="29%">
									<input name="email" id="email" class="txt-plano" style="width:98%" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);" 
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left">&nbsp;Cargo</td>
					<td>:</td>
					<td colspan="5">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="26%"><input class="txt-sborde" style="width:100%;background-color:#ececec" readonly="true" value="<?php echo $cargo;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="5%" align="left">&nbsp;C.Costo</td>
								<td width="1%">:</td>
								<td width="26%"><input class="txt-sborde" style="width:100%;background-color:#ececec" readonly="true" value="<?php echo $ccosto;?>" /></td>
								<td width="1%">&nbsp;</td>
								<td width="1%">
									<input type="checkbox" name="despachar" id="despachar" 
										onclick="javascript: 
											if(this.checked)
												document.getElementById('despachar_en').value='<?php echo htmlentities($despachar_en);?>';
											else
												document.getElementById('despachar_en').value='';
										"
									/>
								</td>
								<td width="12%">&nbsp;Despachar en</td>
								<td width="1%">:</td>
								<td width="25%"><input name="despachar_en" id="despachar_en" class="txt-plano" style="width:99%" readonly="true" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;Nota</td>
					<td valign="top">:</td>
					<td >
						<input type="hidden" name="hdnNota<?php echo $cont;?>" id="hdnNota<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($nota);?>" />
						<input name="txtNota<?php echo $cont;?>" id="txtNota<?php echo $cont;?>" class="txt-plano" style="width:100%; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="&nbsp;<?php echo htmlentities($nota);?>" 
							onmouseover="javascript: 
								clearInterval(Intervalo); 
								Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnNota<?php echo $cont;?>\')', 250);
							"
							onmouseout="javascript: DetieneTexto(Intervalo, this.id, 'hdnNota<?php echo $cont;?>');"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td align="center">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<th width="10%">C&oacute;digo</th>
					<th width="0%" align="left">&nbsp;Descripci&oacute;n</th>
					<th width="10%">Unidad</th>
					<?php
					if($tipodoc == 'O' || $tipodoc == 'I'){
						echo '<th width="10%">F.Inicio</th>';
						echo '<th width="10%">F.T&eacute;rmino</th>';
					}
					?>
					<th width="10%">Cantidad</th>
					<th width="10%">Valor</th>
					<th width="10%">Total</th>
				</tr>
			</table>
			<div style="position: relative; width:100%; height:73px; overflow:scroll;">
			<table border="0" width="100%" cellpadding="0" cellspacing="1">
			<?php
				$stmt = mssql_query("EXEC Bodega..sp_getTMPDetalleOrdenCompra 5, '$usuario'", $cnx);
				while($rst=mssql_fetch_array($stmt)){
					$ln++;?>
				<tr bgcolor="<?php echo ($ln % 2 ==0 ? '#FFFFFF' : '#EBF3FE');?>">
					<td width="10%" align="center">
					<?php
						echo $rst["strCodigo"];?>
						<input type="hidden" name="cod<?php echo $ln;?>" id="cod<?php echo $ln;?>" value="<?php echo $rst["strCodigo"];?>"/>
					</td>
					<td width="0%" align="left">&nbsp;<?php echo $rst["strDescripcion"];?></td>
					<td width="10%" align="center"><?php echo $rst["strUnidad"];?></td>
					<?php	
					if($tipodoc=='O' || $tipodoc == 'I'){
						echo '<td width="10%" align="center">'.$rst["dtmFchIni"].'</td>';
						echo '<td width="10%" align="center">'.$rst["dtmFchTer"].'</td>';
					}?>
					<td width="10%" align="right">
						<?php echo number_format($rst["dblCAutorizada"], 2, ',', '.');?>&nbsp;
						<input type="hidden" name="cant<?php echo $ln;?>" id="cant<?php echo $ln;?>" value="<?php echo $rst["dblCAutorizada"];?>" />
					</td>
					<td width="10%">
						<input name="val<?php echo $ln;?>" id="val<?php echo $ln;?>" class="txt-plano" style="width:95%; text-align:right" value="<?php echo $rst["dblValor"];?>"
							onblur="javascript: 
								CambiaColor(this.id, false); 
								Blur('cod<?php echo $ln;?>', this.id);
							"
							onfocus="javascript: CambiaColor(this.id, true);" 
							onkeypress="javascript: return KeyPress(event, this.id);"
							onkeyup="javascript: Calcular('cant<?php echo $ln;?>', 'val<?php echo $ln;?>', 'tot<?php echo $ln;?>');"
						/>
					</td>
					<td width="10%"><input name="tot<?php echo $ln;?>" id="tot<?php echo $ln;?>" class="txt-plano" style="width: 95%; text-align:right" readonly="true" value="<?php echo $rst["dblCAutorizada"] * $rst["dblValor"];?>" /></td>
				</tr>
			<?php
					$neto += $rst["dblCAutorizada"] * $rst["dblValor"];
				}
				mssql_free_result($stmt);?>
			</table></div>
		</td>
	</tr>
	<tr><td style="height:5px"></td></tr>
	<tr>
		<td align="right">
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td width="75%" align="left"><input name="solicitante" id="solicitante" class="txt-sborde" readonly="true" style="width:50%;background-color:#ececec" value="<?php echo htmlentities($nombsol);?>" /></td>
					<td width="14%" align="right"><b><?php echo $docpago == 2 ? 'A PAGAR' : 'NETO';?></b></td>
					<td width="1%">:</td>
					<td width="10%" align="left"><input name="neto" id="neto" class="txt-plano" style="width:97%; text-align:right" readonly="true" value="<?php echo $neto;?>" /></td>
				</tr>
				<tr>
					<td align="left"><b>&nbsp;Observaci&oacute;n</b></td>
					<td align="right"><b><?php echo $docpago == 2 ? '(-)Impuesto 10%' : 'I.V.A.';?></b></td>
					<td>:</td>
					<td><input name="impto" id="impto" class="txt-plano" style="width: 98%; text-align:right" readonly="true" value="<?php echo number_format($neto*$factor, 0,'','');?>" ></td>
				</tr>
				<tr>
					<td><input name="obs" id="obs" class="txt-plano" maxlength="1000" style="width:99%" /></td>
					<td align="right"><b>TOTAL<?php echo $docpago == 2 ? ' HONORARIOS' : '';?></b></td>
					<td>:</td>
					<td><input name="totalOC" id="totalOC" class="txt-plano" style="width:98%; text-align:right" readonly="true" value="<?php echo number_format($neto*($factor+1), 0,'','');?>" ></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="height:5px"><HR /></td></tr>
	<tr>
		<td align="right">
			<table border="0" width="100%" cellpadding="1" cellspacing="2">
				<tr>
					<td align="right">
					<?php					
					if($estado == 1 || $estado == 2 || $estado == 4 || $estado == 10 || trim($usu_sol) == trim($usuario)){?>
						<input type="button" name="btnAnula2" id="btnAnula2" class="boton" style="width:90px" value="Anular" 
							onclick="javascript: 
								if(confirm('¿Está seguro que desea anular esta orden de compra?')) CambiarEstado('N');
							"
						/>
					<?php
					}
					if($estado==2 || $estado==10 || $estado==11 || $estado==12 || $estado==16) $imprime=true;
					if($estado==4 && $tipodoc=='O') $imprime=true;
					if($imprime){?>
						<input type="button" name="btnImprimir" id="btnImprimir" class="boton" style="width:90px" value="Imprimir" 
							onClick="javascript: Imprimir('<?php echo $estado;?>');"
						/>
						<input type="button" name="btnGenerarPDF" id="btnGenerarPDF" class="boton" style="width:90px" value="Generar PDF..." 
							onClick="javascript: generarPDF('<?php echo $numero;?>');"
						/>
					<?php
					}
					
					if($tipodoc=='A') //&& ($estado==1 || $estado==4)) 
						$graba=true;
					elseif($tipodoc=='A' && $estado != 10)
						$vb=true;
					elseif($tipodoc=='O' && ($estado==0 || $estado==4)){
						$graba=true;
						if($nivel==1 || $nivel==2) $vb=true;
					}elseif($tipodoc=='O' && $estado==15 && $nivel==2) 
						$vb=true;
					
					if($vb){?>
					<input type="button" name="btnVB" id="btnVB" value="Visto Bueno" class="boton" style="width:100px" 
							onclick="javascript: CambiarEstado('V');" />
					<input type="button" name="btnAnula" id="btnAnula" value="Anular" class="boton" style="width:100px" 
							onClick="javascript: 
								if(confirm('¿Está seguro que desea anular esta orden de compra?')) CambiarEstado('N');
							"
						>
					<?php
					}
					if($graba){
					?>
						<input type="button" name="btnGraba" id="btnGraba" class="boton" style="width:90px" value="Grabar" 
							onclick="javascript: Graba();"
						/>
					<?php
					}?>
						<input type="button" name="btnCerrar" id="btnCerrar" value="Cerrar" class="boton" style="width:100px" 
							onClick="javascript: 
								parent.Deshabilitar(false);
								parent.CierraDialogo('divOrdenCompra', 'frmOrdenCompra');
							"
						>					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="numero" id="numero" value="<?php echo $numero;?>" />
<input type="hidden" name="numsol" id="numsol" value="<?php echo $numsol;?>" />
<input type="hidden" name="totfil" id="totfil" value="<?php echo $ln;?>" />
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="estado" id="estado" value="<?php echo $estado;?>" />
<input type="hidden" name="tdoc" id="tdoc" value="<?php echo $tipodoc;?>" />
<input type="hidden" name="nivel" id="nivel" value="<?php echo $nivel;?>" />
<input type="hidden" name="accion" id="accion"/>
</form>
<iframe name="valido" id="valido" frameborder="0" width="0px" height="0px"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
