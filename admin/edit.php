<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location:login.php");
}
if ($_SESSION['role'] != 1) {
    header("location:login.php");
}
if ($_POST) {
    if (empty($_POST['title']) || empty($_POST['content'])) {
        if (empty($_POST['title'])) {
            $titleError = 'Title cannot be empty!';
        }
        if (isset($_POST['content'])) {
            $contentError = 'Content cannot be empty!';
        }
    } else {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        if ($_FILES['image']['name']) {
            $file = 'images/' . ($_FILES['image']['name']);
            $imageType = pathinfo($file, PATHINFO_EXTENSION);
            if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
                echo "<script>alert('Image must be png,jpg and jpeg')</script>";
            } else {
                $image = $_FILES['image']['name'];
                $author_id = $_SESSION['user_id'];
                move_uploaded_file($_FILES['image']['tmp_name'], $file);
                $stat = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
                $result = $stat->execute();
                if ($result) {
                    echo "<script>alert('Post is updated successfully.');window.location.href='index.php'</script>";
                }
            }
        } else {
            $stat = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
            $result = $stat->execute();
            if ($result) {
                echo "<script>alert('Post is added successfully.');window.location.href='index.php'</script>";
            }
        }
    }
}
$stat = $pdo->prepare("SELECT * FROM posts WHERE id=" . $_GET['id']);
$stat->execute();
$result = $stat->fetchAll();
?>

<?php require "header.php"; ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                            <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>" id="">
                            <div class="form-group">
                                <label for="" class="form-label">Title</label>
                                <p class="text-danger"><?php echo empty($titleError) ?  '': $titleError; ?></p>
                                <input type="text" name="title" class="form-control" id="" value="<?php echo $result[0]['title'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Content</label>
                                <p class="text-danger"><?php echo empty($contentError) ?  '': $contentError; ?></p>
                                <textarea name="content" cols="30" rows="10" class="form-control"><?php echo $result[0]['content']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Image</label>
                                <br>
                                <img src="images/<?php echo $result[0]['image'] ?>" width="150" height="150" alt="">
                                <br>
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
<?php require "footer.php"; ?>