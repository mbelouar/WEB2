<!DOCTYPE html>
<html>
    <head>
        <title>GENERATOR</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <div class="divider"></div>
            <div class="heading">
                <h2>Generateur de CV</h2>
            </div>
            <form id="contact-form" method="post" action="recap.php" role="form">
                
                <!-- Renseignement Personnel -->
                <div class="section">
                    <div class="section-title">Renseignement Personnel</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="firstname" class="form-label">Prénom <span class="blue">*</span></label>
                            <input id="firstname" type="text" name="firstname" class="form-control" placeholder="Votre prénom" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="form-label">Nom <span class="blue">*</span></label>
                            <input id="name" type="text" name="name" class="form-control" placeholder="Votre Nom" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email <span class="blue">*</span></label>
                            <input id="email" type="text" name="email" class="form-control" placeholder="Votre Email" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="phone" class="form-label">Téléphone <span class="blue">*</span></label>
                            <input id="phone" type="text" name="phone" class="form-control" placeholder="Votre Téléphone" required>
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                            <label for="age" class="form-label">Age <span class="blue">*</span></label>
                            <input id="age" type="text" name="age" class="form-control" placeholder="Votre Age" required>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Renseignement Académique -->
                <div class="section">
                    <div class="section-title">Renseignement Académique</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="formation" class="form-label">Vous êtes en: <span class="blue">*</span></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="2AP" id="formation-2AP" required>
                                <label class="form-check-label" for="formation-2AP">2AP</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GSTR" id="formation-GSTR" required>
                                <label class="form-check-label" for="formation-GSTR">GSTR</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GI" id="formation-GI" required>
                                <label class="form-check-label" for="formation-GI">GI</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="SCM" id="formation-SCM" required>
                                <label class="form-check-label" for="formation-SCM">SCM</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GC" id="formation-GC" required>
                                <label class="form-check-label" for="formation-GC">GC</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="MS" id="formation-MS" required>
                                <label class="form-check-label" for="formation-MS">MS</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_1" id="niveau_1" required>
                                <label class="form-check-label" for="niveau_1">1er annee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_2" id="niveau_2" required>
                                <label class="form-check-label" for="niveau_2">2eme annee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_3" id="niveau_3" required>
                                <label class="form-check-label" for="niveau_3">3eme annee</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label for="formation" class="form-label">Modules suivies cette annee: <span class="blue">*</span></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="proAv" id="proAv">
                                <label class="form-check-label" for="proAv">Pro Av</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="compilation" id="compilation">
                                <label class="form-check-label" for="compilation">Compilation</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="reseauxAv" id="reseauxAv">
                                <label class="form-check-label" for="reseauxAv">Reseaux Av</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="webAv" id="webAv">
                                <label class="form-check-label" for="webAv">Web Avancee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="poo" id="poo">
                                <label class="form-check-label" for="poo">POO</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="bd" id="bd">
                                <label class="form-check-label" for="bd">BD</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label for="project" class="form-label">Nombre de projets réalisés cette année: <span class="blue">*</span></label>
                            <select id="project" name="project" class="form-control" onchange="generateProjectFields()" required>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <!-- Container for dynamic fields -->
                        <div id="projectFields" class="mt-3"></div>
                    </div>
                </div>

                <!-- Centre d'interet -->
                <div class="section">
                    <div class="section-title">Centre d'intérêt</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="interest1" class="form-label">Centre d'intérêt 1: <span class="blue">*</span></label>
                            <input id="interest1" type="text" name="interest1" class="form-control" placeholder="Entrez votre intérêt" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="interest2" class="form-label">Centre d'intérêt 2: <span class="blue">*</span></label>
                            <input id="interest2" type="text" name="interest2" class="form-control" placeholder="Entrez votre intérêt" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="interest3" class="form-label">Centre d'intérêt 3:</label>
                            <input id="interest3" type="text" name="interest3" class="form-control" placeholder="Entrez votre intérêt">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="interest4" class="form-label">Centre d'intérêt 4:</label>
                            <input id="interest4" type="text" name="interest4" class="form-control" placeholder="Entrez votre intérêt">
                        </div>
                    </div>
                </div>

                <!-- Langues -->
                <div class="section">
                    <div class="section-title">Langues</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="langue1" class="form-label">Langue 1: <span class="blue">*</span></label>
                            <input id="langue1" type="text" name="langue1" class="form-control" placeholder="Entrez la langue" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="niveau1" class="form-label">Niveau: <span class="blue">*</span></label>
                            <select id="niveau1" name="niveau1" class="form-control" required>
                                <option value="debutant">Débutant</option>
                                <option value="intermediaire">Intermédiaire</option>
                                <option value="avance">Avancé</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="langue2" class="form-label">Langue 2: <span class="blue">*</span></label>
                            <input id="langue2" type="text" name="langue2" class="form-control" placeholder="Entrez la langue" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="niveau2" class="form-label">Niveau: <span class="blue">*</span></label>
                            <select id="niveau2" name="niveau2" class="form-control" required>
                                <option value="debutant">Débutant</option>
                                <option value="intermediaire">Intermédiaire</option>
                                <option value="avance">Avancé</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="langue3" class="form-label">Langue 3:</label>
                            <input id="langue3" type="text" name="langue3" class="form-control" placeholder="Entrez la langue">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="niveau3" class="form-label">Niveau:</label>
                            <select id="niveau3" name="niveau3" class="form-control">
                                <option value="debutant">Débutant</option>
                                <option value="intermediaire">Intermédiaire</option>
                                <option value="avance">Avancé</option>
                            </select>
                        </div>
                    </div>
                </div>


                <!-- Remarques -->
                <div class="section">
                    <div class="section-title">Vos Remarques</div>
                    <div class="row">
                        <div class="">
                            <textarea id="message" name="message" class="form-control" placeholder="Votre message"></textarea>
                        </div>
                        <!-- upload file -->
                        <div class="col-lg-12">
                            <label for="file" class="form-label">Choisir un fichier</label>
                            <input type="file" name="file" class="" required>
                        </div>
                    </div>
                </div>

                <p id="error-message" style="color: red; display: none;">Veuillez sélectionner au moins un module.</p>

                <div>
                    <input type="submit" onclick="return validateCheckboxes()" class="button1 btn btn-primary" value="Envoyer">
                </div>    

            </form>
        </div>


        <script src="script.js"></script>
    </body>
</html>
