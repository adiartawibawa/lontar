import {
  ChartLine,
  CircleHelp,
  FileText,
  House,
  Image,
  MessageSquare,
  PanelTop,
  SlidersHorizontal,
  Tag,
  Users,
} from '@lucide/vue'

/**
 * Konfigurasi menu sidebar Dashboard, dikelompokkan per bagian.
 *
 * Bagian   : { heading?, permission?, items }   (tanpa `heading` = tanpa judul)
 * Item     : { key, label, icon, href?, exact?, active?, permission?, badge?, children? }
 *
 * - `href`       : tujuan menu. Item TANPA href tampil redup ("segera hadir").
 *                  Cukup isi `href` ketika rutenya sudah dibuat.
 * - `children`   : submenu. Jika semua anak belum punya `href`, grupnya ikut redup.
 * - `exact`      : aktif hanya jika URL sama persis.
 * - `active`     : fungsi (path) => boolean untuk aturan aktif khusus.
 * - `permission` : hanya tampil untuk pengguna yang memiliki permission ini.
 * - `badge`      : teks lencana kecil di kanan (mis. 'Baru').
 *
 * Fungsi pendukungnya ada di lib/menu.js.
 */
export const navigation = [
  {
    items: [{ key: 'home', label: 'Dashboard', icon: House, href: '/dashboard', exact: true }],
  },
  {
    heading: 'Konten',
    items: [
      {
        key: 'articles',
        label: 'Artikel',
        icon: FileText,
        // Submenu di bawah belum punya rute: tambahkan `href` saat fiturnya dibuat
        children: [
          { key: 'articles.all', label: 'Semua artikel' },
          { key: 'articles.create', label: 'Tulis artikel' },
          { key: 'articles.drafts', label: 'Draf' },
        ],
      },
      { key: 'pages', label: 'Halaman', icon: PanelTop },
      { key: 'media', label: 'Media', icon: Image },
      { key: 'taxonomy', label: 'Kategori & tag', icon: Tag },
      { key: 'comments', label: 'Komentar', icon: MessageSquare },
    ],
  },
  {
    heading: 'Administrasi',
    items: [
      {
        key: 'users',
        label: 'Pengguna',
        icon: Users,
        permission: 'users.manage',
        children: [
          {
            key: 'users.all',
            label: 'Semua pengguna',
            href: '/admin/users',
            // Daftar dan halaman ubah dianggap satu; halaman tambah punya menu sendiri
            active: (path) => path.startsWith('/admin/users') && path !== '/admin/users/create',
          },
          { key: 'users.create', label: 'Tambah pengguna', href: '/admin/users/create' },
        ],
      },
      { key: 'analytics', label: 'Analitik', icon: ChartLine },
    ],
  },
  {
    heading: 'Sistem',
    items: [
      { key: 'settings', label: 'Pengaturan', icon: SlidersHorizontal },
      { key: 'help', label: 'Bantuan', icon: CircleHelp },
    ],
  },
]
