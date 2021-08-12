<?php
    require("headers.php");
    require("datosConexion.php");
    $datos=(Object)json_decode(file_get_contents("php://input"));
    $dbconn = pg_connect($conectionString);
    $icolegio=$datos->icolegio;
    $igrado=$datos->igrado;
    $igrupo=$datos->igrupo;
    $sql="select imateria,sum(inasistencias.horas) as horas from inasistencias"; 
    $sql.=" inner join estudiantes on inasistencias.codigo=estudiantes.codigo";
    $sql.=" where 1=1";
    $sql.=" and icolegio='$icolegio'";
    $sql.=" and igrado='$igrado'";
    $sql.=" and igrupo='$igrupo'";
    $sql.=" group by  icolegio,igrado,igrupo,imateria";
    $sql.=" order by igrupo";
    $result = pg_query($dbconn, $sql);
    $datos=[];
    if($result)
    while($dato=pg_fetch_assoc($result)) {
        $datos[]=$dato;
    }
    echo json_encode($datos);
    pg_close($dbconn);