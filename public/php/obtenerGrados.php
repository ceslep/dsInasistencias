<?php
    require("headers.php");
    require("datosConexion.php");
    $dbconn = pg_connect($conectionString);
    $icolegio=$_REQUEST['icolegio'];
    $sql="select distinct igrado from estudiantes where icolegio='$icolegio' order by igrado";
    $result = pg_query($dbconn, $sql);
    $datos=[];
    if($result)
    while($dato=pg_fetch_assoc($result)) {
        $datos[]=$dato;
    }
    echo json_encode($datos);
    pg_close($dbconn);
?>