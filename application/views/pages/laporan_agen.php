<?php $this->load->view('layouts/beforeContent', ['active_page' => 'laporan_agen']); ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
	<h3>Laporan Ku</h3>
	<div class="d-flex">
		<div class="card-body pt-4 pb-5">
			<div class="text-center display-1">
				<?= $anggotaDitangani ?>
			</div>
			<div class="text-center">
				Anggota Yang Ditangani
			</div>
		</div>
		<div class="card-body pt-4 pb-5">
			<div class="text-center display-1">
				<?= $anggotaMenunggu ?>
			</div>
			<div class="text-center">
				Anggota Yang Belum Ditangani
			</div>
		</div>
		<div class="card-body py-5">
			<div class="text-center display-5">
				Rp. <?= ($danaBeredar) ? $danaBeredar : 0 ?>,00
			</div>
			<div class="text-center">
				Dana Yang Diedarkan
			</div>
		</div>
	</div>
	<div>
		<h5>Pembayaran</h5>
		Tahun: <input type="number" min="1990" max="<?= date("Y") ?>" value="<?= date("Y") ?>" name="year" id="year">
		<button onclick="updateChart()">Update</button>
		<canvas id="chart" width="400" height="120"></canvas>
	</div>
</main>

<?php $this->load->view('layouts/afterContent'); ?>
<script>
	const ctx = document.getElementById('chart').getContext('2d');
	var chart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			datasets: [{
					label: '# Pinjaman',
					data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
					backgroundColor: ['rgba(255, 99, 132, 0.2)'],
					borderColor: ['rgba(255, 99, 132, 1)'],
					borderWidth: 1,
					tension: 0
				},
				{
					label: '# Angsuran',
					data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
					backgroundColor: ['rgba(54, 162, 235, 0.2)'],
					borderColor: ['rgba(54, 162, 235, 1)'],
					borderWidth: 1,
					tension: 0
				}
			]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});

	updateChart();

	function updateChart() {
		year = $('#year').val();

		// Request data to api
		$.ajax({
			url: '<?= base_url('api/pinjaman/data_graph_pinjaman') ?>',
			type: 'POST',
			data: {
				year,
				submit: true
			},
			success: function(res) {
				// Update chart
				tahun = new Date().getFullYear();
				month = new Date().getMonth();

				data_pinjaman = [];
				data_angsuran = [];
				temp_pinjaman = 0;
				temp_angsuran = 0;
				for (let i = 0; i < chart.data.labels.length; i++) {
					const r = chart.data.labels[i];

					// find index of object value
					const index1 = res.data.pinjaman.findIndex(x => x.bulan.substr(0, 3) == r);
					const index2 = res.data.angsuran.findIndex(x => x.bulan.substr(0, 3) == r);

					if (index1 >= 0) temp_pinjaman += parseInt(res.data.pinjaman[index1].total_pinjaman);
					if (index2 >= 0) temp_angsuran += parseInt(res.data.angsuran[index2].total_angsuran);

					if (tahun == year) {
						if (i <= month) {
							data_pinjaman.push(temp_pinjaman - temp_angsuran);
							data_angsuran.push(temp_angsuran);
						}
					} else {
						data_pinjaman.push(temp_pinjaman - temp_angsuran);
						data_angsuran.push(temp_angsuran);
					}

				}
				chart.data.datasets[0].data = data_pinjaman
				chart.data.datasets[1].data = data_angsuran


				//  = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
				// chart.data.datasets[1].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]

				// for (let i = 0; i < res.length; i++) {
				// 	const r = res[i];

				// 	// search index from array by value jquery
				// 	const index = chart.data.labels.indexOf(r.bulan.substr(0, 3));
				// 	danaBeredar = danaBeredar - r.total_angsuran;
				// 	console.log(danaBeredar);

				// 	chart.data.datasets[0].data[index] = danaBeredar;
				// 	chart.data.datasets[1].data[index] = r.total_angsuran;
				// }

				// // Fix bug when chart is 0 after not zero data



				chart.update();
			}
		});
	}
</script>
