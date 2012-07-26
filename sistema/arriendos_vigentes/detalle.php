<?php
include '../conexion.inc.php';

$obra = $_GET["obra"];
$contmes = $_GET["cont"];
$numero = $_GET["numero"];
$usuario=$_GET["usuario"];
if($numero != '') mssql_query("EXEC Bodega..sp_CambiaEstado 'OCV', $numero, 16, '$usuario'", $cnx);

if($contmes == 0)
	$fchact = array(date('m'), date('Y'));
else{
	$fchact = array(date('m') + $contmes, date('Y'));
	if($fchact[0] > 12){
		$fchact[0] = '01';
		$fchact[1]++;
	}
}
$fchact[0] = strlen(trim($fchact[0])) == 1 ? '0'.$fchact[0] : $fchact[0]; 

$fchant[] = $fchact[0] - 1;
$fchant[] = $fchact[1];

if($fchant[0] < 1){
	$fchant[0] = 12;
	$fchant[1]--;
}
$fchant[0] = strlen(trim($fchant[0])) == 1 ? '0'.$fchant[0] : $fchant[0];
$inimes_p1 = '01/'.$fchant[0].'/'.$fchant[1];
$finmes_p1 = UltimoDiaMes($fchant[0], $fchant[1], 0);
$ultdia_p1 = split('/', $finmes_p1);

$inimes_p2 = '01/'.$fchact[0].'/'.$fchact[1];
$finmes_p2 = UltimoDiaMes($fchact[0], $fchact[1], 0);
$ultdia_p2 = split('/', $finmes_p2);

