<?php
     include '../components/connect.php';

     session_start();

      $admin_id = $_SESSION['admin_id'];

       if (!isset($admin_id)) {
           header('location:admin_login.php');
       }
    
       if (isset($_POST['submit'])) {

           //update name
           $name = $_POST['name'];
           $name = filter_var($name, FILTER_SANITIZE_STRING);

           if (!empty($name)) {
               $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name=?");
               $select_name->execute([$name]);

               if ($select_name->rowCount() > 0) {
                   $warning_msg[] = 'userame already exist';
               }else{
                  $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id=?");
                  $update_name->execute([$name, $admin_id]);
                  $success_msg[] = 'name updated successfully';

               }
           }

              //update email
           $email = $_POST['email'];
           $email = filter_var($email, FILTER_SANITIZE_STRING);

           if (!empty($email)) {
               $select_email = $conn->prepare("SELECT * FROM `admin` WHERE email=?");
               $select_email->execute([$email]);

               if ($select_email->rowCount() > 0) {
                   $warning_msg[] = 'email already exist';
               }else{
                  $update_email = $conn->prepare("UPDATE `admin` SET email = ? WHERE id=?");
                  $update_email->execute([$email, $admin_id]);
                  $success_msg[] = 'email updated successfully';

               }
           }

           //update image
           $old_image = $_POST['old_image'];
           $image = $_FILES['image']['name'];
           $image_tmp_name = $_FILES['image']['tmp_name'];
           $image_folder = '../uploaded_img/'.$image;

           $update_image = $conn->prepare("UPDATE `admin` SET profile = ? WHERE id=?");
           $update_image->execute([$image, $admin_id]);
           move_uploaded_file($image_tmp_name, $image_folder);
           if ($old_image != $image AND $old_image != '') {
               unlink('../uploaded_img/'.$image);
           }
           $success_msg[] = 'image updated successfully!';


           //update password
           $empty_pass = 'da39a3ee5e6b4bod3255bfef95601890afd80709';
           $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id=?");
           $select_old_pass->execute([$admin_id]);

           $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
           $prev_pass = $fetch_prev_pass['password'];

           $old_pass = sha1($_POST['old_pass']);
           $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

           $new_pass = sha1($_POST['new_pass']);
           $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

           $cpass = sha1($_POST['cpass']);
           $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

           if ($old_pass !=$empty_pass) {
               if ($old_pass != $prev_pass) {
                   $warning_msg[] = 'old password not matched!';
               }elseif ($new_pass != $cpass) {
                   $warning_msg[] = 'confirm password not matched';
               }else{
                  if ($new_pass != $empty_pass) {
                      $update_pass = $conn->prepare("UPDATE `admin` SET password=? WHERE id=?");
                      $update_pass->execute($cpass, $admin_id);
                      $success_msg[] = 'password updated successfully';
                  }
                  else{
                     $warning_msg[] = 'please enter your new password';
                  }
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
                    <div class="profile">
                        <img src="../uploaded_img/<?= $fetch_profile['profile']; ?>" class="logo-img">
                    </div>
                    <h3>update profile</h3>
                      <input type="hidden" name="old_image" value="<?= $fetch_profile['profile']; ?>">
                <div class="input-field">
                     <label>user name<sup>*</sup></label>
                     <input type="text" name="name" maxlength="100" placeholder="Enter User Name"
                     oninput="this.value.replace(/\s/g,'')" value="<?= $fetch_profile['name']; ?>">
                </div> 

                <div class="input-field">
                     <label>user email<sup>*</sup></label>
                     <input type="email" name="email" maxlength="200" placeholder="Enter User Email"
                     oninput="this.value.replace(/\s/g,'')" value="<?= $fetch_profile['email']; ?>">
                </div> 

                 <div class="input-field">
                     <label>old password<sup>*</sup></label>
                     <input type="password" name="old_pass" maxlength="200" placeholder="Enter Your Password"
                     oninput="this.value.replace(/\s/g,'')">
                </div> 

                <div class="input-field">
                     <label>new password<sup>*</sup></label>
                     <input type="password" name="new_pass" maxlength="200" placeholder="Enter Your Password"
                     oninput="this.value.replace(/\s/g,'')">
                </div> 

                <div class="input-field">
                     <label>confirm password<sup>*</sup></label>
                     <input type="password" name="cpass" maxlength="200" placeholder="Confirm Your Password"
                     oninput="this.value.replace(/\s/g,'')">
                </div> 

                <div class="input-field">
                     <label>upload profile<sup>*</sup></label>
                     <input type="file" name="image" accept="image/*">
                     
                </div> 
                     <input type="submit" name="submit" value="update profile" class="btn">
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


