<?php
include '../conexion.inc.php';
$usuario = $_GET["usuario"];
$contrato = $_GET["contrato"];
$reimprime = $_GET["reimprime"];
$original = $_GET["original"];
$cc = $_GET["cc"];

if($reimprime == 0 && $original == 1)
	$sql = "EXEC Orden..sp_getHojaRuta 0, '$usuario', '$contrato', 0";
else
	$sql = "EXEC Orden..sp_getHojaRuta 0, '$usuario', '$contrato', 1";

$titulo = ($original == 1) ? 'Movil' : 'Radio';
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../images/style.css">
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
	self.focus();
	self.print();
	
	if(parseInt('<?php echo $original;?>') == 1 && parseInt('<?php echo $cc;?>') == 1)
		parent.document.getElementById('copia').src = 'hoja_ruta.php?usuario=<?php echo $usuario;?>&contrato=<?php echo $contrato;?>&original=0&cc=1';
	else
		parent.document.getElementById('resultado').src=parent.document.getElementById('resultado').src;
}
-->
</script>
</head>
<body onLoad="javascript: Load();">
<div style=position:absolute;left:0px;top:0px><img src="../images/logo.jpg"></div>
<?php
$stmt = mssql_query($sql, $cnx);
while($rst = mssql_fetch_array($stmt)){?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center">
						<font size=5>Hoja de Ruta EDECO S.A.</font>
						<h3>
							<?php echo $titulo;?> - Sector <?php echo $rst["strDetalleDistrito"];?>
							<table border="2">
								<tr>
									<td ><b><font size=3><?php echo trim($rst["strOrden"]);?></b></font></td>
									<td ><b><font size=3><?php echo str_repeat('0', 6 - strlen($rst["dblNumero"])).$rst["dblNumero"];?></b></font></td>
								</tr>
							</table>
						</h3>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="1" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr><td colspan="11"><b>&nbsp;INFORMACION DE LA ORDEN DE TRABAJO</b></td></tr>
							<tr>
								<td width="80"><b>&nbsp;ORDEN Nº</b></td>
								<td width="1%"><b>:</b></td>
								<td colspan="5" width="320"><?php echo $rst["strOrden"];?></td>
								<td width="80"><b>Movil</b></td>
								<td width="1%"><b>:</b></td>
								<td ><?php echo $rst["strCodigoMovil"];?></td>
							</tr>
							<tr>
								<td ><b>&nbsp;Nº ODS</b></td>
								<td ><b>:</b></td>
								<td colspan="5"><?php echo $rst["strODS"];?></td>
								<td ><b>Inspector</b></td>
								<td ><b>:</b></td>
								<td colspan="3"><?php echo $rst["strInspector"];?></td>
							</tr>
							<tr>
								<td ><b>&nbsp;Direcci&oacute;n</b></td>
								<td ><b>:</b></td>
								<td colspan="5"><?php echo $rst["strDireccion"];?></td>
								<td ><b>Prioridad</b></td>
								<td ><b>:</b></td>
								<td colspan="3"><?php echo $rst["strDetallePrioridad"];?></td>
							</tr>
							<tr>
								<td ><b>&nbsp;Entre Calles</b></td> 
								<td ><b>:</b></td>
								<td colspan="5"><?php echo $rst["strEntreCalle"];?></td>
								<td ><b>F.Emisi&oacute;n</b></td>
								<td ><b>:</b></td>
								<td colspan="3"><?php echo formato_fecha($rst["dtmOrden"]);?></td>
							</tr>
							<tr>
								<td ><b>&nbsp;Comuna</b></td>
								<td ><b>:</b></td>
								<td colspan="5"><?php echo $rst["strDetalleComuna"];?></td>
								<td ><b>F.Vence</b></td> 
								<td ><b>:</b></td>
								<td colspan="3"><font size=3><?php echo formato_fecha($rst["dtmVence"]);?></font></td>
							</tr>
							<tr>
								<td ><b>&nbsp;Trabajo</b></td> 
								<td ><b>:</b></td>
								<td colspan="5"><?php echo $rst["strMotivo"];?></td>
								<td colspan="3"><b>Hr.Inicio:______</b></td>
								<td ><b>Hr.T&eacute;rmino:</b></td>
								<td >______</td> 
							</tr>
							<tr>
								<td><b>&nbsp;Cierre ISO</b></td>
								<td ><b>:</b></td>
								<td ><b>SI</b></td>
								<td width="20" >&nbsp;</td>
								<td style=border:none><b>NO</b></td>
								<td width='20'>&nbsp;</td>
								<td style=border:none width="100">&nbsp;</td>
								<td colspan="4"><b>Fec.y Hr.Soluci&oacute;n:</b></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" valign="top">
						<table border="1" width="100%" cellpadding="0" cellspacing="0" >
							<tr><td border="1"><b>&nbsp;TRABAJO EJECUTADO</b></td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
						</table>
					</td>
					<td width="50%" valign="top">
						<table width="100%" border="1" cellpadding="0" cellspacing="0" >
							<tr><td ><b>&nbsp;ANEXOS</b></td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
							<tr><td >&nbsp;</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="50%" rowspan="2" valign="top">
						<table border="1" width="100%" cellpadding="0" cellspacing="0" >
							<tr><td colspan="4" ><b>&nbsp;INVERSION DE MATERIALES</b></td></tr>
							<tr>
								<td width="25%">&nbsp;Codigo</td>
								<td width="40%">&nbsp;Material</td>
								<td width="15%">&nbsp;Unidad</td>
								<td width="20%">&nbsp;Cantidad</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
								<td >&nbsp;</td>
							</tr>
						</table>
						<table border="1" width="100%" cellpadding="0" cellspacing="0" >
							<tr><td ><b>&nbsp;OBSERVACIONES</b></td></tr>
							<tr><td style="height:102px" valign="top"><?php echo $rst["strObservacion"];?></td></tr>
						</table>
					</td>
					<td width="50%" valign="top">
						<table border="1" width="100%" cellpadding="0" cellspacing="0">
							<tr><td colspan="3"><b>&nbsp;INFORME ODT</b></td></tr>
							<tr>
								<td width="50%">&nbsp;Item</td>
								<td width="25%">&nbsp;Unidad</td>
								<td width="25%">&nbsp;Cantidad</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>			
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>			
					</td>
				</tr>
				<tr>
					<td width="50%" valign="top" style="height:100px">
						<table border="1" width="100%" cellpadding="0" cellspacing="0">
							<tr><td colspan="3"><b>&nbsp;CIERRES DE ATENCION</b></td></tr>
							<tr>
								<td width="34%">&nbsp;Hidraulico</td>
								<td width="33%">&nbsp;Anexo</td>
								<td width="33%">&nbsp;Cobro</td>
							</tr>
							<tr>
								<td style="height:32px">&nbsp;</td>
								<td style="height:32px">&nbsp;</td>
								<td style="height:32px">&nbsp;</td>
							</tr>		
						</table>	
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="1" width="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" width="0" cellpadding="0" cellspacing="0">
							<tr align="center" valign="bottom">
								<td width="200px" style="height:50px"><p><b>Jefe Terreno</b></p></td>
								<td width="200px"><p><b>Recepción</b></p></td>
								<td width="200px"><p><b>Supervisor</b></p></td>
								<td width="200px"><p><b>Revisión y cobro</b></p></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<H3>...</H3>
<?php	
}
mssql_free_result($stmt);
?>
</body>
</html>
<?php
mssql_close($cnx);
?>
