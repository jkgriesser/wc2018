$('select').selectpicker();

var jumboHeight = $('.jumbotron').outerHeight();
function parallax() {
    var scrolled = $(window).scrollTop();
    $('.bg').css('height', (jumboHeight - scrolled) + 'px');
}

$(window).scroll(function(e){
    parallax();
});

$('[data-countdown]').each(function() {
    var $this = $(this), finalDate = $(this).data('countdown');
    finalDate = $.localtime.toLocalTime(finalDate, 'yyyy/MM/dd HH:mm:ss');
    $this.countdown(finalDate, function(event) {
        $this.html(event.strftime('%D days %H:%M:%S'));
    })
    .on('finish.countdown', function(event) {
        $(this).parent().find('input').prop('disabled', true);
    });
});