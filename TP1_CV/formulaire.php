<!DOCTYPE html>
<html>
    <head>
        <title>GENERATOR</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
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
                            <input id="firstname" type="text" name="firstname" value="<?php echo $_POST['firstname'] ?? ''; ?>" class="form-control" placeholder="Votre prénom" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="form-label">Nom <span class="blue">*</span></label>
                            <input id="name" type="text" name="name" value="<?php echo $_POST['name'] ?? ''; ?>" class="form-control" placeholder="Votre Nom" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email <span class="blue">*</span></label>
                            <input id="email" type="text" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" class="form-control" placeholder="Votre Email" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="phone" class="form-label">Téléphone <span class="blue">*</span></label>
                            <input id="phone" type="text" name="phone" value="<?php echo $_POST['phone'] ?? ''; ?>" class="form-control" placeholder="Votre Téléphone" required>
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                            <label for="age" class="form-label">Age <span class="blue">*</span></label>
                            <input id="age" type="text" name="age" value="<?php echo $_POST['age'] ?? ''; ?>" class="form-control" placeholder="Votre Age" required>
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
                            <!-- keep the selected value after form submission -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="2AP" id="formation-2AP"
                                <?php echo ($_POST['formation'] ?? '') == '2AP' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="formation-2AP">1AP</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GSTR" id="formation-GSTR"
                                <?php echo ($_POST['formation'] ?? '') == 'GSTR' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="formation-GSTR">GSTR</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GI" id="formation-GI"
                                <?php echo ($_POST['formation'] ?? '') == 'GI' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="formation-GI">GI</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="SCM" id="formation-SCM"
                                <?php echo ($_POST['formation'] ?? '') == 'SCM' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="formation-SCM">SCM</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GC" id="formation-GC"
                                <?php echo ($_POST['formation'] ?? '') == 'GC' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="formation-GC">GC</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="MS" id="formation-MS"
                                <?php echo ($_POST['formation'] ?? '') == 'MS' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="formation-MS">MS</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_1" id="niveau_1"
                                <?php echo ($_POST['niveau'] ?? '') == 'niveau_1' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="niveau_1">1ere annee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_2" id="niveau_2"
                                <?php echo ($_POST['niveau'] ?? '') == 'niveau_2' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="niveau_2">2eme annee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_3" id="niveau_3"
                                <?php echo ($_POST['niveau'] ?? '') == 'niveau_3' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="niveau_3">3eme annee</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label for="formation" class="form-label">Modules suivies cette annee: <span class="blue">*</span></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="proAv" id="proAv"
                                <?php echo in_array('proAv', $_POST['modules'] ?? []) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="proAv">Pro Av</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="compilation" id="compilation"
                                <?php echo in_array('compilation', $_POST['modules'] ?? []) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="compilation">Compilation</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="reseauxAv" id="reseauxAv"
                                <?php echo in_array('reseauxAv', $_POST['modules'] ?? []) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="reseauxAv">Reseaux Av</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="webAv" id="webAv"
                                <?php echo in_array('webAv', $_POST['modules'] ?? []) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="webAv">Web Avancee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="poo" id="poo"
                                <?php echo in_array('poo', $_POST['modules'] ?? []) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="poo">POO</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="bd" id="bd"
                                <?php echo in_array('bd', $_POST['modules'] ?? []) ? 'checked' : ''; ?>>
                                <label4 class="form-check-label" for="bd">BD</label4>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label for="project" class="form-label">Nombre de projets réalisés cette année: <span class="blue">*</span></label>
                            <select id="project" name="project" class="form-control" onchange="generateProjectFields()" required>
                                <?php
                                $selectedProjectCount = $_POST['project'] ?? 0;
                                for ($i = 0; $i <= 5; $i++) {
                                    $selected = ($selectedProjectCount == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Container for dynamic fields -->
                        <div id="projectFields" class="mt-3"></div>
                    </div>
                </div>

                <!-- Stage -->
                <div class="section">
                    <div class="section-title">Stages</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="stage" class="form-label">Nombre de stages réalisés cette année: <span class="blue">*</span></label>
                            <select id="stage" name="stage" class="form-control" onchange="generateStages()" required>
                                <?php
                                $selectedStageCount = $_POST['stage'] ?? 0;
                                for ($i = 0; $i <= 5; $i++) {
                                    $selected = ($selectedStageCount == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Container for dynamic fields -->
                        <div id="stageFields" class="mt-3"></div>

                    </div>
                </div>

                <!-- Experiences -->
                <div class="section">
                    <div class="section-title">Experiences</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="experience" class="form-label">Nombre des experiences: <span class="blue">*</span></label>
                            <select id="experience" name="experience" class="form-control" onchange="generateExperience()" required>
                                <?php
                                $selectedExperienceCount = $_POST['stage'] ?? 0;
                                for ($i = 0; $i <= 5; $i++) {
                                    $selected = ($selectedExperienceCount == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Container for dynamic fields -->
                        <div id="experienceFields" class="mt-3"></div>

                    </div>
                </div>

                <!-- Competences -->
                <div class="section">
                    <div class="section-title">Competences</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="competence1" class="form-label">Competence 1: <span class="blue">*</span></label>
                            <input id="competence1" type="text" name="competence1" value="<?php echo $_POST['competence1'] ?? ''; ?>" class="form-control" placeholder="Entrez votre competence" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="competence2" class="form-label">Competence 2: <span class="blue">*</span></label>
                            <input id="competence2" type="text" name="competence2" value="<?php echo $_POST['competence2'] ?? ''; ?>" class="form-control" placeholder="Entrez votre competence" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="competence3" class="form-label">Competence 3:</label>
                            <input id="competence3" type="text" name="competence3" value="<?php echo $_POST['competence3'] ?? ''; ?>" class="form-control" placeholder="Entrez votre competence">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="competence4" class="form-label">Competence 4:</label>
                            <input id="competence4" type="text" name="competence4" value="<?php echo $_POST['competence4'] ?? ''; ?>" class="form-control" placeholder="Entrez votre competence">
                        </div>
                    </div>
                </div>

                <!-- Centre d'interet -->
                <div class="section">
                    <div class="section-title">Centre d'intérêt</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="interest1" class="form-label">Centre d'intérêt 1: <span class="blue">*</span></label>
                            <input id="interest1" type="text" name="interest1" value="<?php echo $_POST['interest1'] ?? ''; ?>" class="form-control" placeholder="Entrez votre intérêt" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="interest2" class="form-label">Centre d'intérêt 2: <span class="blue">*</span></label>
                            <input id="interest2" type="text" name="interest2" value="<?php echo $_POST['interest2'] ?? ''; ?>" class="form-control" placeholder="Entrez votre intérêt" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="interest3" class="form-label">Centre d'intérêt 3:</label>
                            <input id="interest3" type="text" name="interest3" value="<?php echo $_POST['interest3'] ?? ''; ?>" class="form-control" placeholder="Entrez votre intérêt">
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label for="interest4" class="form-label">Centre d'intérêt 4:</label>
                            <input id="interest4" type="text" name="interest4" value="<?php echo $_POST['interest4'] ?? ''; ?>" class="form-control" placeholder="Entrez votre intérêt">
                        </div>
                    </div>
                </div>

                <!-- Langues -->
                <div class="section">
                    <div class="section-title">Langues</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="langue1" class="form-label">Langue 1: <span class="blue">*</span></label>
                            <input id="langue1" type="text" name="langue1" value="<?php echo $_POST['langue1'] ?? ''; ?>" class="form-control" placeholder="Entrez la langue" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="niveau1" class="form-label">Niveau: <span class="blue">*</span></label>
                            <select id="niveau1" name="niveau1" class="form-control" required>
                                <option value="debutant" <?php echo (isset($_POST['niveau1']) && $_POST['niveau1'] == 'debutant') ? 'selected' : ''; ?>>Débutant</option>
                                <option value="intermediaire" <?php echo (isset($_POST['niveau1']) && $_POST['niveau1'] == 'intermediaire') ? 'selected' : ''; ?>>Intermédiaire</option>
                                <option value="avance" <?php echo (isset($_POST['niveau1']) && $_POST['niveau1'] == 'avance') ? 'selected' : ''; ?>>Avancé</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mt-2">
                            <label for="langue2" class="form-label">Langue 2: <span class="blue">*</span></label>
                            <input id="langue2" type="text" name="langue2" value="<?php echo $_POST['langue2'] ?? ''; ?>" class="form-control" placeholder="Entrez la langue" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="niveau2" class="form-label">Niveau: <span class="blue">*</span></label>
                            <select id="niveau2" name="niveau2" class="form-control" required>
                                <option value="debutant" <?php echo (isset($_POST['niveau2']) && $_POST['niveau2'] == 'debutant') ? 'selected' : ''; ?>>Débutant</option>
                                <option value="intermediaire" <?php echo (isset($_POST['niveau2']) && $_POST['niveau2'] == 'intermediaire') ? 'selected' : ''; ?>>Intermédiaire</option>
                                <option value="avance" <?php echo (isset($_POST['niveau2']) && $_POST['niveau2'] == 'avance') ? 'selected' : ''; ?>>Avancé</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mt-2">
                            <label for="langue3" class="form-label">Langue 3:</label>
                            <input id="langue3" type="text" name="langue3" value="<?php echo $_POST['langue3'] ?? ''; ?>" class="form-control" placeholder="Entrez la langue">
                        </div>
                        <div class="col-lg-6">
                            <label for="niveau3" class="form-label">Niveau: <span class="blue">*</span></label>
                            <select id="niveau3" name="niveau3" class="form-control" required>
                                <option value="debutant" <?php echo (isset($_POST['niveau3']) && $_POST['niveau3'] == 'debutant') ? 'selected' : ''; ?>>Débutant</option>
                                <option value="intermediaire" <?php echo (isset($_POST['niveau3']) && $_POST['niveau3'] == 'intermediaire') ? 'selected' : ''; ?>>Intermédiaire</option>
                                <option value="avance" <?php echo (isset($_POST['niveau3']) && $_POST['niveau3'] == 'avance') ? 'selected' : ''; ?>>Avancé</option>
                            </select>
                        </div>

                    </div>
                </div>


                <!-- Remarques -->
                <div class="section">
                    <div class="section-title">Vos Remarques</div>
                    <div class="row">
                        <div class="">
                        <textarea id="message" name="message" class="form-control" placeholder="Votre message"><?php 
                            echo isset($_POST['message']) ? htmlspecialchars(trim(preg_replace('/\s+/', ' ', $_POST['message']))) : ''; 
                        ?></textarea>
                        </div>
                        <!-- upload file -->
                        <div class="col-lg-12">
                            <label for="file" class="form-label">Choisir un fichier</label>
                            <input type="file" name="file" class="" required>
                        </div>
                    </div>
                </div>

                <p id="error-message" style="color: red; display: none;">Veuillez sélectionner au moins un module.</p>

                <!-- Hidden input to store previously entered project names -->
                <input type="hidden" id="savedProjects" value='<?php echo json_encode($_POST['projectNames'] ?? []); ?>'>

                <div class="d-flex justify-content-between mt-3">
                    <button type="submit" onclick="return validateCheckboxes()" class="button-env btn btn-primary">Envoyer</button>
                    <button type="button" id="generate-btn" class="button-gen btn btn-success">Générer</button>
                </div>


            </form>
        </div>


        <script src="script.js"></script>
        <script> 
            document.addEventListener('DOMContentLoaded', function() {
                // Get the saved project names and generate the fields
                generateProjectFields();
            });
        </script>
    </body>
</html>
