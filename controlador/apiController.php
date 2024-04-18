<?php

require_once "modelo/Modelo.php";

class ApiController
{
    private $controlador;

    public function __construct()
    {
        $this->controlador = new Controlador();
    }

    public function listado()
    {
        // Obtener la lista de productos utilizando el método correspondiente del Controlador
        $productos = $this->controlador->listado();
        
        // Enviar la respuesta JSON
        echo json_encode($productos);
    }

    public function nuevoProducto()
    {
        // Verificar si se está realizando una solicitud POST o PUT
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Verificar si se enviaron datos en el cuerpo de la solicitud
            if (!empty($_POST)) {
                // Verificar si se adjuntó una imagen
                if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) { 
                    // Obtener la imagen
                    $dataImagen = $this->modelo->get_image();
                    $imagen = $dataImagen['imagen'];
                    $erroresImagen = $dataImagen['error'];
                }
                
                // Verificar si hay errores en el procesamiento de la imagen
                if (!isset($erroresImagen)) {
                    // Obtener el estado del producto
                    $estado = 0;
                    if ($_POST['estado'] == 'on') {
                        $estado = 1;
                    }
                    
                    // Crear un array con los datos del producto
                    $datosProducto = [
                        "nombre" => $_POST['nombre'],
                        "categoria" => $_POST['categoria'],
                        "pvp" => $_POST['pvp'],
                        "stock" => $_POST['stock'],
                        "imagen" => $imagen,
                        "observaciones" => $_POST['observaciones'],
                        // Añadir otros campos requeridos aquí
                        "estado" => $estado,
                        // Añadir más campos si es necesario
                    ];
    
                    // Llamar al método del modelo para agregar el nuevo producto
                    $resultado = $this->modelo->new_Producto($datosProducto);
                    
                    // Verificar si se agregó el producto correctamente
                    if ($resultado['bool']) {
                        // Producto creado correctamente, redirigir a la página de listado
                        header("Location: /listado.php?post=true");
                        exit();
                    } else {
                        // Hubo un error al agregar el producto
                        http_response_code(500); // Código 500: Error interno del servidor
                        echo json_encode(array("mensaje" => "No se pudo crear el producto", "error" => $resultado['error']));
                    }
                } else {
                    // Error al procesar la imagen
                    http_response_code(400); // Código 400: Solicitud incorrecta
                    echo json_encode(array("mensaje" => "Error al procesar la imagen", "error" => $erroresImagen));
                }
            } else {
                // No se enviaron datos en el cuerpo de la solicitud
                http_response_code(400); // Código 400: Solicitud incorrecta
                echo json_encode(array("mensaje" => "No se enviaron datos en el cuerpo de la solicitud"));
            }
        } else {
            // Método no permitido
            http_response_code(405); // Código 405: Método no permitido
            echo json_encode(array("mensaje" => "Método no permitido"));
        }
    }
    

    public function producto($id)
    {
        // Obtener la información del producto con el ID especificado utilizando el Controlador
        $producto = $this->controlador->producto($id);
        
        // Enviar la respuesta JSON
        echo json_encode($producto);
    }

    public function eliminar($id)
    {
        // Eliminar el producto con el ID especificado utilizando el Controlador
        $mensaje = $this->controlador->eliminar($id);
        
        // Enviar la respuesta JSON con un mensaje
        echo json_encode(["mensaje" => $mensaje]);
    }

}
