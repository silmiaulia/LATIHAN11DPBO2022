<?php

class Member extends DB
{
    function getMember()
    {
        $query = "SELECT * FROM member";
        return $this->execute($query);
    }

    function add($data)
    {
        $nim = $data['tnim'];
        $nama = $data['tnama_member'];
        $jurusan = $data['tjurusan'];

        $query = "insert into member values ('$nim', '$nama', '$jurusan')";

        // Mengeksekusi query
        return $this->execute($query);
    }

    
    function update($data)
    {
        $nim = $data['tnim'];
        $nama = $data['tnama_member'];
        $jurusan = $data['tjurusan'];

        $query = "UPDATE member SET nama = '$nama',
                jurusan = '$jurusan' WHERE nim= '$nim'";

        // Mengeksekusi query
        return $this->execute($query);
    }

    function delete($nim){

        $query = "DELETE FROM member WHERE nim = '$nim'";

        // Mengeksekusi query
        return $this->execute($query);
    }


    function getMemberDetail($nim){

        $query = "SELECT * FROM member where nim = '$nim'";
        return $this->execute($query);
    }

}