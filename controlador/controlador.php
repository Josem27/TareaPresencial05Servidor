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

    public function nuevoProducto()
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
            include_once 'vistas/nuevoProducto.php';
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
    
        // Verificar si se recibió un ID válido por GET
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $idProducto = $_GET['id'];
    
            // Obtener los datos del producto por su ID
            $resultModelo = $this->modelo->obtenerProductoPorId($idProducto);
    
            // Verificar si se encontraron los datos del producto
            if ($resultModelo['bool']) {
                $parametros['datos'] = $resultModelo['datos'];
                $resultModelo2 = $this->modelo->categorias();
                $parametros['categorias'] = $resultModelo2['datos'];
                include_once 'vistas/editarProducto.php';
                return; // Salir del método después de incluir la vista
            } else {
                // Si no se encontraron datos, mostrar un mensaje de error
                $parametros['mensaje'] = "No se encontraron datos del producto.";
            }
        } else {
            // Si no se recibió un ID válido por GET, mostrar un mensaje de error
            $parametros['mensaje'] = "ID de producto no válido.";
        }
        
        // Incluir la vista de error
        include_once 'vistas/error.php';
    }
    
}
