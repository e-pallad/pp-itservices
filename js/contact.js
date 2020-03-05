$(function(){
  $('#contact').on('shown.bs.collapse', function () {
    this.scrollIntoView({
      behavior: 'smooth',
      block: 'end'
    });
  });

  $('#backtotop').click(function() {
    $("html, body").animate({scrollTop:0}, 600);
    return false;
  });

  var form = $('.contact');
  var formMessages = $('#form-messages');

  $(form).submit(function(event) {
    event.preventDefault();

    $.ajax({
      type: "POST",
      url: $(form).attr('action'),
      data: form.serialize()
    })
    .done(function(response) {
      $(formMessages).modal('show');
      $(formMessages).removeClass('error');
      $(formMessages).addClass('success');
      $(sucfai).addClass('bg-success text-white')

      $(formresponse).text(response);

      $('#fname').val('');
      $('#lname').val('');
      $('#email').val('');
      $('#message').val('');
    })
    .fail(function(data) {

      $(formMessages).removeClass('success');
      $(formMessages).addClass('error');
      $(sucfai).addClass('bg-warning text-dark')

      if (data.responseText !== '') {
        $(formMessages).modal('show');
        $(formresponse).text(data.responseText);
      } else {
        $(formMessages).modal('show');
        $(formresponse).text('Irgendetwas ist schiefgelaufen! Ihre Nachricht wurde nicht gesendet.');
      }
    });
  });
});
