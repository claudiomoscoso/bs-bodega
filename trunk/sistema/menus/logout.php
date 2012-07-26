<?php 
include 'autentica.php';
include 'conexion.inc.php';
mssql_query("UPDATE General..Usuarios SET login=NULL WHERE usuario='$usuario'", $cnx);
?>
<script language="javascript">
<!--
top.location.href='home.php';
-->
</script>
<?php
mssql_close($cnx);
?>