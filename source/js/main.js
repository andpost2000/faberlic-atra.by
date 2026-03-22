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


$(".contact-form").on("submit", function (event) {
  event.preventDefault();

  var $form = $(this);
  var $submit = $form.find('button[type="submit"]');
  var $ok = $form.find(".contact-form__ok");
  var $err = $form.find(".contact-form__error");

  $err.hide().empty();
  $ok.hide();
  $submit.prop("disabled", true);

  $.ajax({
    url: "/mail/send-mail.php",
    method: "POST",
    dataType: "json",
    data: {
      first_name: $("#first_name").val(),
      last_name: $("#last_name").val(),
      parent_name: $("#parent_name").val(),
      date: $("#date").val(),
      mail: $("#mail").val(),
      phone: $("#phone").val(),
      address: $("#address").val()
    }
  })
    .done(function (res) {
      if (res && res.ok === true) {
        $ok.fadeIn(300);
      } else {
        var msg = res && res.message ? res.message : "Не удалось отправить форму.";
        $err.text(msg).fadeIn(300);
      }
    })
    .fail(function (jqXHR) {
      var msg = "Сервер недоступен или ответ некорректен. Попробуйте позже.";
      if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
        msg = jqXHR.responseJSON.message;
      }
      $err.text(msg).fadeIn(300);
    })
    .always(function () {
      $submit.prop("disabled", false);
    });
});