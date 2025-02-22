<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$modules = $_SESSION['cv_data']['modules'] ?? [];
if (!is_array($modules)) {
    $modules = [$modules];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>GENERATOR</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="style.css">
    </head>
    <body >

        <div id="particles-js"></div>
        <div class="container">
            <div class="divider"></div>
            <div class="heading">
                <h2>Generateur de CV</h2>
            </div>
            <form id="contact-form" method="post" action="recap.php" enctype="multipart/form-data" role="form">
                
                <!-- Renseignement Personnel -->
                <div class="section">
                    <div class="section-title custom-header">Renseignement Personnel</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="firstname" class="form-label">Prénom <span class="blue">*</span></label>
                            <input id="firstname" type="text" name="firstname" value="<?php echo $_SESSION['cv_data']['firstname'] ?? ''; ?>" class="form-control" placeholder="Votre prénom" minlength="5" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="form-label">Nom <span class="blue">*</span></label>
                            <input id="name" type="text" name="name" value="<?php echo $_SESSION['cv_data']['lastname'] ?? ''; ?>" class="form-control" placeholder="Votre Nom" minlength="5" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="age" class="form-label">Age <span class="blue">*</span></label>
                            <input id="age" type="number" name="age" value="<?php echo $_SESSION['cv_data']['age'] ?? ''; ?>" class="form-control" placeholder="Votre Age" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email <span class="blue">*</span></label>
                            <input id="email" type="email" name="email" value="<?php echo $_SESSION['cv_data']['email'] ?? ''; ?>" class="form-control" placeholder="Votre Email" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="phone" class="form-label">Téléphone <span class="blue">*</span></label>
                            <input id="phone" type="tel" name="phone" value="<?php echo $_SESSION['cv_data']['phone'] ?? ''; ?>" 
                                class="form-control" placeholder="Votre Téléphone" required 
                                pattern="^[0-9]{10}$" 
                                title="Le numéro de téléphone doit comporter 10 chiffres.">
                        </div>
                        <div class="col-lg-6">
                            <label for="address" class="form-label">Adresse <span class="blue">*</span></label>
                            <input id="address" type="text" name="address" value="<?php echo $_SESSION['cv_data']['adresse'] ?? ''; ?>" class="form-control" placeholder="Votre Adresse" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="github" class="form-label">Github</label>
                            <input id="github" type="text" name="github" value="<?php echo $_SESSION['cv_data']['github'] ?? ''; ?>" class="form-control" placeholder="Votre Github">
                        </div>
                        <div class="col-lg-6">
                            <label for="linkedin" class="form-label">Linkedin</label>
                            <input id="linkedin" type="text" name="linkedin" value="<?php echo $_SESSION['cv_data']['linkedin'] ?? ''; ?>" class="form-control" placeholder="Votre Linkedin">
                        </div>
                    </div>
                </div>

                <!-- Renseignement Académique -->
                <div class="section">
                    <div class="section-title custom-header">Renseignement Académique</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="formation" class="form-label">Vous êtes en: <span class="blue">*</span></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="2AP" id="formation-2AP"
                                    <?php echo ($_SESSION['cv_data']['formation'] ?? '') == '2AP' ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-2AP">2AP</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GSTR" id="formation-GSTR"
                                    <?php echo ($_SESSION['cv_data']['formation'] ?? '') == 'GSTR' ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-GSTR">GSTR</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GI" id="formation-GI"
                                    <?php echo ($_SESSION['cv_data']['formation'] ?? '') == 'GI' ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-GI">GI</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="SCM" id="formation-SCM"
                                    <?php echo ($_SESSION['cv_data']['formation'] ?? '') == 'SCM' ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-SCM">SCM</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GC" id="formation-GC"
                                    <?php echo ($_SESSION['cv_data']['formation'] ?? '') == 'GC' ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-GC">GC</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="MS" id="formation-MS"
                                    <?php echo ($_SESSION['cv_data']['formation'] ?? '') == 'MS' ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-MS">MS</label>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_1" id="niveau_1"
                                    <?php echo ($_SESSION['cv_data']['niveau'] ?? '') == '1er année' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="niveau_1">1ere annee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_2" id="niveau_2"
                                    <?php echo ($_SESSION['cv_data']['niveau'] ?? '') == '2ème année' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="niveau_2">2eme annee</label>
                            </div>
                            <div class="form-check form-check-inline" id="niveau_3_container">
                                <input class="form-check-input" type="radio" name="niveau" value="niveau_3" id="niveau_3"
                                    <?php echo ($_SESSION['cv_data']['niveau'] ?? '') == '3ème année' ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="niveau_3">3eme annee</label>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <label for="formation" class="form-label">Modules suivies cette annee: <span class="blue">*</span></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="proAv" id="proAv"
                                <?php echo in_array('proAv', $modules) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="proAv">Pro Av</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="compilation" id="compilation"
                                <?php echo in_array('compilation', $modules) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="compilation">Compilation</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="reseauxAv" id="reseauxAv"
                                <?php echo in_array('reseauxAv', $modules) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="reseauxAv">Reseaux Avancee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="webAv" id="webAv"
                                <?php echo in_array('webAv', $modules) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="webAv">Web Avancee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="poo" id="poo"
                                <?php echo in_array('poo', $modules) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="poo">POO</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="modules[]" value="bd" id="bd"
                                <?php echo in_array('bd', $modules) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="bd">BD</label>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <label for="project" class="form-label">Nombre de projets réalisés cette année: <span class="blue">*</span></label>
                            <select id="project" name="project" class="form-control" onchange="generateProjectFields()" required>
                                <?php
                                $selectedProjectCount = $_SESSION['cv_data']['projectCount'] ?? 0;
                                for ($i = 0; $i <= 5; $i++) {
                                    $selected = ($selectedProjectCount == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div id="projectFields" class="mt-3"></div>
                    </div>
                </div>

                <!-- Stage -->
                <div class="section">
                    <div class="section-title custom-header">Stages</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="stage" class="form-label">Nombre de stages réalisés cette année: <span class="blue">*</span></label>
                            <select id="stage" name="stage" class="form-control" onchange="generateStages()" required>
                                <?php
                                $selectedStageCount = $_SESSION['cv_data']['stageCount'] ?? 0;
                                for ($i = 0; $i <= 5; $i++) {
                                    $selected = ($selectedStageCount == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        
                        <div id="stageFields" class="mt-3"></div>

                    </div>
                </div>

                <!-- Experiences -->
                <div class="section">
                    <div class="section-title custom-header">Experiences</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="experience" class="form-label">Nombre des experiences: <span class="blue">*</span></label>
                            <select id="experience" name="experience" class="form-control" onchange="generateExperience()" required>
                                <?php
                                $selectedExperienceCount = $_SESSION['cv_data']['experienceCount'] ?? 0;
                                for ($i = 0; $i <= 5; $i++) {
                                    $selected = ($selectedExperienceCount == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                       
                        <div id="experienceFields" class="mt-3"></div>

                    </div>
                </div>

                <!-- Competences -->
                <div class="section">
                    <div class="section-title custom-header">Compétences</div>
                    <div class="row" id="competenceContainer">
                        <?php
                        $competences = $_SESSION['cv_data']['competences'] ?? ["", ""]; // Default 2 inputs
                        foreach ($competences as $index => $competence) {
                            echo '
                            <div class="col-lg-6 mt-2">
                                <label class="form-label">Compétence ' . ($index + 1) . ':</label>
                                <input type="text" name="competences[]" value="' . htmlspecialchars($competence) . '" class="form-control" placeholder="Entrez votre compétence">
                            </div>';
                        }
                        ?>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn w-100 d-flex align-items-center justify-content-center custom-btn" id="addCompetence">
                            <i class="fa fa-plus me-2"></i> Ajouter
                        </button>
                    </div>
                </div>


                <!-- Centre d'interet -->
                <div class="section">
                    <div class="section-title custom-header">Centre d'intérêt</div>
                    <div class="row" id="interestContainer">
                        <?php
                        $interests = $_SESSION['cv_data']['interests'] ?? ["", ""]; // Default 2 fields
                        foreach ($interests as $index => $interest) {
                            echo '
                            <div class="col-lg-6 mt-2">
                                <label class="form-label">Centre d\'intérêt ' . ($index + 1) . ':</label>
                                <input type="text" name="interests[]" value="' . htmlspecialchars($interest) . '" class="form-control" placeholder="Entrez votre intérêt">
                            </div>';
                        }
                        ?>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn custom-btn w-100 d-flex align-items-center justify-content-center" id="addInterest">
                            <i class="fa fa-plus me-2"></i> Ajouter
                        </button>
                    </div>
                </div>


                <!-- Langues -->
                <div class="section">
                    <div class="section-title custom-header">Langues</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="langue1" class="form-label">Langue 1: <span class="blue">*</span></label>
                            <input id="langue1" type="text" name="langue1" value="<?php echo $_SESSION['cv_data']['langue1'] ?? ''; ?>" class="form-control" placeholder="Entrez la langue" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="niveau1" class="form-label">Niveau: <span class="blue">*</span></label>
                            <select id="niveau1" name="niveau1" class="form-control" required>
                                <option value="debutant" <?php echo (isset($_SESSION['cv_data']['niveau1']) && $_SESSION['cv_data']['niveau1'] == 'debutant') ? 'selected' : ''; ?>>Débutant</option>
                                <option value="intermediaire" <?php echo (isset($_SESSION['cv_data']['niveau1']) && $_SESSION['cv_data']['niveau1'] == 'intermediaire') ? 'selected' : ''; ?>>Intermédiaire</option>
                                <option value="avance" <?php echo (isset($_SESSION['cv_data']['niveau1']) && $_SESSION['cv_data']['niveau1'] == 'avance') ? 'selected' : ''; ?>>Avancé</option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="langue2" class="form-label">Langue 2: <span class="blue">*</span></label>
                            <input id="langue2" type="text" name="langue2" value="<?php echo $_SESSION['cv_data']['langue2'] ?? ''; ?>" class="form-control" placeholder="Entrez la langue" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="niveau2" class="form-label">Niveau: <span class="blue">*</span></label>
                            <select id="niveau2" name="niveau2" class="form-control" required>
                                <option value="debutant" <?php echo (isset($_SESSION['cv_data']['niveau2']) && $_SESSION['cv_data']['niveau2'] == 'debutant') ? 'selected' : ''; ?>>Débutant</option>
                                <option value="intermediaire" <?php echo (isset($_SESSION['cv_data']['niveau2']) && $_SESSION['cv_data']['niveau2'] == 'intermediaire') ? 'selected' : ''; ?>>Intermédiaire</option>
                                <option value="avance" <?php echo (isset($_SESSION['cv_data']['niveau2']) && $_SESSION['cv_data']['niveau2'] == 'avance') ? 'selected' : ''; ?>>Avancé</option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="langue3" class="form-label">Langue 3:</label>
                            <input id="langue3" type="text" name="langue3" value="<?php echo $_SESSION['cv_data']['langue3'] ?? ''; ?>" class="form-control" placeholder="Entrez la langue">
                        </div>
                        <div class="col-lg-6">
                            <label for="niveau3" class="form-label">Niveau: <span class="blue">*</span></label>
                            <select id="niveau3" name="niveau3" class="form-control" required>
                                <option value="debutant" <?php echo (isset($_SESSION['cv_data']['niveau3']) && $_SESSION['cv_data']['niveau3'] == 'debutant') ? 'selected' : ''; ?>>Débutant</option>
                                <option value="intermediaire" <?php echo (isset($_SESSION['cv_data']['niveau3']) && $_SESSION['cv_data']['niveau3'] == 'intermediaire') ? 'selected' : ''; ?>>Intermédiaire</option>
                                <option value="avance" <?php echo (isset($_SESSION['cv_data']['niveau3']) && $_SESSION['cv_data']['niveau3'] == 'avance') ? 'selected' : ''; ?>>Avancé</option>
                            </select>
                        </div>

                    </div>
                </div>


                <!-- Profile and picture -->
                <div class="section">
                    <div class="section-title custom-header">Profile</div>
                    <div class="row">
                        <div class="">
                            <textarea id="profile_desc" name="profile_desc" class="form-control" placeholder="Votre profile" required>
                                <?php 
                                    // Trim the session message to remove leading/trailing spaces
                                    echo isset($_SESSION['cv_data']['message']) ? trim($_SESSION['cv_data']['message']) : '';
                                ?>
                            </textarea>
                        </div>
                        <!-- upload the picture -->
                        <div class="col-lg-12">
                            <label for="picture" class="form-label">Choisir une photo de profile (JPG, PNG, JPEG) <span class="blue">*</span></label>
                            <input type="file" name="picture" class="" required>
                        </div>
                    </div>
                </div>


                <p id="error-message" style="color: red; <?= !empty($error_message) ? 'display: block;' : 'display: none;'; ?>">
                    <?= $error_message; ?>
                </p>
                
                <!-- Hidden input to store previously entered project names and desc -->
                <input type="hidden" id="savedProjects" value='
                <?php echo json_encode($_SESSION['cv_data']['projects'] ?? []); ?>'>
                <input type="hidden" id="savedDescriptions" name="savedDescriptions" value='
                <?php echo json_encode($_SESSION['cv_data']['projectDesc'] ?? []); ?>'>
                <input type="hidden" id="savedStartDate" name="savedStartDate" value='
                <?php echo json_encode($_SESSION['cv_data']['projectStartDate'] ?? []); ?>'>
                <input type="hidden" id="savedEndDate" name="savedEndDate" value='
                <?php echo json_encode($_SESSION['cv_data']['projectEndDate'] ?? []); ?>'>

                <!-- Hidden input to store previously entered stage names and desc -->
                <input type="hidden" id="savedStages" name="savedStages" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['stages'] ?? [])) ?>'>
                <input type="hidden" id="savedStageDescriptions" name="savedStageDescriptions" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['stageDesc'] ?? [])) ?>'>
                <input type="hidden" id="savedStageStartDate" name="savedStageStartDate" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['stageStartDate'] ?? [])) ?>'>
                <input type="hidden" id="savedStageEndDate" name="savedStageEndDate" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['stageEndDate'] ?? [])) ?>'>
                <input type="hidden" id="savedStageEntreprises" name="savedStageEntreprises" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['stageEntreprise'] ?? [])) ?>'>
                <input type="hidden" id="savedStageLocations" name="savedStageLocations" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['stageLocation'] ?? [])) ?>'>

                <!-- Hidden input to store previously entered experience names and desc -->
                <input type="hidden" id="savedExperiences" name="savedExperiences" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['experiences'] ?? [])) ?>'>
                <input type="hidden" id="savedExperienceDescriptions" name="savedExperienceDescriptions" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['experienceDesc'] ?? [])) ?>'>
                <input type="hidden" id="savedExperienceStartDate" name="savedExperienceStartDate" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['experienceStartDate'] ?? [])) ?>'>
                <input type="hidden" id="savedExperienceEndDate" name="savedExperienceEndDate" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['experienceEndDate'] ?? [])) ?>'>
                <input type="hidden" id="savedExperienceEntreprises" name="savedExperienceEntreprises" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['experienceEntreprise'] ?? [])) ?>'>
                <input type="hidden" id="savedExperienceLocations" name="savedExperienceLocations" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['experienceLocation'] ?? [])) ?>'>
                <input type="hidden" id="savedExperiencePositions" name="savedExperiencePositions" value='
                <?= htmlspecialchars(json_encode($_SESSION['cv_data']['experiencePosition'] ?? [])) ?>'>


                <div class="d-flex justify-content-between mt-3">
                    <button type="submit" class="button-env btn btn-primary">Envoyer</button>
                </div>
                        

            </form>
            <?php session_destroy(); ?>
        </div>

        <script src="particles.js"></script>
        <script src="script.js"></script>
    </body>
</html>
