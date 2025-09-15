<?php if (!empty($this->getAlert())) { ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= $this->getAlert() ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>

<div class="d-flex flex-wrap justify-content-end mb-3">
    <form class="col-12 col-lg-auto">
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#generateOrdersModal">
            Generate orders
        </button>
        <button type="button" class="btn btn-primary" id="btnProcessOrders">
            Process orders
        </button>
    </form>
</div>

<form id="frmProcessOrders" action="/?action=process_orders" method="POST">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col"><input type="checkbox" id="checkAllOrders"/></th>
                <th scope="col">ID</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($arrOrders as $objOrder) { ?>
                <tr>
                    <td><input type="checkbox" name="id[]" value="<?= $objOrder->getId() ?>"/></td>
                    <td><?= $objOrder->getId() ?></td>
                    <td><?= $objOrder->getStatusAsString() ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</form>

<div class="modal fade" id="generateOrdersModal" tabindex="-1" aria-labelledby="generateOrdersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="generateOrdersForm" action="/?action=generate_orders" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="generateOrdersModalLabel">Generate orders</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="howManyOrders" name="how_many" placeholder="How many orders?" />
                        <label for="howManyOrders">How many orders?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Generate"/>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/list_orders.js"></script>