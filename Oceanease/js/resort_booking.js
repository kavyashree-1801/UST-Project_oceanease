document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById("bookingForm");
    const list = document.getElementById("bookingList");
    let editId = null;

    /* ================= TIME FORMAT ================= */
    function formatTime(time) {
        if (!time) return "-";
        let [h, m] = time.split(":");
        h = parseInt(h, 10);
        const ampm = h >= 12 ? "PM" : "AM";
        h = h % 12 || 12;
        return `${h}:${m} ${ampm}`;
    }

    /* ================= CHECK IF EXPIRED ================= */
    function isExpired(date, time) {
        if (!date || !time) return false;
        const [year, month, day] = date.split("-");
        const [hour, minute] = time.split(":");
        const bookingDT = new Date(year, month - 1, day, hour, minute, 0);
        return bookingDT < new Date();
    }

    /* ================= LOAD BOOKINGS ================= */
    async function loadBookings() {
        try {
            const res = await fetch("api/resort_booking_api.php", { credentials: "include" });
            const response = await res.json();

            list.innerHTML = "";

            if (response.status !== "success") {
                list.innerHTML = `<tr><td colspan="8">Failed to load bookings</td></tr>`;
                return;
            }

            const data = response.data || [];

            if (!data.length) {
                list.innerHTML = `<tr><td colspan="8">No bookings found</td></tr>`;
                return;
            }

            let i = 1;

            data.forEach(b => {
                const status = (b.status || "confirmed").toLowerCase();

                // Disable if cancelled, completed, or expired
                const expired = isExpired(b.booking_date, b.booking_time);
                const disable = ["cancelled", "completed"].includes(status) || expired;

                let statusClass = "status-confirmed";
                if (status === "completed" || expired) statusClass = "status-completed";
                else if (status === "cancelled") statusClass = "status-cancelled";

                list.innerHTML += `
                    <tr>
                        <td>${i++}</td>
                        <td>${b.resort_name}</td>
                        <td>${b.movie_name || "-"}</td>
                        <td>${b.booking_date}</td>
                        <td>${formatTime(b.booking_time)}</td>
                        <td>${b.guests}</td>
                        <td class="${statusClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</td>
                        <td class="action-wrapper">
                            <button class="action-btn edit-btn" onclick="editBooking(${b.id})" ${disable ? "disabled" : ""}>Edit Booking</button>
                            <button class="action-btn cancel-btn" onclick="cancelBooking(${b.id})" ${disable ? "disabled" : ""}>Cancel Booking</button>
                        </td>
                    </tr>
                `;
            });

        } catch (err) {
            console.error(err);
            list.innerHTML = `<tr><td colspan="8">Server error</td></tr>`;
        }
    }

    /* ================= EDIT BOOKING ================= */
    window.editBooking = function(id) {
        fetch("api/resort_booking_api.php", { credentials: "include" })
            .then(res => res.json())
            .then(response => {
                if (response.status !== "success") return;

                const booking = response.data.find(b => b.id == id);
                if (!booking) return;

                const status = (booking.status || "").toLowerCase();
                if (["cancelled", "completed"].includes(status) || isExpired(booking.booking_date, booking.booking_time)) return;

                form.resort_name.value  = booking.resort_name;
                form.movie_name.value   = booking.movie_name || "";
                form.booking_date.value = booking.booking_date;
                form.booking_time.value = booking.booking_time;
                form.guests.value       = booking.guests;

                editId = id;
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
    };

    /* ================= CANCEL BOOKING ================= */
    window.cancelBooking = function(id) {
        if (!confirm("Are you sure you want to cancel this booking?")) return;

        const fd = new FormData();
        fd.append("action", "status");
        fd.append("id", id);
        fd.append("status", "cancelled");

        fetch("api/resort_booking_api.php", { method: "POST", body: fd, credentials: "include" })
            .then(() => loadBookings());
    };

    /* ================= FORM SUBMIT ================= */
    form.addEventListener("submit", async e => {
        e.preventDefault();

        const formData = new FormData(form);

        if (editId) {
            formData.append("id", editId);
            formData.append("action", "update");
        } else {
            formData.append("action", "create");
        }

        const res = await fetch("api/resort_booking_api.php", {
            method: "POST",
            body: formData,
            credentials: "include"
        });

        const data = await res.json();

        if (data.status === "success") {
            alert(editId ? "Booking Updated" : "Booking Successful");
            form.reset();
            editId = null;
            loadBookings();
        } else {
            alert("Error: " + (data.error || "Failed to save booking"));
        }
    });

    /* ================= INITIAL LOAD ================= */
    loadBookings();

});
