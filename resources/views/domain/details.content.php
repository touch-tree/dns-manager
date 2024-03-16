<?php

use App\Models\Site;

?>

<?php if (isset($domain) && $domain instanceof Site) { ?>
    <div class="property">
        <label>ID:</label>
        <span><?= $domain->id(); ?></span>
    </div>
    <div class="property">
        <label>Name:</label>
        <span><?= $domain->name(); ?></span>
    </div>
    <div class="property">
        <label>CNAME:</label>
        <span>
            <?php
            foreach ($domain->dns_records()->all() as $dns_record) {
                echo $dns_record->name() . '<br>';
            }
            ?>
        </span>
    </div>
    <div class="property">
        <label>Status:</label>
        <span><?= $domain->status(); ?></span>
    </div>
    <div class="property">
        <label>Paused:</label>
        <span><?= $domain->is_paused(); ?></span>
    </div>
    <div class="property">
        <label>Type:</label>
        <span><?= $domain->type(); ?></span>
    </div>
    <div class="property">
        <label>Nameservers:</label>
        <span><?= implode(', ', $domain->nameservers()); ?></span>
    </div>
    <div class="property">
        <label>Original Nameservers:</label>
        <span><?= implode(', ', $domain->original_nameservers()); ?></span>
    </div>
    <div class="property">
        <label>Original Registrar:</label>
        <span><?= $domain->original_registrar(); ?></span>
    </div>
    <div class="property">
        <label>Original DNS Host:</label>
        <span><?= $domain->original_dnshost(); ?></span>
    </div>
    <div class="property">
        <label>Modified On:</label>
        <span><?= $domain->modified_on(); ?></span>
    </div>
    <div class="property">
        <label>Created On:</label>
        <span><?= $domain->created_on(); ?></span>
    </div>
    <div class="property">
        <label>Activated On:</label>
        <span><?= $domain->activated_on(); ?></span>
    </div>
    <div class="property">
        <label>Account ID:</label>
        <span><?= $domain->account()->id(); ?></span>
    </div>
    <div class="property">
        <label>Account Name:</label>
        <span><?= $domain->account()->name(); ?></span>
    </div>
    <div class="property">
        <label>Permissions:</label>
        <span><?= implode(', ', $domain->permissions()); ?></span>
    </div>
    <div class="property">
        <label>Plan ID:</label>
        <span><?= $domain->plan()->id(); ?></span>
    </div>
    <div class="property">
        <label>Plan Name:</label>
        <span><?= $domain->plan()->name(); ?></span>
    </div>
    <div class="property">
        <label>Plan Price:</label>
        <span><?= $domain->plan()->price(); ?></span>
    </div>
    <div class="property">
        <label>Plan Currency:</label>
        <span><?= $domain->plan()->currency(); ?></span>
    </div>
    <div class="property">
        <label>Plan Frequency:</label>
        <span><?= $domain->plan()->frequency(); ?></span>
    </div>
    <div class="property">
        <label>Is Subscribed:</label>
        <span><?= $domain->plan()->is_subscribed(); ?></span>
    </div>
    <div class="property">
        <label>Can Subscribe:</label>
        <span><?= $domain->plan()->can_subscribe(); ?></span>
    </div>
<?php } ?>