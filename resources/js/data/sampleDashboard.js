/**
 * DATA CONTOH untuk halaman Dashboard.
 *
 * Dipakai sebagai nilai bawaan props di Pages/Dashboard.vue selama fitur artikel
 * dan analitik belum dibangun. Setelah data asli tersedia, kirim dari controller
 * lewat Inertia::render('Dashboard', [...]) dan berkas ini bisa dihapus.
 */
export const sampleDashboard = {
  // Periode yang ditampilkan pada tombol di bagian judul
  period: { range: '1 Sep – 30 Sep 2026', preset: '30 hari terakhir' },

  // Kartu statistik: trend 'up' (naik) atau 'down' (turun); delta tanpa tanda
  stats: [
    { label: 'Tayangan halaman', value: '16.431', delta: '15,5%', trend: 'up', icon: 'eye', tone: 'blue' },
    { label: 'Pengunjung', value: '6.225', delta: '6,4%', trend: 'up', icon: 'user', tone: 'indigo' },
    { label: 'Klik', value: '2.832', delta: '10,5%', trend: 'down', icon: 'click', tone: 'sky' },
    { label: 'Artikel terbit', value: '1.224', delta: '4,4%', trend: 'up', icon: 'pen', tone: 'violet' },
  ],

  // Grafik area "Total pembaca" + tiga kartu ringkasan di bawahnya
  readers: {
    total: '184,7K',
    delta: '24,4%',
    points: [
      40, 48, 44, 52, 50, 58, 55, 70, 62, 66, 60, 74, 72, 64, 78,
      70, 82, 76, 90, 84, 96, 88, 100, 94, 110, 104, 120, 126, 122, 134,
    ],
    xLabels: ['1 Sep', '8 Sep', '15 Sep', '22 Sep', '29 Sep'],
    mini: [
      { label: 'Artikel', value: '2.884', tone: 'blue' },
      { label: 'Halaman', value: '1.432', tone: 'emerald' },
      { label: 'Penulis', value: '562', tone: 'amber' },
    ],
  },

  // Grafik batang "Hari paling aktif": height 0-100 (persen dari batang tertinggi)
  activeDays: {
    peakValue: '8.162',
    days: [
      { label: 'Sen', height: 52 },
      { label: 'Sel', height: 60 },
      { label: 'Rab', height: 100 },
      { label: 'Kam', height: 44 },
      { label: 'Jum', height: 38 },
      { label: 'Sab', height: 70 },
      { label: 'Min', height: 58 },
    ],
  },

  // Tabel "Konten terpopuler"; status: Terbit | Dijadwalkan | Draf
  popular: [
    { title: 'Panduan lengkap migrasi situs ke Nuxt 4', author: 'Ayu Wulandari', reads: '2.310', comments: '128', status: 'Terbit' },
    { title: '10 tips menulis judul yang dibaca banyak orang', author: 'Made Sukma', reads: '1.230', comments: '92', status: 'Terbit' },
    { title: 'Mengenal aksara Bali dan penggunaannya kini', author: 'Komang Adi', reads: '812', comments: '74', status: 'Terbit' },
    { title: 'Cara belajar tes CPNS dengan jadwal 30 hari', author: 'Rina Putri', reads: '645', comments: '62', status: 'Dijadwalkan' },
    { title: 'Menjaga performa situs statis tetap cepat', author: 'Dewa Raka', reads: '572', comments: '45', status: 'Draf' },
  ],

  // Target publikasi bulanan
  target: {
    percent: 68,
    done: 34,
    goal: 50,
    note: 'Sisa 8 hari. Butuh 2 artikel per hari untuk mencapai target.',
  },
}
