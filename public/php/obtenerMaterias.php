<?php
    require("headers.php");
    require("datosConexion.php");
    $datos=(Object)json_decode(file_get_contents("php://input"));
    $dbconn = pg_connect($conectionString);
    $icolegio=$datos->icolegio;
    $codigo=$datos->codigo;
    $sql="select distinct imateria from notas"; 
    $sql.=" where 1=1";
    $sql.=" and icolegio='$icolegio'";
    $sql.=" and codigo='$codigo'";
    $sql.=" order by imateria";
    $result = pg_query($dbconn, $sql);
    $datos=[];
    if($result)
    while($dato=pg_fetch_assoc($result)) {
        $datos[]=$dato;
    }
    echo json_encode($datos);
    pg_close($dbconn);
?>