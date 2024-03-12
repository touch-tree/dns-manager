<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view('partials.header')->render(); ?>

    <title>Add</title>
</head>

<body>

<?php echo view('partials.dependencies')->render(); ?>

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
            <form action="<?php echo route('domain.create'); ?>" method="post" class="form js-form" data-blockui-form>
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
                        <label for="root_cname_target" class="text-input-column checkbox-enable">
                            Root CNAME target:
                            <input type="text" name="root_cname_target" id="root_cname_target" readonly
                                   value="<?php echo old('root_cname_target', config('root_cname_target', '')) ?>">
                            <label class="checkbox-enable__label">
                                <input type="checkbox" data-enable-input="#root_cname_target">
                                <span>Enable input field</span>
                            </label>
                            <?php if (error('root_cname_target')) { ?>
                                <span class="validation-text error"><?php echo error('root_cname_target') ?></span>
                            <?php } ?>
                        </label>

                        <label for="sub_cname_target" class="text-input-column checkbox-enable">
                            Sub CNAME target:
                            <input type="text" name="sub_cname_target" id="sub_cname_target" readonly
                                   value="<?php echo old('sub_cname_target', config('sub_cname_target', '')) ?>">
                            <label class="checkbox-enable__label">
                                <input type="checkbox" data-enable-input="#sub_cname_target">
                                <span>Enable input field</span>
                            </label>
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

                    <label for="pagerule_forwarding_url" class="text-input-column">
                        PAGERULE destination URL:
                        <input type="text" name="pagerule_forwarding_url" id="pagerule_forwarding_url"
                               placeholder="For example: https://other.domain.sub.com/"
                               value="<?php echo old('pagerule_forwarding_url') ?>">
                        <?php if (error('pagerule_forwarding_url')) { ?>
                            <span class="validation-text error"><?php echo error('pagerule_forwarding_url') ?></span>
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

<?php echo view('partials.footer')->render(); ?>
</body>

</html>