document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    var calendarEl = document.getElementById("calendar");
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: "nl",
        initialView: "dayGridMonth",
        height: "auto",
        contentHeight: "auto",
        aspectRatio: 2,
        themeSystem: "bootstrap5",
        editable: true,
        droppable: true,
        headerToolbar: {start: "prev,next", center: "title", end: "listYear,dayGridMonth"},
        scrollTime: "00:00:00",
        events: "/Events/loadCalendarEvents",
        weekNumbers: true,
        weekNumbersWithinDays: true,
        weekNumberCalculation: "ISO",
        allDaySlot: false,
        selectable: false,
        forceEventDuration: true,
        slotDuration: "01:00:00",
        eventDrop: function (info) {
            var start = moment(info.event.start).format("Y-MM-DD HH:mm:ss");
            var end = moment(info.event.end).format("Y-MM-DD HH:mm:ss");
            var title = info.event.title;
            var id = info.event.id;
            fetch("/Events/updateEventDate", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: new URLSearchParams({title: title, start: start, end: end, id: id})
            }).then(function () {
                calendar.render();
                alert("De datum van het evenement is aangepast");
            });
        },
        eventClick: function (info) {
            var link = "/Events/Edit?id=" + info.event.id;
            fetch("/Events/Details?id=" + info.event.id)
                .then(function (response) {
                    return response.text();
                })
                .then(function (html) {
                    document.getElementById("modalBody").innerHTML = html;
                });
            document.getElementById("eventUrl").setAttribute("href", link);
            document.getElementById("deleteUrl").value = info.event.id;
            bootstrap.Modal.getOrCreateInstance(document.getElementById("fullCalModal")).show();
        }
    });
    calendar.render();
});
