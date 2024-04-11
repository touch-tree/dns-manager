<!doctype html>
<html lang="en">

<head>
    <?php echo view('partials.header')->render(); ?>

    <title>Page not found</title>
</head>

<body>

<p>Page not found.</p>

<?php if (config('app.development_mode') === true) { ?>
    <p>Tried to access: <?php echo url()->current(); ?></p>
    <p>Base URL: <?php echo url()->full(); ?></p>
<?php } ?>

<?php echo view('partials.footer')->render(); ?>

</body>

</html>