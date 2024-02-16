const searchInput = document.getElementById("search-navbar");
const searchResultsContainer = document.getElementById("search-results");
const RUTA_URL = "http://localhost:8080/TaskForce/aplicacion";
const RUTA_API = "http://localhost:8080/TaskForce/api/";
const token = getCookie('token');

searchInput.addEventListener("input", function () {

    const searchTerm = searchInput.value;

    if (searchTerm.trim() === "") {
        searchResultsContainer.innerHTML = "";
        searchResultsContainer.classList.add("d-none");
        return;
    }

    fetch(`${RUTA_API}usuario/busqueda?correo=${encodeURIComponent(searchTerm)}`, {
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    }
    )
        .then((response) => response.json())
        .then((data) => {
            searchResultsContainer.innerHTML = "";
            data.forEach((result) => {
                const card = document.createElement("div");
                card.classList.add(
                    "card",
                    "border",
                    "rounded-md",
                    "cursor-pointer",
                    "d-flex",
                    "flex-row",
                    "align-items-center",
                    "transition",
                    "transform",
                    "hover-shadow-md"
                );
                if (result.ruta_foto_perfil == null) {
                    result.ruta_foto_perfil = 'public/img/usr/blank_user.webp';
                }
                result.ruta = `${RUTA_URL}/${result.ruta_foto_perfil}`;
                card.innerHTML = `<img src = "${result.ruta}" class= "img-thumbnail img-busqueda" >
                 <div>
        <h5 class="f1 fw-bold">${result.correo}</h2>
        <h7 class="f075 fw-semibold text-gray-600">${result.username}</h3>
        <p class="text-sm text-gray-500">${result.nombre}</p>
    </div>
        `;

                card.addEventListener("click", function () {
                    searchInput.value = result.correo;
                    searchResultsContainer.classList.add("d-none");
                });



                searchResultsContainer.appendChild(card);
            });
            searchResultsContainer.classList.remove("d-none");
        })
        .catch((error) => {
            console.error("Error al buscar películas:", error);
        });
});

document.addEventListener("click", function (event) {
    if (!searchResultsContainer.contains(event.target)) {
        searchResultsContainer.classList.add("d-none");
    }
});