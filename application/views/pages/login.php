<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?= base_url('/public/assets/vendors/bootstrap-5.2.0-beta1/css/bootstrap.min.css') ?>" rel="stylesheet" crossorigin="anonymous">
	<link href="<?= base_url('/public/assets/styles/login.css') ?>" rel="stylesheet" crossorigin="anonymous">
	<link rel="shortcut icon" href="<?= base_url('/public/assets/images/handshake_filled_icon.png') ?>" type="image/x-icon">
	<title>Login | Koperasi X</title>
</head>

<body class="text-center">
	<main class="form-signin w-100 m-auto">
		<form>
			<img class="mb-4" src="<?= base_url('/public/assets/images/handshake_filled_icon.png') ?>" alt="" width="72" height="72">
			<h1 class="h3 mb-3 fw-normal">Koperasi X</h1>
			<h6>Silahkan login terlebih dahulu!</h6>
			<div class="form-floating">
				<input type="text" class="form-control" id="floatingInput" placeholder="Username">
				<label for="floatingInput">Username</label>
			</div>
			<div class="form-floating">
				<input type="password" class="form-control" id="floatingPassword" placeholder="Password">
				<label for="floatingPassword">Password</label>
			</div>
			<button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
			<p class="mt-5 mb-3 text-muted">&copy; 2022</p>
		</form>

		<!-- Toast Section Begin -->
		<div class="toast-container position-fixed top-0 end-0 p-3">
			<div id="liveToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="d-flex">
					<div class="toast-body" id="toast-body"></div>
					<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			</div>
		</div>
		<!-- Toast Section Ended -->
	</main>
</body>
<script src="<?= base_url('/public/assets/vendors/bootstrap-5.2.0-beta1/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('/public/assets/vendors/jquery-3.6.0/jquery-3.6.0.js') ?>"></script>
<script>
	$(document).ready(function() {

		$('form').submit(function(e) {
			e.preventDefault();

			// Get form data
			var username = $('#floatingInput').val();
			var password = $('#floatingPassword').val();

			// Handler login with ajax
			$.ajax({
				url: '<?= base_url('api/auth/check_login') ?>',
				type: 'POST',
				data: {
					username,
					password,
					submit: true
				},
				success: function(r) {
					if (r.status == 'success') {
						window.location.href = '<?= base_url('dashboard') ?>';
					} else {
						$('#toast-body').text(r.message);
						const toast = new bootstrap.Toast($('#liveToast'))
						toast.show()
					}
				}
			});
		});
	});
</script>

</html>
