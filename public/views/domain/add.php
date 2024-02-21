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
                <form action="<?php echo route('domain.create'); ?>" method="post" class="form js-form">
                    <label for="domain" class="text-input-column">
                        Domain:
                        <input type="text" name="domain" id="domain" placeholder="For example: domain.sub.com"
                               value="<?php echo old('domain') ?>">
                        <?php if (error('domain')) { ?>
                            <span class="validation-text error"><?php echo error('domain') ?></span>
                        <?php } ?>
                    </label>

                    <div class="text-input-row">
                        <label for="root_cname_target" class="text-input-column">
                            Root CNAME target:
                            <input type="text" name="root_cname_target" id="root_cname_target"
                                   value="<?php echo old('root_cname_target') ?>">
                            <?php if (error('root_cname_target')) { ?>
                                <span class="validation-text error"><?php echo error('root_cname_target') ?></span>
                            <?php } ?>
                        </label>

                        <label for="sub_cname_target" class="text-input-column">
                            Sub CNAME target:
                            <input type="text" name="sub_cname_target" id="sub_cname_target"
                                   value="<?php echo old('sub_cname_target') ?>">
                            <?php if (error('sub_cname_target')) { ?>
                                <span class="validation-text error"><?php echo error('sub_cname_target') ?></span>
                            <?php } ?>
                        </label>
                    </div>

                    <div class="text-input-row">
                        <label for="pagerule_url" class="text-input-column">
                            PAGERULE URL:
                            <input type="text" name="pagerule_url" id="pagerule_url"
                                   placeholder="For example: domain.sub.com" value="<?php echo old('pagerule_url') ?>">
                            <?php if (error('pagerule_url')) { ?>
                                <span class="validation-text error"><?php echo error('pagerule_url') ?></span>
                            <?php } ?>
                        </label>

                        <label for="pagerule_full_url" class="text-input-column">
                            PAGERULE FULL URL:
                            <input type="text" name="pagerule_full_url" id="pagerule_full_url"
                                   placeholder="For example: www.domain.sub.com"
                                   value="<?php echo old('pagerule_full_url') ?>">
                            <?php if (error('pagerule_full_url')) { ?>
                                <span class="validation-text error"><?php echo error('pagerule_full_url') ?></span>
                            <?php } ?>
                        </label>
                    </div>

                    <label for="pagerule_destination_url" class="text-input-column">
                        PAGERULE destination URL:
                        <input type="text" name="pagerule_destination_url" id="pagerule_destination_url"
                               value="<?php echo old('pagerule_destination_url') ?>">
                        <?php if (error('pagerule_destination_url')) { ?>
                            <span class="validation-text error"><?php echo error('pagerule_destination_url') ?></span>
                        <?php } ?>
                    </label>

                    <button type="submit" class="btn btn-primary form-submit">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add site
                    </button>
                </form>
            </form>
        </div>
    </div>
</div>

<?php echo get_template('footer'); ?>
</body>

</html>