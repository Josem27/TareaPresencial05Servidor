<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once 'includes/header.php' ?>
</head>

<body>
    <div class="container cuerpo text-center">
        <p>
            <h2>Nuevo Producto</h2>
        </p>
        <hr width="50%" color="black">
    </div>
    <div class="container cuerpo text-center">
        <a class="btn btn-primary" href="index.php?accion=listado" role="button">Volver</a>
        <hr width="50%" color="black">
    </div>
    <script>
        CKEDITOR.replace('observaciones');
    </script>
    <div class="container text-center">
        <!--<form action="/index.php/api/nuevoProducto" method="POST" enctype="multipart/form-data">-->
        <form action="/index.php?accion=nuevoProducto" method="POST" enctype="multipart/form-data">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required><br>
            <label for="categoria" class="form-label">Categoría</label>
            <select name="categoria" id="categoria" class="form-control" required>
                <option value="">Selecciona una categoría</option>
                    <?php foreach ($parametros['categorias'] as $categoria): ?>
                <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
                    <?php endforeach; ?>
            </select><br>
            <label for="pvp" class="form-label">Precio (€)</label>
            <input type="number" step="0.01" class="form-control" name="pvp" id="pvp" required><br>
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" id="stock" required><br>
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="form-control-file" required><br>
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones" required></textarea><br>
            <button type="submit" name="submit" class="btn btn-primary">Registrar Producto</button><br>
        </form>
    </div>
</body>

</html>
