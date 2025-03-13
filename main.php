<?php
include 'Model/gaji.php';

// ANSI Escape Code untuk warna terminal
$red = "\e[31m";
$green = "\e[32m";
$yellow = "\e[33m";
$blue = "\e[34m";
$reset = "\e[0m";

function menu() {
    global $blue, $reset;
    echo "\n{$blue}Sistem Manajemen Gaji Karyawan{$reset}\n";
    echo "\n{$blue}1. Lihat karyawan\n";
    echo "2. Tambah karyawan\n";
    echo "3. Update karyawan\n";
    echo "4. Hapus karyawan\n";
    echo "5. Hitung Gaji Karyawan\n";
    echo "6. Keluar aplikasi\n";
}

function lihatKaryawan() {
    global $karyawan, $blue, $reset;
    echo "\n{$blue}Daftar Karyawan:{$reset}\n";
    foreach ($karyawan as $index => $k) {
        echo ($index + 1) . ". Nama: {$k['nama']}, Jabatan: {$k['jabatan']}\n";
    }
}

function tambahKaryawan() {
    global $karyawan, $green, $red, $reset;
    
    echo "\nMasukkan Nama Karyawan: ";
    $nama = trim(fgets(STDIN));
    
    echo "Masukkan Jabatan (Manajer/Supervisor/Staf): ";
    $jabatan = trim(fgets(STDIN));
    
    if (!in_array($jabatan, ['Manajer', 'Supervisor', 'Staf'])) {
        echo "{$red}Jabatan tidak valid!{$reset}\n";
        return;
    }
    
    $karyawan[] = ['nama' => $nama, 'jabatan' => $jabatan];
    simpanData($karyawan);
    echo "{$green}Karyawan berhasil ditambahkan!{$reset}\n";
}

function updateKaryawan() {
    global $karyawan, $yellow, $reset;

    lihatKaryawan();
    echo "Masukkan nomor karyawan yang ingin diupdate: ";
    $index = trim(fgets(STDIN)) - 1;

    if (!isset($karyawan[$index])) {
        echo "{$yellow}Nomor tidak ditemukan!{$reset}\n";
        return;
    }

    echo "Masukkan Nama Baru: ";
    $nama = trim(fgets(STDIN));
    echo "Masukkan Jabatan Baru (Manajer/Supervisor/Staf): ";
    $jabatan = trim(fgets(STDIN));

    if (!in_array($jabatan, ['Manajer', 'Supervisor', 'Staf'])) {
        echo "{$yellow}Jabatan tidak valid!{$reset}\n";
        return;
    }

    $karyawan[$index] = ['nama' => $nama, 'jabatan' => $jabatan];
    simpanData($karyawan);
    echo "{$yellow}Data karyawan diperbarui!{$reset}\n";
}

function hapusKaryawan() {
    global $karyawan, $red, $reset;
    
    lihatKaryawan();
    echo "Masukkan nomor karyawan yang ingin dihapus: ";
    $index = trim(fgets(STDIN)) - 1;
    
    if (!isset($karyawan[$index])) {
        echo "{$red}Nomor tidak ditemukan!{$reset}\n";
        return;
    }
    
    echo "Apakah Anda yakin ingin menghapus? (y/n): ";
    $konfirmasi = trim(fgets(STDIN));
    
    if (strtolower($konfirmasi) == 'y') {
        array_splice($karyawan, $index, 1);
        simpanData($karyawan);
        echo "{$red}Karyawan berhasil dihapus!{$reset}\n";
    } else {
        echo "Penghapusan dibatalkan.\n";
    }
}

function hitungGaji() {
    global $karyawan, $green, $blue, $reset;
    
    lihatKaryawan();
    echo "Pilih nomor karyawan untuk menghitung gaji: ";
    $index = trim(fgets(STDIN)) - 1;

    if (!isset($karyawan[$index])) {
        echo "Nomor tidak ditemukan!\n";
        return;
    }

    echo "Masukkan jumlah jam lembur: ";
    $jamLembur = trim(fgets(STDIN));
    
    echo "Masukkan rating kinerja (1-5): ";
    $rating = trim(fgets(STDIN));
    
    $gajiPokok = 5000000;
    $tunjangan = ($karyawan[$index]['jabatan'] == 'Manajer') ? 2000000 : (($karyawan[$index]['jabatan'] == 'Supervisor') ? 1500000 : 1000000);
    $lembur = $jamLembur * 50000;
    $bonus = $rating * 100000;
    $totalGaji = $gajiPokok + $tunjangan + $lembur + $bonus;

    echo "{$green}Gaji Karyawan:{$reset}\n";
    echo "{$green}Nama: {$karyawan[$index]['nama']}\n";
    echo "Jabatan: {$karyawan[$index]['jabatan']}\n";
    echo "Gaji Pokok: Rp " . number_format($gajiPokok, 0, ',', '.') . "\n";
    echo "Tunjangan: Rp " . number_format($tunjangan, 0, ',', '.') . "\n";
    echo "Lembur: Rp " . number_format($lembur, 0, ',', '.') . "\n";
    echo "Bonus Kinerja: Rp " . number_format($bonus, 0, ',', '.') . "\n";
    echo "Total Gaji: Rp " . number_format($totalGaji, 0, ',', '.') . "\n";
}

while (true) {
    menu();
    echo "Pilih menu: ";
    $pilihan = trim(fgets(STDIN));

    switch ($pilihan) {
        case '1':
            lihatKaryawan();
            break;
        case '2':
            tambahKaryawan();
            break;
        case '3':
            updateKaryawan();
            break;
        case '4':
            hapusKaryawan();
            break;
        case '5':
            hitungGaji();
            break;
        case '6':
            echo "Terimakasih, sampai jumpa!\n";
            exit;
        default:
            echo "{$red}Pilihan tidak valid!{$reset}\n";
    }
}
?>