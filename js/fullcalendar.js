/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


console.log("return");

$(function(){
    
    var affiche=$('#phpAffiche');
    affiche.hide();
    //FullCalendar(affiche);
});


//function FullCalendar(affiche){
//    var calendarEl = $('#phpAffiche');
//    console.log("methode");
//  var calendar = new FullCalendar.Calendar(calendarEl, {
//    plugins: [ 'interaction', 'resourceTimeline' ],
//    timeZone: 'UTC',
//    header: {
//      left: 'today prev,next',
//      center: 'title',
//      right: 'resourceTimelineDay,resourceTimelineTenDay,resourceTimelineMonth,resourceTimelineYear'
//    },
//    defaultView: 'resourceTimelineDay',
//    scrollTime: '08:00',
//    aspectRatio: 1.5,
//    views: {
//      resourceTimelineDay: {
//        buttonText: ':15 slots',
//        slotDuration: '00:15'
//      },
//      resourceTimelineTenDay: {
//        type: 'resourceTimeline',
//        duration: { days: 10 },
//        buttonText: '10 days'
//      }
//    },
//    editable: true,
//    resourceLabelText: 'Rooms',
//    resources: 'https://fullcalendar.io/demo-resources.json?with-nesting&with-colors',
//    events: 'https://fullcalendar.io/demo-events.json?single-day&for-resource-timeline'
//  });
//
//  calendar.render();
//}