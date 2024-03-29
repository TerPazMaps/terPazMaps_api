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
        #map {
            height: 400px;
            width: 100%;
        }

        #checkboxes {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            /* Para centralizar verticalmente */
            padding: 0 20%;
            /* Adiciona espaço de 20% nos lados */

            /* Adaptação para tornar os checkboxes responsivos */
            flex-wrap: wrap;
            /* Permite que os elementos sejam envolvidos para uma nova linha */
            gap: 10px;
            /* Espaçamento entre os checkboxes */
        }

        #checkboxes input[type="checkbox"] {
            /* Estilos específicos para os checkboxes */
            margin: 0;
            /* Remove margem padrão */
        }
    </style>

</head>

<body>
    <form id="streetForm" method="POST">
        <label for="regionId">Region:</label><br>
        <select id="regionId" name="regionId"></select><br><br>
        <hr>
        <div id="checkboxes"></div>
        <button type="submit"
            style="background-color: #28a745; color: #fff; border: none; padding: 8px 16px; cursor: pointer;">Carregar Região</button>
    </form>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> <!-- Carrega o arquivo Leaflet.js -->
    <script>
        var baseUrl = "{{ $baseUrl }}";
        var map = L.map('map');
        var streetsLayer;

        // Função para buscar os dados das condições de ruas e criar as opções de checkbox
        fetch(baseUrl+'api/v5/geojson/street_condition/')
            .then(response => response.json())
            .then(data => {
                var checkboxesDiv = document.getElementById('checkboxes');
                data.forEach(item => {
                    var checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'conditionIds[]';
                    checkbox.value = item.id;
                    checkbox.checked = true; // Marcando o checkbox por padrão
                    var label = document.createElement('label');
                    label.appendChild(document.createTextNode(item.id + "-" + item.condition));
                    label.appendChild(document.createElement('br'));
                    checkboxesDiv.appendChild(checkbox);
                    checkboxesDiv.appendChild(label);
                });
            })
            .catch(error => console.error('Error fetching street conditions:', error));
       
        

        fetch(baseUrl+'api/v5/geojson/region/')
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

        // Intercepta o envio do formulário para buscar e exibir as ruas
        document.getElementById('streetForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o comportamento padrão do formulário
            var formData = new FormData(this); // Cria um objeto FormData com os dados do formulário
            var conditionIds = formData.getAll('conditionIds[]'); // Obtém os valores dos checkboxes
            var regionId = formData.get('regionId'); // Obtém o ID da região selecionada
            console.log(conditionIds); // Imprime o array no console
            console.log(regionId); // Imprime o ID da região no console
            fetchAndDisplayStreets(regionId, conditionIds);
        });
     
        function fetchAndDisplayStreets(regionId, conditionIds) {
            // Remove as camadas do mapa se já existirem
            if (streetsLayer) {
                map.removeLayer(streetsLayer);
            }

            var url = baseUrl+'api/v5/geojson/region/' + regionId + '/streets/';
            if (conditionIds && conditionIds.length > 0) {
                url += '?condition_id=' + encodeURIComponent(conditionIds.join(','));
            }
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Adiciona as novas linhas ao mapa
                    streetsLayer = L.geoJSON(data).addTo(map);

                    // Centraliza o mapa com base nas coordenadas do centro do bairro
                    fetch(baseUrl+'api/v5/geojson/region/' + regionId)
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

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            minZoom: 1,
            maxZoom: 19
        }).addTo(map);
    </script>
</body>

</html>
