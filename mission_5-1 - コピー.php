<html>  
<body>

<?php


  #DB接続
  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  
  #テーブル作成
  $sql = "CREATE TABLE IF NOT EXISTS tbtable5"
	. " ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT NOT NULL,"
        . "postedAt TEXT NOT NULL,"
        . "pass TEXT NOT NULL"
	. ");";
  $stmt = $pdo->query($sql);

  $data1 = "";
  $data2 = "";
  $data3 = "";
  $mess = "";


  #投稿フォームが空でなければ
  if(!empty($_POST["name"]) and empty($_POST["num_e"])){
    #投稿フォームの入力
    $sql = $pdo -> prepare("INSERT INTO tbtable5 (name, comment, postedAt, pass) VALUES (:name, :comment, :postedAt, :pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':postedAt', $postedAt, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $postedAt = date('Y年m月d日 H:i:s');
    $pass = $_POST["pass"];

    $sql -> execute();
    }

  #削除フォームとパスワードが空でなければ
  if(!empty($_POST["del"]) and !empty($_POST["pass_d"])){
    $del_num = (int)$_POST["del"];

    #データベース内の指定されたidに対応するパスワードを判定し、削除
    $sql = 'SELECT * FROM tbtable5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();

      foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        if($row['id'] == $del_num){
          if($row['pass'] != $_POST["pass_d"]){
            $mess = "!---------------------------!<br>Error:Password is invalid.<br>!---------------------------!<br>";
            break ;
            }
          else{
            $id = $del_num;
            $sql = 'delete from tbtable5 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            }
          }
        }
    }


  #編集フォームとパスワードが空でなければ
  if(!empty($_POST["edit"])){
    $edit_num = (int)$_POST["edit"];

    #データベース内の指定されたidに対応するパスワードを判定し、表示
    $sql = 'SELECT * FROM tbtable5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();

      foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        if($row['id'] == $edit_num){
          if($row['pass'] != $_POST["pass_e"]){
            $mess = "!---------------------------!<br>Error:Password is invalid.<br>!---------------------------!<br>";
            break ;
            }
          else{
            $data1 = $row['name'];
            $data2 = $row['comment'];
            $data3 = $row['id'];
            }
          }
        }
    }

  #編集機能
  if(!empty($_POST["num_e"])){
    $num_e = (int)$_POST["num_e"];

    $id = $num_e; //変更する投稿番号
    $name = $_POST["name"];
    $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
    $postedAt = date('Y年m月d日 H:i:s');
    $sql = 'update tbtable5 set name=:name,comment=:comment,postedAt=:postedAt where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':postedAt', $postedAt, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    }

?>


<h3>簡易掲示板</h3>
<strong><?php echo $mess;?></strong>
<h4>【　投稿フォーム　】</h4>
<form action="mission_5-1.php" method="post">
  <p><input type="text" required  name="name" placeholder="名前" value = <?php echo "$data1";?>></p>
  <p><input type="text" required  name="comment" placeholder="コメント" value = <?php echo "$data2";?>></p>
  <p><input type="password" required  name="pass" placeholder="パスワード"> <input type="submit"></p>
  <p><input type="hidden" name="num_e" value = <?php echo "$data3";?>></p>
</form>
<br>

<h4>【　削除フォーム　】</h4>
<form action="mission_5-1.php" method="post">
  <p><input type="text" required  name="del" placeholder="削除対象番号"></p>
  <p><input type="password" required  name="pass_d" placeholder="パスワード"> <input type= "submit" value = "削除"></p>
</form>
<br>

<h4>【　編集フォーム　】</h4>
<form action="mission_5-1.php" method="post">
  <p><input type="text" required  name="edit" placeholder="編集対象番号"></p>
  <p><input type="password" required  name="pass_e" placeholder="パスワード"> <input type= "submit" value = "編集"></p>
</form>

<br>

<h4>--------------------------------------------------- <br>
【　投稿一覧　】</h4>

<?php

	$sql = 'SELECT * FROM tbtable5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].'　';
		echo $row['name'].'　';
		echo $row['comment'].'　';
		echo $row['postedAt'].'　'.'<br>';
	}
?>

<br>

</body>
</html>