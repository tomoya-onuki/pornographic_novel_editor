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
            function (data) {
                alert("ok");
            },
            "json"
        );
        // $.ajax({
        //     url: hostUrl,
        //     type: 'POST',
        //     dataType: 'json',
        //     data: {
        //         'word': $('#word').val(),
        //         'meaning': $('#meaning').val(),
        //         'ex_sentence': $('#ex_sentence').val(),
        //         'author': $('#author').val()
        //     },
        //     timeout: 3000,
        // }).done(function (data) {
        //     alert("ok");
        // }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        //     alert("error");
        // })
    });


});