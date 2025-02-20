<?php session_start(); 

// Retrieve experiences from session
$experienceCount = $_SESSION['cv_data']['experienceCount'] ?? 0;
$experiences = $_SESSION['cv_data']['experiences'] ?? [];
$experienceDesc = $_SESSION['cv_data']['experienceDesc'] ?? [];


// Retrieve projects from session
$projectCount = $_SESSION['cv_data']['projectCount'] ?? 0;
$projects = $_SESSION['cv_data']['projects'] ?? [];
$projectDesc = $_SESSION['cv_data']['projectDesc'] ?? [];

// Assign experiences dynamically
$experienceList = [];
$experienceDesc = [];

for ($i = 0; $i < $experienceCount; $i++) {
    $experienceList[] = $experiences[$i] ?? ''; // Ensure no errors if the index is missing
    $experienceDesc[] = $experienceDesc[$i] ?? ''; // Ensure no errors if the index is missing
}

// Assign projects dynamically
$projectList = [];
$projectDesc = [];

for ($i = 0; $i < $projectCount; $i++) {
    $projectList[] = $projects[$i] ?? ''; // Ensure no errors if the index is missing
    $projectDesc[] = $projectDesc[$i] ?? ''; // Ensure no errors if the index is missing
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
          <li><?php echo $_SESSION['cv_data']['competence1'] ?></li>
          <li><?php echo $_SESSION['cv_data']['competence2'] ?></li>
          <?php if (!empty($_SESSION['cv_data']['competence3'])) { ?>
            <li><?php echo $_SESSION['cv_data']['competence3'] ?></li>
          <?php } ?>
          <?php if (!empty($_SESSION['cv_data']['competence4'])) { ?>
            <li><?php echo $_SESSION['cv_data']['competence4'] ?></li>
          <?php } ?>
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
          <li><?php echo $_SESSION['cv_data']['interest1'] ?></li>
          <li><?php echo $_SESSION['cv_data']['interest2'] ?></li>
          <?php if (!empty($_SESSION['cv_data']['interest3'])) { ?>
            <li><?php echo $_SESSION['cv_data']['interest3'] ?></li>
          <?php } ?>
          <?php if (!empty($_SESSION['cv_data']['interest4'])) { ?>
            <li><?php echo $_SESSION['cv_data']['interest4'] ?></li>
          <?php } ?>
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
            <h3><?php echo $experienceList[0] ?></h3>
            <p class="date">January 2023 - December 2023</p>
            <ul>
              <li><?php echo $experienceDesc[0] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($experienceList[1])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[1] ?></h3>
            <p class="date">January 2022 - December 2022</p>
            <ul>
              <li><?php echo $experienceDesc[1] ?></li>
            </ul>
          </div> 
        <?php } ?>

        <?php if (!empty($experienceList[2])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[2] ?></h3>
            <p class="date">January 2021 - December 2021</p>
            <ul>
              <li><?php echo $experienceDesc[2] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($experienceList[3])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[3] ?></h3>
            <p class="date">January 2020 - December 2020</p>
            <ul>
              <li><?php echo $experienceDesc[3] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($experienceList[4])) { ?>
          <div class="experience">
            <h3><?php echo $experienceList[4] ?></h3>
            <p class="date">January 2019 - December 2019</p>
            <ul>
              <li><?php echo $experienceDesc[4] ?></li>
            </ul>
          </div>
        <?php } ?>

      </section>
      
      <section id="projects">
        <h2>Projects</h2>

       <?php if (!empty($projectList[0])) { ?>
          <div class="project">
            <h3><?php echo $projectList[0] ?></h3>
            <p class="date">2023</p>
            <ul>
              <li> <?php echo $projectDesc[0] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($projectList[1])) { ?>
          <div class="project">
            <h3><?php echo $projectList[1] ?></h3>
            <p class="date">2022</p>
            <ul>
              <li> <?php echo $projectDesc[1] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($projectList[2])) { ?>
          <div class="project">
            <h3><?php echo $projectList[2] ?></h3>
            <p class="date">2021</p>
            <ul>
              <li> <?php echo $projectDesc[2] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($projectList[3])) { ?>
          <div class="project">
            <h3><?php echo $projectList[3] ?></h3>
            <p class="date">2020</p>
            <ul>
              <li> <?php echo $projectDesc[3] ?></li>
            </ul>
          </div>
        <?php } ?>

        <?php if (!empty($projectList[4])) { ?>
          <div class="project">
            <h3><?php echo $projectList[4] ?></h3>
            <p class="date">2019</p>
            <ul>
              <li> <?php echo $projectDesc[4] ?></li>
            </ul>
          </div>
        <?php } ?>

      </section>
      

    </div>
  </div>
  
  <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>