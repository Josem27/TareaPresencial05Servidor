<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once 'includes/header.php'; ?>
    <title>Contenido</title>
    <style>
        .center-content {
            text-align: center;
            margin-top: 20px;
        }
        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>    
    <?php include_once 'includes/menu.php'; ?>
    <?php 
    if(isset($_GET['post'])){
        if($_GET['post']==true){
            echo '<div class="alert alert-success">Producto creado correctamente</div>';
        }
    }
    // Verificar si $parametros['datos'] está definida y no es null antes de intentar iterar sobre ella
    if (isset($parametros['datos']) && is_array($parametros['datos'])) {
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Observaciones</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parametros['datos'] as $datos): ?>
                        <tr>
                            <td><?= $datos['nombre']?></td>
                            <td><?= substr($datos['observaciones'], 0, 45) . (strlen($datos['observaciones']) > 45 ? '...' : '') ?></td>
                            <td><?= $datos['pvp']?> €</td>
                            <td><?= $datos['stock']?></td>
                            <td><?= $datos['categoria_nombre']?></td>
                            <td><img src="<?= $datos['imagen'] ?>" alt="Imagen del producto" class="product-image"></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?accion=editar&id=<?= $datos['codprod'] ?>" role="button">Editar</a>
                                <a class="btn btn-primary" href="/index.php/api/producto&id=<?= $datos['codprod'] ?>" role="button">Detalles</a>
                                <a class="btn btn-danger" href="/index.php/api/eliminar&id=<?= $datos['codprod'] ?>" role="button">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } else { ?>
        <!-- Mostrar mensaje si la lista de productos está vacía -->
        <div class="container center-content">
            <p>No hay productos disponibles.</p>
        </div>
    <?php } ?>

    <?php 
    // Obtener los datos de paginación del modelo
    $modelo = new Modelo();
    $resultModelo = $modelo->get_paginacion();

    // Incluir el archivo paginado.php y pasar los valores necesarios
    include_once 'includes/paginado.php';
    ?>

    <script>
        // Script para manejar la eliminación de productos usando AJAX
        document.querySelectorAll('.delete-product').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                    fetch(`api/producto/${productId}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al eliminar el producto');
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert('Producto eliminado correctamente');
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al eliminar el producto');
                    });
                }
            });
        });
    </script>
</body>
</html>
