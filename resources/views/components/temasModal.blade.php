<div class="offcanvas offcanvas-start" tabindex="-1" id="temasModal" aria-labelledby="temasTituloModal">
    <div class="offcanvas-header">
        <span id="temaSelecionados" class="border bg-primary text-white border-2 rounded-4 p-1">0 selecionados</span>
        <h5 class="offcanvas-title text-center " id="temasTituloModal"><span
                class="border border-primary border-2 rounded-4 p-1">Temas</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body pt-0 overflow-auto" style="max-height: 85;">

        <div class="accordion accordion-flush" id="accordion1">
            <div class="accordion-item ">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Nome Classe
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                    data-bs-parent="#accordion1">
                    <div class="accordion-body border border-primary">

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                            <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox
                                input</label>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>

    </div>
</div>
