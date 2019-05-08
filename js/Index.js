console.log("Index");
$(function () {
    $('#loginForm').validate({
        rules: {
            pseudo: {
                remote: {
                    url: 'User/rentals_by_user',
                    type: 'post',
                    data: {
                        pseudo: function () {
                            return $("#pseudo").val();
                        }
                    }
                },
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
    
    $("input:text:first").focus();
    
    var r = $('#pseudo');
    r.focusout(function () {
        console.log(r.val());
    $.get("User/ValidateUser", function (data) {
            console.log(data);
        });

    });


});
               