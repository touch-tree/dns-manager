<?php

use App\Domain\Site\Site;

/**
 * @var Site $domain ;
 */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view('partials.header')->render(); ?>

    <title>Details</title>
</head>

<body>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <?php if (isset($domain)) { ?>
            <div class="dashboard-header">
                <h1 class="dashboard-header__header">Details for <?php echo $domain->name(); ?></h1>
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
                <?php echo view(resource_path('views/domain/details_content.php'), ['domain' => $domain])->render(); ?>
            </div>
        <?php } ?>
    </div>
</div>

<?php echo view('partials.footer')->render(); ?>
</body>

</html>


