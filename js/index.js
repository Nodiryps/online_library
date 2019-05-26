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
            password : {required: true}
           

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
                required: true
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
                required: true

            },
            password_confirm: {
                required: true,
                equalTo:'#password'
            }
        },
        messages: {
            fullname: {
                required: 'le fullname est obligatoire'
            },
            username: {
                required: 'required',
                minlength: 'minimum 3 characters',
                maxlength: 'maximum 16 characters',
                remote: 'this pseudo is already taken'
            },
            password: {
                required: 'required'

            },
            password_confirm: {
                required: 'required',
                equalTo: 'Not same password'

            },
            email: {
                required: 'required',
                remote: 'Email existant'
            }
        }

    });
}

