<?php

include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Member.class.php");


$member = new Member($db_host, $db_user, $db_pass, $db_name);
$member->open();


$data_input = null;

if (isset($_POST['add'])) { //jika tombol add di klik
    //memanggil add
    $member->add($_POST);
    header("location:member.php");
}

if (!empty($_GET['id_hapus'])) { //jika tombol hapus di klik
    
    $nim = $_GET['id_hapus'];
    //memanggil delete
    $member->delete($nim);
    header("location:member.php");
}

if (!empty($_GET['id_edit'])) { //jika tombol edit di klik

    $nim = $_GET['id_edit'];
    //memanggil getMemberDetail
    $member->getMemberDetail($nim);
    
    // dapatkan form update member
    while (list($nim, $nama, $jurusan) = $member->getResult()){

        $data_input .= "<h2 class='card-title'>Update Member</h2>
                    <form action='member.php' method='POST'>
                    <div class='form-row'>
                        <div class='form-group col'>
                        <label for='tnama_member'>Nama Member</label>
                        <input type='text' class='form-control' name='tnama_member' value='" . $nama . "'/>
                        </div>
                    </div>

                    <div class='form-row'>
                        <div class='form-group col'>
                        <label for='tnim'>NIM</label>
                        <input type='text' class='form-control' name='tnim' value='" . $nim . "' readonly='readonly' />
                        </div>
                    </div>

                    <div class='form-row'>
                        <div class='form-group col'>
                        <label for='tjurusan'>Jurusan</label>
                        <input type='text' class='form-control' name='tjurusan' value='" . $jurusan . "'/>
                        </div>
                    </div>

                    <button type='submit' name='update' class='btn btn-primary mt-3'>Update</button>
                    </form>";
    }

    
    
}

if (isset($_POST['update'])) { //jika tombol update pada form diklik
    //memanggil update
    $member->update($_POST);
    header("location:member.php");
}

$data = null;
$no = 1;

if (empty($_GET['id_edit'])) { //jika tombol edit belum ditekan

    $data_input .= "<h2 class='card-title'>Add Member</h2>
                    <form action='member.php' method='POST'>
                    <div class='form-row'>
                        <div class='form-group col'>
                        <label for='tnama_member'>Nama Member</label>
                        <input type='text' class='form-control' name='tnama_member' required />
                        </div>
                    </div>

                    <div class='form-row'>
                        <div class='form-group col'>
                        <label for='tnim'>NIM</label>
                        <input type='text' class='form-control' name='tnim' required />
                        </div>
                    </div>

                    <div class='form-row'>
                        <div class='form-group col'>
                        <label for='tjurusan'>Jurusan</label>
                        <input type='text' class='form-control' name='tjurusan' required />
                        </div>
                    </div>

                    <button type='submit' name='add' class='btn btn-primary mt-3'>Add</button>
                    </form>";
}

// simpan data tabel member
$member->getMember();
while (list($nim, $nama, $jurusan) = $member->getResult()){

        $data .= "<tr>
            <td>" . $no++ . "</td>
            <td>" . $nama . "</td>
            <td>" . $nim . "</td>
            <td>" . $jurusan . "</td>
            <td>
            <a href='member.php?id_edit=" . $nim . "' class='btn btn-warning' '>Edit</a>
            <a href='member.php?id_hapus=" . $nim . "' class='btn btn-danger' '>Hapus</a>
            </td>
            </tr>";

}

$member->close();
$tpl = new Template("templates/member.html");
$tpl->replace("DATA_TABEL", $data);
$tpl->replace("DATA_INPUT", $data_input);
$tpl->write();
