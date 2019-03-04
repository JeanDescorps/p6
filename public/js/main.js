$(function () {
    $("div.img-trick").slice(0, 2).show();
    $("#loadMoreImg").on('click', function (e) {
        e.preventDefault();
        $("div.img-trick:hidden").slice(0, 2).slideDown();
        if ($("div.img-trick:hidden").length == 0) {
            $("#loadMoreImg").fadeOut('slow');
        }
        $('html,body').animate({
            scrollTop: $(this).offset().top
        }, 1500);
        if ($("div.img-trick:last").css('display') == 'block') {
            $('#loadMoreImg').hide();
        }
    });
});

$(function () {
    $("div.video-trick").slice(0, 2).show();
    $("#loadMoreVideo").on('click', function (e) {
        e.preventDefault();
        $("div.video-trick:hidden").slice(0, 2).slideDown();
        if ($("div.video-trick:hidden").length == 0) {
            $("#loadMoreVideo").fadeOut('slow');
        }
        $('html,body').animate({
            scrollTop: $(this).offset().top
        }, 1500);
    });
    if ($("div.video-trick:last").css('display') == 'block') {
        $('#loadMoreVideo').hide();
    }
});