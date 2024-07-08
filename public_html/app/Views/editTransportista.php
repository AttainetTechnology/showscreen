<style>
    .modal-backdrop.show {
        background-color: #fff3cd;
    }
    .modal-backdrop.show {
        background-color: #fff3cd;
    }
    /* Añadir este estilo para alinear las etiquetas a la izquierda */
    #editTransportForm label {
        text-align: left;
        display: block;
    }
</style>
</style>
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModal">Editar usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='https://dev.showscreen.app/Rutas_transporte/rutas'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="editTransportForm" action="<?= base_url('Rutas_transporte/save') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    
            <div class="form-group">
                    <br>
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($user) ? $user->username : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($user) ? $user->email : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="userfoto">Imagen de usuario</label>
                        <input type="file" class="form-control-file" id="userfoto" name="userfoto">
                        <?php 
                        $maxSize = 1 * 1024 * 1024; // 1MB
                        if (isset($user->imagePath) && file_exists($user->imagePath) && filesize($user->imagePath) <= $maxSize): ?>
                            <img id="userfotoPreview" src="<?= base_url($user->imagePath) ?>" alt="UserFoto" style="width: 20%; height: auto;">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#userfoto').change(function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#userfotoPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);

            var maxSize = 1 * 1024 * 1024; // 1MB
            if (this.files[0].size > maxSize) {
                alert('La imagen es demasiado grande. El tamaño máximo permitido es 1MB.');
                this.value = ''; // Limpiar el campo de archivo
            }
        });
    });
    </script>
