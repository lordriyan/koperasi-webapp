<?php $this->load->view('layouts/beforeContent', ['active_page' => 'home']); ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
	<h2 class="mb-2">Hi <?= $user['fullname'] ?>!</h2>
	<h3>Selamat datang di aplikasi Koperasi X</h3>
	<h6>Silahkan memulai dengan melalui menu disamping kiri!</h6>
	<p class="text-muted">
		Powered by CodeIgniter3 + Bootstrap5 + Jquery + Chart.js + Feather Icons
	</p>
</main>

<?php $this->load->view('layouts/afterContent'); ?>
