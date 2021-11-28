<?php
session_start();
include_once(__DIR__.'/../cnx/conexion.php');
include_once(__DIR__.'/../classes/pozo.php');
include_once(__DIR__.'/../classes/medicion.php');
date_default_timezone_set('America/Caracas'); # Convirtiendo la zona horaria a Caracas
$fmt_d = 'Y-m-d'; # Formato de fecha
$fmt_h = 'H:i:s'; # Formato de hora

$_SESSION['pozos'] = [];
$pozos = &$_SESSION['pozos']; # Array de pozos

# Crea un pozo a partir de los parametros ingresados.
function create_pozo($name,$profundidad,$mediciones=[]){
    global $con;
    $nlower = strtolower($name);
    $exists = check_existence('pozos','nombre',$name,TRUE);
    if($exists) return [FALSE,"Este pozo ya existe"];
    $query = make_query("INSERT INTO pozos (nombre,profundidad) VALUES ('$name','$profundidad')");
    if($query) return [TRUE,'Registrado con exito'];
    return [FALSE,"No se pudo registrar"];

}

# Crea una medición y la sube a la base de datos. Recibe la id del pozo como parámetro, el valor, y opcionalmente la fecha.
# Si el pozo con la ID no existe, retorna un array de tipo [FALSE,mensaje de error]
function create_medicion($id_pozo,$val,$fecha=FALSE){
    $query = make_query("SELECT * FROM pozos WHERE id_pozo=$id_pozo");
    if(!$query) return;
    if (mysqli_num_rows($query)<=0) return [FALSE,'No se ha encontrado el pozo'];
    global $fmt_d;
    global $fmt_h;
    if(!$fecha) $fecha = date("$fmt_d");
    $query = make_query("INSERT INTO mediciones (id_pozo,valor,fecha) VALUES ($id_pozo,$val,STR_TO_DATE('$fecha','%Y-%m-%d'))");
    if(!$query) return [FALSE,'No se ha podido registrar'];
    return [TRUE,'Registrado correctamente'];
}

# Realiza una petición a la base de datos.
function make_query($query){
    global $con;
    $rsQUERY = mysqli_query($con,$query) or die('Error: '.mysqli_error($con));
    return $rsQUERY;
}

# Añade un pozo al array general de objetos de tipo pozo.
function add_pozo($pozo){
    global $pozos;
    array_push($pozos,$pozo);
}

# Añade todos los pozos de la base de datos al array general de objetos de tipo pozo.
function read_pozos(){
    global $con;
    global $pozos;
    $pozos = [];
    $query = make_query("SELECT * FROM pozos");
    if(!$query) return;
    while($arr =  mysqli_fetch_array($query)){
        $pozo = new Pozo($arr['nombre'],floatval($arr['profundidad']));
        $pozo->id = $arr['id_pozo'];
        $resMed = read_mediciones($pozo->id);
        if($resMed) $pozo->mediciones = $resMed;
        add_pozo($pozo);
    }
}

# Consigue todas las mediciones del pozo introducido y las devuelve como un array de objetos de tipo Medicion.
function read_mediciones($id_pozo){
    $query = make_query("SELECT * FROM `mediciones` WHERE id_pozo=$id_pozo");
    if(!$query) return FALSE;
    if (mysqli_num_rows($query)<=0) return FALSE;
    return get_mediciones_from_query($query);
}

function get_mediciones_from_query($query){
    $mediciones = [];
    while($arr =  mysqli_fetch_array($query)) array_push($mediciones,new Medicion(floatval($arr['valor']),$arr['fecha'],intval($arr['id_medicion'])));
    return $mediciones;
}

# Revisa si existe un elemento con el valor introducido en la tabla y columna introducidas, puede o no ser insensible a mayusculas y minúsculas.
function check_existence($table,$column,$value,$sensitive=FALSE){
    global $con;
    $condition = $sensitive ? "LOWER($column)='".strtolower($value)."'" : "$column='$value'";
    $query = make_query("SELECT * FROM $table WHERE ".$condition);
    if(!$query) return FALSE;
    $countQuery = mysqli_num_rows($query);
    return $countQuery>0;
}

read_pozos();

?>  