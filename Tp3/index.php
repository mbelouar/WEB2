<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Pétitions</title>
    <link rel="stylesheet" href="IHM/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="IHM/main.js"></script>
</head>
<body>
    <header>
        <h1>Système de Pétitions</h1>
        <nav>
            <button id="btnNouvellePetition">Nouvelle Pétition</button>
        </nav>
    </header>

    <main>
        <section id="listePetitions">
            <!-- Les pétitions seront chargées ici dynamiquement via AJAX -->
        </section>

        <!-- Modal pour nouvelle pétition -->
        <div id="modalPetition" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Créer une nouvelle pétition</h2>
                <form id="formPetition">
                    <div>
                        <label for="titre">Titre:</label>
                        <input type="text" id="titre" name="titre" required>
                    </div>
                    <div>
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    <div>
                        <label for="dateFin">Date de fin:</label>
                        <input type="date" id="dateFin" name="dateFin" required>
                    </div>
                    <div>
                        <button type="submit">Créer</button>
                        <button type="button" class="btnFermer">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal pour signature -->
        <div id="modalSignature" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Signer la pétition</h2>
                <form id="formSignature">
                    <input type="hidden" id="petitionId" name="petitionId">
                    <div>
                        <label for="nom">Nom:</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>
                    <div>
                        <label for="prenom">Prénom:</label>
                        <input type="text" id="prenom" name="prenom" required>
                    </div>
                    <div>
                        <label for="pays">Pays:</label>
                        <input type="text" id="pays" name="pays" required>
                    </div>
                    <div>
                        <button type="submit">Signer</button>
                        <button type="button" class="btnFermer">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html> 