<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$cancha_cod_cancha = "";
$reserva_cod_reserva = "";
$cantidad = "";
$precio = "";

$cancha_cod_cancha_err = "";
$reserva_cod_reserva_err = "";
$cantidad_err = "";
$precio_err = "";

// Processing form data when form is submitted
if (isset($_POST["reserva_cod_reserva"]) && !empty($_POST["reserva_cod_reserva"])) {
    // Get hidden input value
    $reserva_cod_reserva = $_POST["reserva_cod_reserva"];

    $cancha_cod_cancha = trim($_POST["cancha_cod_cancha"]);
    $reserva_cod_reserva = trim($_POST["reserva_cod_reserva"]);
    $cantidad = trim($_POST["cantidad"]);
    $precio = trim($_POST["precio"]);

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

    $vars = parse_columns('detalle_reserva', $_POST);
    $stmt = $pdo->prepare("UPDATE detalle_reserva SET cancha_cod_cancha=?,reserva_cod_reserva=?,cantidad=?,precio=? WHERE reserva_cod_reserva=?");

    if (!$stmt->execute([$cancha_cod_cancha, $reserva_cod_reserva, $cantidad, $precio, $reserva_cod_reserva])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: detalle_reserva-read.php?reserva_cod_reserva=$reserva_cod_reserva");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["reserva_cod_reserva"] = trim($_GET["reserva_cod_reserva"]);
    if (isset($_GET["reserva_cod_reserva"]) && !empty($_GET["reserva_cod_reserva"])) {
        // Get URL parameter
        $reserva_cod_reserva = trim($_GET["reserva_cod_reserva"]);

        // Prepare a select statement
        $sql = "SELECT * FROM detalle_reserva WHERE reserva_cod_reserva = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $reserva_cod_reserva;

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

                    $cancha_cod_cancha = $row["cancha_cod_cancha"];
                    $reserva_cod_reserva = $row["reserva_cod_reserva"];
                    $cantidad = $row["cantidad"];
                    $precio = $row["precio"];

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
                                    <select class="form-control" id="cancha_cod_cancha" name="cancha_cod_cancha">
                                    <?php
$sql = "SELECT *,cod_cancha FROM cancha";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_pop($row);
    $value = implode(" | ", $row);
    if ($row["cod_cancha"] == $cancha_cod_cancha) {
        echo '<option value="' . "$row[cod_cancha]" . '"selected="selected">' . "$value" . '</option>';
    } else {
        echo '<option value="' . "$row[cod_cancha]" . '">' . "$value" . '</option>';
    }
}
?>
                                    </select>
                                <span class="form-text"><?php echo $cancha_cod_cancha_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Cod. de reserva</label>
                                    <select class="form-control" id="reserva_cod_reserva" name="reserva_cod_reserva">
                                    <?php
$sql = "SELECT *,cod_reserva FROM reserva";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_pop($row);
    $value = implode(" | ", $row);
    if ($row["cod_reserva"] == $reserva_cod_reserva) {
        echo '<option value="' . "$row[cod_reserva]" . '"selected="selected">' . "$value" . '</option>';
    } else {
        echo '<option value="' . "$row[cod_reserva]" . '">' . "$value" . '</option>';
    }
}
?>
                                    </select>
                                <span class="form-text"><?php echo $reserva_cod_reserva_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Cantidad</label>
                                <input type="number" name="cantidad" class="form-control" value="<?php echo $cantidad; ?>">
                                <span class="form-text"><?php echo $cantidad_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Precio</label>
                                <input type="number" name="precio" class="form-control" value="<?php echo $precio; ?>" step="any">
                                <span class="form-text"><?php echo $precio_err; ?></span>
                            </div>

                        <input type="hidden" name="reserva_cod_reserva" value="<?php echo $reserva_cod_reserva; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="detalle_reserva-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
