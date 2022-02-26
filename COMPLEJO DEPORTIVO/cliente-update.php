<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
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

// Processing form data when form is submitted
if (isset($_POST["cod_cliente"]) && !empty($_POST["cod_cliente"])) {
    // Get hidden input value
    $cod_cliente = $_POST["cod_cliente"];

    $ci_cliente = trim($_POST["ci_cliente"]);
    $nombre_cliente = trim($_POST["nombre_cliente"]);
    $apellido_cliente = trim($_POST["apellido_cliente"]);
    $dir_cliente = trim($_POST["dir_cliente"]);
    $tel_cliente = trim($_POST["tel_cliente"]);
    $correo_cliente = trim($_POST["correo_cliente"]);
    $genero_cliente = trim($_POST["genero_cliente"]);
    $fn_cliente = trim($_POST["fn_cliente"]);

    // Prepare an update statement
    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }

    $vars = parse_columns('cliente', $_POST);
    $stmt = $pdo->prepare("UPDATE cliente SET ci_cliente=?,nombre_cliente=?,apellido_cliente=?,dir_cliente=?,tel_cliente=?,correo_cliente=?,genero_cliente=?,fn_cliente=? WHERE cod_cliente=?");

    if (!$stmt->execute([$ci_cliente, $nombre_cliente, $apellido_cliente, $dir_cliente, $tel_cliente, $correo_cliente, $genero_cliente, $fn_cliente, $cod_cliente])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: cliente-read.php?cod_cliente=$cod_cliente");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["cod_cliente"] = trim($_GET["cod_cliente"]);
    if (isset($_GET["cod_cliente"]) && !empty($_GET["cod_cliente"])) {
        // Get URL parameter
        $cod_cliente = trim($_GET["cod_cliente"]);

        // Prepare a select statement
        $sql = "SELECT * FROM cliente WHERE cod_cliente = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $cod_cliente;

            // Bind variables to the prepared statement as parameters
            if (is_int($param_id)) {
                $__vartype = "i";
            } elseif (is_string($param_id)) {
                $__vartype = "s";
            } elseif (is_numeric($param_id)) {
                $__vartype = "d";
            } else {
                $__vartype = "b";
            }
            // blob
            mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $ci_cliente = $row["ci_cliente"];
                    $nombre_cliente = $row["nombre_cliente"];
                    $apellido_cliente = $row["apellido_cliente"];
                    $dir_cliente = $row["dir_cliente"];
                    $tel_cliente = $row["tel_cliente"];
                    $correo_cliente = $row["correo_cliente"];
                    $genero_cliente = $row["genero_cliente"];
                    $fn_cliente = $row["fn_cliente"];

                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Actualizar Registro</h2>
                    </div>
                    <p>Por favor, edite los valores introducidos y envíelos para actualizar el registro.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

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

                        <input type="hidden" name="cod_cliente" value="<?php echo $cod_cliente; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="cliente-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
