<?php 
    if(isset($_GET['alert'])){
        $alert = '<p class="sub">'.$_GET['alert'].'</p>';
    }
    else{
        $alert = "";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/login.css">
</head>

<body>
    <div class="form-container">
        <p class="title">Dashboard Admin</p>
        <?= $alert ?>
        <form class="form" method="post">
            <input type="text" class="input" name="username" placeholder="Username">
            <input type="password" class="input" name="password" placeholder="Password">
            <p class="page-link">
                <a href="" class="page-link-label">Forgot Password?</a>
            </p>
            <button class="form-btn" type="submit" name="confirm">Log in</button>
        </form>
    </div>
    <?php 
        include "../app/conf.php";
        if(isset($_POST['confirm'])){
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $query    = $conf->query("SELECT * FROM admin WHERE username = '$username' AND password = '$password'");
            if($query->num_rows > 0){
                $rows = $query->fetch_array();
                session_start();
                $_SESSION['adminid'] = $rows['adminid'];
                echo '<meta http-equiv="refresh" content="0;index.html">';
            }
            else{
                echo '<meta http-equiv="refresh" content="0;?alert=Username / Password salah">';
            }
        }
    ?>
    
</body>

</html>