<div class="modal fade" id="newpriceModal" tabindex="-1" role="dialog" aria-labelledby="categoryCreateModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryCreateModalLabel">New Price</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="newpriceForm">
                <div class="modal-body">
                    <div class="form-control-wrap number-spinner-wrap d-flex align-items-center">
                        <button type="button" class="btn btn-icon btn-primary number-spinner-btn number-minus" data-number="minus">
                            <em class="icon ni ni-minus"></em>
                        </button>
                        <input type="number" class="form-control number-spinner mx-2" value="0" min="0" id="spinnerInput">
                        <button type="button" class="btn btn-icon btn-primary number-spinner-btn number-plus" data-number="plus">
                            <em class="icon ni ni-plus"></em>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save<i class="bi bi-check"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Selecting elements
    const spinnerInput = document.getElementById('spinnerInput');
    const minusBtn = document.querySelector('.number-minus');
    const plusBtn = document.querySelector('.number-plus');

    // Minus Button Event
    minusBtn.addEventListener('click', function() {
        let value = parseInt(spinnerInput.value);
        if (value > 0) {
            spinnerInput.value = value - 1;
        }
    });

    // Plus Button Event
    plusBtn.addEventListener('click', function() {
        let value = parseInt(spinnerInput.value);
        spinnerInput.value = value + 0;
    });
</script>

