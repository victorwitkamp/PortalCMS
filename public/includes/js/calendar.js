/* global FullCalendar, moment, $, alert */
/* jslint browser */

document.addEventListener('DOMContentLoaded', function () {
    'use strict'
    var calendarEl = document.getElementById('calendar')
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['list', 'dayGrid', 'interaction', 'bootstrap'],
        locale: 'nl',
        defaultView: 'dayGridMonth',
        height: 'auto',
        contentHeight: 'auto',
        aspectRatio: 2,
        themeSystem: 'bootstrap',
        now: moment.now.getDate,
        bootstrapFontAwesome: {
            custom1: 'fa-calendar-plus'
        },
        editable: !0,
        droppable: !0,
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'custom1 listYear dayGridMonth,dayGridWeek'
        },
        scrollTime: '00:00:00',
        events: '../api/loadCalendarEvents.php',
        weekNumbers: !0,
        weekNumberTitle: 'Week',
        allDaySlot: !1,
        selectable: !1,
        selectHelper: !1,
        forceEventDuration: !0,
        slotDuration: '01:00:00',
        eventDrop: function (e) {
            var start = moment(e.event.start).format('Y-MM-DD HH:mm:ss')
            var end = moment(e.event.end).format('Y-MM-DD HH:mm:ss')
            var title = e.event.title;
            var id = e.event.id;
            $.ajax({
                url: '../api/updateEventDate.php',
                type: 'POST',
                data: {
                    title: title,
                    start: start,
                    end: end,
                    id: id
                },
                success: function () {
                    calendar.render();
                    alert('De datum van het evenement is aangepast')
                }
            })
        },
        eventClick: function (e) {
            var link = 'edit.php?id=' + e.event.id;
            // e.event.id,
            $('#modalBody').load('details.php?id=' + e.event.id);
            $('#eventUrl').attr('href', link);
            $('#deleteUrl').attr('value', e.event.id);
            $('#fullCalModal').modal()
        }
    });
    calendar.render()
    // $('div.fc-dayGrid-view table.table-bordered').addClass('card');
});
