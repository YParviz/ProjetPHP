<?php
function renderNavbar(): void
{
    echo '
    <nav class="navbar">
        <div class="navbar__logo">
            <img src="/DebatArena/src/web/logo.png" alt="Debate Arena Logo" class="navbar__logo-img">
            <span>Debate Arena</span>
        </div>
        <div class="navbar__search">
    <input type="text" placeholder="Rechercher un débat..." />
    <button>
        <img src="/DebatArena/src/web/loupe.png" alt="Loupe" class="search-icon" />
    </button>
</div>
        <div class="navbar__actions">
            <button class="create-debate">+ Créer un débat</button>
            <div class="user-icon">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
    </nav>
    ';
}
