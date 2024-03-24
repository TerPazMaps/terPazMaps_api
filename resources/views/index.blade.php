<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerPazMaps</title>
    <!-- Adicione o link para o arquivo Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    {{-- <link href="{{ asset('css/headers.css') }}" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
            position: relative;
            /* Adiciona uma posição relativa para permitir o posicionamento absoluto dos elementos filhos */
        }

        header {
            height: 45px;
            /* Ajuste a altura conforme necessário */
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            /* Garante que o cabeçalho esteja acima do mapa */
        }

        .navbar-toggler {
            margin-left: 5px;
            /* Adiciona uma pequena margem à esquerda */
            padding: 5px;
            /* Adiciona um pouco de preenchimento ao botão */
        }

        #regionContainer {
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1001;
        }

        #map {
            height: 100%;
            width: 100%;
            position: absolute;
            /* Posiciona o mapa absolutamente em relação ao corpo da página */
            top: 0;
            left: 0;
            z-index: 0;
            /* Garante que o mapa esteja no fundo */
            box-sizing: border-box;
            /* Garante que padding e border não afetem as dimensões totais do elemento */
        }

        .custom-offcanvas {
            width: 50%;
        }
    </style>
</head>

<body>

    <header class="p-2 mb-3 border-bottom bg-primary d-flex align-items-center">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                <use xlink:href="#bootstrap" />
            </svg>
        </a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <div id="regionContainer" class=" bg-white">
        <select id="regionId" name="regionId">
            <option value="0" selected>Selecione uma região</option>
        </select>
        <br>
    </div>

    <main style="padding-top: 45px;"> <!-- 45px é a altura do seu cabeçalho -->
        <div id="map"></div>
    </main>

    <div class="offcanvas offcanvas-start bg-primary text-white " tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">TerPaz Maps</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <hr>
        <ul class="nav nav-pills flex-column mb-auto ">
            <li class="nav-link d-flex align-items-center">
                <div class="me-2" style="width: 10px;">
                    <i class="fa-solid fa-location-dot fa-lg" style="color: #ffffff;"></i>
                </div>
                <a href="{{ route('index') }}" class="nav-link text-white">
                    Navegar
                </a>
            </li>
            <li class="nav-link d-flex align-items-center">
                <div class="me-2" style="width: 10px;">
                    <i class="fa-solid fa-chart-line fa-lg" style="color: #ffffff;"></i>
                </div>
                <a href="#" class="nav-link text-white">
                    Estatisticas
                </a>
            </li>
            <li class="nav-link d-flex align-items-center">
                <div class="me-2" style="width: 10px;">
                    <i class="fa-solid fa-info fa-lg" style="color: #ffffff;"></i>
                </div>
                <a href="#" class="nav-link text-white">
                    Sobre
                </a>
            </li>
            <li class="nav-link d-flex align-items-center">
                <div class="me-2" style="width: 10px;">
                    <i class="fa-solid fa-bars fa-lg" style="color: #ffffff;"></i>
                </div>
                <a href="#" class="nav-link text-white">
                    Creditos
                </a>
            </li>
        </ul>

        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-regular fa-circle-user" style="color: #ffffff;"></i>
                <strong>Teste Da Silva</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Favoritos</a></li>
                <li><a class="dropdown-item" href="#">Configurações</a></li>
                <li><a class="dropdown-item" href="#">perfil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="#">Sair</a></li>
            </ul>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> <!-- Carrega o arquivo Leaflet.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        var baseUrl = "{{ $baseUrl }}";
        var map = L.map('map');
        var streetsLayer;
        var osmTileLayer; // Defina a variável aqui para que possa ser acessada em todo o escopo
        map.removeControl(map.zoomControl);
        map.setView([-1.3830, -48.4291], 12);



        // Adiciona a camada base do OpenStreetMap
        osmTileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            minZoom: 1,
            maxZoom: 19
        }).addTo(map);

        fetch('http://127.0.0.1:8000/api/v5/geojson/region/')
            .then(response => response.json())
            .then(data => {
                var regionSelect = document.getElementById('regionId');
                data.features.forEach(item => {
                    var option = document.createElement('option');
                    option.value = item.properties.ID;
                    option.text = item.properties.Nome;
                    regionSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao buscar regiões:', error));

        function getRegions(regionId) {
            if (regionId === 0) {
                var url = 'http://127.0.0.1:8000/api/v5/geojson/region/';
            } else {
                var url = 'http://127.0.0.1:8000/api/v5/geojson/region/' + regionId;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Remove as camadas existentes do mapa, se houver
                    if (streetsLayer) {
                        map.removeLayer(streetsLayer);
                    }

                    // Desenha as regiões no mapa
                    streetsLayer = L.geoJSON(data, {
                        style: function(feature) {
                            return {
                                color: 'blue', // Cor das bordas
                                fillColor: 'lightblue', // Cor de preenchimento
                                weight: 2, // Espessura da linha
                                opacity: 1, // Opacidade da borda
                                fillOpacity: 0.5 // Opacidade do preenchimento
                            };
                        }
                    }).addTo(map);

                    // Ajusta o zoom e a posição do mapa para se adequar às regiões desenhadas
                    map.fitBounds(streetsLayer.getBounds());

                    // Evento de clique para cada feature na camada streetsLayer
                    streetsLayer.on('click', function(event) {
                        var regionId = event.layer.feature.properties.ID; // Extrai o ID da região clicada

                        // Desativa temporariamente o ouvinte de eventos 'change' no select de regiões
                        document.getElementById('regionId').removeEventListener('change', regionChangeListener);

                        // Atualiza o valor do select de regiões
                        document.getElementById('regionId').value = regionId;

                        // Ativa novamente o ouvinte de eventos 'change' no select de regiões
                        document.getElementById('regionId').addEventListener('change', regionChangeListener);

                        // Carrega os detalhes da região clicada
                        getRegions(regionId);
                    });

                    // Adiciona a camada de regiões (streetsLayer) ao mapa
                    streetsLayer.addTo(map);
                })
                .catch(error => console.error('Erro ao buscar regiões:', error));
            // Função de ouvinte de eventos 'change' no select de regiões
            function regionChangeListener() {
                var selectedRegion = parseInt(this.value); // Obtém o valor selecionado e converte para um número inteiro
                console.log(selectedRegion);
                getRegions(selectedRegion);
            }

            // Adiciona o ouvinte de eventos 'change' ao select de regiões
            document.getElementById('regionId').addEventListener('change', regionChangeListener);
        }

        getRegions(0);


        document.getElementById('regionId').addEventListener('change', function() {
            var selectedRegion = parseInt(this
                .value); // Obtém o valor selecionado e converte para um número inteiro
            console.log(selectedRegion);
            getRegions(selectedRegion);
        });
    </script>
    <script src="https://kit.fontawesome.com/bf4bab225b.js" crossorigin="anonymous"></script>

</body>

</html>
