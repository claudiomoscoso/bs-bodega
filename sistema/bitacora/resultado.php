<?php
include '../conexion.inc.php';

$contrato = $_GET["contrato"];
$usuario = $_GET["usuario"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bitacora</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
<!--
function Load(){
	parent.Deshabilita(false);
}

function Selecciona(fila){
	var totfil = document.getElementById('totfil').value;
	for(i = 1; i <= totfil; i++){
		if(eval(i) != eval(fila) && document.getElementById('chkElige' + i).checked){
			document.getElementById('chkElige' + i).checked = false;
			break;
		}
	}
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load()">
<table border="0" width="100%" cellpadding="0" cellspacing="1">
<?php
$stmt = mssql_query("EXEC Orden..sp_getBitacora 0, '$contrato', 'NULL', '$usuario'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'" >';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="10%" align="center"><input name="txtMovil'.$cont.'" id="txtMovil'.$cont.'" class="txt-sborde" style="width:99%;text-align:center;background-color:'.($cont % 2 == 0 ? '#EBF1FF' : '#FFFFFF').'" readonly="true" value="'.trim($rst["strMovil"]).'"></td>';
		echo '<td width="55%" >&nbsp;'.$rst["strNombre"].'</td>';
		echo '<td width="10%" align="center">'.number_format($rst["dblTPendiente"], 0, '', '.').'</td>';
		echo '<td width="10%" align="center">'.$rst["dtmFPendiente"].'</td>';
		echo '<td width="10%" align="center">';
		echo '<input type="checkbox" name="chkElige'.$cont.'" id="chkElige'.$cont.'"';
		echo 'onclick="javascript: Selecciona('.$cont.')"';
		echo '>';
		echo '</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else{
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n.</b></td></tr>';
}
mssql_free_result($stmt);
?>
</table>
<input type="hidden" name="totfil" id="totfil" value="<?php echo $cont;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
