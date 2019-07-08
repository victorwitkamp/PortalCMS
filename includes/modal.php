<?php
/* Acording to te filename this has probably something to do with a modal
 *
 * not sure though...
 */
?>
<div class="modal fade" id="exampleModalCenter" tabindex="-1"
role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    <?php echo stripslashes($value['title']); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo stripslashes($value['message']); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="/includes/js/modal.js"></script>
