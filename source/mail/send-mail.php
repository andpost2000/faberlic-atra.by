<meta charset="utf-8">
<?php

$emailTheme = 'Запрос регистрации"';
//Отключение предупреждений и нотайсов (warning и notice) на сайте
// error_reporting( E_ERROR );
// создание переменных из полей формы		
if (isset($_POST['first_name'])) {
  $first_name      = $_POST['first_name'];
  if ($first_name == '') {
    unset($first_name);
  }
}
if (isset($_POST['last_name'])) {
  $last_name      = $_POST['last_name'];
  if ($last_name == '') {
    unset($last_name);
  }
}
if (isset($_POST['parent_name'])) {
  $parent_name      = $_POST['parent_name'];
  if ($parent_name == '') {
    unset($parent_name);
  }
}
if (isset($_POST['date'])) {
  $date      = $_POST['date'];
  if ($date == '') {
    unset($date);
  }
}
if (isset($_POST['mail'])) {
  $mail      = $_POST['mail'];
  if ($mail == '') {
    unset($mail);
  }
}
if (isset($_POST['phone'])) {
  $phone      = $_POST['phone'];
  if ($phone == '') {
    unset($phone);
  }
}
if (isset($_POST['address'])) {
  $address      = $_POST['address'];
  if ($address == '') {
    unset($address);
  }
}
// if (isset($_POST['sab']))			{$sab			= $_POST['sab'];		if ($sab == '')		{unset($sab);}}
//стирание треугольных скобок из полей формы
if (isset($first_name)) {
  $first_name = stripslashes($first_name);
  $first_name = htmlspecialchars($first_name);
}
if (isset($last_name)) {
  $last_name = stripslashes($last_name);
  $last_name = htmlspecialchars($last_name);
}
if (isset($parent_name)) {
  $parent_name = stripslashes($parent_name);
  $parent_name = htmlspecialchars($parent_name);
} else {
  $parent_name = '===не указано===';
}
if (isset($date)) {
  $date = stripslashes($date);
  $date = htmlspecialchars($date);
}
if (isset($mail)) {
  $mail = stripslashes($mail);
  $mail = htmlspecialchars($mail);
} else {
  $mail = '===не указан===';
}
if (isset($phone)) {
  $phone = stripslashes($phone);
  $phone = htmlspecialchars($phone);
}
if (isset($address)) {
  $address = stripslashes($address);
  $address = htmlspecialchars($address);
}
$dateSend = date("d-m-Y H:i:s"); // Дата отправки
$addressSend = "nataatra2016@gmail.com";// адрес почты куда придет письмо
// $addressSend = "mandpost2000@gmail.com";// адрес почты куда придет письмо
$headers    = "Content-type: text/html; charset=utf-8 \r\n";//хедер кодировка (Это важно)
$headers   .= "From: faberlic-atra@faberlic-atra.by";//хедер от кого
// текст письма 
$note_text = "
<html>
<head>
  <title>$dateSend</title>
</head>
<body>
  <h1>Запрос</h1>
  <div>
    Фамилия: <b>$last_name</b>
  </div>
  <div>
  <div>
    Имя: <b>$first_name</b>
  </div>
  <div>
  <div>
    Отчество: <b>$parent_name</b>
  </div>
  <div>
    Дата рождения: <b>$date</b>
  </div>
  <div>
    Е-майл: <b>$mail</b>
  </div>
  <div>
    Телефон: <b>$phone</b>
  </div>
  <div>
    Адрес: <b>$address</b>
  </div>
</body>
</html>
";

if (isset($first_name)) {
  mail($addressSend, $emailTheme, $note_text, $headers);
  // сообщение после отправки формы
  // echo "Уважаемый(ая) <b>$name</b> ваш запрос принят!";
}

?>