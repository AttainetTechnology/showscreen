<!-- app/Views/edit_mi_perfil.php -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    #editUserModal {
        display: none;
    }
</style>

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">MI PERFIL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='<?= base_url() ?>'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="<?= base_url('Mi_perfil/save') ?>" method="post" enctype="multipart/form-data">
                    <?php $session = \Config\Services::session(); ?>
                    <?php if($session->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $session->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="alert alert-danger" id="imageError" style="display: none;"></div>
                    <input type="hidden" id="userId" name="id" value="<?= isset($user) ? $user->id : '' ?>">
                    <div class="form-group">
                        <label for="username">Nombre usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($user) ? $user->username : '' ?>" required autocomplete="off">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($user) ? $user->email : '' ?>" autocomplete="off">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="password">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números" autocomplete="new-password">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="userfoto">Imagen</label>
                        <br>
                        <input type="file" class="form-control-file" id="userfoto" name="userfoto">
                        <?php 
                        $maxSize = 1 * 1024 * 1024; // 1MB
                        if (isset($user->imagePath) && file_exists($user->imagePath) && filesize($user->imagePath) <= $maxSize): ?>
                            <img id="userfotoPreview" src="<?= base_url($user->imagePath) ?>" alt="UserFoto" style="width: 50px; height: auto;">
                        <?php endif; ?>
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    var id = window.location.pathname.split('/').pop();
    if (id) {
        $.get('<?= base_url('Mi_perfil/getUserData/') ?>' + id, function(data) {
            $('#nombre_usuario').val(data.username);
            $('#username').val(data.username);
            $('#email').val(data.email);
            $('#userId').val(id);
            $('#editUserForm').attr('action', '<?= base_url('Mi_perfil/save/') ?>' + id);

            if (data.userfoto) {
                $('#userfotoPreview').attr('src', '<?= base_url() ?>/' + data.userfoto).show();
            }
        }).fail(function(error) {
            console.error("Error al obtener los datos del usuario", error);
        });
    }

    $('#editUserModal').on('hidden.bs.modal', function(e) {
        window.location.href = '<?= base_url() ?>';
    });

    var maxSize = 1 * 1024 * 1024; // 1MB
    var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    $('#userfoto').on('change', function() {
        var file = this.files[0];
        var errorMessage = '';

        if (file.size > maxSize) {
            errorMessage = 'El tamaño de la imagen excede el máximo permitido de 1MB.';
        } else if ($.inArray(file.type, allowedTypes) === -1) {
            errorMessage = 'El formato de la imagen no está permitido. Los formatos permitidos son JPEG, PNG y GIF.';
        }

        if (errorMessage) {
            $('#imageError').text(errorMessage).show();
            this.value = ''; // Limpiar el campo de archivo
        } else {
            $('#imageError').hide();
        }
    });

    $('#editUserModal').modal('show');
});
</script>
<?= $this->endSection() ?>
