<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($arrOrders as $objOrder) { ?>
            <tr>
                <td><?=$objOrder->getId();?></td>
                <td><?=$objOrder->getStatusAsString();?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>