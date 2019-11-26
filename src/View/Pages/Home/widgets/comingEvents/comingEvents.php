<?php

use PortalCMS\Core\View\Text;

?>
<h4><?= Text::get('TITLE_WIDGET_COMING_EVENTS') ?></h4>
<div class="card" id="show-events"></div>
<script>
$(document).ready(function () {
    var showData = $('#show-events');
    $.getJSON('/Events/loadComingEvents', function (data) {
      var content;
      var listContent;
      var records = data.map(function (item) {
        var tempdisplaydatetime = new Date(0);
        tempdisplaydatetime.setUTCSeconds(item.date_time);
        displaydate = tempdisplaydatetime.toLocaleDateString();
        displaytime = tempdisplaydatetime.toLocaleTimeString();
        displaydatetime = displaydate + ' ' + displaytime;
        return '<i class="far fa-calendar"></i> <a href="/events/details?id=' + item.id + '">' + item.title + '</a><br>Start: ' + item.start;
      });
      showData.empty();
      if (records.length) {
        content = '<li class="list-group-item">' + records.join('</li><li>') + '</li>';
        listContent = '<ul class="list-group list-group-flush">' + content + '</ul>';
        showData.append(listContent);
      }
    });
    // showData.text('Laden...');
});
</script>
