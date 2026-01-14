// supervisor.js

// Example: Filter table rows by status
document.addEventListener('DOMContentLoaded', function() {
    const filterSelect = document.getElementById('statusFilter');
    if (!filterSelect) return;

    filterSelect.addEventListener('change', function() {
        const filterValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const status = row.querySelector('.status').textContent.toLowerCase();
            if (filterValue === 'all' || status === filterValue) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

// Example: Optional future feature - confirm status change
function confirmStatusChange(itemName) {
    return confirm(`Are you sure you want to change the status for "${itemName}"?`);
}
