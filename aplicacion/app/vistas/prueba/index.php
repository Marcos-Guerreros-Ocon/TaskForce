<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="<?= RUTA_URL ?>/public/img/logo.png" />

    <title>Task Force</title>

    <!-- Custom fonts for this template-->
    <link href="<?= RUTA_URL ?>/public/css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= RUTA_URL ?>/public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?= RUTA_URL ?>/public/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= RUTA_URL ?>/public/css/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        html {
            font-family: "Nunito";
            --scrollbarBG: #1B1C28;
            --thumbBG: #ffff;

        }

        body {
            box-sizing: border-box;
            margin: 0;
            overflow: auto;
            padding: 0;
            background-color: #FFFF;
            height: 100%;
            width: 100%;
            scrollbar-width: thin;
            scrollbar-color: var(--thumbBG) var(--scrollbarBG);
        }

        body::-webkit-scrollbar {
            width: 11px;
            height: 4px;
        }

        body::-webkit-scrollbar:horizontal {
            height: 4px;
        }

        body::-webkit-scrollbar-track,
        body::-webkit-scrollbar-track:horizontal {
            background: var(--scrollbarBG);
        }

        body::-webkit-scrollbar-thumb,
        body::-webkit-scrollbar-thumb:horizontal {
            background-color: var(--thumbBG);
            border-radius: 6px;
            border: 3px solid var(--scrollbarBG);
        }

        .boards {
            display: inline-flex;
            flex: 1;
            height: 100%;
            width: 100%;
            border-top: 1px solid rgb(212, 212, 212);
        }

        .board {
            background: #F5F5F5;
            margin: 0 .5rem;
            padding: 0px;
            display: flex;
            flex: 1;
            flex-direction: column;
            max-height: 400px;
            overflow-y: auto;
        }

        .board h3 {
            padding: 16px !important;
            min-width: 300px;
            width: 100%;
            margin: 0;
            background-color: #FFFF;
            font-weight: bold;
            font-size: 18px;
            color: #1C1C2E;
        }

        .dropzone {
            padding: 16px;
            min-width: 300px;
            min-height: 200px;
            justify-content: center;
            height: 100%;
        }

        .kanbanCard {
            background-color: #FFFF;
            padding: 16px;
            margin-bottom: 2rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 16px;
        }

        .description {
            color: #434343;
            font-weight: normal;
            font-size: 14px;
        }

        .red {
            border-left: 5px solid #E2163B;
        }

        .purple {
            border-left: 5px solid #4515CF;
        }

        .blue {
            border-left: 5px solid #158CCF;
        }

        .yellow {
            border-left: 5px solid #EFA20C;
        }

        .green {
            border-left: 5px solid #5AD111;
        }

        .highlight {
            background-color: #D7D7D7cc;
        }

        .kanbanCard,
        .dropzone {
            transition: 400ms;
        }

        .is-dragging,
        .darkmode .is-dragging {
            cursor: move !important;
            cursor: -webkit-grabbing;
            opacity: .3;
        }

        .over {
            background: #E9E9E9;
        }

        .form-inline {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        label {
            margin: 0 !important
        }

        input {
            margin: 0rem 1rem 0rem .5rem !important;
        }

        .priority {
            cursor: pointer;
            color: #989898;
        }

        .is-priority,
        .darkmode .is-priority {
            cursor: pointer;
            color: #FF7A00;
        }

        .delete {
            color: #E2163B;
            cursor: pointer;
        }

        .invisibleBtn:focus,
        #theme-btn:focus {
            padding: 0;
            margin: 0;
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            box-shadow: none !important;
        }

        .invisibleBtn {
            padding: 0;
            margin: 0;
            background: none;
            border: none;
            cursor: pointer;
        }

        /* width */
        ::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

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
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="#">Mis tareas</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Mis tareas</h6>
                        </div>
                        <div class="card-body">
                            <div class="boards overflow-auto p-0" id="boardsContainer"></div>

                        </div>
                    </div>

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

    <script src="<?= RUTA_URL ?>/public/css/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= RUTA_URL ?>/public/css/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>

    <script>
        $(document).ready(function() {
            //variables
            let cardBeignDragged;
            let dropzones = document.querySelectorAll('.dropzone');
            let priorities;
            // let cards = document.querySelectorAll('.kanbanCard');
            let dataColors = [{
                    color: "red",
                    title: "Pendiente"
                },
                {
                    color: "yellow",
                    title: "En progreso"
                },
                {
                    color: "green",
                    title: "Completada"
                }
            ];
            let dataCards = {
                'tareas': <?= json_encode($datos['tareas']) ?>
            };

            //initialize

            $(document).ready(() => {
                $("#loadingScreen").addClass("d-none");


                initializeBoards();
                initializeComponents(dataCards.tareas);
                initializeCards();
            });

            //functions
            function initializeBoards() {
                dataColors.forEach(item => {
                    let htmlString = `
        <div class="board">
            <h3 class="text-center">${item.title.toUpperCase()}</h3>
            <div class="dropzone" id="${item.color}">
                
            </div>
        </div>
        `
                    $("#boardsContainer").append(htmlString)
                });
                let dropzones = document.querySelectorAll('.dropzone');
                dropzones.forEach(dropzone => {
                    dropzone.addEventListener('dragenter', dragenter);
                    dropzone.addEventListener('dragover', dragover);
                    dropzone.addEventListener('dragleave', dragleave);
                    dropzone.addEventListener('drop', drop);
                });
            }

            function initializeCards() {
                cards = document.querySelectorAll('.kanbanCard');

                cards.forEach(card => {
                    card.addEventListener('dragstart', dragstart);
                    card.addEventListener('drag', drag);
                    card.addEventListener('dragend', dragend);
                });
            }

            function initializeComponents(dataArray) {
                //create all the stored cards and put inside of the todo area
                dataArray.forEach(card => {
                    appendComponents(card);
                })
            }

            function appendComponents(card) {
                let position;
                //creates new card inside of the todo area
                switch (card.estado) {
                    case 'pendiente':
                        position = "red";
                        break;
                    case 'en_progreso':
                        position = "yellow";
                        break;
                    case 'completada':
                        position = "green";
                        break;
                    default:
                        break;
                }


                let htmlString = `
        <div id="${card.id_tarea}" class="kanbanCard ${position}" draggable="true">
            <div class="content">               
                <h4 class="title">${card.nombre}</h4>
                <p class="description">${card.nombre_tarea}</p>
            </div>
            <form class="row mx-auto justify-content-between">
            </form>
        </div>
    `
                $(`#${position}`).append(htmlString);
                priorities = document.querySelectorAll(".priority");
            }


            function removeClasses(cardBeignDragged, color) {
                cardBeignDragged.classList.remove('red');
                cardBeignDragged.classList.remove('blue');
                cardBeignDragged.classList.remove('purple');
                cardBeignDragged.classList.remove('green');
                cardBeignDragged.classList.remove('yellow');
                cardBeignDragged.classList.add(color);
                position(cardBeignDragged, color);
            }

            function save() {
                // localStorage.setItem('@kanban:data', JSON.stringify(dataCards));
            }

            function position(cardBeignDragged, color) {
                const index = dataCards.tareas.findIndex(card => card.id_tarea === parseInt(cardBeignDragged.id));
                console.log(index);
                dataCards.tareas[index].position = color;
                save();
            }

            //cards
            function dragstart() {
                dropzones.forEach(dropzone => dropzone.classList.add('highlight'));
                this.classList.add('is-dragging');
            }

            function drag() {

            }

            function dragend() {
                dropzones.forEach(dropzone => dropzone.classList.remove('highlight'));
                this.classList.remove('is-dragging');
            }

            // Release cards area
            function dragenter() {

            }

            function dragover({
                target
            }) {
                this.classList.add('over');
                cardBeignDragged = document.querySelector('.is-dragging');
                if (this.id === "yellow") {
                    removeClasses(cardBeignDragged, "yellow");

                } else if (this.id === "green") {
                    removeClasses(cardBeignDragged, "green");
                } else if (this.id === "blue") {
                    removeClasses(cardBeignDragged, "blue");
                } else if (this.id === "purple") {
                    removeClasses(cardBeignDragged, "purple");
                } else if (this.id === "red") {
                    removeClasses(cardBeignDragged, "red");
                }

                this.appendChild(cardBeignDragged);
            }

            function dragleave() {

                this.classList.remove('over');
            }

            function drop() {
                this.classList.remove('over');
            }
        });
    </script>


</body>

</html>