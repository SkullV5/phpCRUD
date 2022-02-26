<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6b773fe9e4.js" crossorigin="anonymous"></script>
    <style type="text/css">
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 5px;
        }
        body {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="float-left">Clientes</h2>
                        <a href="cliente-create.php" class="btn btn-success float-right">Añadir registro</a>
                        <a href="cliente-index.php" class="btn btn-info float-right mr-2">Reiniciar vista</a>
                        <a href="index.php" class="btn btn-secondary float-right mr-2">Volver</a>
                    </div>

                    <div class="form-row">
                        <form action="cliente-index.php" method="get">
                        <div class="col">
                          <input type="text" class="form-control" placeholder="Buscar en esta tabla" name="search">
                        </div>
                    </div>
                        </form>
                    <br>

    <?php
require_once "config.php";
require_once "helpers.php";

$protocol = $_SERVER['SERVER_PROTOCOL'];
$domain = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https')
=== false ? 'http' : 'https';
$currenturl = $protocol . '://' . $domain . $script . '?';

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$offset = ($pageno - 1) * $no_of_records_per_page;

$total_pages_sql = "SELECT COUNT(*) FROM cliente";
$result = mysqli_query($link, $total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

$orderBy = array('cod_cliente', 'ci_cliente', 'nombre_cliente', 'apellido_cliente', 'dir_cliente', 'tel_cliente', 'correo_cliente', 'genero_cliente', 'fn_cliente');
$order = 'cod_cliente';
if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
    $order = $_GET['order'];
}

$sortBy = array('asc', 'desc');
$sort = 'desc';
if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {
    if ($_GET['sort'] == 'asc') {
        $sort = 'desc';
    } else {
        $sort = 'asc';
    }
}

$sql = "SELECT * FROM cliente ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
$count_pages = "SELECT * FROM cliente";

if (!empty($_GET['search'])) {
    $search = ($_GET['search']);
    $sql = "SELECT * FROM cliente
                            WHERE CONCAT_WS (cod_cliente,ci_cliente,nombre_cliente,apellido_cliente,dir_cliente,tel_cliente,correo_cliente,genero_cliente,fn_cliente)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
    $count_pages = "SELECT * FROM cliente
                            WHERE CONCAT_WS (cod_cliente,ci_cliente,nombre_cliente,apellido_cliente,dir_cliente,tel_cliente,correo_cliente,genero_cliente,fn_cliente)
                            LIKE '%$search%'
                            ORDER BY $order $sort";
} else {
    $search = "";
}

if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        if ($result_count = mysqli_query($link, $count_pages)) {
            $total_pages = ceil(mysqli_num_rows($result_count) / $no_of_records_per_page);
        }
        $number_of_results = mysqli_num_rows($result_count);
        echo " " . $number_of_results . " resultados - Pagina " . $pageno . " de " . $total_pages;

        echo "<table class='table table-bordered table-striped'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th><a href=?search=$search&sort=&order=cod_cliente&sort=$sort>ID</th>";
        echo "<th><a href=?search=$search&sort=&order=ci_cliente&sort=$sort>C.I.</th>";
        echo "<th><a href=?search=$search&sort=&order=nombre_cliente&sort=$sort>Nombre</th>";
        echo "<th><a href=?search=$search&sort=&order=apellido_cliente&sort=$sort>Apellido</th>";
        echo "<th><a href=?search=$search&sort=&order=dir_cliente&sort=$sort>Direccion</th>";
        echo "<th><a href=?search=$search&sort=&order=tel_cliente&sort=$sort>Telefono</th>";
        echo "<th><a href=?search=$search&sort=&order=correo_cliente&sort=$sort>Correo</th>";
        echo "<th><a href=?search=$search&sort=&order=genero_cliente&sort=$sort>Genero</th>";
        echo "<th><a href=?search=$search&sort=&order=fn_cliente&sort=$sort>Fecha de nac.</th>";

        echo "<th>Acciones</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['cod_cliente'] . "</td>";
            echo "<td>" . $row['ci_cliente'] . "</td>";
            echo "<td>" . $row['nombre_cliente'] . "</td>";
            echo "<td>" . $row['apellido_cliente'] . "</td>";
            echo "<td>" . $row['dir_cliente'] . "</td>";
            echo "<td>" . $row['tel_cliente'] . "</td>";
            echo "<td>" . $row['correo_cliente'] . "</td>";
            echo "<td>" . $row['genero_cliente'] . "</td>";
            echo "<td>" . $row['fn_cliente'] . "</td>";
            echo "<td>";
            echo "<a href='cliente-read.php?cod_cliente=" . $row['cod_cliente'] . "' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
            echo "<a href='cliente-update.php?cod_cliente=" . $row['cod_cliente'] . "' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
            echo "<a href='cliente-delete.php?cod_cliente=" . $row['cod_cliente'] . "' title='Eliminar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        ?>
                                <ul class="pagination" align-right>
                                <?php
$new_url = preg_replace('/&?pageno=[^&]*/', '', $currenturl);
        ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo $new_url . '&pageno=1' ?>">⏪</a></li>
                                    <li class="page-item <?php if ($pageno <= 1) {echo 'disabled';}?>">
                                        <a class="page-link" href="<?php if ($pageno <= 1) {echo '#';} else {echo $new_url . "&pageno=" . ($pageno - 1);}?>">◀</a>
                                    </li>
                                    <li class="page-item <?php if ($pageno >= $total_pages) {echo 'disabled';}?>">
                                        <a class="page-link" href="<?php if ($pageno >= $total_pages) {echo '#';} else {echo $new_url . "&pageno=" . ($pageno + 1);}?>">▶</a>
                                    </li>
                                    <li class="page-item <?php if ($pageno >= $total_pages) {echo 'disabled';}?>">
                                        <a class="page-item"><a class="page-link" href="<?php echo $new_url . '&pageno=' . $total_pages; ?>">⏩</a>
                                    </li>
                                </ul>
<?php
mysqli_free_result($result);
    } else {
        echo "<p class='lead'><em>No se encontraron registros.</em></p>";
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

mysqli_close($link);
?>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>