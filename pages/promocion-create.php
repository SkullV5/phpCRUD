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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_promocion = trim($_POST["cod_promocion"]);
    $nombre_promocion = trim($_POST["nombre_promocion"]);
    $estado_promocion = trim($_POST["estado_promocion"]);
    $fechai_promocion = trim($_POST["fechai_promocion"]);
    $fechaf_promocion = trim($_POST["fechaf_promocion"]);
    $descuento_promocion = trim($_POST["descuento_promocion"]);
    $Disciplina_cod_disciplina = trim($_POST["Disciplina_cod_disciplina"]);

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

    $vars = parse_columns('promocion', $_POST);
    $stmt = $pdo->prepare("INSERT INTO promocion (cod_promocion,nombre_promocion,estado_promocion,fechai_promocion,fechaf_promocion,descuento_promocion,Disciplina_cod_disciplina) VALUES (?,?,?,?,?,?,?)");

    if ($stmt->execute([$cod_promocion, $nombre_promocion, $estado_promocion, $fechai_promocion, $fechaf_promocion, $descuento_promocion, $Disciplina_cod_disciplina])) {
        $stmt = null;
        header("location: promocion-index.php");
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

                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="promocion-index.php" class="btn btn-secondary">Cancelar</a>
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