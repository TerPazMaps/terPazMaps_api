<div class="offcanvas offcanvas-start" tabindex="-1" id="buscaModal" aria-labelledby="buscaTituloModal">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-center flex-grow-1 " id="buscaTituloModal"><span
                class="border border-primary border-2 rounded-4 p-1">Buscar</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body pt-0">
        <div class="text-center">
            <small class="text-dark" id="nomeBairroBusca">selct aqui</small><br>
            <small class="text-secondary">clique no resultado para visualizar no mapa</small>
            <div class="rounded-pill p-1 bg-light">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" id="inputBusca" class="form-control " placeholder="sorve..."
                        aria-label="Pesquisar atividades" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary " type="button" id="limpaInputBusca"><i
                            class="fas fa-times"></i></button>
                </div>
            </div>
        </div>
        <div id="searchResults" class="mt-1 overflow-auto" style="max-height: 85;">
            <!-- Adicione mais resultados conforme necessário -->
            <div class="list-group mb-1">
                <div type="button" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="flex-grow-1">
                        Sorvete do chicão
                    </span>
                    <div id="subclasseAtividade" class="badge bg-secondary text-wrap" style="width: 6rem;">
                        Venda de chopp e similares
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
