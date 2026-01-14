const salonForm = document.getElementById("salonForm");
const salonList = document.getElementById("salonList").querySelector("tbody");
const bookingIdInput = document.getElementById("booking_id");

function formatTimeToAMPM(time){
    if(!time) return "-";
    let [h,m] = time.split(":");
    h = parseInt(h,10);
    const ampm = h>=12?"PM":"AM";
    h = h%12 || 12;
    return `${h}:${m} ${ampm}`;
}

async function loadBookings(){
    try{
        const res = await fetch("api/salon_booking_api.php?action=get",{credentials:"include"});
        const response = await res.json();
        salonList.innerHTML="";

        if(response.status !== "success"){
            salonList.innerHTML="<tr><td colspan='6'>Failed to load bookings</td></tr>";
            return;
        }

        const bookings = response.data || [];
        if(!bookings.length){
            salonList.innerHTML="<tr><td colspan='6'>No bookings found</td></tr>";
            return;
        }

        bookings.forEach((b,index)=>{
            const status = (b.status || "confirmed").toLowerCase();
            let disableActions = status==="completed" || status==="cancelled";
            let statusClass = "status-confirmed";
            if(status==="completed") statusClass="status-completed";
            else if(status==="cancelled") statusClass="status-cancelled";

            salonList.innerHTML+=`
                <tr>
                    <td>${index+1}</td>
                    <td>${b.service_name}</td>
                    <td>${b.booking_date}</td>
                    <td>${formatTimeToAMPM(b.booking_time)}</td>
                    <td class="${statusClass}">${status.charAt(0).toUpperCase()+status.slice(1)}</td>
                    <td>
                        <div class="action-wrapper">
                            <button class="action-btn edit-btn"
                                onclick="editBooking(${b.id},'${b.service_name}','${b.booking_date}','${b.booking_time}')"
                                ${disableActions?"disabled":""}>
                                Edit Booking
                            </button>
                            <button class="action-btn cancel-btn"
                                onclick="cancelBooking(${b.id})"
                                ${disableActions?"disabled":""}>
                                Cancel Booking
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

    }catch(err){
        console.error(err);
        salonList.innerHTML="<tr><td colspan='6'>Server error</td></tr>";
    }
}

function editBooking(id,service,date,time){
    bookingIdInput.value=id;
    salonForm.service_name.value=service;
    salonForm.booking_date.value=date;
    salonForm.booking_time.value=time;
}

salonForm.addEventListener("submit",async e=>{
    e.preventDefault();
    const formData = new FormData(salonForm);
    formData.append("action","add");

    try{
        const res = await fetch("api/salon_booking_api.php",{method:"POST",body:formData,credentials:"include"});
        const response = await res.json();
        if(response.status==="success"){
            salonForm.reset();
            bookingIdInput.value="";
            loadBookings();
        } else {
            alert(response.error||"Something went wrong!");
        }
    }catch(err){
        console.error(err);
        alert("Network error");
    }
});

function cancelBooking(id){
    if(!confirm("Are you sure you want to cancel this booking?")) return;
    const formData=new FormData();
    formData.append("action","cancel");
    formData.append("booking_id",id);

    fetch("api/salon_booking_api.php",{method:"POST",body:formData,credentials:"include"})
    .then(res=>res.json())
    .then(response=>{
        if(response.status==="success") loadBookings();
        else alert(response.error||"Could not cancel booking");
    })
    .catch(()=>alert("Network error"));
}

/* ================= INITIAL LOAD ================= */
loadBookings();
