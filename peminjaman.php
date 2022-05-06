<?php

include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Buku.class.php");
include("includes/Member.class.php");
include("includes/Peminjaman.class.php");


$buku = new Buku($db_host, $db_user, $db_pass, $db_name);
$member = new Member($db_host, $db_user, $db_pass, $db_name);
$peminjaman = new Peminjaman($db_host, $db_user, $db_pass, $db_name);

$buku->open();
$member->open();
$peminjaman->open();


$data=null;
$option_member = null;
$option_buku = null;
$no = 1;

if (isset($_POST['add'])) { //jika tombol add di klik
    //memanggil add
    $peminjaman->add($_POST);
    header("location:peminjaman.php");
}

if (!empty($_GET['id_hapus'])) { ///jika tombol hapus di klik
    
    $id = $_GET['id_hapus'];
    //memanggil delete
    $peminjaman->delete($id);
    header("location:peminjaman.php");
}

if (!empty($_GET['id_edit'])) { //jika tombol edit di klik

    $id = $_GET['id_edit'];

    //memanggil statusPinjam
    $peminjaman->statusPinjam($id);
    header("location:peminjaman.php");
}


// simpan data tabel peminjaman
$peminjaman->getPeminjaman();
while (list($id, $nim_member, $id_buku, $status) = $peminjaman->getResult()) {
    
    $buku->getJudulBuku($id_buku);
    $member->getMemberDetail($nim_member);

    if ($status == "Sudah Kembali") {
        $data .= "<tr>
            <td>" . $no++ . "</td>
            <td>" . $member->getResult()['nama']. "</td>
            <td>" . $buku->getResult()['judul_buku'] . "</td>
            <td>" . $status . "</td>
            <td>
            <a href='peminjaman.php?id_hapus=" . $id . "' class='btn btn-danger' '>Hapus</a>
            </td>
            </tr>";
    }
    else {
        $data .= "<tr>
            <td>" . $no++ . "</td>
            <td>" . $member->getResult()['nama'] . "</td>
            <td>" . $buku->getResult()['judul_buku'] . "</td>
            <td>" . $status . "</td>
            <td>
            <a href='peminjaman.php?id_edit=" . $id .  "' class='btn btn-warning' '>Kembalikan</a>
            <a href='peminjaman.php?id_hapus=" . $id . "' class='btn btn-danger' '>Hapus</a>
            </td>
            </tr>";
    }
}

// get data for input option member
$member->getMember();
while (list($nim, $nama, $jurusan) = $member->getResult()) {

    $option_member .= "<option value='".$nim."'>".$nama."</option>";
}

// get data for input option buku
$buku->getBuku();
while (list($id, $judul, $penerbit, $deskripsi, $status, $id_author) = $buku->getResult()) {
    
    $peminjaman->getPeminjaman();
    $status_buku = 0;

    while (list($id_peminjaman, $nim_member, $id_buku, $status_peminjam) = $peminjaman->getResult()) {
        
        if($id == $id_buku){ // jika id pada tabel buku ditemukan di id_buku tabel peminjaman

            if($status_peminjam == "Belum Kembali"){ // jika buku berstatus belum kembali
                $status_buku = 1;
            }else{ //jika buku berstatus sudah kembali
                $status_buku = 0;
            }
        }
    }

    if($status_buku == 0){
        $option_buku .= "<option value='".$id."'>".$judul."</option>";
    }else{
        // jika buku berstatus belum kembali/masih dipinjam buat option disabled
        $option_buku .= "<option value='".$id."' disabled>".$judul."</option>";
    }


}


$member->close();
$buku->close();
$peminjaman->close();
$tpl = new Template("templates/peminjaman.html");
$tpl->replace("OPTION_MEMBER", $option_member);
$tpl->replace("OPTION_BUKU", $option_buku);
$tpl->replace("DATA_TABEL", $data);
$tpl->write();

