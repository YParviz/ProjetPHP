<?php
function renderNavbar(): void
{
    // Vérifier si l'utilisateur est connecté
    $isUserLoggedIn = isset($_SESSION['user']);

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
        <div class="navbar__actions">';

    // Bouton "Ajouter un débat" (Redirige vers /debat/creer si connecté, sinon vers /login)
    $debateUrl = $isUserLoggedIn ? "/debat/creer" : "/login";
    echo '
        <a href="' . $debateUrl . '" class="btn-create-debate">
            Ajouter un débat
        </a>';

    // Icône de connexion
    echo '
        <a href="/profile" class="login-button">
            <img src="/image/profil.svg">
        </a>
    </div></nav>';
}
?>
