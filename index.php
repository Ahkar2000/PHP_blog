<?php
session_start();
require "config/config.php";
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BLog</title>

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
        <div class="content-wrapper" style="margin-left: 0 !important;">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class=" text-center">
                    <h1>Blog Side</h1>
                </div>
            </section>
            <div class="row">
                <!-- Main content -->
                <?php
                if ($_GET) {
                    $pageno = $_GET['pageno'];
                } else {
                    $pageno = 1;
                }
                $numofrec = 6;
                $offset = ($pageno - 1) * $numofrec;

                $stat = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                $stat->execute();
                $rawResult = $stat->fetchAll();
                $total_pages = ceil(count($rawResult) / $numofrec);

                $stat = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numofrec");
                $stat->execute();
                $result = $stat->fetchAll();
                foreach ($result as $value) {
                ?>

                    <div class="col-md-4">
                        <!-- Box Comment -->
                        <div class="card card-widget ml-3">
                            <div class="card-header text-center">
                                <span class="username"><a href="blog_detail.php?id=<?php echo $value['id'] ?>"><?php echo $value['title'] ?></a></span>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body d-flex flex-column align-items-center">
                                <img class="img-fluid pad" style="height: 250px !important;" src="admin/images/<?php echo $value['image'] ?>" alt="Photo">
                                <hr>
                                <p>
                                    <?php echo substr($value['content'], 0, 50) . "..."; ?>
                                </p>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>

                    <!-- /.content -->
                <?php
                }
                ?>

            </div>

        </div>
        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>
        <div class="d-flex justify-content-end mt-3">
            <nav aria-label="..." class="float-right mt-3">
            <ul class="pagination">
              <li class="page-item">
                <a class="page-link" href="?pageno=1">First</a>
              </li>
              <li class="page-item <?php if($pageno <= 1){echo 'disabled';}; ?>">
                <a class="page-link" href="<?php if($pageno <= 1){echo '#';}else{echo "?pageno=".($pageno-1);}?>">Previous</a>
              </li>
              <li class="page-item active" aria-current="page">
                <a class="page-link" href="#"><?php echo $pageno; ?></a>
              </li>
              <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';}; ?>">
                <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);}?>">Next</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a>
              </li>
            </ul>
          </nav>
        </div>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer" style="margin-left: 0 !important;">
        <div class="float-right d-none d-sm-inline">
            <a href="logout.php" type="button" class="btn btn-secondary">Logout</a>
        </div>
        <strong>Copyright &copy; 2022 <a href="#">Ahkar Min Htut</a>.</strong> All rights reserved.
    </footer>
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