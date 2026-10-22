<?php
/**
 * MINI APPLICATION OOP
 * SISTEM PENJADWALAN LATIHAN PADUAN SUARA
 *
 * TUGAS RUMAH PERTEMUAN 5 - ADVANCED OOP
 *
 * 1. DOMAIN
 * Sistem Penjadwalan Latihan Paduan Suara adalah program
 * untuk mengelola data anggota, pelatih, dan jadwal latihan.
 *
 * 2. KANDIDAT CLASS
 *
 * a. Personel
 *    Class induk abstrak yang menyimpan ID dan nama personel.
 *
 * b. Anggota
 *    Class untuk menyimpan data anggota dan jenis suara.
 *    Mewarisi Personel dan menerapkan interface notifikasi.
 *
 * c. Pelatih
 *    Class untuk menyimpan data pelatih dan keahliannya.
 *    Mewarisi Personel dan menerapkan interface notifikasi.
 *
 * d. JadwalLatihan
 *    Class untuk mengelola jadwal, tanggal, lokasi,
 *    materi lagu, pelatih, dan daftar anggota.
 *
 * 3. RELASI ANTARCLASS
 * - Inheritance: Anggota dan Pelatih mewarisi Personel.
 * - Interface: Anggota dan Pelatih menerapkan
 *   BisaDiberiNotifikasi.
 * - Composition/relasi kepemilikan: JadwalLatihan
 *   menyimpan referensi Pelatih dan daftar Anggota.
 *
 * 4. KONSEP ADVANCED OOP
 * - Abstract class: Personel.
 * - Overriding: getPeran() dan tampilkanInfo().
 * - Polymorphism: tampilkanPersonel().
 * - Encapsulation: properti menggunakan private.
 * - Validasi: memeriksa input dan menangani kesalahan.
 */

echo "<pre style='font-family: Consolas, monospace; font-size: 14px;'>";

// INTERFACE: kontrak untuk objek yang dapat menerima notifikasi.
interface BisaDiberiNotifikasi
{
    public function kirimNotifikasi(string $pesan): string;
}

// ABSTRACT CLASS: class dasar untuk Anggota dan Pelatih.
abstract class Personel
{
    private string $id;
    private string $nama;

    public function __construct(string $id, string $nama)
    {
        $this->validasiTeks($id, 'ID');
        $this->validasiTeks($nama, 'Nama');
        $this->id = trim($id);
        $this->nama = trim($nama);
    }

