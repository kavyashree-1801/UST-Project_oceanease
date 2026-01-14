document.addEventListener("DOMContentLoaded", () => {
    fetch("api/manager_fitness_bookings.php")
        .then(res => {
            if (!res.ok) throw new Error("HTTP Error: " + res.status);
            return res.json();
        })
        .then(response => {

            // Safely update manager email if element exists
            const managerEl = document.getElementById("managerEmail");
            if (managerEl && response.manager_email) {
                managerEl.textContent = "Hello, " + response.manager_email;
            }

            const tbody = document.getElementById("fitnessTable");
            tbody.innerHTML = "";

            if (!response.data || response.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;">No fitness bookings found</td>
                    </tr>
                `;
                return;
            }

            response.data.forEach(row => {
                const tr = document.createElement("tr");

                // Status classes
                let statusClass = "";
                switch (row.status.toLowerCase()) {
                    case "completed": statusClass = "status-completed"; break;
                    case "cancelled": statusClass = "status-cancelled"; break;
                    default: statusClass = "status-confirmed";
                }

                tr.innerHTML = `
                    <td>${row.name}</td>
                    <td>${row.workout_type}</td>
                    <td>${row.booking_date}</td>
                    <td>${row.booking_time}</td>
                    <td class="${statusClass}">${row.status.charAt(0).toUpperCase() + row.status.slice(1)}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => {
            console.error("Fetch error:", err);
            const tbody = document.getElementById("fitnessTable");
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Failed to load fitness bookings
                    </td>
                </tr>
            `;
        });
});
