<!-- app/Views/edit_user_modal.php -->
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
                <h5 class="modal-title" id="editUserModalLabel">Editar usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='https://dev.showscreen.app/usuarios'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="" method="post"> <!-- La URL se establecerá dinámicamente -->
                    <?php $session = \Config\Services::session(); ?>
                    <?php if($session->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $session->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <input type="hidden" id="userId" name="id" value="<?= isset($user) ? $user->id : '' ?>">
                    <div class="form-group">
                        <label for="nombre_usuario"></label>
                        <input type="text" class="form-control" id="nombre_usuario" readonly>
                        <br>
                        <div class="form-group">
                            <label for="username">Nombre de usuario</label>
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
                        <div class="form-group" id="nivelAccesoGroup">
                        <label for="nivel_acceso">Nivel de acceso</label>
                        <select class="form-control" id="nivel_acceso" name="nivel_acceso" required>
                            <option value="" <?= !isset($nivel_acceso_usuario) || is_null($nivel_acceso_usuario) ? 'selected' : '' ?>>Seleccione un nivel de acceso</option>
                            <?php foreach ($niveles_acceso as $nivel): ?>
                                <option value="<?= $nivel->id_nivel ?>" <?= isset($nivel_acceso_usuario) && $nivel->id_nivel == $nivel_acceso_usuario ? 'selected' : '' ?>><?= $nivel->nombre_nivel ?></option>
                            <?php endforeach; ?>
                        </select>
                        <br>
                    </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
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
        $.get('<?= base_url('Password/getNombreUsuario/') ?>' + id, function(data) {
            $('#nombre_usuario').val(data.nombre_usuario);
            $('#email').val(data.email);
            $('#userId').val(id);
            $('#editUserForm').attr('action', '<?= base_url('password/save/') ?>' + id);
        });

        $.get('<?= base_url('Password/getNivelAcceso/') ?>' + id, function(data) {
            if (data.nivel_acceso) {
                var nivelAcceso = data.nivel_acceso.toString();
                $('#nivel_acceso').val(nivelAcceso).change();
            } else {
                console.error("Error al obtener el nivel de acceso", data.error);
            }
        }).fail(function(error) {
            console.error("Error al obtener el nivel de acceso", error);
        });
    }

    $('#editUserModal').on('hidden.bs.modal', function(e) {
        window.location.href = 'https://dev.showscreen.app/usuarios/';
    });

    $('#editUserModal').modal('show');
});
</script>
<?= $this->endSection() ?>
