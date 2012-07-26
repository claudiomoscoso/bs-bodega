<?php
include '../conexion.inc.php';

$usuario = $_GET["usuario"];
$perfil = $_GET["perfil"];
echo '<table border="0" width="100%" align="center" cellpadding="0" cellspacing="1">';
$stmt = mssql_query("EXEC Bodega..sp_getOrdenCompra 'OCI', NULL, '%', NULL, NULL, NULL, NULL, NULL, NULL,  '$usuario', $perfil'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="20%" >';
		echo '<input type="hidden" name="hdnCargo'.$cont.'" id="hdnCargo'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strDescCargo"])).'" />';
		echo '<input name="txtCargo'.$cont.'" id="txtCargo'.$cont.'" class="txt-sborde" style="width:99%;background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.htmlentities($rst["strDescCargo"]).'"';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnCargo$cont\')', 250);";
		echo '"';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnCargo$cont');";
		echo '"';
		echo '>';
		echo '</td>';
		echo '<td width="10%" align="center">';
		echo '<a href="#" title="O.Compra Interna N&deg; '.$rst["dblUltima"].'"';
		echo 'onclick="javascript: ';
		echo 'Deshabilita(true);';
		echo 'AbreDialogo(\'divOCompra\', \'frmOCompra\', \'detalle_orden_compra.php?numero='.$rst["dblNumero"].'\'); "';
		echo 'onmouseover="javascript: ';
		echo "window.status = 'O.Compra Interna N&deg; ".$rst["dblUltima"]."'; ";
		echo 'return true;';
		echo '"';
		echo '>'.$rst["dblUltima"].'</a>';
		echo '</td>';
		echo '<td width="10%" align="center">'.$rst["dtmFch"].'</td>';
		echo '<td width="38%" >';
		echo '<input type="hidden" name="hdnObservacion'.$cont.'" id="hdnObservacion'.$cont.'" value="&nbsp;'.htmlentities(trim($rst["strObservacion"])).'" />';
		echo '<input name="txtObservacion'.$cont.'" id="txtObservacion'.$cont.'" class="txt-sborde" style="width:99%;background-color:'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'" readonly="true" value="&nbsp;'.htmlentities(trim($rst["strObservacion"])).'"';
		echo 'onmouseover="javascript: ';
		echo 'clearInterval(Intervalo); ';
		echo "Intervalo = setInterval('MueveTexto(\''+this.id+'\', \'hdnObservacion$cont\')', 250);";
		echo '"';
		echo 'onmouseout="javascript: ';
		echo "DetieneTexto(Intervalo, this.id, 'hdnObservacion$cont');";
		echo '"';
		echo '>';
		echo '</td>';
		echo '<td width="15%" align="center">&nbsp;'.$rst["Estado"].'</td>';
		echo '<td width="2%" align="center">';
		echo '<input type="checkbox" name="chkMarca'.$cont.'" id="chkMarca'.$cont.'" value="'.$rst["dblNumero"].'" ';
		echo 'onclick="javascript: Seleccionar(this)"';
		echo '/>';
		echo '</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n</b></td></tr>';
mssql_free_result($stmt);
mssql_close($cnx);

echo '</table>';
echo '<input type="hidden" name="totfil" id="totfil" value="'.$cont.'" />';
?>