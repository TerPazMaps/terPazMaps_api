document.addEventListener("DOMContentLoaded", function () {
    var baseUrl = "{{ $baseUrl }}";
    var streetsLayer;
    var map = L.map("map");
    map.removeControl(map.zoomControl);
    map.setView([-1.383, -48.4291], 12);
    var configButton = document.getElementById("voltarButton");
    var streetConditionsData;

    var regionCache = {}; // Objeto para armazenar as regiões em cache
    var activitiesCache = {}; // Objeto para armazenar as atividades em cache
    var markers = [];

  
    // Adiciona a camada base do Google Maps com o estilo personalizado
    // var googleLayer = L.tileLayer(
    //     "https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}",
    //     {
    //         maxZoom: 20,
    //         subdomains: ["mt0", "mt1", "mt2", "mt3"],
    //         style: customMapStyle, // Adiciona o estilo personalizado
    //     }
    // ).addTo(map);

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

        console.log(data);
        // Desenha as regiões no mapa
        streetsLayer = L.geoJSON(data, {
            style: function (feature) {
                return {
                    color: feature.type === "Feature" ? "black" : "blue", // Cor das bordas
                    fillColor: "black", // Cor de preenchimento
                    weight: feature.type === "Feature" ? 0.8 : 2, // Espessura da linha
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

    function getActivities(regions) {
        fetch(
            "http://127.0.0.1:8000/api/v5/geojson/activitie?regions=" + regions
        )
            .then((response) => response.json())
            .then((data) => {
                activitiesCache = data.features.map((feature) => ({
                    id: feature.properties["ID Geral"],
                    nome: feature.properties.Nome,
                    classe: feature.properties.Classe,
                    subclasse: feature.properties["Sub-classe"],
                    bairroId: feature.properties.Bairro_id,
                    bairro: feature.properties.Bairro,
                    nivel: feature.properties.Nível,
                    geometry: feature.geometry,
                    img_url: feature.properties.img_url,
                }));
            })
            .catch((error) =>
                console.error("Erro ao buscar dados das atividades:", error)
            );
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
        ferraOptions.classList.toggle("d-none");
        // Lógica quando a opção "Buscar" é clicada
        getActivities(parseInt(document.getElementById("regionId").value));
        // Exibir o modal de Ruas
        document.getElementById("buscaModal").style.display = "block";
        // Definir o valor do campo de texto com o texto da opção selecionada
        // Obter o texto da opção selecionada
        document.getElementById("nomeBairroBusca").textContent =
            document.getElementById("regionId").options[
                document.getElementById("regionId").selectedIndex
            ].text;
    });

    var inputBusca = document.getElementById("inputBusca");
    var searchResults = document.getElementById("searchResults");

    inputBusca.addEventListener("input", function () {
        var termoBusca = inputBusca.value.toLowerCase();
        var resultados = [];

        // Caso contrário, filtre os resultados de acordo com o termo de busca
        resultados = activitiesCache.filter(function (atividade) {
            if (atividade.nome.trim() === "") {
                return atividade.subclasse
                    .toLowerCase()
                    .includes(termoBusca.toLowerCase());
            } else {
                return atividade.nome
                    .toLowerCase()
                    .includes(termoBusca.toLowerCase());
            }
        });

        // Limpa os resultados anteriores
        searchResults.innerHTML = "";
        if (termoBusca === "") {
            // Crie a estrutura correspondente
            var divSearchResults = document.createElement("div");
            divSearchResults.id = "searchResults";
            divSearchResults.classList.add("mt-1", "overflow-auto");
            divSearchResults.style.maxHeight = "85px";

            // Adiciona a lista de resultados
            var divListGroup = document.createElement("div");
            divListGroup.classList.add("list-group", "mb-1");

            var divListItem = document.createElement("div");
            divListItem.classList.add(
                "list-group-item",
                "list-group-item-action",
                "d-flex",
                "align-items-center"
            );

            var iconMarker = document.createElement("i");
            iconMarker.classList.add("fas", "fa-map-marker-alt");

            var spanNome = document.createElement("span");
            spanNome.classList.add("flex-grow-1");
            spanNome.textContent = "Sorvete do chicão";

            var divSubclasse = document.createElement("div");
            divSubclasse.id = "subclasseAtividade";
            divSubclasse.classList.add("badge", "bg-secondary", "text-wrap");
            divSubclasse.style.width = "6rem";
            divSubclasse.textContent = "Venda de chopp e similares";

            divListItem.appendChild(iconMarker);
            divListItem.appendChild(spanNome);
            divListItem.appendChild(divSubclasse);

            divListGroup.appendChild(divListItem);
            divSearchResults.appendChild(divListGroup);

            // Adiciona a estrutura criada ao searchResults
            searchResults.appendChild(divSearchResults);
        } else if (resultados.length > 0) {
            // Crie uma div externa com a classe "list-group"
            var divListGroup = document.createElement("div");
            divListGroup.classList.add("list-group", "mb-1");

            // Adicione os novos resultados à div externa "list-group"
            resultados.forEach(function (atividade) {
                var divItem = document.createElement("div");
                divItem.setAttribute("type", "button");
                divItem.classList.add(
                    "list-group-item",
                    "list-group-item-action",
                    "d-flex",
                    "border",
                    "mt-1",
                    "align-items-center"
                );

                // Cria o elemento de imagem
                var imgIcon = document.createElement("img");
                imgIcon.src = atividade.img_url; // Adiciona a URL da imagem
                imgIcon.alt = "Ícone"; // Texto alternativo da imagem (opcional)
                imgIcon.style.width = "1.5rem"; // Define a largura da imagem
                imgIcon.style.height = "1.5rem"; // Define a altura da imagem

                var spanNome = document.createElement("span");
                spanNome.classList.add("flex-grow-1");

                spanNome.textContent = atividade.nome;
                if (atividade.nome == "") {
                    spanNome.classList.add("text-danger");
                    spanNome.textContent = "Sem nome";
                }

                var divSubclasse = document.createElement("div");
                divSubclasse.id = "subclasseAtividade";
                divSubclasse.classList.add(
                    "badge",
                    "bg-secondary",
                    "text-wrap"
                );
                // divSubclasse.style.width = "6rem";
                divSubclasse.textContent = atividade.subclasse;

                divItem.appendChild(imgIcon); // Adiciona a imagem
                divItem.appendChild(spanNome);
                divItem.appendChild(divSubclasse);
                // Adicionando um evento de clique à divItem
                // Declara um array global para manter a referência de todos os marcadores

                divItem.addEventListener("click", function () {
                    // Disparar o evento de clique no botão de fechar do modal
                    var closeButton = document.querySelector(
                        "#buscaModal .btn-close"
                    );
                    closeButton.click();

                    // Remover os marcadores anteriores, se existirem
                    if (markers.length > 0) {
                        markers.forEach(function (markerId) {
                            var markerToRemove = map._layers[markerId];
                            if (markerToRemove) {
                                map.removeLayer(markerToRemove);
                            }
                        });
                        markers = []; // Limpar o array de marcadores
                    }

                    // Criar um novo marcador com as informações da atividade
                    var icon = L.icon({
                        iconUrl: atividade.img_url, // URL do ícone do marcador
                        iconSize: [38, 38], // Tamanho do ícone do marcador
                        iconAnchor: [19, 38], // Posição do ícone em relação ao marcador
                        popupAnchor: [0, -38], // Posição do popup em relação ao marcador
                    });

                    // Centralizar o mapa na coordenada da atividade
                    var coordenadas = atividade.geometry.coordinates; // Obter as coordenadas da atividade atual e da próxima atividade

                    map.setView([coordenadas[1], coordenadas[0]], 18);

                    var newMarker = L.marker([coordenadas[1], coordenadas[0]], {
                        icon: icon,
                    }).addTo(map);

                    // Adicionar informações adicionais ao marcador, como um popup
                    var popupContent =
                        "<b>" +
                        atividade.nome +
                        "</b><br>" +
                        "<em>" +
                        atividade.classe +
                        " - " +
                        atividade.subclasse +
                        "</em>";
                    newMarker.bindPopup(popupContent).openPopup();

                    // Adicionar o novo marcador ao array de marcadores
                    markers.push(newMarker._leaflet_id);
                });

                // Adicione o item de resultado à div externa "list-group"
                divListGroup.appendChild(divItem);
            });

            // Adicione a div externa "list-group" ao elemento de resultados de busca
            searchResults.appendChild(divListGroup);
        } else {
            var divItem = document.createElement("div");
            divItem.classList.add(
                "list-group-item",
                "list-group-item-action",
                "d-flex",
                "border",
                "mt-1",
                "align-items-center"
            );

            var iconFeedback = document.createElement("i");
            iconFeedback.classList.add(
                "fas",
                "fa-exclamation-circle",
                "me-2",
                "text-secondary"
            );

            var spanMessage = document.createElement("span");
            spanMessage.textContent = "Nenhum resultado encontrado";

            divItem.appendChild(iconFeedback);
            divItem.appendChild(spanMessage);

            searchResults.appendChild(divItem);
        }
    });

    document
        .getElementById("limpaInputBusca")
        .addEventListener("click", function () {
            document.getElementById("inputBusca").value = ""; // Define o valor como uma string vazia
            document
                .getElementById("inputBusca")
                .dispatchEvent(new Event("input")); // Dispara o evento de input
        });

    window.addEventListener("DOMContentLoaded", (event) => {
        // Calcula a altura disponível
        const offcanvasBody = document.querySelector(".offcanvas-body");
        const maxHeight =
            offcanvasBody.clientHeight - offcanvasBody.offsetTop - 20; // 20px para folga

        // Define a altura máxima da div searchResults
        const searchResults = document.getElementById("searchResults");
        searchResults.style.maxHeight = `${maxHeight}px`;
    });

    // ---------------Manipulador de Ruas---------------
    var ruasOption = ferraOptions.querySelector("button:nth-of-type(2)");
    ruasOption.addEventListener("click", function () {
        ferraOptions.classList.toggle("d-none"); // Alternar a visibilidade do menu suspenso
        // Quando a opção "Ruas" é clicada
        document.getElementById("ruasModal").style.display = "block";
    });
    // ---------------Manipulador de Ruas---------------Fim

    // Exemplo de manipulador de evento para a opção "Temas":
    var temasOption = ferraOptions.querySelector("button:nth-of-type(3)");
    temasOption.addEventListener("click", function () {
        // Lógica quando a opção "Temas" é clicada
        ferraOptions.classList.toggle("d-none"); // Alternar a visibilidade do menu suspenso
        // Quando a opção "Ruas" é clicada
        document.getElementById("temasModal").style.display = "block";
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
