document.getElementById('feedbackForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch('api/feedback_api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const msg = document.getElementById('feedbackMsg');
        if(data.success){
            msg.style.color = 'green';
            msg.innerText = data.message;
            document.getElementById('feedbackForm').reset();
        } else {
            msg.style.color = 'red';
            msg.innerText = data.message;
        }
    })
    .catch(error => {
        document.getElementById('feedbackMsg').style.color = 'red';
        document.getElementById('feedbackMsg').innerText = 'An error occurred. Please try again.';
    });
});
