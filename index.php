<?php
/**
 * ����������� ����������� ����� � ������� � ������� � ��������������
 * ������ ��� ��������� ������������ ������ � ���������� ������,
 * ������ � ����� ������������ ������������� ��� �������������� �������� �����.
 */

// ���������� �������� ���������� ���������,
// ���� index.php ������ ���� � ��������� UTF-8 ��� BOM.
header('Content-Type: text/html; charset=UTF-8');

// � ��������������� ������� $_SERVER PHP ��������� �������� ��������� ������� HTTP
// � ������ �������� � �������� � �������, �������� ����� �������� ������� $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // ������ ��� ���������� �������� ��������� ������������.
  $messages = array();
  // � ��������������� ������� $_COOKIE PHP ������ ��� ����� � �������� ���� �������� �������.
  // ������ ��������� �� �������� ����������.
  if (!empty($_COOKIE['save'])) {
    // ������� ����, �������� ����� ����������� � �������.
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // ������� ��������� ������������.
    $messages[] = '�������, ���������� ���������. ';
    // ���� � ����� ���� ������, �� ������� ���������.
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('�� ������ <a href="login.php">�����</a> � ������� <strong>%s</strong>
        � ������� <strong>%s</strong> ��� ��������� ������.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }

  // ���������� ������� ������ � ������.
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['checkbox'] = !empty($_COOKIE['checkbox_error']);

  // ������ ��������� �� �������.
  if ($errors['name']) {
    // ������� ����, �������� ����� ����������� � �������.
    setcookie('name_error', '', 100000);
    // ������� ���������.
    $messages[] = '<div>��������� ���.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div>������������ email.</div>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div>�������� ��� ��������.</div>';
  }
  if ($errors['gender']) {
    setcookie('gender_error', '', 100000);
    $messages[] = '<div>�������� ���.</div>';
  }
  if ($errors['limbs']) {
    setcookie('limbs_error', '', 100000);
    $messages[] = '<div>�������� ���������� �����������.</div>';
  }
  if ($errors['checkbox']) {
    setcookie('checkbox_error', '', 100000);
    $messages[] = '<div>��������� �������.</div>';
  }

  // ���������� ���������� �������� ����� � ������, ���� ����.
  // ��� ���� ���������� ��� ������ ��� ����������� ����������� � ��������.
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
  $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
  $values['checkbox'] = empty($_COOKIE['checkbox_value']) ? '' : $_COOKIE['checkbox_value']; 
  if(empty($_COOKIE['ability_value']))
    $values['ability'] = array();
  else 
    $values['ability'] = json_decode($_COOKIE['ability_value'], true);
  
  // ���� ��� ���������� ������ �����, ���� ���� ������, ������ ������ �
  // ����� � ������ ������� ���� ��������� ������.
  session_start();
  if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
    // ��������� ������ ������������ �� ��
    // � ��������� ���������� $values,
    // �������������� �����������.
    $db = new PDO('mysql:host=localhost;dbname=u47563', 'u47563', '4182160', array(PDO::ATTR_PERSISTENT => true));
    
    $stmt = $db->prepare("SELECT * FROM human WHERE id = ?");
    $stmt -> execute([$_SESSION['uid']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $values['name'] = strip_tags($row['name']);
    $values['email'] = strip_tags($row['email']);
    $values['year'] = $row['year'];
    $values['gender'] = $row['gender'];
    $values['limbs'] = $row['limbs'];
    $values['bio'] = strip_tags($row['bio']);
    $values['checkbox'] = true; 

    $stmt = $db->prepare("SELECT * FROM superability WHERE human_id = ?");
    $stmt -> execute([$_SESSION['uid']]);
    $ability = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      array_push($ability, strip_tags($row['name_of_superability']));
    }
    $values['ability'] = $ability;
    
    printf('���� � ������� %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  // �������� ���������� ����� form.php.
  // � ��� ����� �������� ���������� $messages, $errors � $values ��� ������ 
  // ���������, ����� � ����� ������������ ������� � ���������� ������.
  include('form.php');
}
// �����, ���� ������ ��� ������� POST, �.�. ����� ��������� ������ � ��������� �� � XML-����.
else {
  // ��������� ������.
  $errors = FALSE;
  if (empty(htmlentities($_POST['name']))) {
    // ������ ���� �� ���� � ������� �� ������ � ���� name.
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // ��������� ����� ��������� � ����� �������� �� ���.
    setcookie('name_value', $_POST['name'], time() + 12 * 30 * 24 * 60 * 60);
  }
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 12 * 30 * 24 * 60 * 60);
  }
  if (empty($_POST['year'])) {
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('year_value', $_POST['year'], time() + 12 * 30 * 24 * 60 * 60);
  }
  if (empty($_POST['gender'])) {
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('gender_value', $_POST['gender'], time() + 12 * 30 * 24 * 60 * 60);
  }
  if (empty($_POST['limbs'])) {
    setcookie('limbs_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('limbs_value', $_POST['limbs'], time() + 12 * 30 * 24 * 60 * 60);
  }
  if (empty($_POST['checkbox'])) {
    setcookie('checkbox_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('checkbox_value', $_POST['checkbox'], time() + 12 * 30 * 24 * 60 * 60);
  }
  if(!empty($_POST['bio'])){
    setcookie ('bio_value', $_POST['bio'], time() + 12 * 30 * 24 * 60 * 60);
  }
  if(!empty($_POST['ability'])){
    $json = json_encode($_POST['ability']);
    setcookie ('ability_value', $json, time() + 12 * 30 * 24 * 60 * 60);
  }

  if ($errors) {
    // ��� ������� ������ ������������� �������� � ��������� ������ �������.
    header('Location: index.php');
    exit();
  }
  else {
    // ������� Cookies � ���������� ������.
    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('checkbox_error', '', 100000);
  }

  // ��������� �������� �� ����� ����������� ������ ��� ������������ �����.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // �������������� ������ � �� ������ �������,
    // ����� ������ � ������.
    $db = new PDO('mysql:host=localhost;dbname=u47563', 'u47563', '4182160', array(PDO::ATTR_PERSISTENT => true));
    
    // ���������� ������ � ������� human
    $stmt = $db->prepare("UPDATE human SET name = ?, email = ?, year = ?, gender = ?, limbs = ?, bio = ? WHERE id= ?");
    $stmt -> execute([$_POST['name'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['limbs'], $_POST['bio'],$_SESSION['uid']]);

    // ���������� ������ � ������� superability
    $stmt = $db->prepare("DELETE FROM superability WHERE human_id = ?");
    $stmt -> execute([$_SESSION['uid']]);

    $ability = $_POST['ability'];

    foreach($ability as $item) {
      $stmt = $db->prepare("INSERT INTO superability SET human_id = ?, name_of_superability = ?");
      $stmt -> execute([$_SESSION['uid'], $item]);
    }
  }
  else {
    // ���������� ���������� ����� � ������.
    $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
    $max=rand(8,16);
    $size=StrLen($chars)-1;
    $pass=null;
    while($max--)
      $pass.=$chars[rand(0,$size)];
    $login = $chars[rand(0,25)] . strval(time());
    // ��������� � Cookies.
    setcookie('login', $login);
    setcookie('pass', $pass);

    // ���������� ������ �����, ������ � ��� md5() ������ � ���� ������.
    $db = new PDO('mysql:host=localhost;dbname=u47563', 'u47563', '4182160', array(PDO::ATTR_PERSISTENT => true));

    // ������ � ������� human
    $stmt = $db->prepare("INSERT INTO human SET name = ?, email = ?, year = ?, gender = ?, limbs = ?, bio = ?");
    $stmt -> execute([$_POST['name'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['limbs'], $_POST['bio']]);
    
    // ����� id ��������� ������ � ������� human
    $res = $db->query("SELECT max(id) FROM human");
    $row = $res->fetch();
    $count = (int) $row[0];

    $ability = $_POST['ability'];

    foreach($ability as $item) {
      // ������ � ������� superability
      $stmt = $db->prepare("INSERT INTO superability SET human_id = ?, name_of_superability = ?");
      $stmt -> execute([$count, $item]);
    }

    // ������ � ������� login_pass
    $stmt = $db->prepare("INSERT INTO login_pass SET human_id = ?, login = ?, pass = ?");
    $stmt -> execute([$count, $login, md5($pass)]);
  }

  // ��������� ���� � ��������� ��������� ����������.
  setcookie('save', '1');

  // ������ ���������������.
  header('Location: ./');
}