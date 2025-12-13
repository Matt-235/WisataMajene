<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wisata;
use App\Models\Booking;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        User::truncate();
        Wisata::truncate();
        Booking::truncate();
        Ulasan::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // ==================== BUAT USERS ====================
        
        // 1. Buat Admin
        $admin = User::create([
            'nama' => 'Administrator',
            'email' => 'admin@wisatamajene.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status_verifikasi' => 'terverifikasi',
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Kantor Pusat No. 1, Majene',
            'created_at' => Carbon::now()->subMonths(6),
        ]);
        
        // 2. Buat Pengelola Wisata (Terverifikasi)
        $pengelola1 = User::create([
            'nama' => 'Budi Santoso',
            'email' => 'pengelola1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola_wisata',
            'status_verifikasi' => 'terverifikasi',
            'no_telepon' => '081234567891',
            'alamat' => 'Desa Manakarra, Majene',
            'created_at' => Carbon::now()->subMonths(5),
        ]);
        
        $pengelola2 = User::create([
            'nama' => 'Siti Aminah',
            'email' => 'pengelola2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola_wisata',
            'status_verifikasi' => 'terverifikasi',
            'no_telepon' => '081234567892',
            'alamat' => 'Kelurahan Tammangalle, Majene',
            'created_at' => Carbon::now()->subMonths(4),
        ]);
        
        // 3. Buat Pengelola Wisata (Belum Terverifikasi)
        User::create([
            'nama' => 'Joko Susilo',
            'email' => 'pengelola3@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola_wisata',
            'status_verifikasi' => 'belum_terverifikasi',
            'no_telepon' => '081234567893',
            'alamat' => 'Kecamatan Banggae, Majene',
            'created_at' => Carbon::now()->subMonths(1),
        ]);
        
        // 4. Buat Wisatawan
        $wisatawan1 = User::create([
            'nama' => 'Ahmad Fauzi',
            'email' => 'wisatawan1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'wisatawan',
            'status_verifikasi' => 'terverifikasi',
            'no_telepon' => '081234567894',
            'alamat' => 'Jl. Sudirman No. 45, Makassar',
            'created_at' => Carbon::now()->subMonths(3),
        ]);
        
        $wisatawan2 = User::create([
            'nama' => 'Rina Melati',
            'email' => 'wisatawan2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'wisatawan',
            'status_verifikasi' => 'terverifikasi',
            'no_telepon' => '081234567895',
            'alamat' => 'Jl. Gatot Subroto No. 23, Palu',
            'created_at' => Carbon::now()->subMonths(2),
        ]);
        
        $wisatawan3 = User::create([
            'nama' => 'Dewi Sartika',
            'email' => 'wisatawan3@example.com',
            'password' => Hash::make('password123'),
            'role' => 'wisatawan',
            'status_verifikasi' => 'terverifikasi',
            'no_telepon' => '081234567896',
            'alamat' => 'Kompleks Permata Indah, Mamuju',
            'created_at' => Carbon::now()->subMonths(1),
        ]);
        
        // ==================== BUAT WISATA ====================
        
        $wisataData = [
            [
                'nama_wisata' => 'Pantai Manakarra',
                'deskripsi' => 'Pantai dengan pasir putih yang lembut dan pemandangan sunset yang memukau. Air lautnya jernih dan tenang, cocok untuk berenang dan bersantai. Dilengkapi dengan area kuliner seafood yang menyajikan ikan bakar segar langsung dari nelayan.',
                'lokasi' => 'Desa Manakarra, Kecamatan Sendana, Kabupaten Majene',
                'latitude' => -3.5516,
                'longitude' => 118.9586,
                'kategori' => 'Pantai',
                'harga_tiket' => 15000,
                'kapasitas_harian' => 200,
                'gambar' => null,
                'status' => 'aktif',
                'pengelola_id' => $pengelola1->id,
                'created_at' => Carbon::now()->subMonths(5),
            ],
            [
                'nama_wisata' => 'Air Terjun Sarambu',
                'deskripsi' => 'Air terjun dengan ketinggian 50 meter yang dikelilingi oleh hutan tropis yang asri. Suasananya sejuk dan alami, cocok untuk trekking, fotografi alam, dan mandi di bawah air terjun. Airnya yang jernih berasal dari mata air pegunungan.',
                'lokasi' => 'Desa Sarambu, Kecamatan Tubo Sendana, Kabupaten Majene',
                'latitude' => -3.5102,
                'longitude' => 118.9015,
                'kategori' => 'Air Terjun',
                'harga_tiket' => 10000,
                'kapasitas_harian' => 150,
                'gambar' => null,
                'status' => 'aktif',
                'pengelola_id' => $pengelola1->id,
                'created_at' => Carbon::now()->subMonths(4),
            ],
            [
                'nama_wisata' => 'Bukit Tammangalle',
                'deskripsi' => 'Spot terbaik untuk melihat pemandangan kota Majene dari ketinggian. Sangat indah saat sunrise dan sunset. Dilengkapi dengan spot foto instagramable dan warung kopi dengan view panorama. Cocok untuk berkemah dan melihat bintang di malam hari.',
                'lokasi' => 'Kelurahan Tammangalle, Kecamatan Banggae, Kabupaten Majene',
                'latitude' => -3.5407,
                'longitude' => 118.9706,
                'kategori' => 'Bukit',
                'harga_tiket' => 5000,
                'kapasitas_harian' => 100,
                'gambar' => null,
                'status' => 'aktif',
                'pengelola_id' => $pengelola2->id,
                'created_at' => Carbon::now()->subMonths(4),
            ],
            [
                'nama_wisata' => 'Taman Kaluku Nangka',
                'deskripsi' => 'Taman rekreasi keluarga dengan berbagai wahana permainan anak, kolam renang, dan area piknik yang luas. Dilengkapi dengan taman bunga yang indah dan tempat bermain anak yang aman. Cocok untuk liburan akhir pekan bersama keluarga.',
                'lokasi' => 'Jl. Poros Majene-Mamuju, Desa Kaluku Nangka, Majene',
                'latitude' => -3.5058,
                'longitude' => 118.9812,
                'kategori' => 'Taman',
                'harga_tiket' => 20000,
                'kapasitas_harian' => 300,
                'gambar' => null,
                'status' => 'aktif',
                'pengelola_id' => $pengelola2->id,
                'created_at' => Carbon::now()->subMonths(3),
            ],
            [
                'nama_wisata' => 'Kawasan Kuliner Pesisir',
                'deskripsi' => 'Kawasan kuliner dengan berbagai makanan khas pesisir Majene. Menyediakan ikan bakar segar, seafood, coto makassar, pisang epe, dan berbagai makanan tradisional lainnya. Suasana makan di tepi pantai dengan angin laut yang sejuk.',
                'lokasi' => 'Kawasan Pantai Majene, Jl. Pesisir Pantai',
                'latitude' => -3.5439,
                'longitude' => 118.9745,
                'kategori' => 'Kuliner',
                'harga_tiket' => 0,
                'kapasitas_harian' => 500,
                'gambar' => null,
                'status' => 'aktif',
                'pengelola_id' => $pengelola1->id,
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'nama_wisata' => 'Gua Pattunuang',
                'deskripsi' => 'Gua alam dengan stalaktit dan stalagmit yang indah. Di dalam gua terdapat sungai bawah tanah dan habitat kelelawar. Cocok untuk wisata petualangan dan edukasi geologi. Perlu pemandu lokal untuk eksplorasi yang aman.',
                'lokasi' => 'Desa Pattunuang, Kecamatan Banggae Timur, Majene',
                'latitude' => -3.5254,
                'longitude' => 118.9923,
                'kategori' => 'Gua',
                'harga_tiket' => 15000,
                'kapasitas_harian' => 80,
                'gambar' => null,
                'status' => 'aktif',
                'pengelola_id' => $pengelola2->id,
                'created_at' => Carbon::now()->subMonths(1),
            ],
        ];
        
        foreach ($wisataData as $data) {
            Wisata::create($data);
        }
        
        $wisata1 = Wisata::where('nama_wisata', 'Pantai Manakarra')->first();
        $wisata2 = Wisata::where('nama_wisata', 'Air Terjun Sarambu')->first();
        $wisata3 = Wisata::where('nama_wisata', 'Bukit Tammangalle')->first();
        
        // ==================== BUAT BOOKING ====================
        
        $bookingData = [
            [
                'kode_booking' => 'BK-' . date('Ymd') . '-001',
                'wisatawan_id' => $wisatawan1->id,
                'wisata_id' => $wisata1->id,
                'tanggal_kunjungan' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'jumlah_tiket' => 4,
                'total_harga' => $wisata1->harga_tiket * 4,
                'status' => 'dikonfirmasi',
                'catatan' => 'Membawa 2 anak kecil',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'kode_booking' => 'BK-' . date('Ymd') . '-002',
                'wisatawan_id' => $wisatawan2->id,
                'wisata_id' => $wisata2->id,
                'tanggal_kunjungan' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'jumlah_tiket' => 2,
                'total_harga' => $wisata2->harga_tiket * 2,
                'status' => 'pending',
                'catatan' => 'Ingin datang pagi hari',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'kode_booking' => 'BK-' . date('Ymd') . '-003',
                'wisatawan_id' => $wisatawan3->id,
                'wisata_id' => $wisata3->id,
                'tanggal_kunjungan' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'jumlah_tiket' => 3,
                'total_harga' => $wisata3->harga_tiket * 3,
                'status' => 'selesai',
                'catatan' => 'Untuk foto pre-wedding',
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'kode_booking' => 'BK-' . date('Ymd') . '-004',
                'wisatawan_id' => $wisatawan1->id,
                'wisata_id' => $wisata3->id,
                'tanggal_kunjungan' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'jumlah_tiket' => 2,
                'total_harga' => $wisata3->harga_tiket * 2,
                'status' => 'selesai',
                'catatan' => null,
                'created_at' => Carbon::now()->subDays(20),
            ],
        ];
        
        foreach ($bookingData as $data) {
            Booking::create($data);
        }
        
        // ==================== BUAT ULASAN ====================
        
        $ulasanData = [
            [
                'wisatawan_id' => $wisatawan1->id,
                'wisata_id' => $wisata1->id,
                'rating' => 5,
                'komentar' => 'Pantai yang sangat indah! Airnya jernih dan pasirnya putih. Sunset-nya luar biasa. Pelayanan dari pengelola juga sangat baik.',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'wisatawan_id' => $wisatawan3->id,
                'wisata_id' => $wisata1->id,
                'rating' => 4,
                'komentar' => 'Pantainya bagus, tapi sampah plastik agak banyak. Harus lebih dijaga kebersihannya. Tapi secara keseluruhan oke.',
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'wisatawan_id' => $wisatawan1->id,
                'wisata_id' => $wisata3->id,
                'rating' => 5,
                'komentar' => 'View dari atas bukit sangat memukau! Cocok untuk melihat sunrise. Warung kopi di atas juga enak.',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'wisatawan_id' => $wisatawan2->id,
                'wisata_id' => $wisata2->id,
                'rating' => 4,
                'komentar' => 'Air terjunnya indah, perjalanan trekkingnya cukup menantang. Pemandu lokalnya ramah dan berpengalaman.',
                'created_at' => Carbon::now()->subDays(3),
            ],
        ];
        
        foreach ($ulasanData as $data) {
            Ulasan::create($data);
        }
        
        // ==================== BUAT WISATAWAN TAMBAHAN ====================
        
        for ($i = 4; $i <= 10; $i++) {
            User::create([
                'nama' => 'Wisatawan ' . $i,
                'email' => 'wisatawan' . $i . '@example.com',
                'password' => Hash::make('password123'),
                'role' => 'wisatawan',
                'status_verifikasi' => 'terverifikasi',
                'no_telepon' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'alamat' => 'Alamat Wisatawan ' . $i,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
        
        $this->command->info('==============================================');
        $this->command->info('DATABASE SEEDED SUCCESSFULLY!');
        $this->command->info('==============================================');
        $this->command->info('Admin Login:');
        $this->command->info('Email: admin@wisatamajene.com');
        $this->command->info('Password: password123');
        $this->command->info('');
        $this->command->info('Pengelola Wisata (Terverifikasi):');
        $this->command->info('Email: pengelola1@example.com / pengelola2@example.com');
        $this->command->info('Password: password123');
        $this->command->info('');
        $this->command->info('Pengelola Wisata (Belum Terverifikasi):');
        $this->command->info('Email: pengelola3@example.com');
        $this->command->info('Password: password123');
        $this->command->info('');
        $this->command->info('Wisatawan:');
        $this->command->info('Email: wisatawan1@example.com / wisatawan2@example.com / wisatawan3@example.com');
        $this->command->info('Password: password123');
        $this->command->info('==============================================');
    }
}