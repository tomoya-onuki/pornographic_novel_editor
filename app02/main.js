$(function () {
    var SCENE = 0;

    var screen = [
        "snail.jpg",
        "love_dart.jpg",
        "leucochloridium.jpg"
    ];

    init();


    $('.selectBtn').each(function () {
        $(this).on('input', function () {
            let id = $(this).attr("id").toInteger();
            if (id == 0) {
                SCENE += 1;
            } else if (id == 1) {
                SCENE += 2;
            }
        });

        if (SCENE > screen.length) {

        } else {
            let src = screen[SCENE];
            $('#img').attr('src', './img/'+src);
        }

    });


});

function init() {
    SCENE = 0;
    let src = screen[SCENE];
    $('#img').attr('src', './img/'+src);
}

