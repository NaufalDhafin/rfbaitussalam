<?php 
    include "app/conf.php";
    include "app/google/auth.php";
    session_start();
    if(isset($_SESSION['userid'])){
        echo "<meta http-equiv='refresh' content='0;dashboard/'>";
    }
    else{
        echo "";
    }
    if(isset($_GET['code'])){
        $codeGoogle = $_GET['code'];
        echo "<meta http-equiv='refresh' content='0;app/auth.php?google=$codeGoogle'>";
    }
    $google = $google_client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appname ?> - Masuk & Daftar</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/style/main.css">
    <link rel="stylesheet" href="assets/style/login.css">
</head>
<body>
    <div class="navbar bg-base-100 nav-color">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow nav-color">
                    
                </ul>
            </div>
            <a class="btn btn-ghost text-xl"><?= $appname ?></a>
        </div>
        <div class="navbar-center hidden lg:flex">
            
        </div>
        <div class="navbar-end">
            <!-- <a class="btn">Masuk / Daftar</a> -->
        </div>
    </div>
    <?php 
        if(isset($_GET['alert'])){
            $apsn = $_GET['alert'];
            $alert = "<div class='text'><p>$apsn</p></div>";
        }
        else{
            $alert = "";
        }
        if(isset($_GET['act'])){
            $act = $_GET['act'];
            if($act == "continue"){
                $userid = $_GET['userid'];
    ?>
    <form method="post">
        <div class="atas">
            <img src="assets/img/logo.png" alt="">
            <p>Isi Data Selanjutnya</p>
        </div>
        <?= $alert ?>
        <div class="inputs">
            <label for="">
                <p>Whatsapp</p>
                <input type="number" name="whatsapp">
            </label>
            <label for="">
                <p>Nomor KTP</p>
                <input type="number" name="ktp">
            </label>
            <label for="">
                <p>Password</p>
                <input type="password" name="password">
            </label>
        </div>
        <button type="submit" name="confirm">Selesai</button>
    </form>
    <?php
                if(isset($_POST['confirm'])){
                    $whatsapp   = $_POST['whatsapp'];
                    $ktp        = $_POST['ktp'];
                    $password   = md5($_POST['password']);
                    $query      = $conf->query("UPDATE users SET whatsapp = '$whatsapp', ktp = '$ktp', password = '$password' WHERE userid = '$userid'");
                    if($query){
                        echo "<meta http-equiv='refresh' content='0;dashboard/'>";
                    }
                    else{
                        echo "";
                    }

                }
            }
            elseif($act == "register"){
        ?>
    <form method="post">
        <div class="atas">
            <img src="assets/img/logo.png" alt="">
            <p>Daftar Akun {NAMA APLIKASI}</p>
        </div>
        <?= $alert ?>
        <div class="inputs">
            <label for="">
                <p>Nama Lengkap</p>
                <input type="text" name="fullname" required>
            </label>
            <label for="">
                <p>Whatsapp</p>
                <input type="number" name="whatsapp" required>
            </label>
            <label for="">
                <p>Email</p>
                <input type="email" name="email">
            </label>
            <label for="">
                <p>Nomor KTP</p>
                <input type="number" name="ktp" required>
            </label>
            <label for="">
                <p>Password</p>
                <input type="password" name="password" required>
            </label>
            <label for="">
                <p>Konfirmasi Password</p>
                <input type="password" name="cpassword" required>
            </label>
            <label for="" id="link">
                <div class="term">
                    <input type="checkbox" name="term" class="checkbox-success" required checked>
                    <a href="">Syarat dan Ketentuan</a>
                </div>
                <a href="login.php">Sudah Punya Akun? Masuk</a>
            </label>
        </div>
        <button type="submit" name="confirm">Daftar Sekarang</button>
        <p class="or">Atau</p>
        <a href="<?= $google?>" class="google">
            <img src="assets/img/google.webp">
            <p>Daftar Dengan Google</p>
        </a>
    </form>
        <?php
                if(isset($_POST['confirm'])){
                    $fullname   = $_POST['fullname'];
                    $whatsapp   = $_POST['whatsapp'];
                    $email      = $_POST['email'];
                    $ktp        = $_POST['ktp'];
                    $password   = md5($_POST['password']);
                    $cpassword  = md5($_POST['cpassword']);
                    if($password == $cpassword){
                        $conf->query("INSERT INTO users SET userid = '$create_userid', fullname = '$fullname', ktp = '$ktp', whatsapp = '$whatsapp', email = '$email', password = '$cpassword', levels = 'member', otp = ''");
                        echo "<meta http-equiv='refresh' content='0;dashboard/'>";
                    }
                    else{
                        echo "<meta http-equiv='refresh' content='0;?act=register&alert=Password Tidak Sama'>";
                    }
                }
            }
            elseif($act == "forgot"){
        ?>
    <form action="" method="post">
        <div class="atas">
            <img src="assets/img/logo.png" alt="">
            <p>Lupa Password</p>
        </div>
        <?= $alert ?>
        <div class="inputs">
            <label for="">
                <p>Whatsapp</p>
                <input type="text" name="whatsapp">
            </label>
        </div>
        <button type="submit" name="confirm">Kirim OTP</button>
    </form>
        <?php
                if(isset($_POST['confirm'])){
                    $whatsapp = $_POST['whatsapp'];
                    $query = $conf->query("SELECT * FROM users WHERE whatsapp = '$whatsapp'");
                    if($query->num_rows > 0){
                        $ruser = $query->fetch_array();
                        $userid = $ruser['userid'];
                        $otp = random_int(111111,999999);
                        $conf->query("UPDATE users SET otp = '$otp' WHERE userid = '$userid'");
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                          CURLOPT_URL => 'https://api.fonnte.com/send',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS => array(
                        'target' => "$whatsapp",
                        'message' => "JANGAN BERITAHU SIAPA PUN! 
                        Jika bukan anda yang melakukan maka hiraukanlah.
                        
                        kode OTP: $otp"
                        ),
                          CURLOPT_HTTPHEADER => array(
                            'Authorization: Lz#NVM@nC2_P1!wNCyhZ'
                          ),
                        ));

                        $response = curl_exec($curl);
                        echo "<meta http-equiv='refresh' content='0;?act=forgot2&userid=$userid'>";
                    }
                    else{
                        $userid = "";
                        echo "<meta http-equiv='refresh' content='0;?act=forgot&alert=Whatsapp Tidak Terdaftar'>";
                    }
                }
                
            }
            elseif($act == "forgot2"){
                $userid = $_GET['userid'];
        ?>
        <form action="" method="post">
        <div class="atas">
            <img src="assets/img/logo.png" alt="">
            <p>Konfirmasi OTP</p>
        </div>
        <?= $alert ?>
        <div class="inputs">
            <label for="">
                <p>Password Baru</p>
                <input type="password" name="password">
            </label>
            <label for="">
                <p>Kode OTP</p>
                <input type="text" name="otp">
            </label>
            <label for="" style="display:none;">
                <input type="text" name="userid" value="<?= $userid?>">
            </label>
        </div>
        <button type="submit" name="confirm">Konfirmasi OTP</button>
    </form>
        <?php
                if(isset($_POST['confirm'])){
                    $password = md5($_POST['password']);
                    $otp = $_POST['otp'];
                    $userid = $_POST['userid'];
                    $cekuser = $conf->query("SELECT * FROM users WHERE userid = '$userid'");
                    if($cekuser->num_rows > 0){
                        $cekotp = $conf->query("SELECT * FROM users WHERE userid = '$userid' AND otp = '$otp'");
                        if($cekotp->num_rows < 1){
                            echo "<meta http-equiv='refresh' content='0;?act=forgot2&userid=$userid&alert=KODE OTP SALAH'>";
                        }
                        else{
                            $conf->query("UPDATE users SET password = '$password', otp = '' WHERE userid = '$userid'");
                            echo "<meta http-equiv='refresh' content='0;login.php'>";
                        }
                    }
                    else{
                        echo "<meta http-equiv='refresh' content='0;?act=forgot2&userid=$userid&alert=Ada masalah dari sistem'>";
                    }
                }
            
            }
        }
        else{
    ?>
<form action="" method="post">
        <div class="atas">
            <img src="assets/img/logo.png" alt="">
            <p>Masuk Ke <?= $appname ?></p>
        </div>
        <?= $alert ?>
        <div class="inputs">
            <label for="">
                <p>Email / Whatsapp</p>
                <input type="text" name="input">
            </label>
            <label for="">
                <p>Password</p>
                <input type="password" name="password">
            </label>
            <label for="" id="link">
                <a href="login.php?act=register">Belum Punya Akun? Daftar</a>
                <a href="login.php?act=forgot">Lupa Password?</a>
            </label>
        </div>
        <button type="submit" name="confirm">Masuk Sekarang</button>
        <p class="or">Atau</p>
        <a href="<?= $google?>" class="google">
            <img src="assets/img/google.webp">
            <p>Masuk Dengan Google</p>
        </a>
    </form>
    <?php
        if(isset($_POST['confirm'])){
            $input = $_POST['input'];
            $password = md5($_POST['password']);
            $cekuser = $conf->query("SELECT * FROM users WHERE email = '$input' OR whatsapp = '$input' AND password = '$password'");
            if($cekuser->num_rows > 0){
                $rows = $cekuser->fetch_array();
                session_start();
                $_SESSION['userid'] = $rows['userid'];
                echo "<meta http-equiv='refresh' content='0;dashboard/'>";
            }
            else{
                echo "<meta http-equiv='refresh' content='0;?alert=Email, Whatsapp atau Password Tidak ditemukan'>";
            }

        }
        }
    ?>
</body>

</html>