
$(function () {
    isbn();
    addFeatures();
});


function isbn() {
    var isbn = $('#ISBN');
    isbn.focus();
    isbn.keyup(function () {
        var tmp = isbn.val();
        $.get("book/getIsbn/" + isbn.val(), function (data) {
            console.log("Ajax return " + data);
            var datas = JSON.parse(data);
            console.log("Json return " + datas);

            isbn.val(datas);
        });


    });
}

function addFeatures() {
    var isbn = $('#ISBN');
    isbn.focusout(function () {
        $.get("book/addFeatures/" + isbn.val(), function (data) {
            console.log(data);
            var datas = JSON.parse(data);
            isbn.val("");
            isbn.val(datas);
        });
    });
}