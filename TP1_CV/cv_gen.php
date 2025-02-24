<?php
session_start();

// Retrieve projects from session
$projectCount = $_SESSION['cv_data']['projectCount'] ?? 0;
$projects = $_SESSION['cv_data']['projects'] ?? [];
$projectDescriptions = $_SESSION['cv_data']['projectDesc'] ?? []; 
$projectStartDates = $_SESSION['cv_data']['projectStartDate'] ?? [];
$projectEndDates = $_SESSION['cv_data']['projectEndDate'] ?? [];

// Assign projects dynamically
$projectList = [];
$projectDescList = [];  
$projectStartList = [];
$projectEndList = [];

for ($i = 0; $i < $projectCount; $i++) {
    $projectList[] = $projects[$i] ?? ''; 
    $projectDescList[] = $projectDescriptions[$i] ?? ''; 
    $projectStartList[] = $projectStartDates[$i] ?? '';
    $projectEndList[] = $projectEndDates[$i] ?? '';
}

// Retrieve experiences from session
$experienceCount = $_SESSION['cv_data']['experienceCount'] ?? 0;
$experiences = $_SESSION['cv_data']['experiences'] ?? [];
$experienceDescriptions = $_SESSION['cv_data']['experienceDesc'] ?? [];
$experienceStartDates = $_SESSION['cv_data']['experienceStartDate'] ?? [];
$experienceEndDates = $_SESSION['cv_data']['experienceEndDate'] ?? [];
$experienceEntreprises = $_SESSION['cv_data']['experienceEntreprise'] ?? [];
$experienceLocations = $_SESSION['cv_data']['experienceLocation'] ?? [];
$experiencePositions = $_SESSION['cv_data']['experiencePosition'] ?? [];

// Assign experiences dynamically
$experienceList = [];
$experienceDescList = [];
$experienceStartList = [];
$experienceEndList = [];
$experienceEntreList = [];
$experienceLocList = [];
$experiencePosList = [];

for ($i = 0; $i < $experienceCount; $i++) {
    $experienceList[] = $experiences[$i] ?? '';
    $experienceDescList[] = $experienceDescriptions[$i] ?? '';
    $experienceStartList[] = $experienceStartDates[$i] ?? '';
    $experienceEndList[] = $experienceEndDates[$i] ?? '';
    $experienceEntreList[] = $experienceEntreprises[$i] ?? '';
    $experienceLocList[] = $experienceLocations[$i] ?? '';
    $experiencePosList[] = $experiencePositions[$i] ?? '';
}

// Retrieve stages from session
$stageCount = $_SESSION['cv_data']['stageCount'] ?? 0;
$stages = $_SESSION['cv_data']['stages'] ?? [];
$stageDescriptions = $_SESSION['cv_data']['stageDesc'] ?? [];
$stageStartDates = $_SESSION['cv_data']['stageStartDate'] ?? [];
$stageEndDates = $_SESSION['cv_data']['stageEndDate'] ?? [];
$stageEntreprises = $_SESSION['cv_data']['stageEntreprise'] ?? [];

// Assign stages dynamically
$stageList = [];
$stageDescList = [];
$stageStartList = [];
$stageEndList = [];
$stageEntreList = [];

for ($i = 0; $i < $stageCount; $i++) {
    $stageList[] = $stages[$i] ?? '';
    $stageDescList[] = $stageDescriptions[$i] ?? '';
    $stageStartList[] = $stageStartDates[$i] ?? '';
    $stageEndList[] = $stageEndDates[$i] ?? '';
    $stageEntreList[] = $stageEntreprises[$i] ?? '';
}

?>


