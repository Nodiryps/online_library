console.log("adduser");
$(function () {

    $('#username').keyup(function () {
        $.post("user/ValidateUser", $('#username').val(), function (data) {
            console.log(data);
        });
    });
    validateUser();


});


function validateUser() {
    $('#addUserForm').validate({

        rules: {
            required: true,
            minlength: 3,
            maxlength: 16,
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
            required: true,
            minlength: 4,
            maxlength: 16

        },
        password_confirm: {
            required: true,
            minlength: 4,
            maxlength: 16,
            equalsTo: '#password'
        },
        mail: {
            required: true,
            remote: {
                url: "user/isEmailExist/",
                type: "get",
                data: {
                    mail: function () {
                        return $('#mail').val();
                    }
                }
            }
        },
        messages: {
            username: {

                required: 'required',
                minlength: 'minimum 3 characters',
                maxlength: 'maximum 16 characters',
                remote: 'this pseudo is already taken'
            },
            password: {
                required: 'required',
                minlength: 'minimum 8 characters',
                maxlength: 'maximum 16 characters',
                equalsTo: 'mot de passe doivent etre similaire'

            },
             email: {
                required: 'required',
               remote:'email existe deja'

            }



        }




    });
}