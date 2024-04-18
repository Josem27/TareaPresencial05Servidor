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
    
        // Verificar si se ha enviado el formulario y si hay datos en $_POST
        if (isset($_POST['submit']) && !empty($_POST)) {
            $imagen = null;
            $errores = array();
    
            // Verificar si se ha subido una imagen
            if (isset($_FILES["imagen"]) && !empty($_FILES["imagen"]["tmp_name"])) {
                $data = $this->modelo->get_image();
                $imagen = $data['imagen'];
                $errores = $data['error'];
            }
    
            // Verificar si hay errores en la imagen
            if (!empty($errores)) {
                // Mostrar los errores en la vista del formulario
                $parametros['errores'] = $errores;
            } else {
                // Preparar los datos del producto para la inserción en la base de datos
                $datos = [
                    "nombre" => $_POST['nombre'],
                    "categoria" => $_POST['categoria'],
                    "pvp" => $_POST['pvp'],
                    "stock" => $_POST['stock'],
                    "imagen" => $imagen,
                    "observaciones" => $_POST['observaciones']
                ];
    
                // Insertar el producto en la base de datos
                $resultModelo = $this->modelo->new_Producto($datos);
    
                // Verificar si la inserción fue exitosa
                if ($resultModelo['bool']) {
                    // Redirigir después de la inserción exitosa
                    header("Location: index.php?accion=listado&post=true");
                    exit(); // Salir del script después de la redirección
                } else {
                    // Mostrar un mensaje de error en la vista
                    $parametros['error'] = "Error al crear el producto. Inténtalo de nuevo.";
                }
            }
        }
    
        // Obtener las categorías para el formulario
        $resultModelo = $this->modelo->categorias();
        $parametros['categorias'] = $resultModelo['datos'];
    
        // Incluir la vista del formulario
        include_once 'vistas/nuevoProducto.php';
    }
    

    public function producto()
    {
        $parametros = [
            "titulo" => "Producto",
            "datos" => null,
            "mensaje" => null
        ];

        $datos = $_GET['id'];
        $resultModelo = $this->modelo->obtenerProductoPorId($datos);
        if ($resultModelo['bool']) {
            $parametros['datos'] = $resultModelo['datos'];
            include_once 'vistas/verProducto.php';
        }
    }

    public function eliminar()
    {
        // Verificar si se recibió un ID válido por GET
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            // Obtener el ID del producto desde la URL
            $idProducto = $_GET['id'];
    
            // Eliminar el producto utilizando el método del_Modelo()
            $resultModelo = $this->modelo->del_Producto($idProducto);
    
            // Verificar si la eliminación fue exitosa
            if ($resultModelo['bool']) {
                // Redirigir después de eliminar el producto exitosamente
                header("Location: index.php?accion=listado&delete=true");
                exit(); // Salir del script después de la redirección
            } else {
                // Mostrar un mensaje de error si la eliminación falló
                $parametros['mensaje'] = "Error al intentar eliminar el producto.";
            }
        } else {
            // Si no se recibió un ID válido por GET, mostrar un mensaje de error
            $parametros['mensaje'] = "ID de producto no válido.";
        }
    
        // Incluir la vista de error
        include_once 'vistas/error.php';
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
    
    public function generarPDF()
{
    // Obtener todo el listado de productos
    $resultModelo = $this->modelo->productos();

    // Incluir los archivos de TCPDF localmente
    require_once 'tcpdf/tcpdf.php';

    // Crear una instancia de TCPDF
    $pdf = new TCPDF();

    // Establecer la ubicación de las fuentes de TCPDF
    $fontPath = 'ruta/a/la/carpeta/fonts/';
    TCPDF_FONTS::addTTFfont($fontPath . 'arial.ttf', 'TrueTypeUnicode', '', 32);

    // Agregar contenido al PDF (ajusta según tus necesidades)
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 16);

    foreach ($resultModelo as $producto) {
        $pdf->Cell(0, 10, 'Detalles del Producto', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Nombre: ' . $producto['nombre'], 0, 1);
        $pdf->Cell(0, 10, 'Observaciones: ' . $producto['observaciones'], 0, 1);
        $pdf->Cell(0, 10, 'Precio: ' . $producto['pvp'] . ' €', 0, 1);
        $pdf->Cell(0, 10, 'Stock: ' . $producto['stock'], 0, 1);
        $pdf->Cell(0, 10, 'Categoría: ' . $producto['categoria_nombre'], 0, 1);

        // Agregar la imagen al PDF
        if ($producto['imagen'] != null) {
            $imagePath = 'fotos/' . $producto['imagen']; // Ajusta la ruta de la imagen según sea necesario
            $pdf->Image($imagePath, 10, 40, 90, 0, '', '', '', false, 300, '', false, false, 0);
        }

        // Agregar un salto de página después de cada producto
        $pdf->AddPage();
    }

    // Salida del PDF
    $pdf->Output('Listado_de_Productos.pdf', 'I');
    exit();
}


}
