<?php
include '../conexion.inc.php';

$ccosto = $_GET["ccosto"] != '' ? $_GET["ccosto"] : $_POST["txtCCosto"];
$accion = $_POST["accion"];
if($accion == 'N' || $accion == 'M'){
	$equipo=$_POST["equipo"];
	$clase=$_POST["cmbClase"];
	$marca=$_POST["txtMarca"];
	$modelo=$_POST["txtModelo"];
	$serie=$_POST["txtSerie"];
	$capacidad=$_POST["txtCapacidad"];
	$rtecnica=$_POST["txtRTecnica"] != '' ? "'".formato_fecha($_POST["txtRTecnica"], false, true)."'" : 'NULL';
	$caract=$_POST["txtCaract"];
	$ano=$_POST["txtAno"] != '' ? $_POST["txtAno"] : 'NULL';
	$patente=$_POST["txtPatente"];
	$tcontador=$_POST["cmbTContador"];
	
	if($accion=='N'){
		$stmt = mssql_query("EXEC Operaciones..sp_getCentroCosto 1, '$ccosto'", $cnx);
		if($rst=mssql_fetch_array($stmt)) $estado=2;
		mssql_free_result($stmt);
	}
	
	if($estado!=2){
		mssql_query("EXEC Operaciones..sp_setAgregaCentroCosto '$ccosto', $equipo, $clase, '$marca', '$modelo', '$serie', '$capacidad', '$caract', $rtecnica, '$patente', $ano, $tcontador", $cnx);	
		$estado=1;
	}
}else{
	$stmt = mssql_query("EXEC Operaciones..sp_getCentroCosto 1, '$ccosto'", $cnx);
	if($rst=mssql_fetch_array($stmt)){
		$equipo=$rst["dblEquipo"];
		$desc_equipo=$rst["strEquipo"];
		$clase=$rst["dblClase"];
		$marca=$rst["strMarca"];
		$modelo=$rst["strModelo"];
		$serie=$rst["strSerie"];
		$ano=$rst["dblAno"];
		$patente=$rst["strPatente"];
		$capacidad=$rst["strCapacidad"];
		$caract=$rst["strCaracteristicas"];
		$rtecnica=$rst["dtmRTecnica"];
		$tcontador=$rst["dblTipoUso"];
	}
	mssql_free_result($stmt);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Centro de Costos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	if('<?php echo $estado;?>'=='1'){
		parent.Deshabilita(false);
		parent.CierraDialogo('divCCosto', 'frmCCosto');
		parent.Buscar();
	}else if('<?php echo $estado;?>'=='2'){ 
		alert('El centro de costo que intenta ingresar ya existe.');
		document.getElementById('txtCCosto').focus();
	}else{ 
		if('<?php echo $ccosto;?>'!=''){
			document.getElementById('txtCCosto').readOnly=true;
			document.getElementById('accion').value='M';
		}else
			document.getElementById('accion').value='N';
	}
}

function KeyPress(evento, ctrl){
	var tecla = getCodigoTecla(evento);
	var sw=true;
	if(tecla==13){
		if(ctrl=='txtEquipo'){
			Deshabilita(true);
			AbreDialogo('divEquipos', 'frmEquipos', 'buscar_equipos.php?texto='+document.getElementById('txtEquipo').value+'&ctrl=txtEquipo&foco=cmbTContador');
		}
	}else if(tecla=='46'){
		if(ctrl=='txtRTecnica') document.getElementById(ctrl).value='';
	}else{
		if(ctrl=='txtRTecnica') sw=false;
	}
	return sw;
}

function Deshabilita(sw){
	document.getElementById('txtCCosto').disabled=sw;
	document.getElementById('txtEquipo').disabled=sw;
	document.getElementById('cmbClase').disabled=sw;
	document.getElementById('txtMarca').disabled=sw;
	document.getElementById('txtModelo').disabled=sw;
	document.getElementById('txtSerie').disabled=sw;
	document.getElementById('txtAno').disabled=sw;
	document.getElementById('txtCapacidad').disabled=sw;
	document.getElementById('txtRTecnica').disabled=sw;
	document.getElementById('txtPatente').disabled=sw;
	document.getElementById('txtCaract').disabled=sw;
	document.getElementById('btnGuardar').disabled=sw;
	document.getElementById('btnCerrar').disabled=sw;
}

function Guardar(){
	if(document.getElementById('txtCCosto').value==''){
		alert('Debe ingresar el centro de costo');
		document.getElementById('txtCCosto').focus();
	}else if(document.getElementById('equipo').value==''){
		alert('Debe ingresar el equipo');
		document.getElementById('txtEquipo').focus();
	}else
		document.getElementById('frm').submit();
}
-->
</script>
<body onload="javascript: Load();">
<div id="divCalendario" style="z-index:1; position:absolute; width:30%; left: 70%; top: 8%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra calendario." 
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divCalendario', 'frmCalendario');
										"
										onmouseover="javascript: window.status='Cierra administrador de equipos.'; return true"
									><img border="0" align="middle" src="../images/close.png"></a>
								</td>
								<td align="center">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmCalendario" id="frmCalendario" frameborder="1" style="border:thin" scrolling="no" width="100%" height="125px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="divEquipos" style="z-index:1; position:absolute; width:60%; left: 20%; top: 1%; visibility:hidden">
