<?php $this->load->view('layouts/beforeContent', ['active_page' => 'daftar_pinjaman']); ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
	<h3>Daftar Pinjamanku</h3>
	<p>Berikut adalah daftar pinjamanmu:</p>
	<div class="card my-3">
		<div class="card-body">
			<h5>Pinjaman Aktif</h5>
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Tanggal</th>
						<th scope="col">Agen Yang Menangani</th>
						<th scope="col">Jumlah Peminjaman</th>
						<th scope="col">Terlunaskan</th>
						<th scope="col">Sisa Hutang</th>
						<th scope="col">Catatan</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i = 0; $i < count($pinjamansAktif); $i++) :
						$pinjaman = $pinjamansAktif[$i];
						if ($pinjaman['status'] == "diterima") : ?>
							<tr>
								<th><?= $i + 1 ?></th>
								<td><?= date_format(date_create($pinjaman['tanggal']), "d/m/Y") ?></td>
								<td><?= $pinjaman['agen_name'] ?></td>
								<td>Rp. <?= $pinjaman['jumlah_permintaan'] ?>,00</td>
								<td>Rp. <?= $pinjaman['total_lunas'] ?>,00
									<span class="badge text-bg-primary">
										<?= number_format($pinjaman['total_lunas'] / $pinjaman['jumlah_permintaan'] * 100, 1)  ?> %
									</span>
								</td>
								<td>Rp. <?= $pinjaman['jumlah_permintaan'] - $pinjaman['total_lunas'] ?>,00</td>
								<td><button class="btn btn-sm" onclick="alert('<?= $pinjaman['catatan'] ?>')"><span data-feather="file" class="align-text-bottom"></span> Lihat</button></td>
							</tr>
					<?php endif;
					endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="card my-3">
		<div class="card-body">
			<h5>Permohonan Pinjaman</h5>
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Tanggal</th>
						<th scope="col">Jumlah Peminjaman</th>
						<th scope="col">Status</th>
						<th scope="col">Catatan</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i = 0; $i < count($pinjamansNonaktif); $i++) :
						$pinjaman = $pinjamansNonaktif[$i];
						if ($pinjaman['status']) : ?>
							<tr>
								<th><?= $i + 1 ?></th>
								<td><?= date_format(date_create($pinjaman['tanggal']), "d/m/Y") ?></td>
								<td>Rp. <?= $pinjaman['jumlah_permintaan'] ?>,00</td>
								<td>
									<span class="badge <?= ($pinjaman['status'] == 'ditolak') ? 'text-bg-danger' : 'text-bg-warning' ?>">
										<?= ucfirst($pinjaman['status']) ?>
									</span>
								</td>
								<td><button class="btn btn-sm" onclick="alert('<?= $pinjaman['catatan'] ?>')"><span data-feather="file" class="align-text-bottom"></span> Lihat</button></td>
							</tr>
					<?php endif;
					endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
</main>

<?php $this->load->view('layouts/afterContent'); ?>
