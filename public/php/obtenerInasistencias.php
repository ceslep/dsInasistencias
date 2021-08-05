<?php
    require("headers.php");
    require("datosConexion.php");
    $datos=(Object)json_decode(file_get_contents("php://input"));
    $dbconn = pg_connect($conectionString);
    $codigo=$datos->codigo;
    $sql="select imateria,periodo,sum(horas) as horas from inasistencias";
    $sql.=" where codigo='$codigo'";
    $sql.=" group by codigo,imateria,periodo";
    $result = pg_query($dbconn, $sql);
    $datos=[];
    if($result)
    while($dato=pg_fetch_assoc($result)) {
        $datos[]=$dato;
    }
    echo json_encode($datos);
    pg_close($dbconn);