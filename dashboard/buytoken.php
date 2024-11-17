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
    <title></title>
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
            <div class="atas">
                <p id="data-jml">Harga Token Terkini</p>
                <div class="data">
                    <div class="rp">
                        <p id="jml">Token PROP</p>
                        <p id="isi">1 PROP</p>
                    </div>
                    <p id="convert">=</p>
                    <div class="prop">
                        <p id="jml">Rupiah</p>
                        <p id="isi"><?= rp($rpPerToken) ?></p>
                    </div>
                </div>
            </div>
            <form method="post">
                <div class="input">
                    <label for="">
                        <p>Jumlah Token <span style="font-size:13px;">(minimal 1.5 PROP)</span></p>
                        <div>
                            <input type="text" name="amount">PROP
                        </div>
                    </label>
                </div>
                <div class="paywith">
                    <p id="jdl">Pilih Pembayaran</p>
                    <div class="list">
                    <?php
                        $apiKey = 'DEV-WeLRMjIYnGObKXjG2Nnc3W87DToLQsbjDyrPxOGZ';
                        $channel_curl = curl_init();
                        curl_setopt_array($channel_curl, array(
                          CURLOPT_FRESH_CONNECT  => true,
                          CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/merchant/payment-channel',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_HEADER         => false,
                          CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
                          CURLOPT_FAILONERROR    => false,
                          CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
                        ));
                        $channel_response = curl_exec($channel_curl);
                        $channel_json = json_decode($channel_response, true);
                        foreach($channel_json['data'] as $data){
                    ?>
                        <input type="radio" name="paywith" value="<?= $data['code']?>" id="<?= $data['code']?>">
                        <label style="min-height:70px;" for="<?= $data['code']?>">
                            <img style="width:70px;" src="<?= $data['icon_url']?>" alt="<?= $data['icon_url']?>">
                            <p style="font-weight:600;"><?= $data['name']?></p>
                        </label>
                    <?php
                        }
                    ?>
                    </div>
                </div>
                <button type="submit" name="confirm" id="buy">Beli Token</button>
            </form>
            <?php 
                if(isset($_POST['confirm'])){
                    $amount     = (float)$_POST['amount'];
                    $payconvert = $amount * $rpPerToken;
                    $paywith    = $_POST['paywith'];
                    $nama       = $rusers['fullname'];
                    $whatsapp   = $rusers['whatsapp'];
                    include "../app/payment/create.php";
                    
                    $trx  = createTransaction('DEV-WeLRMjIYnGObKXjG2Nnc3W87DToLQsbjDyrPxOGZ', 'GeuCO-VjKFL-DI62F-kqxXJ-VQ8dM', 'T24688', $paywith, $create_trxid_coin,$payconvert, $nama, $create_trxid_coin.'@rfbaitussalam.com', $whatsapp, "Membeli Token $amount");
                    $json = json_decode($trx, true);

                    if($json['success'] == true){
                        $checkout_url = $json['data']['checkout_url'];
                        $trx_coin   = $conf->query("INSERT INTO trx_coin SET trxid = '$create_trxid_coin', userid = '$sesiUser', note = 'Beli', type = 'beli', amount = '$amount', status = 'success'");
                        if($trx_coin){
                            $newtoken = $rremainCoin['used'] + $amount;
                            $remainingToken = (float)$rremainCoin['remaining'] - $amount;
                            $tuser = $rusers['coin'] + $amount;
                            $conf->query("UPDATE users SET coin = '$tuser' WHERE userid = '$sesiUser'");
                            $conf->query("UPDATE coin SET used = '$newtoken', remaining = '$remainingToken'");
                            echo "<meta http-equiv='refresh' content='0;$checkout_url'>";
                        }
                        else{
                            echo "<meta http-equiv='refresh' content='0;wallet.php?error=insertdatabase'>";
                        }
                    }
                    else{
                        $err = $json['message'];
                        echo "<meta http-equiv='refresh' content='0;wallet.php?error=$err'>";
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