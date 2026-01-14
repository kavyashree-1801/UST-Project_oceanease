document.addEventListener("DOMContentLoaded", () => {
    const partyForm = document.getElementById("partyForm");
    const partyList = document.getElementById("partyList");
    let editId = null;

    // ------------------ TIME FORMAT ------------------
    function formatTime(time) {
        if (!time) return "-";
        let [h, m] = time.split(":");
        h = parseInt(h, 10);
        const ampm = h >= 12 ? "PM" : "AM";
        h = h % 12 || 12;
        return `${h}:${m} ${ampm}`;
    }

    // ------------------ STATUS HELPERS ------------------
    function normalizeStatus(status) {
        if (!status) return "confirmed";
        status = status.toLowerCase();
        if (status === "completed") return "completed";
        if (status === "cancelled") return "cancelled";
        return "confirmed";
    }

    function displayStatus(status) {
        return status.charAt(0).toUpperCase() + status.slice(1);
    }

    // ------------------ LOAD BOOKINGS ------------------
    async function loadBookings() {
        try {
            const res = await fetch("api/party_booking_api.php", { credentials: "include" });
            const result = await res.json();
            const data = Array.isArray(result.data) ? result.data : [];

            partyList.innerHTML = "";

            if (!data.length) {
                const tr = document.createElement("tr");
                tr.innerHTML = "<td colspan='8'>No bookings found</td>";
                partyList.appendChild(tr);
                return;
            }

            const now = new Date();

            data.forEach((b, i) => {
                const safeStatus = normalizeStatus(b.status);

                let time = b.booking_time || "00:00";
                if (time.split(":").length === 2) time += ":00";

                const [y, m, d] = (b.booking_date || "1970-01-01").split("-");
                const [hh, mm, ss] = time.split(":");

                const bookingDT = new Date(
                    parseInt(y), parseInt(m) - 1, parseInt(d),
                    parseInt(hh), parseInt(mm), parseInt(ss)
                );

                let finalStatus = safeStatus;
                if (finalStatus === "confirmed" && bookingDT < now) finalStatus = "completed";

                const disable = finalStatus === "completed" || finalStatus === "cancelled";

                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${i + 1}</td>
                    <td>${b.hall_name || "-"}</td>
                    <td>${b.event_type || "-"}</td>
                    <td>${b.booking_date || "-"}</td>
                    <td>${formatTime(b.booking_time)}</td>
                    <td>${b.guests || 0}</td>
                    <td class="status-${finalStatus}">${displayStatus(finalStatus)}</td>
                    <td class="action-wrapper">
                        <button class="action-btn edit-btn" data-id="${b.id}" ${disable ? "disabled" : ""}>Edit Booking</button>
                        <button class="action-btn cancel-btn" data-id="${b.id}" ${disable ? "disabled" : ""}>Cancel Booking</button>
                    </td>
                `;
                partyList.appendChild(tr);
            });

            // Bind click events after rendering
            bindActions();
        } catch (err) {
            console.error("Load failed:", err);
            const tr = document.createElement("tr");
            tr.innerHTML = "<td colspan='8'>Failed to load bookings</td>";
            partyList.appendChild(tr);
        }
    }

    // ------------------ BIND BUTTONS ------------------
    function bindActions() {
        // Edit buttons
        document.querySelectorAll(".edit-btn").forEach(btn => {
            btn.onclick = async () => {
                const id = btn.dataset.id;
                const res = await fetch(`api/party_booking_api.php?id=${id}`, { credentials: "include" });
                const response = await res.json();

                if (!response || response.status !== "success") {
                    alert("Booking not found");
                    return;
                }

                const b = response.data;

                editId = b.id;
                partyForm.hall_name.value = b.hall_name || "";
                partyForm.event_type.value = b.event_type || "";
                partyForm.booking_date.value = b.booking_date || "";
                partyForm.booking_time.value = b.booking_time || "";
                partyForm.guests.value = b.guests || 10;

                partyForm.querySelector("button").textContent = "Update Booking";
                window.scrollTo({ top: 0, behavior: "smooth" });
            };
        });

        // Cancel buttons
        document.querySelectorAll(".cancel-btn").forEach(btn => {
            btn.onclick = async () => {
                if (!confirm("Cancel this booking?")) return;

                const formData = new URLSearchParams();
                formData.append("action", "cancel");
                formData.append("id", btn.dataset.id);

                const res = await fetch("api/party_booking_api.php", {
                    method: "POST",
                    body: formData,
                    credentials: "include"
                });

                const data = await res.json();

                if (data.status === "success") {
                    loadBookings();
                } else {
                    alert(data.error || "Failed to cancel booking");
                }
            };
        });
    }

    // ------------------ FORM SUBMIT ------------------
    partyForm.onsubmit = async e => {
        e.preventDefault();

        const formData = new FormData(partyForm);

        if (editId) {
            formData.append("action", "update");
            formData.append("id", editId);
        } else {
            formData.append("action", "book");
        }

        const res = await fetch("api/party_booking_api.php", {
            method: "POST",
            body: formData,
            credentials: "include"
        });

        const data = await res.json();

        if (data.status === "success") {
            alert(editId ? "Booking updated!" : "Booking successful!");
            partyForm.reset();
            editId = null;
            partyForm.querySelector("button").textContent = "Book Party Hall";
            loadBookings();
        } else {
            alert(data.error || "Something went wrong!");
        }
    };

    // ------------------ INITIAL LOAD ------------------
    loadBookings();
});
