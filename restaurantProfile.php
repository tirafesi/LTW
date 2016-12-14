<!DOCTYPE html>

<html>

<head>
  <title>EAT&AVAIL</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="stylesheets/global-style.css">
  <link rel="stylesheet" href="stylesheets/header.css">
  <link rel="stylesheet" href="stylesheets/restaurantProfile.css">
  <link rel="stylesheet" href="stylesheets/footer.css">
  <script src="includes/jquery-3.1.1.min.js"></script>
  <script type="text/javascript" src="scripts/imageSlideShow.js" defer></script>
</head>


<body>

  <?php //TODO pagina esta mal quando nao ha reviews
  session_start();
  include_once dirname(__FILE__).'/database/connection.php';
  include_once dirname(__FILE__).'/includes/header.php';
  ?>


  <!-- RESTAURANT INFO -->
  <?php

  // get restaurant id
  $restaurantId = $_GET['id'];

  // get restaurant info
  $stmt = $db->prepare(
    'SELECT name, description, address, type AS priceRange, AVG(Review.score) AS restScore
    FROM Restaurant, Review, PriceRange
    WHERE Restaurant.id = :restaurantId
    AND Review.restaurant = Restaurant.id
    AND Restaurant.priceRange = PriceRange.id
    GROUP BY name');

  // bind, execute and fetch
  $stmt->bindParam(':restaurantId', $restaurantId);
  $stmt->execute();
  $restaurantInfo = $stmt->fetch();

  // get restaurant score
  $stmt = $db->prepare(
  'SELECT AVG(Review.score) AS restScore
  FROM Restaurant, Review
  WHERE Restaurant.id = :restaurantId
  AND Review.restaurant = Restaurant.id
  GROUP BY name');

  // bind, execute and fetch
  $stmt->bindParam(':restaurantId', $restaurantId);
  $stmt->execute();
  $restaurantScore = $stmt->fetch();

  if ($restaurantScore.length == 0) {
    /* TODO aqui */
    $restaurantScore = 'No Reviews available for this restaurant';
  }

  ?>
  <!-- !RESTAURANT INFO -->

  <section class="restaurantProfile">

    <header class="header-wrap">
      <h1 class="name"><?= $restaurantInfo['name'] ?></h1>
    </header>

    <!--<h2>Image Gallery</h2>-->

    <!-- IMAGE GALLERY (SLIDESHOW) -->

    <!--
    http://i.imgur.com/ZG2WCNP.jpg
    http://i.imgur.com/fQDtGXU.jpg
    http://i.imgur.com/aSirRq7.jpg
    -->

    <div class="img-gallery-wrap">

      <?php
      // get restaurant images
      $stmt = $db->prepare(
        'SELECT url, description
        FROM Image
        WHERE Image.restaurant = :restaurantId');

      // bind, execute and fetch
      $stmt->bindParam(':restaurantId', $restaurantId);
      $stmt->execute();

      // number of images found
      $nImg = 0;

      while ($img = $stmt->fetch()) {
        ?>
        <div class="img-wrap">
          <img class="img-slide" src="<?= $img['url'] ?>" alt="<?= $img['description'] ?>">
        </div>
      <?php
        $nImg++;
      } ?>

      <div class="dot-wrap">

        <?php
        // create dots
        for ($i = 0; $i < $nImg; $i++) {
          ?>
          <span class="dot"></span>
        <?php
        } ?>

      </div>

      <div class="prev">
        <a>&#10096;</a>
      </div>

      <div class="next">
        <a>&#10097;</a>
      </div>


    </div>

    <!-- IMAGE GALLERY (SLIDESHOW) -->

    <div class="description-wrap">
      <h2>Description</h2>
      <p><?= $restaurantInfo['description'] ?></p>
    </div>

    <aside class="recentReview-wrap">

      <h1>Recent Reviews</h1>

      <?php

      // prepare query
      $stmt = $db->prepare(
        'SELECT score, tldr, body, name
        FROM Review, Reviewer, User
        WHERE Review.restaurant = :restaurantId
        AND Review.reviewer = Reviewer.id
        AND Reviewer.id = User.id
        ORDER BY Review.id DESC LIMIT 3');

      // bind and execute
      $stmt->bindParam(':restaurantId', $restaurantId);
      $stmt->execute();

      while ($row = $stmt->fetch()) {

        $tldr_clean = str_replace('\n', '<br />', $row['tldr']);
    		$body_clean = str_replace('\n', '<br />', $row['body']);
        ?>

        <section>
          <h2 class="tldr"><?= $tldr_clean?> <?= $row['score']?>/10</h2>
          <!-- TODO fazer display dos \n correctamente -->
          <p class="body"><?= $body_clean?></p>
          <!-- TODO link name of user to his profile page -->
          <p class="reviewer">Written by <?= $row['name'] ?></p>
        </section>

      <?php
      } ?>

    </aside>

  </section>

  <?php include_once dirname(__FILE__).'/includes/footer.php'; ?>

</body>

</html>
