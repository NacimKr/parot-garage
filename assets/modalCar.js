const modal = document.getElementById('modal');

modal.addEventListener('click', () => {

    const url = new URL(window.location.href);

    fetch(url + "/" + modal.getAttribute('data-index'), {
        method: "POST",
        headers: {
            "content-type" : "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        console.log(data)
    })
});