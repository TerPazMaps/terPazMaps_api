document.addEventListener("DOMContentLoaded", function () {
    var baseUrl = "{{ $baseUrl }}";
    var streetsLayer;
    var map = L.map("map");
    map.removeControl(map.zoomControl);
    map.setView([-1.383, -48.4291], 12);
    var configButton = document.getElementById("voltarButton");
    var streetConditionsData;

    var regionCache = {}; // Objeto para armazenar as regiões em cache

    // Adiciona a camada base do Google Maps
    // var googleLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    //     maxZoom: 20,
    //     subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    // }).addTo(map);

    // Adiciona a camada base do OpenStreetMaps
    var osmTileLayer = L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            minZoom: 1,
            maxZoom: 19,
        }
    ).addTo(map);

    fetch("http://127.0.0.1:8000/api/v5/geojson/street_condition/")
        .then((response) => response.json())
        .then((data) => {
            streetConditionsData = data;

            streetConditionsData.forEach((condition) => {
                // Adiciona a chave "ativo" com valor booleano true a cada objeto do array
                condition.ativo = false;
                if (condition.id == 1 || condition.id == 7) {
                    condition.ativo = true;
                }
            });
            createStreetConditionDivs();
        })
        .catch((error) =>
            console.error("Erro ao buscar dados das condições das ruas:", error)
        );

    // Carrega todas as regiões e as armazena em cache
    fetch("http://127.0.0.1:8000/api/v5/geojson/region/")
        .then((response) => response.json())
        .then((data) => {
            regionCache.allRegions = data.features;
            populateRegionSelect(data.features);
            drawRegionLayer(data); // Desenha todas as regiões no mapa inicialmente
        })
        .catch((error) => console.error("Erro ao buscar regiões:", error));

    // Função para preencher o select de regiões
    function populateRegionSelect(regions) {
        var regionSelect = document.getElementById("regionId");
        regionSelect.innerHTML = ""; // Limpa as opções existentes

        // Adiciona a opção padrão
        var defaultOption = document.createElement("option");
        defaultOption.value = "0";
        defaultOption.text = "Selecione uma região";
        regionSelect.appendChild(defaultOption);

        // Adiciona as opções das regiões
        regions.forEach((item) => {
            var option = document.createElement("option");
            option.value = item.properties.ID;
            option.text = item.properties.Nome;
            regionSelect.appendChild(option);
        });
    }

    // Função para desenhar a camada da região no mapa
    function drawRegionLayer(data) {
        // Remove as camadas de região existentes do mapa, se houver
        limparCamadasPersonalizadas("região");

        // Desenha as regiões no mapa
        streetsLayer = L.geoJSON(data, {
            style: function (feature) {
                return {
                    color: data.type === "Feature" ? "black" : "blue", // Cor das bordas
                    fillColor: "black", // Cor de preenchimento
                    weight: data.type === "Feature" ? 0.8 : 2, // Espessura da linha
                    opacity: 1, // Opacidade da borda
                    fillOpacity: 0.5, // Opacidade do preenchimento
                };
            },
            layerType: "região", // Adiciona a propriedade layerType com valor "região"
        }).addTo(map);

        // Ajusta o zoom e a posição do mapa para se adequar às regiões desenhadas
        map.fitBounds(streetsLayer.getBounds());

        // Remova o evento de clique da camada streetsLayer antes de adicionar um novo
        streetsLayer.off("click");

        // Evento de clique para cada feature na camada streetsLayer
        streetsLayer.on("click", function (event) {
            var regionId = event.layer.feature.properties.ID; // Extrai o ID da região clicada

            // Atualiza o valor do select de regiões
            document.getElementById("regionId").value = regionId;

            // Carrega os detalhes da região clicada
            loadAndDrawRegion(regionId);
            loadStreetsByRegion(regionId);

            bottoesMenu(regionId);
        });

        // Adiciona a camada de regiões (streetsLayer) ao mapa
        streetsLayer.addTo(map);
    }

    // Função para carregar e desenhar a camada da região selecionada
    function loadAndDrawRegion(regionId) {
        // Remove as camadas existentes do mapa, se houver
        limparCamadasPersonalizadas("região");

        var regionData;

        // Verifica se a região já está no cache
        if (regionCache.allRegions) {
            regionData = regionCache.allRegions.find(
                (region) => region.properties.ID === regionId
            );
        }
        drawRegionLayer(regionData); // Desenha a camada da região usando os dados do cache
    }

    // Função para carregar as ruas com base na região selecionada
    function loadStreetsByRegion(regionId) {
        var conditionIds = getConditionIds();
        var url =
            "http://127.0.0.1:8000/api/v5/geojson/region/" +
            regionId +
            "/streets";
        if (conditionIds.length > 0) {
            url +=
                "?condition_id=" + encodeURIComponent(conditionIds.join(","));
        } else {
            url += "?condition_id=8888";
        }

        fetch(url)
            .then((response) => response.json())
            .then((data) => {
                drawStreetLayer(data); // Desenha as ruas no mapa
            })
            .catch((error) => console.error("Erro ao buscar ruas:", error));
    }

    function getConditionIds() {
        // Array para armazenar os IDs dos objetos com ativo = true
        var activeConditionIds = [];

        // Iterar sobre os objetos em streetConditionsData
        for (var i = 0; i < streetConditionsData.length; i++) {
            // Verificar se o objeto tem ativo = true
            if (streetConditionsData[i].ativo === true) {
                // Adicionar o ID do objeto ao array activeConditionIds
                activeConditionIds.push(streetConditionsData[i].id);
            }
        }

        // Retornar o array com os IDs dos objetos com ativo = true
        return activeConditionIds;
    }

    // Função para desenhar a camada das ruas no mapa
    function drawStreetLayer(data) {
        // Remove as camadas de ruas existentes do mapa, se houver
        limparCamadasPersonalizadas("rua");

        // Define a função de estilo personalizado para as ruas
        function styleStreet(feature) {
            return {
                color: feature.properties.color, // Define a cor da rua com base nos dados
                weight: 1, // Espessura da linha
            };
        }

        // Cria a camada de ruas com estilo personalizado e adiciona a propriedade layerType
        streetsLayer = L.geoJSON(data, {
            style: styleStreet, // Define o estilo personalizado para as ruas
            layerType: "rua", // Adiciona a propriedade layerType com valor "rua"
        }).addTo(map);
    }

    function bottoesMenu(selectedRegion) {
        if (selectedRegion !== 0) {
            document.getElementById("voltarButton").style.display = "block"; // Exibe o botão configButton
            document.getElementById("configButton").style.display = "block"; // Exibe o botão configButton
            document.getElementById("ferraButton").style.display = "block"; // Exibe o botão configButton
        } else {
            document.getElementById("voltarButton").style.display = "none"; // Oculta o botão configButton
            document.getElementById("configButton").style.display = "none"; // Oculta o botão configButton
            document.getElementById("ferraButton").style.display = "none"; // Oculta o botão configButton
            document.getElementById("regionId").value = selectedRegion;
        }
    }

    // Função para criar as divs das condições das ruas
    function createStreetConditionDivs() {
        if (!streetConditionsData) return;

        var streetConditionsContainer = document.getElementById(
            "streetConditionsContainer"
        );
        streetConditionsContainer.innerHTML = "";

        streetConditionsData.forEach((condition) => {
            var div = document.createElement("div");
            div.classList.add(
                "form-check",
                "form-switch",
                "form-check-reverse",
                "p-2",
                "align-self-center"
            );

            var input = document.createElement("input");
            input.classList.add("form-check-input");
            input.type = "checkbox";
            input.id = "flexSwitchCheckReverse_" + condition.id; // Adiciona um ID único para cada checkbox

            // Define o estado do checkbox com base no atributo 'ativo' do objeto condition

            input.checked = condition.ativo;

            // Adiciona um ouvinte de evento change para cada input
            input.addEventListener("change", function () {
                // Atualiza o estado 'ativo' do objeto condition com base no estado do checkbox
                condition.ativo = this.checked;

                // Recarrega as ruas com base na região selecionada
                var selectedRegion = document.getElementById("regionId").value;
                loadStreetsByRegion(selectedRegion);
            });

            var label = document.createElement("label");
            label.classList.add("form-check-label");
            label.htmlFor = "flexSwitchCheckReverse_" + condition.id; // Define o for do label para o ID do input
            label.innerHTML = `<i class="bi bi-circle-fill" style="color: ${condition.color};"></i>${condition.condition}`;

            div.appendChild(input);
            div.appendChild(label);
            streetConditionsContainer.appendChild(div);
        });
    }

    function resetMap() {
        // Limpar camadas adicionadas ao mapa, se houver
        limparCamadasPersonalizadas();

        // Redefinir a visualização do mapa para a posição inicial
        map.setView([-1.383, -48.4291], 12);

        // Limpar seleção do select de regiões
        document.getElementById("regionId").value = "0";

        // Desenhar todas as regiões no mapa
        drawRegionLayer(regionCache.allRegions);

        // Ocultar botões e menu suspenso
        bottoesMenu(0);
    }

    function limparCamadasPersonalizadas(tipo) {
        // Obtém todas as camadas adicionadas ao mapa
        var layers = map._layers;

        // Itera sobre as camadas e remove apenas as camadas personalizadas do tipo especificado
        for (var layerId in layers) {
            // Verifica se a camada não é uma camada padrão e tem a propriedade layerType correspondente ao tipo especificado
            if (
                !layers[layerId].options.isBaseLayer &&
                layers[layerId].options.layerType === tipo &&
                layers[layerId]._url === undefined
            ) {
                map.removeLayer(layers[layerId]);
            }
        }
    }

    function limparCamadaDeRuas() {
        // Itera sobre as camadas e remove apenas as camadas personalizadas do tipo especificado
        var layers = map._layers;
        console.log("remover layer:" + Object.keys(layers));
        console.log("remover layer:" + Object.keys(layers[25]["options"]));

        for (var layerId in layers) {
            // Verifica se a camada não é uma camada padrão e tem a propriedade layerType correspondente ao tipo especificado
            if (
                !layers[layerId].options.isBaseLayer &&
                layers[layerId].options.layerType != "região" &&
                layers[layerId]._url !==
                    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            ) {
                map.removeLayer(layers[layerId]);
                // console.log(Object(map._layers));
            }
        }
    }

    var ferraButton = document.getElementById("ferraButton");
    var ferraOptions = document.getElementById("ferraOptions");

    ferraButton.addEventListener("click", function () {
        ferraOptions.classList.toggle("d-none"); // Alternar a visibilidade do menu suspenso
    });

    // Adicione manipuladores de evento para cada opção do menu suspenso se desejar
    // Exemplo de manipulador de evento para a opção "Buscar":
    var buscarOption = ferraOptions.querySelector("button:nth-of-type(1)");
    buscarOption.addEventListener("click", function () {
        // Lógica quando a opção "Buscar" é clicada
    });

    // Exemplo de manipulador de evento para a opção "Ruas":
    var ruasOption = ferraOptions.querySelector("button:nth-of-type(2)");
    ruasOption.addEventListener("click", function () {
        ferraOptions.classList.toggle("d-none"); // Alternar a visibilidade do menu suspenso
        // Quando a opção "Ruas" é clicada
        ruasOption.addEventListener("click", function () {
            // Exibir o modal de Ruas
            document.getElementById("ruasModal").style.display = "block";
        });

        // Fechar o modal ao clicar fora do modal
        window.onclick = function (event) {
            var modal = document.getElementById("ruasModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    });

    // Exemplo de manipulador de evento para a opção "Temas":
    var temasOption = ferraOptions.querySelector("button:nth-of-type(3)");
    temasOption.addEventListener("click", function () {
        // Lógica quando a opção "Temas" é clicada
    });

    // Exemplo de manipulador de evento para a opção "Desfazer Tudo":
    var desfazerOption = ferraOptions.querySelector("button:nth-of-type(4)");
    desfazerOption.addEventListener("click", function () {
        // Lógica quando a opção "Desfazer Tudo" é clicada
    });

    // Adicione um evento de clique ao botão de configuração
    configButton.addEventListener("click", function () {
        limparCamadasPersonalizadas();
        drawRegionLayer(regionCache.allRegions);
        bottoesMenu(0);
    });

    // Evento de mudança no select de regiões
    document.getElementById("regionId").addEventListener("change", function () {
        var selectedRegion = parseInt(this.value); // Obtém o valor selecionado e converte para um número inteiro

        if (selectedRegion != 0) {
            loadAndDrawRegion(selectedRegion); // Carrega e desenha a camada da região selecionada
            loadStreetsByRegion(selectedRegion); // Carrega as ruas da região selecionada
        } else {
            // Se "Selecione uma região" for selecionado, desenha todas as regiões do cache
            drawRegionLayer(regionCache.allRegions);
        }

        bottoesMenu(selectedRegion);
    });
});

