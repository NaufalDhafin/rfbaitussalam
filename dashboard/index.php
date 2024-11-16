<?php 
    include "../app/conf.php";
    session_start();
    $sesitrue = "";
    if(isset($_SESSION['userid'])){
        $sesiUser = $_SESSION['userid'];
        $cekSesiUser = $conf->query("SELECT * FROM users WHERE userid = '$sesiUser'");
        if($cekSesiUser->num_rows < 1){
            echo '<meta http-equiv="refresh" content="0;../login.php?sesi=nouser">';
        }
        else{
            $rowsUser = $conf->query("SELECT * FROM users WHERE userid = '$sesiUser'");
            $hisedekah = $conf->query("SELECT * FROM trx_sedekah WHERE userid = '$sesiUser'");
            $totalsdk  = $conf->query("SELECT SUM(amount) FROM trx_sedekah WHERE userid = '$sesiUser'");
            $sesitrue = $rowsUser->fetch_array();
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
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/profile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include "../assets/sidebar.php" ?>
        <div class="sambut">
            <p>Selamat Datang <span><?= $sesitrue['fullname'] ?></span> di RF BAITUSSALAM</p>
        </div>
        <div class="kotak">
                <a href="index.php" class="list">
                    <p id="judul">Transaksi Sedekah</p>
                    <p id="isi">
                        <?php 
                            echo $hisedekah->num_rows;
                        ?>
                    </p>
                    <p id="sub">
                        <?php 
                            if($hisedekah->num_rows > 0){
                                $rtsdk = $totalsdk->fetch_array();
                                echo rp($rtsdk['SUM(amount)']);   
                            }           
                            else{
                                echo rp(0);
                            }       
                        ?>
                    </p>
                </a>
                <a href="wallet.php" class="list">
                    <p id="judul">Token Dimiliki</p>
                    <p id="isi"><?= $sesitrue['coin'] ?> PROP</p>
                    <p id="sub">
                        <?php 
                            $convert = $sesitrue['coin'] * $rpPerToken;
                            echo rp($convert);
                        ?>
                    </p>
                </a>
                <a href="wallet.php" class="list">
                    <p id="judul">Total Token Masuk</p>
                    <p id="isi">
                        <?php 
                            $tcoin = $conf->query("SELECT SUM(amount) FROM trx_coin WHERE userid = '$sesiUser' AND type = 'masuk'");
                            $rtcoin = $tcoin->fetch_array();
                            echo $rtcoin['SUM(amount)'];
                        ?> PROP
                    </p>
                    <p id="sub">
                        <?php 
                            $convert = $rtcoin['SUM(amount)'] * $rpPerToken;
                            echo rp($convert); 
                        ?>
                    </p>
                </a>
                <a href="wallet.php" class="list">
                    <p id="judul">Total Jual Token</p>
                    <p id="isi">
                        <?php 
                            $token = $conf->query("SELECT * FROM trx_coin WHERE userid = '$sesiUser' AND type = 'jual'");
                            echo $token->num_rows;
                        ?> PROP
                    </p>
                    <p id="sub">
                        <?php 
                            $tcoin = $conf->query("SELECT SUM(amount) FROM trx_coin WHERE userid = '$sesiUser' AND type = 'jual'");
                            $rtcoin = $tcoin->fetch_array();
                            $convert = $rtcoin['SUM(amount)'] * $rpPerToken;
                            echo $rtcoin['SUM(amount)']." PROP = ". rp($convert); 
                        ?>
                    </p>
                </a>
                <a href="wallet.php" class="list">
                    <p id="judul">Riwayat Beli Token</p>
                    <p id="isi">
                        <?php 
                            $token = $conf->query("SELECT * FROM trx_coin WHERE userid = '$sesiUser' AND type = 'beli'");
                            echo $token->num_rows;
                        ?> PROP
                    </p> 
                    <p id="sub">
                        <?php 
                            $tcoin = $conf->query("SELECT SUM(amount) FROM trx_coin WHERE userid = '$sesiUser' AND type = 'beli'");
                            $rtcoin = $tcoin->fetch_array();
                            $convert = $rtcoin['SUM(amount)'] * $rpPerToken;
                            echo $rtcoin['SUM(amount)']." PROP = ". rp($convert); 
                        ?>
                    </p>
                </a>
            </div>
        <div class="riwayat">
            <p id="jdl">Riwayat Bersedekah</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jumlah Sedekah</th>
                        <th>Keterangan</th>
                        <th>Hadiah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while($rows = $hisedekah->fetch_array()){
                    ?>
                    <tr>
                        <td data-column="ID"><?= $rows['trxid'] ?></td>
                        <td data-column="Nama"><?= $rows['username'] ?></td>
                        <td data-column="Jumlah Sedekah"><?= rp($rows['amount']) ?></td>
                        <td data-column="Keterangan"><?= $rows['note'] ?></td>
                        <td data-column="Hadiah"><?= $rows['coin'] ?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
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