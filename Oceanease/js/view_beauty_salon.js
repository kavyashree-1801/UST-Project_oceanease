document.addEventListener("DOMContentLoaded", () => {
    fetch("api/manager_beauty_salon_bookings.php")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not OK");
            return res.json();
        })
        .then(response => {
            if (response.error) {
                alert(response.error);
                return;
            }

            // Show manager email
            const emailSpan = document.getElementById("managerEmail");
            if (emailSpan && response.manager_email) {
                emailSpan.textContent = "Hello, " + response.manager_email;
            }

            const tbody = document.getElementById("salonTable");
            tbody.innerHTML = "";

            // No bookings
            if (!response.data || response.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" style="text-align:center;">No beauty salon bookings found</td>
                    </tr>
                `;
                return;
            }

            // Populate rows with status colors
            response.data.forEach(row => {
                const tr = document.createElement("tr");

                let statusClass = "";
                switch (row.status.toLowerCase()) {
                    case "completed": statusClass = "status-completed"; break;
                    case "cancelled": statusClass = "status-cancelled"; break;
                    default: statusClass = "status-confirmed";
                }

                tr.innerHTML = `
                    <td>${row.name}</td>
                    <td>${row.service_name}</td>
                    <td>${row.booking_date}</td>
                    <td class="${statusClass}">${row.status.charAt(0).toUpperCase() + row.status.slice(1)}</td>
                `;

                tbody.appendChild(tr);
            });
        })
        .catch(err => {
            console.error("Fetch error:", err);
            const tbody = document.getElementById("salonTable");
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" style="text-align:center;">Failed to load beauty salon bookings</td>
                </tr>
            `;
        });
});
