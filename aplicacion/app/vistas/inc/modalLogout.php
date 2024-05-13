<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Quieres salir?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Si seleccionas "Salir" se cerrará la sesión y volverás a la página de inicio.
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-secondary btn-icon-split" data-dismiss="modal">
                    <span class="icon text-white-50"> <i class="fa fa-arrow-left"></i> </span>
                    <span class="text">Volver</span>
                </a>
                <a href="<?= RUTA_URL ?>/usuario/logout" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50"> <i class="fa fa-door-open"></i> </span>
                    <span class="text">Salir</span>
                </a>
            </div>
        </div>
    </div>