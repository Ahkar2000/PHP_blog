<?php
session_start();
require "../config/config.php";
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("location:login.php");
}
if($_POST){
  $file = 'images/'.($_FILES['image']['name']);
  $imageType = pathinfo($file,PATHINFO_EXTENSION);

  if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg'){
    echo "<script>alert('Image must be png,jpg and jpeg')</script>";
  }else{
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    $author_id = $_SESSION['user_id'];
    move_uploaded_file($_FILES['image']['tmp_name'],$file);
    $stat = $pdo->prepare("INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:author_id)");
    $result = $stat->execute(
      array(
        ':title'=>$title,
        ':content'=>$content,
        ':image'=>$image,
        ':author_id'=>$author_id
      ),
    );
    if($result){
      echo "<script>alert('Post is added successfully.');window.location.href='index.php'</script>";
    }
  }
}
?>

<?php require "header.php"; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="col-12">
        <div class="card">
            <div class="card-body">
              <form action="add.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="" class="form-label">Title</label>
                  <input type="text" name="title" class="form-control" id="">
                </div>
                <div class="form-group">
                  <label for="" class="form-label">Content</label>
                  <textarea name="content" cols="30" rows="10" class="form-control">
                  </textarea>
                </div>
                <div class="form-group">
                  <label for="" class="form-label">Image</label>
                  <input type="file" class="form-control" name="image" id="">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-success" name="" value="SUBMIT" id="">
                  <a href="index.php" class="btn btn-primary">Back</a>
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
<?php require "footer.php";?>