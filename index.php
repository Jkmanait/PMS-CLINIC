<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IPMS</title>
  <meta name="title" content="IPMS - home">
  <meta name="description" content="This is a madical html template made by codewithsadee">
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Rubik:wght@400;500;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/style1.css">
  <link rel="preload" as="image" href="./assets/images/hero-banner.png">
  <link rel="preload" as="image" href="./assets/images/hero-bg.png">
  <script>
    function bookAmbulance() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var latitude = position.coords.latitude;
          var longitude = position.coords.longitude;
          alert("Ambulance booked at\nLatitude: " + latitude + "\nLongitude: " + longitude);
        }, function(error) {
          alert("Error getting location: " + error.message);
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }
  </script>

</head>

<body id="top">
  <div class="preloader" data-preloader>
    <div class="circle"></div>
  </div>
  <header class="header" data-header>
    <div class="container">
      <a href="#" class="logo">
        <img src="./assets/images/logo.png.png" width="136" height="46" alt="ipms home">
      </a>
      <nav class="navbar" data-navbar>
        <div class="navbar-top">
          <a href="#" class="logo">
            <img src="./assets/images/logo.svg" width="136" height="46" alt="ipms home">
          </a>
          <button class="nav-close-btn" aria-label="clsoe menu" data-nav-toggler>
            <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
          </button>
        </div>
        <ul class="navbar-list">
          <li class="navbar-item">
            <a href="#" class="navbar-link title-md">Home</a>
          </li>
          <!-- <li class="navbar-item">
            <a href="backend/doc/index.php" class="navbar-link title-md">Doctors</a>
          </li> -->
          <li class="navbar-item">
            <a href="backend/admin/index.php" class="navbar-link title-md">Admin</a>
          </li>
          <li class="navbar-item">
            <a href="#" class="navbar-link title-md">Blog</a>
          </li>
          <li class="navbar-item">
            <a href="#" class="navbar-link title-md">Contact</a>
          </li>
        </ul>

        <ul class="social-list">

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo- pinterest"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-youtube"></ion-icon>
            </a>
          </li>

        </ul>

      </nav>

      <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
        <ion-icon name="menu-outline"></ion-icon>
      </button>

      <a href="login.php" class="btn has-before title-md" value="Make Appointment" class="login-btn btn-primary btn">Patient Login</a>
      <div class="overlay" data-nav-toggler data-overlay></div>

      <a href="https://emergitrack-user.onrender.com/" class="btn has-before title-md" >#</a>
      <div class="overlay" data-nav-toggler data-overlay></div>

    </div>
  </header>
  <main>
    <article>
      <section class="section hero" style="background-image: url('./assets/images/hero-bg.png')" aria-label="home">
        <div class="container">

          <div class="hero-content">

            <p class="hero-subtitle has-before" data-reveal="left">Welcome To Mission-Buenaventure</p>

            <h1 class="headline-lg hero-title" data-reveal="left">
              Find Nearest <br>
              Doctor.
            </h1>

            <div class="hero-card" data-reveal="left">

              <p class="title-lg card-text">
                Search doctors. clinics. hospitals. etc.
              </p>

              <div class="wrapper">

                <div class="input-wrapper title-lg">
                  <input type="text" name="location" placeholder="Locations" class="input-field">

                  <ion-icon name="location"></ion-icon>
                </div>

                <button class="btn has-before">
                  <ion-icon name="search"></ion-icon>

                  <span class="span title-md">Find Now</span>
                </button>

              </div>

            </div>

          </div>

          <figure class="hero-banner" data-reveal="right">
            <img src="./assets/images/hero-banner.png" width="590" height="517" loading="eager" alt="hero banner"
              class="w-100">
          </figure>

        </div>
      </section>
      <section class="service" aria-label="service">
        <div class="container">

          <ul class="service-list">

            <li>
              <div class="service-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-1.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <h3 class="headline-sm card-title">
                  <a href="#">Psychiatry</a>
                </h3>

                <p class="card-text">
                  Fostering resilience through expert psychiatry care
                </p>

                <button class="btn-circle" aria-label="read more about psychiatry">
                  <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                </button>

              </div>
            </li>

            <li>
              <div class="service-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-2.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <h3 class="headline-sm card-title">
                  <a href="#">Gynecology</a>
                </h3>

                <p class="card-text">
                  Empowering women through expert gynecological care
                </p>

                <button class="btn-circle" aria-label="read more about Gynecology">
                  <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                </button>

              </div>
            </li>

            <li>
              <div class="service-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-3.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <h3 class="headline-sm card-title">
                  <a href="#">Pulmonology</a>
                </h3>

                <p class="card-text">
                  Your breath matters; our expertise in pulmonology cares
                </p>

                <button class="btn-circle" aria-label="read more about Pulmonology">
                  <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                </button>

              </div>
            </li>

            <li>
              <div class="service-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-4.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <h3 class="headline-sm card-title">
                  <a href="#">Orthopedics</a>
                </h3>

                <p class="card-text">
                  Orthopedic excellence, restoring motion and joy
                </p>

                <button class="btn-circle" aria-label="read more about Orthopedics">
                  <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                </button>

              </div>
            </li>

          </ul>

        </div>
      </section>
      <section class="section about" aria-labelledby="about-label">
        <div class="container">

          <div class="about-content">

            <p class="section-subtitle title-lg has-after" id="about-label" data-reveal="left">About Us</p>

            <h2 class="headline-md" data-reveal="left">Experienced Workers</h2>

            <p class="section-text" data-reveal="left">
              Meet our skilled team: Experts dedicated to excellence, committed to your well-being. 
              We strive to provide exceptional care, putting your health at the forefront. 
              Your journey to wellness begins with us
            </p>

            <style>
        .tab-content {
            display: none;
            margin-top: 20px;
        }

        .tab-content.active {
            display: block;
        }

        .tab-btn.active {
            background-color: #e9ecef;
            border-bottom: 2px solid #007bff;
        }
    </style>
