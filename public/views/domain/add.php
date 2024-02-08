<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo get_template('header'); ?>

    <title>Add</title>
</head>

<body>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <div class="dashboard-header">
            <h1 class="dashboard-header__header">Add a site</h1>
            <div class="button-menu">
                <a href="<?php echo route('dashboard'); ?>">
                    <button class="btn btn-outline-primary">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Return to dashboard
                    </button>
                </a>
            </div>
        </div>
        <div class="dashboard-content">
            <form action="<?php echo route('domain.create'); ?>" method="post" class="form js-form">
                <?php echo view('domain.form.add')->render(); ?>
            </form>
        </div>
    </div>
</div>

<?php echo get_template('footer.php'); ?>
</body>

</html>