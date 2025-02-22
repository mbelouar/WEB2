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

?>


<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modern CV</title>
  <link rel="stylesheet" href="cv_gen.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
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
      /*float: right;*/
      /* position: relative; */ 
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
        <p><i class="fas fa-envelope icon"></i> <?php echo $_SESSION['cv_data']['email']; ?></p>
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

        <?php if (!empty($experienceList[0])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[0]?> - <?php echo $experienceEntreList[0] ?></h3>
            <p class="date"> <?php echo $experienceStartList[0] ?> - <?php echo $experienceEndList[0] ?></p>
            <ul>
              <li><?php echo $experienceDescList[0] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($experienceList[1])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[1] ?> - <?php echo $experienceEntreList[1] ?></h3>
            <p class="date"> <?php echo $experienceStartList[1] ?> - <?php echo $experienceEndList[1] ?></p>
            <ul>
              <li><?php echo $experienceDescList[1] ?></li>
          </div> 
        <?php } ?>

        <?php if (!empty($experienceList[2])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[2] ?> - <?php echo $experienceEntreList[2] ?></h3>
            <p class="date"> <?php echo $experienceStartList[2] ?> - <?php echo $experienceEndList[2] ?></p>
            <ul>
              <li><?php echo $experienceDescList[2] ?></li>
          </div>
        <?php } ?>

        <?php if (!empty($experienceList[3])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[3] ?> - <?php echo $experienceEntreList[3] ?></h3>
            <p class="date"> <?php echo $experienceStartList[3] ?> - <?php echo $experienceEndList[3] ?></p>
            <ul>
              <li><?php echo $experienceDescList[3] ?></li>
          </div>
        <?php } ?>

        <?php if (!empty($experienceList[4])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[4] ?> - <?php echo $experienceEntreList[4] ?></h3>
            <p class="date"> <?php echo $experienceStartList[4] ?> - <?php echo $experienceEndList[4] ?></p>
            <ul>
              <li><?php echo $experienceDescList[4] ?></li>
            </ul>
          </div>
        <?php } ?>

      </section>
      
      <section id="projects">
        <h2>Projects</h2>

       <?php if (!empty($projectList[0])) { ?>
          <div class="project">
            <h3><?php echo $projectList[0] ?></h3>
            <p class="date"> <?php echo $projectStartList[0] ?> - <?php echo $projectEndList[0] ?></p>
            <ul>
              <li> <?php echo $projectDescList[0] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($projectList[1])) { ?>
          <div class="project">
            <h3><?php echo $projectList[1] ?></h3>
            <p class="date"> <?php echo $projectStartList[1] ?> - <?php echo $projectEndList[1] ?></p>
            <ul>
              <li> <?php echo $projectDescList[1] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($projectList[2])) { ?>
          <div class="project">
            <h3><?php echo $projectList[2] ?></h3>
            <p class="date"> <?php echo $projectStartList[2] ?> - <?php echo $projectEndList[2] ?></p>
            <ul>
              <li> <?php echo $projectDescList[2] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($projectList[3])) { ?>
          <div class="project">
            <h3><?php echo $projectList[3] ?></h3>
            <p class="date"> <?php echo $projectStartList[3] ?> - <?php echo $projectEndList[3] ?></p>
            <ul>
              <li> <?php echo $projectDescList[3] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($projectList[4])) { ?>
          <div class="project">
            <h3><?php echo $projectList[4] ?></h3>
            <p class="date"> <?php echo $projectStartList[4] ?> - <?php echo $projectEndList[4] ?></p>
            <ul>
              <li> <?php echo $projectDescList[4] ?></li>
            </ul>
          </div>
        <?php } ?>

      </section>
      

    </div>
  </div>
  
  <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>