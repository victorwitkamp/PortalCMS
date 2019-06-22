document.addEventListener('DOMContentLoaded', function () {
  var style = getComputedStyle(document.body);
  var theme = {};

  theme.primary = style.getPropertyValue('--primary');
  theme.secondary = style.getPropertyValue('--secondary');
  theme.success = style.getPropertyValue('--success');
  theme.info = style.getPropertyValue('--info');
  theme.warning = style.getPropertyValue('--warning');
  theme.danger = style.getPropertyValue('--danger');
  theme.light = style.getPropertyValue('--light');
  theme.dark = style.getPropertyValue('--dark');

  var now = moment.now.getDate;
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: ['list', 'dayGrid', 'interaction', 'bootstrap'],
    locale: 'nl',
    defaultView: 'dayGridMonth',
    height: 'auto',
    contentHeight: 'auto',
    aspectRatio: 2,
    themeSystem: 'bootstrap',
    now: now,
    bootstrapFontAwesome: {
      custom1: 'fa-calendar-plus'
    },

    editable: true,
    droppable: true,
    header: {
      left: 'prev,next',
      center: 'title',
      right: 'custom1 listYear dayGridMonth,dayGridWeek'
    },
    scrollTime: '00:00:00',
    events: '../api/loadEvents.php',
    weekNumbers: true,
    weekNumberTitle: "Week",
    allDaySlot: false,
    selectable: false,
    selectHelper: false,
    forceEventDuration: true,
    slotDuration: '01:00:00',
    eventResize: function (event) {
      var start = moment(event.start).format('Y-MM-DD HH:mm:ss');
      var end = moment(event.end).format('Y-MM-DD HH:mm:ss');
      var title = event.title;
      var id = event.id;
      $.ajax({
        url: "../api/updateEventDate.php",
        type: "POST",
        data: { title: title, start: start, end: end, id: id },
        success: function () {
          calendar.fullCalendar('refetchEvents');
          alert('De tijd van het evenement is aangepast');
        }
      })
    },
    eventDrop: function (info) {
      var start = moment(info.event.start).format('Y-MM-DD HH:mm:ss');
      var end = moment(info.event.end).format('Y-MM-DD HH:mm:ss');
      var title = info.event.title;
      var id = info.event.id;
      $.ajax({
        url: "../api/updateEventDate.php",
        type: "POST",
        data: { title: title, start: start, end: end, id: id },
        success: function () {
          calendar.render();
          alert("De datum van het evenement is aangepast");
        }
      });
    },
    eventClick: function (info) {
      var myurl = 'edit.php?id=' + info.event.id;
      var deleteurl = 'delete.php?id=' + info.event.id;
      $('#modalBody').load("details.php?id=" + info.event.id);
      $('#eventUrl').attr('href', myurl);
      $('#deleteUrl').attr('value', info.event.id);
      $('#fullCalModal').modal();
    }
  });
calendar.render();
});