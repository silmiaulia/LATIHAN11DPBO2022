<?php

class Peminjaman extends DB
{
    function getPeminjaman()
    {
        $query = "SELECT * FROM peminjaman";
        return $this->execute($query);
    }

    function add($data)
    {
        $nim_member = $data['tnama_member'];
        $id_buku = $data['tjudul_buku'];
        $status = "Belum Kembali";

        $query = "insert into peminjaman values ('', '$nim_member', '$id_buku', '$status')";

        // Mengeksekusi query
        return $this->execute($query);
    }

    
    function delete($id)
    {

        $query = "delete FROM peminjaman WHERE id_peminjaman = '$id'";

        // Mengeksekusi query
        return $this->execute($query);
    }

    function statusPinjam($id)
    {

        $status = "Sudah Kembali";
        $query = "update peminjaman set status = '$status' where id_peminjaman = '$id'";

        // Mengeksekusi query
        return $this->execute($query);
    }


}