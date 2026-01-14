document.getElementById("contactForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = this;
    const msg = document.getElementById("contactMsg");

    fetch("api/contact_api.php", {
        method: "POST",
        body: new FormData(form)
    })
    .then(res => res.json())
    .then(data => {
        msg.textContent = data.message;
        msg.style.color = data.status === "success" ? "green" : "red";

        if (data.status === "success") {
            form.reset();
        }
    })
    .catch(() => {
        msg.textContent = "Something went wrong.";
        msg.style.color = "red";
    });
});
