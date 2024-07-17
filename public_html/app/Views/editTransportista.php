<style>
    .modal-backdrop.show {
        background-color: #fff3cd;
    }
    /* Añadir este estilo para alinear las etiquetas a la izquierda */
    #editTransportForm label {
        text-align: left;
        display: block;
    }
    .alert-danger{
    text-align: left; /* Justifica el texto a la izquierda */
    /* Otros estilos como color, margen, etc. */
    color: red;
    margin: 10px;
}
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
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <div class="alert alert-danger" style="display: none;"></div>
                <form id="editTransportForm" action="<?= base_url('Rutas_transporte/save') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= old('username', isset($user) ? $user->username : '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', isset($user) ? $user->email : '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números" autocomplete="new-password">
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
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('editTransportForm');
        var passwordInput = document.getElementById('password');
        var errorMessageContainer = document.querySelector('.alert-danger');

        form.addEventListener('submit', function(event) {
            var password = passwordInput.value;
            var errorMessage = '';

            if (password.length > 0) {
                if (password.length < 8) {
                    errorMessage = 'La contraseña debe tener al menos 8 caracteres.';
                } else if (!/[A-Z]/.test(password)) {
                    errorMessage = 'La contraseña debe tener al menos una letra mayúscula.';
                } else if (!/[a-z]/.test(password)) {
                    errorMessage = 'La contraseña debe tener al menos una letra minúscula.';
                } else if (!/[0-9]/.test(password)) {
                    errorMessage = 'La contraseña debe tener al menos un número.';
                }
            }

            if (errorMessage) {
                event.preventDefault();
                errorMessageContainer.style.display = 'block';
                errorMessageContainer.innerHTML = errorMessage;
            }
        });

        var userFotoElement = document.getElementById('userfoto');
        if (userFotoElement) {
            userFotoElement.addEventListener('change', function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var userFotoPreview = document.getElementById('userfotoPreview');
                    if (userFotoPreview) {
                        userFotoPreview.src = e.target.result;
                    }
                }
                reader.readAsDataURL(this.files[0]);

                var maxSize = 1 * 1024 * 1024; // 1MB
                if (this.files[0].size > maxSize) {
                    alert('La imagen es demasiado grande. El tamaño máximo permitido es 1MB.');
                    this.value = ''; // Limpiar el campo de archivo
                }
            });
        }

        var errorMessage = <?= json_encode(session()->getFlashdata('error')) ?>;
        if (errorMessage) {
            errorMessageContainer.style.display = 'block';
            errorMessageContainer.innerHTML = errorMessage;
        }
    });
</script>

