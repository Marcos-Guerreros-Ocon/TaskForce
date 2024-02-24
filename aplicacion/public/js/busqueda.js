const searchInput = document.getElementById("search-navbar");
const searchResultsContainer = document.getElementById("search-results");

const token = getCookie('token');
if (searchInput != null && searchResultsContainer != null) {
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
}
const searchInput2 = document.getElementById("search-navbar-2");
const searchResultsContainer2 = document.getElementById("search-results-2");
if (searchInput2 != null && searchResultsContainer2 != null) {
    searchInput2.addEventListener("input", function () {

        const searchTerm = searchInput2.value;

        if (searchTerm.trim() === "") {
            searchResultsContainer2.innerHTML = "";
            searchResultsContainer2.classList.add("d-none");
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
                searchResultsContainer2.innerHTML = "";
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
                        searchInput2.value = result.correo;
                        searchResultsContainer2.classList.add("d-none");
                    });



                    searchResultsContainer2.appendChild(card);
                });
                searchResultsContainer2.classList.remove("d-none");
            })
            .catch((error) => {
                console.error("Error al buscar películas:", error);
            });
    });

    document.addEventListener("click", function (event) {

        if (!searchResultsContainer2.contains(event.target)) {
            searchResultsContainer2.classList.add("d-none");
        }


    });
}