$(function () {

//    $('#username').keyup(function () {
//        $.post("user/ValidateUser", $('#username').val(), function (data) {
//            console.log(data);
//        });
//    });
    validateUser();


});


function validateUser() {
    $('#addUserForm').validate({

        rules: {
            username: {
                required: true,
                minlength: 3,
                maxlength: 16,
                remote: {
                    url: "user/ValidateUser/",
                    type: "post",
                    data: {
                        username: function () {
                            return $('#username').val();
                        }
                    }
                }
            },
            fullname:{
                required: true,
                minlength: 2,
                maxlength: 50
            },
            password: {
                required: true,
                minlength: 4,
                maxlength: 16

            },
            password_confirm: {
                required: true,
                minlength: 4,
                maxlength: 16,
                equalTo: '#password'
            },
            mail: {
                required: true,
                minlength: 11,
                email:true,
                remote: {
                    url: "user/isEmailExist/",
                    type: "get",
                    data: {
                        mail: function () {
                            return $('#email').val();
                        }
                    }
                }
            }
        },
        messages: {
            username: {
                required: 'required',
                minlength: 'minimum 3 caracteres',
                maxlength: 'maximum 16 caracteres',
                remote: 'pseudo existant'
            },
            fullname:{
                required: 'required',
                minlength: 'minimum 2 caracteres',
                maxlength: 'maximum 50 caracteres'
            },
            password: {
                required: 'required',
                minlength: 'minimum 4 caracteres',
                maxlength: 'maximum 16 caracteres'

            },
            password_confirm: {
                required: 'required',
                minlength: 'minimum 4 caracteres',
                maxlength: 'maximum 16 caracteres',
                equalTo: 'les mdp doivent etre identique'
            },
            mail: {
                required: 'required',
                remote: 'email existant',
                email: 'email invalide',
                minlength: 'minimum 11 caracteres'
            }
        }
    });
}