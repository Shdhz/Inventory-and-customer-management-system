
# Sistem Manajemen Data Inventaris dan Pelanggan

Sistem ini dirancang untuk mengelola inventaris produk dan data pelanggan, serta melacak transaksi penjualan dan pembelian.

## Fitur Utama

- **Manajemen Produk**: Menambahkan, memperbarui, dan menghapus informasi produk dalam inventaris.
- **Manajemen Pelanggan**: Menyimpan dan mengelola informasi pelanggan.
- **Manajemen Transaksi**: Mencatat dan melacak penjualan dan pembelian produk.
- **Manajemen rencana produksi**: Membuat jadwal rencana produksi oleh tim produksi.
- **Laporan dan Analitik**: Menghasilkan laporan tentang status inventaris, aktivitas penjualan, dan data pelanggan.


## Instalasi

1. **Kloning Repositori**:

   ```bash
   git clone https://github.com/Shdhz/Inventory-and-customer-management-system.git
   ```

2. **Masuk ke Direktori Proyek**:

   ```bash
   cd Inventory-and-customer-management-system
   ```

3. **Instal Dependensi**:

   ```bash
   composer install
   npm install
   ```

4. **Salin File Konfigurasi Lingkungan**:

   ```bash
   cp .env.example .env
   ```

5. **Generate Kunci Aplikasi**:

   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Basis Data**:

   Edit file `.env` dan sesuaikan pengaturan basis data:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=username_anda
   DB_PASSWORD=password_anda
   ```

7. **Migrasi Basis Data**:

   ```bash
   php artisan migrate
   ```

8. **Jalankan Server Pengembangan**:

   ```bash
   php artisan serve
   ```

   Aplikasi akan berjalan di `http://localhost:8000`.


## Kontribusi

Kontribusi sangat dihargai. Silakan buat *issue* atau kirim *pull request* untuk perbaikan atau fitur baru.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

 
