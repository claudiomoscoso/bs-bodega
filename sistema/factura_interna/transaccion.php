<?php
include '../conexion.inc.php';

$modulo = $_GET["modulo"];
$usuario = $_GET["usuario"];
$numero = $_GET["numero"];
$codigo = $_GET["codigo"];
$sw = $_GET["sw"];
switch($modulo){
	case 0:
		mssql_query("EXEC Bodega..sp_setTMPFacturaInterna $modulo, '$usuario', NULL, NULL, $sw", $cnx);
		break;
	case 1:
		mssql_query("EXEC Bodega..sp_setTMPFacturaInterna $modulo, '$usuario', $numero, '$codigo', $sw", $cnx);
		break;
	case 2:
		mssql_query("DELETE FROM Bodega..TMPFacturaInterna WHERE dblModulo = 0 AND strUsuario = '$usuario' AND dblSeleccion = 0", $cnx);
		echo '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
		$stmt = mssql_query("Bodega..sp_getTMPFacturaInterna 1, '$usuario'", $cnx);
		if($rst = mssql_fetch_array($stmt)){
			do{
				$cont++;
				echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
				echo '<td width="3%" align="center">'.$cont.'</td>';
				echo '<td width="10%" >';
				echo '<input type="hidden" name="hdnNumero'.$cont.'" id="hdnNumero'.$cont.'" value="'.$rst["dblNumero"].'">';
				echo '<input name="txtCodigo'.$cont.'" id="txtCodigo'.$cont.'" class="txt-sborde" style="width:99%;text-align:center;background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="'.$rst["strCodigo"].'">';
				echo '</td>';
				echo '<td width="25%" >&nbsp;'.$rst["strDescripcion"].'</td>';
				echo '<td width="10%" align="center">'.$rst["dtmFInicio"].'</td>';
				echo '<td width="10%" align="center">'.$rst["dtmFTermino"].'</td>';
				echo '<td width="10%" align="center">'.$rst["strCCosto"].'</td>';
				echo '<td width="10%" >';
				echo '<input type="hidden" name="hdnCantidad'.$cont.'" id="hdnCantidad'.$cont.'" value="'.number_format($rst["dblCantidad"], 2, '.', '').'" >';
				echo '<input name="txtCantidad'.$cont.'" id="txtCantidad'.$cont.'" class="txt-plano" style="width:99%; text-align:right" value="'.number_format($rst["dblCantidad"], 2, '.', '').'" ';
				echo 'onblur="javascript: Blur(this, \''.$cont.'\')"';
				echo 'onfocus="javascript: CambiaColor(this.id, true);"';
				echo 'onkeypress="javascript: return KeyPress(event, this);"';
				echo 'onkeyup="javascript: KeyUp(this, \''.$cont.'\')"';
				echo '>';
				echo '</td>';
				echo '<td width="10%" align="right">';
				echo '<input type="hidden" name="hdnPrecio'.$cont.'" id="hdnPrecio'.$cont.'" value="'.number_format($rst["dblPrecio"], 0, '', '').'" >';
				echo '<input name="txtPrecio'.$cont.'" id="txtPrecio'.$cont.'" class="txt-plano" style="width:99%; text-align:right" value="'.number_format($rst["dblPrecio"], 0, '', '').'" ';
				echo 'onblur="javascript: Blur(this, \''.$cont.'\')"';
				echo 'onfocus="javascript: CambiaColor(this.id, true);"';
				echo 'onkeypress="javascript: return KeyPress(event, this);"';
				echo 'onkeyup="javascript: KeyUp(this, \''.$cont.'\')"';
				echo '>';
				echo '</td>';
				echo '<td width="10%"><input name="txtTotal'.$cont.'" id="txtTotal'.$cont.'" class="txt-sborde" style="width:99%;text-align:right;background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="'.number_format($rst["dblTotal"], 0, '', '').'"></td>';
				echo '</tr>';
				$total += $rst["dblTotal"];
			}while($rst = mssql_fetch_array($stmt));
		}else
			echo '';
		mssql_free_result($stmt);
		echo '</table>';
		echo '<input type="hidden" name="totfil" id="totfil" value="'.$cont.'">';
		echo '<input type="hidden" name="hdnTotal" id="hdnTotal" value="'.number_format($total, 0, '', '.').'">';
		break;
	case 3:
		$cantidad = $_GET["cantidad"];
		$precio = $_GET["precio"];
		mssql_query("EXEC Bodega..sp_setTMPFacturaInterna 2, '$usuario', $numero, '$codigo', NULL, $cantidad, $precio", $cnx);
		break;
}
mssql_close($cnx);
?>