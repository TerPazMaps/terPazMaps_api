<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Streets</title>
    <!-- Adicione o link para o arquivo Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">-->
    <style>
        body,
        html {
            height: 100%;
            /* Garante que o corpo e o HTML ocupem toda a altura da janela */
            margin: 0;
            /* Remove margens padrão */
            padding: 0;
            /* Remove preenchimentos padrão */
        }

        #map {
            height: 80%;
            /* Define a altura do mapa como 80% da altura da janela */
            width: 100%;
            /* Define a largura do mapa como 100% da largura da janela */
        }

        #checkboxesClasses {
            height: 20%;
            /* Define a altura dos checkboxes como 20% da altura da janela */
            width: 100%;
            /* Define a largura dos checkboxes como 100% da largura da janela */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 5px;
            /* Adiciona um pouco de espaço nas laterais */
            /* Defina o tamanho da fonte desejado */
            flex-wrap: wrap;
            gap: 10px;
        }

        #checkboxesClasses input[type="checkbox"] {
            margin: 0;
        }
    </style>




</head>

<body>
    <form id="classesForm" method="POST">
        <label for="regionId">Region:</label><br>
        <select id="regionId" name="regionId"></select><br><br>
        <div id="checkboxesClasses"></div>
        <button type="submit"
            style="background-color: #28a745; color: #fff; border: none; padding: 8px 16px; cursor: pointer;">Carregar
            atividades</button>
    </form>

    <div id="map"></div>
    <div class="clear"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> <!-- Carrega o arquivo Leaflet.js -->
    <script>
        var baseUrl = "{{ $baseUrl }}";
        var map = L.map('map');
        var streetsLayer;
        var osmTileLayer; // Defina a variável aqui para que possa ser acessada em todo o escopo

        // Função para buscar classes e criar as opções de checkbox
        fetch(baseUrl + 'api/v5/geojson/classe/')
            .then(response => response.json())
            .then(data => {
                var checkboxesClasse = document.getElementById('checkboxesClasses');
                data.forEach(item => {
                    var checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'classesIds[]';
                    checkbox.value = item.Classe.ID;
                    if (item.Classe.ID == 1) {
                        checkbox.checked = true; // Marcando o checkbox por padrão
                    }
                    var label = document.createElement('label');
                    label.appendChild(document.createTextNode(item.Classe.ID + "-" + item.Classe.Nome));
                    label.appendChild(document.createElement('br'));
                    checkboxesClasse.appendChild(checkbox);
                    checkboxesClasse.appendChild(label);
                });
            })
            .catch(error => console.error('Error fetching classes:', error));


        fetch(baseUrl + 'api/v5/geojson/region/')
            .then(response => response.json())
            .then(data => {
                var regionSelect = document.getElementById('regionId');
                data.features.forEach(item => {
                    var option = document.createElement('option');
                    option.value = item.properties.ID;
                    option.text = item.properties.ID + "-" + item.properties.Nome;
                    regionSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching regions:', error));

        document.getElementById('classesForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o comportamento padrão do formulário
            var formData = new FormData(this); // Cria um objeto FormData com os dados do formulário
            var classesIds = formData.getAll('classesIds[]'); // Obtém os valores dos checkboxes
            var regionId = formData.get('regionId'); // Obtém o ID da região selecionada
            console.log("region: " + regionId); // Imprime o array no console
            console.log("classes: " + classesIds); // Imprime o array no console
            fetchAndDisplayIcons(regionId, classesIds);
        });

        function fetchAndDisplayIcons(regionId, classesIds) {
            // Limpa os marcadores do mapa
            if (streetsLayer) {
                streetsLayer.clearLayers();
            } else {
                streetsLayer = L.featureGroup().addTo(map);
            }

            var url = baseUrl + 'api/v5/geojson/region/' + regionId + '/icons/';
            if (classesIds && classesIds.length > 0) {
                url += '?class_id=' + encodeURIComponent(classesIds.join(','));
            }
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Adiciona os ícones no mapa
                    data.features.forEach(feature => {
                        var icon = L.icon({
                            iconUrl: feature.properties.subclass.icon.img_url, // URL do ícone
                            iconSize: [32, 32], // Tamanho do ícone
                            iconAnchor: [16, 32], // Ponto de ancoragem do ícone
                            popupAnchor: [0, -32] // Ponto de ancoragem do pop-up
                        });

                        var marker = L.marker([feature.geometry.coordinates[1], feature.geometry.coordinates[
                                0]], {
                                icon: icon
                            })
                            .bindPopup(feature.properties.name) // Define o texto do pop-up
                            .addTo(streetsLayer); // Adiciona o marcador à camada de marcadores
                    });

                    // Centraliza o mapa com base nas coordenadas do centro do bairro
                    fetch(baseUrl + 'api/v5/geojson/region/' + regionId)
                        .then(response => response.json())
                        .then(regionData => {
                            var centerCoordinates = regionData.properties.Centro.coordinates;
                            map.setView([centerCoordinates[1], centerCoordinates[0]], 14);
                        })
                        .catch(error => console.error('Error fetching region center coordinates:', error));
                })
                .catch(error => console.error('Error fetching streets:', error));
        }

        map.setView([-1.3936, -48.3951], 11);

        // Adiciona a camada base do OpenStreetMap
        osmTileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            minZoom: 1,
            maxZoom: 19
        }).addTo(map);

        // Define um estilo escuro para a camada de sobreposição
        var darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://carto.com/">Carto</a>',
            maxZoom: 19
        });

        // Adiciona os controles de camadas
        var baseLayers = {
            "OpenStreetMap": osmTileLayer,
        };

        var overlays = {
            "Dark": darkLayer,
        };

        L.control.layers(baseLayers, overlays).addTo(map);
    </script>

</body>

</html>
