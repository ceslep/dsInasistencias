<?php
require("headers.php");
require("datosConexion.php");
$datos = (object)json_decode(file_get_contents("php://input"));
$dbconn = pg_connect($conectionString);
$fields = "";
$fields = [];
$values = [];
foreach ($datos as $key => $value) {

    $fields[] = $key;
    if ($value == "")
        $values[] = sprintf("NULL", $value);
    else
        $values[] = sprintf("'%s'", $value);
}
$fields = implode(",", $fields);
$values = implode(",", $values);
$sql = sprintf("insert into inasistencias (%s) values (%s)", $fields, $values);

    set_error_handler("warning_handler", E_WARNING);
    $result = pg_query($dbconn, $sql);
    restore_error_handler();

if ($result){
    $datos = array("estado" => "ok", "mensaje" => "almacenado correctamente");
     echo json_encode($datos);
} 
pg_close($dbconn);

function warning_handler($errno, $errstr) { 
    $datos = array("estado" => "error", "mensaje" => "ha ocurrido un error", "Error" => $errno, "errorSql" => $errstr);
    echo json_encode($datos);
    }
