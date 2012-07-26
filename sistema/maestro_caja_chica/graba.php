<?php
include '../conexion.inc.php';

$bodega = $_POST["bodega"];
$usuario = $_POST["usuario"];
$numero = $_POST["numero"];
$factor = $_POST["factor"];

$nota = $_POST["txtNota"];

mssql_query("EXEC Bodega..sp_setAgregaCajaChica 1, '$usuario', '$bodega', NULL, NULL, '$nota', NULL, $numero", $cnx);
?>
<script language="javascript">
<!--
parent.CierraDialogo('divCajaChica', 'frmCajaChica');
Deshabilitar(false);
parent.document.getElementById('btnGuardar').disabled=false;
-->
</script>
<?php
mssql_close($cnx);
?>