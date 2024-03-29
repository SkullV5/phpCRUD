<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nombre_empresa = "";
$ruc_empresa = "";
$sec_empresa = "";
$direccion_empresa = "";
$telefono_empresa = "";
$correo_empresa = "";
$representante_empresa = "";

$nombre_empresa_err = "";
$ruc_empresa_err = "";
$sec_empresa_err = "";
$direccion_empresa_err = "";
$telefono_empresa_err = "";
$correo_empresa_err = "";
$representante_empresa_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_empresa = trim($_POST["nombre_empresa"]);
    $ruc_empresa = trim($_POST["ruc_empresa"]);
    $sec_empresa = trim($_POST["sec_empresa"]);
    $direccion_empresa = trim($_POST["direccion_empresa"]);
    $telefono_empresa = trim($_POST["telefono_empresa"]);
    $correo_empresa = trim($_POST["correo_empresa"]);
    $representante_empresa = trim($_POST["representante_empresa"]);

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
        exit('Something weird happened'); //something a user can understand
    }

    $vars = parse_columns('empresa', $_POST);
    $stmt = $pdo->prepare("INSERT INTO empresa (nombre_empresa,ruc_empresa,sec_empresa,direccion_empresa,telefono_empresa,correo_empresa,representante_empresa) VALUES (?,?,?,?,?,?,?)");

    if ($stmt->execute([$nombre_empresa, $ruc_empresa, $sec_empresa, $direccion_empresa, $telefono_empresa, $correo_empresa, $representante_empresa])) {
        $stmt = null;
        header("location: empresa-index.php");
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
                                <label>Nombre</label>
                                <input type="text" name="nombre_empresa" maxlength="45"class="form-control" value="<?php echo $nombre_empresa; ?>">
                                <span class="form-text"><?php echo $nombre_empresa_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>R.U.C.</label>
                                <input type="text" name="ruc_empresa" maxlength="45"class="form-control" value="<?php echo $ruc_empresa; ?>">
                                <span class="form-text"><?php echo $ruc_empresa_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>sec_empresa</label>
                                <input type="text" name="sec_empresa" maxlength="45"class="form-control" value="<?php echo $sec_empresa; ?>">
                                <span class="form-text"><?php echo $sec_empresa_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Dirección</label>
                                <input type="text" name="direccion_empresa" maxlength="45"class="form-control" value="<?php echo $direccion_empresa; ?>">
                                <span class="form-text"><?php echo $direccion_empresa_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="telefono_empresa" maxlength="45"class="form-control" value="<?php echo $telefono_empresa; ?>">
                                <span class="form-text"><?php echo $telefono_empresa_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Correo</label>
                                <input type="text" name="correo_empresa" maxlength="45"class="form-control" value="<?php echo $correo_empresa; ?>">
                                <span class="form-text"><?php echo $correo_empresa_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Representante</label>
                                <input type="text" name="representante_empresa" maxlength="45"class="form-control" value="<?php echo $representante_empresa; ?>">
                                <span class="form-text"><?php echo $representante_empresa_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="empresa-index.php" class="btn btn-secondary">Cancelar</a>
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