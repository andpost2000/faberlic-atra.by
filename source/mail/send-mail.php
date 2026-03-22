<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

/**
 * Send JSON and stop script.
 *
 * @param bool $ok
 * @param string $message
 * @param int $httpCode
 */
function respond(bool $ok, string $message, int $httpCode = 200): void
{
  http_response_code($httpCode);
  echo json_encode(
    ['ok' => $ok, 'message' => $message],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
  );
  exit;
}

$emailTheme = 'Запрос регистрации';

// Build variables from POST (same fields as the form)
$first_name = trim((string) ($_POST['first_name'] ?? ''));
$last_name = trim((string) ($_POST['last_name'] ?? ''));
$parent_name = trim((string) ($_POST['parent_name'] ?? ''));
$date = trim((string) ($_POST['date'] ?? ''));
$mail = trim((string) ($_POST['mail'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$address = trim((string) ($_POST['address'] ?? ''));

$missing = [];
if ($first_name === '') {
  $missing[] = 'имя';
}
if ($last_name === '') {
  $missing[] = 'фамилия';
}
if ($date === '') {
  $missing[] = 'дата рождения';
}
if ($phone === '') {
  $missing[] = 'телефон';
}
if ($address === '') {
  $missing[] = 'адрес';
}

if ($missing !== []) {
  respond(false, 'Заполните обязательные поля: ' . implode(', ', $missing) . '.', 400);
}

// Strip dangerous markup
$first_name = htmlspecialchars(stripslashes($first_name), ENT_QUOTES | ENT_HTML5, 'UTF-8');
$last_name = htmlspecialchars(stripslashes($last_name), ENT_QUOTES | ENT_HTML5, 'UTF-8');
$parent_name = $parent_name !== ''
  ? htmlspecialchars(stripslashes($parent_name), ENT_QUOTES | ENT_HTML5, 'UTF-8')
  : '===не указано===';
$date = htmlspecialchars(stripslashes($date), ENT_QUOTES | ENT_HTML5, 'UTF-8');
$mail = $mail !== ''
  ? htmlspecialchars(stripslashes($mail), ENT_QUOTES | ENT_HTML5, 'UTF-8')
  : '===не указан===';
$phone = htmlspecialchars(stripslashes($phone), ENT_QUOTES | ENT_HTML5, 'UTF-8');
$address = htmlspecialchars(stripslashes($address), ENT_QUOTES | ENT_HTML5, 'UTF-8');

$dateSend = date('d-m-Y H:i:s');
$addressSend = 'atra-74@mail.ru';
// $addressSend = 'andpost2000@mail.ru';
$headers = "Content-type: text/html; charset=utf-8 \r\n";
$headers .= 'From: faberlic-atra@faberlic-atra.by';

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
    Имя: <b>$first_name</b>
  </div>
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

$sent = @mail($addressSend, $emailTheme, $note_text, $headers);

if ($sent) {
  respond(true, 'Сообщение отправлено.');
}

respond(false, 'Не удалось отправить сообщение. Попробуйте позже или напишите на почту.', 500);
