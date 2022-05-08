<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>���� � �������</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
/**
 * ���� login.php ��� �� ��������������� ������������ ������� ����� ������.
 * ��� �������� ����� ��������� �����/������ � ������� ������,
 * ���������� � ��� ����� � id ������������.
 * ����� ����������� ������������ ���������������� �� ������� ��������
 * ��� ��������� ����� ��������� ������.
 **/

// ���������� �������� ���������� ���������,
// ���� login.php ������ ���� � ��������� UTF-8 ��� BOM.
header('Content-Type: text/html; charset=UTF-8');

// �������� ������.
session_start();

// � ��������������� ������� $_SESSION �������� ���������� ������.
// ����� ��������� ���� ����� ����� �������� �����������.
if (!empty($_SESSION['login'])) {
  // ���� ���� ����� � ������, �� ������������ ��� �����������.
  // TODO: ������� ����� (��������� ������ ������� session_destroy()
  //��� ������� �� ������ �����).
  session_destroy();
  // ������ ��������������� �� �����.
  header('Location: ./');
}

// � ��������������� ������� $_SERVER PHP ��������� �������� ��������� ������� HTTP
// � ������ �������� � �������� � �������, �������� ����� �������� ������� $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if (!empty($_GET['nologin']))
    print("<div>������������ � ����� ������� �� ����������</div>");
  if (!empty($_GET['wrongpass']))
    print("<div>�������� ������!</div>");

?>

  <form action="" method="post">
    <input name="login" placeholder="����� �����"/>
    <input name="pass" placeholder="����� ������"/>
    <input type="submit" id="login" value="�����" />
  </form>

  <?php
}
// �����, ���� ������ ��� ������� POST, �.�. ����� ������� ����������� � ������� ������ � ������.
else {

  // TODO: �������� ���� �� ����� ����� � ������ � ���� ������.
  // ������ ��������� �� �������.
  $db = new PDO('mysql:host=localhost;dbname=u47563', 'u47563', '4182160', array(PDO::ATTR_PERSISTENT => true));
  $stmt = $db->prepare("SELECT human_id, pass FROM login_pass WHERE login = ?");
  $stmt -> execute([$_POST['login']]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row) {
    header('Location: ?nologin=1');
    exit();
  }
  if($row["pass"] != md5($_POST['pass'])) {
    header('Location: ?wrongpass=1');
    exit();
  }
  // ���� ��� ��, �� ���������� ������������.
  $_SESSION['login'] = $_POST['login'];
  // ���������� ID ������������.
  $_SESSION['uid'] = $row["human_id"];

  // ������ ���������������.
  header('Location: ./');
}

?>

</body>
</html>