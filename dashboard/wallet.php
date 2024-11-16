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
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> <?= $sesiUser ?> </title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/profile.css">
    <link rel="stylesheet" href="style/wallet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php include "../assets/sidebar.php" ?>
        <div class="atas">
            <div class="kotak" id="prop">
                <div class="a">
                    <img src="../assets/img/prop.png" alt="">
                    <div class="text">
                        <p id="name">JUMLAH PROP DIMIKILI</p>
                        <p id="jumlah">
                            <?= str_replace(".",",",$rusers['coin'])?>
                            PROP
                        </p>
                    </div>
                </div>
                <div class="b">
                    <a id="tkr" href="buytoken.php">Beli Koin</a>
                </div>
            </div>
            <div class="kotak" id="prop">
                <div class="a">
                    <img src="../assets/img/57421944_1371636006308255_3647136573922738176_n.webp" alt="">
                    <div class="text">
                        <p id="name">SALDO RUPIAH</p>
                        <p id="jumlah">
                            <?php 
                                $jmlCoin = $rusers['coin'];
                                $convert = $jmlCoin * $rpPerToken;
                                echo rp($convert);
                            ?>
                        </p>
                    </div>
                </div>
                <div class="b">
                    <?php 
                        if($convert >= 10000){
                    ?>
                        <a id="tkr" href="withdraw.php">Tarik Rupiah</a>
                    <?php 
                        }
                        else{
                    ?>
                        <a style="background:gray;" id="tkr">Tarik Rupiah</a>
                    <?php
                        }
                    ?>
                    
                </div>
            </div>
        </div>
        <div class="riwayat">
            <p id="jdl">Riwayat Transaksi Koin</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipe</th>
                        <th>Keterangan</th>
                        <th>Jumlah Token</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while($rows = $tcoin->fetch_array()){
                    ?>
                    <tr>
                        <td data-column="ID"><?= $rows['trxid'] ?></td>
                        <td data-column="Tipe"><?= $rows['type'] ?></td>
                        <td data-column="Keterangan"><?= $rows['note'] ?></td>
                        <td data-column="Jumlah Token">
                        <?php 
                            echo str_replace(".",",",$rows['amount']);
                        ?>
                        </td>
                        <td data-column="Tanggal"><?= $rows['date'] ?></td>
                        <td data-column="Status"><?= $rows['status'] ?></td>
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