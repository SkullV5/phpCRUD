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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_reserva = trim($_POST["fecha_reserva"]);
    $fecha_juego = trim($_POST["fecha_juego"]);
    $horario_juego = trim($_POST["horario_juego"]);
    $estado_reserva = trim($_POST["estado_reserva"]);
    $cliente_cod_cliente = trim($_POST["cliente_cod_cliente"]);

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

    $vars = parse_columns('reserva', $_POST);
    $stmt = $pdo->prepare("INSERT INTO reserva (fecha_reserva,fecha_juego,horario_juego,estado_reserva,cliente_cod_cliente) VALUES (?,?,?,?,?)");

    if ($stmt->execute([$fecha_reserva, $fecha_juego, $horario_juego, $estado_reserva, $cliente_cod_cliente])) {
        $stmt = null;
        header("location: reserva-index.php");
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

                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="reserva-index.php" class="btn btn-secondary">Cancelar</a>
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