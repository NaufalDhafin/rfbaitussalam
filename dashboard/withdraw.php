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
        $tcoin  = $conf->query("SELECT * FROM trx_coin WHERE userid = '$sesiUser'");
        $rusers = $users->fetch_array();
        $coinu  = (float)$rusers['coin'];
        if($coinu <= 2.4){
            echo '<meta http-equiv="refresh" content="0;wallet.php">';
        }
        else{
            echo "";
        }
    }
}
else{
    echo '<meta http-equiv="refresh" content="0;../login.php?sesi=nosesi">';
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>RF Baitussalam</title>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.rtl.min.css"/>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/withdraw.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php include "../assets/sidebar.php" ?>
            <?php 
                if(isset($_GET['alert'])){
            ?>
            <div class="bks-ver">
                <div class="verifikasi">
                    <img src="../assets/img/secure.png">
                    <div class="text">
                        <p id="jdl">Gagal Menarik Saldo</p>
                        <p id="sub">Penarikan minimal jumlah token adalah 3</p>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
            <div class="atas">
                <p id="data-jml">Jumlah yang bisa ditarik</p>
                <div class="data">
                    <div class="prop">
                        <p id="jml">Jumlah PROP</p>
                        <p id="isi">
                            <?php 
                                $coins   = $rusers['coin'];
                                echo $coins." PROP";
                            ?>
                        </p>
                    </div>
                    <p id="convert"><i class='bx bx-right-arrow-alt'></i></p>
                    <div class="rp">
                        <p id="jml">Total Rupiah</p>
                        <p id="isi"><?php
                            $convert = $coins * $rpPerToken;
                            echo rp($convert);
                        ?></p>
                    </div>
                </div>
            </div>
            <?php 
                $cekrek = $conf->query("SELECT * FROM rekening WHERE userid = '$sesiUser'");
                if($cekrek->num_rows < 1){
                    $row = $cekrek->fetch_array();
            ?>
            <div class="alert">
                <p>Anda belum menambahkan rekening! <br> <a href="rekening.php?userid=<?= $sesiUser?>">Tambah Sekarang</a></p>
            </div>
            <?php
                }
                else{
                    $row = $cekrek->fetch_array();
            ?>
            <form method="post">
                <div class="input">
                    <label for="">
                        <p>Rekening Tujuan</p>
                        <div>
                            <input type="text" value="<?= $row['rekening']." | ".$row['fullname']?>" readonly>
                        </div>
                    </label>
                    <label for="">
                        <p>Jumlah <span style="font-size:13px;">(minimal 10.000)</span></p>
                        <div>
                            Rp<input type="text" name="amount" id="convert-rp">
                        </div>
                    </label>
                    <button type="submit" name="confirm">Tarik Sekarang</button>
                </div>
            </form>
            <?php
                }
            if(isset($_POST['confirm'])){
                $titik = (int)str_replace(".","",$_POST['amount']);
                $amount     = $titik / $rpPerToken;
                if($titik <= 10000){
                    echo '<meta http-equiv="refresh" content="0;?alert">';
                }
                else{
                    $kurangi    = $rusers['coin'] - $amount;
                    $trx_coin   = $conf->query("INSERT INTO trx_coin SET trxid = '$create_trxid_coin', userid = '$sesiUser', note = 'Jual', type = 'jual', amount = '$kurangi', status = 'menunggu'");
                    $update     = $conf->query("UPDATE users SET coin = '$kurangi' WHERE userid = '$sesiUser'");
                    $uprecoin   = $rremainCoin['remaining'] + $amount;
                    $conf->query("UPDATE coin SET used = '$kurangi', remaining = '$uprecoin'");
                    echo '<meta http-equiv="refresh" content="0;withdraw.php">';
                }
            }
            ?>
            
            
    </section>
    <script type="text/javascript">
        var tanpa_rupiah = document.getElementById('convert-rp');
        tanpa_rupiah.addEventListener('keyup', function (e) {
            tanpa_rupiah.value = formatRupiah(this.value);
        });
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }
    </script>
    <script>
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
                arrowParent.classList.toggle("showMenu");
            });
        }
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        console.log(sidebarBtn);
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    </script>
</body>

</html>