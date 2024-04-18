<?php

class Modelo {

    private $conexion;
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "bdproductos"; // Nombre de la base de datos

    private $longitudPag = 3; // Para Paginacion, si hiciera falta en el listado
    private $page = 0;

    public function __construct()
    {
        $this->conectar();
        if (isset($_GET['page']) && is_numeric($_GET['page'])) { // Paginacion
            $this->page = $_GET['page'];
        }
    }

    public function get_paginacion(){
        $listCount = $this->conexion->query("SELECT COUNT(*) FROM productos")->fetch()[0];
        if ($this->page > ($listCount / $this->longitudPag)) {            
            header("Location: /index.php?accion=listado");            
        }
        $result = [
            'paginas' => $listCount,
            'longitudPag' => $this->longitudPag,
            'offset' => 3
        ];
        return $result;
    }

    // Funcion para realizar la conexion a la base de datos
    public function conectar()
    {
        try {
            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexion;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

// Función para listar todos los productos con el nombre de la categoría
public function productos()
{
    $offset = $this->page * $this->longitudPag; // LIMIT $longitudPag OFFSET $offset
    $sql = $this->conexion->prepare("SELECT p.*, c.nombre as categoria_nombre FROM productos p LEFT JOIN categorias c ON p.categoria = c.id LIMIT $this->longitudPag OFFSET $offset");
    $sql->execute();
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}


    // Funcion para crear una nueva entrada en la tabla
    public function new_Producto($datos)
    {
        $result = [
            "bool" => false
        ];

        try {
            // Sentencia SQL
            $sql = "INSERT INTO productos(nombre, categoria, pvp, stock, imagen, Observaciones) VALUES (:nombre, :categoria, :pvp, :stock, :imagen, :observaciones)";

            $query = $this->conexion->prepare($sql);

            $query->execute([                
                'nombre' => $datos['nombre'],
                'categoria' => $datos['categoria'],
                'pvp' => $datos['pvp'],
                'stock' => $datos['stock'],
                'imagen' => $datos['imagen'],
                'observaciones' => $datos['observaciones']
            ]);

            if ($query->rowCount() > 0) {
                $result['bool'] = true;
                $result['error'] = 0;
            } else {
                $result['error'] = "No se insertó ninguna fila";
            }

        } catch (PDOException $e) {
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    // Funcion para eliminar una entrada
    public function del_Producto($id)
    {
        $result = [
            'bool' => false,
            'error' => null
        ];
        try {
            $sql = "DELETE FROM productos WHERE codprod = :id";

            $query = $this->conexion->prepare($sql);

            $query->execute(['id' => $id]);

            if ($query->rowCount() > 0) {
                $result['bool'] = true;
            }

        } catch (PDOException $e) {
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    // Funcion para editar una entrada con id
    public function editar($datos)
    {
        $result = [
            "bool" => false,
            "error" => null
        ];
        try {
            // Sentencia SQL            
            $sql = "UPDATE productos SET nombre= :nombre, categoria= :categoria, pvp= :pvp, stock= :stock, imagen= :imagen, Observaciones= :observaciones WHERE codprod= :id";
    
            $query = $this->conexion->prepare($sql);
    
            $query->execute([               
                'nombre' => $datos['nombre'],
                'categoria' => $datos['categoria'],
                'pvp' => $datos['pvp'],
                'stock' => $datos['stock'],
                'imagen' => $datos['imagen'],
                'observaciones' => $datos['observaciones'],
                'id' => $datos['id']
            ]);   
    
            // Verificar si la actualización se realizó correctamente
            if ($query->rowCount() > 0) {
                $result['bool'] = true;
                $result['error'] = null;
            } else {
                $result['error'] = "No se encontró ninguna fila para actualizar.";
            }
        } catch (PDOException $e) {
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    // Funcion para gestionar la imagen subida
    public function get_image() {
        $result = [
            "error" => null,
            "imagen" => null,
        ];
        if (!is_dir("images")) {
            $dir = mkdir("images", 0777, true);
        } else {
            $dir = true;
        }

        if ($dir) {
            $nombreFichImg = time() . "-" . $_FILES["imagen"]["name"];
            $movFichImg = move_uploaded_file($_FILES["imagen"]["tmp_name"], "images/" . $nombreFichImg);            
            if (!$movFichImg) {
                $result["error"] = "Error, imagen no cargada";
            }
            $result['imagen'] = $nombreFichImg;        
        }

        return $result;
    }

    public function categorias()
    {
        $result = [
            "datos" => null,
            "mensaje" => null,
            "bool" => false,
        ];

        try {
            $sql = "SELECT * FROM categorias";
            $resultquery = $this->conexion->query($sql);
            if ($resultquery) {
                $result['bool'] = true;
                $result['datos'] = $resultquery->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $result['mensaje'] = $e->getMessage();
        }
        return $result;
    }
}

?>
