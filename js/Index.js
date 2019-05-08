console.log("Index");
$(function () {
    $("input:text:first").focus();
    validatePasswordLogin();
    validate();
    validateUserLogin();
    validationPseudoSignup()
    validationEmailSignup();

});

function validateUserLogin() {

    let errPseudo = $("#errPseudo");
    var r = $('#pseudo');
    r.focusout(function () {
        console.log(r.val());
        $.get("user/ValidateUser/" + r.val(),
                function (data) {
                    console.log("le Data :"+data);
                    if (data === "false") {
                        errPseudo.html("");
                        errPseudo.append("ce Pseudo n'existe pas  ");
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
             console.log("le Data :"+data);
            if (data === "true"){
                errPseudo.html("");
                errPseudo.append("Pseudo existant");
                errPseudo.css("color","red");
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

function validatePasswordSignup(){
    
}

function validate() {
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



function validationEmailSignup(){
    var errEmail=$('#errEmail');
    var email=$('#email');
      email.focusout(function () {
          console.log(email.val());
        $.post("user/isEmailExist/"+email.val(), function (data) {
             console.log("le Data :"+data);
            if (data === "false"){
                errEmail.html("");
                errEmail.append("Email existant");
                errEmail.css("color","red");
            }
        });
    });

    
}

