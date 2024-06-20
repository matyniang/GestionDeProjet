document.addEventListener('DOMContentLoaded', async function () {
    try {
        const response = await fetch('../../public/ListUser.php');
        const result = await response.json();

        if (result.success) {
            const utilisateurs = result.utilisateurs;
            const tableBody = document.getElementById('utilisateurTableBody');
            utilisateurs.forEach(utilisateur => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${utilisateur.id}</td>
                    <td>${utilisateur.nom_complet}</td>
                    <td>${utilisateur.fonction}</td>
                    <td>${utilisateur.poste}</td>
                    <td>${utilisateur.statut}</td>
                    <td>${utilisateur.email}</td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            alert('Erreur: ' + result.message);
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
});

