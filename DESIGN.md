# DESIGN SPECIFICATION — WEBSITE DEALER MOBIL

## 1. Tujuan Dokumen

Dokumen ini adalah spesifikasi desain dan arahan implementasi untuk AI Agent yang akan membangun **Website Dealer Mobil** menggunakan:

- Backend: **PHP — Laravel**
- Template/UI: **Laravel Blade**
- Database: **MySQL/MariaDB**
- CSS: **Tailwind CSS** atau CSS terstruktur yang mengikuti token desain di dokumen ini
- JavaScript: vanilla JS atau Alpine.js untuk interaksi ringan
- Architecture: Laravel MVC

Website harus mengikuti kebutuhan yang tercantum dalam proposal penawaran pembuatan Website Dealer Mobil, yaitu website informasi, katalog dan promosi kendaraan dengan dashboard administrator.

## 2. Sumber Kebutuhan Bisnis

Kebutuhan utama website:

1. Halaman Beranda.
2. Profil Dealer.
3. Katalog Mobil.
4. Detail Kendaraan.
5. Pencarian Kendaraan.
6. Promo Kendaraan.
7. Simulasi Kredit.
8. WhatsApp Sales.
9. Galeri.
10. Dashboard Administrator.
11. Responsive Design untuk desktop, laptop, tablet dan smartphone.
12. Pengelolaan data kendaraan, harga, foto, spesifikasi, kategori, promo, konten website dan informasi dealer.

Website berfungsi sebagai pusat informasi digital dealer. Calon pelanggan dapat melihat kendaraan, harga, spesifikasi, foto dan promo, lalu menghubungi sales melalui WhatsApp.

## 3. Prinsip Penting untuk AI Agent

- Jangan membuat website e-commerce/full online transaction karena proposal tidak menyebutkan checkout, pembayaran online, keranjang, akun pelanggan, atau transaksi kendaraan secara langsung.
- Fokus pada **informasi, katalog, promosi dan lead/contact ke sales**.
- Jangan membuat fitur yang tidak tercantum dalam proposal kecuali fitur tersebut diperlukan secara teknis untuk menjalankan fitur utama.
- Semua konten kendaraan dan informasi dealer harus dapat dikelola administrator melalui dashboard.
- UI harus terlihat premium, profesional, bersih dan automotive-oriented.
- Gunakan fotografi kendaraan sebagai elemen visual utama.
- Jangan menggunakan identitas BMW secara langsung untuk website klien. Referensi BMW.com hanya digunakan sebagai referensi bahasa visual: minimal, premium, monochrome, whitespace besar, tipografi ringan, foto kendaraan dominan.
- Jangan menggunakan logo BMW, nama BMW, tagline BMW atau aset berhak cipta BMW sebagai konten production website.

# 4. VISUAL DIRECTION

## 4.1 Design Concept

Konsep visual:

> **Premium Automotive Gallery**

Website harus terasa seperti showroom digital: kendaraan merupakan pusat perhatian, sedangkan UI berfungsi sebagai bingkai informasi.

Karakter visual:

- Premium
- Modern
- Minimal
- Editorial
- Automotive
- Clean
- Banyak whitespace
- Kontras hitam/putih
- Foto kendaraan berukuran besar
- Layout presisi dan rapi

## 4.2 Color Tokens

```css
:root {
  --color-carbon: #262626;
  --color-pure-white: #ffffff;
  --color-concrete: #bbbbbb;
  --color-fog: #f1f1f1;

  /* Functional colors are allowed only for system feedback. */
  --color-success: #166534;
  --color-error: #b91c1c;
  --color-warning: #92400e;

  --surface-canvas: #ffffff;
  --surface-subtle-band: #f1f1f1;
  --surface-footer: #262626;
}
```

### Aturan warna

- Primary visual UI: `#ffffff` dan `#262626`.
- Secondary surface: `#f1f1f1`.
- Muted text/divider: `#bbbbbb`.
- Jangan menggunakan gradient dekoratif.
- Jangan menggunakan warna-warni pada CTA utama.
- Warna success/error/warning hanya digunakan untuk validasi, notifikasi, atau status sistem di dashboard; bukan sebagai aksen visual landing page.

## 4.3 Typography

Utamakan font yang tersedia secara legal dan konsisten. Gunakan Inter sebagai pengganti jika font brand tidak tersedia.

