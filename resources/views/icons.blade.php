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
            margin: 0;
            padding: 0;
            position: relative;
            /* Adiciona uma posição relativa para permitir o posicionamento absoluto dos elementos filhos */
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
        }

        #formContainer {
            display: none;
            /* Oculta o contêiner do formulário inicialmente */
            position: absolute;
            /* Posiciona o contêiner do formulário absolutamente em relação ao corpo da página */
            top: 50px;
            /* Ajuste conforme necessário para evitar que o formulário se sobreponha ao cabeçalho */
            left: 50px;
            /* Ajuste conforme necessário para evitar que o formulário se sobreponha à esquerda */
            z-index: 1;
            /* Garante que o formulário esteja sobre o mapa */
            background-color: rgba(255, 255, 255, 0.8);
            /* Fundo semitransparente para facilitar a leitura */
            padding: 20px;
            border-radius: 10px;
        }

        #toggleFormButton {
            position: absolute;
            /* Posiciona o botão absolutamente em relação ao mapa */
            left: 50px;
            /* Ajuste conforme necessário para a posição vertical */
            top: 20px;
            /* Ajuste conforme necessário para a posição horizontal */
            z-index: 2;
            /* Garante que o botão esteja sobre o mapa e o formulário */
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        #selectClasses,
        #selectSubclasses,
        #regionId {
            margin-bottom: 10px;
            /* Adiciona uma margem inferior de 10px entre os selects */
        }

        #map,
        #formContainer {
            box-sizing: border-box;
            /* Garante que padding e border não afetem as dimensões totais do elemento */
        }
    </style>
</head>

<body>
    <div id="formContainer">
        <form id="classesForm" method="POST">

            <label for="regionId">Região:</label><br>
            <select id="regionId" name="regionId"></select><br>

            <label for="regionId">Classes:</label><br>
            <div id="selectClasses">
                <select id="classesSelect">
                    <!-- Opções adicionadas dinamicamente aqui -->
                </select>
            </div>

            <label for="regionId">Subclasses:</label>
            <div id="selectSubclasses">
                <select id="subclassesSelect">
                    <!-- Opções adicionadas dinamicamente aqui -->
                </select>
            </div>

            <button type="submit"
                style="background-color: #28a745; color: #fff; border-radius: 5px; border: none; padding: 8px 16px; cursor: pointer;">Carregar
                atividades
                <i class="fa-solid fa-magnifying-glass" style="color: #000000;"></i></button>
        </form>
    </div>

    <div id="map"></div>

    <button id="toggleFormButton">
        Atividades
        <i class="fa-solid fa-gear" style="color: #000000;"></i>
    </button> <!-- Botão para alternar a visibilidade do formulário -->

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> <!-- Carrega o arquivo Leaflet.js -->
    <script>
        // Define a função fechaForme() fora do escopo do evento click
        function fechaForme() {
            var formContainer = document.getElementById('formContainer');
            if (formContainer.style.display === 'none') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        }

        // Adiciona um evento de clique ao botão para alternar a visibilidade do formulário
        document.getElementById('toggleFormButton').addEventListener('click', fechaForme);


        var baseUrl = "{{ $baseUrl }}";
        var map = L.map('map');
        var streetsLayer;
        var osmTileLayer; // Defina a variável aqui para que possa ser acessada em todo o escopo

        //busca regiões
        fetch('http://127.0.0.1:8000/api/v5/geojson/region/')
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

        // Função para buscar classes e criar as opções de select
        fetch('http://127.0.0.1:8000/api/v5/geojson/classe/')
            .then(response => response.json())
            .then(data => {
                var classesSelect = document.getElementById('classesSelect');
                data.forEach(item => {
                    var option = document.createElement('option');
                    option.value = item.Classe.ID;
                    option.text = item.Classe.ID + "-" + item.Classe.Nome;
                    if (item.Classe.ID == 1) {
                        option.selected = true; // Seleciona a opção por padrão
                    }
                    classesSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching classes:', error));

        // Função para buscar Subclasses e criar as opções de select
        function selectSubclasses(classesIds) {
            // Atualiza a URL com o ID da classe selecionada
            var url = 'http://127.0.0.1:8000/api/v5/geojson/classe/' + classesIds + '/subclasses';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var subclassesSelect = document.getElementById('subclassesSelect');
                    // Limpa as opções existentes do select
                    subclassesSelect.innerHTML = '';
                    data.forEach(item => {
                        item.subclasse.forEach(sub => {
                            var option = document.createElement('option');
                            option.value = sub.id; // Define o valor da opção como o ID da subclasse
                            option.text = sub.name; // Define o texto da opção como o nome da subclasse
                            subclassesSelect.appendChild(option);
                        });
                    });
                })
                .catch(error => console.error('Error fetching subclasses:', error));
        }

        // popula as subclasses na primeira abertura de tela
        selectSubclasses(1);

        // Adiciona um manipulador de eventos ao elemento classesSelect para chamar a função selectSubclasses() quando uma classe for selecionada
        document.getElementById('classesSelect').addEventListener('change', function() {
            var selectedClass = parseInt(this.value); // Obtém o valor selecionado e converte para um número inteiro
            selectSubclasses(selectedClass);
        });

        document.getElementById('classesForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o comportamento padrão do formulário
            var regionId = document.getElementById('regionId').value; // Obtém o ID da região selecionada
            var classesSelect = document.getElementById('classesSelect');
            var classesIds = Array.from(classesSelect.selectedOptions).map(option => option
            .value); // Obtém os valores selecionados do select de classes
            var subclassesSelect = document.getElementById('subclassesSelect');
            var subclassId = subclassesSelect.value; // Obtém o ID da subclasse selecionada
            fechaForme();
            fetchAndDisplayIcons(regionId, classesIds,
            subclassId); // Passa o ID da subclasse selecionada para a função fetchAndDisplayIcons
        });

        function fetchAndDisplayIcons(regionId, classesIds, subclassId) {
            // Limpa os marcadores do mapa
            if (streetsLayer) {
                streetsLayer.clearLayers();
            } else {
                streetsLayer = L.featureGroup().addTo(map);
            }

            var url = 'http://127.0.0.1:8000/api/v5/geojson/region/' + regionId + '/icons/';
            if (classesIds && classesIds.length > 0) {
                url += '?class_id=' + encodeURIComponent(classesIds.join(
                ',')); // Adiciona os IDs das classes como parâmetro na URL
                url += '&subclass_id=' + encodeURIComponent(subclassId); // Adiciona o ID da subclasse como parâmetro na URL
            }
            console.log(url);
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
                    fetch('http://127.0.0.1:8000/api/v5/geojson/region/' + regionId)
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
    <script src="https://kit.fontawesome.com/bf4bab225b.js" crossorigin="anonymous"></script>

</body>

</html>
