document.getElementById("resetForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const reset_id = document.getElementById("reset_id").value.trim();
    const new_pass = document.getElementById("new_password").value.trim();
    const confirm_pass = document.getElementById("confirm_password").value.trim();
    const result = document.getElementById("result");

    if(!reset_id){
        result.innerHTML = '<span class="error">Invalid reset link.</span>';
        return;
    }

    if(new_pass.length < 6){
        result.innerHTML = '<span class="error">Password must be at least 6 characters.</span>';
        return;
    }

    if(new_pass !== confirm_pass){
        result.innerHTML = '<span class="error">Passwords do not match.</span>';
        return;
    }

    result.innerHTML = 'Processing...';

    try{
        const formData = new FormData();
        formData.append("reset_id", reset_id);
        formData.append("new_password", new_pass);

        const res = await fetch("api/reset_password_api.php", {
            method: "POST",
            body: formData
        });

        const data = await res.json();

        if(data.status === "success"){
            result.innerHTML = `<span class="success">${data.message}</span>`;

            // Redirect to login after 3 seconds
            setTimeout(() => {
                window.location.href = "auth.php";
            }, 3000);
        } else {
            result.innerHTML = `<span class="error">${data.message}</span>`;
        }
    } catch(err){
        result.innerHTML = '<span class="error">Server error. Try again later.</span>';
        console.error(err);
    }
});
function togglePassword(fieldId, icon){
    const field = document.getElementById(fieldId);
    if(field.type === "password"){
        field.type = "text";
        // Change SVG to eye-slash
        icon.innerHTML = '<path d="M12 5c-5 0-9.27 3.11-11 7 1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7zm0 12c-2.76 0-5-2.24-5-5 0-.88.24-1.7.65-2.41l6.76 6.76c-.71.41-1.53.65-2.41.65zm3.35-1.59L8.59 8.65C9.3 8.24 10.12 8 11 8c2.76 0 5 2.24 5 5 0 .88-.24 1.7-.65 2.41z"/>';
    } else {
        field.type = "password";
        // Change back to eye
        icon.innerHTML = '<path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>';
    }
}
