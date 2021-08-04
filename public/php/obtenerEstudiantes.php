<?php
    require("headers.php");
    require("datosConexion.php");
    $datos=(Object)json_decode(file_get_contents("php://input"));
    $dbconn = pg_connect($conectionString);
    $icolegio=$datos->icolegio;
    $igrado=$datos->igrado;
    $igrupo=$datos->igrupo;
    $sql="select estudiantes from estudiantes"; 
    $sql.=" where 1=1";
    $sql.=" and icolegio='$icolegio'";
    $sql.=" and igrado='$igrado'";
    $sql.=" and igrupo='$igrupo'";
    $sql.=" order by nestudiante";
    $result = pg_query($dbconn, $sql);
    $datos=[];
    if($result)
    while($dato=pg_fetch_assoc($result)) {
        $datos[]=$dato;
    }
    echo json_encode($datos);
    pg_close($dbconn);
?>