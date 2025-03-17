<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if this is a direct access (not from edit page)
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'edit_student.php') === false) {
    // If not coming from edit page, clear all session data
    if (!isset($_SESSION['preserve_edit_mode']) || $_SESSION['preserve_edit_mode'] !== true) {
        $_SESSION['edit_mode'] = false;
        $_SESSION['cv_data'] = [];
    }
    // Reset the preserve flag
    $_SESSION['preserve_edit_mode'] = false;
}

// Initialize cv_data if not set
if (!isset($_SESSION['cv_data'])) {
    $_SESSION['cv_data'] = [];
}

// Check if we're in edit mode
$isEditMode = isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'] === true && isset($_SESSION['cv_data']['user_id']);

// Debug log
error_log('Form Mode: ' . ($isEditMode ? 'EDIT' : 'CREATE'));
if ($isEditMode) {
    error_log('User ID: ' . $_SESSION['cv_data']['user_id']);
    error_log('Session Data: ' . print_r($_SESSION['cv_data'], true));
}

// If in edit mode and we don't have the data, fetch it from the database
if ($isEditMode && empty($_SESSION['cv_data'])) {
    $conn = connectDB();
    $userId = $_SESSION['cv_data']['user_id'];
    
    // Get user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $_SESSION['cv_data'] = [
            'user_id' => $userId,
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'age' => $user['age'],
            'adresse' => $user['address'],
            'github' => $user['github'] ?? '',
            'linkedin' => $user['linkedin'] ?? '',
            'message' => $user['profile_desc'] ?? '',
            'picture' => $user['picture_path'] ?? ''
        ];
        
        // Get academic info
        $stmt = $conn->prepare("SELECT * FROM academic_info WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($academic = $result->fetch_assoc()) {
            $_SESSION['cv_data']['formation'] = $academic['formation'];
            $_SESSION['cv_data']['niveau'] = $academic['niveau'];
            
            // Get modules
            $stmt = $conn->prepare("SELECT module_name FROM modules WHERE academic_id = ?");
            $stmt->bind_param("i", $academic['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $modules = [];
            while ($row = $result->fetch_assoc()) {
                $modules[] = $row['module_name'];
            }
            $_SESSION['cv_data']['modules'] = $modules;
        }
        
        // Get other data (projects, experiences, etc.)
        // ... Add similar code for other tables ...
    }
    $stmt->close();
    $conn->close();
    
    error_log('Loaded edit data from database: ' . print_r($_SESSION['cv_data'], true));
}

$modules = $_SESSION['cv_data']['modules'] ?? [];
if (!is_array($modules)) {
    $modules = [$modules];
}

// Debug log for profile description and niveau
error_log('Profile Description (message): ' . getSessionValue('message'));
error_log('Profile Description (profile_desc): ' . getSessionValue('profile_desc'));
error_log('Niveau: ' . getSessionValue('niveau'));

