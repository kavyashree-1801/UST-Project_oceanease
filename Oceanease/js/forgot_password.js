document.getElementById("forgotForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const result = document.getElementById("result");
    result.innerHTML = "Processing...";

    if(!email){
        result.innerHTML = '<span class="error">Please enter your email.</span>';
        return;
    }

    try{
        // FormData for POST
        const formData = new FormData();
        formData.append("email", email);

        // Correct fetch path from HTML page
        const res = await fetch("api/forgot_password_api.php", {
            method: "POST",
            body: formData
        });

        const data = await res.json();

        if(data.status === "success"){
            result.innerHTML = `<p>Reset link (expires at ${data.expires}):</p>
            <a href="${data.link}" target="_blank">${data.link}</a>`;
        } else {
            result.innerHTML = `<span class="error">${data.message}</span>`;
        }
    } catch(err){
        result.innerHTML = `<span class="error">Server error. Try again later.</span>`;
        console.error(err);
    }
});
