<?php
     include '../components/connect.php';

     session_start();

      if (isset($_POST['submit'])) {
         $name = $_POST['name'];
         $name = filter_var($name, FILTER_SANITIZE_STRING);
         $email = $_POST['email'];
         $email = filter_var($email, FILTER_SANITIZE_STRING);
         $pass = sha1($_POST['pass']);
         $pass = filter_var($pass, FILTER_SANITIZE_STRING);
         $cpass = sha1($_POST['cpass']);
         $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

         $image = $_FILES['image']['name'];
         $image = filter_var($image, FILTER_SANITIZE_STRING);
         $image_tmp_name = $_FILES['image']['tmp_name'];
         $image_folder = '../uploaded_img/'.$image;

         $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name=?");
         $select_admin->execute([$name]);

         if ($select_admin->rowCount() > 0) {
             $warning_msg[] = 'userame already exists';
            }else{
                if ($pass != $cpass) {
                    $warning_msg[] = 'confirm password not matched';
                }else{
                     $insert_admin = $conn->prepare("INSERT INTO `admin`(name, email, password, profile) VALUES(?
                        ,?,?,?)");
                     $insert_admin->execute([$name, $email, $cpass, $image]);
                     move_uploaded_file($image_tmp_name, $image_folder);
                     $success_msg[] = "New admin registered successfully";

                }
            }
         
      }
?>
<style>
    <?php include 'admin_style.css'?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-----box icon cdn link------->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <title>Admin - Registration page</title>
</head>
<body>
      
      
      <div class="main-container">
                <?php include '../components/admin_header.php'?>
          <section>
              <div class="form-container" id="admin_login">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Register Now</h3>
                <div class="input-field">
                     <label>user name<sup>*</sup></label>
                     <input type="text" name="name" maxlength="100" required placeholder="Enter User Name"
                     oninput="this.value.replace(/\s/g,'')">
                </div> 

                <div class="input-field">
                     <label>user email<sup>*</sup></label>
                     <input type="email" name="email" maxlength="200" required placeholder="Enter User Email"
                     oninput="this.value.replace(/\s/g,'')">
                </div> 

                <div class="input-field">
                     <label>user password<sup>*</sup></label>
                     <input type="password" name="pass" maxlength="200" required placeholder="Enter Your Password"
                     oninput="this.value.replace(/\s/g,'')">
                </div> 

                <div class="input-field">
                     <label>confirm password<sup>*</sup></label>
                     <input type="password" name="cpass" maxlength="200" required placeholder="Confirm Your Password"
                     oninput="this.value.replace(/\s/g,'')">
                </div> 

                <div class="input-field">
                     <label>upload profile<sup>*</sup></label>
                     <input type="file" name="image" accept="image/*">
                     
                </div> 
                     <input type="submit" name="submit" value="register now" class="btn">
                     <p>already have account <a href="admin_login.php">login now</a></p>
                </form>
                  
              </div>
          </section>
      </div>
          <?php include '../components/dark.php'?>

          <!-----sweet alert cdn link------->
          <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.1/sweetalert.min.js"></script>

          <!---custom js link--->
          <script type="text/javascript" src="script.js"></script>

          <?php include '../components/alert.php'?>
</body>
</html>


