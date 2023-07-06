/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

document.addEventListener('DOMContentLoaded', function() {
    const btnActiveCars = document.querySelectorAll('#isActive');

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
          btn.setAttribute("class", data.cars.isActive ? 'btn btn-primary btn-sm' : "btn btn-danger btn-sm")
          btn.innerText = data.cars.isActive ? "Active" : "Disabled"
        })
        .catch(function(error) {
          // Gérer les erreurs (si nécessaire)
          console.log(error.message);
        });
      })
    })



    //Systeme de recherche par filtres
    const formFilter = document.querySelector('#filters');
    const allInputs = document.querySelectorAll('#filters input');

    formFilter.addEventListener('submit', (e) => {
      e.preventDefault();

      // allInputs.forEach(input => {
        // input.addEventListener('input', () => {
          //On recupere les données du formulaire
          const formData = new FormData(formFilter);
          
          //On créer l'url pour faire un fetch ensuite
          const params = new URLSearchParams();
  
          formData.forEach((value, key) => {
            params.append(key, value)
          });
  
          const Url = new URL(window.location.href);
  
          fetch(Url.pathname+"?"+params.toString()+"&ajax=1", {
            headers:{
              'Content-Type': 'application/json'
            }
          })
          .then(response=> response.json())
          .then((data) => {
            console.log(data)
            document.getElementById('content-cars').innerHTML = data.content
          })
          .catch(err => console.error(err.message))
        // });
      // });
    })


});