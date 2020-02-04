var futureDate = new Date("February 09, 2020 5:00 PM EDT");
var clock;
var currentDate = new Date();
var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;

function dayDiff(first, second) {
  return (second - first) / (1000 * 60 * 60 * 24);
}
if (dayDiff(currentDate, futureDate) < 100) {
  $('.clock').addClass('twoDayDigits');
} else {
  $('.clock').addClass('threeDayDigits');
}
if (diff < 0) {
  diff = 0;
}
clock = $('.clock').FlipClock(diff, {
  clockFace: 'DailyCounter',
  language: 'ru',
  countdown: true
});

$('.btn--open-form').click(function () {
  $('.overlay').fadeIn(100);
  $('.contact-form').show(300);
  $('body').addClass('has-overlay')
});

$(".btn--close-form").click(function () {
  $('.contact-form').hide(100);
  $('.overlay').fadeOut(300);
  $('body').removeClass('has-overlay');
});


$(".contact-form").submit(function (event) {
  // Предотвращаем обычную отправку формы
  event.preventDefault();
  $.post('http://faberlic-atra.by/mail/send-mail.php', {
    'first_name': $('#first_name').val(),
    'last_name': $('#last_name').val(),
    'parent_name': $('#parent_name').val(),
    'date': $('#date').val(),
    'mail': $('#mail').val(),
    'phone': $('#phone').val(),
    'address': $('#address').val(),
  },
    function (data) {
      $('#result').html(data);
    });
  $('.contact-form__ok').fadeIn(300);
});