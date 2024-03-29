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
if (isset($_POST["cod_empresa"]) && !empty($_POST["cod_empresa"])) {
    // Get hidden input value
    $cod_empresa = $_POST["cod_empresa"];

    $nombre_empresa = trim($_POST["nombre_empresa"]);
    $ruc_empresa = trim($_POST["ruc_empresa"]);
    $sec_empresa = trim($_POST["sec_empresa"]);
    $direccion_empresa = trim($_POST["direccion_empresa"]);
    $telefono_empresa = trim($_POST["telefono_empresa"]);
    $correo_empresa = trim($_POST["correo_empresa"]);
    $representante_empresa = trim($_POST["representante_empresa"]);

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

    $vars = parse_columns('empresa', $_POST);
    $stmt = $pdo->prepare("UPDATE empresa SET nombre_empresa=?,ruc_empresa=?,sec_empresa=?,direccion_empresa=?,telefono_empresa=?,correo_empresa=?,representante_empresa=? WHERE cod_empresa=?");

    if (!$stmt->execute([$nombre_empresa, $ruc_empresa, $sec_empresa, $direccion_empresa, $telefono_empresa, $correo_empresa, $representante_empresa, $cod_empresa])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: empresa-read.php?cod_empresa=$cod_empresa");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["cod_empresa"] = trim($_GET["cod_empresa"]);
    if (isset($_GET["cod_empresa"]) && !empty($_GET["cod_empresa"])) {
        // Get URL parameter
        $cod_empresa = trim($_GET["cod_empresa"]);

        // Prepare a select statement
        $sql = "SELECT * FROM empresa WHERE cod_empresa = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $cod_empresa;

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

                    $nombre_empresa = $row["nombre_empresa"];
                    $ruc_empresa = $row["ruc_empresa"];
                    $sec_empresa = $row["sec_empresa"];
                    $direccion_empresa = $row["direccion_empresa"];
                    $telefono_empresa = $row["telefono_empresa"];
                    $correo_empresa = $row["correo_empresa"];
                    $representante_empresa = $row["representante_empresa"];

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

                        <input type="hidden" name="cod_empresa" value="<?php echo $cod_empresa; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="empresa-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
