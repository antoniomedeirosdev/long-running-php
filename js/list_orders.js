const btnProcessOrders = document.getElementById('btnProcessOrders');
const checkAllOrders = document.getElementById('checkAllOrders');
const orderCheckboxes = document.getElementsByName('id[]');

btnProcessOrders.addEventListener('click', function () {
    const frmProcessOrders = document.getElementById('frmProcessOrders');
    frmProcessOrders.submit();
});

checkAllOrders.addEventListener('change', function() {
    for (let i = 0; i < orderCheckboxes.length; i++) {
        orderCheckboxes[i].checked = checkAllOrders.checked;
    }
});

for (let i = 0; i < orderCheckboxes.length; i++) {
    orderCheckboxes[i].addEventListener('change', function(event) {
        if (event.target.checked) {
            let blnAtLeastOneUnchecked = false;
            for (let i = 0; i < orderCheckboxes.length; i++) {
                if (!orderCheckboxes[i].checked) {
                    blnAtLeastOneUnchecked = true;
                    break;
                }
            }
            if (!blnAtLeastOneUnchecked) {
                checkAllOrders.checked = true;
            }
        } else {
            checkAllOrders.checked = false;
        }
    });
}
