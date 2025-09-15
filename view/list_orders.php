<div class="d-flex flex-wrap justify-content-end mb-3">
    <form class="col-12 col-lg-auto">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateOrdersModal">
            Generate orders
        </button>
    </form>
</div>

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
                <td><?= $objOrder->getId(); ?></td>
                <td><?= $objOrder->getStatusAsString(); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

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