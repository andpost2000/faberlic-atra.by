<meta charset="utf-8">
<?php

$emailTheme = 'Запрос регистрации"';
//Отключение предупреждений и нотайсов (warning и notice) на сайте
// error_reporting( E_ERROR );
// создание переменных из полей формы		
if (isset($_POST['name'])) {
  $name      = $_POST['name'];
  if ($name == '') {
    unset($name);
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
if (isset($name)) {
  $name = stripslashes($name);
  $name = htmlspecialchars($name);
}
if (isset($date)) {
  $date = stripslashes($date);
  $date = htmlspecialchars($date);
}
if (isset($mail)) {
  $mail = stripslashes($mail);
  $mail = htmlspecialchars($mail);
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
    Имя: <b>$name</b>
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

if (isset($name)) {
  mail($addressSend, $emailTheme, $note_text, $headers);
  // сообщение после отправки формы
  // echo "Уважаемый(ая) <b>$name</b> ваш запрос принят!";
}

?>