<?php 
    include_once('storage.php');
    $stor = new Storage(new JsonIO('user.json'));
    $id = $_GET['id'] ?? -1;
    $data = $stor -> findById($id);

    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $errors = [];

    if($_POST){
        if(empty($fullname)){
            $errors['fullname'] = 'Fullname is required';
        } else {
            $user = $stor -> findOne(['fullname' => $fullname]);
            if($user !== null){
                $errors['fullname'] = 'Fullname already exists';
            }
        }

        if(empty($email)){
            $errors['email'] = 'Email is required';
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'Email has wrong format';
        }

        if(empty($password)){
            $errors['password'] = 'Password is required';
        } else if($password !== $confirmPassword){
            $errors['password'] = 'Passwords do not match';
        }

        if(empty($confirmPassword)){
            $errors['confirmPassword'] = 'Confirm password is required';
        }
        if(count($errors) == 0){
            $user = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password,
                'money' => 1500,
            ];
            include_once('storage.php');
            $stor = new Storage(new JsonIO('user.json'));
            $stor -> add($user);
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
        <h1>Registration Form</h1>
        <div class="error">
        <?php 
            foreach($errors as $error){
                echo $error . '<br>';
            }
        ?>
        </div>
        <form action="register.php" method="POST" novalidate>
            <p>Full Name: </p> <input type="text" name="fullname" value="<?= $fullname ?>">

            <br>
            <p>Email: </p><input type="text" name="email" value="<?= $email ?>">

            <br>
            <p>Password: </p><input type="password" name="password" value="<?= $password ?>">

            <br>
            <p>Confirm Password: </p><input type="password" name="confirmPassword" value="<?= $confirmPassword ?>">

            <br>
            <button class="buttonText">Submit</button>
            <br>
        </form>
    </div>
</body>
</html>