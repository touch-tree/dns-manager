<?php

use App\Models\Site;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= view('partials.header')->render(); ?>

    <title>Details</title>
</head>

<body>

<?= view('partials.addons')->render(); ?>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <?php if (isset($domain) && $domain instanceof Site) { ?>
            <div class="dashboard-header">
                <h1 class="dashboard-header__header">Details for <?= $domain->name(); ?></h1>
                <div class="button-menu">
                    <a href="<?= route('dashboard'); ?>">
                        <button class="btn btn-outline-primary">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            Return to dashboard
                        </button>
                    </a>
                </div>
            </div>
            <div class="dashboard-content">
                <?= view(resource_path('views/domain/details.content.php'), ['domain' => $domain])->render(); ?>
            </div>
        <?php } ?>
    </div>
</div>

<?= view('partials.footer')->render(); ?>
</body>

</html>


