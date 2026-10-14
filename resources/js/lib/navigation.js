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
 * Daftar menu sidebar Dashboard.
 *
 * - `icon`       : komponen ikon Lucide.
 * - `href`       : tujuan menu. Menu TANPA href tampil nonaktif ("segera hadir")
 *                  karena fiturnya belum dibangun. Isi `href` saat rutenya ada.
 * - `permission` : menu hanya tampil untuk pengguna yang memiliki permission ini.
 * - `exact`      : aktif hanya jika URL sama persis (tanpa ini, cukup diawali href).
 */
export const navigation = [
  { key: 'home', label: 'Dashboard', icon: House, href: '/dashboard', exact: true },
  { key: 'file', label: 'Artikel', icon: FileText },
  { key: 'page', label: 'Halaman', icon: PanelTop },
  { key: 'image', label: 'Media', icon: Image },
  { key: 'tag', label: 'Kategori & tag', icon: Tag },
  { key: 'chat', label: 'Komentar', icon: MessageSquare },
  { key: 'users', label: 'Pengguna', icon: Users, href: '/admin/users', permission: 'users.manage' },
  { key: 'chart', label: 'Analitik', icon: ChartLine },
  { key: 'set', label: 'Pengaturan', icon: SlidersHorizontal },
  { key: 'help', label: 'Bantuan', icon: CircleHelp },
]
