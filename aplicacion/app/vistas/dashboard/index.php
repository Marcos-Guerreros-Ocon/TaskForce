<?php
$bg = "";

$tareas_porcentaje = 0;
$tareas_completadas = 0;
$tareas_en_progreso = 0;
$tareas_pendientes = 0;
$tareas_completadas = 0;
$tareas_total = 0;
$proyectos = array();
if (isset($datos['datos'])) {
  $tareas_total = $datos['datos']['tareas_total'];
  $tareas_porcentaje = $datos['datos']['tareas_porcentaje'];
  $tareas_completadas = $datos['datos']['tareas_completadas'];
  $tareas_en_progreso = $datos['datos']['tareas_en_progreso'];
  $tareas_pendientes = $datos['datos']['tareas_pendientes'];
  $proyectos = $datos['datos']['misProyectos'];

}

if ($tareas_porcentaje == 100) {
    $bg = "bg-success";
} else if ($tareas_porcentaje < 100  && $tareas_porcentaje >= 50) {
    $bg = "";
} else if ($tareas_porcentaje < 50 && $tareas_porcentaje >= 25) {
    $bg = "bg-warning";
} else {
    $bg = "bg-danger";
}
?>
<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>
<style>
    .chart-area {
        width: 100%;
        margin: auto;
    }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php require_once RUTA_APP . '/vistas/inc/sidebar.php'; ?>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php require_once RUTA_APP . '/vistas/inc/topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Total Tareas -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total de tareas
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $tareas_total ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tareas Completadas -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Tareas Completadas
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $tareas_completadas ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tareas Pendientes -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Tareas Pendientes
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $tareas_pendientes  ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Porcentaje Tareas -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tareas
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?= $tareas_porcentaje ?>%
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar <?= $bg ?>" role="progressbar" style="width: <?= $tareas_porcentaje ?>%" aria-valuenow="<?= $tareas_porcentaje ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Mis tareas por proyeto</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Estado mis tareas</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart" class="chartjs-render-monitor"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Completadas
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> En Progreso
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-warning"></i> Pendientes
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($_SESSION['user']['rol'] !== 'usuario') : ?>
                        <!-- Content Row -->
                        <div class="row">
                            <!-- Content Column -->
                            <div class="col-lg-6 mb-4">
                                <!-- Project Card Example -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Últimos proyectos</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php foreach ($proyectos as $proyecto) : ?>
                                            <?php
                                            $porcentaje = intval($proyecto['porcentaje_completado']);
                                            if ($porcentaje == 100) {
                                                $bg = "bg-success";
                                            } else if ($porcentaje < 100  && $porcentaje >= 50) {
                                                $bg = "";
                                            } else if ($porcentaje < 50 && $porcentaje >= 25) {
                                                $bg = "bg-warning";
                                            } else {
                                                $bg = "bg-danger";
                                            }
                                            ?>
                                            <h4 class="small font-weight-bold"><?= $proyecto['nombre_proyecto'] ?> <span class="float-right"><?= intval($proyecto['porcentaje_completado']) ?>%</span></h4>
                                            <div class="progress mb-4">
                                                <div class="progress-bar <?= $bg ?>" role="progressbar" style="width: <?= $porcentaje ?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        <?php endforeach; ?>
            
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php require_once RUTA_APP . '/vistas/inc/modalLogout.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= RUTA_URL ?>/public/js/jquery/jquery.min.js"></script>
    <script src="<?= RUTA_URL ?>/public/js/bootstrap/bootstrap.bundle.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?= RUTA_URL ?>/public/js/jquery-easing/jquery.easing.min.js"></script>


    <!-- Custom scripts for all pages-->
    <script src="<?= RUTA_URL ?>/public/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const misTareas = <?= json_encode($datos['datos']['mis_tareas']) ?>;
        const nombresProyectos = Object.values(misTareas).map(item => item.nombre_proyecto);
        const cantidades = Object.values(misTareas).map(item => item.cantidad);

        const tareasCompletas = <?= $tareas_completadas ?>;
        const tareasEnProgreso = <?= $tareas_en_progreso ?>;
        const tareasPendientes = <?= $tareas_pendientes ?>;

        const ctx = document.getElementById('myAreaChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nombresProyectos,
                datasets: [{
                    label: 'Cantidad',
                    data: cantidades,
                    backgroundColor: 'rgba(78,115,223, 0.2)',
                    borderColor: 'rgba(78,115,223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }

                    }
                }
            }
        });


        const ctx2 = document.getElementById("myPieChart");
        const myChart2 = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [tareasCompletas, tareasEnProgreso, tareasPendientes],
                    backgroundColor: ['#1cc88a', '#4e73df', '#f6c23e '],
                    hoverBackgroundColor: ['#289f74', '#2d63ff', '#efb31b'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    </script>


</body>

</html>