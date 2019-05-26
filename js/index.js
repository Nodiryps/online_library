//let errPassword = $("#errPassword");
//
$(function () {
    $("input:text:first").focus();
//    validateLogin();
    validateSignup();
});


function validateLogin() {
    $('#loginForm').validate({

        rules: {
            username: {
                required: true
            },
            password : {
                required: true
            }
        },
        messages: {
            username: {
                required: 'required'
            },
            password: {
                required: 'required'
            }
        }
    });
}

function validateSignup() {
    $('#signupForm').validate({
        rules: {
            fullname: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
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
            email: {
                email: true,
                required: true,
                remote: {
                    url: "user/isEmailExist/",
                    type: "post",
                    data: {
                        email: function () {
                            return $('#email').val();
                        }
                    }
                }
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
                equalTo:'#password'
            }
        },
        messages: {
            fullname: {
                required: 'le fullname est obligatoire',
                minlength: 'minimum 2 caracteres',
                maxlength: 16
            },
            username: {
                required: 'required',
                minlength: 'minimum 3 caracteres',
                maxlength: 'maximum 16 caracteres',
                remote: 'pseudo existant'
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
                equalTo: 'les mdp doivent etre idntiques'

            },
            email: {
                email:'email invalide',
                required: 'required',
                remote: 'Email existant'
            }
        }

    });
}