<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modern CV</title>
  <link rel="stylesheet" href="cv_gen.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    
    body {
      min-height: 297mm;
      display: flex;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
    }

    .header img {
      border-radius: 50%;
      width: 150px;
      height: 150px;
      object-fit: cover;
      border: 2px solid #007bff;
    }

    .header h1 {
      color: #007bff;
      font-size: 2.5em;
      margin: 0;
      text-align: center;
      width: 100%;
    }
    
    .main-content {
      width: 75%;
      background-color: #fff;
      padding: 20px;
      box-shadow: -3px 0 5px rgba(0, 0, 0, 0.1);

      margin-top: 10px;
    }

    .container {
      display: flex;
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <div class="header">
        <img src="<?php echo $_SESSION['cv_data']['picture'] ?>" alt="Profile Picture">
      </div>
      <section id="contact-info">
        <h2>Contact</h2>
        <!-- retrieve the email, phone, linkedin, and github from the session -->
        <p><i class="fas fa-envelope icon"></i> <?php echo $_SESSION['cv_data']['picture']; ?></p>
        <p><i class="fas fa-phone icon"></i> (+212) <?php echo $_SESSION['cv_data']['phone'] ?></p>
        <?php if (!empty($_SESSION['cv_data']['linkedin'])) { ?>
          <p><i class="fab fa-linkedin icon"></i> <?php echo $_SESSION['cv_data']['linkedin'] ?></p>
        <?php } ?>
        <?php if (!empty($_SESSION['cv_data']['github'])) { ?>
          <p><i class="fab fa-github icon"></i> <?php echo $_SESSION['cv_data']['github'] ?></p>
        <?php } ?>
      </section>

      <section id="education">
        <h2>Education</h2>
        <ul>
          <li>
            <h3>ENSA Tetouan</h3>
            <p><?php echo $_SESSION['cv_data']['formation'] ?> , <?php echo $_SESSION['cv_data']['niveau'] ?></p>
            <p>2020 - 2025</p>
          </li>
        </ul>
      </section>

      <section id="competences">
        <h2>Competences</h2>
        <ul>
          <?php if (!empty($_SESSION['cv_data']['competences'])): ?>
            <?php foreach ($_SESSION['cv_data']['competences'] as $competence): ?>
              <li><?php echo htmlspecialchars($competence); ?></li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>

        
        <h2>Languages</h2>
        <ul>
          <li><?php echo $_SESSION['cv_data']['langue1'] ?> (<?php echo $_SESSION['cv_data']['niveau1'] ?>)</li>
          <li><?php echo $_SESSION['cv_data']['langue2'] ?> (<?php echo $_SESSION['cv_data']['niveau2'] ?>)</li>
          <?php if (!empty($_SESSION['cv_data']['langue3'])) { ?>
            <li><?php echo $_SESSION['cv_data']['langue3'] ?> (<?php echo $_SESSION['cv_data']['niveau3'] ?>)</li>
          <?php } ?>
        </ul>

        <h2>Interests</h2>
        <ul>
          <?php if (!empty($_SESSION['cv_data']['interests'])): ?>
            <?php foreach ($_SESSION['cv_data']['interests'] as $interest): ?>
              <li><?php echo htmlspecialchars($interest); ?></li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </section>
    </div>

    <div class="main-content">
      <div class="header-name">
        <h1><?php echo $_SESSION['cv_data']['firstname'] . " " . $_SESSION['cv_data']['lastname']; ?></h1>
      </div>
      
      <div class="divider">
        <svg width="100" height="3" viewBox="0 0 0 3" fill="none" xmlns="http://www.w3.org/2000/svg">
          <line x1="0" y1="1.5" x2="100" y2="1.5" stroke="#007bff" stroke-width="3" stroke-linecap="round"/>
        </svg>
      </div>
      
      <section id="summary">
        <h2>Profile</h2>
        <p><?php echo $_SESSION['cv_data']['message'] ?></p>
      </section>

      <section id="experience">
        <h2>Experiences</h2>

        <?php for ($i = 0; $i < $experienceCount; $i++) { ?>
          <div class="experience" id="experience">
            <h3><?php echo $experienceList[$i] ?> - <?php echo $experienceEntreList[$i] ?></h3>
            <p class="date"> <?php echo $experienceStartList[$i] ?> - <?php echo $experienceEndList[$i] ?></p>
            <ul>
              <li><?php echo $experienceDescList[$i] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php for ($i = 0; $i < $stageCount; $i++) { ?>
          <div class="experience" id="stage">
            <h3><?php echo $stageList[$i] ?> - <?php echo $stageEntreList[$i] ?></h3>
            <p class="date"> <?php echo $stageStartList[$i] ?> - <?php echo $stageEndList[$i] ?></p>
            <ul>
              <li><?php echo $stageDescList[$i] ?></li>
            </ul>
          </div>
        <?php } ?>

      </section>
      
      <section id="projects">
        <h2>Projects</h2>

        <?php for ($i = 0; $i < $projectCount; $i++) { ?>
          <div class="project">
            <h3><?php echo $projectList[$i] ?></h3>
            <p class="date"> <?php echo $projectStartList[$i] ?> - <?php echo $projectEndList[$i] ?></p>
            <ul>
              <li><?php echo $projectDescList[$i] ?></li>
            </ul>
          </div>
        <?php } ?>

      </section>
    </div>
  </div>
  
  <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>