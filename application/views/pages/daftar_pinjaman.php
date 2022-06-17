<?php $this->load->view('layouts/beforeContent', ['active_page' => 'daftar_pinjaman_anggota']); ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
	<h3>Daftar Pinjaman Anggota</h3>
	<p>Berikut adalah daftar pinjaman anggota:</p>
	<div class="card my-3">
		<div class="card-body">
			<h5>Pinjaman Yang Ditangani</h5>
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Tanggal</th>
						<th scope="col">Peminjam</th>
						<th scope="col">Jumlah Peminjaman</th>
						<th scope="col">Terlunaskan</th>
						<th scope="col">Sisa Hutang</th>
						<th scope="col">Aksi</th>
					</tr>
				</thead>
				<tbody id="aktif">
					<?php for ($i = 0; $i < count($pinjamansAktif); $i++) : $pinjaman = $pinjamansAktif[$i];
						if (($pinjaman['jumlah_permintaan'] - $pinjaman['total_lunas']) > 0) : ?>
							<tr>
								<th><?= $i + 1 ?></th>
								<td><?= date_format(date_create($pinjaman['tanggal']), "d/m/Y") ?></td>
								<td><?= $pinjaman['anggota_name'] ?></td>
								<td>Rp. <?= $pinjaman['jumlah_permintaan'] ?>,00</td>
								<td>Rp. <?= (empty($pinjaman['total_lunas'])) ? 0 : $pinjaman['total_lunas'] ?>,00
									<span class="badge text-bg-primary">
										<?= number_format($pinjaman['total_lunas'] / $pinjaman['jumlah_permintaan'] * 100, 1)  ?> %
									</span>
								</td>
								<td>Rp. <?= $pinjaman['jumlah_permintaan'] - $pinjaman['total_lunas'] ?>,00</td>
								<td>
									<a href="<?= base_url('/dashboard/detail_transaksi/' . $pinjaman['pinjaman_id']) ?>">
										<button class="btn btn-sm">Lihat Transaksi</button>
									</a>
								</td>
							</tr>
					<?php endif;
					endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="card my-3">
		<div class="card-body">
			<h5>Pinjaman Yang Menunggu Diverifikasi</h5>
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Tanggal</th>
						<th scope="col">Peminjam</th>
						<th scope="col">Jumlah Peminjaman</th>
						<th scope="col">Catatan</th>
						<th scope="col">Status</th>
						<th scope="col">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i = 0; $i < count($pinjamansNonaktif); $i++) : $pinjaman = $pinjamansNonaktif[$i]; if($pinjaman['status'] != 'ditolak'): ?>
						<tr id="row-<?= $pinjaman['pinjaman_id'] ?>">
							<th id="c5"><?= $i + 1 ?></th>
							<td><?= date_format(date_create($pinjaman['tanggal']), "d/m/Y") ?></td>
							<td><?= $pinjaman['fullname'] ?></td>
							<td id="c0">Rp. <?= $pinjaman['jumlah_permintaan'] ?>,00</td>
							<td id="c1"><button class="btn btn-sm" onclick="alert('<?= $pinjaman['catatan'] ?>')"><span data-feather="file" class="align-text-bottom"></span> Lihat</button></td>
							<td id="c2">
								<span class="badge <?= ($pinjaman['status'] == 'ditolak') ? 'text-bg-danger' : 'text-bg-warning' ?>">
									<?= ucfirst($pinjaman['status']) ?>
								</span>
							</td>
							<td id="c4">
								<button class="btn btn-sm" onclick="aksi(<?= $pinjaman['pinjaman_id'] ?>, 'diterima')"><span data-feather="check-circle" class="align-text-bottom text-success"></span></button>
								<button class="btn btn-sm" onclick="aksi(<?= $pinjaman['pinjaman_id'] ?>, 'ditolak')"><span data-feather="x-circle" class="align-text-bottom text-danger"></span></button>
							</td>
						</tr>
					<?php endif; endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
</main>

<?php $this->load->view('layouts/afterContent'); ?>

<script>
	function aksi(pinjaman_id, status) {

		// Request Ajax untuk mengubah status pinjaman
		$.ajax({
			url: '<?= base_url('api/pinjaman/verifikasi') ?>',
			type: 'POST',
			data: {
				pinjaman_id,
				status,
				submit: true
			},
			success: function(res) {
				if (res.status == 'success') {
					// Dumb way to simulate re-rendering the table
					if (status == 'diterima') {
						num = $('#aktif tr:last-child th').text()
						$('#aktif').append($('#row-' + pinjaman_id));
						$('#row-' + pinjaman_id + ' #c1').html(`Rp. 0,00 <span class="badge text-bg-primary">0.0 % </span>`);
						$('#row-' + pinjaman_id + ' #c2').html($('#row-' + pinjaman_id + ' #c0').html());
						$('#row-' + pinjaman_id + ' #c4').html(`<a href="<?= base_url('/dashboard/detail_transaksi/') ?>${pinjaman_id}"><button class="btn btn-sm">Lihat Transaksi</button></a>`);
						$('#row-' + pinjaman_id + ' #c5').html(parseInt(num) + 1);
					} else {
						badge = $('#row-' + pinjaman_id + ' .badge')
						$(badge).text('Ditolak');
						$(badge).removeClass('text-bg-warning');
						$(badge).addClass('text-bg-danger');
					}
				} else {
					alert(res.message);
				}

			}
		});

	}
</script>
