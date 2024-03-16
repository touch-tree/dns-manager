<!doctype html>
<html lang="en">

<head>
    <?= view('partials.header')->render(); ?>

    <title>Page not found</title>
</head>

<body>

<p>Page not found.</p>

<?php if (config('app.development_mode') === true) { ?>
    <p>Tried to access: <?= url()->current(); ?></p>
    <p>Base URL: <?= url()::base_url(); ?></p>
<?php } ?>

<?= view('partials.footer')->render(); ?>

</body>

</html>