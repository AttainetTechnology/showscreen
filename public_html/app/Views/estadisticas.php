<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?

use App\Models\Menu_familias_model; ?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Panel de Control - Offertiles</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-3 col-md-6">
			<a href="<?php echo site_url('Lista_produccion/pendientes') ?>">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-clock-o fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
									<?
									echo $pendientes;
									?>
								</div>
								<div>Pendientes de material</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">Ver partes</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="<?php echo site_url('Lista_produccion/enmarcha/0') ?>">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-tasks fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
									<?
									echo $en_cola;
									?>
								</div>
								<div>Partes en Cola</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">Ver partes</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="<?php echo site_url('Lista_produccion/enmaquina/0') ?>">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-wrench fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
									<?
									echo $en_maquina;
									?>
								</div>
								<div>Partes en máquina</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">Ver partes</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="<?php echo site_url('Lista_produccion/terminados') ?>">
				<div class="panel panel-success">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-check fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
									<?
									echo $terminados;
									?>
								</div>
								<div>Partes terminados</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">Ver partes</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</div>
		</div>
		</a>
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-8">
			<!-- /.panel -->
			<div class="panel panel-default <? echo $clase; ?>">
				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i> <? echo $titulo; ?>
					<div class="pull-right">
						<div class="btn-group">
							<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
								Otras gráficas
							</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?php echo site_url('Index/pendientes') ?>">En espera de material</a></li>
								<li><a class="dropdown-item" href="<?php echo site_url('Index/enmarcha') ?>">En cola de producción</a></li>
								<li><a class="dropdown-item" href="<?php echo site_url('Index/enmaquina') ?>">En máquina</a></li>
								<li><a class="dropdown-item" href="<?php echo site_url('Index/terminados') ?>">Terminadas</a></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>Sección</th>
											<th>Piezas</th>
											<th>Gráfica</th>
											<?
											if ($nivel > '6') {
											?>
												<th>Importe</th>
											<? } ?>
										</tr>
									</thead>
									<tbody>
										<?
										$linea = "";
										$mayor = "0";
										if (isset($piezasfamilia)) {
											foreach ($piezasfamilia as $pz) :
												if ($mayor < $pz['total_piezas']) :
													$mayor = $pz['total_piezas'];
												endif;
											endforeach;
											foreach ($piezasfamilia as $pz) :
												$ancho = ((100 * $pz['total_piezas']) / $mayor);
												$linea .= "<tr>
                            			<td>" . $pz['nombre'] . "</td>
                            			<td>" . $pz['total_piezas'] . "</td>
        								<td><img src=" . site_url('public/assets/uploads/files/grafico.png') . " height='25px' width='" .  $ancho . "%'></td>";
												if ($nivel > 6) :
													$euros = number_format($pz['total_euros'], 0, ',', '.');
													$linea .= "<td><strong>" . $euros . " €</strong></td>";
												endif;
												$linea .= "</tr>";
											endforeach;
										}
										echo $linea;
										?>
									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.col-lg-4 (nested) -->
					</div>
					<!-- /.row -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->

		</div>
		<!-- /.col-lg-8 -->
		<div class="col-lg-4">
			<? if (isset($rutas)) : ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-truck fa-fw"></i> Rutas de Transporte
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">

						<? echo view('rutas_home');	?>

						<!-- /.list-group -->
					</div>
					<!-- /.panel-body -->
				</div>
			<? endif; ?>
			<!-- /.panel -->

			
			<!-- Tu vista estadisticas.php -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-users fa-fw"></i> Incidencias
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<?php if (!empty($incidencias)) : ?>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Usuario</th>
									<th>Entrada</th>
									<th>Salida</th>
									<th>Incidencia</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($incidencias as $incidencia) : ?>
									<tr>
										<td><?= $incidencia['nombre_usuario'] ?></td>
										<td><?= $incidencia['entrada_hora'] ?></td>
										<td><?= $incidencia['salida_hora'] ?? 'No registrado' ?></td>
										<td><?= $incidencia['incidencia'] ?></td>
										<td>
											<button type="button" class="btn btn-success btn-sm">
												<i class="fa fa-check"></i>
											</button>
											<button type="button" class="btn btn-danger btn-sm">
												<i class="fa fa-times"></i>
											</button>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else : ?>
						<p>No hay incidencias</p>
					<?php endif; ?>
				</div>
				<!-- /.panel-body -->
			</div>




			<!-- /.panel -->
			<!-- /.col-lg-4 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /#page-wrapper -->
	<?= $this->endSection() ?>