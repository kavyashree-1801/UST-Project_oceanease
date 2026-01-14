document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".status-action").forEach(select => {

        select.addEventListener("change", () => {

            const orderId = select.dataset.id;
            const newStatus = select.value;

            if (newStatus !== "completed") return;

            if (!confirm("Mark this order as completed?")) {
                select.value = "pending";
                return;
            }

            const formData = new FormData();
            formData.append("ajax", "1");
            formData.append("order_id", orderId);

            fetch("view_catering_orders.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    const statusBadge = document.getElementById("status-" + orderId);
                    statusBadge.textContent = "Completed";
                    statusBadge.className = "status completed";
                    select.parentElement.innerHTML = "—";
                } else {
                    alert("Failed to update order");
                    select.value = "pending";
                }

            })
            .catch(() => {
                alert("Network error");
                select.value = "pending";
            });
        });

    });

});
