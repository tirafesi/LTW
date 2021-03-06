<!DOCTYPE html>

<html>

<head>
    <title>EAT&AVAIL</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="stylesheets/global-style.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/login.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

</head>

<body>

    <?php
    session_start();
    include_once(dirname(__FILE__) . "/database/connection.php");
    include_once(dirname(__FILE__) . "/database/action_login.php");
    include_once(dirname(__FILE__) . "/constants.php");
    include_once(dirname(__FILE__) . "/includes/header.php");
    global $login_errors;
    ?>
    <p id="login_error"> </p>

    <div class="sign_login_form">
        <h2>Login</h2>
        <!-- <form action="database/action_login.php" method="post"> -->
        <form action="" method="post">

            <label class="login-label">Username</label>
            <input type="text" name="username" placeholder="username" required>

            <label class="login-label">Password</label>
            <input type="password" name="password" placeholder="password" required>

            <input class="button2" type="submit" value="Login">

        </form>

    </div>


    <?php
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        check_creds();
    }



    if(isset($login_errors)){

        ?>


        <script>
        var errors = "<?php echo $login_errors?>";
        document.getElementById("login_error").innerHTML = "<font color=\"red\">"+errors+"</font>";
        </script>



        <?php

    }

    ?>

    <?php
    include_once(dirname(__FILE__) . "/includes/footer.php");
    ?>

</body>
</html>
