<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekening</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');

    * {
        margin: 0;
        padding: 0;
    }

    body {
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-family: rubik;
        gap: 10px;
    }

    #judul {
        font-size: 20px;
        color: lightseagreen;
        font-weight: 600;
    }

    #text {
        color: brown;
        width: 90%;
        text-align: center;
        max-width: 500px;
    }

    form {
        max-width: 300px;
        width: 95%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        padding: 10px;
    }

    form label input:focus,
    form label input:focus-visible,
    form label input:hover {
        border: 2px solid rgb(2, 189, 142);
        outline: none;
    }
    form label select:focus,
    form label select:focus-visible,
    form label select:hover {
        border: 2px solid rgb(2, 189, 142);
        outline: none;
    }
    form label{
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    form label p{
        font-size: 15px;
        color: lightseagreen;
    }
    form label select{
        width: 100%;
        height: 35px;
        border: 2px solid transparent;
        border-radius: 5px;
        border-bottom: 2px solid lightseagreen;
        transition: .2s;
        color: lightseagreen;
        font-size: 15px;
    }
    form label select:hover{
        border: 2px solid lightseagreen;
    }
    form label input{
        width: 100%;
        height: 30px;
        border: 2px solid transparent;
        border-radius: 5px;
        border-bottom: 2px solid lightseagreen;
        transition: .2s;
        color: lightseagreen;
        font-size: 15px;
        font-weight: 500;
    }
    form label input:hover{
        border: 2px solid lightseagreen;
    }
    form button{
        width: 100%;
        background: lightseagreen;
        border: none;
        height: 40px;
        border-radius: 10px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        transition: .2s;
    }
    form button:hover{
        background: rgb(25, 158, 151);
        cursor: pointer;
    }

</style>
<?php 
    include "../app/conf.php";
    include "../app/bank.php";
    if(isset($_GET['userid'])){
        $userid = $_GET['userid'];
        $query = $conf->query("SELECT * FROM users WHERE userid = '$userid'");
        $rows  = $query->fetch_array();
        $fullname = $rows['fullname'];
        $bank = file_get_contents("../app/json/bank.json");
    }
    else{
        echo "<meta http-equiv='refresh' content='0;profile.php'>";
    }
?>
<body>
    <p id="judul">DAFTAR REKENING PENARIKAN</p>
    <p id="text">Harap nama pemilik rekening harus sama dengan nama lengkap yang terdaftar</p>
    <form method="post">
        <label>
            <p>Nama Bank</p>
            <select name="bankname">
                <option>Pilih Bank</option>
                <?php 
                foreach(json_decode($bank, true) as $bankname){
                    $nbank = substr($bankname['name'], 3);
                    echo "<option value='$nbank'>$nbank</option>";
                }
                ?>
            </select>
        </label>
        <label>
            <p>Nomor Rekening</p>
            <input type="number" name="rekening">
        </label>
        <label>
            <p>Nama Lengkap</p>
            <input type="text" name="fullname" value="<?= $fullname?>">
        </label>
        <button type="submit" name="confirm">Konfirmasi Rekening</button>
    </form>
    <?php 
        if(isset($_POST['confirm'])){
            $bankname = $_POST['bankname'];
            $rekening = $_POST['rekening'];
            $fullname = $_POST['fullname'];
            $conf->query("INSERT INTO rekening SET userid = '$userid', bankname = '$bankname', fullname = '$fullname', rekening = '$rekening'");
            echo "<meta http-equiv='refresh' content='0;profile.php'>";
        }
    ?>
</body>
</html>