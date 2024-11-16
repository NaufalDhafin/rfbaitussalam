<?php 
    include "../app/conf.php";
    session_start();
    if(isset($_SESSION['userid'])){
        $namesesi = $_SESSION['userid'];
        $usersesi = $conf->query("SELECT * FROM users WHERE userid = '$namesesi'");
        $rnamasesi= $usersesi->fetch_array();
        $namaa    = $rnamasesi['fullname'];
        $wa       = $rnamasesi['whatsapp'];
    }
    else{
        $namaa= "";
        $wa   = "";
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title><?= $appname ?></title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/donation.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
</head>

<body>
    <div class="back" style="width:100%;padding:5px;margin-bottom:-10px;">
        <input type="button" value="Kembali" onclick="history.back()" style="color:lightseagreen;font-weight:600;">
    </div>
    <div class="sedekah">
        <div class="atas">
            <img src="../assets/img/sedekah.jpg" alt="">
            <p>Mari Bersedekah Bersama <?= $appname ?></p>
        </div>
        <form method="post">
            <div class="user">
                <label for="">
                    <p>Nama Panggilan (opsional)</p>
                    <input type="text" name="nama" value="<?= $namaa?>">
                </label>
                <label for="">
                    <p>Whatsapp</p>
                    <input type="number" placeholder="08123456789" name="whatsapp" value="<?= $wa?>" required>
                </label>
            </div>
            <div class="manual">
                <p id="jdl">Masukan Nominal Sedekah</p>
                <label class="nml" for="manual">
                    <p>Rp</p>
                    <input type="text" name="amount" id="convert-rp" required>
                </label>
            </div>
            <!-- <div class="manual" style="display: none;">
                <p id="jdl" style="font-size: 17px;">Atau Nominal Cepat</p>
                <div class="lbl">
                    <input type="radio" name="amount" id="1" value="10000" required>
                    <label for="1">
                        <p>Rp 10.000</p>
                    </label>
                    <input type="radio" name="amount" id="3" value="30000">
                    <label for="3">
                        <p>Rp 30.000</p>
                    </label>
                    <input type="radio" name="amount" id="5" value="50000">
                    <label for="5">
                        <p>Rp 50.000</p>
                    </label>
                    <input type="radio" name="amount" id="7" value="70000">
                    <label for="7">
                        <p>Rp 70.000</p>
                    </label>
                    <input type="radio" name="amount" id="10" value="100000">
                    <label for="10">
                        <p>Rp 100.000</p>
                    </label>
                    <input type="radio" name="amount" id="15" value="150000">
                    <label for="15">
                        <p>Rp 150.000</p>
                    </label>
                    <input type="radio" name="amount" id="20" value="200000">
                    <label for="20">
                        <p>Rp 200.000</p>
                    </label>
                    <input type="radio" name="amount" id="30" value="300000">
                    <label for="30">
                        <p>Rp 300.000</p>
                    </label>
                    <input type="radio" name="amount" id="50" value="500000">
                    <label for="50">
                        <p>Rp 500.000</p>
                    </label>
                    <input type="radio" name="amount" id="100" value="1000000">
                    <label for="100">
                        <p>Rp 1.000.000</p>
                    </label>
                </div>
            </div> -->
            <div class="pesan">
                <p id="jdl">Dukungan atau Doamu (Optional)</p>
                <textarea name="note"></textarea>
            </div>
            <div class="payment">
                <p id="kategori">Pilih Metode Pembayaran</p>
                <div class="collapse collapse-plus bg-base-200">
                    <input type="radio" name="my-accordion-3"/>
                    <div class="collapse-title text-xl font-medium">E-Wallet</div>
                    <div class="collapse-content">
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
                        <input type="radio" name="pay" id="<?= $data['code']?>" value="<?= $data['code']?>" required>
                        <label for="<?= $data['code']?>">
                            <img src="<?= $data['icon_url']?>">
                            <p><?= $data['name']?></p>
                        </label>
                    <?php
                        } 
                    ?>           
                    </div>
                </div>
            </div>
            <button type="submit" name="confirm">LANJUTKAN PEMBAYARAN</button>
        </form>
        <?php 
        if(isset($_POST['confirm'])){
            $nama = $_POST['nama'];
            $whatsapp = $_POST['whatsapp'];
            $amount = $_POST['amount'];
            $paywith = $_POST['pay'];
            $note = $_POST['note'];
            $tamount = str_replace(".","",$amount);
            include "../app/payment/create.php";
            $trx  = createTransaction('DEV-WeLRMjIYnGObKXjG2Nnc3W87DToLQsbjDyrPxOGZ', 'GeuCO-VjKFL-DI62F-kqxXJ-VQ8dM', 'T24688', $paywith, $create_sedekah,$tamount, $nama, $create_sedekah.'133@gmail.com', $whatsapp, $note);
            $json = json_decode($trx, true);
            $reference = $json['data']['reference'];
            $checkout_url = $json['data']['checkout_url'];
            if($json['success'] == true){
                $sesi = "";
                if(isset($_SESSION['userid'])){
                    $coinAmount = (int)$tamount * 0.005 / 100;
                    $sesi = $_SESSION['userid'];
                    $trxcoin = $conf->query("INSERT INTO trx_coin SET trxid = '$create_trxid_coin', userid = '$sesi', note = 'Masuk', type='masuk', amount='$coinAmount', status='success'");
                    if($trxcoin){
                        $quser = $conf->query("SELECT * FROM users WHERE userid = '$sesi'");
                        $ruser = $quser->fetch_array();
                        $newcoin = $ruser['coin'] + $coinAmount;
                        $conf->query("UPDATE users SET coin = '$newcoin' WHERE userid = '$sesi'");
                        $sisatoken = (float)$rremainCoin['remaining'] - $coinAmount;
                        $oldtoken  = $rremainCoin['used'] + $coinAmount;
                        $conf->query("UPDATE coin SET used = '$oldtoken', remaining = '$sisatoken'");
                    }
                    else{
                        echo "<meta http-equiv='refresh' content='0;?error'>";
                    }
                }
                else{
                    $coinAmount = 0;
                }
                $trxsdk = $conf->query("INSERT INTO trx_sedekah SET trxid = '$create_trxid_sedekah', trxpay = '$reference', userid = '$sesi', username = '$nama', payment = '$paywith', amount = '$tamount', whatsapp = '$whatsapp', note = '$note', coin = '$coinAmount'");
            }
            echo "<meta http-equiv='refresh' content='0;$checkout_url'>";
        }
    ?>
    
    </div>
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
    
</body>

</html>