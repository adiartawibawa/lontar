/**
 * DATA CONTOH untuk Landing page (artikel, unggulan, terbaru).
 *
 * Dipakai sebagai nilai bawaan props di Pages/Landing.vue sampai fitur artikel
 * dibangun. Setiap item boleh memakai `image` (URL gambar) atau `gradient`
 * (latar gradien sementara). Setelah data asli tersedia, kirim dari controller
 * dan berkas ini bisa dihapus.
 */

// Gradien sementara pengganti gambar sampul
const gradients = [
  'linear-gradient(180deg,#38bdf8 0%,#fde68a 62%,#0e7490 100%)',
  'linear-gradient(180deg,#d6d3d1,#78716c 55%,#1c1917)',
  'linear-gradient(160deg,#f1f5f9,#86efac 55%,#14532d)',
  'linear-gradient(180deg,#67e8f9,#0891b2 60%,#164e63)',
  'linear-gradient(180deg,#e2e8f0,#64748b 50%,#1e293b)',
  'linear-gradient(180deg,#fdba74,#ef4444 55%,#4c1d95)',
  'linear-gradient(160deg,#a7f3d0,#16a34a 60%,#052e16)',
  'linear-gradient(180deg,#312e81,#6d28d9 50%,#0f172a)',
  'linear-gradient(180deg,#fef3c7,#f59e0b 55%,#78350f)',
  'linear-gradient(180deg,#bae6fd,#38bdf8 45%,#1e3a8a)',
  'linear-gradient(180deg,#fecdd3,#fb7185 55%,#881337)',
  'linear-gradient(180deg,#d9f99d,#65a30d 55%,#1a2e05)',
]

// [kategori, judul, ringkasan]
const postRows = [
  ['Kesehatan & Gizi', 'Wanderlust: Jelajahi Permata Tersembunyi Tahun Ini', 'Tujuan di luar jalur turis dengan pemandangan memukau dan pengalaman yang sulit dilupakan.'],
  ['Keberlanjutan', 'Daftar Impian: 25 Tujuan untuk Setiap Petualang', 'Kumpulan tempat wajib kunjung untuk pencinta gunung, pantai, maupun situs budaya.'],
  ['Budaya', 'Bepergian Seperti Warga Lokal: Tips Pengalaman Autentik', 'Cara membaur dengan budaya setempat, mulai dari warung makan sampai pasar pagi.'],
  ['Persiapan', 'Solo Traveling? Ini yang Perlu Anda Ketahui agar Aman', 'Panduan perjalanan sendirian lengkap dengan tips keselamatan, perencanaan, dan percaya diri.'],
  ['Kuliner', 'Petualangan Kuliner: Panduan Mencicipi Makanan Dunia', 'Dari jajanan kaki lima sampai restoran mewah, cara menikmati hidangan terbaik di tiap kota.'],
  ['Resep', 'Liburan Akhir Pekan: Perjalanan Singkat Berdampak Besar', 'Destinasi dekat kota yang cocok untuk akhir pekan, dari pesisir sampai pegunungan.'],
  ['Budaya', 'Mengenal Aksara Bali dan Cara Membacanya', 'Pengantar singkat untuk pemula yang ingin memahami papan nama dan prasasti di Pulau Dewata.'],
  ['Persiapan', 'Daftar Barang Bawaan untuk Perjalanan Seminggu', 'Bawa seperlunya: daftar ringkas yang sudah diuji di bandara dan terminal.'],
  ['Kuliner', 'Warung Terbaik untuk Sarapan Pagi di Ubud', 'Tujuh tempat sarapan dengan antrean yang sepadan dengan rasanya.'],
  ['Keberlanjutan', 'Wisata Hemat Sampah: Mulai dari Botol Minum', 'Langkah kecil yang membuat perjalanan Anda lebih ringan bagi lingkungan.'],
  ['Kesehatan & Gizi', 'Menjaga Stamina Saat Mendaki Gunung', 'Pola makan, istirahat, dan peregangan yang membantu Anda tiba di puncak.'],
  ['Resep', 'Nasi Jinggo Rumahan dalam 30 Menit', 'Resep praktis dengan bumbu sederhana yang mudah ditemukan di pasar.'],
]

// [tanggal, judul, nomor gradien]
const featuredRows = [
  ['7 Agustus 2026', 'Permata Tersembunyi: Tempat Wajib Kunjung Tahun Ini', 7],
  ['23 Maret 2026', 'Daftar Impian: 25 Petualangan untuk Setiap Pelancong', 1],
  ['31 Mei 2026', 'Bepergian Seperti Warga Lokal: Tips Pengalaman Autentik', 3],
]

const latestRows = [
  ['24 Oktober 2026', 'Perjalanan Biasa yang Berubah Menjadi Cerita', 5],
  ['19 Oktober 2026', 'Kapan Waktu Terbaik Mengunjungi Danau Batur', 11],
  ['11 Oktober 2026', 'Kereta Malam: Tidur Nyaman Sepanjang Jalur', 4],
]

// Artikel pada grid "Pilihan redaksi"
export const samplePosts = postRows.map(([category, title, excerpt], i) => ({
  category,
  title,
  excerpt,
  gradient: gradients[i % gradients.length],
  href: '#',
}))

// Daftar "Unggulan" di sidebar
export const sampleFeatured = featuredRows.map(([date, title, g]) => ({
  date,
  title,
  gradient: gradients[g],
  href: '#',
}))

// Daftar "Terbaru" di sidebar
export const sampleLatest = latestRows.map(([date, title, g]) => ({
  date,
  title,
  gradient: gradients[g],
  href: '#',
}))
