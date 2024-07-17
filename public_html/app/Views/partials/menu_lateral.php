    <a class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none" href="<?php echo site_url('/Index/'); ?>">
        <img src="<?php 
              $session = session();
              $session_data = $session->get('logged_in');
              $id_empresa = $session_data['id_empresa']; 
              echo base_url('public/assets/uploads/files/' . $url_logo);
            ?>" class="logo_app">
    </a>

    <!-- Menú de navegación -->
    <div class="collapse show" id="navbarToggleExternalContent">
        <div class="col-xs-12">
            <div class="flex-shrink-0 p-3 col-xs-12 bg-white">

                <!-- Inicio del menú desplegable -->
                <div class="accordion" id="menuAccordion">
                    <!-- Iterando sobre los elementos del menú -->
                    <?php foreach ($menu as $index => $menuItem) : ?>
                        <?php 
                        $nivelUsuario = control_login();
                        if ($menuItem['activo'] != 0 && $nivelUsuario >= $menuItem['nivel']) : ?>
                    
                    <?php
                        // Comprobando si el elemento actual del menú está activo
                        $isActive = array_reduce($menuItem['submenu'], function ($carry, $sub) use ($currentPage) {
                            $sub['enlace'] = rtrim(strtolower($sub['enlace']), '/');
                            return $carry || $currentPage == $sub['enlace'] || (isset($sub['submenu']) && array_reduce($sub['submenu'], function ($carry, $subSub) use ($currentPage) {
                                $subSub['enlace'] = rtrim(strtolower($subSub['enlace']), '/');
                                return $carry || $currentPage == $subSub['enlace'];
                            }, false));
                        }, false);


                        ?>
                        <!-- Elemento del menú -->
                        <div class="accordion-item" id="menu-item-<?= $index ?>">
                            <h2 class="accordion-header" id="heading<?= $index ?>">
                                <!-- Botón para desplegar o colapsar el elemento del menú -->
                                <button class="accordion-button <?= !$isActive ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="<?= $isActive ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                                    <?= $menuItem['titulo']; ?>
                                </button>
                            </h2>
                            <!-- Contenido del elemento del menú -->
                            <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $isActive ? 'show' : '' ?>" aria-labelledby="heading<?= $index ?>" data-bs-parent="#menuAccordion">
                                <div class="accordion-body">
                                    <!-- Submenú si existe -->
                                    <?php if (!empty($menuItem['submenu'])) : ?>
                                        <ul class="submenu list-unstyled">
                                            <!-- Iterando sobre los elementos del submenú -->
                                            <?php foreach ($menuItem['submenu'] as $subIndex => $subMenuItem) : ?>
                                                <!-- Filtro menu por activo y nivel  -->
                                                <?php 
                                                  $nivelUsuario = control_login();
                                                if ($subMenuItem['activo'] != 0 && $nivelUsuario >= $subMenuItem['nivel']) : ?>
                                                <?php
                                                // Comprobando si el submenú está activo
                                                $subMenuItem['enlace'] = rtrim(strtolower($subMenuItem['enlace']), '/');
                                                $isActiveSubMenu = $currentPage == $subMenuItem['enlace'] || (isset($subMenuItem['submenu']) && array_reduce($subMenuItem['submenu'], function ($carry, $subSub) use ($currentPage) {
                                                    $subSub['enlace'] = rtrim(strtolower($subSub['enlace']), '/');
                                                    return $carry || $currentPage == $subSub['enlace'];
                                                }, false));
                                                ?>
                                                <!-- Elemento del submenú -->
                                                <li>
                                                    <?php if (empty($subMenuItem['submenu'])) : ?>
                                                        <!-- Enlace del submenú -->
                                                        <a href="<?= base_url($subMenuItem['enlace']); ?>" class="submenu-link <?= str_replace(' ', '-', strtolower($subMenuItem['titulo'])); ?>" <?= $subMenuItem['nueva_pestana'] == '1' ? 'target="_blank"' : '' ?>>
                                                            <?= $subMenuItem['titulo']; ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <!-- Enlace del submenú con subelementos -->
                                                        <a href="#subMenuAccordion<?= $index . $subIndex ?>" data-bs-toggle="collapse" class="d-block <?= $isActiveSubMenu ? 'active' : ''; ?>" <?= $subMenuItem['nueva_pestana'] == '1' ? 'target="_blank"' : '' ?>>
                                                            <?= $subMenuItem['titulo']; ?> <i class="fa fa-caret-down float-end"></i>
                                                        </a>
                                                        <!-- Submenú desplegable -->
                                                        <ul class="collapse <?= $isActiveSubMenu ? 'show' : '' ?>" id="subMenuAccordion<?= $index . $subIndex ?>">
                                                            <!-- Iterando sobre los elementos del submenú -->
                                                            <?php foreach ($subMenuItem['submenu'] as $subSubMenuItem) : ?>
                                                                <?php 
                                                                    $nivelUsuario = control_login();
                                                                    if ($subSubMenuItem['activo'] != 0 && $nivelUsuario >= $subSubMenuItem['nivel']) : 
                                                                ?>
                                                                    <li>
                                                                        <!-- Enlace del submenú secundario -->
                                                                        <a href="<?= base_url($subSubMenuItem['enlace']); ?>" class="<?= $currentPage == $subSubMenuItem['enlace'] ? 'active' : ''; ?>" <?= $subSubMenuItem['nueva_pestana'] == '1' ? 'target="_blank"' : '' ?>>
                                                                            <?= $subSubMenuItem['titulo']; ?>
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                
                <?php
                $datos = new \App\Models\Usuarios2_Model();
                $data=usuario_sesion();
                $id_empresa=$data['id_empresa'];
                $dbConnectionsModel = new \App\Models\DbConnectionsModel();
                // Obtiene el NIF de la empresa
                $nif = $dbConnectionsModel->getNIF($id_empresa);
                if ($nif === null) {
                    die('No se encontró un NIF para el id_empresa proporcionado');
                }
                $url = "https://dev.showscreen.app/presentes/" . $nif;
                ?>

                <ul class="nav nav-second-level">
		        <a href="<?php echo $url; ?>" target="_blank"><i class="fa fa-hand-o-up fa-fw"></i> FICHAR <i class="fa fa-external-link" aria-hidden="true"></i></a>
		        </ul>

                <div class="dropdown mt-3">
                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php 
                        $imagePath = "public/assets/uploads/files/" . $id_empresa . "/usuarios/" . $userfoto;
                        $fullPath = FCPATH . $imagePath;                      
                        if (!empty($userfoto) && file_exists($fullPath)) : ?>
                            <img src="<?= base_url($imagePath) ?>" alt="" width="auto" height="32" class="miperfil me-2">
                        <?php endif; ?>
                        <strong>
                            <?= $nombre_usuario . " " . $apellidos_usuario; ?></strong>
                    </a>
                    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                        <li><a class="dropdown-item" href="<?php echo site_url('/Mi_perfil#/edit/') . $id_user; ?>">Mi perfil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <?php if ($nivel == 9) : ?>
                            <li><a class="dropdown-item" href="<?php echo site_url('/Select_empresa/'); ?>">Cambiar de empresa</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        <?php endif; ?>
                        <li><a href="<?php echo site_url('/home/logout') ?>" class="dropdown-item">Salir <i class="fa fa-sign-out fa-fw"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="footer_menulateral">
        &copy; ATTAINET TECHNOLOGY <?php echo date("Y"); ?>
    </div>

    <script>
        $(document).ready(function() {
            $('.accordion-item').on('hide.bs.collapse', function() {
                var id = $(this).attr('id');
                localStorage.setItem(id, 'collapsed');
            });

            $('.accordion-item').on('show.bs.collapse', function() {
                var id = $(this).attr('id');
                localStorage.setItem(id, 'expanded');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Comprobando si estamos en la página de inicio
            var isHomePage = window.location.href === 'https://dev.showscreen.app/index.php/Index';
            // Si estamos en la página de inicio, borramos el estado almacenado
            if (isHomePage) {
                localStorage.clear();
            }
            $('.accordion-item').each(function() {
                var id = $(this).attr('id');
                var state = localStorage.getItem(id);
                if (state === 'expanded') {
                    $(this).find('.accordion-collapse').addClass('show');
                    $(this).find('.accordion-button').removeClass('collapsed');
                } else if (state === 'collapsed') {
                    $(this).find('.accordion-collapse').removeClass('show');
                    $(this).find('.accordion-button').addClass('collapsed');
                }
            });
        });
    </script>


    <script>
        // Obteniendo la URL de la página actual
        var currentPage = window.location.href;
        // Obteniendo todos los enlaces de submenú
        var submenuLinks = document.querySelectorAll('.submenu-link');
        // Iterando sobre los enlaces de submenú
        for (var i = 0; i < submenuLinks.length; i++) {
            // Obteniendo la URL del enlace de submenú
            var submenuLink = submenuLinks[i].href;
            // Si la URL de la página actual es igual a la URL del enlace de submenú, entonces se añade la clase 'active'
            if (currentPage === submenuLink) {
                submenuLinks[i].classList.add('active');
            }
        }
    </script>