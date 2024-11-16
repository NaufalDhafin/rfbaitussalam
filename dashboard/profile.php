<?php 
    error_reporting(0);
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
            $banks  = $conf->query("SELECT * FROM rekening WHERE userid = '$sesiUser'");
            $rusers = $users->fetch_array(); 
            $rbanks = $banks->fetch_array();

            
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php include "../assets/sidebar.php" ?>
        <div class="box">
            <div class="profile">
                <p id="judul">Data Pengguna</p>
                <div class="isi">
                    <label for="">
                        <p id="name">ID Pengguna : </p>
                        <p id="isi"><?= $rusers['userid'] ?></p>
                    </label>
                    <label for="">
                        <p id="name">Nama Lengkap : </p>
                        <p id="isi"><?= $rusers['fullname'] ?></p>
                    </label>
                    <label for="">
                        <p id="name">Whatsapp : </p>
                        <p id="isi"><?= $rusers['whatsapp'] ?></p>
                    </label>
                    <label for="">
                        <p id="name">Email : </p>
                        <p id="isi"><?= $rusers['email'] ?></p>
                    </label>
                    <label for="">
                        <p id="name">Verifikasi : </p>
                        <?php 
                            if($rusers['verification'] == "yes"){
                        ?>
                        <p id="isi">Terverifikasi</p>
                        <?php     
                            }
                            else{
                        ?>
                        <a href="" id="isi">Belum Terverifikasi</a>
                        <?php
                            }
                        ?>
                    </label>
                </div>
                <div class="tombol">
                    <a href="">Ubah Data</a>
                </div>
            </div>
            <div class="profile">
                <p id="judul">Rekening Penarikan</p>
                <div class="isi">
                    <label for="">
                        <p id="name">Nama Bank : </p>
                        <?php 
                            if($rbanks['bankname'] == ""){
                                echo "-";
                            }
                            else{
                        ?>
                        <p id="isi"><?= $rbanks['bankname'] ?></p>
                        <?php
                            }
                        ?>
                    </label>
                    <label for="">
                        <p id="name">Nomor Rekening : </p><?php 
                            if($rbanks['rekening'] == ""){
                                echo "-";
                            }
                            else{
                        ?>
                        <p id="isi"><?= $rbanks['rekening'] ?></p>
                        <?php
                            }
                        ?>
                    </label>
                    <label for="">
                        <p id="name">Nama Pemilik : </p><?php 
                            if($rbanks['fullname'] == ""){
                                echo "-";
                            }
                            else{
                        ?>
                        <p id="isi"><?= $rbanks['fullname'] ?></p>
                        <?php
                            }
                        ?>
                    </label>
                    <label for="">
                        <p id="name">Biaya : </p>
                        <p id="isi">Rp 3.200</p>
                    </label>
                    
                </div>
                <div class="tombol">
                    <a href="">Tambah Rekening</a>
                </div>
            </div>
        </div>
        <div class="riwayat">
            <p id="jdl">Riwayat Penarikan</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tujuan</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $withdraw = $conf->query("SELECT * FROM withdraw WHERE userid = '$sesiUser'");
                if($withdraw->num_rows < 1){
                ?>
                    <tr>
                        <td data-column="ID">-</td>
                        <td data-column="Tujuan">-</td>
                        <td data-column="Jumlah">-</td>
                        <td data-column="Tanggal">-</td>
                        <td data-column="Status">-</td>
                    </tr>
                <?php
                }
                else{
                while($rows = $withdraw->fetch_array()){
                ?>
                    <tr>
                        <td data-column="ID"><?= $rows['id'] ?></td>
                        <td data-column="Tujuan"><?= $rbanks['bankname']." ".$rbanks['rekening']." ".$rbanks['fullname']?></td>
                        <td data-column="Jumlah"><?= rp($rows['amount']) ?></td>
                        <td data-column="Tanggal"><?= $rows['date'] ?></td>
                        <td data-column="Status"><?= $rows['status'] ?></td>
                    </tr>
                <?php
                }    
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