<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php" ?>

    <form action="./LoginHandler.php" method="post">
      <label for="username"> Username: </label>
      <input type="text" id="username" name="username"> </br>
      <label for="password"> Password: </label>
      <input type="password" id="password" name="password"> </br>
      <input type="submit" value="Login">
    </form> </br>
    <p> or: </p> <a href="CreateAccount.html">Create Account</a>

</body>
</html>
