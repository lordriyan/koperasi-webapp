<?php $this->load->view('layouts/beforeContent', ['active_page' => 'laporan_admin']); ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
	<h3>Laporan Keuangan</h3>
	<div class="d-flex">
		<div class="card-body py-5">
			<div class="text-center display-5 text-danger">
				Rp. <?= ($dana_diedarkan) ? $dana_diedarkan : 0 ?>,00
			</div>
			<div class="text-center">
				<h6>Dana Yang Diedarkan</h6>
			</div>
		</div>
		<div class="card-body py-5">
			<div class="text-center display-5 text-primary">
				<?= number_format($dana_dilunasi / $dana_diedarkan * 100, 1) ?> %
			</div>
		</div>
		<div class="card-body py-5">
			<div class="text-center display-5 text-success">
				Rp. <?= ($dana_dilunasi) ? $dana_dilunasi : 0 ?>,00
			</div>
			<div class="text-center">
				<h6>Dana Yang Dilunasi</h6>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<h5>Anggota Peminjam Terbanyak</h5>
			<div class="card-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>No</th>
							<th>Photo</th>
							<th>Nama</th>
							<th>Jumlah Pinjaman</th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; for ($i = 0; $i < count($top_anggota); $i++) : $r = $top_anggota[$i];
							$ext = array('jpg', 'png', 'jpeg');
							$photo = base_url("public/assets/images/default-user-image.jpeg");
							for ($z = 0; $z < count($ext); $z++) {
								$path = __DIR__ . "/../../../public/assets/images/users/" . $r['user_id'] . "." . $ext[$z];
								if (file_exists($path)) {
									$photo = base_url("public/assets/images/users/" . $r['user_id'] . "." . $ext[$z]);
									break;
								}
							}
							$total = $total + $r['jumlah_pinjaman'];
						?>
							<tr>
								<th><?= $i + 1 ?></th>
								<td>
									<img src="<?= $photo ?>" width="30">
								</td>
								<td><?= $r['fullname'] ?></td>
								<td>Rp. <?= $r['jumlah_pinjaman'] ?>,00</td>
							</tr>
						<?php endfor; ?>
						<tr>
							<th></th>
							<td></td>
							<td></td>
							<th>Rp. <?= $total ?>,00</th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col">
			<h5>Agen Pengedar Terbanyak</h5>
			<div class="card-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>No</th>
							<th>Photo</th>
							<th>Nama</th>
							<th>Jumlah Pinjaman</th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; for ($i = 0; $i < count($top_agen); $i++) : $r = $top_agen[$i];
							$ext = array('jpg', 'png', 'jpeg');
							$photo = base_url("public/assets/images/default-user-image.jpeg");
							for ($z = 0; $z < count($ext); $z++) {
								$path = __DIR__ . "/../../../public/assets/images/users/" . $r['user_id'] . "." . $ext[$z];
								if (file_exists($path)) {
									$photo = base_url("public/assets/images/users/" . $r['user_id'] . "." . $ext[$z]);
									break;
								}
							}
							$total = $total + $r['jumlah_pinjaman'];
						?>
							<tr>
								<th><?= $i + 1 ?></th>
								<td>
									<img src="<?= $photo ?>" width="30">
								</td>
								<td><?= $r['fullname'] ?></td>
								<td>Rp. <?= $r['jumlah_pinjaman'] ?>,00</td>
							</tr>
						<?php endfor; ?>
						<tr>
							<th></th>
							<td></td>
							<td></td>
							<th>Rp. <?= $total ?>,00</th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</main>

<?php $this->load->view('layouts/afterContent'); ?>
