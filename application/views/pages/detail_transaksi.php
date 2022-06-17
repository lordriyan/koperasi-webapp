<?php $this->load->view('layouts/beforeContent', ['active_page' => 'daftar_pinjaman_anggota']); ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
	<div class="d-flex justify-content-between">
		<div>
			<h3>Detail Transaksi <?= $transaksi['pinjaman']['fullname'] ?></h3>
			<h6><?= $transaksi['pinjaman']['catatan'] ?></h6>
			<p>Berikut adalah data pembayaran pinjaman:</p>
		</div>
		<div class="h1">
			Rp. <?= $transaksi['pinjaman']['jumlah_permintaan'] ?>
		</div>
	</div>
	<div class="card my-2">
		<div class="card-body">
			<h5>Daftar Transaksi</h5>
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Tanggal</th>
						<th scope="col">Jumlah Pembayaran</th>
						<th scope="col">Sisa Hutang</th>
					</tr>
				</thead>
				<tbody id="tbody">
					<?php
					$sisa = $transaksi['pinjaman']['jumlah_permintaan'];
					for ($i = 0; $i < count($transaksi['pembayaran']); $i++) :
						$row = $transaksi['pembayaran'][$i];
						$sisa = $sisa - $row['jumlah'];
					?>
						<tr row-id="<?= $row['pembayaran_id'] ?>">
							<th><?= $i + 1 ?></th>
							<td><?= date_format(date_create($row['tanggal']), "d/m/Y") ?></td>
							<td>Rp. <?= $row['jumlah'] ?>,00</td>
							<td>Rp. <?= $sisa ?>,00</td>
							<td>
								<button class="btn btn-sm text-bg-danger" onclick="delRow('<?= $row['pembayaran_id'] ?>')">
									Hapus
								</button>
							</td>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="card my-2">
		<div class="card-body">
			<h5>Tambah Transaksi</h5>
			<form>
				<div class="form-group">
					<label for="jumlah_pembayaran">Nominal Pembayaran*</label>
					<div class="input-group mb-3">
						<span class="input-group-text">Rp. </span>
						<input type="number" class="form-control" min="0" id="jumlah_pembayaran" placeholder="12000000">
						<span class="input-group-text">,00</span>
					</div>
				</div>
				<div class="form-group my-3">
					<button class="w-100 btn btn-primary" type="submit">Simpan</button>
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
	function delRow(id) {
		$.ajax({
			url: "<?= base_url('api/pinjaman/delete_transaksi') ?>",
			type: "POST",
			data: {
				pembayaran_id: id,
				submit: true
			},
			success: function(r) {
				if (r.status) {
					$('#liveToast').addClass('text-bg-success');
					$('#liveToast').removeClass('text-bg-danger');
					$('tr[row-id="' + id + '"]').remove();
				} else {
					$('#liveToast').addClass('text-bg-danger');
					$('#liveToast').removeClass('text-bg-success');
				}
				$('#toast-body').text(r.message);
				const toast = new bootstrap.Toast($('#liveToast'))
				toast.show()
			}
		});
	}

	$(document).ready(function() {
		dataCount = <?= count($transaksi['pembayaran']) ?>;
		sisa = <?= $sisa ?>

		$('form').submit(function(e) {
			e.preventDefault();

			// Get form data
			var jumlah_pembayaran = $('#jumlah_pembayaran').val();

			// Check if jumlah_pembayaran is less or equal to sisa
			if (jumlah_pembayaran > sisa) {
				$('#liveToast').addClass('text-bg-danger');
				$('#liveToast').removeClass('text-bg-success');
				$('#toast-body').html('Jumlah pembayaran melebihi sisa hutang');
				const toast = new bootstrap.Toast($('#liveToast'))
				return toast.show()
			}

			// Handler login with ajax
			$.ajax({
				url: '<?= base_url('api/pinjaman/tambah_transaksi') ?>',
				type: 'POST',
				data: {
					jumlah_pembayaran,
					pinjaman_id: <?= $transaksi['pinjaman']['pinjaman_id'] ?>,
					submit: true
				},
				success: function(r) {
					console.log(r);
					if (r.status == 'success') {

						dataCount++;
						sisa = sisa - jumlah_pembayaran;
						$('#liveToast').addClass('text-bg-success');
						$('#liveToast').removeClass('text-bg-danger');

						// Format date to dd-mm-yyyy
						var d = new Date(),
							month = '' + (d.getMonth() + 1),
							day = '' + d.getDate(),
							year = d.getFullYear();

						if (month.length < 2)
							month = '0' + month;
						if (day.length < 2)
							day = '0' + day;

						// Add new row to table
						$('#tbody').append(
							`<tr row-id="${r.data.insert_id}">
								<th>${dataCount}</th>
								<td>${[day, month, year].join('/')}</td>
								<td>Rp. ${jumlah_pembayaran},00</td>
								<td>Rp. ${sisa},00</td>
								<td>
									<button class="btn btn-sm text-bg-danger" onclick="delRow(${r.data.insert_id})">
										Hapus
									</button>
								</td>
							</tr>`);

						// Empty the form
						$('#jumlah_pembayaran').val('');
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

	});
</script>