```css
:root {
  --font-sans: Inter, ui-sans-serif, system-ui, -apple-system,
               BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;

  --text-body-sm: 16px;
  --text-body: 18px;
  --text-display: 60px;

  --font-weight-light: 300;
  --font-weight-regular: 400;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;

  --leading-body-sm: 1.6;
  --leading-body: 1.63;
  --leading-display: 1.3;
}
```

### Hierarki

- Display/Hero heading: 48–60px, weight 300.
- Section heading: 36–48px, weight 300–400.
- Card/product title: 20–28px, weight 400–600.
- Body: 16–18px, weight 400.
- Metadata: 14–16px, weight 400.
- Label: 12–14px, weight 600.

Pada mobile, display heading wajib diperkecil secara responsif, misalnya 36–42px.

## 4.4 Geometry

- Base spacing unit: 4px.
- Page max-width: 1200px.
- Section gap: 56px atau lebih untuk section utama.
- Card padding: 24px.
- Element gap: 10–20px.
- Border radius default: `0px`.
- Button radius: `0px`.
- Input radius: `0px`.
- Card radius: `0px`.
- Shadow: hindari; gunakan whitespace dan contrast surface.

Rounded modern SaaS cards tidak digunakan karena bertentangan dengan karakter automotive premium yang dipilih.

# 5. INFORMATION ARCHITECTURE

## 5.1 Public Website

```text
/
├── Home
├── Tentang / Profil Dealer
├── Mobil
│   ├── Katalog
│   └── Detail Mobil
├── Promo
├── Simulasi Kredit
├── Galeri
└── Kontak
```

## 5.2 Admin Website

```text
/admin
├── Dashboard
├── Kendaraan
│   ├── Daftar
│   ├── Tambah
│   ├── Edit
│   └── Detail
├── Kategori Kendaraan
├── Promo
├── Galeri
├── Konten Website
├── Informasi Dealer
├── Sales / Kontak WhatsApp
└── Pengaturan
```

# 6. PUBLIC WEBSITE PAGES

## 6.1 HOME / BERANDA

### Tujuan

Menjadi halaman utama yang langsung memperkenalkan dealer, kendaraan unggulan, promo dan jalur kontak sales.

### Urutan section

1. Header/navigation.
2. Hero kendaraan.
3. Intro singkat dealer.
4. Kendaraan unggulan.
5. Promo terbaru.
6. CTA katalog/simulasi kredit.
7. Galeri pilihan.
8. Informasi lokasi dan kontak dealer.
9. Footer.

### Hero

Gunakan foto mobil berkualitas tinggi, full-width.

Aturan hero:

- Foto kendaraan menjadi fokus utama.
- Hindari terlalu banyak teks di atas gambar.
- Jangan menutup detail penting kendaraan dengan UI.
- Karena website dealer membutuhkan CTA fungsional, teks/CTA hero boleh ditempatkan pada area putih di bawah atau setelah hero image.
- Jika overlay digunakan, hanya gunakan ketika kontras dan keterbacaan tetap baik; default-nya adalah **tanpa overlay**.

Hero dapat menampilkan:

- nama kendaraan unggulan
- tahun
- harga mulai
- link `Lihat Detail`

## 6.2 PROFIL DEALER

Tampilkan:

- Nama dealer
- Sejarah dealer
- Informasi perusahaan
- Alamat
- Nomor telepon
- Email
- Jam operasional
- Informasi layanan

Gunakan layout editorial dengan whitespace besar. Jangan membuat profil seperti corporate dashboard.

## 6.3 KATALOG MOBIL

### Tujuan

Menampilkan seluruh kendaraan yang tersedia di dealer.

### Data minimum kendaraan

- Nama mobil
- Merek
- Tipe
- Tahun
- Harga
- Warna
- Transmisi
- Kapasitas mesin
- Kilometer
- Status kendaraan
- Foto kendaraan
- Kategori

### Tampilan

Desktop:

- Maksimum 3–4 produk per row bila diperlukan.
- Gunakan gambar besar.
- Card tanpa shadow.
- Border minimal atau tidak digunakan.

Mobile:

- 1–2 item per row sesuai lebar layar.
- Gambar tetap dominan.
- Informasi jangan terlalu padat.

### Filter dan pencarian

Pencarian berdasarkan:

- Nama
- Merek
- Tipe
- Kategori

Filter tambahan dapat mencakup:

