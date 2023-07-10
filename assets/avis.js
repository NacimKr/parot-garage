const btnAvis = document.querySelectorAll('#isActive-avis')
console.log(btnAvis);

btnAvis.forEach(btn => {
    let valueOfBtn = btn.getAttribute("data-isactive")
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        fetch(`/employee/manage/avis/${btn.getAttribute('data-index')}`,{
            method:"POST",
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.setAttribute('data-isactive', data.avis.isactive)
            btn.setAttribute('class', data.avis.isactive ? 'btn btn-primary' : 'btn btn-danger')
            btn.innerHTML = data.avis.isactive ? "Active" : "Disabled"
        })
        .catch(err => console.log(err))
    })
})