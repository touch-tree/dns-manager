<!DOCTYPE html>
<html lang="en">

<head>
    <?php

    use App\Services\DashboardService;

    echo get_template('header');

    ?>

    <title>Dashboard</title>
</head>

<body>

<?php echo get_template('modal'); ?>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <div class="dashboard-header">
            <h1 class="dashboard-header__header">Dashboard</h1>
            <div class="button-menu">
                <a href="<?php echo route('domain.add'); ?>">
                    <button class="btn btn-primary">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add a site
                    </button>
                </a>
                <a href="<?php echo route('dashboard'); ?>">
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
                        <tr>
                            <td class="domain-name">
                                <a href="<?php echo route('domain.edit', ['id' => $domain['id']]); ?>"
                                   class="link">
                                    <?php echo $domain['name']; ?>
                                </a>
                            </td>
                            <td class="owner"><?php echo strtolower($domain['account']['name']); ?></td>
                            <td>
                                <?php
                                foreach (app(DashboardService::class)->get_dns_records($domain['id'])['result'] as $dns_record) {
                                    echo $dns_record['name'] . '<br>';
                                }
                                ?>
                            </td>
                            <td class="name-server">
                                <?php
                                foreach ($domain['name_servers'] as $name_server) {
                                    echo $name_server . '<br>';
                                }
                                ?>
                            </td>
                            <td class="domain-status">
                                <?php
                                $status = strtoupper($domain['status']);

                                echo $status;
                                ?>
                            </td>
                            <td class="created-on"><?php echo date('Y-m-d H:i:s', strtotime($domain['created_on'])); ?></td>
                            <td class="options-action">
                                <div class="options-action__container">
                                    <?php if (strtolower($status) === 'pending') { ?>
                                        <a href="<?php echo route('nameservers.verify', ['id' => $domain['id']]); ?>">
                                            <button class="btn btn-outline-primary btn-activation-check">
                                                <i class="fa-solid fa-check" aria-hidden="true"></i>
                                                Check nameservers
                                            </button>
                                        </a>
                                    <?php } ?>
                                    <a href="https://dash.cloudflare.com/<?php echo $domain['account']['id'] ?>/<?php echo $domain['name']; ?>"
                                       target="_blank">
                                        <button class="btn btn-cloudflare normal-icon">
                                            <i class="fa-brands fa-cloudflare"></i>
                                        </button>
                                    </a>
                                    <div class="options">
                                        <div class="options-icon" data-popup-for="<?php echo $domain['id']; ?>">
                                            <i class="bi bi-three-dots"></i>
                                        </div>
                                        <div class="options-popup" data-popup="<?php echo $domain['id']; ?>">
                                            <a href="<?php echo route('domain.edit', ['id' => $domain['id']]); ?>">Edit</a>
                                            <a href="<?php echo route('domain.details', ['id' => $domain['id']]); ?>">Details</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo get_template('footer'); ?>
</body>

</html>
