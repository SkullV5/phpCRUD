<?php
if (isset($_POST["cod_cliente"]) && !empty($_POST["cod_cliente"])) {
    require_once "config.php";
    require_once "helpers.php";

    $sql = "DELETE FROM cliente WHERE cod_cliente = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        $param_id = trim($_POST["cod_cliente"]);

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
            header("location: cliente-index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
        }
    }

    mysqli_stmt_close($stmt);

    mysqli_close($link);
} else {
    $_GET["cod_cliente"] = trim($_GET["cod_cliente"]);
    if (empty($_GET["cod_cliente"])) {
        header("location: error.php");
        exit();
    }
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
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h1>Eliminar Registro</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div class="alert alert-danger fade-in">
                            <input type="hidden" name="cod_cliente" value="<?php echo trim($_GET["cod_cliente"]); ?>"/>
                            <p>Are you sure you want to delete this record?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="cliente-index.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
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
