<?php
include '../conexion.inc.php';

$bodega = $_GET["bodega"];
$texto = $_GET["texto"];
echo '<table border="0" width="100%" cellpadding="0" cellspacing="1">';
$stmt = mssql_query("EXEC sp_getMateriales '$texto', NULL, NULL, '$bodega', 'BSC'", $cnx);
if($rst = mssql_fetch_array($stmt)){
	do{
		$cont++;
		echo '<tr bgcolor="'.($cont % 2 == 0 ? '#FFFFFF' : '#EBF3FE').'">';
		echo '<td width="3%" align="center">'.$cont.'</td>';
		echo '<td width="10%" align="center">'.trim($rst["strCodigo"]).'</td>';
		echo '<td width="40%" align="left">&nbsp;'.htmlentities($rst["strDescripcion"]).'</td>';
		echo '<td width="25%" align="left">&nbsp;'.htmlentities($rst["strFamilia"]).'</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblEntradas"], 2, ',', '.').'&nbsp;</td>';
		echo '<td width="10%" align="right">'.number_format($rst["dblSalidas"], 2, ',', '.').'&nbsp;</td>';
		echo '</tr>';
	}while($rst = mssql_fetch_array($stmt));
}else
	echo '<tr><td align="center" style="color:#FF0000"><b>No se ha encontrado informaci&oacute;n</b></td></tr>';
echo '</table>';
mssql_free_result($stmt);
mssql_close($cnx);
?>