<?php 
include __DIR__ . '/header.php';
?>

<h1>Processing orders...</h1>

<div class="progress my-5" role="progressbar" aria-label="Progress" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="height: 20px">
    <div class="progress-bar <?= ($progress < 100 ? 'progress-bar-striped' : 'text-bg-success') ?>"
        style="width: <?= $progress ?>%"><?= $progress ?>%</div>
</div>

<?php if ($progress < 100) { ?>
    <script>
        function autoRefresh() {
            location.reload();
        }

        setInterval(autoRefresh, 1000);
    </script>
<?php } else { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Finished processing orders! <a href="<?= self::APP_URL ?>">Return to the list of orders</a>.
    </div>
<?php } ?>

<?php
include __DIR__ . '/footer.php';
?>