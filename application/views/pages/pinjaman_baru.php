<?php $this->load->view('layouts/beforeContent', ['active_page' => 'mohon_pinjaman_baru']); ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
	<h3>Mohon Pinjaman Baru</h3>
	<p>Silahkan diisi isian berikut:</p>
	<div class="card my-3">
		<div class="card-body">
			<form>
				<div class="form-group">
					<label for="jumlah_permintaan">Nominal Pinjaman*</label>
					<div class="input-group mb-3">
						<span class="input-group-text">Rp. </span>
						<input type="number" class="form-control" min="0" id="jumlah_permintaan" placeholder="12000000">
						<span class="input-group-text">,00</span>
					</div>
				</div>
				<div class="form-group">
					<label for="catatan">Catatan*</label>
					<div class="input-group">
						<textarea class="form-control" placeholder="Untuk membeli mobil.." id="catatan"></textarea>
					</div>
				</div>
				<div class="form-group my-3">
					<button class="w-100 btn btn-primary" type="submit">Ajukan</button>
				</div>
				<strong>
					NB: * Harus diisi
				</strong>
			</form>
		</div>
	</div>
</main>
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

<?php $this->load->view('layouts/afterContent'); ?>

<script>
	$(document).ready(function() {

		$('form').submit(function(e) {
			e.preventDefault();

			// Get form data
			var jumlah_permintaan = $('#jumlah_permintaan').val();
			var catatan = $('#catatan').val();

			// Handler login with ajax
			$.ajax({
				url: '<?= base_url('api/pinjaman/tambah') ?>',
				type: 'POST',
				data: {
					jumlah_permintaan,
					catatan,
					submit: true
				},
				success: function(r) {
					console.log(r);
					if (r.status == 'success') {
						$('#liveToast').addClass('text-bg-success');
						$('#liveToast').removeClass('text-bg-danger');

						$('#jumlah_permintaan').val('');
						$('#catatan').val('');
					} else {
						$('#liveToast').addClass('text-bg-danger');
						$('#liveToast').removeClass('text-bg-success');
					}
					$('#toast-body').text(r.message);
					const toast = new bootstrap.Toast($('#liveToast'))
					toast.show()
				}
			});
		});
	});
</script>
