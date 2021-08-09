<?php
require("headers.php");
require("datosConexion.php");
$datos = (object)json_decode(file_get_contents("php://input"));
$dbconn = pg_connect($conectionString);
$id = $datos->id;
$sql = "delete from inasistencias where id='$id'";


$result = pg_query($dbconn, $sql);

if ($result) {
    $datos = array("estado" => "ok", "mensaje" => "eliminado correctamente");
    echo json_encode($datos);
}
pg_close($dbconn);
