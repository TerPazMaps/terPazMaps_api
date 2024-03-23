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

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
            aria-controls="offcanvasExample" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
    </header>



    <main style="padding-top: 45px;"> <!-- 45px é a altura do seu cabeçalho -->
        <div id="map"></div>
    </main>

    <div class="offcanvas offcanvas-start bg-primary text-white " tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">TerPazMaps</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body custom-offcanvas">
            {{-- 
            esse botão deve apontar para o mapa.    
        --}}
            <a href="{{ route('index') }}" class="nav-link p-3 d-block">
                <i class="fa-solid fa-map-location-dot fa-lg " style="color: #ffffff;"></i>
                Navegar</a>
            <a href="http://" class="nav-link p-3 d-block">
                <i class="fa-solid fa-chart-line fa-lg" style="color: #ffffff;"></i>
                Estatisticas
            </a>
            <a href="http://" class="nav-link p-3 d-block">
                <i class="fa-solid fa-circle-info fa-lg" style="color: #ffffff;"></i>
                Sobre
            </a>
            <a href="http://" class="nav-link link-none p-3 d-block">
                <i class="fa-solid fa-bars fa-lg" style="color: #ffffff;"></i>
                Creditos
            </a>
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

        map.setView([-1.3730, -48.3951], 12);

        // Adiciona a camada base do OpenStreetMap
        osmTileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            minZoom: 1,
            maxZoom: 19
        }).addTo(map);
    </script>
    <script src="https://kit.fontawesome.com/bf4bab225b.js" crossorigin="anonymous"></script>

</body>

</html>
