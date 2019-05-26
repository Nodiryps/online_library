$(function () {
    console.log($('#isbn').val());
    validateBook();
});
function validateBook() {
    $('#editBook').validate({

        rules: {
            isbn: {
                remote: {
                    url: "book/isbnExists",
                    type: 'POST',
                    data: {
                        ISBN: function () {
                            return $('#ISBN').val();
                        }
                    }
                },
                required: true,
                minlength: 12,
                maxlength: 12
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
            nbCopie: {
                required: true,
                minlength: 1
            }
        },
        messages: {
            isbn: {
                required: 'required',
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
            nbCopie: {
                required: 'required',
                minlength: '1 caractère min'
            }
        }
    });
}