document.addEventListener('DOMContentLoaded', () => {

    const tbody = document.querySelector('#stationeryBookingTable tbody');
    const form  = document.getElementById('stationeryForm');

    /* ================= STATUS HELPERS ================= */
    function normalizeStatus(status) {
        if (!status) return 'confirmed';
        status = status.toString().toLowerCase();
        if (status === 'completed') return 'completed';
        if (status === 'cancelled') return 'cancelled';
        return 'confirmed';
    }

    function statusClass(status) {
        return `status-${status}`;
    }

    function displayStatus(status) {
        return status.charAt(0).toUpperCase() + status.slice(1);
    }

    /* ================= FORMAT DATE & TIME ================= */
    function formatDateTime(dateStr, timeStr) {
        if (!dateStr || !timeStr) return { date: '-', time: '-' };
        const dtStr = `${dateStr} ${timeStr}`;
        const dt = new Date(dtStr);

        if (isNaN(dt.getTime())) return { date: '-', time: '-' };

        const date = dt.toLocaleDateString();

        let hours = dt.getHours();
        const minutes = dt.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;

        return { date, time: `${hours}:${minutes} ${ampm}` };
    }

    /* ================= CHECK IF EXPIRED (3 HOURS) ================= */
    function isExpired(dateStr, timeStr) {
        if (!dateStr || !timeStr) return false;
        const orderDT = new Date(`${dateStr} ${timeStr}`);
        return new Date() - orderDT >= 3 * 60 * 60 * 1000; // 3 hours
    }

    /* ================= LOAD ORDERS ================= */
    function loadOrders() {
        fetch('api/stationery_api.php?action=get_orders', { credentials: 'include' })
        .then(res => res.json())
        .then(response => {
            const data = response.data || [];
            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="8">No orders yet</td></tr>`;
                return;
            }

            data.forEach((order, index) => {
                const status = normalizeStatus(order.status);
                const dt = formatDateTime(order.order_date, order.order_time);

                const expired = isExpired(order.order_date, order.order_time);
                const finalStatus = expired && status === 'confirmed' ? 'completed' : status;
                const disableCancel = (finalStatus === 'completed' || finalStatus === 'cancelled');

                tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${order.ordered_by || '-'}</td>
                        <td>${order.item_name} × ${order.quantity}</td>
                        <td>₹${(order.price * order.quantity).toFixed(2)}</td>
                        <td class="${statusClass(finalStatus)}">${displayStatus(finalStatus)}</td>
                        <td>${dt.date}</td>
                        <td>${dt.time}</td>
                        <td>
                            ${
                                disableCancel
                                ? '-'
                                : `<button class="cancel-btn" data-id="${order.id}">Cancel</button>`
                            }
                        </td>
                    </tr>
                `;
            });

            // ADD CANCEL BUTTON EVENTS
            document.querySelectorAll('.cancel-btn').forEach(btn => {
                btn.addEventListener('click', () => cancelOrder(btn.dataset.id));
            });
        })
        .catch(err => {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="8">Error loading orders</td></tr>`;
        });
    }

    /* ================= PLACE ORDER ================= */
    form.addEventListener('submit', e => {
        e.preventDefault();

        const orders = [];
        form.querySelectorAll('.item-quantity').forEach(input => {
            const qty = parseInt(input.value, 10);
            if (qty > 0) {
                orders.push({
                    item_name: input.dataset.name,
                    quantity: qty,
                    price: parseFloat(input.dataset.price)
                });
            }
        });

        if (!orders.length) {
            alert('Select at least one item');
            return;
        }

        fetch('api/stationery_api.php?action=place_order', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ orders }),
            credentials: 'include'
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                form.reset();
                loadOrders();
            }
        });
    });

    /* ================= CANCEL ORDER ================= */
    function cancelOrder(id) {
        if (!confirm('Cancel this order?')) return;

        fetch(`api/stationery_api.php?action=cancel_order&id=${id}`, { credentials: 'include' })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            loadOrders();
        });
    }

    /* ================= INITIAL LOAD ================= */
    loadOrders();

});
