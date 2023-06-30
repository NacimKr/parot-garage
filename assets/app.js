/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

document.addEventListener('DOMContentLoaded', function() {
    const btnActiveCars = document.querySelectorAll('#isActive');
    const formFilter = document.querySelector('#filters');
    const allInputs = document.querySelectorAll('#input-search');

    btnActiveCars.forEach(btn => {
      let valueOfBtn = btn.getAttribute("data-isactive")

      btn.addEventListener('click', (e)=>{
        e.preventDefault();
        const getValueBtn = btn.getAttribute('data-index');

        const url = new URL(window.location.href)
        
        fetch(`${url}change/visibility/${getValueBtn}`,{
          method:"POST",
          //body: JSON.stringify({getValueBtnBoolean}),
          headers: {
            "X-Requested-With":"XMLHttpRequest",
            'Content-Type': 'application/json'
          }
        })
        .then(function(response) {
          // Gérer la réponse du serveur (si nécessaire)
          return response.json()
        })
        .then(function(data) {
          // Gérer la réponse du serveur (si nécessaire)
          console.log(data.cars)
          btn.setAttribute("data-isactive", data.cars.isActive)
          btn.setAttribute("class", data.cars.isActive ? 'btn btn-primary' : "btn btn-danger")
          btn.innerText = data.cars.isActive ? "Active" : "Disabled"
        })
        .catch(function(error) {
          // Gérer les erreurs (si nécessaire)
        });
      })
    })

  
  formFilter.addEventListener('submit', (e) => {
    e.preventDefault();

    allInputs.forEach(input => {
      //Je recupère les données du formulaire
      const form = new FormData(formFilter);

      //Je créer ma "queryString"
      const params = new URLSearchParams();

      form.forEach((value, key) => {
        console.log(value)
        params.append(key, value);
      });
      
      //On recupère l'url active ( sur lequel on est)
      const url = new URL(window.location.href);

      //On lance la requête ajax
      fetch(`${url.pathname}?${params.toString()}&ajax=1`, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(function(response) {
        // Gérer la réponse du serveur (si nécessaire)
        return response.json()
      })
      .then(data => {
        const contentCars = document.getElementById('content-cars');
        contentCars.innerHTML = data.content
      })
      .catch(function(error) {
        // Gérer les erreurs (si nécessaire)
        alert(error.message)
      })
    });
  });
});

    // fetch('/filter/search', {
    //     method: 'POST',
    //     body: JSON.stringify({ kilometrage: kilometrage.value }),
    //     headers: {
    //       'Content-Type': 'application/json'
    //     }
    // })
    // .then(function(response) {
    //   // Gérer la réponse du serveur (si nécessaire)
    //   console.log(response)
    // })
    // .catch(function(error) {
    //   // Gérer les erreurs (si nécessaire)
    // })