</head>
<body>
    <ul class="tab-list" data-reveal="left">
        <li>
            <button class="tab-btn active" data-target="vision-content">Vision</button>
        </li>
        <li>
            <button class="tab-btn" data-target="mission-content">Mission</button>
        </li>
        <li>
            <button class="tab-btn" data-target="strategy-content">Strategy</button>
        </li>
    </ul>

    <div id="vision-content" class="tab-content active">
        Vision content goes here.
    </div>
    <div id="mission-content" class="tab-content">
        Crafting a future of excellence. Our commitment transcends boundaries, and our strategic approach ensures impactful outcomes.
        We embrace challenges, paving the way for success with dedication and innovation.
    </div>
    <div id="strategy-content" class="tab-content">
        Strategy content goes here.
    </div>

    <script>
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

                let targetContent = document.getElementById(button.getAttribute('data-target'));
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    </script>

            <!-- <p class="tab-text" data-reveal="left">
              Crafting a future of excellence. Our commitment transcends boundaries, and our strategic approach ensures impactful outcomes.
               We embrace challenges, paving the way for success with dedication and innovation
            </p> -->
<!-- 
            <div class="wrapper">

              <ul class="about-list">

                <li class="about-item" data-reveal="left">
                  <ion-icon name="checkmark-circle-outline"></ion-icon>

                  <span class="span">Mission of Excellence in Patient Care</span>
                </li>

                <li class="about-item" data-reveal="left">
                  <ion-icon name="checkmark-circle-outline"></ion-icon>

                  <span class="span">Mission of Innovation and Research</span>
                </li>

                <li class="about-item" data-reveal="left">
                  <ion-icon name="checkmark-circle-outline"></ion-icon>

                  <span class="span">Mission of Community Wellness</span>
                </li>

                <li class="about-item" data-reveal="left">
                  <ion-icon name="checkmark-circle-outline"></ion-icon>

                  <span class="span">Mission of Inclusivity and Diversity</span>
                </li>

              </ul>

            </div>

          </div> -->

          <!-- <figure class="about-banner" data-reveal="right">
            <img src="./assets/images/about-banner.png" width="554" height="678" loading="lazy" alt="about banner"
              class="w-100">
          </figure> -->

        </div>
      </section>
      <section class="section listing" aria-labelledby="listing-label">
        <div class="container">

          <ul class="grid-list">

            <li>
              <p class="section-subtitle title-lg" id="listing-label" data-reveal="left">Doctors Listig</p>

              <h2 class="headline-md" data-reveal="left">Browse by specialist</h2>
            </li>

            <li>
              <div class="listing-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-1.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <div>
                  <h3 class="headline-sm card-title">Psychiatry</h3>

                  <p class="card-text"></p>
                </div>

              </div>
            </li>

            <li>
              <div class="listing-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-2.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <div>
                  <h3 class="headline-sm card-title">Gynecology</h3>

                  <p class="card-text"></p>
                </div>

              </div>
            </li>

            <li>
              <div class="listing-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-4.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <div>
                  <h3 class="headline-sm card-title">Pulmonology</h3>

                  <p class="card-text"></p>
                </div>

              </div>
            </li>

            <li>
              <div class="listing-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-5.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <div>
                  <h3 class="headline-sm card-title">Orthopedics</h3>

                  <p class="card-text"></p>
                </div>

              </div>
            </li>

            <li>
              <div class="listing-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-6.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <div>
                  <h3 class="headline-sm card-title">Pediatrics</h3>

                  <p class="card-text"></p>
                </div>

              </div>
            </li>

            <li>
              <div class="listing-card" data-reveal="bottom">

                <div class="card-icon">
                  <img src="./assets/images/icon-7.png" width="71" height="71" loading="lazy" alt="icon">
                </div>

                <div>
                  <h3 class="headline-sm card-title">Osteology</h3>

                  <p class="card-text"></p>
                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>
      <section class="section blog" aria-labelledby="blog-label">
        <div class="container">

          <p class="section-subtitle title-lg text-center" id="blog-label" data-reveal="bottom">
            News & Article
          </p>

          <h2 class="section-title headline-md text-center" data-reveal="bottom">Latest Articles</h2>

          <ul class="grid-list">

            <li>
              <div class="blog-card has-before has-after" data-reveal="bottom">

                <div class="meta-wrapper">

                  <div class="card-meta">
                    <ion-icon name="person-outline"></ion-icon>

                    <span class="span">By Admin</span>
                  </div>

                  <div class="card-meta">
                    <ion-icon name="folder-outline"></ion-icon>

                    <span class="span">Specialist</span>
                  </div>

                </div>

                <h3 class="headline-sm card-title">News Title</h3>

                <time class="title-sm date" datetime="2022-01-28">Date</time>

                <p class="card-text">
                blah,blah,blah,blah,blah,blah,blah,blah,
                blah,blah,blah,blah,blah,blah,blah,blah
                </p>

                <a href="#" class="btn-text title-lg">Read More</a>

              </div>
            </li>

            <li>
              <div class="blog-card has-before has-after" data-reveal="bottom">

                <div class="meta-wrapper">

                  <div class="card-meta">
                    <ion-icon name="person-outline"></ion-icon>

                    <span class="span">By Admin</span>
                  </div>

                  <div class="card-meta">
                    <ion-icon name="folder-outline"></ion-icon>

                    <span class="span">Specialist</span>
                  </div>

                </div>

                <h3 class="headline-sm card-title">News Title</h3>

                <time class="title-sm date" datetime="2022-01-28">Date</time>

                <p class="card-text">
                  blah,blah,blah,blah,blah,blah,blah,blah,
                  blah,blah,blah,blah,blah,blah,blah,blah
                </p>

                <a href="#" class="btn-text title-lg">Read More</a>

              </div>
            </li>

            <li>
              <div class="blog-card has-before has-after" data-reveal="bottom">

                <div class="meta-wrapper">

                  <div class="card-meta">
                    <ion-icon name="person-outline"></ion-icon>

                    <span class="span">By Admin</span>
                  </div>

                  <div class="card-meta">
                    <ion-icon name="folder-outline"></ion-icon>

                    <span class="span">Specialist</span>
                  </div>

                </div>

                <h3 class="headline-sm card-title">News Title</h3>

                <time class="title-sm date" datetime="2022-01-28">Date</time>

                <p class="card-text">
                blah,blah,blah,blah,blah,blah,blah,blah,
                blah,blah,blah,blah,blah,blah,blah,blah 
                </p>

                <a href="#" class="btn-text title-lg">Read More</a>

              </div>
            </li>

          </ul>

        </div>
      </section>

    </article>
  </main>
  <footer class="footer" style="background-image: url('./assets/images/footer-bg.png')">
    <div class="container">

      <div class="section footer-top">

        <div class="footer-brand" data-reveal="bottom">

          <a href="#" class="logo">
            <img src="./assets/images/logo.png.png" width="136" height="46" loading="lazy" alt="ipms home">
          </a>

          <ul class="contact-list has-after">

            <li class="contact-item">

              <div class="item-icon">
                <ion-icon name="mail-open-outline"></ion-icon>
              </div>

              <div>
                <p>
                  Main Email : <a href="#" class="contact-link">#</a>
                </p>

                <p>
                  Inquiries : <a href="#" class="contact-link">#</a>
                </p>
              </div>

            </li>

            <li class="contact-item">

              <div class="item-icon">
                <ion-icon name="call-outline"></ion-icon>
              </div>

              <div>
                <p>
                  Office Telephone : <a href="tel:#" class="contact-link">#</a>
                </p>

                <p>
                  Mobile : <a href="tel#" class="contact-link">#</a>
                </p>
              </div>

            </li>

          </ul>

        </div>

        <div class="footer-list" data-reveal="bottom">

          <p class="headline-sm footer-list-title">About Us</p>

          <p class="text">
            The swelling elitr will go away, but the agony is severe.          </p>

          <address class="address">
            <ion-icon name="map-outline"></ion-icon>

            <span class="text">
            MISSION-BUENAVENTURE 
            CARE FAMILY HOSPITAL FOUNDATION INC...
            </span>
          </address>

        </div>

        <ul class="footer-list" data-reveal="bottom">

          <li>
            <p class="headline-sm footer-list-title">Services</p>
          </li>

          <li>
            <a href="#" class="text footer-link">Conditions</a>
          </li>

          <li>
            <a href="#" class="text footer-link">Listing</a>
          </li>

          <li>
            <a href="#" class="text footer-link">How It Works</a>
          </li>

          <li>
            <a href="#" class="text footer-link">What We Offer</a>
          </li>

          <li>
            <a href="#" class="text footer-link">Latest News</a>
          </li>

          <li>
            <a href="#" class="text footer-link">Contact Us</a>
          </li>

        </ul>

        <ul class="footer-list" data-reveal="bottom">

          <li>
            <p class="headline-sm footer-list-title">Useful Links</p>
          </li>

          <li>
            <a href="#" class="text footer-link">Conditions</a>
          </li>

          <li>
            <a href="#" class="text footer-link">Terms of Use</a>
          </li>

          <li>
            <a href="#" class="text footer-link">Our Services</a>
          </li>

          <li>
            <a href="#" class="text footer-link">Join as a Doctor</a>
          </li>

          <li>
            <a href="#" class="text footer-link">New Guests List</a>
          </li>

          <li>
            <a href="#" class="text footer-link">The Team List</a>
          </li>

        </ul>

        <div class="footer-list" data-reveal="bottom">

          <p class="headline-sm footer-list-title">Subscribe</p>

          <form action="" class="footer-form">
            <input type="email" name="email" placeholder="Email" class="input-field title-lg">

            <button type="submit" class="btn has-before title-md">Subscribe</button>
          </form>

          <p class="text">
            Get the latest updates via email. Any time you may unsubscribe
          </p>

        </div>

      </div>

      <div class="footer-bottom">

       
        <ul class="social-list">

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-google"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-linkedin"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-pinterest"></ion-icon>
            </a>
          </li>

        </ul>

      </div>

    </div>
  </footer>
  <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
    <ion-icon name="chevron-up"></ion-icon>
  </a>
  <script src="./assets/js/script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>