<?php
include '../autentica.php';
include '../conexion.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Opciones de Consulta</title>
<style>
a {text-decoration: none;}
a:hover {font-weight:bold;}
</style> 
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<script language="javascript" src="../ventanas.js"></script>
<script language="javascript">
<!--
var oculta=0;
setInterval("OcultaMenuAutomatico(oculta, 'tdListado', 'aListado', 'divListado')", 3000);

function Load(){
	AjustaMenu('frmListado', 'tdListado', document.getElementById('alto').value, document.getElementById('ancho').value);
}
-->
</script>
<body marginheight="0" marginwidth="0" onload="javascript: Load();">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<?php 
	$stmt = mssql_query("EXEC General..sp_getMenus '$usuario', 'L'", $cnx);
	while($rst = mssql_fetch_array($stmt)){
		if($clase != $rst["strClase"]){
			$clase = $rst["strClase"];
			switch($clase){
				case 'A':
					$menu = 'Adquisiciones';
					break;
				case 'B':
					$menu = 'Bodega';
					break;
				case 'C':
					$menu = 'Administraci&oacute;n';
					break;
				case 'E':
					$menu = 'Estado de Pago';
					break;
				case 'I':
					$menu = 'Inform&aacute;tica';
					break;
				case 'M':
					$menu = 'Control Ordenes';
					break;
				case 'O':
					$menu = 'Operaciones';
					break;
			}
			$height += 20;
			echo '<tr height="20px">';
			echo '<td width="20px" bgcolor="#8F5F4A">&nbsp;</td>';
			echo '<td>';
			echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
			echo '<tr>';
			echo '<td width="5%" nowrap style="font-weight:bold">&nbsp;'.$menu.'&nbsp;</td>';
			echo '<td><hr ></td>';
			echo '</tr>';
			echo '</table>';
			echo '</td>';
			echo '</tr>';
		}
		$opcion++;
		$height += 20;
		if($width < strlen($rst["strMenu"])) $width = strlen($rst["strMenu"]);
		echo '<tr height="20px">';
		echo '<td width="20px" bgcolor="#8F5F4A">&nbsp;</td>';
		echo '<td id="tdL'.$opcion.'" nowrap="nowrap">&nbsp;';
		echo '<a id="aL'.$opcion.'" href="#"';
		echo 'onclick="javascript: ';
		echo "CreaVentana('".$rst["strLink"].$parametros."', '".$rst["strMenu"]."', 98, 350, true);";
		echo "OcultaMenu('tdListado', 'aListado', 'divListado', true, true);";
		echo '"';
		echo 'onmouseover="javascript: ';
		echo 'oculta=0;';
		echo "MuestraMenu('tdL$opcion', 'aL$opcion');";
		echo '"';
		echo 'onmouseout="javascript: ';
		echo 'oculta=1;';
		echo "OcultaMenu('tdL$opcion', 'aL$opcion');";
		echo '"';
		echo '>'.$rst["strMenu"].'</a>';
		echo '</td>';
		echo '</tr>';
	}
	mssql_free_result($stmt);
	mssql_close($cnx);
	?>
	</table>
<input type="hidden" name="alto" id="alto" value="<?php echo $height;?>" />
<input type="hidden" name="ancho" id="ancho" value="<?php echo $width;?>" />
</body>
</html>
<?php
mssql_close($cnx);
?>
