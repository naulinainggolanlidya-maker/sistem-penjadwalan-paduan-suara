# sistem-penjadwalan-paduan-suara

1. Masalah
    Program ini memodelkan penjadwalan latihan paduan suara. Sistem menyimpan data pelatih dan anggota, menampilkan           jadwal, mengirim pengingat, serta mencatat konfirmasi kehadiran.

3. Kandidat class dan kontrak
  - Personel: abstract class untuk data umum personel, yaitu ID dan nama.
  - Anggota: turunan Personel, memiliki jenis suara.
  - Pelatih: turunan Personel, memiliki keahlian.
  - JadwalLatihan: mengelola informasi jadwal, satu objek pelatih, dan daftar anggota.
  - BisaDiberiNotifikasi: interface yang mewajibkan method kirimNotifikasi().
  
3. Alasan memilih relasi
  - Inheritance: Anggota dan Pelatih adalah jenis Personel, sehingga keduanya mewarisi ID dan nama.
  - Overriding: Anggota dan Pelatih mengimplementasikan getPeran() dan tampilkanInfo() dengan hasil yang sesuai dengan         jenis personelnya.
  - Polymorphism: fungsi tampilkanPersonel(Personel $personel) dapat menerima objek Anggota maupun Pelatih. Pengiriman         notifikasi juga memakai kontrak interface yang sama.
  - Abstraction: Personel merupakan abstract class yang menetapkan method abstrak getPeran() dan tampilkanInfo().
  - Interface: BisaDiberiNotifikasi menetapkan kontrak notifikasi untuk anggota dan pelatih.
  - Komposisi: JadwalLatihan menyimpan objek Pelatih dan beberapa objek Anggota sebagai bagian dari data jadwal.
  
4. Validasi dan penanganan error
    Program menolak teks kosong, tanggal yang tidak valid, status kehadiran selain Hadir, Izin, atau Berhalangan, serta       status Izin atau Berhalangan tanpa keterangan. Kesalahan ditangani menggunakan try-catch-finally dan ditampilkan dalam     bagian pengujian.

5. Cara menjalankan
  1. File Simpan Mini_Application_OOP_Paduan_Suara.php.
  2. Jalankan menggunakan PHP 8 atau lebih baru, atau buka melalui server lokal seperti XAMPP.
  3. Jika memakai terminal, jalankan php Mini_Application_OOP_Paduan_Suara.php. Untuk tampilan HTML dengan tag <pre>,           jalankan melalui browser lewat server PHP lokal.
  4. Ambil screenshot output yang memperlihatkan data personel, jadwal, notifikasi, kehadiran, dan pengujian validasi.
   
6. Refleksi
    Konsep Advanced OOP membantu memisahkan tanggung jawab class. Inheritance mengurangi pengulangan data umum, overriding   memberi perilaku khusus, dan polymorphism memungkinkan objek berbeda diproses dengan cara pemanggilan yang sama.           Abstract class dan interface menetapkan kontrak, sedangkan composition menyusun jadwal dari objek yang dibutuhkan.         Validasi membantu mencegah data yang tidak sesuai aturan.
