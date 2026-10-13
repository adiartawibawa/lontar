/**
 * Daftar menu sidebar Dashboard.
 *
 * - `href`       : tujuan menu. Menu TANPA href tampil nonaktif ("segera hadir")
 *                  karena fiturnya belum dibangun. Isi `href` saat rutenya ada.
 * - `permission` : menu hanya tampil untuk pengguna yang memiliki permission ini.
 * - `exact`      : aktif hanya jika URL sama persis (tanpa ini, cukup diawali href).
 */
export const navigation = [
  { key: 'home', label: 'Dashboard', icon: 'home', href: '/dashboard', exact: true },
  { key: 'file', label: 'Artikel', icon: 'file' },
  { key: 'page', label: 'Halaman', icon: 'page' },
  { key: 'image', label: 'Media', icon: 'image' },
  { key: 'tag', label: 'Kategori & tag', icon: 'tag' },
  { key: 'chat', label: 'Komentar', icon: 'chat' },
  { key: 'users', label: 'Pengguna', icon: 'users', href: '/admin/users', permission: 'users.manage' },
  { key: 'chart', label: 'Analitik', icon: 'chart' },
  { key: 'set', label: 'Pengaturan', icon: 'set' },
  { key: 'help', label: 'Bantuan', icon: 'help' },
]
