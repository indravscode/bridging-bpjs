# Bridging BPJS untuk PHP dan Laravel: VClaim, PCare, Aplicare, Antrean Online, i-Care

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)
[![Latest Version](https://img.shields.io/packagist/v/indravscode/bridging-bpjs.svg?style=flat-square)](https://packagist.org/packages/indravscode/bridging-bpjs)
[![Total Downloads](https://img.shields.io/packagist/dt/indravscode/bridging-bpjs.svg?style=flat-square)](https://packagist.org/packages/indravscode/bridging-bpjs)

`indravscode/bridging-bpjs` adalah package PHP untuk integrasi Web Service BPJS Kesehatan pada aplikasi Laravel. Package ini ditujukan bagi developer SIMRS, aplikasi klinik, dan sistem informasi fasilitas kesehatan yang membutuhkan akses ke VClaim, PCare, Aplicare, Antrean Online, dan i-Care.

## Layanan yang tersedia

| Layanan | Cakupan dalam package | Panduan |
| --- | --- | --- |
| VClaim | Referensi, peserta, SEP, rujukan, monitoring, dan lembar pengajuan klaim | [Integrasi VClaim](#vclaim) |
| PCare | Peserta, pendaftaran, kunjungan, diagnosa, obat, tindakan, dan referensi layanan FKTP | [Integrasi PCare](#pcare) |
| Aplicare | Referensi kelas serta baca, tambah, ubah, dan hapus data ketersediaan kamar | [Integrasi Aplicare](#aplicare) |
| Antrean Online BPJS | Referensi poli dan dokter serta tambah, panggil, dan batal antrean FKTP | [Integrasi Antrean FKTP](#antrean-fktp) |
| i-Care | Validasi i-Care FKTP dan FKRTL | [Integrasi i-Care](#i-care) |

## Daftar isi

- [Persyaratan dan kompatibilitas](#persyaratan-dan-kompatibilitas)
- [Instalasi](#instalasi)
- [Mulai menggunakan di Laravel](#mulai-menggunakan-di-laravel)
- [Contoh nilai parameter](#contoh-nilai-parameter)
- [VClaim](#vclaim)
- [Aplicare](#aplicare)
- [PCare](#pcare)
- [Antrean FKTP](#antrean-fktp)
- [i-Care](#i-care)
- [Katalog Web Service BPJS](#katalog-web-service-bpjs)
- [Contributing](#contributing)
- [Manual Installation](#manual-installation)
- [Inspirations](#inspirations)
- [License](#license)

## Persyaratan dan kompatibilitas

- Composer untuk instalasi package dan dependensi.
- Dependensi HTTP: `guzzlehttp/guzzle >=6.0`, sesuai `composer.json`.
- Dekompresi respons PCare dan i-Care menggunakan `nullpunkt/lz-string-php ^1.3`, yang diinstal otomatis oleh Composer.
- Kredensial dan akses Web Service BPJS untuk setiap layanan yang digunakan. Sesuaikan base URL dan nama layanan dengan environment yang diberikan BPJS.

Contoh di bawah menggunakan helper Laravel seperti `env()`, `config()`, dan `response()`. Pada aplikasi PHP di luar Laravel, berikan array konfigurasi langsung ke constructor kelas layanan dan tangani hasilnya sesuai aplikasi Anda.

## Instalasi

```bash
composer require indravscode/bridging-bpjs
```

## Mulai menggunakan di Laravel

Contoh berikut mengambil referensi provinsi melalui VClaim.

1. Tambahkan konfigurasi berikut ke `.env`. Ganti nilai contoh kredensial dengan kredensial VClaim Anda, lalu sesuaikan URL dan nama layanan dengan akses BPJS yang digunakan.

```env
BPJS_CONSID="your-cons-id"
BPJS_SECRET_KEY="your-secret-key"
BPJS_BASE_URL="https://apijkn.bpjs-kesehatan.go.id"
BPJS_SERVICE_NAME="vclaim-rest"
BPJS_USER_KEY="your-user-key"
```

2. Buat file `config/bpjs.php` agar konfigurasi dapat dibaca melalui `config()` pada aplikasi yang menggunakan cache konfigurasi Laravel.

```php
<?php

return [
    'vclaim' => [
        'cons_id' => env('BPJS_CONSID'),
        'secret_key' => env('BPJS_SECRET_KEY'),
        'base_url' => env('BPJS_BASE_URL'),
        'service_name' => env('BPJS_SERVICE_NAME'),
        'user_key' => env('BPJS_USER_KEY'),
    ],
];
```

3. Tambahkan route berikut ke `routes/web.php` pada aplikasi Laravel untuk mencoba permintaan pertama.

```php
use Bridging\Bpjs\VClaim\Referensi;
use Illuminate\Support\Facades\Route;

Route::get('/bpjs/vclaim/provinsi', function () {
    $referensi = new Referensi(config('bpjs.vclaim'));

    return response()->json($referensi->propinsi());
});
```

Buka `/bpjs/vclaim/provinsi` pada aplikasi Anda. Route ini mengembalikan hasil pemanggilan layanan BPJS sebagai JSON. Keberhasilan permintaan bergantung pada kredensial, akses jaringan, dan ketersediaan layanan BPJS.

## Contoh nilai parameter

Contoh value:

```php
$nomorKartu = '0000039043765';
$nomorKunjungan = '0114A0260815Y000003';
$kodeDokter = '123123';
$kodeDiagnosa = 'A001';
$jenisKartu = 'nik'; // 'noka' atau 'nik'
$kodeJenisKelompok = 01;
$kodeTkp = 10;
$kodeSpesialis = 'ANA';
$kodeSubSpesialis = '26';
$kodeSarana = '1';
$kodeKhusus = 'THA'; // ["THA","HEM"]
$keywordObat = '38250800016'; // Kode atau Nama Obat DPHO
$bulanKegiatan = '15-01-2026';
$educationId = '16020000001';
$nomorUrut = 'A1';
$tanggalDaftar = '31-01-2026';
$tanggalRujuk = '17-01-2026';
$indeksMulai = 1;
$batasData = 10;
```

## VClaim

Untuk konfigurasi `.env`, file `config/bpjs.php`, dan contoh route, ikuti [panduan mulai menggunakan di Laravel](#mulai-menggunakan-di-laravel).

Gunakan konfigurasi yang sama saat membuat kelas VClaim lainnya, misalnya untuk mengambil data peserta:

```php
use Bridging\Bpjs\VClaim\Peserta;

$peserta = new Peserta(config('bpjs.vclaim'));
$hasil = $peserta->getByNoKartu($nomorKartu, $tanggalPelayananSep);
```

Isi `$nomorKartu` dengan nomor kartu peserta dan `$tanggalPelayananSep` dengan tanggal pelayanan berformat `YYYY-MM-DD`.

Kelas VClaim yang tersedia: `Referensi`, `Peserta`, `Sep`, `Rujukan`, `Monitoring`, dan `LembarPengajuanKlaim`. Lihat implementasi di [`src/VClaim`](src/VClaim) untuk metode dan parameternya.

## Aplicare

Aplicare menyediakan metode untuk referensi kelas dan pengelolaan data ketersediaan kamar.

Tambahkan variabel berikut ke `.env`. Isi semua nilai dengan kredensial, base URL, dan nama layanan Aplicare yang diberikan BPJS; nilai kosong berikut merupakan placeholder.

```env
BPJS_APLICARE_CONSID=""
BPJS_APLICARE_SECRET_KEY=""
BPJS_APLICARE_BASE_URL=""
BPJS_APLICARE_SERVICE_NAME=""
BPJS_APLICARE_USER_KEY=""
```

Tambahkan entri `aplicare` ke array konfigurasi dalam `config/bpjs.php`:

```php
'aplicare' => [
    'cons_id' => env('BPJS_APLICARE_CONSID'),
    'secret_key' => env('BPJS_APLICARE_SECRET_KEY'),
    'base_url' => env('BPJS_APLICARE_BASE_URL'),
    'service_name' => env('BPJS_APLICARE_SERVICE_NAME'),
    'user_key' => env('BPJS_APLICARE_USER_KEY'),
],
```

Ambil referensi kelas kamar:

```php
use Bridging\Bpjs\Aplicare\KetersediaanKamar;

$kamar = new KetersediaanKamar(config('bpjs.aplicare'));
$hasil = $kamar->refKelas();
```

## PCare

Tambahkan ke file `.env`:

```env
BPJS_PCARE_CONSID="2112121"
BPJS_PCARE_SECRET_KEY="1a2b1a2b1a2b"
BPJS_PCARE_USERNAME="username-pcare"
BPJS_PCARE_PASSWORD="password-pcare"
BPJS_PCARE_APP_CODE="012"
BPJS_PCARE_BASE_URL="https://apijkn.bpjs-kesehatan.go.id"
BPJS_PCARE_SERVICE_NAME="pcare-rest"
BPJS_PCARE_USER_KEY="1a2b3c1a2b3c"
```

```php
use Bridging\Bpjs\PCare;

function pcare_config(){
    $config = [
            'cons_id'      => env('BPJS_PCARE_CONSID'),
            'secret_key'   => env('BPJS_PCARE_SECRET_KEY'),
            'username'     => env('BPJS_PCARE_USERNAME'),
            'password'     => env('BPJS_PCARE_PASSWORD'),
            'app_code'     => env('BPJS_PCARE_APP_CODE'),
            'base_url'     => env('BPJS_PCARE_BASE_URL'),
            'service_name' => env('BPJS_PCARE_SERVICE_NAME'),
            'user_key' => env('BPJS_PCARE_USER_KEY'),
    ];
    return $config;
}

// Diagnosa - Get Diagnosa
$bpjs = new PCare\Diagnosa($this->pcare_config());
return $bpjs->keyword($kodeDiagnosa)->index($indeksMulai, $batasData);

// Dokter - Get Dokter
$bpjs = new PCare\Dokter($this->pcare_config());
return $bpjs->index($indeksMulai, $batasData);

// Kesadaran - Get Kesadaran
$bpjs = new PCare\Kesadaran($this->pcare_config());
return $bpjs->index();

// Kunjungan - Get Rujukan
$bpjs = new PCare\Kunjungan($this->pcare_config());
return $bpjs->rujukan($nomorKunjungan)->index();

// Kunjungan - Get Riwayat Kunjungan
$bpjs = new PCare\Kunjungan($this->pcare_config());
return $bpjs->riwayat($nomorKartu)->index();

// Get MCU
$bpjs = new PCare\Mcu($this->pcare_config());
return $bpjs->kunjungan($nomorKunjungan)->index();

// Obat - Get DPHO
$bpjs = new PCare\Obat($this->pcare_config());
return $bpjs->dpho($keywordObat)->index($indeksMulai, $batasData);

// Obat - Get Obat by Kunjungan
$bpjs = new PCare\Obat($this->pcare_config());
return $bpjs->kunjungan($nomorKunjungan)->index();

// Pendaftaran - Get Pendaftaran by Nomor Urut
$bpjs = new PCare\Pendaftaran($this->pcare_config());
return $bpjs->nomorUrut($nomorUrut)->tanggalDaftar($tanggalDaftar)->index($indeksMulai, $batasData);

// Pendaftaran - Get Pendaftaran Provider
$bpjs = new PCare\Pendaftaran($this->pcare_config());
return $bpjs->tanggalDaftar($tanggalDaftar)->index($indeksMulai, $batasData);

// Peserta - Get Peserta
$bpjs = new PCare\Peserta($this->pcare_config());
return $bpjs->keyword($nomorKartu)->show();

// Peserta - Get Peserta by Jenis Kartu ["nik" || "noka"]
$bpjs = new PCare\Peserta($this->pcare_config());
return $bpjs->jenisKartu($jenisKartu)->keyword($nomorKartu)->show();

// Poli - Get Poli FKTP
$bpjs = new PCare\Poli($this->pcare_config());
return $bpjs->fktp()->index($indeksMulai, $batasData);

// Provider - Get Provider Rayonisasi
$bpjs = new PCare\Provider($this->pcare_config());
return $bpjs->index($indeksMulai, $batasData);

// Tindakan - Get Referensi Tindakan
$bpjs = new PCare\Tindakan($this->pcare_config());
return $bpjs->kodeTkp($kodeTkp)->index($indeksMulai, $batasData);

// Tindakan - Get Tindakan by Kunjungan
$bpjs = new PCare\Tindakan($this->pcare_config());
return $bpjs->kunjungan($nomorKunjungan)->index();

// Kelompok - Get Club Prolanis
$bpjs = new PCare\Kelompok($this->pcare_config());
return $bpjs->club($kodeJenisKelompok)->index();

// Kelompok - Get Kegiatan Kelompok
$bpjs = new PCare\Kelompok($this->pcare_config());
return $bpjs->kegiatan($bulanKegiatan)->index();

// Kelompok - Get Peserta Kegiatan Kelompok
$bpjs = new PCare\Kelompok($this->pcare_config());
return $bpjs->peserta($educationId)->index();

// Spesialis - Get Referensi Spesialis
$bpjs = new PCare\Spesialis($this->pcare_config());
return $bpjs->index();

// Spesialis - Get Referensi Sub Spesialis
$bpjs = new PCare\Spesialis($this->pcare_config());
return $bpjs->keyword($kodeSpesialis)->subSpesialis()->index();

// Spesialis - Get Referensi Sarana
$bpjs = new PCare\Spesialis($this->pcare_config());
return $bpjs->sarana()->index();

// Spesialis - Get Referensi Khusus
$bpjs = new PCare\Spesialis($this->pcare_config());
return $bpjs->khusus()->index();

// Spesialis - Get Faskes Rujukan Sub Spesialis
$bpjs = new PCare\Spesialis($this->pcare_config());
return $bpjs->rujuk()->subSpesialis($kodeSubSpesialis)->sarana($kodeSarana)->tanggalRujuk($tanggalRujuk)->index();

// Spesialis - Get Faskes Rujukan Khusus THALASEMIA dan HEMOFILI
$bpjs = new PCare\Spesialis($this->pcare_config());
return $bpjs->rujuk()->khusus($kodeKhusus)->subSpesialis($kodeSubSpesialis)->nomorKartu($nomorKartu)->tanggalRujuk($tanggalRujuk)->index();

// Skrining - Get Skrining Riwayat Kesehatan by Penyakit
$bpjs = new PCare\Skrining($this->pcare_config());
return $bpjs->rekap()->index();

// Skrining - Get Detail Peserta Skrining Riwayat Kesehatan
$bpjs = new PCare\Skrining($this->pcare_config());
return $bpjs->peserta()->keyword($nomorAtauNamaPeserta)->index($indeksMulai, $batasData);

// Skrining - Get Data Prolanis Diabetes Mellitus
$bpjs = new PCare\Skrining($this->pcare_config());
return $bpjs->prolanisDm()->keyword($nomorAtauNamaPeserta)->index($indeksMulai, $batasData);

// Skrining - Get Data Prolanis Hipertensi
$bpjs = new PCare\Skrining($this->pcare_config());
return $bpjs->prolanisHt()->keyword($nomorAtauNamaPeserta)->index($indeksMulai, $batasData);
```

## Antrean FKTP

Tambahkan ke file `.env`:

```env
BPJS_ANTREAN_CONSID="2112121"
BPJS_ANTREAN_SECRET_KEY="1a2b1a2b1a2b"
BPJS_ANTREAN_BASE_URL="https://apijkn.bpjs-kesehatan.go.id"
BPJS_ANTREAN_SERVICE_NAME="antrean"
BPJS_ANTREAN_USER_KEY="your-antrean-user-key"
```

```php
use Bridging\Bpjs\Antrean;

function antrean_config(){
    $config = [
        'cons_id'      => env('BPJS_ANTREAN_CONSID'),
        'secret_key'   => env('BPJS_ANTREAN_SECRET_KEY'),
        'base_url'     => env('BPJS_ANTREAN_BASE_URL'),
        'service_name' => env('BPJS_ANTREAN_SERVICE_NAME'),
        'user_key'     => env('BPJS_ANTREAN_USER_KEY'),
    ];
    return $config;
}

// Referensi Poli (GET)
$bpjs = new Antrean\ReferensiPoli($this->antrean_config());
return $bpjs->tanggal('2024-01-03')->show();

// Referensi Dokter (GET)
$bpjs = new Antrean\ReferensiDokter($this->antrean_config());
return $bpjs->kodePoli('001')->tanggal('2024-01-03')->show();

// Tambah Antrean (POST)
$bpjs = new Antrean\WSBPJS($this->antrean_config());
$data = [
    'nomorkartu' => '000012345678',
    'nik' => '3212345678987654',
    'nohp' => '085635228888',
    'kodepoli' => 'ANA',
    'namapoli' => 'Anak',
    'norm' => '123345',
    'tanggalperiksa' => '2021-01-28',
    'kodedokter' => 12345,
    'namadokter' => 'Dr. Hendra',
    'jampraktek' => '08:00-16:00',
    'nomorantrean' => 'A-12',
    'angkaantrean' => 12,
    'keterangan' => '',
];
return $bpjs->add()->store($data);

// Update Status / Panggil Antrean (POST)
$bpjs = new Antrean\WSBPJS($this->antrean_config());
$data = [
    'tanggalperiksa' => '2024-03-01',
    'kodepoli' => '001',
    'nomorkartu' => '0000034563234',
    'status' => 1,
    'waktu' => 1616559330000,
];
return $bpjs->panggil()->store($data);

// Batal Antrean (POST)
$bpjs = new Antrean\WSBPJS($this->antrean_config());
$data = [
    'tanggalperiksa' => '2024-01-03',
    'kodepoli' => '001',
    'nomorkartu' => '0000045258563',
    'alasan' => 'Terjadi perubahan jadwal dokter',
];
return $bpjs->batal()->store($data);
```

## i-Care

Tambahkan ke file `.env`:

```env
BPJS_ICARE_CONSID="2112121"
BPJS_ICARE_SECRET_KEY="1a2b1a2b1a2b"
BPJS_ICARE_USERNAME="username-icare"
BPJS_ICARE_PASSWORD="password-icare"
BPJS_ICARE_APP_CODE="012"
BPJS_ICARE_BASE_URL="https://apijkn.bpjs-kesehatan.go.id"
BPJS_ICARE_SERVICE_NAME="ihs"
BPJS_ICARE_USER_KEY="1a2b3c1a2b3c"
```

```php
use Bridging\Bpjs\ICare;

function icare_config(){
    $config = [
        'cons_id'      => env('BPJS_ICARE_CONSID'),
        'secret_key'   => env('BPJS_ICARE_SECRET_KEY'),
        'username'     => env('BPJS_ICARE_USERNAME'),
        'password'     => env('BPJS_ICARE_PASSWORD'),
        'app_code'     => env('BPJS_ICARE_APP_CODE'),
        'base_url'     => env('BPJS_ICARE_BASE_URL'),
        'service_name' => env('BPJS_ICARE_SERVICE_NAME'),
        'user_key'     => env('BPJS_ICARE_USER_KEY'),
    ];
    return $config;
}

// FKTP validate
$bpjs = new ICare\FKTP($this->icare_config());
return $bpjs->validate($nomorKartu);

// FKRTL validate
$bpjs = new ICare\FKRTL($this->icare_config());
return $bpjs->validate($nomorKartu, $kodeDokter);
```

## Katalog Web Service BPJS:

- https://dvlp.bpjs-kesehatan.go.id:8888/trust-mark/login.html

## Contributing

Untuk melaporkan kerentanan keamanan secara privat, ikuti [kebijakan keamanan](SECURITY.md).

Semua kontribusi sangat kami apresiasi dan terbuka untuk diterima! Silakan lihat [panduan kontribusi](CONTRIBUTING.md).

Jika Anda menemukan package ini bermanfaat, pertimbangkan untuk memberikan bintang! ⭐

## Manual Installation

Clone the repo:

```bash
git clone --depth 1 https://github.com/indravscode/bridging-bpjs.git
cd bridging-bpjs
rm -rf ./.git
```

Install the dependencies:

```bash
composer install
```

## Inspirations

- [aamdsam/bridging-bpjs](https://github.com/aamdsam/bridging-bpjs)

## License

[MIT](LICENSE)