$bloquea = 1;
$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'UAV', NULL, '%', '$obra'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	$inimin = split('/', $rst["dtmFchDsd"]);
	$finmax = split('/', $rst["dtmFchHst"]);
	$bloquea = 0;
}
mssql_free_result($stmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Arriendos Vigentes</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
var Intervalo = 0;
parent.document.getElementById('panel1').value='<?php echo Mes($fchant[0]).' de '.$fchant[1];?>';
parent.document.getElementById('panel2').value='<?php echo Mes($fchact[0]).' de '.$fchact[1];?>';

if((parseInt('<?php echo $fchant[1].$fchant[0];?>') <= parseInt('<?php echo $inimin[2].$inimin[1];?>')) || parseInt('<?php echo $bloquea;?>') == 1)
	parent.document.getElementById('btnAnt').disabled = true;
else
	parent.document.getElementById('btnAnt').disabled = false;

if((parseInt('<?php echo $fchact[1].$fchact[0];?>') == parseInt('<?php echo $finmax[2].$finmax[1];?>')) || parseInt('<?php echo $bloquea;?>') == 1)
	parent.document.getElementById('btnSgte').disabled = true;
else
	parent.document.getElementById('btnSgte').disabled = false;

function Load(){
	parent.Deshabilita(false);
}

function Explora(sw){
	var cont = parseInt('<?php echo $contmes;?>');
	if(sw == 'S') cont++; else cont--;
	self.location.href = 'detalle.php?obra=<?php echo trim($obra);?>&cont='+cont;
}
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<?php
$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'AV', NULL, '%', '$obra'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		$fchdsd = split('/', $rst["dtmFchDsd"]);
		$fchhst = split('/', $rst["dtmFchHst"]);
		echo '<td width="1%" align="center">'.$cont.'</td>';
		for($dia=1; $dia<=$ultdia_p1[0]; $dia++){
			$imagen = '';
			$sw = 0;
			
			$fchini = (strlen($dia) == 1 ? '0'.$dia : $dia).'/'.(strlen($fchant[0]) == 1 ? '0'.$fchant[0] : $fchant[0]).'/'.$fchant[1];
			$fchter = (strlen($dia) == 1 ? '0'.$dia : $dia).'/'.(strlen($fchant[0]) == 1 ? '0'.$fchant[0] : $fchant[0]).'/'.$fchant[1];
			if(ComparaFechas($fchini, 'entre', $rst["dtmFchDsd"], $rst["dtmFchHst"]) && ComparaFechas($fchini, 'entre', '01/'.$fchant[0].'/'.$fchant[1], $rst["dtmFchHst"]))
				$sw=1;
			
			if($sw == 1){
				if(ComparaFechas(SumarFecha(date('d/m/Y'), 3), '=', $rst["dtmFchHst"], '')){
					$imagen = '../images/p_amarilla.gif';
				}elseif(ComparaFechas($rst["dtmFchHst"], '<=', date('d/m/Y'), '')){
					$imagen = '../images/p_roja.gif';
				}else{
					$imagen = '../images/p_verde.gif';
				}
				echo '<td width="1%">';
				echo '<a href="#" ';
				echo 'onclick="javascript: ';
				echo 'parent.document.getElementById(\'hdnNumOC\').value='.$rst["dblNumero"].';';
				echo 'parent.Deshabilita(true);';
				echo 'AbreDialogo(\'divDetalle\',\'frmDetalle\',\'orden_compra.php?numero='.$rst["dblNumero"].'&codigo='.$rst["strCodigo"].'\', true);';
				echo '"';
				echo '><img border="0" width="100%" height="15px" src="'.$imagen.'" /></a>';
				echo '</td>';
			}else
				echo '<td width="1%" align="center"><img border="0" width="100%" height="15px" src="../images/p_blank.gif" /></td>';
		}
		echo '<td width="1%" align="center">&brvbar;</td>';
		
		for($dia=1; $dia<=$ultdia_p2[0]; $dia++){
			$imagen = '';
			$sw=0;
			
			$fchini = (strlen($dia) == 1 ? '0'.$dia : $dia).'/'.(strlen($fchdsd[1]) == 1 ? '0'.$fchdsd[1] : $fchdsd[1]).'/'.$fchdsd[2];
			$fchter = (strlen($dia) == 1 ? '0'.$dia : $dia).'/'.(strlen($fchhst[1]) == 1 ? '0'.$fchhst[1] : $fchhst[1]).'/'.$fchhst[2];
			if(ComparaFechas($fchini, 'entre', $rst["dtmFchDsd"], $rst["dtmFchHst"]) && ComparaFechas($fchini, 'entre', '01/'.$fchact[0].'/'.$fchact[1], $rst["dtmFchHst"]))
				$sw=1;
			if(ComparaFechas($fchter, 'entre', $inimes_p2, $finmes_p2) && ComparaFechas($fchter, 'entre', $rst["dtmFchDsd"], $rst["dtmFchHst"]))
				$sw=1;
			
			if($sw == 1){
				if(ComparaFechas(SumarFecha(date('d/m/Y'), 3), '=', $rst["dtmFchHst"], '')){
					$imagen = '../images/p_amarilla.gif';
				}elseif(ComparaFechas($rst["dtmFchHst"], '<=', date('d/m/Y'), '')){
					$imagen = '../images/p_roja.gif';
				}else{
					$imagen = '../images/p_verde.gif';
				}
				echo '<td width="1%">';
				echo '<a href="#" ';
				echo 'onclick="javascript: ';
				echo 'parent.document.getElementById(\'hdnNumOC\').value='.$rst["dblNumero"].';';
				echo 'parent.Deshabilita(true);';
				echo 'AbreDialogo(\'divDetalle\',\'frmDetalle\',\'orden_compra.php?numero='.$rst["dblNumero"].'&codigo='.$rst["strCodigo"].'\', true);';
				echo '"';
				echo '><img border="0" width="100%" height="15px" src="'.$imagen.'" /></a>';
				echo '</td>';
			}else
				echo '<td width="1%" align="center"><img border="0" width="100%" height="15px" src="../images/p_blank.gif" /></td>';
		}
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n</b></td></tr>';
}
mssql_free_result($stmt);
mssql_close($cnx);
?>
</table>
</body>
</html>