- Tahun
- Rentang harga
- Transmisi
- Status kendaraan

Filter tambahan ini harus sederhana dan tidak mengubah fokus utama katalog.

## 6.4 DETAIL KENDARAAN

Halaman harus menampilkan:

- Gallery foto kendaraan
- Nama kendaraan
- Merek
- Tipe
- Tahun
- Harga
- Warna
- Transmisi
- Kapasitas mesin
- Kilometer
- Status kendaraan
- Deskripsi
- Spesifikasi lengkap
- Tombol WhatsApp Sales
- Link simulasi kredit

### Layout desktop

Gunakan komposisi:

```text
------------------------------------------------
|       Gallery besar       | Informasi mobil  |
|                           |                  |
|                           | Harga            |
|                           | Spesifikasi      |
|                           | WhatsApp Sales   |
------------------------------------------------
| Deskripsi / Detail / Spesifikasi Lengkap    |
------------------------------------------------
| Kendaraan Terkait                            |
------------------------------------------------
```

### WhatsApp Sales

Klik tombol harus menghasilkan URL WhatsApp dengan pesan otomatis, misalnya:

```text
Halo, saya tertarik dengan Toyota Avanza 2024 yang tersedia di website. Mohon informasi lebih lanjut.
```

Nomor WhatsApp harus berasal dari data dealer/sales yang dapat diubah admin.

## 6.5 PENCARIAN KENDARAAN

Implementasikan pencarian server-side melalui Laravel Eloquent/query builder.

Contoh logika:

```php
Vehicle::query()
    ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('type', 'like', "%{$search}%");
        });
    });
```

Jangan menaruh seluruh dataset kendaraan di HTML hanya untuk melakukan filtering menggunakan JavaScript.

## 6.6 PROMO

Tampilkan promo yang tersedia, misalnya:

- Diskon
- Promo pembelian
- Bonus
- Program cicilan
- Penawaran khusus

Setiap promo minimal mempunyai:

- Judul
- Ringkasan
- Deskripsi
- Gambar
- Periode mulai
- Periode berakhir
- Status aktif/nonaktif
- Kendaraan terkait jika ada

Promo kedaluwarsa tidak boleh ditampilkan sebagai promo aktif.

## 6.7 SIMULASI KREDIT

Simulator digunakan untuk memberikan **perkiraan pembayaran kendaraan**, bukan keputusan pembiayaan resmi.

Input:

- Harga kendaraan
- Uang muka (DP)
- Jangka waktu

Optional:

- Suku bunga

Output:

- Pokok pembiayaan
- Estimasi cicilan per bulan
- Total estimasi pembayaran

### Contoh formula sederhana

Jika menggunakan bunga flat tahunan:

```text
Pokok = Harga - DP
Bunga per bulan = Pokok × (bunga_tahunan / 12)
Cicilan per bulan = (Pokok / tenor_bulan) + Bunga per bulan
```

Tampilkan catatan:

> Simulasi hanya bersifat estimasi. Nilai final dapat berbeda sesuai kebijakan leasing, bunga, biaya administrasi, asuransi, dan persyaratan pembiayaan.

Jangan membuat klaim bahwa angka simulasi merupakan penawaran kredit resmi.

## 6.8 WHATSAPP SALES

Tombol WhatsApp wajib mudah ditemukan pada:

- Detail kendaraan
- Kontak
- Area CTA

Gunakan nomor sales/dealer dari database.

Pesan otomatis harus dapat dibuat berdasarkan konteks halaman.

Contoh:

```text
Halo, saya ingin menanyakan Toyota Fortuner 2023 dengan harga Rp xxx. Apakah unit masih tersedia?
```

## 6.9 GALERI

Kategori galeri:

- Kendaraan
- Showroom
- Kegiatan dealer
- Dokumentasi lain

Gunakan gambar besar dan layout visual yang bersih.

Tidak perlu membuat social media feed otomatis kecuali ada kebutuhan tambahan di luar proposal.

## 6.10 KONTAK

Tampilkan:

- Alamat dealer
- Nomor telepon
- Email
- Jam operasional
- WhatsApp sales

Map/embed dapat digunakan sebagai elemen tambahan pada halaman kontak, tetapi jangan menjadikannya fitur utama bila tidak dibutuhkan.

# 7. HEADER / NAVIGATION

Header public website:

```text
[ LOGO DEALER ]    Home   Mobil   Promo   Tentang   Galeri   Kontak
```

