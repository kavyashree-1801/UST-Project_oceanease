document.addEventListener('DOMContentLoaded', loadOrders);

function loadOrders() {
    fetch('api/catering_orders_api.php?action=get_orders')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#ordersTable tbody');
            tbody.innerHTML = '';

            data.forEach(order => {
                tbody.innerHTML += `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.order_date}</td>
                        <td>${order.item_name}</td>
                        <td>${order.total_amount}</td>
                        <td>${order.status}</td>
                        <td>
                            <select onchange="updateStatus(${order.id}, this.value)">
                                ${statusOptions(order.status)}
                            </select>
                        </td>
                    </tr>
                `;
            });
        });
}

function statusOptions(current) {
    const statuses = ['Pending', 'Preparing', 'Completed', 'Cancelled'];
    return statuses.map(s =>
        `<option value="${s}" ${s === current ? 'selected' : ''}>${s}</option>`
    ).join('');
}

function updateStatus(id, status) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);

    fetch('api/catering_orders_api.php?action=update_status', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(() => loadOrders());
}
