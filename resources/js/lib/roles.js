/**
 * Nama peran yang ditampilkan ke pengguna.
 * Kunci = nama peran di database (Spatie); nilai = label bahasa Indonesia.
 */
const labels = {
  admin: 'Admin',
  user: 'Pengguna',
}

/**
 * Mengubah nama peran di database menjadi label tampilan.
 * Peran yang belum terdaftar di atas ditampilkan dengan huruf awal kapital.
 */
export function roleLabel(name) {
  if (!name) return '-'

  return labels[name] ?? name.charAt(0).toUpperCase() + name.slice(1)
}
