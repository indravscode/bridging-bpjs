# BPJS Bridging Vclaim, APlicare, Pcare & I-Care for Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

Bridging VClaim

### Installation

```bash
composer require indravscode/bridging-bpjs
```

### use for VCLAIM

add to `.env` file

```env
BPJS_CONSID="2112121"
BPJS_SCREET_KEY="121212121"
BPJS_BASE_URL="https://new-api.bpjs-kesehatan.go.id:8080"
BPJS_SERVICE_NAME="new-vclaim-rest"
```

```php
use Bridging\Bpjs\VClaim;

function vclaim_conf(){
    $config = [
        'cons_id' => env('BPJS_CONSID'),
        'secret_key' => env('BPJS_SCREET_KEY'),
        'base_url' => env('BPJS_BASE_URL'),
        'service_name' => env('BPJS_SERVICE_NAME'),
    ];
    return $config;
}


$referensi = new VClaim\Referensi($this->vclaim_conf());
return response($referensi->propinsi());
```

### use for PCare

add to `.env` file

```env
BPJS_PCARE_CONSID="2112121"
BPJS_PCARE_SECRET_KEY="1a2b1a2b1a2b"
BPJS_PCARE_USERNAME="username"
BPJS_PCARE_PASSWORD="password"
BPJS_PCARE_APP_CODE="095"
BPJS_PCARE_BASE_URL="https://apijkn.bpjs-kesehatan.go.id"
BPJS_PCARE_SERVICE_NAME="pcare-rest"
BPJS_PCARE_USER_KEY="1a2b3c1a2b3c"
BPJS_PCARE_ANTREAN_USER_KEY="3c2b1a3c2b1a"
```

```php
use Bridging\Bpjs\PCare;

function pcare_conf(){
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

//diagnosa
$bpjs = new PCare\Diagnosa($this->pcare_conf());
return $bpjs->keyword('001')->index(0, 2);

// dokter
$bpjs = new PCare\Dokter($this->pcare_conf());
return $bpjs->index($start, $limit);

// kesadaran
$bpjs = new PCare\Kesadaran($this->pcare_conf());
return $bpjs->index();

// kunjungan rujukan
$bpjs = new PCare\Kunjungan($this->pcare_conf());
return $bpjs->rujukan($nomorKunjungan)->index();
// kunjungan riwayat

$bpjs = new PCare\Kunjungan($this->pcare_conf());
return $bpjs->riwayat($nomorKartu)->index();

// mcu
$bpjs = new PCare\Mcu($this->pcare_conf());
return $bpjs->kunjungan($nomorKunjungan)->index();

// obat dpho
$bpjs = new PCare\Obat($this->pcare_conf());
return $bpjs->dpho($keyword)->index($start, $limit);

// obat kunjungan
$bpjs = new PCare\Obat($this->pcare_conf());
return $bpjs->kunjungan($nomorKunjungan)->index();

// pendaftaran tanggal daftar
$bpjs = new PCare\Pendaftaran($this->pcare_conf());
return $bpjs->tanggalDaftar($tglDaftar)->index($start, $limit);

// pendaftaran nomor urut
$bpjs = new PCare\Diagnosa($this->pcare_conf());
return $bpjs->nomorUrut($nomorUrut)->tanggalDaftar($tanggalDaftar)->index();

// peserta
$bpjs = new PCare\Peserta($this->pcare_conf());
return $bpjs->keyword($keyword)->show();

// peserta jenis kartu ["nik" || "noka"]
$bpjs = new PCare\Peserta($this->pcare_conf());
return $bpjs->jenisKartu($jenisKartu)->keyword($keyword)->show();

// poli
$bpjs = new PCare\Poli($this->pcare_conf());
return $bpjs->fktp()->index($start, $limit);

// provider
$bpjs = new PCare\provider($this->pcare_conf());
return $bpjs->index($start, $limit);

// tindakan kode tkp
$bpjs = new PCare\Tindakan($this->pcare_conf());
return $bpjs->kodeTkp($keyword)->index($start, $limit);

// tindakan kunjungan
$bpjs = new PCare\Tindakan($this->pcare_conf());
return $bpjs->kunjungan($nomorKunjungan)->index();

// kelompok club
$bpjs = new PCare\Kelompok($this->pcare_conf());
return $bpjs->club($kodeJenisKelompok)->index();

// kelompok kegiatan
$bpjs = new PCare\Kelompok($this->pcare_conf());
return $bpjs->kegiatan($bulan)->index();

// kelompok peserta
$bpjs = new PCare\Kelompok($this->pcare_conf());
return $bpjs->peserta($eduId)->index();

// spesialis
$bpjs = new PCare\Spesialis($this->pcare_conf());
return $bpjs->index();

// spesialis sub spesialis
$bpjs = new PCare\Spesialis($this->pcare_conf());
return $bpjs->keyword($keyword)->subSpesialis()->index();

// spesialis sarana
$bpjs = new PCare\Spesialis($this->pcare_conf());
return $bpjs->sarana()->index();

// spesialis khusus
$bpjs = new PCare\Spesialis($this->pcare_conf());
return $bpjs->khusus()->index();

// spesialis rujuk
$bpjs = new PCare\Spesialis($this->pcare_conf());
return $bpjs->rujuk()->subSpesialis($kodeSubSpesialis)->sarana($kodeSarana)->tanggalRujuk($tanggalRujuk)->index();

// spesialis rujuk
$bpjs = new PCare\Spesialis($this->pcare_conf());
return $bpjs->rujuk()->khusus($kodeKhusus)->nomorKartu($nomorKartu)->tanggalRujuk($tanggalRujuk)->index();

// spesialis rujuk
$bpjs = new PCare\Spesialis($this->pcare_conf());
return $bpjs->rujuk()->khusus($kodeKhusus)->subSpesialis($kodeSubSpesialis)->nomorKartu($nomorKartu)->tanggalRujuk($tanggalRujuk)->index();
```

### use for I-Care

add to `.env` file

```env
BPJS_ICARE_CONSID="2112121"
BPJS_ICARE_SECRET_KEY="1a2b1a2b1a2b"
BPJS_ICARE_USERNAME="username"
BPJS_ICARE_PASSWORD="password"
BPJS_ICARE_APP_CODE="095"
BPJS_ICARE_BASE_URL="https://apijkn.bpjs-kesehatan.go.id"
BPJS_ICARE_SERVICE_NAME="ihs_dev"
BPJS_ICARE_USER_KEY="1a2b3c1a2b3c"
BPJS_ICARE_ANTREAN_USER_KEY="3c2b1a3c2b1a"
```

```php
use Bridging\Bpjs\ICare;

function icare_conf(){
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
$bpjs = new ICare\FKTP($this->icare_conf());
return $bpjs->validate($nomorKartu);

// FKRTL validate
$bpjs = new ICare\FKRTL($this->icare_conf());
return $bpjs->validate($nomorKartu, $kodeDokter);
```

Katalog BPJS:

- Vclaim V1.1: https://dvlp.bpjs-kesehatan.go.id/VClaim-Katalog
- Pcare V3: https://new-api.bpjs-kesehatan.go.id/pcare-rest-v3.0

## Contributing

Contributions are more than welcome!

If you find this package useful, consider giving it a star! ⭐

## Inspirations

- [aamdsam/bridging-bpjs](https://github.com/aamdsam/bridging-bpjs)

## License

[MIT](LICENSE)
