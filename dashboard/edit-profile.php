<?php 
    include "../app/conf.php";
    session_start();
    if(isset($_SESSION['userid'])){
        $sesiUser = $_SESSION['userid'];
        $cekSesiUser = $conf->query("SELECT * FROM users WHERE userid = '$sesiUser'");
        if($cekSesiUser->num_rows < 1){
            echo '<meta http-equiv="refresh" content="0;../login.php?sesi=nouser">';
        }
        else{
            $users  = $conf->query("SELECT * FROM users WHERE userid = '$sesiUser'");
            $tcoin  = $conf->query("SELECT * FROM trx_coin WHERE userid = '$sesiUser' AND type = 'jual' OR type = 'beli'");
            $rusers = $users->fetch_array(); 
        }
    }
    else{
        echo '<meta http-equiv="refresh" content="0;../login.php?sesi=nosesi">';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nama User</title>
    <link rel="stylesheet" href="style/edit-profile.css">
</head>

<body>
    <form method="post">
        <div class="atas">
            <p>Ubah Rekening</p>
            <span>Nama pemilik rekening harus sama dengan yang terdaftar</span>
        </div>
        <div class="input">
            <label for="">
                <p>Pilih BANK</p>
                <select name="bankname">
                    <?php 
                        include "../app/bank.php";
                        $bank = file_get_contents("../app/json/bank.json");
                        foreach(json_decode($bank, true) as $bankname){
                            $nbank = substr($bankname['name'], 3);
                            echo "<option value='$nbank'>$nbank</option>";
                        }
                    ?>
                </select>
            </label>
            <label for="">
                <p>Nama Pemilik Rekening </p>
                <input type="text" name="fullname" value="<?= $rusers['fullname']?>" readonly>
            </label>
            <label for="">
                <p>Nomor Rekening </p>
                <input type="number" name="rekening">
            </label>
        </div>
        <button type="submit" name="confirm">UBAH REKENING SEKARANG</button>
    </form>
    <?php 
        if(isset($_POST['confirm'])){
            $bankname = $_POST['bankname'];
            $fullname = $_POST['fullname'];
            $rekening = $_POST['rekening'];

            $conf->query("UPDATE rekening SET bankname = '$bankname', fullname = '$fullname', rekening = '$rekening' WHERE userid = '$sesiUser'");
            echo '<meta http-equiv="refresh" content="0;profile.php">';
        }
    ?>
</body>

</html>