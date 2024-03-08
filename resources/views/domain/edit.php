<?php

use App\Models\Site;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view('partials.header')->render(); ?>

    <title>Edit</title>
</head>

<body>

<?php echo view('partials.dependencies')->render(); ?>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <?php if (isset($domain) && $domain instanceof Site) { ?>
            <div class="dashboard-header">
                <h1 class="dashboard-header__header">Edit <?php echo $domain->name(); ?></h1>
                <div class="button-menu">
                    <a href="<?php echo route('dashboard'); ?>">
                        <button class="btn btn-outline-primary">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            Return to dashboard
                        </button>
                    </a>
                    <button type="button" class="btn btn-secondary"
                            data-api-route="<?php echo route('domain.details.modal', ['id' => $domain->id()]); ?>"
                            data-toggle="modal"
                            data-target="#modal-main">
                        Details
                    </button>
                </div>
            </div>
            <div class="dashboard-content">
                <form action="<?php echo route('domain.update', ['id' => $domain->id()]); ?>" method="post"
                      class="form js-form">
                    <div class="text-input-row">
                        <label for="root_cname_target" class="text-input-column">
                            Root CNAME target:
                            <input type="text" name="root_cname_target" id="root_cname_target"
                                   value="<?php echo $domain->get_dns_records()->get($domain->name())->content(); ?>">
                            <?php if (error('root_cname_target')) { ?>
                                <span class="validation-text error"><?php echo error('root_cname_target') ?></span>
                            <?php } ?>
                        </label>

                        <label for="sub_cname_target" class="text-input-column">
                            Sub CNAME target:
                            <input type="text" name="sub_cname_target" id="sub_cname_target"
                                   value="<?php echo $domain->get_dns_records()->get('www.' . $domain->name())->content(); ?>">
                            <?php if (error('sub_cname_target')) { ?>
                                <span class="validation-text error"><?php echo error('sub_cname_target') ?></span>
                            <?php } ?>
                        </label>
                    </div>

                    <?php if (!empty($domain->pagerules()->all())) { ?>
                        <label for="pagerule_forwarding_url" class="text-input-column">
                            PAGERULE destination URL:
                            <input type="text" name="pagerule_forwarding_url" id="pagerule_forwarding_url"
                                   placeholder="For example: https://other.domain.sub.com/"
                                   value="<?php echo $domain->pagerules()->first()->forwarding_url(); ?>">
                            <?php if (error('pagerule_forwarding_url')) { ?>
                                <span class="validation-text error"><?php echo error('pagerule_forwarding_url') ?></span>
                            <?php } ?>
                        </label>
                    <?php } ?>

                    <button type="submit" class="btn btn-primary form-submit">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Update site
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<?php echo view('partials.footer')->render(); ?>
</body>

</html>