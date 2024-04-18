<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once 'includes/header.php'; ?>
    <title>Detalle del Producto</title>
</head>

<body>
    <div class="container">
        <h2>Detalle del Producto</h2>
        <hr>
        <?php if (isset($parametros['datos'])): ?>
            <?php $producto = $parametros['datos']; ?>
            <p><strong>Nombre:</strong> <?= $producto['nombre'] ?></p>
            <p><strong>Categoría:</strong> <?= $producto['categoria'] ?></p>
            <p><strong>Precio (€):</strong> <?= $producto['pvp'] ?></p>
            <p><strong>Stock:</strong> <?= $producto['stock'] ?></p>
            <p><strong>Observaciones:</strong> <?= $producto['observaciones'] ?></p>
            <p><strong>Imagen:</strong> <img src="<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>"></p>
        <?php else: ?>
            <p>No se encontraron detalles para este producto.</p>
        <?php endif; ?>
        
        <a href="index.php?accion=listado" class="btn btn-primary">Volver al listado</a>
    </div>
</body>

</html>
