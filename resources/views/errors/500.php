<!DOCTYPE html>
<html>
  <head>
    <title>Something went wrong</title>
  </head>
  <body>
    <div>
      <h1>500 - There was an error on the server</h1>
      <p>Sorry, we encountered an internal server error. Please try again later.</p>
      <p><strong>Error Message: </strong><?php echo htmlspecialchars($e->getMessage()); ?></p>
      <a href="?c=dashboard&m=index">Go to Dashboard</a>
    </div>
  </body>
</html>
