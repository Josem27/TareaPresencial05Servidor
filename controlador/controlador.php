<?php
require_once "modelo/Modelo.php";

class Controlador
{

    private $modelo;
    private $mensajes;

    public function __construct()
    {
        $this->modelo = new Modelo();
        $this->mensajes = [];
    }

    public function index()
    {
        $parametros = [
            "titulo" => "MVC"
        ];

        header("Location: index.php?accion=listado");
    }

    public function listado()
    {
        $parametros = [
            "titulo" => "Listado",
            "datos" => null,
            "mensaje" => null
        ];


        $resultModelo = $this->modelo->productos();
        if ($resultModelo) {
            $parametros['datos'] = $resultModelo;
        }
        include_once 'vistas/listado.php';
    }

    public function nuevaEntrada()
    {
        $parametros = [
            "titulo" => "Nuevo Producto"
        ];

        $errores = array();
        $imagen = null;
        if (isset($_POST['submit']) && !empty($_POST)) {
            if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) {
                $data = $this->modelo->get_image();
                $imagen = $data['imagen'];
                $errores = $data['error'];
            }
            if (!isset($errores)) {
                $datos = [
                    "nombre" => $_POST['nombre'],
                    "categoria" => $_POST['categoria'],
                    "pvp" => $_POST['pvp'],
                    "stock" => $_POST['stock'],
                    "imagen" => $imagen,
                    "observaciones" => $_POST['observaciones']
                ];
                $resultModelo = $this->modelo->new_Producto($datos);
            }

            if ($resultModelo['bool']) {
                header("Location: index.php?accion=listado&post=true");
            }
        } else {
            $resultModelo = $this->modelo->categorias();
            $parametros['categorias'] = $resultModelo['datos'];
            include_once 'vistas/nuevaEntrada.php';
        }
    }

    public function producto()
    {
        $parametros = [
            "titulo" => "Producto",
            "datos" => null,
            "mensaje" => null
        ];

        $datos = $_GET['id'];
        $resultModelo = $this->modelo->productoId($datos);
        if ($resultModelo['bool']) {
            $parametros['datos'] = $resultModelo['datos'];
            include_once 'vistas/verProducto.php';
        }
    }

    public function eliminar()
    {
        $resultModelo = $this->modelo->del_Producto($_GET['id']);
        header("Location: index.php?accion=listado");
    }

    public function editar()
    {
        $parametros = [
            "titulo" => "Editar",
            "datos" => null,
            "mensaje" => null
        ];
        $errores = array();
        $imagen = null;
        if (isset($_POST['submit']) && !empty($_POST)) {
            if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) {
                $data = $this->modelo->get_image();
                $imagen = $data['imagen'];
                $errores = $data['error'];
            }
            if (isset($_POST['estado']) && $_POST['estado'] == 'on') {
                $estado = 1;
            } else {
                $estado = 0;
            }
            $datos = [
                "nombre" => $_POST['nombre'],
                "categoria" => $_POST['categoria'],
                "pvp" => $_POST['pvp'],
                "stock" => $_POST['stock'],
                "imagen" => $imagen,
                "observaciones" => $_POST['observaciones'],
                "id" => $_GET['id']
            ];
            $resultModelo = $this->modelo->editar($datos);

            if ($resultModelo['bool']) {
                header("Location: index.php?accion=listado");
            } else {
                echo '<div class="alert alert-danger">error' . $resultModelo['error'] . '</div>';
            }
        }

        $datos = $_GET['id'];
        $resultModelo = $this->modelo->productoId($datos);
        if ($resultModelo['bool']) {
            $parametros['datos'] = $resultModelo['datos'];
            $resultModelo2 = $this->modelo->categorias();
            $parametros['categorias'] = $resultModelo2['datos'];
            include_once 'vistas/editarProducto.php';
        }
    }
}
