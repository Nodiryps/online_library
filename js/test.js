/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        refetchResourcesOnNavigate: true,
        plugins: ['interaction', 'resourceTimeline'],
        timeZone: 'UTC',
        defaultView: 'resourceTimelineWeek',
        aspectRatio: 1.5,
        allDaySlot: false,
        eventLimit: true,
        height: "parent",
        firstday: 1,
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        header: {
            left: 'today, prev, next',
            center: 'title',
            right: 'resourceTimelineWeek, resourceTimelineMonth, resourceTimelineYear',
        },
        views: {
            year: {
                slotDuration: {month: 1}
            },
            month: {
                slotDuration: {day: 1},
                slotLabelFormat: [
                    {day: 'numeric'}
                ]
            },
            week: {
                slotDuration: {day: 1},
                slotLabelFormat: [
                    {day: 'numeric'}
                ]
            }

        },
        editable: true,
        selectable: true,
        resourceColumns: [
            {
                labelText: 'Users',
                field: 'user'
            },
            {
                labelText: 'Books',
                field: 'book'
            }
        ],
        resources: {
            url: "rental/getRentalsRessources",
            method: 'POST',
            extraParams : function() {
                return {
                    username :  $("#username").val(),
                    book :  $("#book").val(),
                    rentaldate :  $("#rentaldate").val(),
                    autres :  $("#autres").is(":checked"),
                    autre1 :  $("#autre1").is(":checked"),
                    autre2 :  $("#autre2").is(":checked"),
                    
                };
            }
        },
        events: {
            url: "rental/getRentalsEvents",
            method: 'POST',
            extraParams : function() {
                return {
                    username :  $("#username").val(),
                    book :  $("#book").val(),
                    rentaldate :  $("#rentaldate").val(),
                    autres :  $("#autres").is(":checked"),
                    autre1 :  $("#autre1").is(":checked"),
                    autre2 :  $("#autre2").is(":checked"),
                    rentId: $("#rentalid").val()
                };
            }
        },
        eventClick: function (data) {
            var res = data.event.getResources();
            var colonne = res["0"]._resource.extendedProps;
            $("#rentalid").text(data.event.id);
            $("#user").text(colonne.user);
            $("#bookTitle").text(colonne.book);
            $("#start").text(data.event.start.toDateString());
            $("#end").text(colonne.end);
            $('#confirmDialog').dialog({
                resizable: false,
                height: 300,
                width: 500,
                modal: true,
                autoOpen: true,
                
                buttons: {
                    Return: function () {
                        var idRent = $("#rentalid").html();
                        $.post("rental/returnDate", {rentId: idRent}, refresh, "html");
                        $(this).dialog("close");
                    },
                    Delete: function () {
                        var idRent = $("#rentalid").html();
                        $.post("rental/deleteRental", {rentdel: idRent}, refresh, "html");
                        $(this).dialog("close");
                    },
                    Close: function () {
                        $(this).dialog("close");
                    }
                }
            });
        }
    });
    function refresh(){
        calendar.refetchEvents();
        calendar.refetchResources();
    }
    calendar.render();
    $("#username, #book, #rentaldate, #autres, #autre1, #autre2, #rentalid").on("input", function(){
        calendar.refetchEvents();
        calendar.refetchResources();
    });
});

    
    





