const fitnessForm = document.getElementById("fitnessForm");
const fitnessList = document.getElementById("fitnessList");
const bookingIdInput = document.getElementById("booking_id");

/* ================= TIME FORMAT ================= */
function formatTime(time) {
    if (!time) return "-";
    const [h, m] = time.split(":");
    let hour = parseInt(h, 10);
    const ampm = hour >= 12 ? "PM" : "AM";
    hour = hour % 12 || 12;
    return `${hour}:${m} ${ampm}`;
}

/* ================= LOAD BOOKINGS ================= */
async function loadBookings() {
    try {
        const res = await fetch("api/fitness_booking_api.php?action=get");
        const response = await res.json();

        fitnessList.innerHTML = "";

        if (response.status !== "success") {
            fitnessList.innerHTML =
                "<tr><td colspan='6'>Failed to load bookings</td></tr>";
            return;
        }

        const bookings = response.data || [];

        if (!bookings.length) {
            fitnessList.innerHTML =
                "<tr><td colspan='6'>No bookings found</td></tr>";
            return;
        }

        bookings.forEach((b, index) => {

            const status = (b.status || "confirmed").toLowerCase();
            let statusClass = "status-confirmed";
            let disableActions = false;

            if (status === "completed") {
                statusClass = "status-completed";
                disableActions = true;
            } else if (status === "cancelled") {
                statusClass = "status-cancelled";
                disableActions = true;
            }

            fitnessList.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${b.workout_type}</td>
                    <td>${b.booking_date}</td>
                    <td>${formatTime(b.booking_time)}</td>
                    <td class="${statusClass}">
                        ${status.charAt(0).toUpperCase() + status.slice(1)}
                    </td>
                    <td>
                        <div class="action-wrapper">
                            <button class="action-btn edit-btn"
                                onclick="editBooking(${b.id})"
                                ${disableActions ? "disabled" : ""}>
                                Edit Booking
                            </button>
                            <button class="action-btn cancel-btn"
                                onclick="cancelBooking(${b.id})"
                                ${disableActions ? "disabled" : ""}>
                                Cancel Booking
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

    } catch (err) {
        console.error(err);
        fitnessList.innerHTML =
            "<tr><td colspan='6'>Server error</td></tr>";
    }
}

/* ================= SUBMIT (BOOK / EDIT) ================= */
fitnessForm.addEventListener("submit", async e => {
    e.preventDefault();

    const formData = new FormData(fitnessForm);
    formData.append("action", bookingIdInput.value ? "edit" : "book");

    try {
        const res = await fetch("api/fitness_booking_api.php", {
            method: "POST",
            body: formData
        });

        const response = await res.json();

        if (response.status === "success") {
            fitnessForm.reset();
            bookingIdInput.value = "";
            loadBookings();
        } else {
            alert(response.error || "Something went wrong!");
        }

    } catch (err) {
        console.error(err);
        alert("Network error");
    }
});

/* ================= EDIT ================= */
function editBooking(id) {
    fetch(`api/fitness_booking_api.php?action=get&id=${id}`)
        .then(res => res.json())
        .then(response => {
            if (response.status !== "success") return;

            const b = response.data;
            bookingIdInput.value = b.id;
            fitnessForm.workout_type.value = b.workout_type;
            fitnessForm.booking_date.value = b.booking_date;
            fitnessForm.booking_time.value = b.booking_time;
        });
}

/* ================= CANCEL ================= */
function cancelBooking(id) {
    if (!confirm("Are you sure to cancel this booking?")) return;

    fetch(`api/fitness_booking_api.php?action=cancel&id=${id}`)
        .then(res => res.json())
        .then(response => {
            if (response.status === "success") {
                loadBookings();
            } else {
                alert(response.error || "Could not cancel booking");
            }
        });
}

/* ================= INITIAL LOAD ================= */
loadBookings();
