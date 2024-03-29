<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$fecha_reserva = "";
$fecha_juego = "";
$horario_juego = "";
$estado_reserva = "";
$cliente_cod_cliente = "";

$fecha_reserva_err = "";
$fecha_juego_err = "";
$horario_juego_err = "";
$estado_reserva_err = "";
$cliente_cod_cliente_err = "";

// Processing form data when form is submitted
if (isset($_POST["cod_reserva"]) && !empty($_POST["cod_reserva"])) {
    // Get hidden input value
    $cod_reserva = $_POST["cod_reserva"];

    $fecha_reserva = trim($_POST["fecha_reserva"]);
    $fecha_juego = trim($_POST["fecha_juego"]);
    $horario_juego = trim($_POST["horario_juego"]);
    $estado_reserva = trim($_POST["estado_reserva"]);
    $cliente_cod_cliente = trim($_POST["cliente_cod_cliente"]);

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

    $vars = parse_columns('reserva', $_POST);
    $stmt = $pdo->prepare("UPDATE reserva SET fecha_reserva=?,fecha_juego=?,horario_juego=?,estado_reserva=?,cliente_cod_cliente=? WHERE cod_reserva=?");

    if (!$stmt->execute([$fecha_reserva, $fecha_juego, $horario_juego, $estado_reserva, $cliente_cod_cliente, $cod_reserva])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: reserva-read.php?cod_reserva=$cod_reserva");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["cod_reserva"] = trim($_GET["cod_reserva"]);
    if (isset($_GET["cod_reserva"]) && !empty($_GET["cod_reserva"])) {
        // Get URL parameter
        $cod_reserva = trim($_GET["cod_reserva"]);

        // Prepare a select statement
        $sql = "SELECT * FROM reserva WHERE cod_reserva = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $cod_reserva;

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

                    $fecha_reserva = $row["fecha_reserva"];
                    $fecha_juego = $row["fecha_juego"];
                    $horario_juego = $row["horario_juego"];
                    $estado_reserva = $row["estado_reserva"];
                    $cliente_cod_cliente = $row["cliente_cod_cliente"];

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
                                <label>Fecha</label>
                                <input type="datetime-local" name="fecha_reserva" class="form-control" value="<?php echo date("Y-m-d\TH:i:s", strtotime($fecha_reserva)); ?>">
                                <span class="form-text"><?php echo $fecha_reserva_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha de juego</label>
                                <input type="date" name="fecha_juego" class="form-control" value="<?php echo $fecha_juego; ?>">
                                <span class="form-text"><?php echo $fecha_juego_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Horario de juego</label>
                                <input type="text" name="horario_juego" class="form-control" value="<?php echo $horario_juego; ?>">
                                <span class="form-text"><?php echo $horario_juego_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Estado</label>
                                <input type="text" name="estado_reserva" class="form-control" value="<?php echo $estado_reserva; ?>">
                                <span class="form-text"><?php echo $estado_reserva_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Codigo de cliente</label>
                                    <select class="form-control" id="cliente_cod_cliente" name="cliente_cod_cliente">
                                    <?php
$sql = "SELECT *,cod_cliente FROM cliente";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_pop($row);
    $value = implode(" | ", $row);
    if ($row["cod_cliente"] == $cliente_cod_cliente) {
        echo '<option value="' . "$row[cod_cliente]" . '"selected="selected">' . "$value" . '</option>';
    } else {
        echo '<option value="' . "$row[cod_cliente]" . '">' . "$value" . '</option>';
    }
}
?>
                                    </select>
                                <span class="form-text"><?php echo $cliente_cod_cliente_err; ?></span>
                            </div>

                        <input type="hidden" name="cod_reserva" value="<?php echo $cod_reserva; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="reserva-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