    protected function validasiTeks(string $nilai, string $field): void
    {
        if (trim($nilai) === '') {
            throw new InvalidArgumentException("$field tidak boleh kosong.");
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    abstract public function getPeran(): string;
    abstract public function tampilkanInfo(): string;
}

// INHERITANCE: Anggota mewarisi Personel.
class Anggota extends Personel implements BisaDiberiNotifikasi
{
    private string $jenisSuara;

    public function __construct(string $id, string $nama, string $jenisSuara)
    {
        parent::__construct($id, $nama);
        $this->validasiTeks($jenisSuara, 'Jenis suara');
        $this->jenisSuara = trim($jenisSuara);
    }

    public function getJenisSuara(): string
    {
        return $this->jenisSuara;
    }

    // OVERRIDING: implementasi khusus untuk anggota.
    public function getPeran(): string
    {
        return 'Anggota Paduan Suara';
    }

    public function tampilkanInfo(): string
    {
        return 'ID: ' . $this->getId()
            . ' | Nama: ' . $this->getNama()
            . ' | Jenis suara: ' . $this->jenisSuara
            . ' | Peran: ' . $this->getPeran();
    }

    public function kirimNotifikasi(string $pesan): string
    {
        $this->validasiTeks($pesan, 'Pesan notifikasi');
        return 'Notifikasi kepada ' . $this->getNama() . ': ' . trim($pesan);
    }
}

// INHERITANCE: Pelatih juga mewarisi Personel.
class Pelatih extends Personel implements BisaDiberiNotifikasi
{
    private string $keahlian;

    public function __construct(string $id, string $nama, string $keahlian)
    {
        parent::__construct($id, $nama);
        $this->validasiTeks($keahlian, 'Keahlian');
        $this->keahlian = trim($keahlian);
    }

    public function getKeahlian(): string
    {
        return $this->keahlian;
    }

    // OVERRIDING: implementasi khusus untuk pelatih.
    public function getPeran(): string
    {
        return 'Pelatih';
    }

    public function tampilkanInfo(): string
    {
        return 'ID: ' . $this->getId()
            . ' | Nama: ' . $this->getNama()
            . ' | Keahlian: ' . $this->keahlian
            . ' | Peran: ' . $this->getPeran();
    }

    public function kirimNotifikasi(string $pesan): string
    {
        $this->validasiTeks($pesan, 'Pesan notifikasi');
        return 'Notifikasi kepada pelatih ' . $this->getNama() . ': ' . trim($pesan);
    }
}

// COMPOSITION: jadwal memiliki satu objek Pelatih dan beberapa objek Anggota.
class JadwalLatihan
{
    private string $idLatihan;
    private string $tanggal;
    private string $lokasi;
    private string $materiLagu;
    private Pelatih $pelatih;
    /** @var Anggota[] */
    private array $anggota = [];

    public function __construct(
        string $idLatihan,
        string $tanggal,
        string $lokasi,
        string $materiLagu,
        Pelatih $pelatih
    ) {
        $this->validasiTeks($idLatihan, 'ID latihan');
        $this->validasiTanggal($tanggal);
        $this->validasiTeks($lokasi, 'Lokasi');
        $this->validasiTeks($materiLagu, 'Materi lagu');

        $this->idLatihan = trim($idLatihan);
        $this->tanggal = trim($tanggal);
        $this->lokasi = trim($lokasi);
        $this->materiLagu = trim($materiLagu);
        $this->pelatih = $pelatih;
    }

    private function validasiTeks(string $nilai, string $field): void
    {
        if (trim($nilai) === '') {
            throw new InvalidArgumentException("$field tidak boleh kosong.");
        }
    }

    private function validasiTanggal(string $tanggal): void
    {
        $objTanggal = DateTime::createFromFormat('!Y-m-d', trim($tanggal));
        $error = DateTime::getLastErrors();

        if (
            $objTanggal === false
            || ($error !== false && ($error['warning_count'] > 0 || $error['error_count'] > 0))
            || $objTanggal->format('Y-m-d') !== trim($tanggal)
        ) {
            throw new InvalidArgumentException('Tanggal harus valid dengan format YYYY-MM-DD.');
        }
    }

    public function tambahAnggota(Anggota $anggota): void
    {
        $this->anggota[] = $anggota;
    }

    public function tampilkanJadwal(): void
    {
        echo "ID Latihan : {$this->idLatihan}\n";
        echo "Tanggal    : {$this->tanggal}\n";
        echo "Lokasi     : {$this->lokasi}\n";
        echo "Materi Lagu: {$this->materiLagu}\n";
        echo "Pelatih    : {$this->pelatih->getNama()}\n";
        echo "Jumlah anggota: " . count($this->anggota) . "\n";
    }

    public function kirimPengingat(): void
    {
        echo "\n[ NOTIFIKASI LATIHAN ]\n";

        // POLYMORPHISM: anggota dan pelatih diproses melalui interface yang sama.
        $penerima = $this->anggota;
        $penerima[] = $this->pelatih;

        foreach ($penerima as $orang) {
            echo $orang->kirimNotifikasi(
                "Latihan paduan suara tanggal {$this->tanggal} di {$this->lokasi}."
            ) . "\n";
        }
    }

    public function konfirmasiKehadiran(Anggota $anggota, string $status, string $keterangan = ''): void
    {
        $statusDiizinkan = ['Hadir', 'Izin', 'Berhalangan'];
        $status = trim($status);
        $keterangan = trim($keterangan);

        if (!in_array($status, $statusDiizinkan, true)) {
            throw new InvalidArgumentException('Status harus Hadir, Izin, atau Berhalangan.');
        }

        if (in_array($status, ['Izin', 'Berhalangan'], true) && $keterangan === '') {
            throw new InvalidArgumentException('Keterangan wajib diisi untuk status Izin atau Berhalangan.');
        }

        echo $anggota->getNama() . ' - Status: ' . $status;
        if ($keterangan !== '') {
            echo ' | Keterangan: ' . $keterangan;
        }
        echo "\n";
    }
}

// FUNGSI POLYMORPHISM: menerima Personel, tetapi hasilnya mengikuti objek konkret.
function tampilkanPersonel(Personel $personel): void
{
    echo $personel->tampilkanInfo() . "\n";
}

function ujiValidasi(string $judul, callable $aksi): void
{
    echo "\n$judul\n";
    try {
        $aksi();
        echo "Hasil: BERHASIL\n";
    } catch (Throwable $error) {
        echo "Hasil: ERROR TERDETEKSI\n";
        echo "Pesan: " . $error->getMessage() . "\n";
    }
}

// PROGRAM UTAMA
try {
    echo "==================================================\n";
    echo " SISTEM PENJADWALAN LATIHAN PADUAN SUARA\n";
    echo "==================================================\n";

    $pelatih = new Pelatih('P001', 'Maestro Andre', 'Dirigen dan Vokal Klasik');
    $anggota1 = new Anggota('A001', 'Lidya Nauli', 'Soprano');
    $anggota2 = new Anggota('A002', 'Budi Santoso', 'Tenor');

    echo "\n[ DATA PERSONEL: CONTOH POLYMORPHISM ]\n";
    $daftarPersonel = [$pelatih, $anggota1, $anggota2];
    foreach ($daftarPersonel as $personel) {
        tampilkanPersonel($personel);
    }

    $jadwal = new JadwalLatihan(
        'JL001', '2026-10-25', 'Studio Musik Kampus',
        'Lagu Wajib Nasional', $pelatih
    );
    $jadwal->tambahAnggota($anggota1);
    $jadwal->tambahAnggota($anggota2);

    echo "\n[ JADWAL LATIHAN ]\n";
    $jadwal->tampilkanJadwal();
    $jadwal->kirimPengingat();

    echo "\n[ KONFIRMASI KEHADIRAN ]\n";
    $jadwal->konfirmasiKehadiran($anggota1, 'Hadir');
    $jadwal->konfirmasiKehadiran($anggota2, 'Izin', 'Ada praktikum di kampus');

    echo "\n[ PENGUJIAN VALIDASI ]\n";
    ujiValidasi('1. Menguji nama anggota kosong', function (): void {
        new Anggota('A003', '   ', 'Alto');
    });

    ujiValidasi('2. Menguji tanggal tidak valid', function () use ($pelatih): void {
        new JadwalLatihan('JL002', '2026-02-30', 'Aula Kampus', 'Lagu Daerah', $pelatih);
    });

    ujiValidasi('3. Menguji status kehadiran tidak valid', function () use ($jadwal, $anggota1): void {
        $jadwal->konfirmasiKehadiran($anggota1, 'Mungkin');
    });

    ujiValidasi('4. Menguji izin tanpa keterangan', function () use ($jadwal, $anggota2): void {
        $jadwal->konfirmasiKehadiran($anggota2, 'Izin');
    });
} catch (Throwable $error) {
    echo "\nKESALAHAN PROGRAM: " . $error->getMessage() . "\n";
} finally {
    echo "\n==================================================\n";
    echo " PROGRAM SELESAI DIJALANKAN\n";
    echo "==================================================\n";
    echo '</pre>';
}
?>
