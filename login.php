<?php 
    session_start();
    include_once('storage.php');
    $stor = new Storage(new JsonIO('user.json'));
    $id = $_GET['id'] ?? -1;
    $data = $stor -> findById($id);

    $fullname = $_POST['fullname'] ?? '';
    $password = $_POST['password'] ?? '';
    $errors = [];

    if($_POST){
        if(empty($fullname)){
            $errors['fullname'] = 'Fullname is required';
        } else {
            $user = $stor -> findOne(['fullname' => $fullname]);
            if($user === null){
                $errors['fullname'] = 'You need to register first';
            }
        }

        if(empty($password)){
            $errors['password'] = 'Password is required';
        } else{
            if($user !== null && $user['password'] !== $password){
                $errors['password'] = 'Wrong password';
            }
        }

        if(count($errors) == 0){
            $_SESSION['fullname'] = $fullname; 
            $_SESSION['money'] = $user['money']; 
            
            header('location: index.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="background">
    <div class="register">
        <h1>Profile</h1>
        <div class="error">
        <?php 
            foreach($errors as $error){
                echo $error . '<br>';
            }
        ?>
        </div>
        <form action="login.php" method="POST">
            <p>Full Name: </p> <input type="text" name="fullname" value="<?= $fullname ?>">

            <br>
            <p>Password: </p><input type="password" name="password" value="<?= $password ?>">

            <br>
            <button class="buttonText">Submit</button>
            <br>
        </form>
    </div>
</body>
</html>