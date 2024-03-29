<div class="offcanvas offcanvas-start bg-primary text-white " tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">TerPaz Maps</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <hr>
        <ul class="nav nav-pills flex-column mb-auto ">
            <li class="nav-link d-flex align-items-center">
                <div class="me-2" style="width: 10px;">
                    <i class="fa-solid fa-location-dot fa-lg" style="color: #ffffff;"></i>
                </div>
                <a href="{{ route('index') }}" class="nav-link text-white">
                    Navegar
                </a>
            </li>
            <li class="nav-link d-flex align-items-center">
                <div class="me-2" style="width: 10px;">
                    <i class="fa-solid fa-chart-line fa-lg" style="color: #ffffff;"></i>
                </div>
                <a href="#" class="nav-link text-white">
                    Estatisticas
                </a>
            </li>
            <li class="nav-link d-flex align-items-center">
                <div class="me-2" style="width: 10px;">
                    <i class="fa-solid fa-info fa-lg" style="color: #ffffff;"></i>
                </div>
                <a href="#" class="nav-link text-white">
                    Sobre
                </a>
            </li>
            <li class="nav-link d-flex align-items-center">
                <div class="me-2" style="width: 10px;">
                    <i class="fa-solid fa-bars fa-lg" style="color: #ffffff;"></i>
                </div>
                <a href="#" class="nav-link text-white">
                    Creditos
                </a>
            </li>
        </ul>

        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-regular fa-circle-user" style="color: #ffffff;"></i>
                <strong>Teste Da Silva</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Favoritos</a></li>
                <li><a class="dropdown-item" href="#">Configurações</a></li>
                <li><a class="dropdown-item" href="#">perfil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="#">Sair</a></li>
            </ul>
        </div>
    </div>