$(function () {


    $('#db_add_btn').click(function () {
        var hostUrl = 'db_add.php';
        $.post('db_add.php',
            {
                'word': $('#word').val(),
                'meaning': $('#meaning').val(),
                'ex_sentence': $('#ex_sentence').val(),
                'author': $('#author').val()
            },
            function (data) {},
            "json"
        );
    });


});