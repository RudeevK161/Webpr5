<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>���������� �����</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

  <?php
  
  if (!empty($messages)) {
    print('<div class="messages">');
    // ������� ��� ���������.
    foreach ($messages as $message) {
      print($message);
    }
    print('</div>');
  }
  // ����� ������� ����� ������� �������� � �������� ������� error
  // � ������� ��������� �������� ��������� ����� ������������.
  ?>

  <div class="container">
    <h2>
        ���������� �����
    </h2>
    <form action="" method="POST">
      ���:<br><input type="text" name="name" <?php if ($errors['name']) {print 'class="group error"';} else print 'class="group"'; ?> value="<?php print $values['name']; ?>">
      <br>
      E-mail:<br><input type="text" name="email" <?php if ($errors['email']) {print 'class="group error';} else print 'class="group"'; ?> value="<?php print $values['email']; ?>">
      <br>
      <div class="form-group">
<legend for="year"class="group" style="color: white;">���� ��������:</legend>
<input type="date" id="year" size="3" name="year" <?php if ($errors['year']) {print 'class="group error"';} else print 'class="group"';?> value="<?php print $values['year']; ?>">
</div>
      <div <?php if ($errors['gender']) {print 'class="error"';} ?>>
        ���:<br>
        <input class="radio" type="radio" name="gender" value="M" <?php if ($values['gender'] == 'M') {print 'checked';} ?>> �������
        <input class="radio" type="radio" name="gender" value="W" <?php if ($values['gender'] == 'W') {print 'checked';} ?>> �������
      </div>
      <div <?php if ($errors['limbs']) {print 'class="error"';} ?>>
        ���������� �����������:<br>
        <input class="radio" type="radio" name="limbs" value="4" <?php if ($values['limbs'] == '4') {print 'checked';} ?>> 4
        <input class="radio" type="radio" name="limbs" value="3" <?php if ($values['limbs'] == '3') {print 'checked';} ?>> 3
        <input class="radio" type="radio" name="limbs" value="2" <?php if ($values['limbs'] == '2') {print 'checked';} ?>> 2
        <input class="radio" type="radio" name="limbs" value="1" <?php if ($values['limbs'] == '1') {print 'checked';} ?>> 1
        <input class="radio" type="radio" name="limbs" value="0" <?php if ($values['limbs'] == '0') {print 'checked';} ?>> 0 
      </div>
      C���������������:<br>
      <select class="group" name="ability[]" size="3" multiple>
          <option value="����������" <?php if (in_array("����������", $values['ability'])) {print 'selected';} ?>>����������</option>
          <option value="����������� ������ �����" <?php if (in_array("����������� ������ �����", $values['ability'])) {print 'selected';} ?>>����������� ������ �����</option>
          <option value="���������" <?php if (in_array("���������", $values['ability'])) {print 'selected';} ?>>���������</option>
      </select>
      <br>
      ���������:<br><textarea class="group" name="bio" rows="3" cols="30"><?php print $values['bio']; ?></textarea>
      <div  <?php if ($errors['checkbox']) {print 'class="error"';} ?>>
        <input type="checkbox" name="checkbox" <?php if ($values['checkbox']) {print 'checked';} ?>> � ���������� ����������(a) 
      </div>
      <input type="submit" id="send" value="���������">
    </form>
  </div>
  <div class="container">
    <?php
      if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']))
        print('<a href="login.php" class = "enter-exit" title = "Log out">�����</a>');
      else
        print('<a href="login.php" class = "enter-exit"  title = "Log in">�����</a>');
    ?>
  </div>
</body>
</html>