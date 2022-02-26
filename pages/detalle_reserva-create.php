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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cancha_cod_cancha = trim($_POST["cancha_cod_cancha"]);
    $reserva_cod_reserva = trim($_POST["reserva_cod_reserva"]);
    $cantidad = trim($_POST["cantidad"]);
    $precio = trim($_POST["precio"]);

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

    $vars = parse_columns('detalle_reserva', $_POST);
    $stmt = $pdo->prepare("INSERT INTO detalle_reserva (cancha_cod_cancha,reserva_cod_reserva,cantidad,precio) VALUES (?,?,?,?)");

    if ($stmt->execute([$cancha_cod_cancha, $reserva_cod_reserva, $cantidad, $precio])) {
        $stmt = null;
        header("location: detalle_reserva-index.php");
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

                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="detalle_reserva-index.php" class="btn btn-secondary">Cancelar</a>
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