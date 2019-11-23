<!--//mission5-->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//データベースの接続とテーブル作成
$dsn='mysql:dbname=tb******db;host=localhost';
//データベースに接続するために必要な情報。データベース名と、MySQLホスト名
$username='tb-******';
//dsn系列のユーザー名
$password='sTz********';
//dsn系列のパスワード
$pdo=new PDO($dsn,$username,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
//pdoを使うことで、以下の関数をどんなデータベースでも使えるようにする。また、array以降はエラーを防いでいる。
$sql="CREATE TABLE IF NOT EXISTS mission_5"
//テーブルを作る。if notを入れることで、2回目以降にmission_5を開いたときにエラーが出るのを防いでいる。
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
//指定がないときテーブルに自動で番号が割り振られる
."name char(32),"
."comment TEXT,"
."post_date TEXT,"
."password char(50)"
.");";
$stmt=$pdo->query($sql);


//削除機能
 if(isset($_POST["delete"])){
   $delete=$_POST["delete"];
   $id=$delete;
   $sql="SELECT password FROM mission_5 WHERE id=$_POST[delete]";
   $stmt=$pdo->query($sql);
   $result=$stmt->fetch();
   if($result['password']==$_POST["del_password"]){
     //入力されたパスワードが
   $sql='delete from mission_5 where id=:id';
   $stmt=$pdo->prepare($sql);
   $stmt->bindParam(':id',$id,PDO::PARAM_INT);
   $stmt->execute();
 }
}

//編集番号情報取得機能
if(isset($_POST["Edit"])){
  $edit=$_POST["Edit"];
  $sql='SELECT * FROM mission_5';//mission_5から全て取り出す
  $stmt=$pdo->query($sql);
  $results=$stmt->fetchALL();
  foreach($results as $row){
    if($row['id']==$edit && $row['password']==$_POST["edit_password"]){
    $Edit_name=$row['name'];
    $Edit_comment=$row['comment'];
  }
}
}

//編集機能
  if(isset($_POST["name"])&&isset($_POST["comment"])&& !empty($_POST["Edit_Number"])){
    $Edit_Number=$_POST["Edit_Number"];
    $Edit_Name=$_POST["name"];
    $Edit_Comment=$_POST["comment"];
    $sql='SELECT * FROM mission_5';//mission_5から全て取り出す
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchALL();
    foreach($results as $row){
      if($row['id']==$Edit_Number){
        $sql='update mission_5 set name=:name,comment=:comment where id=:id';//idに入っている番号を編集
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':name',$Edit_Name,PDO::PARAM_STR);
        $stmt->bindParam(':comment',$Edit_Comment,PDO::PARAM_STR);
        $stmt->bindParam(':id',$row['id'],PDO::PARAM_INT);
        $stmt->execute();
      }
    }
}

//送信機能
if(isset($_POST["name"])&&isset($_POST["comment"])&& empty($_POST["Edit_Number"])){
  if($_POST["comment"]!=null){
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $Post_Date=date("Y/m/d H:i:s");
    $password=$_POST["password"];
    $sql=$pdo->prepare("INSERT INTO mission_5(id,name,comment,post_date,password) VALUES(:id,:name,:comment,:post_date,:password)");
    $sql->bindParam(':id',$id,PDO::PARAM_INT);
    $sql->bindParam(':name',$name,PDO::PARAM_STR);//nameテーブルに文字列をinsert
    $sql->bindParam(':comment',$comment,PDO::PARAM_STR);//commentテーブルに文字列をinsert
    $sql->bindParam(':post_date',$Post_Date,PDO::PARAM_STR);//post_dateテーブルに文字列をinsert
    $sql->bindParam(':password',$password,PDO::PARAM_STR);//passwordテーブルに文字列をinsert
    $sql->execute();
}
}
}
?>

<!DOCTYPE html>
<html>
  <meta charset="utf-8">
  <form action="" method="post">
    <p>名前を入力:</p>
    <input type="text" name="name" value="<?php if(isset($Edit_name)){echo $Edit_name;} else{echo "名前";}?>">
    <p>コメントを入力:</p>
    <input type="text" name="comment" value="<?php if(isset($Edit_comment)){echo $Edit_comment;} else{echo "コメント";}?>"><br>
    <p>パスワードを入力:</p>
    <input type="text" name="password" value="パスワード"><br>
    <input type="hidden"  name="Edit_Number" value="<?php if(isset($edit)){echo $edit;} else{echo null;}?>"><br>
    <input type="submit" value="送信">
  </form>
    <p>削除対象番号の入力:</p>
    <form action="" method="post">
      <input type="text" name="delete" value="削除対象番号"><br>
      <p>パスワードを入力:</p>
      <input type="text" name="del_password" value="パスワード"><br>
    <input type="submit" value="削除">
  </form>
  <p>編集対象番号の入力:</p>
  <form action="" method="post">
    <input type="text" name="Edit" value="編集対象番号"><br>
    <p>パスワードを入力:</p>
    <input type="text" name="edit_password" value="パスワード"><br>
  <input type="submit" value="編集">
</form>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//表示機能
$sql='SELECT * FROM mission_5';//mission_5から全て取り出す
$stmt=$pdo->query($sql);
$results=$stmt->fetchALL();
foreach($results as $row){
  echo $row['id'].',';
  echo $row['name'].',';
  echo $row['comment'].',';
  echo $row['post_date'];
  echo "<hr>";
}
}
?>