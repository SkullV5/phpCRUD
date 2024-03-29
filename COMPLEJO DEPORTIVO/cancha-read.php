<?php
$_GET["cod_cancha"] = trim($_GET["cod_cancha"]);
if (isset($_GET["cod_cancha"]) && !empty($_GET["cod_cancha"])) {
    require_once "config.php";
    require_once "helpers.php";

    $sql = "SELECT * FROM cancha WHERE cod_cancha = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {

        $param_id = trim($_GET["cod_cancha"]);

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
            } else {

                header("location: error.php");
                exit();
            }

        } else {
            echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
        }
    }

    mysqli_stmt_close($stmt);

    mysqli_close($link);
} else {
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ver Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="page-header">
                        <h1>Ver Registro</h1>
                    </div>

                     <div class="form-group">
                            <h4>Nombre</h4>
                            <p class="form-control-static"><?php echo $row["nombre_cancha"]; ?></p>
                        </div><div class="form-group">
                            <h4>Estado</h4>
                            <p class="form-control-static"><?php echo $row["estado_cancha"]; ?></p>
                        </div><div class="form-group">
                            <h4>Observacion</h4>
                            <p class="form-control-static"><?php echo $row["obs_cancha"]; ?></p>
                        </div><div class="form-group">
                            <h4>Codigo de disciplina</h4>
                            <p class="form-control-static"><?php echo $row["Disciplina_cod_disciplina"]; ?></p>
                        </div>

                    <p><a href="cancha-index.php" class="btn btn-primary">Volver</a></p>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>