Karakter:

- Background putih.
- Tanpa shadow.
- Tanpa blur.
- Border-bottom sangat tipis hanya bila dibutuhkan untuk accessibility.
- Typography 14–16px.
- Navigation sederhana.

Desktop dan mobile wajib memiliki navigasi yang nyaman.

Mobile:

- Gunakan menu toggle.
- Jangan membuat mega-menu.

Header tidak wajib sticky. Gunakan sticky hanya apabila benar-benar meningkatkan usability dan tidak mengganggu foto kendaraan.

# 8. FOOTER

Footer menggunakan Carbon `#262626`.

Struktur:

```text
------------------------------------------------------
Dealer Mobil       Navigasi       Kontak       Sosial
------------------------------------------------------
Alamat             Home           Telepon      Instagram
Jam Operasional    Mobil          Email        WhatsApp
                   Promo          Alamat
------------------------------------------------------
© Dealer Mobil — All Rights Reserved
------------------------------------------------------
```

Gunakan teks putih.

Footer tidak boleh terlihat seperti template Bootstrap standar.

# 9. ADMIN DASHBOARD

## 9.1 Tujuan

Dashboard digunakan pihak dealer untuk mengelola informasi website tanpa harus mengubah kode program.

## 9.2 Dashboard Overview

Widget informasi yang relevan:

- Jumlah kendaraan
- Kendaraan aktif
- Kendaraan terjual/tidak tersedia
- Promo aktif
- Foto galeri
- Konten yang tersedia

Jangan menambahkan analytics kompleks apabila tidak diperlukan.

## 9.3 CRUD Kendaraan

Admin dapat:

- Create
- Read
- Update
- Delete
- Upload foto
- Mengatur kategori
- Mengubah harga
- Mengubah status

Status minimum:

- Available
- Sold
- Reserved
- Inactive

Konfirmasi wajib ditampilkan sebelum delete.

## 9.4 CRUD Kategori

Field minimum:

- Nama kategori
- Slug
- Deskripsi
- Status

## 9.5 CRUD Promo

Admin dapat membuat, mengubah, mengaktifkan, menonaktifkan dan menghapus promo.

Validasi periode harus mencegah data promo yang tidak konsisten.

## 9.6 CRUD Galeri

Admin dapat:

- Upload foto
- Judul/caption
- Kategori
- Status publish
- Hapus foto

Gunakan validasi MIME/type dan ukuran file.

## 9.7 Konten Website

Konten yang dapat diubah admin minimal:

- Nama dealer
- Logo
- Sejarah
- Alamat
- Nomor telepon
- Email
- Jam operasional
- Informasi layanan
- Nomor WhatsApp
- Hero image
- Konten halaman home

## 9.8 Sales / WhatsApp

Admin dapat menyimpan:

- Nama sales
- Nomor WhatsApp
- Jabatan/label
- Status aktif

Website public hanya menampilkan sales aktif.

# 10. ROLE DAN AUTHENTICATION

Minimal terdapat satu role:

- `admin`

Gunakan Laravel authentication yang aman.

Admin route harus dilindungi middleware authentication.

Contoh:

```php
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // resource routes...
});
```

Jangan membuat authentication publik untuk pelanggan karena proposal tidak membutuhkannya.

# 11. DATABASE DESIGN

Gunakan database relasional.

## 11.1 Tabel users

```text
users
- id
- name
- email
- password
- role
- remember_token
- timestamps
```

## 11.2 Tabel categories

```text
categories
- id
- name
- slug
- description
- is_active
- timestamps
```

## 11.3 Tabel vehicles

```text
vehicles
- id
- category_id
- name
- slug
- brand
- type
- year
- price
- color
- transmission
- engine_capacity
- mileage
- status
- description
- is_featured
- timestamps
```

## 11.4 Tabel vehicle_images

```text
vehicle_images
- id
- vehicle_id
- image_path
- alt_text
- sort_order
- is_primary
- timestamps
```

## 11.5 Tabel promotions

```text
promotions
- id
- vehicle_id nullable
- title
- slug
- summary
- description
- image_path nullable
- start_date
- end_date
- is_active
- timestamps
```

## 11.6 Tabel galleries

```text
galleries
- id
- title
- image_path
- category
- caption nullable
- is_published
- sort_order
- timestamps
```

## 11.7 Tabel dealer_settings

