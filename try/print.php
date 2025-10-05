<?php
include "php/print.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="style.css" rel="stylesheet" />
    <title></title>
</head>

<body>
    <div class="navbar">
        <div class="nav-content">
            <div class="logo" href="index.php">
                <span>SAMUEL</span>
            </div>
            <ul>
                <li><a href="index.html" class="nav-btn login-btn"> Page 1</a></li>
                <li><a href="page3.html" class="nav-btn login-btn"> Page 3</a></li>
            </ul>
        </div>
    </div>
    <div class="container">
        <form action="" method="post">
            <h1>Print Page</h1>
            <input type="text" name="name" class="box" value="<?php echo $name; ?>" readonly>
            <input type="text" name="fname" class="box" value="<?php echo $first_name; ?>" readonly>
            <input type="email" name="mail" class="box" value="<?php echo $email; ?>" readonly>
            <input type="text" name="pswd" class="box" value="<?php echo $pswd; ?>" readonly>
            <input type="text" name="phone " class="box" value="<?php echo $phone; ?>" readonly>
            <input type="text" name="skname " class="box" value="<?php echo $skname; ?>" readonly>

            <input type="submit" value="Je confirme les infos" name="submit" class="btn">
        </form>
    </div>
</body>

</html>