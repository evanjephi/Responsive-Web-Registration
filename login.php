<?php
session_start();
require "includes/library.php";

$errors = [];

// ENSURE THAT THERE IS INFORMATION IN $_POST
if (isset($_POST['submit'])) {
    // GET LOGIN INFORMATION FROM $_POST
    $username = $_POST['username'];
    $password = $_POST['password'];

    // CONNECT TO THE DATABASE
    $pdo = connectDB();

    // CHECK THE DATABASE FOR THE USER
    $query = "SELECT id,username, password FROM `cois3420_users` WHERE username = ?";
    $statement = $pdo->prepare($query);

    $statement->execute([$username]);
    $results = $statement->fetch();

    // IF USER DOES NOT EXIST
    if ($results === false) {
        $errors['user'] = true;
    }

    // IF USER LOGGED IN SUCCESSFULLY
    else if (password_verify($password, $results['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['userid'] = $results['id'];

        header("Location: list.php");
        exit();
    }

    // IF PASSWORD IS INCORRECT
    else {
        $errors['login'] = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php
$page_title = "Login";
include "includes/metadata.php";
?>
</head>

<body>
    <!-- HEADER -->
    <?php include "includes/header.php"?>

    <!-- MAIN -->
    <section>
        <h2>Login</h2>

        <!-- LOGIN FORM -->
        <form action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off">
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username"  value="<?=$username;?>">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password">
            </div>
            <div>
                <span class="error <?=!isset($errors['user']) ? 'hidden' : "";?>">*That user doesn't exist</span>
                <span class="error <?=!isset($errors['login']) ? 'hidden' : "";?>">*Incorrect login info</span>
            </div>
            <button id="submit" name="submit">Log In</button>
        </form>
    </section>

    <!-- FOOTER -->
    <?php include "includes/footer.php"?>
</body>

</html>