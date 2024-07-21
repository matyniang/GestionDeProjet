function confirmLogout(event) {
    event.preventDefault();
    if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
        document.getElementById("logout-form").submit();
    }
}