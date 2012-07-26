<?php
$bodega=$_GET["bodega"];
$tipodoc=$_GET["tipodoc"]; 
$mes=$_GET["mes"];
$ano=$_GET["ano"];
$material=$_GET["material"];
$proveedor=$_GET["proveedor"]; 
$tbusqueda=$_GET["tbusqueda"]; 
$observacion=$_GET["observacion"];
$numero=$_GET["numero"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Busqueda de Documentos</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){ 
	document.getElementById('frmListado').setAttribute('height', parent.document.getElementById('frmDetalle').height - 5);
	document.getElementById('frmListado').src="det_detalle.php?bodega=<?php echo $bodega;?>&tipodoc=<?php echo $tipodoc;?>&mes=<?php echo $mes;?>&ano=<?php echo $ano;?>&material=<?php echo $material;?>&proveedor=<?php echo $proveedor;?>&tbusqueda=<?php echo $tbusqueda;?>&observacion=<?php echo $observacion;?>&numero=<?php echo $numero;?>"
}
-->
</script>
<body marginwidth="0" marginheight="1px" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<?php
			switch($tipodoc){
				case 0:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="10%">Fecha</th>';
					echo '<th width="10%">N&deg; Solicitud</th>';
					echo '<th width="30%" align="left">&nbsp;Cargo</th>';
					echo '<th width="45%" align="left">&nbsp;Observaci&oacute;n</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 1:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="8%">Fecha</th>';
					echo '<th width="8%">N&deg; OC</th>';
					echo '<th width="3%">T.D.</th>';
					echo '<th width="15%" align="left">&nbsp;Proveedor</th>';
					echo '<th width="10%">N&deg;Comp.</th>';
					echo '<th width="2%" >&nbsp;</th>';
					echo '<th width="8%" >Factura</th>';
					echo '<th width="10%" align="right">Monto&nbsp;</th>';
					echo '<th width="10%" align="left">&nbsp;Cargo</th>';
					echo '<th width="12%" align="left">&nbsp;Observaci&oacute;n</th>';
					echo '<th width="9%" align="right">Neto&nbsp;</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 2:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="10%">Fecha</th>';
					echo '<th width="10%">N&deg; G.Ingreso</th>';
					echo '<th width="30%" align="left">&nbsp;Bodega</th>';
					echo '<th width="10%">N&deg; O.Compra</th>';
					echo '<th width="20%" align="left">&nbsp;T.Documento</th>';
					echo '<th width="15%">Referencia</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 3:
				case 4:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="10%">Fecha</th>';
					echo '<th width="12%">N&deg; G.'.($tipodoc == 3 ? 'Despacho' : 'Devoluci&oacute;n').'</th>';
					echo '<th width="45%" align="left">&nbsp;Movil</th>';
					echo '<th width="28%" align="left">&nbsp;Bodega</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 5:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="10%">Fecha</th>';
					echo '<th width="11%">N&deg; V.Consumo</th>';
					echo '<th width="37%" align="left">&nbsp;Obra</th>';
					echo '<th width="37%" align="left">&nbsp;Responsable</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 6:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="10%">Fecha</th>';
					echo '<th width="11%">N&deg; Documento</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '<th width="12%" align="left">&nbsp;T. Documento</th>';
					echo '<th width="30%" align="left">&nbsp;Proveedor</th>';
					echo '<th width="10%">N&deg; O.Compra</th>';
					echo '<th width="10%">N&deg; Recepci&oacute;n</th>';
					echo '<th width="10%" align="right">Monto&nbsp;</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 7:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="8%">Fecha</th>';
					echo '<th width="12%">N&deg; Documento</th>';
					echo '<th width="20%" align="left">&nbsp;Cargo</th>';
					echo '<th width="45%" align="left">&nbsp;Nota</th>';
					echo '<th width="10%" align="right">Neto&nbsp;</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 8:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="8%">Fecha</th>';
					echo '<th width="12%">N&deg; Gu&iacute;a</th>';
					echo '<th width="15%" align="left">&nbsp;Cargo</th>';
					echo '<th width="30%" align="left">&nbsp;Nombre</th>';
					echo '<th width="30%" align="left">&nbsp;Bodega</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 9:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="8%">Fecha</th>';
					echo '<th width="12%">N&deg; Gu&iacute;a</th>';
					echo '<th width="15%" align="left">&nbsp;Cargo</th>';
					echo '<th width="20%" align="left">&nbsp;Nombre</th>';
					echo '<th width="20%" align="left">&nbsp;Bodega</th>';
					echo '<th width="20%" align="left">&nbsp;Usuario</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 10:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="10%">Fecha</th>';
					echo '<th width="13%">N&deg; T.bodega</th>';
					echo '<th width="72%" align="left">&nbsp;Usuario</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
				case 11:
					echo '<tr>';
					echo '<th width="3%">N&deg;</th>';
					echo '<th width="10%">Fecha</th>';
					echo '<th width="10%">N&deg;F.Interna</th>';
					echo '<th width="65%" align="left">&nbsp;Cargo</th>';
					echo '<th width="10%">Total&nbsp;</th>';
					echo '<th width="2%">&nbsp;</th>';
					echo '</tr>';
					break;
			}
			?>
			</table>
		</td>
	</tr>
	<tr><td><iframe name="frmListado" id="frmListado" frameborder="0" width="100%" scrolling="yes" ></iframe></td></tr>
</table>
</body>
</html>