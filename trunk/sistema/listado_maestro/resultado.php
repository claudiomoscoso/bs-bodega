<?php
include '../autentica.php';
include '../conexion.inc.php';

$campos = $_POST["hdnCampos"];
$contrato = $_POST["cmbContrato"];
$movil = $_POST["cmbMovil"];
$anexos = $_POST["cmbAnexos"];
$tanexo = $_POST["cmbTAnexo"];
$ocriterios = $_POST["cmbOCriterios"];
$ftdesde = $_POST["txtFTDesde"] != '' ? "'".formato_fecha($_POST["txtFTDesde"], false, true)."'" : 'NULL';
$fthasta = $_POST["txtFTHasta"] != '' ? "'".formato_fecha($_POST["txtFTHasta"], false, true)."'" : 'NULL';
$fodesde = $_POST["txtFODesde"] != '' ? "'".formato_fecha($_POST["txtFODesde"], false, true)."'" : 'NULL';
$fohasta = $_POST["txtFOHasta"] != '' ? "'".formato_fecha($_POST["txtFOHasta"], false, true)."'" : 'NULL';
$fhdesde = $_POST["txtFHDesde"] != '' ? "'".formato_fecha($_POST["txtFHDesde"], false, true)."'" : 'NULL';
$fhhasta = $_POST["txtFHHasta"] != '' ? "'".formato_fecha($_POST["txtFHHasta"], false, true)."'" : 'NULL';
$orden = $_POST["hdnOrden"];
$lordenes = $_POST["txtOrdenes"];
$depto = $_POST["cmbDepto"];
if($depto <> 'all') $campos .= ',39,40,41,42';
$stmt = mssql_query("EXEC Orden..sp_getCampos 2, '$campos'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$alias = split('&&&', $rst["strAlias"]);
	$alinea = split('&&&', $rst["strAlinea"]);
	$largo = split('&&&', $rst["strLargo"]);
	$lrgttl = $rst["dblLrgTtl"];
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado Maestro</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Scroll(ctrl){
	document.getElementById('divCabecera').scrollLeft = ctrl.scrollLeft;
	return true;
}

function Load(){
	document.getElementById('divDetalle').style.height = (window.innerHeight - 60) + 'px';
	if(parseInt('<?php echo $lrgttl;?>') < window.innerWidth){
		document.getElementById('tblCabecera').setAttribute('width', window.innerWidth + 'px');
		document.getElementById('tblDetalle').setAttribute('width', (window.innerWidth - 17) + 'px');
	}
}
-->
</script>
<body onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<div id="divCabecera" style="position:relative; width:100%; height:22px; overflow:hidden">
			<table id="tblCabecera" border="0" width="<?php echo $lrgttl;?>px" cellpadding="0" cellspacing="0">
				<tr>
					<th width="25px">N&deg;</th>
				<?php
				for($i = 0; $i < count($alias); $i++){
					echo '<th width="'.$largo[$i].'px" align="'.$alinea[$i].'">'.$alias[$i].'</th>';
				}
				?>
				</tr>
			</table>
			</div>
			<div id="divDetalle" style="position:relative; width:100%; height:100px; overflow:scroll"
				onscroll="javascript: Scroll(this);"
			>
			<table id="tblDetalle" width="<?php echo $lrgttl;?>px" border="0" cellpadding="0" cellspacing="1">
			<?php
/*echo "EXEC Orden..sp_getListadoMaestro 0, '$campos', '$orden', '$contrato', '$movil', '$anexos', '$tanexo', $ocriterios, $ftdesde, $fthasta, $fodesde, $fohasta, $fhdesde, $fhhasta, '$lordenes', '$depto'";*/
			$stmt = mssql_query("EXEC Orden..sp_getListadoMaestro 0, '$campos', '$orden', '$contrato', '$movil', '$anexos', '$tanexo', $ocriterios, $ftdesde, $fthasta, $fodesde, $fohasta, $fhdesde, $fhhasta, '$lordenes', '$depto', '$usuario'", $cnx);
			if($rst = mssql_fetch_array($stmt)){
				do{
					$cont++;
					echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
					echo '<td width="25px" align="center">'.$cont.'</td>';
					for($i = 0; $i < count($alias); $i++){
						if(strlen($rst[$i])==26 && substr($rst[$i],14,1)==":" && substr($rst[$i],17,1)==":" && substr($rst[$i],20,1)==":" && substr($rst[$i],25,1)=="M") {
							$d='';
							$d=substr(trim($rst[$i]),4,2).' '.substr(trim($rst[$i]),0,3).' '.substr(trim($rst[$i]),7,4).' '.substr(trim($rst[$i]),12,8).' '.substr($rst[$i],24,2);
							$d=strtotime($d);
							$d=date('d/m/Y H:i:s',$d);
							echo '<td width="'.$largo[$i].'px" align="'.$alinea[$i].'" >&nbsp;'.$d.'</td>';
						}
						else {
							echo '<td width="'.$largo[$i].'px" align="'.$alinea[$i].'" >&nbsp;'.trim($rst[$i]).'</td>';
						}
					}
					echo '</tr>';
				}while($rst = mssql_fetch_array($stmt));
			}else
				echo '<tr ><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
			mssql_free_result($stmt);
			?>
			</table>
			</div>
		</td>
	</tr>
<!--	<tr><td><hr /></td></tr> -->
	<tr>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top">
						
						<input type="button" name="btnAtras" id="btnAtras" class="boton" style="width:90px" value="&lt;&lt; Anterior" 
							onclick="javascript: self.location.href='index.php<?php echo $parametros;?>';"
						/>
					</td>
					<td align="left">
					<form name="frm" id="frm" method="post" action="exporta.php" target="transaccion">
						<input type="button" name="btnExportar" id="btnExportar" class="boton" style="width:90px" <?php echo ($cont == 0 ? 'disabled="disabled"' : '');?> value="Exportar..."
							onclick="javascript: document.getElementById('frm').submit();"
						/>
						<input type="hidden" name="hdnCampos" id="hdnCampos" value="<?php echo $campos;?>" />
						<input type="hidden" name="hdnContrato" id="hdnContrato" value="<?php echo $contrato;?>" />
						<input type="hidden" name="hdnMovil" id="hdnMovil" value="<?php echo $movil;?>" />
						<input type="hidden" name="hdnAnexo" id="hdnAnexo" value="<?php echo $anexos;?>" />
						<input type="hidden" name="hdnTAnexo" id="hdnTAnexo" value="<?php echo $tanexo;?>" />
						<input type="hidden" name="hdnOCriterios" id="hdnOCriterios" value="<?php echo $ocriterios;?>" />
						<input type="hidden" name="hdnFTDesde" id="hdnFTDesde" value="<?php echo $ftdesde;?>" />
						<input type="hidden" name="hdnFTHasta" id="hdnFTHasta" value="<?php echo $fthasta;?>" />
						<input type="hidden" name="hdnFODesde" id="hdnFODesde" value="<?php echo $fodesde;?>" />
						<input type="hidden" name="hdnFOHasta" id="hdnFOHasta" value="<?php echo $fohasta;?>" />
						<input type="hidden" name="hdnFHDesde" id="hdnFHDesde" value="<?php echo $fhdesde;?>" />
						<input type="hidden" name="hdnFHHasta" id="hdnFHHasta" value="<?php echo $fhhasta;?>" />
						<input type="hidden" name="hdnOrden" id="hdnOrden" value="<?php echo $orden;?>" />
						<input type="hidden" name="hdnDepto" id="hdnDepto" value="<?php echo $depto;?>" />
					</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<iframe name="transaccion" id="transaccion" style="display:none"></iframe>
</body>
</html>
<?php
mssql_close($cnx);
?>
