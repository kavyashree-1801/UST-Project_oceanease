document.addEventListener("DOMContentLoaded", () => {
    loadOrders();
});

/* ================= LOAD ORDERS ================= */
function loadOrders() {
    fetch("api/stationery_orders_api.php?action=list")
        .then(res => res.json())
        .then(data => {
            const table = document.getElementById("ordersTable");
            table.innerHTML = "";

            if (data.error) {
                table.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align:center;color:red;">
                            ${data.error}
                        </td>
                    </tr>`;
                return;
            }

            if (data.length === 0) {
                table.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align:center;">
                            No orders found
                        </td>
                    </tr>`;
                return;
            }

            data.forEach((order, index) => {

                // ✅ ENUM status handling
                const statusText = order.status.trim().toLowerCase(); 
                const statusClass = `status-${statusText}`;

                table.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${order.item_name}</td>
                        <td>${order.quantity}</td>
                        <td>${order.ordered_by}</td>
                        <td>₹${order.price}</td>
                        <td>${formatDate(order.order_date)}</td>
                        <td>
                            <span class="status ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td>
                            <button class="delete-btn"
                                onclick="deleteOrder(${order.id})">
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => console.error(err));
}

/* ================= DELETE ORDER ================= */
function deleteOrder(id) {
    if (!confirm("Are you sure you want to delete this order?")) return;

    const formData = new FormData();
    formData.append("id", id);

    fetch("api/stationery_orders_api.php?action=delete", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadOrders();
        } else {
            alert(data.error || "Delete failed");
        }
    })
    .catch(err => console.error(err));
}

/* ================= FORMAT DATE ================= */
function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString("en-IN", {
        day: "2-digit",
        month: "short",
        year: "numeric"
    });
}
