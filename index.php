<?php

require_once 'controlador/controlador.php';
$controlador = new controlador();
$ruta = $_SERVER["REQUEST_URI"];

if (strpos($ruta, "/api") === 0) {
    require_once "controlador/apiController.php";
    // Eliminar '/api' de la ruta antes de pasarla al controlador de la API
    $ruta_api = str_replace("/api", "", $ruta);
    $api = new ApiController($ruta_api);
} elseif ($_GET && isset($_GET['accion'])) {
    $accion = filter_input(INPUT_GET, "accion", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (method_exists($controlador,$accion)) {
        $controlador->$accion();
    } else {
        $controlador->index();
    }

} else {
    $controlador->index();
}
?>