// Helper function to get session value
function getSessionValue($field, $default = '') {
    error_log("Getting session value for field: " . $field . ", value: " . (isset($_SESSION['cv_data'][$field]) ? $_SESSION['cv_data'][$field] : 'not set'));
    return isset($_SESSION['cv_data'][$field]) ? $_SESSION['cv_data'][$field] : $default;
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
        <link rel="stylesheet" href="/WEB2/TP1_CV/style.css">
    </head>
    <body >

        <!-- <div id="particles-js"></div> -->
        <div class="container">
            <?php if ($isEditMode): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <a href="ADMIN/index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à l'administration
                    </a>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> Vous êtes en mode édition pour l'utilisateur #<?php echo $_SESSION['cv_data']['user_id']; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <div class="divider"></div>
            <div class="heading">
                <h2><?php echo $isEditMode ? 'Modifier le CV' : 'Generateur de CV'; ?></h2>
            </div>
            <form id="contact-form" method="post" action="recap.php" enctype="multipart/form-data" role="form">
                <!-- Add hidden fields to track edit mode and user ID -->
                <?php if ($isEditMode && isset($_SESSION['cv_data']['user_id'])): ?>
                <input type="hidden" name="edit_mode" value="1">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['cv_data']['user_id']; ?>">
                <script>
                    console.log("Form is in edit mode for user ID: <?php echo $_SESSION['cv_data']['user_id']; ?>");
                </script>
                <?php else: ?>
                <script>
                    console.log("Form is in create mode");
                </script>
                <?php endif; ?>
                
                <!-- Renseignement Personnel -->
                <div class="section">
                    <div class="section-title custom-header">Renseignement Personnel</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="firstname" class="form-label">Prénom <span class="blue">*</span></label>
                            <input id="firstname" type="text" name="firstname" value="<?php echo getSessionValue('firstname'); ?>" class="form-control" placeholder="Votre prénom" minlength="5" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="form-label">Nom <span class="blue">*</span></label>
                            <input id="name" type="text" name="lastname" value="<?php echo getSessionValue('lastname'); ?>" class="form-control" placeholder="Votre Nom" minlength="5" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="age" class="form-label">Age <span class="blue">*</span></label>
                            <input id="age" type="number" name="age" value="<?php echo getSessionValue('age'); ?>" class="form-control" placeholder="Votre Age" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email <span class="blue">*</span></label>
                            <input id="email" type="email" name="email" value="<?php echo getSessionValue('email'); ?>" class="form-control" placeholder="Votre Email" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="phone" class="form-label">Téléphone <span class="blue">*</span></label>
                            <input id="phone" type="tel" name="phone" value="<?php echo getSessionValue('phone'); ?>" 
                                class="form-control" placeholder="Votre Téléphone" required 
                                pattern="^[0-9]{10}$" 
                                title="Le numéro de téléphone doit comporter 10 chiffres.">
                        </div>
                        <div class="col-lg-6">
                            <label for="address" class="form-label">Adresse <span class="blue">*</span></label>
                            <input id="address" type="text" name="adresse" value="<?php echo getSessionValue('adresse'); ?>" class="form-control" placeholder="Votre Adresse" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="github" class="form-label">Github</label>
                            <input id="github" type="text" name="github" value="<?php echo getSessionValue('github'); ?>" class="form-control" placeholder="Votre Github">
                        </div>
                        <div class="col-lg-6">
                            <label for="linkedin" class="form-label">Linkedin</label>
                            <input id="linkedin" type="text" name="linkedin" value="<?php echo getSessionValue('linkedin'); ?>" class="form-control" placeholder="Votre Linkedin">
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
                                    <?php echo (getSessionValue('formation') == '2AP') ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-2AP">2AP</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GSTR" id="formation-GSTR"
                                    <?php echo (getSessionValue('formation') == 'GSTR') ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-GSTR">GSTR</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GI" id="formation-GI"
                                    <?php echo (getSessionValue('formation') == 'GI') ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-GI">GI</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="SCM" id="formation-SCM"
                                    <?php echo (getSessionValue('formation') == 'SCM') ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-SCM">SCM</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="GC" id="formation-GC"
                                    <?php echo (getSessionValue('formation') == 'GC') ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-GC">GC</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="formation" value="MS" id="formation-MS"
                                    <?php echo (getSessionValue('formation') == 'MS') ? 'checked' : ''; ?> required onchange="toggleNiveauOptions()">
                                <label class="form-check-label" for="formation-MS">MS</label>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-check form-check-inline">
                                <?php $niveau = getSessionValue('niveau'); error_log("Current niveau value: " . $niveau); ?>
                                <input class="form-check-input" type="radio" name="niveau" value="1ere annee" id="niveau_1"
                                    <?php echo (strtolower($niveau) == '1ere annee' || strtolower($niveau) == '1ère année') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="niveau_1">1ere annee</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="niveau" value="2eme annee" id="niveau_2"
                                    <?php echo (strtolower($niveau) == '2eme annee' || strtolower($niveau) == '2ème année') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="niveau_2">2eme annee</label>
                            </div>
                            <div class="form-check form-check-inline" id="niveau_3_container">
                                <input class="form-check-input" type="radio" name="niveau" value="3eme annee" id="niveau_3"
                                    <?php echo (strtolower($niveau) == '3eme annee' || strtolower($niveau) == '3ème année') ? 'checked' : ''; ?> required>
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
                                $selectedProjectCount = getSessionValue('projectCount', 0);
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
                                $selectedStageCount = getSessionValue('stageCount', 0);
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
                                $selectedExperienceCount = getSessionValue('experienceCount', 0);
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
                        $competences = getSessionValue('competences', ["", ""]); // Default 2 inputs
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
                        $interests = getSessionValue('interests', ["", ""]); // Default 2 fields
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
                            <input id="langue1" type="text" name="langue1" value="<?php echo getSessionValue('langue1'); ?>" class="form-control" placeholder="Entrez la langue" required>
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
                            <input id="langue2" type="text" name="langue2" value="<?php echo getSessionValue('langue2'); ?>" class="form-control" placeholder="Entrez la langue" required>
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
                            <input id="langue3" type="text" name="langue3" value="<?php echo getSessionValue('langue3'); ?>" class="form-control" placeholder="Entrez la langue">
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
                            <textarea id="profile_desc" name="profile_desc" class="form-control" placeholder="Votre profile" required><?php 
                                $profile_text = getSessionValue('profile_desc');
                                if (empty($profile_text)) {
                                    $profile_text = getSessionValue('message');
                                }
                                echo htmlspecialchars($profile_text);
                            ?></textarea>
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

                <div class="section">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (isset($_POST['edit_mode']) || (isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'] === true && isset($_SESSION['cv_data']['user_id']))): ?>
                                <input type="hidden" name="edit_mode" value="1">
                                <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['cv_data']['user_id']) ? $_SESSION['cv_data']['user_id'] : ''; ?>">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mettre à jour le CV</button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Créer mon CV</button>
                            <?php endif; ?>
                            
                            <button type="button" class="btn btn-secondary" onclick="customReset()"><i class="fas fa-undo"></i> Réinitialiser</button>
                        </div>
                    </div>
                </div>
            </form>
            <?php 
            // Don't destroy the session, just clear non-essential data
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        </div>

        <script src="/WEB2/TP1_CV/particles.js?v=<?php echo time(); ?>"></script>
        <script src="/WEB2/TP1_CV/script.js?v=<?php echo time(); ?>"></script>
        
        <script>
        // Custom reset function that preserves personal information
        function customReset() {
            // Store personal information
            const personalInfo = {
                firstname: document.getElementById('firstname').value,
                name: document.getElementById('name').value,
                age: document.getElementById('age').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                github: document.getElementById('github').value,
                linkedin: document.getElementById('linkedin').value
            };

            // Reset the form
            document.getElementById('contact-form').reset();

            // Restore personal information
            document.getElementById('firstname').value = personalInfo.firstname;
            document.getElementById('name').value = personalInfo.name;
            document.getElementById('age').value = personalInfo.age;
            document.getElementById('email').value = personalInfo.email;
            document.getElementById('phone').value = personalInfo.phone;
            document.getElementById('address').value = personalInfo.address;
            document.getElementById('github').value = personalInfo.github;
            document.getElementById('linkedin').value = personalInfo.linkedin;

            // Clear all dynamic fields
            document.getElementById('projectFields').innerHTML = '';
            document.getElementById('stageFields').innerHTML = '';
            document.getElementById('experienceFields').innerHTML = '';
            
            // Reset all select dropdowns to 0
            document.getElementById('project').value = '0';
            document.getElementById('stage').value = '0';
            document.getElementById('experience').value = '0';
            
            // Reset competence and interest containers to default state
            document.getElementById('competenceContainer').innerHTML = `
                <div class="col-lg-6 mt-2">
                    <label class="form-label">Compétence 1:</label>
                    <input type="text" name="competences[]" class="form-control" placeholder="Entrez votre compétence">
                </div>
                <div class="col-lg-6 mt-2">
                    <label class="form-label">Compétence 2:</label>
                    <input type="text" name="competences[]" class="form-control" placeholder="Entrez votre compétence">
                </div>
            `;
            
            document.getElementById('interestContainer').innerHTML = `
                <div class="col-lg-6 mt-2">
                    <label class="form-label">Centre d'intérêt 1:</label>
                    <input type="text" name="interests[]" class="form-control" placeholder="Entrez votre intérêt">
                </div>
                <div class="col-lg-6 mt-2">
                    <label class="form-label">Centre d'intérêt 2:</label>
                    <input type="text" name="interests[]" class="form-control" placeholder="Entrez votre intérêt">
                </div>
            `;
            
            // Clear all hidden inputs
            document.getElementById('savedProjects').value = '[]';
            document.getElementById('savedDescriptions').value = '[]';
            document.getElementById('savedStartDate').value = '[]';
            document.getElementById('savedEndDate').value = '[]';
            document.getElementById('savedStages').value = '[]';
            document.getElementById('savedStageDescriptions').value = '[]';
            document.getElementById('savedStageStartDate').value = '[]';
            document.getElementById('savedStageEndDate').value = '[]';
            document.getElementById('savedStageEntreprises').value = '[]';
            document.getElementById('savedStageLocations').value = '[]';
            document.getElementById('savedExperiences').value = '[]';
            document.getElementById('savedExperienceDescriptions').value = '[]';
            document.getElementById('savedExperienceStartDate').value = '[]';
            document.getElementById('savedExperienceEndDate').value = '[]';
            document.getElementById('savedExperienceEntreprises').value = '[]';
            document.getElementById('savedExperienceLocations').value = '[]';
            document.getElementById('savedExperiencePositions').value = '[]';
            
            // Clear file input
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.value = '';
            }
            
            // Reset formation radio buttons
            const formationInputs = document.querySelectorAll('input[name="formation"]');
            formationInputs.forEach(input => input.checked = false);
            
            // Reset niveau radio buttons
            const niveauInputs = document.querySelectorAll('input[name="niveau"]');
            niveauInputs.forEach(input => input.checked = false);
            
            // Reset all module checkboxes
            const moduleCheckboxes = document.querySelectorAll('input[name="modules[]"]');
            moduleCheckboxes.forEach(checkbox => checkbox.checked = false);
            
            // Reset language fields
            document.getElementById('langue1').value = '';
            document.getElementById('langue2').value = '';
            document.getElementById('langue3').value = '';
            document.getElementById('niveau1').value = 'debutant';
            document.getElementById('niveau2').value = 'debutant';
            document.getElementById('niveau3').value = 'debutant';
            
            // Reset profile description
            document.getElementById('profile_desc').value = '';
        }

        // Initialize particles.js with inline configuration
        document.addEventListener("DOMContentLoaded", function() {
            try {
                // Inline particles configuration to avoid path issues
                const particlesConfig = {
                    "particles": {
                        "number": {"value": 80, "density": {"enable": true, "value_area": 800}},
                        "color": {"value": "#ffffff"},
                        "shape": {
                            "type": "circle",
                            "stroke": {"width": 0, "color": "#000000"},
                            "polygon": {"nb_sides": 5}
                        },
                        "opacity": {
                            "value": 0.5, "random": false,
                            "anim": {"enable": false, "speed": 1, "opacity_min": 0.1, "sync": false}
                        },
                        "size": {
                            "value": 10, "random": true,
                            "anim": {"enable": false, "speed": 80, "size_min": 0.1, "sync": false}
                        },
                        "line_linked": {"enable": true, "distance": 300, "color": "#ffffff", "opacity": 0.4, "width": 2},
                        "move": {
                            "enable": true, "speed": 12, "direction": "none", "random": false,
                            "straight": false, "out_mode": "out", "bounce": false,
                            "attract": {"enable": false, "rotateX": 600, "rotateY": 1200}
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {"enable": false, "mode": "repulse"},
                            "onclick": {"enable": true, "mode": "push"},
                            "resize": true
                        },
                        "modes": {
                            "grab": {"distance": 800, "line_linked": {"opacity": 1}},
                            "bubble": {"distance": 800, "size": 80, "duration": 2, "opacity": 0.8, "speed": 3},
                            "repulse": {"distance": 400, "duration": 0.4},
                            "push": {"particles_nb": 4},
                            "remove": {"particles_nb": 2}
                        }
                    },
                    "retina_detect": true
                };

                // Apply particles directly
                particlesJS('particles-js', particlesConfig);
                
                // Safe initialization of niveau options
                if (typeof toggleNiveauOptions === 'function') {
                    var formationInputs = document.querySelectorAll('input[name="formation"]');
                    if (formationInputs.length > 0) {
                        // Add event listeners to formation inputs
                        formationInputs.forEach(function(input) {
                            input.addEventListener('change', toggleNiveauOptions);
                        });
                        
                        // Initial call if any are checked
                        var checked = document.querySelector('input[name="formation"]:checked');
                        if (checked) {
                            toggleNiveauOptions();
                        }
                    } else {
                        console.log('Formation inputs not found on this page');
                    }
                }
                
                // Pre-populate dynamic fields in edit mode
                <?php if ($isEditMode): ?>
                    console.log("Initializing edit mode data for dynamic fields");
                    
                    // Projects
                    var projectCount = <?php echo getSessionValue('projectCount', 0); ?>;
                    if (projectCount > 0) {
                        document.getElementById('project').value = projectCount;
                        // Trigger generation and then set values
                        generateProjectFields();
                        
                        // Add a small delay to ensure fields are created before setting values
                        setTimeout(function() {
                            var projects = <?php echo json_encode(getSessionValue('projects', [])); ?>;
                            var projectDesc = <?php echo json_encode(getSessionValue('projectDesc', [])); ?>;
                            var projectStartDate = <?php echo json_encode(getSessionValue('projectStartDate', [])); ?>;
                            var projectEndDate = <?php echo json_encode(getSessionValue('projectEndDate', [])); ?>;
                            
                            for (var i = 0; i < projects.length; i++) {
                                var inputPrefix = "project-" + (i+1);
                                if (document.getElementById(inputPrefix)) {
                                    document.getElementById(inputPrefix).value = projects[i] || '';
                                    document.getElementById(inputPrefix + "-desc").value = projectDesc[i] || '';
                                    document.getElementById(inputPrefix + "-startdate").value = projectStartDate[i] || '';
                                    document.getElementById(inputPrefix + "-enddate").value = projectEndDate[i] || '';
                                }
                            }
                        }, 100);
                    }
                    
                    // Internships
                    var stageCount = <?php echo getSessionValue('stageCount', 0); ?>;
                    if (stageCount > 0) {
                        document.getElementById('stage').value = stageCount;
                        generateStages();
                        
                        setTimeout(function() {
                            var stages = <?php echo json_encode(getSessionValue('stages', [])); ?>;
                            var stageDesc = <?php echo json_encode(getSessionValue('stageDesc', [])); ?>;
                            var stageStartDate = <?php echo json_encode(getSessionValue('stageStartDate', [])); ?>;
                            var stageEndDate = <?php echo json_encode(getSessionValue('stageEndDate', [])); ?>;
                            var stageEntreprise = <?php echo json_encode(getSessionValue('stageEntreprise', [])); ?>;
                            var stageLocation = <?php echo json_encode(getSessionValue('stageLocation', [])); ?>;
                            
                            for (var i = 0; i < stages.length; i++) {
                                var inputPrefix = "stage-" + (i+1);
                                if (document.getElementById(inputPrefix)) {
                                    document.getElementById(inputPrefix).value = stages[i] || '';
                                    document.getElementById(inputPrefix + "-desc").value = stageDesc[i] || '';
                                    document.getElementById(inputPrefix + "-startdate").value = stageStartDate[i] || '';
                                    document.getElementById(inputPrefix + "-enddate").value = stageEndDate[i] || '';
                                    document.getElementById(inputPrefix + "-entreprise").value = stageEntreprise[i] || '';
                                    document.getElementById(inputPrefix + "-location").value = stageLocation[i] || '';
                                }
                            }
                        }, 100);
                    }
                    
                    // Experiences
                    var expCount = <?php echo getSessionValue('experienceCount', 0); ?>;
                    if (expCount > 0) {
                        document.getElementById('experience').value = expCount;
                        generateExperience();
                        
                        setTimeout(function() {
                            var experiences = <?php echo json_encode(getSessionValue('experiences', [])); ?>;
                            var experienceDesc = <?php echo json_encode(getSessionValue('experienceDesc', [])); ?>;
                            var experienceStartDate = <?php echo json_encode(getSessionValue('experienceStartDate', [])); ?>;
                            var experienceEndDate = <?php echo json_encode(getSessionValue('experienceEndDate', [])); ?>;
                            var experienceEntreprise = <?php echo json_encode(getSessionValue('experienceEntreprise', [])); ?>;
                            var experienceLocation = <?php echo json_encode(getSessionValue('experienceLocation', [])); ?>;
                            var experiencePosition = <?php echo json_encode(getSessionValue('experiencePosition', [])); ?>;
                            
                            for (var i = 0; i < experiences.length; i++) {
                                var inputPrefix = "experience-" + (i+1);
                                if (document.getElementById(inputPrefix)) {
                                    document.getElementById(inputPrefix).value = experiences[i] || '';
                                    document.getElementById(inputPrefix + "-desc").value = experienceDesc[i] || '';
                                    document.getElementById(inputPrefix + "-startdate").value = experienceStartDate[i] || '';
                                    document.getElementById(inputPrefix + "-enddate").value = experienceEndDate[i] || '';
                                    document.getElementById(inputPrefix + "-entreprise").value = experienceEntreprise[i] || '';
                                    document.getElementById(inputPrefix + "-location").value = experienceLocation[i] || '';
                                    document.getElementById(inputPrefix + "-position").value = experiencePosition[i] || '';
                                }
                            }
                        }, 100);
                    }
                    
                    // Initialize modules
                    var modules = <?php echo json_encode($modules); ?>;
                    if (modules.length > 0) {
                        var moduleInputs = document.querySelectorAll('input[name="module[]"]');
                        moduleInputs.forEach(function(input) {
                            if (modules.includes(input.value)) {
                                input.checked = true;
                            }
                        });
                    }
                <?php endif; ?>
                
            } catch (e) {
                console.error('Error initializing particles or form:', e);
            }
        });
        </script>
    </body>
</html>
