<?php

	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	//PDOオブジェクトの生成(DB接続)
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
    $sql = "CREATE TABLE IF NOT EXISTS mission_51"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date datetime,"
	. "pass char(12)"
	.");";
	$stmt = $pdo->query($sql);

	//データ入力 送信ボタン動作
	if(isset($_POST["Ssubmit"]) && empty($_POST["lognumber"]) && !empty($_POST["pass"])){
	    $sql = $pdo -> prepare("INSERT INTO mission_51 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':date', $date, PDO::PARAM_STR);
		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

		$name=$_POST["name"];
   		$comment=$_POST["comment"];
    	$date=date("Y/m/d H:i:s");
    	$pass=$_POST["pass"];
		$sql -> execute();
	}	
	//bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなります。最適なものを適宜決めよう。
    
    //削除機能
	if(isset($_POST["Dsubmit"])){
		$Dpass=$_POST["Dpass"];
		$Did =$_POST["delete"];//フォーム内の投稿番号とパスワードを定義


		$sql = 'SELECT * FROM mission_51 WHERE id=:id ';
		$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
		$stmt->bindParam(':id', $Did, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
		$stmt->execute();                             // ←SQLを実行する。
		$results = $stmt->fetchAll(); 
			foreach ($results as $row){
				//$rowの中にはテーブルのカラム名が入る
				$check = $row["pass"];
		
				if($check == $Dpass){//パスワード一致したら
					$sql = 'delete from mission_51 where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':id', $Did, PDO::PARAM_INT);
					$stmt->execute();
				}	
			}	
	}

	//編集機能準備
		if(isset($_POST["Esubmit"])){
			$Eid=$_POST["edit"];
			$Epass=$_POST["Epass"];
			$sql = 'SELECT * FROM mission_51';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach ($results as $row){
				if($row["id"]==$Eid && $row["pass"]==$Epass){
					$Elognum=$row["id"];
					$Ename=$row["name"];
					$Ecomment=$row["comment"];
					$Epassword=$row["pass"];
				}
			}
		}

	//編集機能
		if(isset($_POST["Ssubmit"]) && isset($_POST["lognumber"]) && !empty($_POST["pass"])){
			$Eid2=$_POST["lognumber"];
			$name = $_POST["name"];
			$comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
			$sql = 'UPDATE mission_51 SET name=:name,comment=:comment WHERE id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':id', $Eid2, PDO::PARAM_INT);
			$stmt->execute();
		}


?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
<body>
    <div align="left"><h1 class="midashi_1">眠いけど起きてなきゃいけないときの対処法教えて！</h1></div>
    
        <form action="mission_5-0.php" method="post">
            <input type="txt" name="name" size=5 placeholder="名前" value="<?php if(isset($Ename)){ echo $Ename;} ?>">
            <input type="txt" name="comment" placeholder="コメント" value="<?php if(isset($Ecomment)){echo $Ecomment;} ?>">
            <input type="txt" name="pass" size=5 placeholder="パスワード" value="<?php if(isset($Epassword)){echo $Epassword;} ?>">
            <input type="submit" name="Ssubmit">
           
            <br>

            <input type="hidden" name="lognumber" placeholder="見えません" value="<?php if(isset($Elognum)){echo $Elognum;} ?>">
            <input type="txt" name="edit" size=5 placeholder="編集番号">
            <input type="txt" name="Epass" size=5 placeholder="パスワード">
            <input type="submit" name="Esubmit" value="編集">
           <br>
            
            <input type="txt" name="delete" size=5 placeholder="削除番号">
            <input type="txt" name=Dpass size=5 placeholder="パスワード">
            <input type="submit" name="Dsubmit" value="削除"><br><br>
            
                </form>
</body>
    
	
<?php
	//表示部分
	//$rowの添字（[ ]内）は、4-2で作成したカラムの名称に併せる必要があります。
	$sql = 'SELECT * FROM mission_51';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].' ';
		echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].'<br>';
	echo "<hr>";
	}






?>