var list;
var actual;
$(function () {
    $("#serach").focus();
    var s = $('#search');
    var u = $('#user');
    list = $('.list');
    actual = $('#user');
    $('#search').keyup(function () {
        $.get("book/get_search/" + s.val() + "/" + u.val(), function (data) {
            if (data !== "") {
                var datas = JSON.parse(data);
                list.html("");
                applyFilter(datas);
            }
        });
    });
});



function applyFilter(datas) {
    var table = " <table class='table table-striped table-condensed ' >" +
            "<legend class='text-center'>" +
            "<h1>Bibliothèque</h1>" +
            " </legend>" +
            " <thead class='thead-dark'>" +
            "  <tr>" +
            "  <th class='text-center' scope='col'>ISNB</th>" +
            "  <th class='text-center' scope='col'>TITRE</th>" +
            " <th class='text-center' scope='col'>AUTEUR.E</th>" +
            " <th class='text-center' scope='col'>EDITION</th>" +
            "  <th class='text-center' scope='col'>NBCOPIES</th>" +
            "  <th class='text-center' scope='col'>COUVERTURE</th>" +
            "  </tr>" +
            "</thead>";
    for (var t = 0; t < datas.length; ++t) {
        table += "<tr><td class='text-center ' >" + datas[t].isbn + "</td>" +
                "<td class='text-center ' >" + datas[t].title + "</td>" +
                "<td class='text-center' >" + datas[t].author + " </td>" +
                "<td class='text-center' >" + datas[t].editor + "</td>" +
                "<td class='text-center' >" + datas[t].nbCopies + "</td>" +
                " <td class='text-center' ><img  id='zoomimg' style='width:45px;height:65px;'";
        if (datas[t].picture !== null)
            table += "src='uploads/" + datas[t].picture + "' width='100' alt='Couverture'></td>"; //Why????
        else
            table += "src='uploads/images.png' width='100' alt='Couverture'>  </td>";
        table += " <td style='border:none;' bgcolor='white' >";
        if (actual.val() === "1") {
            table += " <td><form  method='post' action='book/edit_book'>" +
                    "<input type='hidden' name='editbook' value='" + datas[t].id + "'>" +
                    "<input type='hidden' name='panierof' value='" + actual.val() + "'>" +
                    "<button type='submit' name='idsubmit' class='btn btn-info'>" +
                    " <span class='glyphicon glyphicon-pencil'></span>" +
                    " </button>" +
                    "</form>" +
                    "</td>";
        } else {
            table += "<td style='border:none;' bgcolor='white' >" +
                    "<form  method='post' action='book/book_detail'>" +
                    "<input type='hidden' name='idbook' value='" + datas[t].id + "'>" +
                    "<input type='hidden' name='panierof'; value='" + actual.val() + "'>" +
                    "<button type='submit' name='idsubmit' class='btn btn-default'>" +
                    "<span class='glyphicon glyphicon-eye-open'></span>" +
                    "</button>" +
                    "</form>" +
                    " </td>";
        }

        if (actual.val() === "1") {
            table += " <td style='border:none;margin-left:10px;' bgcolor='white' id='list'>" +
                    "<form  method='post' action='book/delete_book'>" +
                    " <input type='hidden' name='delbook' value='" + datas[t].id + "'>" +
                    " <input type='hidden' name='panierof' value='" + actual.val() + "'>" +
                    "<button type='submit' name='idsubmit' class='btn btn-danger'>" +
                    "<span class='glyphicon glyphicon-trash'></span >" +
                    " </button> " +
                    "</form>" +
                    " </td>";
        }
        if (datas[t].nbCopies > 0) {
            table += "<td style='border:none;' bgcolor='white' id='list'>" +
                    "<form  method='post' action='rental/add_rental_in_basket'>" +
                    "<input type='hidden' name='idbook' value='" + datas[t].id + "'>" +
                    "<input type='hidden' name='panierof' value='" + actual.val() + "'>" +
                    "<button type='submit'  name='idsubmit' class='btn btn-success'>" +
                    " <span class='glyphicon glyphicon-plus'></span>" +
                    "</button>" +
                    " </form>" +
                    " </td>";
        }


    }
    table += "</tr></table>";
    list.append(table);
}




