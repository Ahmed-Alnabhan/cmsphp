<?php
   //including the RSSFeed.class.php
   include('RSSFeed.class.php');
   // define the password as a constant
   define('PASSWORD','Ahmed');
   // define the edetorial file as a constant
   define('EDITORIAL_FILE','editorial.txt');
   // define the news file as a constant
   define('NEWS_FILE','news.txt');
   // define the banners file as a constant
   define('BANNERS_FILE','banners.txt');
   // array of news
   $arrayNews = array();
   // array of the students
   $arrStudents = array();
   // Builds the banner for each page in project1
   function buildBanner(){
    echo '<div id="banner">';
         $ad = chooseAd();//chooseAd();
    echo '<img src="images/logo.jpg" alt="newspaper logo" height="150" width="150"/>' . "\n" .
      '<img src="' . $ad . '" alt="newspaper Ad" height="150" width="1024"/>' . "\n" .
    '</div> <!-- closing the banner-->';
   }
   
   // Builds the Navigation bar for each page in project1
   function buildNavBar(){
      echo '<div id="navbar">' . "\n" .
       '<ul>' . "\n" .
        '<li><a href="index.php">Home</a></li>' . "\n" .
        '<li><a href="news.php">News</a></li>' . "\n" .
        '<li><a href="admin.php">Admin</a></li>' . "\n" .
       '</ul>' . "\n" .
     '</div> <!-- closing the navbar-->';
   }
   
   // Builds the footer for each page in project1
   function buildFooter(){
      echo '<div id="footer">' . "\n" .
      '<ul>' . "\n" .
      '<li> You are using: <span class="highlightedinfo">' . $_SERVER['HTTP_USER_AGENT'] .  '</span> browser </li>' . "\n" .
      '<li> Your IP address is: <span class="highlightedinfo">' . $_SERVER['REMOTE_ADDR'] . '</span></li>' . "\n";
      if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){ 
       echo '<li> You are coming from the page: <span class="highlightedinfo">' . $_SERVER['HTTP_REFERER'] . '</span></li>' . "\n";
      }
      echo '<li> You are using the port: <span class="highlightedinfo">' . $_SERVER['REMOTE_PORT'] . '</span></li>' . "\n" .      
      '</ul>' . "\n" .
    '</div> <!-- closing the footer-->';
   }
   
   // Builds the content of admin.php page
   function adminContent(){      
      echo '<div id="contentarea">' . "\n" .
           '<h2> Admin page </h2>'  . "\n" ;
           if(isset($_POST['submit'])){
               checkAndWrite($_POST['txt_editorial'], $_POST['pwd_editorial'],EDITORIAL_FILE);        
           }
         
      echo '<h4> Editorial letter editor: </h4>'  . "\n" .
           '<form method="post" name="f_editorial" action="admin.php">'  . "\n" .
           '<textarea rows="20" cols="75" name="txt_editorial"></textarea>'  . "<br/><br/>\n" .
           '<label from="pwd_editorial" /><b> Enter the password: </b></label>'  . "\n" .
           '<input type="password" name="pwd_editorial"/>'  . "<br/><br/>\n" .
           '<input type="submit" name="submit" value="Add the editorial article!"/>'  . "\n" .
           '<input type="reset" name="btn_reset" value="Reset form!"/>'  . "\n" .
           '</form>'  . "\n" .
           '<hr/>' . "\n" .
           '<h4> News posting editor: </h4>'  . "\n";
            date_default_timezone_set( 'America/New_York' );
	    $currentDate = date("F j, Y, g:i a");
            if(isset($_POST['btn_submit_posting'])){
              if($_POST['txt_title'] !== "" && $_POST['txt_title'] !== "wirte the title here..." ){
                  writeToFile($_POST['txt_title']."|||", NEWS_FILE);
                  writeToFile($currentDate . "|||", NEWS_FILE);
                  checkAndWrite($_POST['txt_posting'], $_POST['pwd_posting'],NEWS_FILE);
		  // creating project2.rss
		  $rssFeed = new RSSFeed();
                  $rssFeed->creeteRSS(NEWS_FILE);
              }
              else {
                    echo '<p style="color:red;"><b> Please, enter the title!!! </b></p>';
              }
           }
           
      echo '<form method="post" name="f_posting" action="admin.php">'  . "\n" .
           '<input type="text" size="75" value="wirte the title here..." name="txt_title">'  . "<br/><br/>\n" .
           '<textarea rows="20" cols="75" name="txt_posting"></textarea>'  . "<br/><br/>\n" .
           '<label from="pwd_editorial" /><b> Enter the password: </b></label>'  . "\n" .
           '<input type="password" name="pwd_posting"/>'  . "<br/><br/>\n" .
           '<input type="submit" name="btn_submit_posting" value="Add the new posting!"/>'  . "\n" .
           '<input type="reset" name="btn_reset_posting" value="Reset form!"/>'  . "\n" .
           '</form>'  . "\n" .
	   '<hr/>' . "\n";
	   // calling the function of the students list
	   classmatesList();
      echo '</div> <!-- closing the contentarea-->';
      
       
   }
   
   // Sanitizes the input in the forms
   // it accepts the text from the caller
   // it returns the sanitized text to the caller
   function sanitizeIt($txt)
   {      
      $txt = trim($txt);
      $txt = htmlentities($txt);
      $txt = stripslashes($txt);
      $txt = strip_tags($txt);
      $txt = html_entity_decode($txt);
      $txt = str_replace("\n"," ",$txt);
      return $txt;
   }
   
   // This function is used to write strings to file
   // it takes a string and the file name as parameters and write the string to the file
   function writeToFile($string,$filename){      
      file_put_contents($filename, $string, FILE_APPEND);
   }
   
   // This function is used to read strings from file
   // it takes the filename as a parameter and return its contents as an array
   function readFromFile($filename){
      
      $string = file_get_contents($filename);
      $content = explode("\r",$string);
      return $content;
   }
   
   // left editorial letter in index.php
   function editorialLetter(){
      echo '<div id="leftc">' . "\n" .
           '<h3> A letter form the editor </h3>' . "\n" .
           '<img src="images/amjn.jpg" alt="the editor" height="247" width="200" />' . "\n";      
           $myContent = readFromFile(EDITORIAL_FILE);
            foreach($myContent as $line){
               echo '<p>' . $line . '</p>';
            }
      echo'</div> <!-- closing the leftc-->  ';
   }
   
   // This function validates the form contents and checks the password correctness if everything is well it writes the content of the form to a file
   // it takes password, posting, submit and file name as parameters
   // it writes the content of the textarea to the file or prints out some messages according to the result of coparisons
   function checkAndWrite($string, $password, $filename){
      
         if(!empty($string)){
            if($password === PASSWORD) {
                  $string = sanitizeIt($string);
                  writeToFile($string . "\r\n",$filename);
                  echo '<p style="color:#7F7F7F;"><b> Your post was written successfully!!!! </b></p>';
                  return true;
            }
            else {
                  echo '<p style="color:red;"><b> INVALID PASSWORD. Try again </b></p>';
                  return false;
            }
         }
      
   }
   
   // This unction add the latest 3 news to the index page
   function displayLatest(){
      echo '<div id="firstcontentarea">';
      readNews(3);
      echo '</div> <!-- closing the displayLatest-->';      
   }
   
   // This function reads and displays a specific no. of news
   function readNews($lines){
      $counter = 1;
      $arrayOfNews = file(NEWS_FILE);
      $arrayOfNews = array_reverse($arrayOfNews);
      foreach($arrayOfNews as $line){
	 if($counter<=$lines){
         list($first,$second,$third)=explode("|||",$line);
	 echo "<div class=\"headings\">" .$first."</div><div class=\"date\">" .$second ."</div><div class=\"paragraph\">$third</div>";
         $counter++;
      }
      }
      
   }
   
   // This function builds the content of news page
   function newsContent(){
      echo '<div id="contentarea">' ."\n";
           
      echo '<form method="post" action="news.php">
           <label from="txt_items" name="pages"><span style="font-weight: bold; color:#8B7E66;"> Enter Number of news items for each page:</span></label>
           <input type="text" name="txt_items" size="2"/>
           <input type="submit" value="Confirm!" name="itemsNo"/>
           </form>';
           if(isset($_POST['itemsNo'])){
            $newsNo = $_POST['txt_items'];            
            readNews($newsNo);
           }
           else {
            $newsNo = 5;
           readNews($newsNo);                     
           }
      echo '</div> <!-- closing the contentarea-->';
   }
   
   // this function chooses the ad and sends it to the banner of the current page
   function chooseAd(){      
      $lag = false;
      $returnedValue = "";
      $string = "";
      $counter = 0;
      $arrayOfBanners = file(BANNERS_FILE);           
      list($f, $s, $t) = explode(',',$arrayOfBanners[0]); 
      $max = $s;  
      foreach($arrayOfBanners as $banner){
         list($first, $second, $third) = explode(',',$banner); 
         if($second <= $max){
            $second++; 
            return $first;
            $max++;           
         }
         
         $string .= trim($first)."," . trim($second) . "," . trim($third) . "\r\n"; //echo $string;
         
        
      } 
            file_put_contents(BANNERS_FILE, $string);
            return $returnedValue;  
      
   }
   
   // This function creates and manipulates the classmates list
    function classmatesList(){
      // reading the xml ile
       echo '<form method="post" name="f_posting" action="admin.php">'  . "\n";
       $doc = new DOMDocument();
       $doc->load('http://people.rit.edu/~dmgics/739/project2/rss_class.xml');
       //the counter that increases the index of the array
       $count = 0;
       $news = $doc->getElementsByTagName('student');
       foreach($news as $item){
	 $firstName = $item->getElementsByTagName('first')->item(0)->nodeValue;	
	 $lastName = $item->getElementsByTagName('last')->item(0)->nodeValue;
	 $arrStudents[$count] = $firstName." ".$lastName;
	 echo '<input type="checkbox" name="'.$firstName.'" value="'.$firstName.'" /> '.$arrStudents[$count] . '<br/>';
       }      
	 echo '<br/><label from="pwd_class" /><b> Enter the password: </b></label>'  . "\n" .
              '<input type="password" name="pwd_class"/>'  . "<br/><br/>\n" .
              '<input type="submit" name="btn_submit_class" value="Save!"/>'  . "\n" .
              '<input type="reset" name="btn_reset_class" value="Reset form!"/>'  . "\n" .
              '</form>'  . "\n" .
	      '<hr/>' . "\n";
       
   }// end of classmatesList()
?>

  
