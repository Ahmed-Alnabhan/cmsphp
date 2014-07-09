<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <title>Admin</title>
</head>

<body>
 <div id="container">
   <!--  The header-->
    <?php
      include('LIB_project2.php');
      //call the function that builds the banner
      buildBanner();
      //call the function that builds the navigation bar
      buildNavBar();
      //call the function that builds the content area
      adminContent();      
      
      //call the function that builds the footer 
      buildFooter();
    ?>
    
    
 </div> <!-- closing the container-->
</body>
</html>
