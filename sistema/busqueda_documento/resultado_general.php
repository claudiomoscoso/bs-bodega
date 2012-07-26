<?php
include '../conexion.inc.php';

$bodega=$_GET["bodega"];
$tipodoc=$_GET["tipodoc"]; 
$mes=$_GET["mes"];
$ano=$_GET["ano"];
$material=$_GET["material"] != '' ? "'".$_GET["material"]."'" : 'NULL';
$proveedor=$_GET["proveedor"] != '' ? "'".$_GET["proveedor"]."'" : 'NULL'; 
$tbusqueda=$_GET["tbusqueda"] != '' ? $_GET["tbusqueda"] : 'NULL'; 
$observacion=$_GET["observacion"] != '' ? "'".$_GET["observacion"]."'" : 'NULL';
$numero=$_GET["numero"] != '' ? $_GET["numero"] : 'NULL';

if($observacion != "NULL" && $tbusqueda == 0 && ($tipodoc == 0 || $tipodoc == 1 || $tipodoc == 6))
	$sql= "EXEC Bodega..sp_getOrdenCompra 'NUM', $observacion, '%', '$bodega'";
elseif($observacion != "NULL" && $tbusqueda == 1 && $tipodoc == 6)
	$sql= "EXEC Bodega..sp_getGuiaIngreso 'NUM', $observacion,  '$bodega'";

if($sql != ''){
	$stmt = mssql_query($sql, $cnx);
	if($rst=mssql_fetch_array($stmt)) $observacion=$rst["dblNumero"];
	mssql_free_result($stmt);
}
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
	parent.Deshabilita(false);
}

function MuestraDocumento(numero){
	var url = ''
	var mes = parent.document.getElementById('cmbMes').value;
	var ano = parent.document.getElementById('cmbAno').value;
	switch(parseInt('<?php echo $tipodoc;?>')){
		case 0:
			url = 'imprime_sm.php';
			break;
		case 1:
			url = 'imprime_oc.php';
			break;
		case 2:
			url = 'imprime_ing.php';
			break;
		case 3:
			url = 'imprime_desp.php';
			break;
		case 4:
			url = 'imprime_dev.php';
			break;
		case 5:
			url = 'imprime_vc.php';
			break;
		case 6:
			url = 'imprime_fact.php';
			break;
		case 7:
			url = 'imprime_cc.php';
			break;
		case 8:
			url = 'imprime_gcargo.php';
			break;
		case 9:
			url = 'imprime_gdevcargo.php';
			break;
		case 10:
			url = 'imprime_tb.php';
			break;
		case 11:
			url = 'imprime_factint.php';
			break;
	}
	AbreDialogo('divDocumento', 'frmDocumento', url + '?bodega=<?php echo $bodega;?>&numero=' + numero + '&mes=' + mes + '&ano=' + ano, true);
}
-->
</script>
<body marginheight="1px" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$col = 0;

$stmt = mssql_query("EXEC Bodega..sp_getBuscaDocumento '$bodega', $tipodoc, $mes, '$ano', $material, $proveedor, $observacion, $numero, $tbusqueda", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$col++;
		if($col==1){
			echo '<tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
		}elseif($col>10){
			$cont++;
			$col=1;
			echo '</tr><tr bgcolor="'.($cont % 2==0 ? '#FFFFFF' : '#EBF3FE').'">';
		}
		echo '<td width="10%" align="center">
			     <a href="#" '.($rst["strEstado"]==4 || $rst["strEstado"]==5 ? 'style="color:#FF0000"' : '').' title="Ver documento Nº '.$rst["dblNum"].'..."
				    onclick="javascript: 
						parent.Deshabilita(true);
						MuestraDocumento(\''.$rst["dblNumero"].'\');
					"
				    onmouseover="javascript: window.status=\'Ver documento Nº '.$rst["dblNum"].'...\'; return true;"
				 >'.$rst["dblNum"].'</a></td>';
	}while($rst=mssql_fetch_array($stmt));
	echo '</tr>';
}else{
	echo '<tr><td align="center" style="color: #FF0000"><b>No se han encontrado documentos.</b></td></tr>';
}
mssql_free_result($stmt);?>
</table>
<input type="hidden" name="resultado" id="resultado" value="R" />
</body>
</html>
<?php
mssql_close($cnx);
?>
