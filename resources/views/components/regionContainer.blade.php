<div id="regionContainer" class="bg-white p-1 d-flex ">
    <button id="ferraButton" class="btn btn-primary ">
        <i class="fa-solid fa-hammer" style="color: #ffffff;"></i>
    </button>
    <div id="ferraOptions" class="d-none mt-1">
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#buscaModal"
            aria-controls="offcanvasExample">
            <i class="fa-solid fa-magnifying-glass fa-2xs" style="color: #ffffff;"></i>
            Buscar
        </button>
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#ruasModal"
            aria-controls="offcanvasExample">
            <i class="fa-solid fa-road fa-2xs" style="color: #ffffff;"></i>
            Ruas
        </button>
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#temasModal"
            aria-controls="offcanvasExample">
            <i class="fa-solid fa-brush fa-2xs" style="color: #ffffff;"></i>
            Temas</button>
        <button class="btn btn-primary disabled"><i class="fa-solid fa-rotate-right fa-flip-horizontal fa-2xs " 
                style="color: #ffffff;"></i>
            Desfazer Tudo</button>


    </div>
    <select id="regionId" name="regionId">
        <option value="0" selected>Selecione uma região</option>
    </select>
    <button id="configButton" class="btn btn-primary disabled">
        <i class="fa-solid fa-gear" style="color: #ffffff;"></i>
    </button>
    <button id="voltarButton" class="btn btn-primary">
        <i class="fa-solid fa-arrow-left-long" style="color: #ffffff;"></i>
    </button>
</div>
