<!-- modal container -->

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $title ?? '' ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <i class="fa-solid fa-x"></i>
        </span>
        </button>
    </div>

    <div class="modal-body">
        <?php echo $content ?? '' ?>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        <?php if (isset($form) && $form) { ?>
            <button type="button" class="btn btn-primary">Submit</button>
        <?php } ?>
    </div>
</div>