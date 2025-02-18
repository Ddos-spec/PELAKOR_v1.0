// Customer Management Functions
function addCustomer() {
    const name = document.getElementById('customerName').value;
    const phone = document.getElementById('customerPhone').value;

    fetch('api/customers.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            name: name,
            phone: phone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error adding customer: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateCustomer() {
    const id = document.getElementById('editCustomerId').value;
    const name = document.getElementById('editCustomerName').value;
    const phone = document.getElementById('editCustomerPhone').value;

    fetch('api/customers.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'update',
            id: id,
            name: name,
            phone: phone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating customer: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteCustomer(id) {
    if (confirm('Are you sure you want to delete this customer?')) {
        fetch('api/customers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'delete',
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting customer: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Transaction Management Functions
function addTransaction() {
    const customerId = document.getElementById('customerSelect').value;
    const date = document.getElementById('transactionDate').value;
    const status = document.getElementById('transactionStatus').value;

    fetch('api/transactions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            customerId: customerId,
            date: date,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error adding transaction: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateTransaction() {
    const id = document.getElementById('editTransactionId').value;
    const customerId = document.getElementById('editCustomerSelect').value;
    const date = document.getElementById('editTransactionDate').value;
    const status = document.getElementById('editTransactionStatus').value;

    fetch('api/transactions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'update',
            id: id,
            customerId: customerId,
            date: date,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating transaction: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteTransaction(id) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        fetch('api/transactions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'delete',
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting transaction: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Laundry Type Management Functions
function addLaundryType() {
    const name = document.getElementById('laundryTypeName').value;
    const price = document.getElementById('laundryTypePrice').value;

    fetch('api/laundry_types.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            name: name,
            price: price
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error adding laundry type: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateLaundryType() {
    const id = document.getElementById('editLaundryTypeId').value;
    const name = document.getElementById('editLaundryTypeName').value;
    const price = document.getElementById('editLaundryTypePrice').value;

    fetch('api/laundry_types.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'update',
            id: id,
            name: name,
            price: price
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating laundry type: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteLaundryType(id) {
    if (confirm('Are you sure you want to delete this laundry type?')) {
        fetch('api/laundry_types.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'delete',
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting laundry type: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Inventory Management Functions
function addInventory() {
    const name = document.getElementById('inventoryName').value;
    const stock = document.getElementById('inventoryStock').value;

    fetch('api/inventory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            name: name,
            stock: stock
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error adding inventory item: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateInventory() {
    const id = document.getElementById('editInventoryId').value;
    const name = document.getElementById('editInventoryName').value;
    const stock = document.getElementById('editInventoryStock').value;

    fetch('api/inventory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'update',
            id: id,
            name: name,
            stock: stock
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating inventory item: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteInventory(id) {
    if (confirm('Are you sure you want to delete this inventory item?')) {
        fetch('api/inventory.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'delete',
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting inventory item: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Initialize modals
document.addEventListener('DOMContentLoaded', function() {
    // Customer Modal
    const editCustomerModal = document.getElementById('editCustomerModal');
    if (editCustomerModal) {
        editCustomerModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const phone = button.getAttribute('data-phone');

            document.getElementById('editCustomerId').value = id;
            document.getElementById('editCustomerName').value = name;
            document.getElementById('editCustomerPhone').value = phone;
        });
    }

    // Transaction Modal
    const editTransactionModal = document.getElementById('editTransactionModal');
    if (editTransactionModal) {
        editTransactionModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');

            // Fetch transaction details and populate form
            fetch('api/transactions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'get',
                    id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('editTransactionId').value = data.id;
                    document.getElementById('editCustomerSelect').value = data.customerId;
                    document.getElementById('editTransactionDate').value = data.date;
                    document.getElementById('editTransactionStatus').value = data.status;
                }
            });
        });
    }

    // Inventory Modal
    const editInventoryModal = document.getElementById('editInventoryModal');
    if (editInventoryModal) {
        editInventoryModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const stock = button.getAttribute('data-stock');

            document.getElementById('editInventoryId').value = id;
            document.getElementById('editInventoryName').value = name;
            document.getElementById('editInventoryStock').value = stock;
        });
    }
});
