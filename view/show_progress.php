<?php 
include __DIR__ . '/header.php';
?>

<h1>Processing orders...</h1>

<div class="progress my-5" role="progressbar" aria-label="Progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="height: 20px">
    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">0%</div>
</div>

<table class="table table-striped table-hover d-none">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="alert alert-success fade" role="alert">
    Finished processing orders! <a href="<?= self::APP_URL ?>">Return to the list of orders</a>.
</div>

<script>
    const APP_URL = '<?= self::APP_URL ?>';
    const QUEUE_KEY = '<?= $queueKey ?>';
</script>

<script src="js/show_progress.js"></script>

<?php
include __DIR__ . '/footer.php';
?>