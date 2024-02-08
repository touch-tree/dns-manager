<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo get_template('header'); ?>

    <title>Edit</title>
</head>

<body>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <?php if (isset($domain)) { ?>
            <div class="dashboard-header">
                <h1 class="dashboard-header__header">Edit <?php echo $domain['name']; ?></h1>
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
                <form action="<?php echo route('domain.update', ['id' => $domain['id']]); ?>" method="post"
                      class="form js-form">
                    <div class="text-input-row">
                        <label for="root_cname_target" class="text-input-column">
                            Root CNAME target:
                            <input type="text" name="root_cname_target" id="root_cname_target"
                                   value="" required>
                        </label>

                        <label for="sub_cname_target" class="text-input-column">
                            Sub CNAME target:
                            <input type="text" name="sub_cname_target" id="sub_cname_target"
                                   value="" required>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary form-submit">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Update site
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<?php echo get_template('footer'); ?>
</body>

</html>