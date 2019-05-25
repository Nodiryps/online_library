
$(function () {
    $('#ISBN').after(isbn2);
    
    isbn();
    addFeatures();
    ValidateAddBook();
    onFocus();
    

});

var isbn2 = "<input style='display:inline-block; width:35px'id='isbn2'>";

function isbn() {
    $('#ISBN').after(isbn2);
    $('#ISBN').removeClass("form-control input-md");
    $('#ISBN').focus();
    $('#ISBN').keyup(function () {
        $.get("book/getIsbn/" + $('#ISBN').val(), function (data) {
            console.log("Ajax return " + data);
            var datas = JSON.parse(data);
            console.log("Json return " + datas);
            $('#isbn2').val(datas);
        });
    });
}

function addFeatures() {

    $('#ISBN').focusout(function () {
        var input = $('#isbn2');
        if ($('#ISBN').val().length === 12) {
            $.get("book/addFeatures/" + $('#ISBN').val(), function (data) {
                console.log(input.val());
                console.log(data);
                var datas = JSON.parse(data);
                $('#ISBN').val(datas + input.val());
                $('#ISBN').addClass("form-control input-md");
            });
            $('#isbn2').remove();
        }


    });
    $('#isbn2').remove();
}

function onFocus() {
    $('#isbn2');

    $('#ISBN').focusin(function () {
        if ($('#isbn2').length === 0) {
            $('#ISBN').after(isbn2);
        }
        $('#ISBN').removeClass("form-control input-md");
        $('#ISBN').val($('#ISBN').val().replace(/\-/g, ''));
        if ($('#ISBN').val().length === 13) {
            $('#isbn2').val($('#ISBN').val().substr(-1));
            $('#ISBN').val($('#ISBN').val().substr(0, $('#ISBN').val().length - 1));
        }
    });
}

function ValidateAddBook() {

    $('#AddBookForm').validate({
        rules: {
            ISBN: {
                required: true,
                remote: {
                    url: "book/isbnExists",
                    type: 'post',
                    data: {
                        ISBN: function () {
                            return $('#ISBN').val();
                        }
                    }
                }
            },
            title: {
                required: true
            },
            author: {
                required: true
            },
            editor: {
                required: true
            }
        },
        messages: {
            ISBN: {
                required: 'le ISBN est obligatoire',
                remote: 'ISBN existant'
            },
            title: {
                required: 'required'
            },
            author: {
                required: 'required'
            },
            editor: {
                required: 'required'
            }

        }

    });
}




