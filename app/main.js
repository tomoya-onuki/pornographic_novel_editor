$(function () {


    $('#db_add_btn').click( function () {
            var hostUrl = 'db_add.php';
            $.ajax({
                url: hostUrl,
                type: 'POST',
                dataType: 'json',
                data: { 
                    word: $('#word').val(), 
                    meaning: $('#meaning').val(),
                    ex_sentence: $('#ex_sentence').val(),
                    author: $('#author').val()
                },
                timeout: 3000,
            }).done(function (data) {
                alert("ok");
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                alert("error");
            })
        });


});