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
                        <h2 class="float-left">Canchas</h2>
                        <a href="cancha-create.php" class="btn btn-success float-right">Añadir registro</a>
                        <a href="cancha-index.php" class="btn btn-info float-right mr-2">Reiniciar vista</a>
                        <a href="index.php" class="btn btn-secondary float-right mr-2">Volver</a>
                    </div>

                    <div class="form-row">
                        <form action="cancha-index.php" method="get">
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

$total_pages_sql = "SELECT COUNT(*) FROM cancha";
$result = mysqli_query($link, $total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

$orderBy = array('cod_cancha', 'nombre_cancha', 'estado_cancha', 'obs_cancha');
$order = 'cod_cancha';
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

$sql = "SELECT * FROM cancha ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
$count_pages = "SELECT * FROM cancha";

if (!empty($_GET['search'])) {
    $search = ($_GET['search']);
    $sql = "SELECT * FROM cancha
                            WHERE CONCAT_WS (cod_cancha,nombre_cancha,estado_cancha,obs_cancha)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
    $count_pages = "SELECT * FROM cancha
                            WHERE CONCAT_WS (cod_cancha,nombre_cancha,estado_cancha,obs_cancha)
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
        echo "<th><a href=?search=$search&sort=&order=cod_cancha&sort=$sort>ID</th>";
        echo "<th><a href=?search=$search&sort=&order=nombre_cancha&sort=$sort>Nombre</th>";
        echo "<th><a href=?search=$search&sort=&order=estado_cancha&sort=$sort>Estado</th>";
        echo "<th><a href=?search=$search&sort=&order=obs_cancha&sort=$sort>Observacion</th>";

        echo "<th>Acciones</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['cod_cancha'] . "</td>";
            echo "<td>" . $row['nombre_cancha'] . "</td>";
            echo "<td>" . $row['estado_cancha'] . "</td>";
            echo "<td>" . $row['obs_cancha'] . "</td>";
            echo "<td>";
            echo "<a href='cancha-read.php?cod_cancha=" . $row['cod_cancha'] . "' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
            echo "<a href='cancha-update.php?cod_cancha=" . $row['cod_cancha'] . "' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
            echo "<a href='cancha-delete.php?cod_cancha=" . $row['cod_cancha'] . "' title='Eliminar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
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