
<link rel="stylesheet" href="style.css">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
</head>
<body>
    <div class="container">
        <h1>Formulaire</h1>
        <div class="content">
            <h2>Renseignement Personnels</h2>
            <form action="" method="post">
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="text" name="age" placeholder="Age" required>
                <input type="tel" name="telephone" placeholder="Téléphone" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit" name="add_email">Ajouter</button>
            </form>
            <h2>Renseignement Academique</h2>
            <form action="" method="post"></form>
                <label for="niveau">Niveau d'études:</label>
                <select name="niveau" id="niveau" required>
                    <option value="bac">Bac</option>
                    <option value="bac+2">Bac+2</option>
                    <option value="bac+3">Bac+3</option>
                    <option value="bac+5">Bac+5</option>
                </select>
                
                <label for="specialite">Spécialité:</label>
                <select name="specialite" id="specialite" required>
                    <option value="informatique">Informatique</option>
                    <option value="gestion">Gestion</option>
                    <option value="droit">Droit</option>
                    <option value="medecine">Médecine</option>
                </select>
                
                <label for="competences">Compétences:</label>
                <select name="competences[]" id="competences" multiple required>
                    <option value="programmation">Programmation</option>
                    <option value="gestion_projet">Gestion de projet</option>
                    <option value="communication">Communication</option>
                    <option value="design">Design</option>
                </select>
                
                <button type="submit" name="add_academic">Ajouter</button>
            </form> </div></select>
    </div>