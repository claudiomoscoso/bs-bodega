<?php
include '../conexion.inc.php';
include '../fpdf.php';

$usuario = $_GET["usuario"];
$bodega = $_GET["bodega"];
$numero = $_GET["numero"];

$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'OC', $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$numusu = $rst["dblUltima"];
	$forma_pago = $rst["Forma_Pago"];
	$fecha = $rst["dtmFecha"];
	$proveedor = $rst["strNombre"];
	$rut = $rst["strRut"];
	$direccion = $rst["strDireccion"];
	$comuna = $rst["Comuna"];
	$telefono = $rst["strTelefono"];
	$fax = $rst["strFax"];
	$nota = $rst["strObservacion"];
	$strCargo = $rst["strCargo"];
	$cargo = $rst["Cargo"];
	$contacto = $rst["strContacto"];
	$factor = $rst["dblIva"];
	$tipodoc = $rst["strTipoDoc"];
	$docpago = $rst["dblDocPago"];
}
mssql_free_result($stmt);

if($factor == ''){
	$stmt = mssql_query("SELECT dblFactor FROM Impuesto WHERE id = $docpago", $cnx);
	if($rst = mssql_fetch_array($stmt)) $factor = $rst["dblFactor"];
	mssql_free_result($stmt);
}

$stmt = mssql_query("EXEC Bodega..sp_getContrato '$strCargo'", $cnx);
if($rst = mssql_fetch_array($stmt)) $contrato = $rst["strDetalle"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'OCA', $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$firma = $rst["firma"];
	$nombsol = $rst["nombre"];
}
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getFirmaGerencia $numero", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$firmagnte = $rst["firma"];
	$nombgnte = $rst["nombre"];
}
mssql_free_result($stmt);

$stmt = mssql_query("SELECT strAutoriza FROM Autorizaciones WHERE dblNumero = $numero AND strAccion = 1",$cnx);
if($rst = mssql_fetch_array($stmt)) $usuvb = $rst["strAutoriza"];
mssql_free_result($stmt);

$stmt = mssql_query("EXEC Bodega..sp_getDatosUsuario 'GNR', NULL, '$usuvb'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$firmavb = $rst["firma"];
	$nombvb = $rst["nombre"];
}
mssql_free_result($stmt);

$despachar_en = '';
if($despachar == 1){
	$stmt = mssql_query("SELECT strDireccion FROM General..Contrato WHERE strCodigo='$strCargo'", $cnx);
	if($rst = mssql_fetch_array($stmt)) $despachar_en = $rst["strDireccion"];
	mssql_free_result($stmt);
}
$sql = "Select replace(replace(replace(strPrint,'<br>','\n'),'<b>',''),'</b>','') as strPrint, strLogo From General..Contrato Where strCodigo='$strCargo'";
$stmt = mssql_query($sql, $cnx);
if($rst = mssql_fetch_array($stmt)){
	$encabezado = $rst["strPrint"];
	$logo = $rst["strLogo"];
}
mssql_free_result($stmt);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 9);

