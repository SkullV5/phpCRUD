<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$cod_promocion = "";
$nombre_promocion = "";
$estado_promocion = "";
$fechai_promocion = "";
$fechaf_promocion = "";
$descuento_promocion = "";
$Disciplina_cod_disciplina = "";

$cod_promocion_err = "";
$nombre_promocion_err = "";
$estado_promocion_err = "";
$fechai_promocion_err = "";
$fechaf_promocion_err = "";
$descuento_promocion_err = "";
$Disciplina_cod_disciplina_err = "";

// Processing form data when form is submitted
if (isset($_POST["cod_promocion"]) && !empty($_POST["cod_promocion"])) {
    // Get hidden input value
    $cod_promocion = $_POST["cod_promocion"];

    $cod_promocion = trim($_POST["cod_promocion"]);
    $nombre_promocion = trim($_POST["nombre_promocion"]);
    $estado_promocion = trim($_POST["estado_promocion"]);
    $fechai_promocion = trim($_POST["fechai_promocion"]);
    $fechaf_promocion = trim($_POST["fechaf_promocion"]);
    $descuento_promocion = trim($_POST["descuento_promocion"]);
    $Disciplina_cod_disciplina = trim($_POST["Disciplina_cod_disciplina"]);

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

    $vars = parse_columns('promocion', $_POST);
    $stmt = $pdo->prepare("UPDATE promocion SET cod_promocion=?,nombre_promocion=?,estado_promocion=?,fechai_promocion=?,fechaf_promocion=?,descuento_promocion=?,Disciplina_cod_disciplina=? WHERE cod_promocion=?");

    if (!$stmt->execute([$cod_promocion, $nombre_promocion, $estado_promocion, $fechai_promocion, $fechaf_promocion, $descuento_promocion, $Disciplina_cod_disciplina, $cod_promocion])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: promocion-read.php?cod_promocion=$cod_promocion");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["cod_promocion"] = trim($_GET["cod_promocion"]);
    if (isset($_GET["cod_promocion"]) && !empty($_GET["cod_promocion"])) {
        // Get URL parameter
        $cod_promocion = trim($_GET["cod_promocion"]);

        // Prepare a select statement
        $sql = "SELECT * FROM promocion WHERE cod_promocion = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $cod_promocion;

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

                    $cod_promocion = $row["cod_promocion"];
                    $nombre_promocion = $row["nombre_promocion"];
                    $estado_promocion = $row["estado_promocion"];
                    $fechai_promocion = $row["fechai_promocion"];
                    $fechaf_promocion = $row["fechaf_promocion"];
                    $descuento_promocion = $row["descuento_promocion"];
                    $Disciplina_cod_disciplina = $row["Disciplina_cod_disciplina"];

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
                                <label>ID</label>
                                <input type="number" name="cod_promocion" class="form-control" value="<?php echo $cod_promocion; ?>">
                                <span class="form-text"><?php echo $cod_promocion_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nombre_promocion" maxlength="200"class="form-control" value="<?php echo $nombre_promocion; ?>">
                                <span class="form-text"><?php echo $nombre_promocion_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Estado</label>
                                <input type="number" name="estado_promocion" class="form-control" value="<?php echo $estado_promocion; ?>">
                                <span class="form-text"><?php echo $estado_promocion_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha inicio</label>
                                <input type="date" name="fechai_promocion" class="form-control" value="<?php echo $fechai_promocion; ?>">
                                <span class="form-text"><?php echo $fechai_promocion_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha fin</label>
                                <input type="date" name="fechaf_promocion" class="form-control" value="<?php echo $fechaf_promocion; ?>">
                                <span class="form-text"><?php echo $fechaf_promocion_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Descuento</label>
                                <input type="number" name="descuento_promocion" class="form-control" value="<?php echo $descuento_promocion; ?>" step="any">
                                <span class="form-text"><?php echo $descuento_promocion_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Codigo de disciplina</label>
                                    <select class="form-control" id="Disciplina_cod_disciplina" name="Disciplina_cod_disciplina">
                                    <?php
$sql = "SELECT *,cod_disciplina FROM disciplina";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_pop($row);
    $value = implode(" | ", $row);
    if ($row["cod_disciplina"] == $Disciplina_cod_disciplina) {
        echo '<option value="' . "$row[cod_disciplina]" . '"selected="selected">' . "$value" . '</option>';
    } else {
        echo '<option value="' . "$row[cod_disciplina]" . '">' . "$value" . '</option>';
    }
}
?>
                                    </select>
                                <span class="form-text"><?php echo $Disciplina_cod_disciplina_err; ?></span>
                            </div>

                        <input type="hidden" name="cod_promocion" value="<?php echo $cod_promocion; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="promocion-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
