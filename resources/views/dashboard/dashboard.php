<?php

use App\Models\Site;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= view('partials.header')->render(); ?>

    <title>Dashboard</title>
</head>

<body>

<?= view('partials.addons')->render(); ?>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <div class="dashboard-header">
            <h1 class="dashboard-header__header">Dashboard</h1>
            <div class="button-menu">
                <a href="<?= route('domain.add'); ?>">
                    <button class="btn btn-primary">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add a site
                    </button>
                </a>
                <a href="<?= route('domain.clear'); ?>" data-blockui>
                    <button class="btn btn-outline-primary">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        Refresh entries
                    </button>
                </a>
            </div>
        </div>
        <div class="dashboard-content">
            <table class="domain-table">
                <thead>
                <tr>
                    <th>Domain</th>
                    <th>Owner</th>
                    <th>CNAME</th>
                    <th>Nameservers</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($domains)) { ?>
                    <?php foreach ($domains as $key => $domain) { ?>
                        <?php if ($domain instanceof Site) { ?>
                            <tr>
                                <td class="domain-name">
                                    <a href="<?= route('domain.edit', ['id' => $domain->id()]); ?>"
                                       class="link">
                                        <?= $domain->name(); ?>
                                    </a>
                                </td>
                                <td class="owner"><?= strtolower($domain->account()->name()); ?></td>
                                <td>
                                    <?php
                                    foreach ($domain->dns_records()->all() as $dns_record) {
                                        echo $dns_record->name() . '<br>';
                                    }
                                    ?>
                                </td>
                                <td class="name-server">
                                    <?php
                                    foreach ($domain->nameservers() as $name_server) {
                                        echo $name_server . '<br>';
                                    }
                                    ?>
                                </td>
                                <td class="domain-status">
                                    <?= $status = strtoupper($domain->status()); ?>
                                </td>
                                <td class="created-on"><?= date('Y-m-d H:i:s', strtotime($domain->created_on())); ?></td>
                                <td class="options-action">
                                    <div class="options-action__container">
                                        <?php if (strtolower($status) === 'pending') { ?>
                                            <a href="<?= route('nameservers.check', ['id' => $domain->id()]); ?>">
                                                <button class="btn btn-outline-primary btn-activation-check">
                                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                                    Check nameservers
                                                </button>
                                            </a>
                                        <?php } ?>
                                        <a href="https://dash.cloudflare.com/<?= $domain->account()->id() ?>/<?= $domain->name(); ?>"
                                           target="_blank">
                                            <button class="btn btn-cloudflare normal-icon">
                                                <i class="fa-brands fa-cloudflare"></i>
                                            </button>
                                        </a>
                                        <div class="options">
                                            <div class="options-icon" data-popup-for="<?= $domain->id(); ?>">
                                                <i class="bi bi-three-dots"></i>
                                            </div>
                                            <div class="options-popup" data-popup="<?= $domain->id(); ?>">
                                                <a href="<?= route('domain.edit', ['id' => $domain->id()]); ?>">Edit</a>
                                                <a data-api-route="<?= route('domain.details.modal', ['id' => $domain->id()]); ?>"
                                                   data-toggle="modal"
                                                   data-target="#modal-main">
                                                    Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('partials.footer')->render(); ?>
</body>

</html>
