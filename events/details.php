<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("events")) {
    Redirect::permissionerror();
    die();
}
$row = Event::getEvent($_GET['id']);
?>

<div class="row">
    <!-- <div class="col-sm-6"><strong>ID:</strong></div> -->
    <!-- <div class="col-sm-6"><p><?php //echo $row['id']; ?></p></div> -->
    <div class="col-sm-6"><strong><?php echo Text::get('LABEL_EVENT_TITLE'); ?>:</strong></div>
    <div class="col-sm-6"><p><?php echo $row['title']; ?></p></div>
    <div class="col-sm-6"><strong><?php echo Text::get('LABEL_EVENT_ADDED_BY'); ?>:</strong></div>
    <div class="col-sm-6"><p><?php
    $User = User::getProfileById($row['CreatedBy']);
    echo $User['user_name']; ?></p></div>

    <div class="col-sm-6"><strong><?php echo Text::get('LABEL_EVENT_START'); ?>:</strong></div>
    <div class="col-sm-6"><p><?php echo $row['start_event']; ?></p></div>
    <div class="col-sm-6"><strong><?php echo Text::get('LABEL_EVENT_END'); ?>:</strong></div>
    <div class="col-sm-6"><p><?php echo $row['end_event']; ?></p></div>
    <div class="col-sm-6"><strong><?php echo Text::get('LABEL_EVENT_DESC'); ?>:</strong></div>
    <div class="col-sm-6"><p><?php echo $row['description']; ?></p></div>
    <!-- <hr>
    <div class="col-sm-6"><strong>Bestuursdienst:</strong></div>
    <div class="col-sm-6"><p></p></div>
    <div class="col-sm-6"><strong>Deurdienst:</strong></div>
    <div class="col-sm-6"><p></p></div>
    <div class="col-sm-6"><strong>Bardienst:</strong></div>
    <div class="col-sm-6"><p></p></div>
    <div class="col-sm-6"><strong>Licht/geluid:</strong></div>
    <div class="col-sm-6"><p></p></div> -->
</div>