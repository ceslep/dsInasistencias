<?php
    require("headers.php");
    require("datosConexion.php");
    $datos=(Object)json_decode(file_get_contents("php://input"));
    $dbconn = pg_connect($conectionString);
    $codigo=$datos->codigo;
    $sql="select imateria,periodo,sum(horas) as horas from inasistencias";
    $sql.=" where codigo='$codigo'";
    $sql.=" group by codigo,imateria,periodo,fecha";
    $sql.=" order by fecha";
    $result = pg_query($dbconn, $sql);
    $datos=[];
    if($result)
    while($dato=pg_fetch_assoc($result)) $datos[]=$dato;
    $sql="select * from inasistencias";
    $sql.=" where codigo='$codigo'";
    $sql.=" order by fecha";
    $result = pg_query($dbconn, $sql);
    $inasistencias=[];
    if($result)
    while($inasistencia=pg_fetch_assoc($result)) $inasistencias[]=$inasistencia;
    $datos=array("resumen"=>$datos,"totalInasistencias"=>$inasistencias);
    echo json_encode($datos);
    pg_close($dbconn);