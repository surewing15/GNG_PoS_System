<div class="modal fade" id="skuModal" tabindex="-1" role="dialog" aria-labelledby="skuCreateModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryCreateModalLabel">Create SKU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_name">SKU Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="sku_name" name="sku_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create <i class="bi bi-check"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="skuModal" tabindex="-1" role="dialog" aria-labelledby="skuCreateModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryCreateModalLabel">Create SKU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sku_name">SKU Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="sku_name" name="sku_name" required>
                    </div>
                    <div class="form-group">
                        <label for="sku_options">SKU Options</label>
                        <div id="skuOptionsList">
                            <!-- Dynamic SKU options will be added here -->
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" id="addOptionBtn">Add Option</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create <i class="bi bi-check"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let skuSelect = document.getElementById('p_category');
    let localStorageKey = 'skuOptions';

    // Load SKU options from local storage on page load
    function loadSkuOptions() {
        const storedOptions = JSON.parse(localStorage.getItem(localStorageKey)) || [];
        skuSelect.innerHTML = `<option disabled selected>Select SKU</option>`; // Reset and add default option
        storedOptions.forEach(optionValue => {
            const option = document.createElement('option');
            option.value = optionValue.toLowerCase();
            option.textContent = optionValue;
            skuSelect.appendChild(option);
        });
    }

    // Function to add SKU option from the modal
    document.getElementById('categoryForm').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent form submission

        const skuName = document.getElementById('sku_name').value;

        if (skuName) {
            const newOption = document.createElement('option');
            newOption.value = skuName.toLowerCase();  // Use lowercase SKU name as the value
            newOption.textContent = skuName;  // Display the SKU name
            skuSelect.appendChild(newOption);  // Append new option to the select dropdown

            // Save the new option to local storage
            const storedOptions = JSON.parse(localStorage.getItem(localStorageKey)) || [];
            storedOptions.push(skuName);
            localStorage.setItem(localStorageKey, JSON.stringify(storedOptions));

            // Clear the modal form and close it
            document.getElementById('sku_name').value = '';
            document.querySelector('.btn-close').click();
        }
    });

    // Function to delete the selected SKU option
    document.getElementById('deleteSkuBtn').addEventListener('click', function() {
        if (skuSelect.selectedIndex > 0) {  // Ensure the user has selected an option that can be deleted
            const selectedOption = skuSelect.options[skuSelect.selectedIndex].text;

            // Remove option from local storage
            let storedOptions = JSON.parse(localStorage.getItem(localStorageKey)) || [];
            storedOptions = storedOptions.filter(option => option.toLowerCase() !== selectedOption.toLowerCase());
            localStorage.setItem(localStorageKey, JSON.stringify(storedOptions));

            // Remove the selected option from the dropdown
            skuSelect.remove(skuSelect.selectedIndex);
        } else {
            alert('Please select a SKU to delete.');
        }
    });

    // Load existing SKU options when the page loads
    loadSkuOptions();
});

</script>
