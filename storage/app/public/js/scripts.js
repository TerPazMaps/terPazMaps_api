document.addEventListener('DOMContentLoaded', function() {
    var baseUrl = "{{ $baseUrl }}";
    var map = L.map('map');
    var streetsLayer;
    map.removeControl(map.zoomControl);
    map.setView([-1.3830, -48.4291], 12);

    var regionCache = {}; // Objeto para armazenar as regiões em cache

    // Adiciona a camada base do Google Maps
    googleLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    // Carrega todas as regiões e as armazena em cache
    fetch('http://127.0.0.1:8000/api/v5/geojson/region/')
        .then(response => response.json())
        .then(data => {
            regionCache.allRegions = data.features;
            populateRegionSelect(data.features);
            drawRegionLayer(data); // Desenha todas as regiões no mapa inicialmente
        })
        .catch(error => console.error('Erro ao buscar regiões:', error));

    // Função para preencher o select de regiões
    function populateRegionSelect(regions) {
        var regionSelect = document.getElementById('regionId');
        regionSelect.innerHTML = ""; // Limpa as opções existentes

        // Adiciona a opção padrão
        var defaultOption = document.createElement('option');
        defaultOption.value = "0";
        defaultOption.text = "Selecione uma região";
        regionSelect.appendChild(defaultOption);

        // Adiciona as opções das regiões
        regions.forEach(item => {
            var option = document.createElement('option');
            option.value = item.properties.ID;
            option.text = item.properties.Nome;
            regionSelect.appendChild(option);
        });
    }

    // Função para desenhar a camada da região no mapa
    function drawRegionLayer(data) {
        // Remove as camadas existentes do mapa, se houver
        if (streetsLayer) {
            map.removeLayer(streetsLayer);
        }

        // Desenha as regiões no mapa
        streetsLayer = L.geoJSON(data, {
            style: function(feature) {
                return {
                    color: (data.type === 'Feature') ? 'black' : 'blue', // Cor das bordas
                    fillColor: 'lightblue', // Cor de preenchimento
                    weight: (data.type === 'Feature') ? 0.8 : 2, // Espessura da linha
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

            // Atualiza o valor do select de regiões
            document.getElementById('regionId').value = regionId;

            // Carrega os detalhes da região clicada
            loadAndDrawRegion(regionId);
        });

        // Adiciona a camada de regiões (streetsLayer) ao mapa
        streetsLayer.addTo(map);
    }

    // Função para carregar e desenhar a camada da região selecionada
    function loadAndDrawRegion(regionId) {
        var regionData;

        // Verifica se a região já está no cache
        if (regionCache.allRegions) {
            regionData = regionCache.allRegions.find(region => region.properties.ID === regionId);
        }

        drawRegionLayer(regionData); // Desenha a camada da região usando os dados do cache                
    }

    // Evento de mudança no select de regiões
    document.getElementById('regionId').addEventListener('change', function() {
        var selectedRegion = parseInt(this
            .value); // Obtém o valor selecionado e converte para um número inteiro
        if (selectedRegion !== 0) {
            loadAndDrawRegion(selectedRegion);
        } else {
            // Se "Selecione uma região" for selecionado, desenha todas as regiões do cache
            drawRegionLayer(regionCache.allRegions);
        }
    });
});