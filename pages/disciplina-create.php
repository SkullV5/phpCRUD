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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_disciplina = trim($_POST["nombre_disciplina"]);
    $tiempo_juego = trim($_POST["tiempo_juego"]);
    $empresa_cod_empresa = trim($_POST["empresa_cod_empresa"]);
    $precio_disciplina = trim($_POST["precio_disciplina"]);

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

    $vars = parse_columns('disciplina', $_POST);
    $stmt = $pdo->prepare("INSERT INTO disciplina (nombre_disciplina,tiempo_juego,empresa_cod_empresa,precio_disciplina) VALUES (?,?,?,?)");

    if ($stmt->execute([$nombre_disciplina, $tiempo_juego, $empresa_cod_empresa, $precio_disciplina])) {
        $stmt = null;
        header("location: disciplina-index.php");
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

                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="disciplina-index.php" class="btn btn-secondary">Cancelar</a>
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