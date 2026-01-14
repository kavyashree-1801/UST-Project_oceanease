const API = '/Oceanease/api/catering_api.php';

document.addEventListener("DOMContentLoaded", () => {
    loadMenu();
    loadOrders();

    const orderBtn = document.getElementById("orderNowBtn");
    if (orderBtn) orderBtn.addEventListener("click", placeOrder);
});

/* ================= TIME FORMATTER ================= */
function formatTimeToAMPM(datetime) {
    if (!datetime) return '';
    const dateObj = new Date(datetime);
    if (isNaN(dateObj)) return '';

    let hours = dateObj.getHours();
    const minutes = dateObj.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // 0 => 12
    return `${hours}:${minutes} ${ampm}`;
}

/* ================= DATE FORMATTER ================= */
function formatDate(datetime) {
    if (!datetime) return '';
    const dateObj = new Date(datetime);
    if (isNaN(dateObj)) return '';
    const year = dateObj.getFullYear();
    const month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
    const day = dateObj.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
}

/* ================= LOAD MENU ================= */
async function loadMenu() {
    try {
        const res = await fetch(`${API}?action=get_menu`, { credentials: 'include' });
        const data = await res.json();
        if (data.status !== 'success') return;

        const grid = document.getElementById("menuGrid");
        grid.innerHTML = '';

        data.data.forEach(item => {
            grid.innerHTML += `
                <div class="card">
                    <img src="${item.image}" alt="${item.item_name}">
                    <div class="card-content">
                        <h3>${item.item_name}</h3>
                        <p>${item.description ?? ''}</p>
                        <strong>₹${item.price}</strong>
                        <div class="quantity-container">
                            Qty:
                            <input type="number" min="0" value="0"
                                data-name="${item.item_name}"
                                data-price="${item.price}">
                        </div>
                    </div>
                </div>
            `;
        });

    } catch (err) {
        console.error("Error loading menu:", err);
    }
}

/* ================= PLACE ORDER ================= */
async function placeOrder() {
    const items = [];
    document.querySelectorAll('#menuGrid input').forEach(i => {
        const qty = parseInt(i.value);
        if (qty > 0) {
            items.push({
                name: i.dataset.name,
                price: parseFloat(i.dataset.price),
                qty
            });
        }
    });

    if (!items.length) {
        alert("Select at least one item");
        return;
    }

    try {
        const res = await fetch(`${API}?action=place_order`, {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(items)
        });

        const data = await res.json();
        alert(data.message);

        if (data.status === 'success') {
            loadOrders();
            document.querySelectorAll('#menuGrid input').forEach(i => i.value = 0);
        }

    } catch (err) {
        console.error("Order failed:", err);
    }
}

/* ================= LOAD ORDERS ================= */
async function loadOrders() {
    try {
        const res = await fetch(`${API}?action=get_orders`, { credentials: 'include' });
        const data = await res.json();
        if (data.status !== 'success') return;

        const tbody = document.querySelector('#bookingTable tbody');
        tbody.innerHTML = '';

        if (!data.data.length) {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center">No orders found</td></tr>`;
            return;
        }

        data.data.forEach((b, i) => {
            let statusClass = 'status-pending';
            const s = b.status.toLowerCase();

            if (s === 'confirmed') statusClass = 'status-confirmed';
            if (s === 'completed') statusClass = 'status-completed';
            if (s === 'cancelled' || s === 'canceled') statusClass = 'status-cancelled';

            const formattedDate = formatDate(b.order_date);
            const formattedTime = formatTimeToAMPM(b.order_date);

            tbody.innerHTML += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${b.username}</td>
                    <td>${b.item_names}</td>
                    <td>₹${parseFloat(b.total_amount).toFixed(2)}</td>
                    <td class="${statusClass}">${b.status}</td>
                    <td>${formattedDate}</td>
                    <td>${formattedTime}</td>
                    <td>
                        ${
                            s === 'pending'
                            ? `<button class="cancel-btn" onclick="cancelOrder(${b.id})">Cancel</button>`
                            : '-'
                        }
                    </td>
                </tr>
            `;
        });

    } catch (err) {
        console.error("Error loading orders:", err);
    }
}

/* ================= CANCEL ORDER ================= */
async function cancelOrder(id) {
    if (!confirm("Cancel this order?")) return;

    try {
        const res = await fetch(`${API}?action=cancel_order&id=${id}`, { credentials: 'include' });
        const data = await res.json();
        alert(data.message);
        loadOrders();
    } catch (err) {
        console.error("Cancel failed:", err);
    }
}
