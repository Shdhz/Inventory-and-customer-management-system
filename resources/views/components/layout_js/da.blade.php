<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('salesChart').getContext('2d');

        // Inisialisasi Chart.js
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Harian', 'Mingguan', 'Bulanan'],
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: [0, 0, 0], // Data awal
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Fetch data untuk semua tipe
        function fetchAllSalesData() {
            $.ajax({
                url: "{{ route('salesStatistics') }}",
                type: "GET",
                success: function(response) {
                    console.log(response); // Debugging
                    if (response && response.daily !== undefined && response.weekly !== undefined &&
                        response.monthly !== undefined) {
                        // Update data chart
                        chart.data.datasets[0].data = [
                            response.daily || 0,
                            response.weekly || 0,
                            response.monthly || 0
                        ];
                        chart.update(); // Perbarui chart setelah data diubah

                        console.log("Chart updated successfully.");
                    } else {
                        console.error("Invalid response format:", response);
                    }
                },
                error: function() {
                    console.error('Error fetching sales data');
                }
            });
        }
        fetchAllSalesData();
    });

    // Invoice belum lunas
    $(document).ready(function() {
        function loadUnpaidInvoices() {
            $.ajax({
                url: "{{ route('admin.unpaidInvoice') }}",
                type: "GET",
                beforeSend: function() {
                    $('#unpaidCustomersTable').html(
                        '<div class="text-center py-3">' +
                        '<div class="spinner-border text-primary" role="status">' +
                        '<span class="visually-hidden">Memuat...</span>' +
                        '</div>' +
                        '<p class="text-muted mt-2">Memuat data...</p>' +
                        '</div>'
                    );
                },
                success: function(data) {
                    console.log(data); 
                    if (data.length === 0) {
                        $('#unpaidCustomersTable').html(
                            '<p class="text-center text-muted">Semua invoice telah dilunasi. 🎉</p>'
                        );
                    } else {
                        let tableHtml = `
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Nota No</th>
                                        <th>Total Tagihan</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Status Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                        data.forEach((invoice, index) => {
                            let customerName = '-';
                            if (invoice.invoice_details && invoice.invoice_details.length >
                                0) {
                                let firstDetail = invoice.invoice_details[0];
                                if (firstDetail.transaksi_detail &&
                                    firstDetail.transaksi_detail.transaksi &&
                                    firstDetail.transaksi_detail.transaksi.customer_order &&
                                    firstDetail.transaksi_detail.transaksi.customer_order
                                    .draft_customer) {
                                    customerName = firstDetail.transaksi_detail.transaksi
                                        .customer_order.draft_customer.Nama;
                                }
                            } else if (invoice.invoice_form_po && invoice.invoice_form_po
                                .length > 0) {
                                let firstFormPo = invoice.invoice_form_po[0];
                                if (firstFormPo.form_po &&
                                    firstFormPo.form_po.customer_order &&
                                    firstFormPo.form_po.customer_order.draft_customer) {
                                    customerName = firstFormPo.form_po.customer_order
                                        .draft_customer.Nama;
                                }
                            }

                            // Ambil data invoice
                            let notaNo = invoice.nota_no || '-';
                            let total = invoice.total ? parseFloat(invoice.total) : 0;
                            let dueDate = invoice.tenggat_invoice || '-';
                            let statusPembayaran = invoice.status_pembayaran || '-';
                            const tenggatWaktu = new Date(invoice.tenggat_invoice);
                            const options = {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            };
                            const formattedTenggatWaktu = new Intl.DateTimeFormat('id-ID', options).format(tenggatWaktu);

                            tableHtml += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${customerName}</td>
                                <td>${notaNo}</td>
                                <td>Rp ${new Intl.NumberFormat('id-ID').format(total)}</td>
                                <td>${formattedTenggatWaktu}</td>
                                <td>${statusPembayaran}</td>
                            </tr>
                        `;
                        });

                        tableHtml += `
                                </tbody>
                            </table>
                        </div>
                    `;
                        $('#unpaidCustomersTable').html(tableHtml);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error); 
                    $('#unpaidCustomersTable').html(
                        `<p class="text-center text-danger">Gagal memuat data. Error: ${error}</p>`
                    );
                }
            });
        }
        loadUnpaidInvoices();
    });

    // Production plan
    $(document).ready(function() {
        function loadProductionPlan() {
            $.ajax({
                url: "{{ route('admin.getProductionPlan') }}",
                type: "GET",
                beforeSend: function() {
                    $('#productionPlanTable').html(
                        '<div class="text-center py-3">' +
                        '<div class="spinner-border text-primary" role="status">' +
                        '<span class="visually-hidden">Memuat...</span>' +
                        '</div>' +
                        '<p class="text-muted mt-2">Memuat data...</p>' +
                        '</div>'
                    );
                },
                success: function(data) {
                    console.log(data); // Debugging: lihat data yang diterima

                    if (data.length === 0) {
                        $('#productionPlanTable').html(
                            '<p class="text-center text-muted">Tidak ada rencana produksi minggu ini. 🎉</p>'
                        );
                    } else {
                        let tableHtml = `
                        <div class="table-responsive text-center">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Pengrajin</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah Produksi</th>
                                        <th>Mulai Produksi</th>
                                        <th>Berakhir Produksi</th>
                                        <th>Status</th>
                                        <th>Prioritas</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                        // Periksa data dan masukkan ke dalam tabel
                        data.forEach((plan, index) => {
                            const mulaiProduksi = new Date(plan.mulai_produksi);
                            const berakhirProduksi = new Date(plan.berakhir_produksi);
                            const options = {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            };
                            const formattedMulai = new Intl.DateTimeFormat('id-ID', options).format(mulaiProduksi);
                            const formattedBerakhir = new Intl.DateTimeFormat('id-ID',options).format(berakhirProduksi);

                            tableHtml += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${plan.nama_pengrajin}</td>
                                <td>${plan.form_po.keterangan}</td>
                                <td>${plan.form_po.qty}</td>
                                <td>${formattedMulai}</td>
                                <td>${formattedBerakhir}</td>
                                <td>${plan.status}</td>
                                <td>${plan.prioritas}</td>
                            </tr>
                        `;
                        });

                        tableHtml += `
                                </tbody>
                            </table>
                        </div>
                    `;
                        $('#productionPlanTable').html(tableHtml); // Tampilkan tabel
                    }
                },
                error: function(xhr, status, error) {
                    // Tampilkan pesan error jika gagal memuat data
                    console.error("Error:", error); // Debugging: lihat error di console
                    $('#productionPlanTable').html(
                        `<p class="text-center text-danger">Gagal memuat data. Error: ${error}</p>`
                    );
                }
            });
        }

        // Panggil fungsi saat halaman dimuat
        loadProductionPlan();
    });
</script>