$sw = 1; $fil = 38;
$stmt = mssql_query("EXEC Bodega..sp_getDetalleOrdenCompra 0, $numero", $cnx);
while($rst = mssql_fetch_array($stmt)){
	$fil++;
	$cont++;
	if($fil == 39){
		$pdf->Image('../images/'.$logo,5, 3);
		$pdf->Text(10, 37, "[Interno: $numero]");
		
		$pdf->SetFont('', 'B', 20);
		$pdf->Text(85, 10, ($tipodoc == 'I' ? 'Orden Interna' : 'Orden de Compra'));
		if($estado == 5){ 
			$pdf->Text(100, 17, 'NULA');
		}
		
		$pdf->SetFont('', '', 8);
		$pdf->setXY(170,7);
		$pdf->MultiCell(40,3,$encabezado,0,'C');		
		$pdf->SetFont('', 'B', 9);
		$pdf->Text(10, 40, 'OC. Nº       :');
		$pdf->Text(90, 40, 'Forma de Pago:');
		$pdf->Text(170, 40, 'Fecha:');
		
		$pdf->Text(10, 44, 'Proveedor:');
		$pdf->Text(170, 44, 'R.U.T.:');
		
		$pdf->Text(10, 48, 'Dirección  :');
		
		$pdf->Text(10, 52, 'Comuna      :');
		$pdf->Text(90, 52, 'Teléfono:');
		$pdf->Text(170, 52, 'Fax:');
		
		$pdf->Text(10, 56, 'Nota            :');
		
		$pdf->Text(10, 65, 'Contrato     :');
		$pdf->Text(110, 65, 'Cargo:');
		
		$pdf->Text(10, 69, 'Contacto    :');
		
		$pdf->SetFont('', '');
		$pdf->Text(30, 40, $numusu);
		$pdf->Text(115, 40, $forma_pago);
		$pdf->Text(181, 40, $fecha);
		
		$pdf->Text(30, 44, $proveedor);
		$pdf->Text(181, 44, $rut);
		
		$pdf->Text(30, 48, $direccion);
		
		$pdf->Text(30, 52, $comuna);
		$pdf->Text(105, 52, $telefono);
		$pdf->Text(177, 52, $fax);
		
		
		$fil = 56;
		$lrg = strlen($nota);
		for($i = 0; $i <= $lrg; $i += 120){
			$pdf->Text(30, $fil, trim(substr(strtolower($nota), $i, 120)));
			$fil+=3;
		}
		
		$pdf->Text(30, 65, $contrato);
		$pdf->Text(121, 65, $cargo);
		
		$pdf->Text(30, 69, $contacto);
		
		$pdf->SetFont('', 'B');
		$pdf->setXY(10, 75);
		$pdf->Cell(5, 4, 'Nº', 0, 0, 'C');
		$pdf->setXY(15, 75);
		$pdf->Cell(15, 4, 'Código', 0, 0, 'C');
		$pdf->setXY(30, 75);
		if($tipodoc=='O' || $tipodoc == 'I'){
			$pdf->Cell(55, 4, 'Descripción', 0, 0, 'L');
			$pdf->setXY(85, 75);
			$pdf->Cell(15, 4, 'Unidad', 0, 0, 'C');
			$pdf->setXY(100, 75);
			$pdf->Cell(20, 4, 'F.Inicio', 0, 0, 'C');
			$pdf->setXY(120, 75);
			$pdf->Cell(20, 4, 'F.Término', 0, 0, 'C');
		}else{
			$pdf->Cell(95, 4, 'Descripción', 0, 0, 'L');
			$pdf->setXY(125, 75);
			$pdf->Cell(15, 4, 'Unidad', 0, 0, 'C');
		}
		$pdf->setXY(140, 75);
		$pdf->Cell(20, 4, 'Cantidad', 0, 0, 'R');
		$pdf->setXY(160, 75);
		$pdf->Cell(20, 4, 'Valor', 0, 0, 'R');
		$pdf->setXY(180, 75);
		$pdf->Cell(20, 4, 'Total', 0, 0, 'R');
		$pdf->Line(10, 80, 200, 80);
		$pdf->SetFont('', '');
		$linea = 78;
	}
	$linea += 3;
	$pdf->setXY(10, $linea);
	$pdf->Cell(5, 4, $cont, 0, 0, 'C');
	$pdf->setXY(15, $linea);
	$pdf->Cell(15, 4, $rst["strCodigo"], 0, 0, 'C');
	$pdf->setXY(30, $linea);
	if($tipodoc=='O' || $tipodoc == 'I'){
		$pdf->Cell(55, 4, $rst["strDescripcion"], 0, 0, 'L');
		$pdf->setXY(85, $linea);
		$pdf->Cell(15, 4, trim($rst["strUnidad"]), 0, 0, 'C');
		$pdf->setXY(100, $linea);
		$pdf->Cell(20, 4, $rst["dtmFchIni"], 0, 0, 'C');
		$pdf->setXY(120, $linea);
		$pdf->Cell(20, 4, $rst["dtmFchTer"], 0, 0, 'C');
	}else{
		$pdf->Cell(95, 4, $rst["strDescripcion"], 0, 0, 'L');
		$pdf->setXY(125, $linea);
		$pdf->Cell(15, 4, trim($rst["strUnidad"]), 0, 0, 'C');
	}
	$pdf->setXY(140, $linea);
	$pdf->Cell(20, 4, number_format($rst["dblCantidad"], 2, ',', '.'), 0, 0, 'R');
	$pdf->setXY(160, $linea);
	$pdf->Cell(20, 4, number_format($rst["dblValor"], 0, ',', '.'), 0, 0, 'R');
	$pdf->setXY(180, $linea);
	$pdf->Cell(20, 4, number_format($rst["dblCantidad"] * $rst["dblValor"], 0, ',', '.'), 0, 0, 'R');
	$neto += $rst["dblCantidad"] * $rst["dblValor"];
}
$linea += 4;
$pdf->Line(10, $linea, 200, $linea);

