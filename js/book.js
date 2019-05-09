console.log("bookmanager");



$(function () {
    $("#serach").focus();
    var s = $('#search');
    var u = $('#user');

    $('#search').keyup(function () {
        $.get("book/get_search/" + s.val() + "/" + u.val(), function (data) {
        // console.log(data);
               for(var d in data){
                  console.log(d);
               }
            }
        );
    });

});


var liste = [
    "Draggable",
    "Droppable",
    "Resizable",
    "Selectable",
    "Sortable"
];