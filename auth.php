<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "user"; // Usuario de ejemplo
    $password = "user"; // Contraseña de ejemplo

    // Verificar si las credenciales son correctas
    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        $_SESSION['loggedin'] = true;
        header("Location: vistas/listado.php");
        exit();
    } else {
        $_SESSION['error'] = "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
        header("Location: index.php");
        exit();
    }
}
?>