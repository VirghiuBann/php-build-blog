<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'My Blog' ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a href="" class="navbar-brand">My Site</a>
  </nav>
  <div class="container mt--4">
    <?= $content ?>
  </div>
  <footer class="bg-light py-4 footer mt-auto">
    <div class="container">
      Load Time in ms <?= round(1000 * (microtime(true) - DEBUG_TIME)) ?>
    </div>
  </footer>
</body>

</html>