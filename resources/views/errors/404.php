<!DOCTYPE html>
<html>
  <head>
    <title>Oops! Page not found</title>
  </head>
  <body>
    <div>
      <h1>404 - Page not found</h1>
      <p>The page you are looking for could not be found. Please make sure the URL you entered is correct.</p>
      <p><strong>Error Message: </strong><?php echo htmlspecialchars($e->getMessage()); ?></p>
      <a href="?c=dashboard&m=index">Go to Dashboard</a>
    </div>
  </body>
</html>