$pdf->SetFont('', 'B');
$linea += 4;
$pdf->setXY(145, $linea);
$pdf->Cell(35, 4, ($docpago == 2 ? 'A PAGAR' : 'NETO').':', 0, 0, 'R');
$pdf->SetFont('', '');
$pdf->setXY(180, $linea);
$pdf->Cell(20, 4, number_format($neto, 0, '', '.'), 0, 0, 'R');
$linea += 4;
$pdf->SetFont('', 'B');
$pdf->setXY(145, $linea);
$pdf->Cell(35, 4, ($docpago == 2 ? '(-)Impuesto 10%' : 'I.V.A.').':', 0, 0, 'R');
$pdf->SetFont('', '');
$pdf->setXY(180, $linea);
$pdf->Cell(20, 4, number_format($neto * $factor, 0, '', '.'), 0, 0, 'R');
$linea += 4;
$pdf->SetFont('', 'B');
$pdf->setXY(145, $linea);
$pdf->Cell(35, 4, 'TOTAL'.($docpago == 2 ? ' HONORARIOS' : '').':', 0, 0, 'R');
$pdf->SetFont('', '');
$pdf->setXY(180, $linea);
$pdf->Cell(20, 4, number_format($neto * ($factor + 1), 0, '', '.'), 0, 0, 'R');
$linea += 4;
$pdf->Line(10, $linea, 200, $linea);
$linea += 4;

if($tipodoc == 'O' && $firmavb != '') $pdf->Image('../images/'.$firmavb, 50, $linea, 0, 0, 'GIF');
if($firma != '') $pdf->Image('../images/'.$firma, 90, $linea, 0, 0, 'GIF');
if($firmagnte != '' && $tipodoc != 'I') $pdf->Image('../images/'.$firmagnte, 130, $linea, 0, 0, 'GIF');

$linea += 27;
$pdf->setXY(50, $linea);
$pdf->Cell(30, 4, 'VºBº', 0, 0, 'C');
$pdf->setX(90);
$pdf->Cell(30, 4, $nombsol, 0, 0, 'C');
$pdf->setX(130);
$pdf->Cell(30, 4, ($nombgnte != '' ? $nombgnte : 'No existe registro de firma digital'), 0, 0, 'C');

mssql_free_result($stmt);
$linea += 4;
if($tipodoc == 'O') $tipo = 'pieoco'; else $tipo = 'pieoca';
$stmt = mssql_query("SELECT strDetalle FROM General..Tablon WHERE strTabla='$tipo' ORDER BY strCodigo", $cnx);
while($rst = mssql_fetch_array($stmt)){	
	$linea += 4;
	$lrg = strlen($rst["strDetalle"]);
	for($i = 0; $i <= $lrg; $i += 121){
		$pdf->Text(10, $linea, trim(substr(ReemplazaInv($rst["strDetalle"]), $i, 121)));
		$linea += 3;
	}
}
mssql_free_result($stmt);

mssql_close($cnx);
$pdf->Output();
?>
