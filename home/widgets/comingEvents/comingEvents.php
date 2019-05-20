<h4><?php echo Text::get('TITLE_WIDGET_COMING_EVENTS'); ?></h4>
<div id="show-events"></div>
<script>
$(document).ready(function () {
// $('#get-data').click(function () {
    var showData = $('#show-events');

    $.getJSON('../../../api/loadComingEvents.php', function (data) {
      //console.log(data);
      var records = data.map(function (item) {
        
        var tempdisplaydatetime = new Date(0);
        tempdisplaydatetime.setUTCSeconds(item.date_time);
        displaydate = tempdisplaydatetime.toLocaleDateString();
        displaytime = tempdisplaydatetime.toLocaleTimeString();
        displaydatetime = displaydate + ' ' + displaytime;
        return '<i class="far fa-calendar"></i> <a href="/events/details.php?id=' + item.id + '">' + item.title + '</a><br>Start: ' + item.start;
      });

      showData.empty();

      if (records.length) {
        var content = '<li>' + records.join('</li><li>') + '</li>';
        var list = $('<ul />').html(content);
        showData.append(list);
      }
    });

    showData.text('Laden...');
//   });
});
</script>