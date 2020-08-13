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
    bootstrapFontAwesome: { custom1: 'fa-calendar-plus' },
    editable: !0,
    droppable: !0,
    header: { left: 'prev,next', center: 'title', right: 'custom1 listYear dayGridMonth' },
    scrollTime: '00:00:00',
    events: '/Events/loadCalendarEvents',
    // weekNumbers: !0,
    weekNumbers: true,
    weekNumbersWithinDays: true,
    weekNumberTitle: 'Week',
    allDaySlot: !1,
    selectable: !1,
    selectHelper: !1,
    forceEventDuration: !0,
    slotDuration: '01:00:00',
    eventDrop: function (e) {
      var start = moment(e.event.start).format('Y-MM-DD HH:mm:ss')
      var end = moment(e.event.end).format('Y-MM-DD HH:mm:ss')
      var title = e.event.title
      var id = e.event.id
      $.ajax({
        url: '/Events/updateEventDate',
        type: 'POST',
        data: { title: title, start: start, end: end, id: id },
        success: function () {
          calendar.render(), alert('De datum van het evenement is aangepast')
        }
      })
    },
    eventClick: function (e) {
      var link = '/Events/Edit?id=' + e.event.id
      $('#modalBody').load('/Events/Details?id=' + e.event.id), $('#eventUrl').attr('href', link), $('#deleteUrl').attr('value', e.event.id), $('#fullCalModal').modal()
    }
  })
  calendar.render()
})
