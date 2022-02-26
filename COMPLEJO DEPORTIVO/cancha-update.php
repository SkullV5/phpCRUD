<?php
require_once "config.php";
require_once "helpers.php";

$nombre_cancha = "";
$estado_cancha = "";
$obs_cancha = "";
$Disciplina_cod_disciplina = "";

$nombre_cancha_err = "";
$estado_cancha_err = "";
$obs_cancha_err = "";
$Disciplina_cod_disciplina_err = "";

if (isset($_POST["cod_cancha"]) && !empty($_POST["cod_cancha"])) {
    $cod_cancha = $_POST["cod_cancha"];

    $nombre_cancha = trim($_POST["nombre_cancha"]);
    $estado_cancha = trim($_POST["estado_cancha"]);
    $obs_cancha = trim($_POST["obs_cancha"]);
    $Disciplina_cod_disciplina = trim($_POST["Disciplina_cod_disciplina"]);

    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }

    $vars = parse_columns('cancha', $_POST);
    $stmt = $pdo->prepare("UPDATE cancha SET nombre_cancha=?,estado_cancha=?,obs_cancha=?,Disciplina_cod_disciplina=? WHERE cod_cancha=?");

    if (!$stmt->execute([$nombre_cancha, $estado_cancha, $obs_cancha, $Disciplina_cod_disciplina, $cod_cancha])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: cancha-read.php?cod_cancha=$cod_cancha");
    }
} else {
    $_GET["cod_cancha"] = trim($_GET["cod_cancha"]);
    if (isset($_GET["cod_cancha"]) && !empty($_GET["cod_cancha"])) {
        $cod_cancha = trim($_GET["cod_cancha"]);

        $sql = "SELECT * FROM cancha WHERE cod_cancha = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            $param_id = $cod_cancha;

            if (is_int($param_id)) {
                $__vartype = "i";
            } elseif (is_string($param_id)) {
                $__vartype = "s";
            } elseif (is_numeric($param_id)) {
                $__vartype = "d";
            } else {
                $__vartype = "b";
            }
            mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    $nombre_cancha = $row["nombre_cancha"];
                    $estado_cancha = $row["estado_cancha"];
                    $obs_cancha = $row["obs_cancha"];
                    $Disciplina_cod_disciplina = $row["Disciplina_cod_disciplina"];

                } else {
                    header("location: error.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
            }
        }

        mysqli_stmt_close($stmt);

    } else {
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
                                <input type="text" name="nombre_cancha" maxlength="200"class="form-control" value="<?php echo $nombre_cancha; ?>">
                                <span class="form-text"><?php echo $nombre_cancha_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Estado</label>
                                <input type="number" name="estado_cancha" class="form-control" value="<?php echo $estado_cancha; ?>">
                                <span class="form-text"><?php echo $estado_cancha_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Observacion</label>
                                <input type="text" name="obs_cancha" maxlength="1000"class="form-control" value="<?php echo $obs_cancha; ?>">
                                <span class="form-text"><?php echo $obs_cancha_err; ?></span>
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

                        <input type="hidden" name="cod_cancha" value="<?php echo $cod_cancha; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="cancha-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
