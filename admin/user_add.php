<?php
session_start();
require "../config/config.php";
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("location:login.php");
}
if($_SESSION['role'] != 1){
  header("location:login.php");
}
if ($_POST) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  $stat = $pdo->prepare("SELECT * FROM users WHERE email=:email");
  $stat->bindValue(':email', $email);
  $stat->execute();
  $user = $stat->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    echo "<script>alert('Email has already used!')</script>";
  } else {
    $stat = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (:name,:email,:password,:role)");
    $result = $stat->execute(
      array(
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role
      ),
    );
    if ($result) {
      echo "<script>alert('User is added successfully.');window.location.href='users.php'</script>";
    }
  }
}
?>

<?php require "header.php"; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <form action="user_add.php" method="post">
              <div class="form-group">
                <label for="" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="">
              </div>
              <div class="form-group">
                <label for="" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="">
              </div>
              <div class="form-group">
                <label for="" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="">
              </div>
              <div class="form-group">
                <label for="" class="form-label">Role</label>
                <select name="role" id="" class="form-control">
                  <option disabled selected>Select Role</option>
                  <option value="0">User</option>
                  <option value="1">Admin</option>
                </select>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-success" name="" value="SUBMIT" id="">
                <a href="users.php" class="btn btn-primary">Back</a>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<?php require "footer.php"; ?>