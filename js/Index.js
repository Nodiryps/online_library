console.log("Index");
$(function () {
    $("input:text:first").focus();
    validatePasswordLogin();
    validateLogin();
    validateUserLogin();
    validationPseudoSignup()
    validationEmailSignup();
    validatePasswordSignup();

});

function validateUserLogin() {

    let errPseudo = $("#errPseudo");
    var r = $('#pseudo');
    r.focusout(function () {
        console.log(r.val());
        $.get("user/ValidateUser/" + r.val(),
                function (data) {
                    console.log("le Data :" + data);
                    if (data === "false") {
                        console.log("coucouc");
                        errPseudo.html("");
                        errPseudo.append("ce Pseudo n'existe pas  ");
                    } else {
                        errPseudo.html("");
                    }
                }
        );

    });
}

function validationPseudoSignup() {
    var errPseudo = $("#errUsername");

    var pseudo = $("#username");
    pseudo.focusout(function () {
        $.get("user/ValidateUser/" + pseudo.val(), function (data) {
            console.log("le Data :" + data);
            if (data === "true") {
                errPseudo.html("");
                errPseudo.append("Pseudo existant");
                errPseudo.css("color", "red");
            } else {
                errPseudo.html("");
            }
        });
    });

}



function validatePasswordLogin() {
    let errPassword = $("#errPassword");
    var r = $('#pseudo');
    var s = $('#password');
    s.focusout(function () {
        console.log("click");
        $.get("user/ValidatePassword/" + r.val() + "/" + s.val(),
                function (data) {
                    console.log("le Data :" + data);
                    if (data === "false") {
                        errPassword.html("");
                        errPassword.append("c'est le mauvais mot de passe, réessayez  ");
                    } else {
                        errPassword.html("");
                    }
                }
        );

    });
}

function validatePasswordSignup() {
    var password = $('#passwordSignup');
    var passwordConfirm = $('#password_confirm');
    var msgErreur = $('#errConfirm');
    passwordConfirm.focusout(function () {
        if (password.val() !== passwordConfirm.val()) {
            msgErreur.html("");
            msgErreur.append("les mots de passe doivent être identique");
        } else {
            msgErreur.html("");
        }
    });
}



function validateLogin() {
    $('#loginForm').validate({
        rules: {
            pseudo: {
                required: true,
                minlength: 3,
                maxlength: 16
            },
            password: {
                required: true,
                minlength: 4,
                maxlength: 16
            },
            messages: {
                pseudo: {
                    required: 'required',
                    minlength: 'minimum 3 characters',
                    maxlength: 'maximum 16 characters'
                },
                password: {
                    required: 'required',
                    minlength: 'minimum 8 characters',
                    maxlength: 'maximum 16 characters'
                }


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
                maxlength: 16
            },
            password: {
                required: true,
                minlength: 4
            },
            password_confirm: {
                required: true,
                minlength: 4
            },
            messages: {
                fullname: {
                    required: 'le fullname est obligatoire'
                },
                username: {
                    required: 'required',
                    minlength: 'minimum 3 characters',
                    maxlength: 'maximum 16 characters'
                },
                password: {
                    required: 'required',
                    minlength: 'minimum 4 characters'
                },
                password_confirm: {
                    required: 'required',
                    minlength: 'minimum 4 characters'
                }
            }
        }
    });
}



function validationEmailSignup() {
    var errEmail = $('#errEmail');
    var email = $('#email');
    email.focusout(function () {
        console.log(email.val());
        $.get("user/isEmailExist/" + email.val(), function (data) {
            //console.log("le Data :"+data);
            encodeURIComponent(email.val())
            if (data === "false") {
                errEmail.html("");
                errEmail.append("Email existant");
                errEmail.css("color", "red");
            } else {
                errEmail.html("");
            }
        });
    });


}

