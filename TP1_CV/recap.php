<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="valide.css">
</head>
<body>
    <div id="particles-js"></div>
    <div class="content-wrapper">
        <?php
        // Include the necessary PHP files
        include "getInfo.php";
        include "handle_upload.php";
        ?>

        <div class="success-message">
            <i class="fas fa-check-circle" style="font-size: 30px; margin-right: 10px;"></i>
            <h2>Données enregistrées avec succès!</h2>
            <div>
                <p>Merci, <?php echo $firstname . " " . $lastname; ?>. Vos informations ont été sauvegardées.</p>
            </div>
        </div>

        <form action="formulaire.php" method="POST">
            <!-- Hidden inputs to retain form data -->
            <input type="hidden" name="firstname" value="<?php echo $firstname; ?>">
            <input type="hidden" name="name" value="<?php echo $lastname; ?>">
            <input type="hidden" name="age" value="<?php echo $age; ?>">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="phone" value="<?php echo $phone; ?>">
            <input type="hidden" name="address" value="<?php echo $adresse; ?>">
            <input type="hidden" name="github" value="<?php echo $github; ?>">
            <input type="hidden" name="linkedin" value="<?php echo $linkedin; ?>">
            <input type="hidden" name="formation" value="<?php echo $formation; ?>">
            <input type="hidden" name="niveau" value="<?php echo $_POST['niveau']; ?>">

            <!-- Selected Modules -->
            <?php 
            if (!empty($_POST['modules'])) {
                foreach ($_POST['modules'] as $module) {
                    echo "<input type='hidden' name='modules[]' value='$module'>";
                }
            }
            ?>

            <!-- Project Count and Names -->
            <input type="hidden" name="project" value="<?php echo $projectCount; ?>">
            <?php 
            if (!empty($projects)) {
                foreach ($projects as $project) {
                    echo "<input type='hidden' name='projectNames[]' value='$project'>";
                    // Include descriptions for the projects
                    if (!empty($projectDesc)) {
                        foreach ($projectDesc as $desc) {
                            echo "<input type='hidden' name='projectDescriptions[]' value='$desc'>";
                        }
                    }
                }
            }
            ?>

            <!-- Stage Count and Names -->
            <input type="hidden" name="stage" value="<?php echo $stageCount; ?>">
            <?php 
            if (!empty($stages)) {
                foreach ($stages as $stage) {
                    echo "<input type='hidden' name='stageNames[]' value='$stage'>";
                    // Include descriptions for the stages
                    if (!empty($stageDesc)) {
                        foreach ($stageDesc as $desc) {
                            echo "<input type='hidden' name='stageDescriptions[]' value='$desc'>";
                        }
                    }
                }
            }
            ?>

            <!-- Experience Count and Names -->
            <input type="hidden" name="experience" value="<?php echo $experienceCount; ?>">
            <?php 
            if (!empty($experiences)) {
                foreach ($experiences as $experience) {
                    echo "<input type='hidden' name='experienceNames[]' value='$experience'>";
                    // Include descriptions for the experiences
                    if (!empty($experienceDesc)) {
                        foreach ($experienceDesc as $desc) {
                            echo "<input type='hidden' name='experienceDescriptions[]' value='$desc'>";
                        }
                    }
                }
            }
            ?>

            <!-- Skills -->
            <input type="hidden" name="competence1" value="<?php echo $competence1; ?>">
            <input type="hidden" name="competence2" value="<?php echo $competence2; ?>">
            <input type="hidden" name="competence3" value="<?php echo $competence3; ?>">
            <input type="hidden" name="competence4" value="<?php echo $competence4; ?>">

            <!-- Interests -->
            <input type="hidden" name="interest1" value="<?php echo $interest1; ?>">
            <input type="hidden" name="interest2" value="<?php echo $interest2; ?>">
            <input type="hidden" name="interest3" value="<?php echo $interest3; ?>">
            <input type="hidden" name="interest4" value="<?php echo $interest4; ?>">

            <!-- Languages -->
            <input type="hidden" name="langue1" value="<?php echo $langue1; ?>">
            <input type="hidden" name="niveau1" value="<?php echo $niveau1; ?>">
            <input type="hidden" name="langue2" value="<?php echo $langue2; ?>">
            <input type="hidden" name="niveau2" value="<?php echo $niveau2; ?>">
            <input type="hidden" name="langue3" value="<?php echo $langue3; ?>">
            <input type="hidden" name="niveau3" value="<?php echo $niveau3; ?>">

            <!-- Message -->
            <input type="hidden" name="message" value="<?php echo $message; ?>">

            <div class="form-controls">
                <button type="submit">Modifier</button>
                <a href="formulaire.php">
                    <button type="button" >Retour</button>
                </a>
            </div>
        </form>
    </div>
    <script src="particles.js"></script>
    <script>
        particlesJS.load('particles-js', 'assets/particles.json', function() {
            console.log('callback - particles.js config loaded');
        });
    </script>
</body>
</html>
