<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$editcant = $_GET["editcant"];
$usuario = $_GET["usuario"];
$perfil = $_GET["perfil"];
$contrato = $_GET["contrato"];
$numero = $_GET["numero"];
$movilpadre = $_GET["movilpadre"];
$cerrada = $_GET["cerrada"];

$error = 0;
switch($modulo){
	case 0: //Carga tabla temporal
		mssql_query("EXEC Orden..sp_getTMPDetalleInformaTrabajos 0, '$usuario', NULL, $numero", $cnx);
		break;
	case 1: //Agrega linea al detalle
		$paso = split('@@@', $_GET["movil"]);
		$movil = $paso[0];
		$ttrabajo = $paso[1];
		$codigo = $_GET["codigo"];
		$cubicacion = $_GET["cubicacion"];
		$cantpagada = $_GET["cantpagada"] != '' ? $_GET["cantpagada"] : 'NULL';
		list($cant1, $cant2, $cant3) = split('x', $cubicacion);
		if($cant3 != '')
			$cantidad = $cant1 * $cant2 * $cant3;
		elseif($cant2 != '')
			$cantidad = $cant1 * $cant2;
		else
			$cantidad = $cant1;
		
		$stmt = mssql_query("EXEC Orden..sp_setTMPDetalleInformaTrabajos 0, '$usuario', '$movil', '$codigo', '$cubicacion', $cantidad, $cantpagada, NULL, '$ttrabajo', 0", $cnx);
		if($rst = mssql_fetch_array($stmt)) $error = $rst["dblError"];
		mssql_free_result($stmt);
		break;
	case 2: //Elimina linea del detalle
		$idanexo = $_GET["idanexo"];
		$movil = $_GET["movil"];
		$codigo = $_GET["codigo"];
		
		mssql_query("EXEC Orden..sp_setTMPDetalleInformaTrabajos 3, '$usuario', '$movil', '$codigo', NULL, NULL, NULL, NULL, NULL, $idanexo", $cnx);
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Informar Trabajos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
var contrato = parent.document.getElementById('hdnContrato').value;
var Intervalo = 0;

function Load(){
	if(parseInt('<?php echo $error;?>') == 0){
		if('<?php echo $perfil;?>' == 'j.cobranza' || '<?php echo $perfil;?>' == 'informatica')
			parent.document.getElementById('divTotal').innerHTML = '<b>TOTAL:</b> ' + document.getElementById('hdnTotal').value + '.-';
	}else if(parseInt('<?php echo $error;?>') == 1)
		alert('Este ítem ya se encuentra informado.');
}

function Blur(ctrl, idanexo, movil, ind){
	var codigo = document.getElementById('hdnCodigo' + ind).value;
	var cubicacion = document.getElementById('hdnCubicacion' + ind).value;
	switch(ctrl.id){
		case 'txtCantInf' + ind:
			var valor_ant = document.getElementById('hdnCantInf' + ind).value;
			var valor_act = ctrl.value;
			if(parseFloat(valor_act) != parseFloat(valor_ant))
				document.getElementById('transaccion').src = 'transaccion.php?modulo=3&usuario=<?php echo $usuario;?>&idanexo=' + idanexo + '&movil=' + movil + '&codigo=' + codigo + '&ind=' + ind + '&valor_act=' + valor_act + '&cubicacion=' + cubicacion;
			break;
		case 'txtCantPag' + ind:
			if(parseInt('<?php echo $editcant;?>') == 1){
				var valor_ant = document.getElementById('hdnCantPag' + ind).value;
				var valor_act = ctrl.value;
				if(parseFloat(valor_act) != parseFloat(valor_ant)){
					document.getElementById('transaccion').src = 'transaccion.php?modulo=6&usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&numero=<?php echo $numero;?>&contrato=<?php echo $contrato;?>&idanexo=' + idanexo + '&movil=' + movil + '&codigo=' + codigo + '&ind=' + ind + '&valor_act=' + valor_act + '&cubicacion=' + cubicacion;
				}
			}
			break;
	}
}

function GotFocus(ctrl, ind){
	document.getElementById('linea').value = ind;
	var cant = document.getElementById('hdnCantidades' + ind).value;
	var paso = document.getElementById('hdnCubicacion' + ind).value;
	var cubic = paso.split('x');
	cubic[0] = (cubic[0] ? cubic[0] : 0);
	cubic[1] = (cubic[1] ? cubic[1] : 0);
	cubic[2] = (cubic[2] ? cubic[2] : 0);
	
	document.getElementById('txtCant1').value = '';
	document.getElementById('txtCant2').value = '';
	document.getElementById('txtCant3').value = '';
	if(parseInt(cant) > 1){
		if(cant == 2){
			document.getElementById('txtCant1').value = cubic[0];
			document.getElementById('txtCant2').value = cubic[1];
			document.getElementById('txtCant3').disabled = true;
		}else{
			document.getElementById('txtCant1').value = cubic[0];
			document.getElementById('txtCant2').value = cubic[1];
			document.getElementById('txtCant3').value = cubic[2];
			document.getElementById('txtCant3').disabled = false;
		}
		parent.Deshabilita(true);
		var top = window.innerHeight / 2;
		document.getElementById('divCantidades').style.top = top;
		document.getElementById('divCantidades').style.visibility = 'visible';
		document.getElementById('txtCant1').focus();
		document.getElementById('txtCant1').select();
	}else
		CambiaColor(ctrl.id, true);
}

function KeyPress(evento, ctrl, ind){
	var tecla = getCodigoTecla(evento);
	var totfil = document.getElementById('totfil').value;
	var sgte = parseInt(ind) + 1;
	if(tecla == 13){
		var movil = document.getElementById('hdnMovil' + ind).value;
		var calculo = 0, pos = 0;
		switch(ctrl.id){
			case 'txtCantInf' + ind:
				parent.document.getElementById('btnFin').focus();
				break;
			case 'txtCantPag' + ind:
				if(parseInt(sgte) <= parseInt(totfil)) 
					document.getElementById('txtCantPag' + sgte).focus();
				else
					parent.document.getElementById('btnFin').focus();
				break;
			case 'txtCant1':
				document.getElementById('txtCant2').focus();
				document.getElementById('txtCant2').select();
				break;
			case 'txtCant2':
				if(document.getElementById('hdnCantidades' + ind).value == 2){
					var cant1 = document.getElementById('txtCant1').value;
					var cant2 = document.getElementById('txtCant2').value;
					if(cant1 == '' || cant2 == '' || cant1 == 0 || cant2 == 0)
						alert('Debe ingresar la cantidad.');
					else{
						document.getElementById('txtCant1').value = 0;
						document.getElementById('txtCant2').value = 0;
						document.getElementById('hdnCubicacion' + ind).value = cant1 + 'x' + cant2;
						document.getElementById('txtCantInf' + ind).value = parseFloat(cant1) * parseFloat(cant2);
						
						document.getElementById('divCantidades').style.visibility = 'hidden';
						Blur(document.getElementById('txtCantInf' + ind), movil, ind);
						parent.Deshabilita(false);
						
						parent.document.getElementById('btnFin').focus();
					}
				}else{
					document.getElementById('txtCant3').focus();
					document.getElementById('txtCant3').select();
				}
				break;
			case 'txtCant3':
				var cant1 = document.getElementById('txtCant1').value;
				var cant2 = document.getElementById('txtCant2').value;
				var cant3 = document.getElementById('txtCant3').value;
				
				if(cant1 == '' || cant2 == '' || cant2 == '' || cant1 == 0 || cant2 == 0 || cant3 == 0)
					alert('Debe ingresar la cantidad');
				else{
					document.getElementById('txtCant1').value = 0;
					document.getElementById('txtCant2').value = 0;
					document.getElementById('txtCant3').value = 0;
					
					document.getElementById('hdnCubicacion' + ind).value = cant1 + 'x' + cant2 + 'x' + cant3;
					document.getElementById('txtCantInf' + ind).value = parseFloat(cant1) * parseFloat(cant2) * parseFloat(cant3);
					document.getElementById('divCantidades').style.visibility = 'hidden';
					Blur(document.getElementById('txtCantInf' + ind), movil, ind);
					parent.Deshabilita(false);
					
					parent.document.getElementById('btnFin').focus();
				}
				break;
		}
	}else if(tecla == 27){
		if(document.getElementById('divCantidades').style.visibility == 'visible'){
			parent.Deshabilita(false);
			document.getElementById('divCantidades').style.visibility = 'hidden';
			if(!document.getElementById('txtCantPag' + ind).readOnly)
				document.getElementById('txtCantPag' + ind).focus();
			else if(document.getElementById('txtCantInf' + (parseInt(ind) + 1)))
				document.getElementById('txtCantInf' + (parseInt(ind) + 1)).focus();
		}
	}else{
		switch(ctrl.id){
			case 'txtCantInf' + ind:
			case 'txtCantPag' + ind:
			case 'txtCant1':
			case 'txtCant2':
			case 'txtCant3':
				switch(document.getElementById('txtUnidad' + ind).value){
					case 'Nº':
					case 'JGO':
					case 'LATA':
					case 'N':
					case 'PAR':
					case 'GLOBAL':
					case 'PZA':
						return ValNumeros(evento, ctrl.id, false);
						break;
					default:
						return ValNumeros(evento, ctrl.id, true);
						break;
				}
				break;
		}
	}
}

function setCerrar(idanexo, movil, codigo, ctrl){
	var cerrar = ctrl.checked ? 1 : 0;
	
	document.getElementById('transaccion').src = 'transaccion.php?modulo=5&usuario=<?php echo $usuario;?>&idanexo=' + idanexo + '&movil=' + movil + '&codigo=' + codigo + '&cerrar=' + cerrar;
}

function setElimina(idanexo, movil, codigo){
	if(confirm('¿Está seguro que desea eliminar este ítem?'))
		self.location.href = 'detalle_orden_trabajo.php?modulo=2&usuario=<?php echo $usuario;?>&perfil=<?php echo $perfil;?>&contrato=<?php echo $contrato;?>&numero=<?php echo $numero;?>&movilpadre=<?php echo $movilpadre;?>&cerrada=<?php echo $cerrada;?>&idanexo=' + idanexo + '&movil=' + movil + '&codigo=' + codigo;
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
<div id="divCantidades" style="position:absolute; top:15%; left:70%; width:30%; visibility:hidden">
<input type="hidden" name="linea" id="linea" />
<table border="1" bordercolor="#CCCCCC" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%">
									<input name="txtCant1" id="txtCant1" class="txt-plano" style="width:99%; text-align:center" value="0" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return KeyPress(event, this, document.getElementById('linea').value);"
									/>
								</td>
								<td width="1%" align="center">x</td>
								<td width="10%">
									<input name="txtCant2" id="txtCant2" class="txt-plano" style="width:99%; text-align:center" value="0" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return KeyPress(event, this, document.getElementById('linea').value);"
									/>
								</td>
								<td width="1%" align="center">x</td>
								<td width="10%">
									<input name="txtCant3" id="txtCant3" class="txt-plano" style="width:99%; text-align:center" value="0" 
										onblur="javascript: CambiaColor(this.id, false);"
										onfocus="javascript: CambiaColor(this.id, true);"
										onkeypress="javascript: return KeyPress(event, this, document.getElementById('linea').value);"
									/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td style="height:5px"><hr /></td></tr>
				<tr><td style="color:#FF0000">&nbsp;Presione la tecla <b>ESC</b> para cerrar.</td></tr>
			</table>			
		</td>
	</tr>
</table>
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getTMPDetalleInformaTrabajos 1, '$usuario', '$contrato', $numero", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$cont++;?>
	<tr bgcolor="<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>">
		<td width="2%" align="center"><?php echo $cont;?></td>
		<td width="2%" align="center">
		<?php
		$icono = '';
		if(strtoupper(substr($rst["strCodigo"], 0, 1)) != 'N'){
			switch($rst["strEstado"]){
				case '37001':
				case '37004':
					$icono = 'ok.gif';
					break;
				case '37002':
				case '37006':
				case '37010':
					$icono = 'error.gif';
					break;
				case '37003':
					$icono = 'resuelta.gif';
					break;
				case '37005':
					$icono = 'pre_aprob.gif';
					break;
				case '37007':
					$icono = 'estrella.gif';
					break;
				default:
					$icono = 'sin_envio.gif';
					break;
			}
		}
		?>
			<img border="0" align="middle" src="../images/<?php echo $icono;?>" />
		</td>
		<td width="17%" align="center">
			<input type="hidden" name="hdnMovil<?php echo $cont;?>" id="hdnMovil<?php echo $cont;?>" value="<?php echo $rst["strMovil"];?>" />
			<input class="txt-sborde" style="width:99%;background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="&nbsp;<?php echo '['.$rst["strMovil"].'] '.(trim($rst["strTTrabajo"]) != '' ? $rst["strTTrabajo"] : $rst["strDescMovil"]);?>" />
		</td>
		<td width="10%" align="center">
		<?php
			if(($cerrada == 1 || $cerrada == 2) && ($perfil == 'j.cobranza' || $perfil == 'informatica')){
				echo '<a href="#" title="Item '.$rst["strCodigo"].'."';
				echo 'onclick="javascript: ';
				echo 'parent.Deshabilita(true); ';
				echo "AbreDialogo('divPagados', 'frmPagados', 'trabajos_pagados.php?modulo=0&usuario=$usuario&correlativo=$numero&movil=".$rst["strMovil"]."&item=".$rst["strCodigo"]."&informado=".$rst["dblCantidad"]."', true);";
				echo '"';
				echo 'onmouseover="window.status=\'Item '.$rst["strCodigo"].'.\'; return true;"';
				echo '>'.$rst["strCodigo"].'</a>';
				echo '<input type="hidden" name="hdnCodigo'.$cont.'" id="hdnCodigo'.$cont.'" value="'.$rst["strCodigo"].'" />';
			}else{
				echo '<input name="hdnCodigo'.$cont.'" id="hdnCodigo'.$cont.'" class="txt-sborde" style="width:99%;text-align:center;background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="'.$rst["strCodigo"].'" />';
			}
		?>
		</td>
		<td width="20%">
			<input type="hidden" name="hdnDescripcion<?php echo $cont;?>" id="hdnDescripcion<?php echo $cont;?>" value="&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>" />
			<input name="txtDescripcion<?php echo $cont;?>" id="txtDescripcion<?php echo $cont;?>" class="txt-sborde" style="width:99%; background-color:<?php echo ($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE');?>" readonly="true" value="&nbsp;<?php echo htmlentities($rst["strDescripcion"]);?>" 
				onmouseover="javascript:
					clearInterval(Intervalo); 
					Intervalo=setInterval('MueveTexto(\''+this.id+'\', \'hdnDescripcion<?php echo $cont;?>\')', 250);
				"
				onmouseout="javascript:
					DetieneTexto(Intervalo, this.id, 'hdnDescripcion<?php echo $cont;?>');
				"
			/>
		</td>
		<td width="10%" align="center"><input name="txtUnidad<?php echo $cont;?>" id="txtUnidad<?php echo $cont;?>" class="txt-sborde" style="width:99%; text-align:center;background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo trim($rst["strUnidad"]);?>" /></td>
		<td width="8%"><input name="txtFechaPrecio<?php echo $cont;?>" id="txtFechaPrecio<?php echo $cont;?>" class="txt-sborde" style="width:99%;text-align:center;background-color:<?php echo ($cont % 2)==0 ? '#FFFFFF' : '#EBF3FE';?>" readonly="true" value="<?php echo ($perfil == 'j.cobranza' || $perfil == 'informatica') ? number_format($rst["dblPrecio"], 0, '', '.').'&nbsp;' : $rst["dtmFch"];?>"/></td>
		<td width="10%" align="right">
			<input type="hidden" id="hdnCantidades<?php echo $cont;?>" value="<?php echo $rst["intCantidades"];?>"/>
			<input type="hidden" id="hdnCubicacion<?php echo $cont;?>" value="<?php echo $rst["strCubicacion"];?>"  />
			<input type="hidden" id="hdnCantInf<?php echo $cont;?>" value="<?php echo $rst["dblCantidad"];?>"  />
			<input id="txtCantInf<?php echo $cont;?>" class="txt-plano" style="width:98%; text-align:right" <?php echo ($rst["strEstado"] == '37007' && ($perfil != 'j.cobranza' && $perfil != 'informatica') ? 'readonly="true"' : '');?> value="<?php echo number_format($rst["dblCantidad"], 2, '.','');?>" 
				onblur="javascript: 
					CambiaColor(this.id, false);
					Blur(this, '<?php echo $rst["IdAnexo"];?>', '<?php echo trim($rst["strMovil"]);?>', <?php echo $cont;?>);
				"
				onfocus="javascript: return GotFocus(this, <?php echo $cont;?>);"
				onkeypress="javascript: return KeyPress(event, this, <?php echo $cont;?>);"
			/>
		</td>
		<td width="10%" align="right">
			<input type="hidden" id="hdnCantPag<?php echo $cont;?>" value="<?php echo $rst["dblCantidadEmos"];?>"/>
			<input id="txtCantPag<?php echo $cont;?>" class="txt-plano" style="width:98%; text-align:right" <?php echo ($editcant == 0 ? 'readonly="true"' : '');?> value="<?php echo number_format($rst["dblCantidadEmos"], 2, '.','');?>" 
				onblur="javascript: 
					CambiaColor(this.id, false);
					Blur(this, '<?php echo $rst["IdAnexo"];?>', '<?php echo trim($rst["strMovil"]);?>', <?php echo $cont;?>);
				"
				onfocus="javascript: CambiaColor(this.id, true);"
				onkeypress="javascript: return KeyPress(event, this, <?php echo $cont;?>);"
			/>
		</td>
		<td width="6%" align="center">
		<?php
		//if(($movilpadre == trim($rst["strMovil"])) || ($rst["strEstado"] == 37007)){
		if($rst["strEstado"] == 37007 || $rst["dblCerrar"] == 2){
			echo '&nbsp;';
		}else{
			$movil = $rst["strMovil"];?>
			<input type="checkbox" name="chkCerrar<?php echo $cont;?>" id="chkCerrar<?php echo $cont;?>" <?php echo $rst["dblCerrar"] == 1 || $rst["dblCerrar"] == 2 ? 'checked' : '';?> <?php echo $rst["dblCerrar"] == 2 ? 'disabled' : '';?>
				onclick="javascript: setCerrar('<?php echo trim($rst["IdAnexo"]);?>', '<?php echo trim($rst["strMovil"]);?>', '<?php echo trim($rst["strCodigo"]);?>', this);"
			/>
		<?php
		}?>
		</td>
		<td width="2%" align="center">
		<?php
		if($rst["strEstado"] == 37007){
			if($perfil == 'informatica' || $perfil == 'j.cobranza'){
				?>
				<a href="#" title="Borra &iacute;tem: <?php echo htmlentities($rst["strDescripcion"]);?>"
				onclick="javascript: setElimina('<?php echo $rst["IdAnexo"];?>', '<?php echo trim($rst["strMovil"]);?>', '<?php echo trim($rst["strCodigo"]);?>')"
				onmouseover="javascript: window.status='Borra ítem: <?php echo htmlentities($rst["strDescripcion"]);?>'; return true;"
			><img id="img<?php echo $cont;?>" src="../images/borrar1.gif" border="0" align="middle" /></a>
			<?php
			} else {
				echo "&nbsp;";
			}
		}else{?>
			<a href="#" title="Borra &iacute;tem: <?php echo htmlentities($rst["strDescripcion"]);?>"
				onclick="javascript: setElimina('<?php echo $rst["IdAnexo"];?>', '<?php echo trim($rst["strMovil"]);?>', '<?php echo trim($rst["strCodigo"]);?>')"
				onmouseover="javascript: window.status='Borra ítem: <?php echo htmlentities($rst["strDescripcion"]);?>'; return true;"
			><img id="img<?php echo $cont;?>" src="../images/borrar1.gif" border="0" align="middle" /></a>
		<?php			
		}
		$total += ($rst["dblPrecio"] * $rst["dblCantidadEmos"]);
		?>
		</td>
	</tr>
<?php
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
<input type="hidden" name="hdnContrato" id="hdnContrato" value="<?php echo $contrato;?>" />
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
<input type="hidden" name="hdnTotal" id="hdnTotal" value="<?php echo number_format($total, 0, '', '.');?>" />
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>