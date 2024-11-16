<div class="sidebar close">
    <div class="logo-details">
        <i class='bx bxs-brightness-half'></i>
        <span class="logo_name"><?= $appname ?></span>
    </div>
    <ul class="nav-links">
        <li>
            <a href="index.php">
                <i class='bx bxs-home'></i>
                <span class="link_name">Beranda</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="index.php">Beranda</a></li>
            </ul>
        </li>
        <li>
            <a href="donation.php">
                <i class='bx bx-money-withdraw'></i>
                <span class="link_name">Sedekah</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="donation.php">Sedekah</a></li>
            </ul>
        </li>
        <li>
            <a href="wallet.php">
                <i class='bx bxs-wallet'></i>
                <span class="link_name">Dompet</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="wallet.php">Dompet</a></li>
            </ul>
        </li>
        <li>
            <a href="../profile/berita.html">
                <i class='bx bx-history'></i>
                <span class="link_name">Berita</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="../profile/berita.html">Berita</a></li>
            </ul>
        </li>
        <li>
            <a href="profile.php">
                <i class='bx bxs-id-card'></i>
                <span class="link_name">Profile</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="profile.php">Profile</a></li>
            </ul>
        </li>
        <li>
            <div class="profile-details">
                <div class="profile-content">
                    <img src="../assets/img/profile.png" alt="profileImg">
                </div>
                <a href="?act=logout" class="name-job">
                    <div class="profile_name">Logout</div>
                    <i class='bx bx-log-out'></i>
                </a>
            </div>
        </li>
    </ul>
</div>
<?php 
    if(isset($_GET['act'])){
        $action = $_GET['act'];
        if($action == "logout"){
            session_destroy();
            session_unset();
            echo '<meta http-equiv="refresh" content="0;../login.php">';
        }
    }
?>
<section class="home-section">
        <div class="home-content">
            <div class="bxm">
                <i class='bx bx-menu'></i>
                <div class="prop">
                    <img src="../assets/img/prop.png">
                    <p>1 PROP = <?= rp($rpPerToken) ?></p>
                </div>
            </div>
            <div class="profile">
                
            </div>
        </div>