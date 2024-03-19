<?php

use App\Models\Site;

?>

<?php if (isset($domain) && $domain instanceof Site) { ?>
    <div class="property">
        <label>ID:</label>
        <span><?php echo $domain->id(); ?></span>
    </div>
    <div class="property">
        <label>Name:</label>
        <span><?php echo $domain->name(); ?></span>
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
        <span><?php echo $domain->status(); ?></span>
    </div>
    <div class="property">
        <label>Paused:</label>
        <span><?php echo $domain->is_paused(); ?></span>
    </div>
    <div class="property">
        <label>Type:</label>
        <span><?php echo $domain->type(); ?></span>
    </div>
    <div class="property">
        <label>Nameservers:</label>
        <span><?php echo implode(', ', $domain->nameservers()); ?></span>
    </div>
    <div class="property">
        <label>Original Nameservers:</label>
        <span><?php echo implode(', ', $domain->original_nameservers()); ?></span>
    </div>
    <div class="property">
        <label>Original Registrar:</label>
        <span><?php echo $domain->original_registrar(); ?></span>
    </div>
    <div class="property">
        <label>Original DNS Host:</label>
        <span><?php echo $domain->original_dnshost(); ?></span>
    </div>
    <div class="property">
        <label>Modified On:</label>
        <span><?php echo $domain->modified_on(); ?></span>
    </div>
    <div class="property">
        <label>Created On:</label>
        <span><?php echo $domain->created_on(); ?></span>
    </div>
    <div class="property">
        <label>Activated On:</label>
        <span><?php echo $domain->activated_on(); ?></span>
    </div>
    <div class="property">
        <label>Account ID:</label>
        <span><?php echo $domain->account()->id(); ?></span>
    </div>
    <div class="property">
        <label>Account Name:</label>
        <span><?php echo $domain->account()->name(); ?></span>
    </div>
    <div class="property">
        <label>Permissions:</label>
        <span><?php echo implode(', ', $domain->permissions()); ?></span>
    </div>
    <div class="property">
        <label>Plan ID:</label>
        <span><?php echo $domain->plan()->id(); ?></span>
    </div>
    <div class="property">
        <label>Plan Name:</label>
        <span><?php echo $domain->plan()->name(); ?></span>
    </div>
    <div class="property">
        <label>Plan Price:</label>
        <span><?php echo $domain->plan()->price(); ?></span>
    </div>
    <div class="property">
        <label>Plan Currency:</label>
        <span><?php echo $domain->plan()->currency(); ?></span>
    </div>
    <div class="property">
        <label>Plan Frequency:</label>
        <span><?php echo $domain->plan()->frequency(); ?></span>
    </div>
    <div class="property">
        <label>Is Subscribed:</label>
        <span><?php echo $domain->plan()->is_subscribed(); ?></span>
    </div>
    <div class="property">
        <label>Can Subscribe:</label>
        <span><?php echo $domain->plan()->can_subscribe(); ?></span>
    </div>
<?php } ?>