document.addEventListener('DOMContentLoaded', async function () {
    try {
        const response = await fetch('../../public/ListProjet.php');
        const result = await response.json();

        if (result.success) {
            const projets = result.projets;
            const tableBody = document.getElementById('projetTableBody');
            projets.forEach(projet => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${projet.id}</td>
                    <td>${projet.nom_projet}</td>
                    <td>${projet.description1}</td>
                    <td>${projet.datedebut}</td>
                    <td>${projet.datefin}</td>
                    <td>${projet.statut}</td>
                    <td>${projet.type_de_projet}</td>
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

