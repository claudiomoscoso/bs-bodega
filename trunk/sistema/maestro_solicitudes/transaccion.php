<?php
include '../conexion.inc.php';

$cargo = $_GET["cargo"];
$ocompra = $_GET["ocompra"];
$stmt = mssql_query("SELECT dblNumero FROM Bodega..CaratulaOC WHERE strCargo = '$cargo' AND dblUltima = $ocompra", $cnx);
if(!$rst = mssql_fetch_array($stmt)){?>
<script language="javascript">
<!--
 alert('La orden de compra ingresada no existe para este cargo.');
 parent.document.getElementById('txtOCompra').value = '';
-->
</script>
<?php
}
mssql_free_result($stmt);
mssql_close($cnx);
?>