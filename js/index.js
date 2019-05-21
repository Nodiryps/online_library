//let errPassword = $("#errPassword");
//
$(function () {
    $("input:text:first").focus();
    validateLogin();
    validateSignup();
});


function validateLogin() {
    $('#loginForm').validate({
        
        rules: {
            pseudo: {
                required: true,
                minlength: 3,
                maxlength: 16,
                remote: {
                    url: "user/ValidateUser/",
                    type: "post",
                    data: {
                        pseudo: function () {
                            return $('#pseudo').val();
                        }
                    }
                }
            },
           
        },
        messages: {
            pseudo: {
                required: 'required',
                minlength: 'minimum 3 characters',
                maxlength: 'maximum 16 characters',
                remote: 'this pseudo is already taken'
            },
            password: {
                required: 'required',
                minlength: 'minimum 8 characters',
                maxlength: 'maximum 16 characters'
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
            email:{
                required: true,
                 remote: {
                    url: "user/isEmailExist/",
                    type: "post",
                    data: {
                        username: function () {
                            return $('#username').val();
                        }
                    }
                }
            },
            password: {
                required: true
                
            },
            password_confirm: {
                required: true
            
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
                    required: 'required',
                   
                },
                password_confirm: {
                    required: 'required',
                   
                },
                email:{
                    required: 'required',
                    remote:'Email existant'
                }
            }
        
    });
}
