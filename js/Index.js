console.log("Index");
$(function () {
     $("input:text:first").focus();
      validatePasswordFocusLost();
    validate();
    validateUserFocusLost();
   

});

function validateUserFocusLost() {
   
    let errPseudo = $("#errPseudo");
    var r = $('#pseudo');
    r.focusout(function () {
        $.get("user/ValidateUser/" + r.val(),
                function (data) {
                    //console.log("le Data :"+data);
                    if (data === "false") {
                        errPseudo.html("");
                        errPseudo.append("ce Pseudo n'existe pas  ");
                    }
                }
        );

    });
}

function validate() {
    $('#loginForm').validate({
        rules: {
            pseudo:{
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

function validatePasswordFocusLost(){
     let errPassword = $("#errPassword");
      var r = $('#pseudo');
    var s=$('#password');
      s.focusout(function(){
          console.log("click");
           $.get("user/ValidatePassword/" + r.val()+"/"+s.val(),
                function (data) {
                    console.log("le Data :"+data);
                    if (data === "false") {
                        errPassword.html("");
                        errPassword.append("c'est le mauvais mot de passe, réessayez  ");
                    }else{
                         errPassword.html("");
                    }
                }
        );
          
      });
}