<?php
require_once "config.php";
require_once "helpers.php";

$ci_cliente = "";
$nombre_cliente = "";
$apellido_cliente = "";
$dir_cliente = "";
$tel_cliente = "";
$correo_cliente = "";
$genero_cliente = "";
$fn_cliente = "";

$ci_cliente_err = "";
$nombre_cliente_err = "";
$apellido_cliente_err = "";
$dir_cliente_err = "";
$tel_cliente_err = "";
$correo_cliente_err = "";
$genero_cliente_err = "";
$fn_cliente_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ci_cliente = trim($_POST["ci_cliente"]);
    $nombre_cliente = trim($_POST["nombre_cliente"]);
    $apellido_cliente = trim($_POST["apellido_cliente"]);
    $dir_cliente = trim($_POST["dir_cliente"]);
    $tel_cliente = trim($_POST["tel_cliente"]);
    $correo_cliente = trim($_POST["correo_cliente"]);
    $genero_cliente = trim($_POST["genero_cliente"]);
    $fn_cliente = trim($_POST["fn_cliente"]);

    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened'); //something a user can understand
    }

    $vars = parse_columns('cliente', $_POST);
    $stmt = $pdo->prepare("INSERT INTO cliente (ci_cliente,nombre_cliente,apellido_cliente,dir_cliente,tel_cliente,correo_cliente,genero_cliente,fn_cliente) VALUES (?,?,?,?,?,?,?,?)");

    if ($stmt->execute([$ci_cliente, $nombre_cliente, $apellido_cliente, $dir_cliente, $tel_cliente, $correo_cliente, $genero_cliente, $fn_cliente])) {
        $stmt = null;
        header("location: cliente-index.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crear registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Crear registro</h2>
                    </div>
                    <p>Rellene este formulario y envíelo para añadir un registro a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                                <label>C.I.</label>
                                <input type="text" name="ci_cliente" maxlength="20"class="form-control" value="<?php echo $ci_cliente; ?>">
                                <span class="form-text"><?php echo $ci_cliente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nombre_cliente" maxlength="200"class="form-control" value="<?php echo $nombre_cliente; ?>">
                                <span class="form-text"><?php echo $nombre_cliente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Apellido</label>
                                <input type="text" name="apellido_cliente" maxlength="200"class="form-control" value="<?php echo $apellido_cliente; ?>">
                                <span class="form-text"><?php echo $apellido_cliente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Direccion</label>
                                <input type="text" name="dir_cliente" maxlength="1000"class="form-control" value="<?php echo $dir_cliente; ?>">
                                <span class="form-text"><?php echo $dir_cliente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Telefono</label>
                                <input type="text" name="tel_cliente" maxlength="20"class="form-control" value="<?php echo $tel_cliente; ?>">
                                <span class="form-text"><?php echo $tel_cliente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Correo</label>
                                <input type="text" name="correo_cliente" maxlength="100"class="form-control" value="<?php echo $correo_cliente; ?>">
                                <span class="form-text"><?php echo $correo_cliente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Genero</label>
                                <input type="text" name="genero_cliente" class="form-control" value="<?php echo $genero_cliente; ?>">
                                <span class="form-text"><?php echo $genero_cliente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha de nac.</label>
                                <input type="date" name="fn_cliente" class="form-control" value="<?php echo $fn_cliente; ?>">
                                <span class="form-text"><?php echo $fn_cliente_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="cliente-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>