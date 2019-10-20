<?php

use PortalCMS\Core\Text;

?>
<h4><?php echo Text::get('TITLE_WIDGET_COMING_EVENTS'); ?></h4>
<div class="card" id="show-events"></div>


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
        var content = '<li class="list-group-item">' + records.join('</li><li>') + '</li>';
        // var list = $('<ul />').html(content);
        var listContent = '<ul class="list-group list-group-flush">' + content + '</ul>';
        showData.append(listContent);
      }
    });

    showData.text('Laden...');
//   });
});
</script>
