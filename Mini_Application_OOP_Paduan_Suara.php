<?php
/**
 * MINI APPLICATION OOP
 * SISTEM PENJADWALAN LATIHAN PADUAN SUARA
 *
 * TUGAS RUMAH PERTEMUAN 5 - ADVANCED OOP
 *
 * 1. DOMAIN
 * Program untuk mengelola data anggota, pelatih, dan jadwal latihan.
 *
 * 2. KANDIDAT CLASS
 * - Personel: class induk abstrak yang menyimpan ID dan nama.
 * - Anggota: menyimpan data anggota dan jenis suara.
 * - Pelatih: menyimpan data pelatih dan keahliannya.
 * - JadwalLatihan: mengelola jadwal, pelatih, dan anggota latihan.
 *
 * 3. RELASI ANTARCLASS
 * - Inheritance: Anggota dan Pelatih mewarisi Personel.
 * - Interface: Anggota dan Pelatih menerapkan BisaDiberiNotifikasi.
 * - Composition: JadwalLatihan membuat dan mengelola objek Pelatih
 *   serta Anggota sebagai bagian dari jadwal.
 *
 * 4. KONSEP ADVANCED OOP
 * - Abstract class: Personel.
 * - Overriding: getPeran() dan tampilkanInfo().
 * - Polymorphism: tampilkanPersonel() dan kirimPengingat().
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

// COMPOSITION: JadwalLatihan membuat dan mengelola objek Pelatih dan Anggota.
class JadwalLatihan
{
    private string $idLatihan;
    private string $tanggal;
    private string $lokasi;
    private string $materiLagu;
    private Pelatih $pelatih;

    /** @var Anggota[] */
    private array $anggota = [];

    /**
     * $dataPelatih berisi id, nama, dan keahlian.
     * $daftarAnggota berisi data anggota: id, nama, dan jenisSuara.
     */
    public function __construct(
        string $idLatihan,
        string $tanggal,
        string $lokasi,
        string $materiLagu,
        array $dataPelatih,
        array $daftarAnggota = []
    ) {
        $this->validasiTeks($idLatihan, 'ID latihan');
        $this->validasiTanggal($tanggal);
        $this->validasiTeks($lokasi, 'Lokasi');
        $this->validasiTeks($materiLagu, 'Materi lagu');

        $this->idLatihan = trim($idLatihan);
        $this->tanggal = trim($tanggal);
        $this->lokasi = trim($lokasi);
        $this->materiLagu = trim($materiLagu);

        // Objek Pelatih dibuat di dalam JadwalLatihan.
        $this->pelatih = new Pelatih(
            $dataPelatih['id'] ?? '',
            $dataPelatih['nama'] ?? '',
            $dataPelatih['keahlian'] ?? ''
        );

        // Objek Anggota dibuat dan disimpan dalam jadwal ini.
        foreach ($daftarAnggota as $data) {
            $this->anggota[] = new Anggota(
                $data['id'] ?? '',
                $data['nama'] ?? '',
                $data['jenisSuara'] ?? ''
            );
        }
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

    public function tampilkanJadwal(): void
    {
        echo "ID Latihan : {$this->idLatihan}\n";
        echo "Tanggal    : {$this->tanggal}\n";
        echo "Lokasi     : {$this->lokasi}\n";
        echo "Materi Lagu: {$this->materiLagu}\n";
        echo "Pelatih    : {$this->pelatih->getNama()}\n";
        echo "Jumlah anggota: " . count($this->anggota) . "\n";
    }

    public function tampilkanDataPersonelJadwal(): void
    {
        echo "\n[ DATA PERSONEL DALAM JADWAL ]\n";
        echo $this->pelatih->tampilkanInfo() . "\n";

        foreach ($this->anggota as $anggota) {
            echo $anggota->tampilkanInfo() . "\n";
        }
    }

    public function kirimPengingat(): void
    {
        echo "\n[ NOTIFIKASI LATIHAN ]\n";

        // POLYMORPHISM: anggota dan pelatih menggunakan interface yang sama.
        $penerima = $this->anggota;
        $penerima[] = $this->pelatih;

        foreach ($penerima as $orang) {
            echo $orang->kirimNotifikasi(
                "Latihan paduan suara tanggal {$this->tanggal} di {$this->lokasi}."
            ) . "\n";
        }
    }

    public function konfirmasiKehadiran(
        string $idAnggota,
        string $status,
        string $keterangan = ''
    ): void {
        $statusDiizinkan = ['Hadir', 'Izin', 'Berhalangan'];
        $status = trim($status);
        $keterangan = trim($keterangan);

        if (!in_array($status, $statusDiizinkan, true)) {
            throw new InvalidArgumentException('Status harus Hadir, Izin, atau Berhalangan.');
        }

        if (in_array($status, ['Izin', 'Berhalangan'], true) && $keterangan === '') {
            throw new InvalidArgumentException('Keterangan wajib diisi untuk status Izin atau Berhalangan.');
        }

        foreach ($this->anggota as $anggota) {
            if ($anggota->getId() === $idAnggota) {
                echo $anggota->getNama() . ' - Status: ' . $status;

                if ($keterangan !== '') {
                    echo ' | Keterangan: ' . $keterangan;
                }

                echo "\n";
                return;
            }
        }

        throw new InvalidArgumentException("Anggota dengan ID $idAnggota tidak ditemukan pada jadwal ini.");
    }
}

// FUNGSI POLYMORPHISM: menerima Personel dan menampilkan informasi sesuai objek.
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

    // Jadwal membuat objek Pelatih dan Anggota di dalam constructor.
    $jadwal = new JadwalLatihan(
        'JL001',
        '2026-10-25',
        'Studio Musik Kampus',
        'Lagu Wajib Nasional',
        [
            'id' => 'P001',
            'nama' => 'Maestro Andre',
            'keahlian' => 'Dirigen dan Vokal Klasik'
        ],
        [
            [
                'id' => 'A001',
                'nama' => 'Lidya Nauli',
                'jenisSuara' => 'Soprano'
            ],
            [
                'id' => 'A002',
                'nama' => 'Budi Santoso',
                'jenisSuara' => 'Tenor'
            ]
        ]
    );

    echo "\n[ DATA PERSONEL ]\n";
    $jadwal->tampilkanDataPersonelJadwal();

    echo "\n[ JADWAL LATIHAN ]\n";
    $jadwal->tampilkanJadwal();
    $jadwal->kirimPengingat();

    echo "\n[ KONFIRMASI KEHADIRAN ]\n";
    $jadwal->konfirmasiKehadiran('A001', 'Hadir');
    $jadwal->konfirmasiKehadiran('A002', 'Izin', 'Ada praktikum di kampus');

    echo "\n[ PENGUJIAN VALIDASI ]\n";

    ujiValidasi('1. Menguji nama anggota kosong', function (): void {
        new Anggota('A003', '   ', 'Alto');
    });

    ujiValidasi('2. Menguji tanggal tidak valid', function (): void {
        new JadwalLatihan(
            'JL002',
            '2026-02-30',
            'Aula Kampus',
            'Lagu Daerah',
            [
                'id' => 'P002',
                'nama' => 'Pelatih Uji',
                'keahlian' => 'Vokal'
            ]
        );
    });

    ujiValidasi('3. Menguji status kehadiran tidak valid', function () use ($jadwal): void {
        $jadwal->konfirmasiKehadiran('A001', 'Mungkin');
    });

    ujiValidasi('4. Menguji izin tanpa keterangan', function () use ($jadwal): void {
        $jadwal->konfirmasiKehadiran('A002', 'Izin');
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
