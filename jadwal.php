<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>MyRepublic</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:'Poppins', sans-serif;
    background:linear-gradient(135deg,#0f2027,#1c92d2,#2c5364);
    overflow:hidden;
    position:relative;
}
body::before{
    content:'';
    position:absolute;
    inset:0;
    background:radial-gradient(circle at 20% 20%, rgba(255,255,255,0.12), transparent 18%),
               radial-gradient(circle at 80% 15%, rgba(255,255,255,0.08), transparent 20%),
               radial-gradient(circle at 60% 80%, rgba(255,255,255,0.08), transparent 20%);
    pointer-events:none;
}
.container{
    text-align:center;
    width:100%;
    max-width:520px;
    padding:0 24px;
    position:relative;
    z-index:1;
}
.title{
    font-family:'Bebas Neue', sans-serif;
    font-size:84px;
    line-height:0.95;
    background: linear-gradient(90deg, #fff, #d3f0ff, #fff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    letter-spacing: 0.08em;
    text-transform:uppercase;
    text-shadow: 0 25px 60px rgba(0,0,0,0.28);
    opacity:0;
    animation:fade 2s forwards;
    position: relative;
}
.title::after{
    content:'';
    position:absolute;
    left:50%;
    bottom:-16px;
    transform:translateX(-50%);
    width:110px;
    height:6px;
    border-radius:999px;
    background:rgba(255,255,255,0.85);
    box-shadow:0 0 18px rgba(255,255,255,0.6);
}
.subtitle{
    color:rgba(255,255,255,0.92);
    margin-top:18px;
    font-size:18px;
    font-weight:600;
    letter-spacing:0.04em;
    opacity:0;
    animation:fade 3s forwards;
}
@keyframes fade{
    from{opacity:0; transform:translateY(12px) scale(0.96);}
    to{opacity:1; transform:translateY(0) scale(1);}
}
</style>
</head>
<body>

<div class="container">
        <div class="title">MyRepublic</div>
        <div class="subtitle">#WifiTerbaik Untuk Indonesia</div>
</div>

<script>
setTimeout(function(){
<?php if(isset($_SESSION['user'])){
    $role = $_SESSION['user']['role'];
    if($role=="pelanggan"){ ?>
        window.location="home_pelanggan.php";
    <?php } elseif($role=="cs"){ ?>
        window.location="home_cs.php";
    <?php } elseif($role=="teknisi"){ ?>
        window.location="home_teknisi.php";
    <?php } else { ?>
        window.location="pilih_role.php"; // fallback
    <?php }
} else { ?>
    window.location="pilih_role.php";
<?php } ?>
},3000);
</script>

</body>
</html>