```text
dealer_settings
- id
- dealer_name
- logo_path nullable
- tagline nullable
- history nullable
- address
- phone
- email
- whatsapp
- opening_hours
- services nullable
- hero_image nullable
- timestamps
```

## 11.8 Tabel sales

```text
sales
- id
- name
- phone
- position nullable
- is_active
- timestamps
```

# 12. MODEL RELATIONSHIPS

Laravel Eloquent relationships:

```text
Category
    hasMany(Vehicle)

Vehicle
    belongsTo(Category)
    hasMany(VehicleImage)
    hasMany(Promotion)

Promotion
    belongsTo(Vehicle, nullable)
```

Gunakan route model binding untuk halaman detail kendaraan dan promo.

# 13. ROUTING

Gunakan route naming yang jelas.

Contoh public routes:

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');
Route::get('/mobil', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/mobil/{vehicle:slug}', [VehicleController::class, 'show'])->name('vehicles.show');
Route::get('/promo', [PromotionController::class, 'index'])->name('promotions.index');
Route::get('/simulasi-kredit', [CreditSimulationController::class, 'index'])->name('credit.index');
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
```

Admin routes menggunakan `auth` middleware dan resource controllers.

# 14. CONTROLLER STRUCTURE

Recommended structure:

```text
app/Http/Controllers/
├── HomeController.php
├── AboutController.php
├── VehicleController.php
├── PromotionController.php
├── CreditSimulationController.php
├── GalleryController.php
├── ContactController.php
└── Admin/
    ├── DashboardController.php
    ├── VehicleController.php
    ├── CategoryController.php
    ├── PromotionController.php
    ├── GalleryController.php
    ├── DealerSettingController.php
    └── SalesController.php
```

Pisahkan public controller dan admin controller.

# 15. BLADE STRUCTURE

Gunakan component-based Blade.

```text
resources/views/
├── layouts/
│   ├── app.blade.php
│   └── admin.blade.php
├── components/
│   ├── header.blade.php
│   ├── footer.blade.php
│   ├── vehicle-card.blade.php
│   ├── promo-card.blade.php
│   ├── gallery-item.blade.php
│   └── whatsapp-button.blade.php
├── home.blade.php
├── about.blade.php
├── vehicles/
│   ├── index.blade.php
│   └── show.blade.php
├── promotions/
│   └── index.blade.php
├── credit/
│   └── index.blade.php
├── gallery/
│   └── index.blade.php
├── contact.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── vehicles/
    ├── categories/
    ├── promotions/
    ├── gallery/
    ├── dealer-settings/
    └── sales/
```

# 16. DESIGN COMPONENTS

## Vehicle Card

Wajib menampilkan minimal:

- Foto utama
- Merek
- Nama/type
- Tahun
- Harga
- Status
- Link detail

Jangan menggunakan badge warna-warni. Status dapat ditampilkan sebagai teks sederhana atau label tipis.

## Price Display

Harga harus mudah dibaca tetapi tidak terlalu besar seperti marketplace.

Contoh:

```text
Toyota Avanza
2024 · Automatic · 18.000 km

Rp 245.000.000
› Lihat detail
```

## Button

Primary:

- Background Carbon
- Text White
- Square
- Tanpa gradient
- Tanpa shadow

Secondary:

- White background
- Carbon text
- Border Carbon 1px
- Square

Text-link:

- Tanpa container.
- Gunakan arrow/chevron sebagai penanda.

## Input

- Background white.
- Border `#bbbbbb`.
- Square.
- Label jelas.
- Focus state menggunakan border/outline yang terlihat untuk accessibility.

## Alert / Validation

Gunakan warna semantic hanya untuk feedback sistem.

Contoh:

- Error validasi: merah.
- Success save: hijau.
- Warning: amber/orange.

# 17. RESPONSIVE DESIGN

## Desktop >= 1024px

- Max content width: 1200px.
- Hero full-width.
- Navigation horizontal.
- Product grid maksimal 3–4 kolom.

## Tablet 768–1023px

- Grid dikurangi.
- Navigation dapat dipadatkan.
- Image tetap menjadi prioritas.

## Mobile < 768px

- Menu menjadi hamburger.
- Konten single-column.
- Gallery menyesuaikan layar.
- CTA full-width jika diperlukan.
- Font display diturunkan secara proporsional.
- Spacing dipadatkan tetapi tetap nyaman.

Jangan membuat horizontal scrolling pada body.

# 18. IMAGE HANDLING

Kendaraan adalah konten utama sehingga image handling harus baik.

Gunakan:

- `storage/app/public` untuk file yang dikelola admin.
- `php artisan storage:link`.
- Path disimpan di database.
- Thumbnail/optimized image bila diperlukan.
- `alt` text wajib.
- Lazy loading untuk gambar di bawah fold.
- `loading="eager"` hanya untuk gambar hero utama jika sesuai kebutuhan.

Contoh:

```blade
<img
    src="{{ Storage::url($vehicle->primaryImage->image_path) }}"
    alt="{{ $vehicle->name }}"
    loading="lazy"
>
```

# 19. SECURITY

Karena dashboard memiliki fungsi CRUD dan upload file, minimal terapkan:

- CSRF protection.
- Authentication.
- Authorization berdasarkan role.
- Laravel validation.
- Mass assignment protection melalui `$fillable` atau `$guarded`.
- Validasi upload file.
- Batas ukuran file.
- MIME validation.
- Escape output Blade.
- Jangan menyimpan password plaintext.
- Jangan menampilkan credential di repository.
- Gunakan `.env` untuk konfigurasi database dan secret.

# 20. VALIDATION

Contoh validasi kendaraan:

```php
$request->validate([
    'category_id' => ['required', 'exists:categories,id'],
    'name' => ['required', 'string', 'max:255'],
    'brand' => ['required', 'string', 'max:100'],
    'type' => ['required', 'string', 'max:100'],
    'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y') + 1],
    'price' => ['required', 'numeric', 'min:0'],
    'color' => ['nullable', 'string', 'max:50'],
    'transmission' => ['nullable', 'string', 'max:50'],
    'engine_capacity' => ['nullable', 'string', 'max:50'],
    'mileage' => ['nullable', 'integer', 'min:0'],
    'status' => ['required', 'in:available,sold,reserved,inactive'],
    'description' => ['nullable', 'string'],
]);
```

Sesuaikan enum/status apabila implementasi menggunakan model berbeda.

# 21. SEO BASIC

Setiap public page sebaiknya memiliki:

- Unique title.
- Meta description.
- Canonical URL.
- Semantic HTML.
- Proper heading hierarchy.
- Alt text pada image.
- Slug kendaraan yang SEO-friendly.

Contoh:

```text
/mobil/toyota-avanza-2024
```

# 22. PERFORMANCE

Prioritas:

- Query database efisien.
- Pagination katalog.
- Eager loading relationship yang diperlukan.
- Optimasi gambar.
- Lazy loading image.
- Minimize unnecessary JavaScript.
- Cache konfigurasi Laravel pada production.

Contoh:

```php
Vehicle::with(['category', 'primaryImage'])
    ->where('status', 'available')
    ->latest()
    ->paginate(12);
```

# 23. ACCESSIBILITY

Wajib:

- Semantic HTML.
- Label form jelas.
- Keyboard accessible.
- Visible focus state.
- Kontras teks memadai.
- `alt` text.
- Button dan link tidak hanya dibedakan melalui warna.
- Mobile navigation dapat digunakan dengan keyboard/screen reader.

# 24. EMPTY STATES

Jangan membiarkan halaman kosong tanpa penjelasan.

Contoh katalog kosong:

```text
Belum ada kendaraan yang tersedia.
Silakan kembali beberapa saat lagi atau hubungi sales kami.
```

Contoh hasil pencarian kosong:

```text
Kendaraan tidak ditemukan.
Coba gunakan kata kunci atau filter yang berbeda.
```

# 25. ERROR PAGES

Sediakan halaman:

- 404 Not Found.
- 403 Forbidden.
- 500 Server Error.

Visual tetap mengikuti desain minimal monochrome.

# 26. ADMIN UI DIRECTION

Public website menggunakan visual premium editorial.

Admin dashboard boleh lebih utilitarian karena tujuan utamanya adalah efisiensi pekerjaan.

Admin boleh memiliki:

- Sidebar.
- Table.
- Form.
- Pagination.
- Modal konfirmasi.
- Search/filter.
- Status badge semantic.

Namun tetap gunakan:

- Square corners.
- Minimal shadow.
- Typography bersih.
- White/Carbon/Fog sebagai visual utama.

# 27. FILE/FOLDER RECOMMENDATION

```text
app/
├── Http/Controllers/
├── Http/Requests/
├── Models/
├── Policies/
└── Services/

resources/
├── views/
├── css/
└── js/

routes/
├── web.php
└── auth.php

database/
├── migrations/
├── seeders/
└── factories/

public/
└── images/
```

Pisahkan business logic dari Blade. Blade hanya bertugas menampilkan data.

# 28. RECOMMENDED SERVICE LAYER

Untuk logika yang mulai kompleks, gunakan service class.

Contoh:

```text
app/Services/
├── VehicleService.php
├── PromotionService.php
├── CreditSimulationService.php
└── WhatsAppService.php
```

`CreditSimulationService` dapat digunakan untuk menjaga formula simulasi kredit tetap terpisah dari controller.

# 29. CREDIT SIMULATION SERVICE

Contoh sederhana:

```php
class CreditSimulationService
{
    public function calculate(float $price, float $dp, int $tenor, float $annualRate): array
    {
        $principal = max(0, $price - $dp);
        $monthlyInterest = $principal * (($annualRate / 100) / 12);
        $monthlyPayment = ($principal / $tenor) + $monthlyInterest;

        return [
            'principal' => $principal,
            'monthly_payment' => $monthlyPayment,
            'total_payment' => $monthlyPayment * $tenor,
        ];
    }
}
```

Validasi harus memastikan:

- DP tidak negatif.
- DP tidak melebihi harga kendaraan.
- Tenor lebih dari 0.
- Harga lebih dari 0.

# 30. WHATSAPP URL

Gunakan format:

```php
$phone = preg_replace('/\\D+/', '', $sales->phone);
$message = urlencode($message);
$url = "https://wa.me/{$phone}?text={$message}";
```

Jangan hardcode nomor WhatsApp pada Blade.

# 31. SEEDER / DEMO DATA

Buat sample data agar AI Agent dapat menjalankan website tanpa input manual terlalu banyak.

Minimum:

- 3 kategori kendaraan.
- 6 kendaraan.
- 2 promo.
- 6–10 foto kendaraan/demo gallery.
- 2 sales.
- 1 konfigurasi dealer.
- 1 akun admin development.

Gunakan placeholder image yang legal dan mudah diganti. Jangan menggunakan logo/aset BMW untuk demo production.

# 32. ADMIN CRUD UX

Setiap CRUD harus mempunyai:

```text
List
 ├── Search
 ├── Filter
 ├── Add New
 ├── Edit
 ├── View
 └── Delete
```

Form harus mempunyai:

- Label.
- Placeholder bila membantu.
- Validation message.
- Old input value.
- Error handling.
- Success flash message setelah berhasil.

# 33. PAGINATION

Katalog kendaraan gunakan pagination Laravel.

Contoh:

```php
Vehicle::query()
    ->where('status', 'available')
    ->latest()
    ->paginate(12)
    ->withQueryString();
```

Filter/search tidak boleh hilang saat berpindah halaman.

# 34. CONTENT RULES

Website harus menggunakan bahasa Indonesia sebagai bahasa utama kecuali klien kemudian meminta bahasa lain.

Gaya copywriting:

- Profesional.
- Ringkas.
- Informatif.
- Tidak berlebihan.
- Tidak menggunakan klaim yang tidak didukung data.

Harga, spesifikasi dan status kendaraan harus berasal dari database.

# 35. DESIGN DO / DON'T

## DO

- Gunakan foto mobil besar.
- Pertahankan whitespace.
- Gunakan warna hitam/putih sebagai visual utama.
- Gunakan typographic hierarchy yang tenang.
- Gunakan layout presisi.
- Gunakan square geometry.
- Gunakan CTA yang jelas untuk detail dan WhatsApp.
- Buat katalog mudah dicari.
- Buat dashboard admin mudah digunakan.

## DON'T

- Jangan menggunakan gradient neon.
- Jangan membuat card rounded besar.
- Jangan menggunakan shadow berlebihan.
- Jangan membuat UI seperti marketplace murah.
- Jangan menampilkan terlalu banyak informasi dalam satu layar.
- Jangan membuat landing page hanya berupa gambar tanpa jalur menuju katalog/detail/contact.
- Jangan menambahkan checkout atau payment gateway.
- Jangan menambahkan akun pelanggan kecuali kebutuhan bisnis berubah.
- Jangan hardcode data kendaraan di Blade.
- Jangan hardcode kontak sales.

# 36. PUBLIC PAGE FLOW

```text
HOME
  ↓
KENDARAAN UNGGULAN
  ↓
DETAIL KENDARAAN
  ├── SIMULASI KREDIT
  └── WHATSAPP SALES

HOME
  ↓
PROMO
  ↓
DETAIL PROMO
  ↓
KENDARAAN TERKAIT
  ↓
WHATSAPP SALES
```

# 37. ADMIN FLOW

```text
LOGIN
  ↓
DASHBOARD
  ├── KENDARAAN
  │    ├── KATEGORI
  │    ├── FOTO
  │    └── STATUS
  ├── PROMO
  ├── GALERI
  ├── DEALER
  └── SALES / WHATSAPP
```

# 38. IMPLEMENTATION ORDER FOR AI AGENT

AI Agent harus membangun project bertahap, bukan sekaligus menghasilkan seluruh kode secara acak.

### Phase 1 — Laravel foundation

- Setup Laravel.
- Environment.
- Database.
- Authentication.
- Layout Blade.
- Base CSS/tokens.

### Phase 2 — Database

- Migrations.
- Models.
- Relationships.
- Factories.
- Seeders.

### Phase 3 — Public pages

- Home.
- About/Profile.
- Catalog.
- Detail vehicle.
- Search/filter.
- Promo.
- Gallery.
- Contact.

### Phase 4 — Functional features

- WhatsApp generator.
- Credit simulation.
- Pagination.
- Image gallery.

### Phase 5 — Admin dashboard

- Dashboard.
- Vehicle CRUD.
- Category CRUD.
- Promotion CRUD.
- Gallery CRUD.
- Dealer settings.
- Sales.

### Phase 6 — Hardening

- Validation.
- Authorization.
- Upload security.
- Error pages.
- SEO.
- Accessibility.
- Performance.

### Phase 7 — Testing

- Feature tests.
- CRUD tests.
- Search tests.
- Credit simulation tests.
- Authentication tests.
- Upload tests.
- Responsive/manual UI testing.

# 39. ACCEPTANCE CRITERIA

Website dianggap memenuhi kebutuhan apabila:

1. Pengunjung dapat membuka beranda.
2. Pengunjung dapat melihat profil dealer.
3. Pengunjung dapat melihat katalog kendaraan.
4. Pengunjung dapat mencari kendaraan.
5. Pengunjung dapat membuka detail kendaraan.
6. Detail kendaraan menampilkan harga, spesifikasi, foto dan status.
7. Pengunjung dapat melihat promo.
8. Pengunjung dapat melakukan simulasi kredit.
9. Pengunjung dapat menghubungi sales melalui WhatsApp.
10. Pengunjung dapat melihat galeri.
11. Admin dapat login.
12. Admin dapat melakukan CRUD kendaraan.
13. Admin dapat mengelola foto kendaraan.
14. Admin dapat mengelola kategori.
15. Admin dapat mengelola promo.
16. Admin dapat mengelola galeri.
17. Admin dapat mengelola informasi dealer.
18. Admin dapat mengelola kontak sales/WhatsApp.
19. Website responsive pada desktop, tablet dan smartphone.
20. Tidak ada data penting yang hardcoded pada public Blade.

# 40. FINAL AGENT INSTRUCTION

Bangun website dealer mobil berbasis Laravel/PHP dengan pendekatan **premium automotive gallery**.

Gunakan visual yang terinspirasi oleh karakter BMW.com yang minimal dan elegan, tetapi **jangan menyalin identitas BMW**. Gunakan brand, logo, tagline, kendaraan, foto dan konten milik dealer sebagai data production.

Prioritaskan urutan berikut:

```text
Functionality
→ Data structure
→ Admin management
→ User experience
→ Visual polish
→ Performance
→ Accessibility
```

Jangan membuat fitur di luar proposal tanpa alasan teknis yang jelas.

Semua data kendaraan, promo, galeri, dealer dan sales harus bersumber dari database Laravel dan dapat dikelola dari dashboard admin.

Gunakan Laravel conventions, Eloquent relationships, Form Request validation, Policies/Middleware, Blade Components, pagination dan service layer bila diperlukan.

Hasil akhir harus terasa seperti **showroom mobil premium digital**, bukan marketplace, bukan blog, dan bukan dashboard yang dipublikasikan ke pelanggan.
