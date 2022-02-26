<?php
$_GET["cod_cliente"] = trim($_GET["cod_cliente"]);
if (isset($_GET["cod_cliente"]) && !empty($_GET["cod_cliente"])) {
    require_once "config.php";
    require_once "helpers.php";

    $sql = "SELECT * FROM cliente WHERE cod_cliente = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        $param_id = trim($_GET["cod_cliente"]);

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
                            <h4>C.I.</h4>
                            <p class="form-control-static"><?php echo $row["ci_cliente"]; ?></p>
                        </div><div class="form-group">
                            <h4>Nombre</h4>
                            <p class="form-control-static"><?php echo $row["nombre_cliente"]; ?></p>
                        </div><div class="form-group">
                            <h4>Apellido</h4>
                            <p class="form-control-static"><?php echo $row["apellido_cliente"]; ?></p>
                        </div><div class="form-group">
                            <h4>Direccion</h4>
                            <p class="form-control-static"><?php echo $row["dir_cliente"]; ?></p>
                        </div><div class="form-group">
                            <h4>Telefono</h4>
                            <p class="form-control-static"><?php echo $row["tel_cliente"]; ?></p>
                        </div><div class="form-group">
                            <h4>Correo</h4>
                            <p class="form-control-static"><?php echo $row["correo_cliente"]; ?></p>
                        </div><div class="form-group">
                            <h4>Genero</h4>
                            <p class="form-control-static"><?php echo $row["genero_cliente"]; ?></p>
                        </div><div class="form-group">
                            <h4>Fecha de nac.</h4>
                            <p class="form-control-static"><?php echo $row["fn_cliente"]; ?></p>
                        </div>

                    <p><a href="cliente-index.php" class="btn btn-primary">Volver</a></p>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>