<table border="1" bordercolor="#CCCCCC" bgcolor="#FFFFFF" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="2" background="../images/borde_med.png">
							<tr>
								<td align="center" valign="middle" width="15px">
									<a href="#" title="Cierra administrador de equipos." 
										onclick="javascript: 
											Deshabilita(false);
											CierraDialogo('divEquipos', 'frmEquipos');
										"
										onmouseover="javascript: window.status='Cierra administrador de equipos.'; return true"
									><img border="0" align="middle" src="../images/close.png"></a>
								</td>
								<td align="center"><b>Equipos</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td valign="top"><iframe name="frmEquipos" id="frmEquipos" frameborder="1" style="border:thin" scrolling="no" width="100%" height="155px" marginheight="0" marginwidth="0" src="../cargando.php"></iframe></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>


<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr><td colspan="3" style="height:10px"></td></tr>
	<tr>
		<td width="10%" nowrap="nowrap">&nbsp;C.Costo</td>
		<td width="1%">:</td>
		<td width="89%">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%" align="left">
						<input name="txtCCosto" id="txtCCosto" class="txt-plano" style="width:98%;" value="<?php echo $ccosto;?>" 
							onblur="javascript:	CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Equipo</td>
					<td width="1%">:</td>
					<td>
						<input name="txtEquipo" id="txtEquipo" class="txt-plano" style="width:98%;" value="<?php echo $desc_equipo;?>" 
							onblur="javascript:	CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
							onkeypress="javascript:	return KeyPress(event, this.id);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;T.Contador</td>
					<td width="1%">:</td>
					<td width="10%">
						<select name="cmbTContador" id="cmbTContador" class="sel-plano" style="width:100%">
							<option value="0"></option>
							<option value="1" <?php echo $tcontador == 1 ? 'selected' : '';?>>Km.</option>
							<option value="2" <?php echo $tcontador == 2 ? 'selected' : '';?>>Hr.</option>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;Clase</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%">
						<select name="cmbClase" id="cmbClase" class="sel-plano" style="width:100%">
							<option value="1" <?php echo $clase == 1 ? 'selected' : '';?>>Menor</option>
							<option value="2" <?php echo $clase == 2 ? 'selected' : '';?>>Mayor</option>
						</select>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Marca</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtMarca" id="txtMarca" class="txt-plano" style="width:98%;" value="<?php echo $marca;?>" 
							onblur="javascript:	CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Modelo</td>
					<td width="1%">:</td>
					<td width="20%" align="right">
						<input name="txtModelo" id="txtModelo" class="txt-plano" style="width:98%;" value="<?php echo $modelo;?>"
							onblur="javascript:	CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="5%">&nbsp;Serie</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtSerie" id="txtSerie" class="txt-plano" style="width:98%;" value="<?php echo $serie;?>" 
							onblur="javascript:	CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;A&ntilde;o</td>
		<td>:</td>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
				<tr>
					<td width="10%">
						<input name="txtAno" id="txtAno" class="txt-plano" style="width:100%; text-align:center" maxlength="4" value="<?php echo $ano;?>" 
							onblur="javascript:	CambiaColor(this.id, false);"
							onkeypress="javascript: return ValNumeros(event, this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Capacidad</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtCapacidad" id="txtCapacidad" class="txt-plano" style="width:98%;" value="<?php echo $capacidad;?>" 
							onblur="javascript:	CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Rev.T&eacute;cnica</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtRTecnica" id="txtRTecnica" class="txt-plano" style="width:98%; text-align:center;" value="<?php echo $rtecnica=='NULL' ? '' : str_replace("'", '',$rtecnica);?>" 
							onkeypress="javascript: return KeyPress(event, this.id);"
						/>
					</td>
					<td width="2%" align="center">
						<a href="#" title="Abre calendario."
							onblur="javascript: 
								CambiaColor('txtRTecnica', false);
								CambiaImagen('imgRTecnica', false);
							"
							onclick="javascript:
								Deshabilita(true);
								AbreDialogo('divCalendario', 'frmCalendario', '../calendario/index.php?ctrl=txtRTecnica&fecha='+document.getElementById('txtRTecnica').value);
							"
							onfocus="javascript: 
								CambiaColor('txtRTecnica', true);
								CambiaImagen('imgRTecnica', true);
							"
						><img id="imgRTecnica" border="0" align="middle" src="../images/aba.gif" /></a>
					</td>
					<td width="1%">&nbsp;</td>
					<td width="5%">&nbsp;Patente</td>
					<td width="1%">:</td>
					<td width="20%">
						<input name="txtPatente" id="txtPatente" class="txt-plano" style="width:100%; text-align:center" value="<?php echo $patente;?>" 
							onblur="javascript:	CambiaColor(this.id, false);"
							onfocus="javascript: CambiaColor(this.id, true);"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" >&nbsp;Caracteristica</td>
		<td valign="top" >:</td>
		<td >
			<textarea name="txtCaract" id="txtCaract" class="txt-plano" style="width:99%;"  rows="3" 
				onblur="javascript:	CambiaColor(this.id, false);"
				onfocus="javascript: CambiaColor(this.id, true);"
			><?php echo $caract;?></textarea>
		</td>
	</tr>
	<tr><td colspan="3" valign="bottom" style="height:27px"><hr /></td></tr>
	<tr>
		<td colspan="3" align="right">
			<input type="hidden" name="ccosto" id="ccosto" value="<?php echo $ccosto;?>" />
			<input type="hidden" name="equipo" id="equipo" value="<?php echo $equipo;?>" />
			<input type="hidden" name="accion" id="accion" value="<?php echo $accion;?>" />
			<input type="button" name="btnGuardar" id="btnGuardar" class="boton" style="width:90px" value="Guardar" 
				onclick="javascript: Guardar();"
			/>
			<input type="button" name="btnCerrar" id="btnCerrar" class="boton" style="width:90px" value="Cerrar" 
				onclick="javascript:
					parent.Deshabilita(false);
					parent.CierraDialogo('divCCosto', 'frmCCosto');
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
