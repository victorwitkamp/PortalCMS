document.addEventListener("DOMContentLoaded", function () {
    var e = getComputedStyle(document.body),
        t = {};
    t.primary = e.getPropertyValue("--primary"), t.secondary = e.getPropertyValue("--secondary"), t.success = e.getPropertyValue("--success"), t.info = e.getPropertyValue("--info"), t.warning = e.getPropertyValue("--warning"), t.danger = e.getPropertyValue("--danger"), t.light = e.getPropertyValue("--light"), t.dark = e.getPropertyValue("--dark");
    var a = moment.now.getDate,
        n = document.getElementById("calendar"),
        r = new FullCalendar.Calendar(n, {
            plugins: ["list", "dayGrid", "interaction", "bootstrap"],
            locale: "nl",
            defaultView: "dayGridMonth",
            height: "auto",
            contentHeight: "auto",
            aspectRatio: 2,
            themeSystem: "bootstrap",
            now: a,
            bootstrapFontAwesome: {
                custom1: "fa-calendar-plus"
            },
            editable: !0,
            droppable: !0,
            header: {
                left: "prev,next",
                center: "title",
                right: "custom1 listYear dayGridMonth,dayGridWeek"
            },
            scrollTime: "00:00:00",
            events: "../api/loadCalendarEvents.php",
            weekNumbers: !0,
            weekNumberTitle: "Week",
            allDaySlot: !1,
            selectable: !1,
            selectHelper: !1,
            forceEventDuration: !0,
            slotDuration: "01:00:00",
            eventResize: function (e) {
                var t = moment(e.start).format("Y-MM-DD HH:mm:ss"),
                    a = moment(e.end).format("Y-MM-DD HH:mm:ss"),
                    n = e.title,
                    o = e.id;
                $.ajax({
                    url: "../api/updateEventDate.php",
                    type: "POST",
                    data: {
                        title: n,
                        start: t,
                        end: a,
                        id: o
                    },
                    success: function () {
                        r.fullCalendar("refetchEvents"), alert("De tijd van het evenement is aangepast")
                    }
                })
            },
            eventDrop: function (e) {
                var t = moment(e.event.start).format("Y-MM-DD HH:mm:ss"),
                    a = moment(e.event.end).format("Y-MM-DD HH:mm:ss"),
                    n = e.event.title,
                    o = e.event.id;
                $.ajax({
                    url: "../api/updateEventDate.php",
                    type: "POST",
                    data: {
                        title: n,
                        start: t,
                        end: a,
                        id: o
                    },
                    success: function () {
                        r.render(), alert("De datum van het evenement is aangepast")
                    }
                })
            },
            eventClick: function (e) {
                var t = "edit.php?id=" + e.event.id;
                e.event.id, $("#modalBody").load("details.php?id=" + e.event.id), $("#eventUrl").attr("href", t), $("#deleteUrl").attr("value", e.event.id), $("#fullCalModal").modal()
            }
        });
    r.render()
    // $("div.fc-dayGrid-view table.table-bordered").addClass("card");
});