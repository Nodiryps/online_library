/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function(){
    console.log("EDIT USER JS");
    
});

function validateUser() {
    $('#addUserForm').validate({
        rules: {
            username: {
                remote: {
                    url: 'user/',
                    type: 'get',
                    data: {
                        username: function () {
                            return $("#username").val();
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
            password_confirm: {
                required: true,
                minlength: 4,
                maxlength: 16
            },
            messages: {
                username: {
                    remote: 'this pseudo is already taken',
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