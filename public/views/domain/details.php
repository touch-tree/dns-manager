<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo get_template('header'); ?>

    <title>Details</title>
</head>

<body>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <?php if (isset($domain)) { ?>
            <div class="dashboard-header">
                <h1 class="dashboard-header__header">Details for <?php echo $domain['name']; ?></h1>
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
                <div class="property">
                    <label>ID:</label>
                    <span><?php echo $domain['id']; ?></span>
                </div>
                <div class="property">
                    <label>Name:</label>
                    <span><?php echo $domain['name']; ?></span>
                </div>
                <div class="property">
                    <label>Status:</label>
                    <span><?php echo $domain['status']; ?></span>
                </div>
                <div class="property">
                    <label>Paused:</label>
                    <span><?php echo $domain['paused']; ?></span>
                </div>
                <div class="property">
                    <label>Type:</label>
                    <span><?php echo $domain['type']; ?></span>
                </div>
                <div class="property">
                    <label>Development Mode:</label>
                    <span><?php echo $domain['development_mode']; ?></span>
                </div>
                <div class="property">
                    <label>Name Servers:</label>
                    <span><?php echo implode(', ', $domain['name_servers']); ?></span>
                </div>
                <div class="property">
                    <label>Original Name Servers:</label>
                    <span><?php echo implode(', ', $domain['original_name_servers']); ?></span>
                </div>
                <div class="property">
                    <label>Original Registrar:</label>
                    <span><?php echo $domain['original_registrar']; ?></span>
                </div>
                <div class="property">
                    <label>Original DNS Host:</label>
                    <span><?php echo $domain['original_dnshost']; ?></span>
                </div>
                <div class="property">
                    <label>Modified On:</label>
                    <span><?php echo $domain['modified_on']; ?></span>
                </div>
                <div class="property">
                    <label>Created On:</label>
                    <span><?php echo $domain['created_on']; ?></span>
                </div>
                <div class="property">
                    <label>Activated On:</label>
                    <span><?php echo $domain['activated_on']; ?></span>
                </div>
                <div class="property">
                    <label>Meta Step:</label>
                    <span><?php echo $domain['meta']['step']; ?></span>
                </div>
                <div class="property">
                    <label>Meta Custom Certificate Quota:</label>
                    <span><?php echo $domain['meta']['custom_certificate_quota']; ?></span>
                </div>
                <div class="property">
                    <label>Meta Page Rule Quota:</label>
                    <span><?php echo $domain['meta']['page_rule_quota']; ?></span>
                </div>
                <div class="property">
                    <label>Meta Phishing Detected:</label>
                    <span><?php echo $domain['meta']['phishing_detected']; ?></span>
                </div>
                <div class="property">
                    <label>Meta Multiple Railguns Allowed:</label>
                    <span><?php echo $domain['meta']['multiple_railguns_allowed']; ?></span>
                </div>
                <div class="property">
                    <label>Owner ID:</label>
                    <span><?php echo $domain['owner']['id']; ?></span>
                </div>
                <div class="property">
                    <label>Owner Type:</label>
                    <span><?php echo $domain['owner']['type']; ?></span>
                </div>
                <div class="property">
                    <label>Owner Email:</label>
                    <span><?php echo $domain['owner']['email']; ?></span>
                </div>
                <div class="property">
                    <label>Account ID:</label>
                    <span><?php echo $domain['account']['id']; ?></span>
                </div>
                <div class="property">
                    <label>Account Name:</label>
                    <span><?php echo $domain['account']['name']; ?></span>
                </div>
                <div class="property">
                    <label>Tenant ID:</label>
                    <span><?php echo $domain['tenant']['id']; ?></span>
                </div>
                <div class="property">
                    <label>Tenant Name:</label>
                    <span><?php echo $domain['tenant']['name']; ?></span>
                </div>
                <div class="property">
                    <label>Tenant Unit ID:</label>
                    <span><?php echo $domain['tenant_unit']['id']; ?></span>
                </div>
                <div class="property">
                    <label>Permissions:</label>
                    <span><?php echo implode(', ', $domain['permissions']); ?></span>
                </div>
                <div class="property">
                    <label>Plan ID:</label>
                    <span><?php echo $domain['plan']['id']; ?></span>
                </div>
                <div class="property">
                    <label>Plan Name:</label>
                    <span><?php echo $domain['plan']['name']; ?></span>
                </div>
                <div class="property">
                    <label>Plan Price:</label>
                    <span><?php echo $domain['plan']['price']; ?></span>
                </div>
                <div class="property">
                    <label>Plan Currency:</label>
                    <span><?php echo $domain['plan']['currency']; ?></span>
                </div>
                <div class="property">
                    <label>Plan Frequency:</label>
                    <span><?php echo $domain['plan']['frequency']; ?></span>
                </div>
                <div class="property">
                    <label>Is Subscribed:</label>
                    <span><?php echo $domain['plan']['is_subscribed']; ?></span>
                </div>
                <div class="property">
                    <label>Can Subscribe:</label>
                    <span><?php echo $domain['plan']['can_subscribe']; ?></span>
                </div>
                <div class="property">
                    <label>Legacy ID:</label>
                    <span><?php echo $domain['plan']['legacy_id']; ?></span>
                </div>
                <div class="property">
                    <label>Legacy Discount:</label>
                    <span><?php echo $domain['plan']['legacy_discount']; ?></span>
                </div>
                <div class="property">
                    <label>Externally Managed:</label>
                    <span><?php echo $domain['plan']['externally_managed']; ?></span>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php echo get_template('footer'); ?>
</body>

</html>


