<?php
function renderNavbar(): void
{
    echo '
    <link rel="stylesheet" href="/css/navbar.css">
    <nav class="navbar">
        <div class="navbar__logo">
            <a href="/">
                <img src="/logo.png" alt="Debate Arena Logo" class="navbar__logo-img">
                <span>Debate Arena</span>
            </a>
        </div>
        <div class="navbar__search">
            <input type="text" placeholder="Rechercher un débat..." />
            <button>
                <img src="/loupe.png" alt="Loupe" class="search-icon" />
            </button>
        </div>
        <div class="navbar__actions">
            <button class="create-debate">
                Créer un débat
            </button>
            <div class="user-icon">
                <a href="/profil">
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>
    </nav>
    ';
}
