$(function () {
    $("#loadMedia").on('click', function (e) {
        e.preventDefault();
        $("div.load-media").removeClass('d-none');
        $("#loadMedia").addClass('d-none');
        $("#hideMedia").removeClass('d-none');
    });
    $("#hideMedia").on('click', function (e) {
        e.preventDefault();
        $("div.load-media").addClass('d-none');
        $("#loadMedia").removeClass('d-none');
        $("#hideMedia").addClass('d-none');
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