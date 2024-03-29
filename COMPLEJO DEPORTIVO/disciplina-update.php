<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nombre_disciplina = "";
$tiempo_juego = "";
$empresa_cod_empresa = "";
$precio_disciplina = "";

$nombre_disciplina_err = "";
$tiempo_juego_err = "";
$empresa_cod_empresa_err = "";
$precio_disciplina_err = "";

// Processing form data when form is submitted
if (isset($_POST["cod_disciplina"]) && !empty($_POST["cod_disciplina"])) {
    // Get hidden input value
    $cod_disciplina = $_POST["cod_disciplina"];

    $nombre_disciplina = trim($_POST["nombre_disciplina"]);
    $tiempo_juego = trim($_POST["tiempo_juego"]);
    $empresa_cod_empresa = trim($_POST["empresa_cod_empresa"]);
    $precio_disciplina = trim($_POST["precio_disciplina"]);

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

    $vars = parse_columns('disciplina', $_POST);
    $stmt = $pdo->prepare("UPDATE disciplina SET nombre_disciplina=?,tiempo_juego=?,empresa_cod_empresa=?,precio_disciplina=? WHERE cod_disciplina=?");

    if (!$stmt->execute([$nombre_disciplina, $tiempo_juego, $empresa_cod_empresa, $precio_disciplina, $cod_disciplina])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: disciplina-read.php?cod_disciplina=$cod_disciplina");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["cod_disciplina"] = trim($_GET["cod_disciplina"]);
    if (isset($_GET["cod_disciplina"]) && !empty($_GET["cod_disciplina"])) {
        // Get URL parameter
        $cod_disciplina = trim($_GET["cod_disciplina"]);

        // Prepare a select statement
        $sql = "SELECT * FROM disciplina WHERE cod_disciplina = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $cod_disciplina;

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

                    $nombre_disciplina = $row["nombre_disciplina"];
                    $tiempo_juego = $row["tiempo_juego"];
                    $empresa_cod_empresa = $row["empresa_cod_empresa"];
                    $precio_disciplina = $row["precio_disciplina"];

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
                                <label>Disciplina</label>
                                <input type="text" name="nombre_disciplina" maxlength="200"class="form-control" value="<?php echo $nombre_disciplina; ?>">
                                <span class="form-text"><?php echo $nombre_disciplina_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Tiempo de juego</label>
                                <input type="text" name="tiempo_juego" maxlength="45"class="form-control" value="<?php echo $tiempo_juego; ?>">
                                <span class="form-text"><?php echo $tiempo_juego_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Codigo de empresa</label>
                                    <select class="form-control" id="empresa_cod_empresa" name="empresa_cod_empresa">
                                    <?php
$sql = "SELECT *,cod_empresa FROM empresa";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_pop($row);
    $value = implode(" | ", $row);
    if ($row["cod_empresa"] == $empresa_cod_empresa) {
        echo '<option value="' . "$row[cod_empresa]" . '"selected="selected">' . "$value" . '</option>';
    } else {
        echo '<option value="' . "$row[cod_empresa]" . '">' . "$value" . '</option>';
    }
}
?>
                                    </select>
                                <span class="form-text"><?php echo $empresa_cod_empresa_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Precio</label>
                                <input type="text" name="precio_disciplina" maxlength="45"class="form-control" value="<?php echo $precio_disciplina; ?>">
                                <span class="form-text"><?php echo $precio_disciplina_err; ?></span>
                            </div>

                        <input type="hidden" name="cod_disciplina" value="<?php echo $cod_disciplina; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="disciplina-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
