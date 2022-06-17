<?php $this->load->view('layouts/beforeContent', ['active_page' => 'agen']); ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
	<div class="d-flex justify-content-between">
		<div>
			<h3>Daftar Agen</h3>
			<p>Berikut adalah daftar daftar agen koperasi:</p>
		</div>
		<div>
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
				Tambah Agen Baru
			</button>
		</div>
	</div>
	<div class="card my-3">
		<div class="card-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Photo</th>
						<th scope="col">Nama</th>
						<th scope="col">Jumlah Dana Diedarkan</th>
						<th scope="col">Jumlah Dana Terlunasi</th>
						<th scope="col">Sisa</th>
						<th scope="col">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i = 0; $i < count($agens); $i++) : $r = $agens[$i];
						$ext = array('jpg', 'png', 'jpeg');
						$photo = base_url("public/assets/images/default-user-image.jpeg");
						for ($z = 0; $z < count($ext); $z++) {
							$path = __DIR__ . "/../../../public/assets/images/users/" . $r['user_id'] . "." . $ext[$z];
							if (file_exists($path)) {
								$photo = base_url("public/assets/images/users/" . $r['user_id'] . "." . $ext[$z]);
								break;
							}
						}
					?>
						<tr id="row-<?= $r['user_id'] ?>">
							<th><?= $i + 1 ?></th>
							<td>
								<img src="<?= $photo ?>" height="30">
							</td>
							<td><?= $r['fullname'] ?></td>
							<td>Rp. <?= ($r['jumlah_pinjaman']) ? $r['jumlah_pinjaman'] : 0 ?>,00</td>
							<td>Rp. <?= ($r['jumlah_angsuran']) ? $r['jumlah_angsuran'] : 0 ?>,00</td>
							<td>Rp. <?= $r['jumlah_pinjaman'] - $r['jumlah_angsuran'] ?>,00</td>
							<td>
								<?php if (($r['jumlah_pinjaman'] - $r['jumlah_angsuran']) <= 0) : ?>
									<button class="btn btn-sm text-bg-danger" onclick="hapus(<?= $r['user_id'] ?>)">
										Hapus
									</button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>
			<i>*Dana yang diedarkan harus terlunasi sebelum dapat menghapus agen</i>
		</div>
	</div>
</main>
<!-- Modal Section Begin -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal">Tambah Agen Baru</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<?php echo form_open_multipart('api/user/add'); ?>
			<div class="modal-body">
				<input type="hidden" name="roles" value="agen">
				<input type="hidden" name="submit" value="true">
				<div class="form-group mb-2">
					<label for="fullname">Nama Lengkap
						<span class="text-danger">*</span>
					</label>
					<input type="text" class="form-control" name="fullname" placeholder="Nama Lengkap">
				</div>
				<div class="form-group mb-2">
					<label for="username">Username
						<span class="text-danger">*</span>
					</label>
					<input type="text" class="form-control" name="username" placeholder="Username">
				</div>
				<div class="form-group mb-2">
					<label for="username">Password
						<span class="text-danger">*</span>
					</label>
					<input type="password" class="form-control" name="password" placeholder="********">
				</div>

				<div class="form-group mb-2">
					<label for="photo">Photo
						<span class="text-danger">*</span>
					</label>
					<div class="input-group mb-3">
						<input type="file" class="form-control" id="photo" name="photo">
					</div>
				</div>
				<div class="form-group mb-2">
					<label for="photo">Preview</label>
					<div>
						<img src="<?= base_url('/public/assets/images/default-user-image.jpeg') ?>" id="imgPreview" width="100" height="100">
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal Section Ended -->
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
	allow = false;
	$('#photo').change(function() {
		const file = this.files[0];

		allowedTypes = ['image/jpeg', 'image/png'];

		if (allowedTypes.includes(file.type)) {
			let reader = new FileReader();
			reader.onload = function(event) {
				$('#imgPreview').attr('src', event.target.result);
			}
			reader.readAsDataURL(file);
			allow = true
		} else {
			allow = false
		}
	});

	$('form').submit(function(e) {
		e.preventDefault();

		if (!allow) {
			// Show error message

			$('#liveToast').addClass('text-bg-danger');
			$('#liveToast').removeClass('text-bg-success');
			$('#toast-body').text('Photo tidak diijinkan, format yang didukung: jpg, jpeg, png.');
			const toast = new bootstrap.Toast($('#liveToast'))
			toast.show()

			return false;
		}

		$.ajax({
			url: '<?= base_url('api/user/add/agen') ?>',
			type: "POST",
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
			success: function(r) {

				console.log(r);
				if (r.status == 'success') {
					$('#liveToast').addClass('text-bg-success');
					$('#liveToast').removeClass('text-bg-danger');
					setTimeout(() => {
						// When you too lazy to rerender the page, just reload it lol
						window.location.reload();
					}, 500);
				} else {
					$('#liveToast').addClass('text-bg-danger');
					$('#liveToast').removeClass('text-bg-success');
				}

				// Show toast message
				$('#toast-body').text(r.message);
				const toast = new bootstrap.Toast($('#liveToast'))
				toast.show()
			}
		});

	});

	function hapus(user_id) {
		$.ajax({
			url: '<?= base_url('api/user/delete/agen') ?>',
			type: 'POST',
			data: {
				user_id,
				submit: true
			},
			success: function(r) {
				console.log(r);
				if (r.status == 'success') {
					$('#row-' + user_id).remove();
					$('#liveToast').addClass('text-bg-success');
					$('#liveToast').removeClass('text-bg-danger');
				} else {
					$('#liveToast').addClass('text-bg-danger');
					$('#liveToast').removeClass('text-bg-success');
				}

				// Show toast message
				$('#toast-body').text(r.message);
				const toast = new bootstrap.Toast($('#liveToast'))
				toast.show()
			}
		});
	}
</script>
