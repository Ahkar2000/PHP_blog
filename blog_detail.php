<?php
session_start();
require "config/config.php";
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("location:login.php");
}

$stat = $pdo->prepare("SELECT * FROM posts WHERE id=" . $_GET['id']);
$stat->execute();
$result = $stat->fetch(PDO::FETCH_ASSOC);

function user($author_id)
{
  global $pdo;
  $ustat = $pdo->prepare("SELECT * FROM users WHERE id=$author_id");
  $ustat->execute();
  $uresult = $ustat->fetch(PDO::FETCH_ASSOC);
  return $uresult['name'];
}

if ($_POST) {
  $comment = $_POST['comment'];
  $stat = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
  $result = $stat->execute(
    array(
      ':content' => $comment,
      ':author_id' => $_SESSION['user_id'],
      ':post_id' => $_GET['id'],
    ),
  );
  if ($result) {
    header("location:blog_detail.php?id=" . $_GET['id']);
  }
}
$cstat = $pdo->prepare("SELECT * FROM comments WHERE post_id=" . $_GET['id']);
$cstat->execute();
$cresult = $cstat->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog Detail</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini" style="overflow-x: hidden;">
  <div class="">
    <!-- Content Wrapper. Contains page content -->
    <div class="">
      <!-- Main content -->
      <div class="row">

        <!-- /.col -->
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="card card-widget">
            <div class="card-header text-center">
              <span class="username"><?php echo $result['title'] ?></span>
            </div>
            <!-- /.card-header -->
            <div class="p-3 d-flex justify-content-center">
              <img class="img-fluid pad w-50" src="admin/images/<?php echo $result['image'] ?>" alt="Photo">
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <!-- post text -->
            <p>
              <?php echo $result['content'] ?>
            </p>
          </div>

          <!-- /.card-body -->
          <div class="card-footer card-comments">
            <div class="d-flex align-items-center justify-content-between">
            <h3>Comments</h3>
            <a href="index.php" class="btn btn-secondary">Back To Home Page</a>
            </div>
            <hr>
            <?php foreach ($cresult as $v) { ?>
              <div class="card-comment d-flex">
                <div class="comment-text" style="margin-left: 5px !important;">
                  <span class="username">
                    <?php echo user($v['author_id']) ?>
                    <span class=" text-muted"><?php echo date("Y-m-d", strtotime($v['created_at'])) ?></span>
                  </span><!-- /.username -->
                  <?php echo $v['content'] ?>
                </div>
                <!-- /.comment-text -->
              </div>
            <?php } ?>
            <!-- /.card-comment -->
            <div class="card-footer">
              <form action="" method="post">
                <div class="img-push">
                  <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                </div>
              </form>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.content -->

      <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
      </a>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer" style="margin-left: 0 !important;">
      <div class="float-right d-none d-sm-inline">
        <a href="logout.php" type="button" class="btn btn-secondary">Logout</a>
      </div>
      <strong>Copyright &copy; 2022 <a href="#">Ahkar Min Htut</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
</body>

</html>