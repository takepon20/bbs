<?php
session_start();
require('funcs.php');

if(isset($_SESSION['id']) && isset( $_SESSION['name'])){
    $id   = $_SESSION['id'];
    $name = $_SESSION['name'];
} else{
    header('Location: login.php');
    exit();
}

$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
if(!$id){
    header('Location: index.php');
    exit();
}

$db = db_connect();
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>緊急連絡アプリ</title>

    <link rel="stylesheet" href="style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>緊急連絡アプリ</h1>
    </div>
    <div id="content">
        <p>&laquo;<a href="index.php">一覧にもどる</a></p>

        <?php
         $stmt =  $db->prepare('SELECT 
                                 p.id, p.member_id, p.message, p.created, m.name, m.picture 
                                FROM posts p, members m 
                                WHERE p.id=? and m.id = p.member_id 
                                ORDER BY id DESC');
         if(!$stmt){
            die($db->error);
         }
         $stmt->bind_param('i', $id);
         $success = $stmt->execute();
         if(!$success){
            die($db->error);
         }
         $stmt->bind_result($id, $member_id, $message, $created, $name, $picture);
         if($stmt->fetch()):
        ?>

         <div class="msg">
            <?php if($picture): ?>
             <img src="member_picture/<?php echo h($picture); ?>" width="48" height="48" alt=""/>
            <?php endif; ?>

            <p><?php echo h($message); ?><span class="name">(<?php echo h($name); ?>)</span></p>
            <p class="day"><a href="view.php?id="><?php echo h($created); ?></a>
                [<a href="delete.php?id=<?php echo h($id); ?>" style="color: #F33;">削除</a>]
            </p>
         </div>
        <?php else: ?>
         <p>その投稿は削除されたか、URLが間違えています</p>
        <?php endif; ?>
    </div>
</div>
</body>

</html>