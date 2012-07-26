<?php
include '../conexion.inc.php';

$usuario=$_POST["usuario"];
$bodega=$_POST["bodega"];
$numero=$_POST["numero"];
$tdocumento=$_POST["tdoc"];
$ndocumento=$_POST["ref"];
$numoc=$_POST["numoc"];
$observacion=$_POST["obs"];
$detalle=$_POST["detalle"];
mssql_query("EXEC sp_setAgregaGuiaIngreso 'GI', '$usuario', '$bodega', $numero, '".date('m/d/Y')."', $tdocumento, '$ndocumento', $numoc, '$detalle'", $cnx);
?>
<script language="javascript">
<!--
parent.Deshabilita(false);
parent.parent.document.getElementById('frmGuiaIngreso').src='../blank.html';
parent.parent.document.getElementById('txtNumGI').value=''
-->
</script>
<?php
mssql_close($cnx);
?>