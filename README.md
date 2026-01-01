# Bridging BPJS (VClaim, Aplicare, PCare, i-Care) for Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

Package PHP untuk bridging beberapa Web Service BPJS Kesehatan:

- VClaim
- Aplicare
- PCare
- i-Care

## Installation

```bash
composer require indravscode/bridging-bpjs
```

## Example Values

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

Tambahkan ke file `.env`:

```env
BPJS_CONSID="2112121"
BPJS_SECRET_KEY="121212121"
BPJS_BASE_URL="https://apijkn.bpjs-kesehatan.go.id"
BPJS_SERVICE_NAME="vclaim-rest"
BPJS_USER_KEY="your-user-key"
```

```php
use Bridging\Bpjs\VClaim;

function vclaim_config(){
    $config = [
        'cons_id' => env('BPJS_CONSID'),
        'secret_key' => env('BPJS_SECRET_KEY'),
        'base_url' => env('BPJS_BASE_URL'),
        'service_name' => env('BPJS_SERVICE_NAME'),
        'user_key' => env('BPJS_USER_KEY'),
    ];
    return $config;
}

$referensi = new VClaim\Referensi($this->vclaim_config());
return response($referensi->propinsi());
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
BPJS_PCARE_ANTREAN_USER_KEY="3c2b1a3c2b1a"
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
            'antrean_user_key' => env('BPJS_PCARE_ANTREAN_USER_KEY'),
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
$bpjs = new PCare\Spesialis(pcare_conf());
return $bpjs->rujuk()->khusus($kodeKhusus)->subSpesialis($kodeSubSpesialis)->nomorKartu($nomorKartu)->tanggalRujuk($tanggalRujuk)->index();
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
BPJS_ICARE_ANTREAN_USER_KEY="3c2b1a3c2b1a"
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
        'antrean_user_key' => env('BPJS_ICARE_ANTREAN_USER_KEY'),
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
