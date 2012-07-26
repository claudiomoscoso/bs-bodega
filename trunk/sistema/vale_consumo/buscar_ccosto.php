<?php
include '../conexion.inc.php';
$bodega=$_GET["bodega"];
$texto=$_GET["texto"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Centros de Costo</title>
</head>
<link rel="stylesheet" type="text/css" href="../images/style.css" />
<script language="javascript" src="../funciones.js"></script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
			<select name="lista" id="lista" class="sel-plano" style="width:100%;" size="10" 
				onkeypress="javascript: return KeyPress(event, this.selectedIndex);"
			>
			<?php
			$stmt = mssql_query("SELECT strCodigo, strDescripcion, strUnidad, strBodega FROM General..Partida WHERE strBodega='$bodega' AND (strCodigo LIKE '$texto%' OR strDescripcion LIKE '$texto%') ORDER BY strCodigo",$cnx);
			if($rst=mssql_fetch_array($stmt)){
				do{
					$arrDatos.="'".trim($rst["strCodigo"]).'&&&'.trim($rst["strDescripcion"]).'&&&'.trim($rst["strUnidad"]).'&&&'.trim($rst["strBodega"])."',";
					if(trim($rst["strUnidad"]) != ''){
						$optValue = htmlentities($rst["strCodigo"]);
						$optText = str_repeat('&nbsp;', 3).htmlentities($rst["strDescripcion"]);
					}else{
						$optValue = 'none';
						$optText = htmlentities($rst["strDescripcion"]);
					}
					echo '<option value="'.$optValue.'">'.$optText.'</option>';
				}while($rst=mssql_fetch_array($stmt));
				$arrDatos=substr($arrDatos,0,-1);
			}else{
				echo '<option value="none">C&oacute;digo o Descripci&oacute;n inexistente</option>';
			}
			mssql_free_result($stmt);?>
			</select>
		</td>
	</tr>
</table>
</body>
</html>
<script language="javascript">
<!--
var arrDatos=new Array(<?php echo $arrDatos;?>);

function KeyPress(evento, ind){
	var tecla = getCodigoTecla(evento);
	if(tecla == 27){
		parent.Deshabilita(false);
		parent.CierraDialogo('divCCosto','frmCCosto');
		parent.document.getElementById('txtCCosto').focus();
	}else if(tecla == 13){
		parent.Deshabilita(false);
		parent.CierraDialogo('divCCosto','frmCCosto');
		if(document.getElementById('lista').value != 'none' && document.getElementById('lista').value != ''){
			var arrPaso=arrDatos[ind].split('&&&');
			parent.document.getElementById('txtCCosto').value = arrPaso[0];
			parent.document.getElementById('hdnCCosto').value = arrPaso[0];
			parent.document.getElementById('txtCantidad').focus();
		}else{
			parent.document.getElementById('txtCCosto').value = '';
			parent.document.getElementById('hdnCCosto').value = '';
			parent.document.getElementById('txtCCosto').focus();
			parent.document.getElementById('txtCCosto').select();
		}
	}
}
document.getElementById('lista').focus();
document.getElementById('lista').selectedIndex = 0;
-->
</script>
<?php
mssql_close($cnx);
?>