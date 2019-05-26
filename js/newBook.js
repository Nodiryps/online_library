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
            var datas = JSON.parse(data);
            $('#isbn2').val(datas);
        });
    });
}

function addFeatures() {

    $('#ISBN').focusout(function () {
        var input = $('#isbn2');
        if ($('#ISBN').val().length === 12) {
            $.get("book/addFeatures/" + $('#ISBN').val(), function (data) {
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
            isbn: {
                required: true,
                minlength: 12,
                maxlength: 12,
                remote: {
                    url: "book/isbnExists" ,
                    type: 'post',
                    data: {
                        ISBN: function () {
                            return $('#ISBN').val();
                        }
                    }
                }
            },
            title: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            author: {
                required: true,
                minlength: 5,
                maxlength: 50
            },
            editor: {
                required: true,
                minlength: 2,
                maxlength: 16
            },
            nbCopie:{
                required: true,
                minlength: 1
            }
        },
        messages: {
            isbn: {
                required: 'required',
                remote: 'ISBN existant',
                minlength: '12 caractères min',
                maxlength: '12 caractères max'
            },
            title: {
                required: 'required',
                minlength: '2 caractères min',
                maxlength: '50 caractères max'
            },
            author: {
                required: 'required',
                minlength: '5 caractères min',
                maxlength: '50 caractères max'
            },
            editor: {
                required: 'required',
                minlength: '2 caractères min',
                maxlength: '16 caractères max'
            },
            nbCopie:{
                required: 'required',
                minlength: '1 caractère min'
            }
        }

    });
}




