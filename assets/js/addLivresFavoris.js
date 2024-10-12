document.addEventListener("DOMContentLoaded", function() {
    var favoriButtons = document.querySelectorAll(".bt-favori");

    if (favoriButtons.length > 0) {
        // On boucle sur tous les boutons et on leur ajoute un écouteur d'événement "click"
        favoriButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault();
                event.stopPropagation();
                console.log(this, button);
                var livreId = this.getAttribute("data-livreid"); // Récupère l'attribut "data-livreid"
                
                // Vérifie si l'icône est déjà un favori (fa-star)
                var isFavori = this.classList.contains("fa-star");
                var url = isFavori ? 'mon-compte/deletefavori' : 'mon-compte/addfavori'; // Définit l'URL en fonction de l'état actuel

                // Utilisation de Fetch API pour envoyer la requête AJAX
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + encodeURIComponent(livreId)
                })
                .then(function(response) {
                    if (response.ok) {
                        if (isFavori) {
                            // Si c'était un favori, on le retire
                            button.classList.remove("fa-star");
                            button.classList.add("fa-star-o");
                        } else {
                            // Sinon, on l'ajoute comme favori
                            button.classList.remove("fa-star-o");
                            button.classList.add("fa-star");
                        }
                    } else {
                        return Promise.reject(response);
                    }
                })
                .catch(function(error) {
                    console.error("Ajax error:", error);
                });
            });
        });
    }
});
