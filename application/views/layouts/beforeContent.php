<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?= base_url('/public/assets/vendors/bootstrap-5.2.0-beta1/css/bootstrap.min.css') ?>" rel="stylesheet" crossorigin="anonymous">
	<link href="<?= base_url('/public/assets/styles/dashboard.css') ?>" rel="stylesheet" crossorigin="anonymous">
	<script src="<?= base_url('/public/assets/vendors/bootstrap-5.2.0-beta1/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= base_url('/public/assets/vendors/jquery-3.6.0/jquery-3.6.0.js') ?>"></script>
	<link rel="shortcut icon" href="<?= base_url('/public/assets/images/handshake_filled_icon.png') ?>" type="image/x-icon">
	<title>Dashboard | Koperasi X</title>
</head>

<body>

	<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
		<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Koperasi X</a>
		<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-nav">
			<div class="nav-item text-nowrap">
				<a class="nav-link px-3" href="<?= base_url('/api/auth/logout') ?>">Logout</a>
			</div>
		</div>
	</header>

	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
				<div class="text-center border-bottom">
					<h6 class="m-0 py-4">
						<div class="mb-2">
							<?php
								$ext = array('jpg', 'png', 'jpeg');
								$photo = base_url("public/assets/images/default-user-image.jpeg");
								for ($z = 0; $z < count($ext); $z++) {
									$path = __DIR__ . "/../../../public/assets/images/users/" . $user['user_id'] . "." . $ext[$z];
									if (file_exists($path)) {
										$photo = base_url("public/assets/images/users/" . $user['user_id'] . "." . $ext[$z]);
										break;
									}
								}
							?>
							<img src="<?= $photo ?>" width="50">
						</div>
						<div>
							<span data-feather="user" class="align-text-bottom"></span> <?= $user['fullname'] ?>
						</div>
						<div>
							<sub><?= ucfirst($user['roles']) ?></sub>
						</div>
						<?php if ($user['last_login']) : ?>
							<div>
								<sub class="text-muted">Last Login: <?= $user['last_login'] ?></sub>
							</div>
						<?php endif; ?>
					</h6>
				</div>
				<div class="position-sticky pt-2">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link <?= ($active_page == 'home' ? 'active' : '') ?>" aria-current="page" href="<?= base_url('/dashboard') ?>">
								<span data-feather="home" class="align-text-bottom"></span>
								Home
							</a>
						</li>
						<?php if (in_array($user['roles'], ['anggota'])) : ?>
							<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
								<span>Anggota</span>
							</h6>
							<li class="nav-item">
								<a class="nav-link <?= ($active_page == 'daftar_pinjaman' ? 'active' : '') ?>" href="<?= base_url('/dashboard/pinjaman') ?>">
									<span data-feather="file-text" class="align-text-bottom"></span>
									Daftar Pinjaman
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= ($active_page == 'mohon_pinjaman_baru' ? 'active' : '') ?>" href="<?= base_url('/dashboard/pinjaman_baru') ?>">
									<span data-feather="file" class="align-text-bottom"></span>
									Mohon Pinjaman Baru
								</a>
							</li>
						<?php endif;
						if (in_array($user['roles'], ['agen', 'admin'])) : ?>
							<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
								<span>Agen</span>
							</h6>
							<li class="nav-item">
								<a class="nav-link <?= ($active_page == 'keanggotaan' ? 'active' : '') ?>" href="<?= base_url('/dashboard/keanggotaan') ?>">
									<span data-feather="users" class="align-text-bottom"></span>
									Keanggotaan
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= ($active_page == 'daftar_pinjaman_anggota' ? 'active' : '') ?>" href="<?= base_url('/dashboard/daftar_pinjaman') ?>">
									<span data-feather="file-text" class="align-text-bottom"></span>
									Daftar Pinjaman Anggota
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= ($active_page == 'laporan_agen' ? 'active' : '') ?>" href="<?= base_url('/dashboard/laporan_agen') ?>">
									<span data-feather="bar-chart-2" class="align-text-bottom"></span>
									Laporan
								</a>
							</li>
						<?php endif;
						if (in_array($user['roles'], ['admin'])) : ?>
							<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
								<span>Admin</span>
							</h6>
							<li class="nav-item">
								<a class="nav-link <?= ($active_page == 'agen' ? 'active' : '') ?>" href="<?= base_url('/dashboard/agen') ?>">
									<span data-feather="users" class="align-text-bottom"></span>
									Agen
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= ($active_page == 'laporan_admin' ? 'active' : '') ?>" href="<?= base_url('/dashboard/laporan') ?>">
									<span data-feather="bar-chart-2" class="align-text-bottom"></span>
									Laporan Keuangan
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</nav>
