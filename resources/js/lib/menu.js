/**
 * Logika murni untuk menu sidebar (tanpa Vue), supaya mudah dipahami dan diuji.
 *
 * Bentuk data menu (lihat lib/navigation.js):
 *   bagian : { heading?, permission?, items: [item] }
 *   item   : { key, label, icon?, href?, exact?, active?, permission?, badge?, children?: [item] }
 *
 * - Item TANPA `href` dan tanpa `children` aktif = menu yang belum tersedia ("segera hadir").
 * - Item dengan `children` = menu bertingkat (grup).
 */

/**
 * Mengambil path murni dari URL halaman (tanpa query dan hash).
 */
export function pathOf(url) {
  return String(url ?? '').split('?')[0].split('#')[0]
}

/**
 * Apakah item (bukan grup) sedang aktif untuk path saat ini?
 * - `active(path)` pada item, bila ada, menentukan sendiri aturan aktifnya.
 * - `exact: true` : aktif hanya jika path sama persis.
 * - Selain itu    : aktif jika path sama atau berada di bawahnya (/admin/users/5/edit
 *                   aktif untuk /admin/users, tetapi /admin/users2 tidak).
 */
export function isActive(item, path) {
  if (typeof item.active === 'function') return item.active(path)
  if (!item.href) return false
  if (item.exact) return path === item.href

  return path === item.href || path.startsWith(`${item.href}/`)
}

/**
 * Apakah item adalah grup (punya anak)?
 */
export function isGroup(item) {
  return Array.isArray(item.children) && item.children.length > 0
}

/**
 * Apakah item bisa diklik? Item biasa butuh href; grup butuh minimal satu anak yang bisa diklik.
 */
export function isEnabled(item) {
  return isGroup(item) ? item.children.some(isEnabled) : Boolean(item.href)
}

/**
 * Apakah ada anak pada grup yang sedang aktif? (dipakai untuk menandai induknya)
 */
export function hasActiveChild(item, path) {
  return isGroup(item) && item.children.some((child) => isActive(child, path))
}

/**
 * Menyaring menu berdasarkan permission pengguna.
 * - Item/anak tanpa izin dibuang.
 * - Grup yang semua anaknya terbuang ikut dibuang.
 * - Bagian (section) yang tidak punya item tersisa ikut dibuang.
 *
 * @param {Array}    sections daftar bagian menu
 * @param {string[]} granted  permission yang dimiliki pengguna
 */
export function buildMenu(sections, granted = []) {
  const allowed = (node) => !node.permission || granted.includes(node.permission)

  return sections
    .filter(allowed)
    .map((section) => ({
      ...section,
      items: section.items
        .filter(allowed)
        .map((item) => (isGroup(item) ? { ...item, children: item.children.filter(allowed) } : item))
        .filter((item) => !('children' in item) || item.children.length > 0),
    }))
    .filter((section) => section.items.length > 0)
}
