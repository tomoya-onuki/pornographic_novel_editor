var SCENE = 0;
var screen = [
    "snail.jpg",
    "love_dart.jpg",
    "leucochloridium.jpg"
];

$(function () {


    init();

    $('.selectBtn').each(function (idx, elem) {
        $(elem).on('click', function () {
            let id = $(this).attr("id");
            console.log(id);
            if (id === "btn0") {
                SCENE += 1;
            } else if (id === "btn1") {
                SCENE += 2;
            }


            if (SCENE >= screen.length - 2) {
                $('#btn0').hide();
                $('#btn1').hide();
                $('#rstBtn').show();
                $('#msg').text("FIN");
            }
            if (SCENE < screen.length) {
                let src = screen[SCENE];
                $('#img').attr('src', './img/' + src);
            }
        });

    });

    $('#rstBtn').on('click', function () {
        init();
    });

});

function init() {
    SCENE = 0;
    let src = screen[SCENE];
    $('#img').attr('src', './img/' + src);

    $('#btn0').show();
    $('#btn1').show();
    $('#rstBtn').hide();
    $('#msg').text("選択肢を選ぶ");
}

