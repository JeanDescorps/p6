/*-----------------------------------------------------------------------------------*/

/*  /* Show trick media in small screen */

/*-----------------------------------------------------------------------------------*/

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

/*-----------------------------------------------------------------------------------*/

/*  /* Show more tricks in the home page */

/*-----------------------------------------------------------------------------------*/

$(function () {
    $("div.trick").slice(0, 6).show();
    $("#loadMoreTrick").on('click', function (e) {
        e.preventDefault();
        $("div.trick:hidden").slice(0, 6).slideDown();
        if ($("div.trick:hidden").length == 0) {
            $("#loadMoreTrick").hide('slow');
            $("#loadLessTrick").show('slow');
        }
    });
    $("#loadLessTrick").on('click', function (e) {
        e.preventDefault();
        $("div.trick").slice(6, $("div.trick").length).hide();
        $("#loadLessTrick").hide('slow');
        $("#loadMoreTrick").show('slow');

    });
});

/*-----------------------------------------------------------------------------------*/

/*  /* Enlarge image on click */

/*-----------------------------------------------------------------------------------*/

$(document).ready(function () {
    $('.enlarge').on('click', function () {
        $(this).toggleClass('clic-image');
    });
});