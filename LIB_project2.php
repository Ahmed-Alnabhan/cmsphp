<?php
//***********************************************************************************************************************************
// The following section contains the variables definition                                                                          *
//*********************************************************************************************************************************** 
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
   // array of the websites
   $arrWebsites = array();
   // array of selected students
   $studentsNo = array();
//***********************************************************************************************************************************
// The following section contains all the required functions                                                                        *
//***********************************************************************************************************************************   
   // Builds the banner for each page in project1
   function buildBanner(){
    echo '<div id="banner">';         
	 $ad = chooseAd();	 
    echo '<img src="images/logo.jpg" alt="newspaper logo" height="150" width="150"/>' . "\n" .
      '<img src="' . $ad . '" alt="newspaper Ad" height="150" width="1024"/>' . "\n" .
    '</div> <!-- closing the banner-->';
   }
//***********************************************************************************************************************************  
   // Builds the Navigation bar for each page in project1
   function buildNavBar(){
      echo '<div id="navbar">' . "\n" .
       '<ul>' . "\n" .
        '<li><a href="index.php">Home</a></li>' . "\n" .
        '<li><a href="news.php">News</a></li>' . "\n" .
        '<li><a href="admin.php">Admin</a></li>' . "\n" .
	'<li><a href="services.php">Services</a></li>' . "\n" .
       '</ul>' . "\n" .
     '</div> <!-- closing the navbar-->';
   }
//**********************************************************************************************************************************   
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
//**********************************************************************************************************************************    
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
           '<label><b> Enter the password: </b></label>'  . "\n" .
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
                  $rssFeed->createRSS(NEWS_FILE);
              }
              else {
                    echo '<p style="color:#ff0000;"><b> Please, enter the title!!! </b></p>';
              }
           }
           
      echo '<form method="post" name="f_posting" action="admin.php">'  . "\n" .
           '<input type="text" size="75" value="wirte the title here..." name="txt_title">'  . "<br/><br/>\n" .
           '<textarea rows="20" cols="75" name="txt_posting"></textarea>'  . "<br/><br/>\n" .
           '<label><b> Enter the password: </b></label>'  . "\n" .
           '<input type="password" name="pwd_posting"/>'  . "<br/><br/>\n" .
           '<input type="submit" name="btn_submit_posting" value="Add the new posting!"/>'  . "\n" .
           '<input type="reset" name="btn_reset_posting" value="Reset form!"/>'  . "\n" .
           '</form>'  . "\n" .
	   '<hr/>' . "\n";
	   // calling the function of the students list
	   classmatesList();
	   webServicesList();
      echo '</div> <!-- closing the contentarea-->';
      
       
   }
//**********************************************************************************************************************************    
   // Sanitizes the input in the forms
   // it accepts the text from the caller
   // it returns the sanitized text to the caller
   function sanitizeIt($txt)
   {      
      $txt = trim($txt);
      $txt = strip_tags($txt);
      $txt = htmlentities($txt);
      $txt = stripslashes($txt);     
      $txt = str_replace("\n"," ",$txt);
      return $txt;
   }
//**********************************************************************************************************************************    
   // This function is used to write strings to file
   // it takes a string and the file name as parameters and write the string to the file
   function writeToFile($string,$filename){      
      file_put_contents($filename, $string, FILE_APPEND);
   }
//**********************************************************************************************************************************    
   // This function is used to read strings from file
   // it takes the filename as a parameter and return its contents as an array
   function readFromFile($filename){
      
      $string = file_get_contents($filename);
      $content = explode("\r",$string);
      return $content;
   }
//**********************************************************************************************************************************    
   // left editorial letter in index.php
   function editorialLetter(){      
      echo '<div id="rightc">' . "\n" .
           '<h3> A letter form the editor </h3>' . "\n" .
           '<img src="images/amjn.jpg" alt="the editor" height="247" width="200" />' . "\n";      
           $myContent = readFromFile(EDITORIAL_FILE);
            foreach($myContent as $line){
               echo '<p>' . $line . '</p>';
            }
      echo'</div> <!-- closing the leftc-->  ';
   }
//**********************************************************************************************************************************    
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
                  echo '<p style="color:#ff0000;"><b> INVALID PASSWORD. Try again </b></p>';
                  return false;
            }
         }
      
   }
//**********************************************************************************************************************************    
   // This unction add the latest 3 news to the index page
   function displayLatest(){
      echo '<div id="firstcontentarea">';
      displayNews(3);
      echo "<hr/>";
      displayWebServices();
      echo '</div> <!-- closing the displayLatest-->';      
   }
//**********************************************************************************************************************************    
   // This function reads and displays a specific no. of news and contains the permalinks
   function readNews($lines){
      $counter = 1;
      $arrayOfNews = file(NEWS_FILE);
      $arrayOfNews = array_reverse($arrayOfNews);
      $NoOfnews = count($arrayOfNews);
       if(!isset($_GET['page'])){
         $_GET['page']=0;
      }      
      
      if(isset($_GET['id']) && isset($_GET['page'])){
         list($first,$second,$third)=explode("|||",$arrayOfNews[$_GET['id']]);
	 echo '<div class="headings">'.$first.'</div><div class="date">' .$second .'</div><div class="paragraph">'. $third. '</div> <a href="'.$_SERVER['HTTP_REFERER'].'"> BACK </a>';
      }
      else{
	 $NoOfpages = ceil($NoOfnews/$lines);
	 for($i=0;$i<$NoOfpages;$i++){
	 echo '<a href="news.php?page='.$lines*$i.'" style="border: 1px solid #cccccc;">' . ($i+1).'</a>';
      }
      
      if (isset($_GET['page'])){
	  $start = $_GET['page'];
	  $end = $_GET['page'] + $lines;
	  for ($j=$start;$j<$NoOfnews;$j++){
	     if($start<$end){
	     list($first,$second,$third)=explode("|||",$arrayOfNews[$j]);
	     echo '<div class="headings"><a href="news.php?page='.$_GET['page'].'&id='.$j.'">'.$first.'</a></div><div class="date">' .$second .'</div><div class="paragraph">'. $third. '</div>';
	     $start++;
             }      
          }      
      }
      }
   }
//**********************************************************************************************************************************    
   // This function builds the content of news page
   function newsContent(){
      echo '<div id="contentarea">' ."\n";    
      echo '<form method="post" action="news.php">
           <label><span style="font-weight: bold; color:#8B7E66;"> Enter Number of news items for each page:</span></label>
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
//**********************************************************************************************************************************    
   // this function chooses the ad and sends it to the banner of the current page
   function chooseAd(){        
      $returnedValue = "";
      $string = "";
      $arrayOfBanners = file(BANNERS_FILE);           
      $counter =0;
      foreach($arrayOfBanners as $banner){
      list($n,$r,$w) = explode(',',$banner);
      $arrOfFiles[$counter]=$n;
      $arrOfIterations[$counter]=$r;
      $arrOfweights[$counter]=$w;
      $counter++;
      }
       $noOfFiles = count($arrOfFiles);
       $max = $arrOfIterations[0];
       for($i=0;$i<$noOfFiles-1;$i++)
      {
       
       
	if ($arrOfIterations[$i]===$arrOfIterations[$i+1])
       {
	$returnedValue = $arrOfFiles[$i];
	$arrOfIterations[$i] = $arrOfIterations[$i] + 1;		
       }
       else {
	$returnedValue = $arrOfFiles[$i+1];
	$arrOfIterations[$i+1] = $arrOfIterations[$i+1] + 1;
       }
      
      }
       for($i=0;$i<$noOfFiles;$i++)
      {
       $string .= trim($arrOfFiles[$i].",". $arrOfIterations[$i].",".$arrOfweights[$i])."\r\n";
      }
      file_put_contents(BANNERS_FILE,$string);
      return $returnedValue;
   }
//**********************************************************************************************************************************    
   // This function creates and manipulates the classmates list
    function classmatesList(){
      // reading the xml file
      echo '<h3> classMates RSS Feeds Control Panel </h3>';       
       echo '<form method="post" name="f_class" action="admin.php">'  . "\n";
       $doc = new DOMDocument();
       $doc->load('rss_class.xml');
       //the counter that increases the index of the array
       $count = 0;
       $news = $doc->getElementsByTagName('student');
       foreach($news as $item){
	 $firstName = $item->getElementsByTagName('first')->item(0)->nodeValue;	
	 $lastName = $item->getElementsByTagName('last')->item(0)->nodeValue;
	 $url = $item->getElementsByTagName('url')->item(0)->nodeValue;
	 $arrStudents[$count] = $firstName." ".$lastName;
	 
	 echo '<input type="checkbox" name="classMates[]" value="'.$firstName."|".$lastName."|".$url.'"/> '.$arrStudents[$count] . '<br/>';
       }      
	 echo '<br/><label><b> Enter the password: </b></label>'  . "\n" .
              '<input type="password" name="pwd_class"/>'  . "<br/><br/>\n" .
              '<input type="submit" name="btn_submit_class" value="Save!"/>'  . "\n" .
              '<input type="reset" name="btn_reset_class" value="Reset form!"/>'  . "\n" .
              '</form>'  . "\n";
             
       if(isset($_POST['btn_submit_class'])){
        
	 if(empty($_POST['classMates'])){
	    echo '<p style="color:#ff0000;font-weight">ATTENTION: You have not selected any student.</p>';	   
	  } 
	  else {
	  $studentsNo = $_POST['classMates'];
	  //write the content of the array to xml file
	  $fileName = 'selectedStudents.xml';
	  $doc=new DOMDocument();
	  
	  $news=$doc->createElement('news');
	  $doc->appendChild($news);         
	  //$content="";
	  foreach($studentsNo as $key => $value){
              list($name,$last,$link)=explode('|',$value);
	      $student = $doc->createElement('student');
	      $news->appendChild($student);
	      $student->setAttribute('selected','yes');
	      $stdName = $doc->createElement('first');
	      $student->appendChild($stdName);
	      $sNTxt = $doc->createTextNode($name);
	      $stdName->appendChild($sNTxt);
	      $stdLast = $doc->createElement('last');
	      $student->appendChild($stdLast);
	      $sLTxt = $doc->createTextNode($last);
	      $stdLast->appendChild($sLTxt);
	      $stdLink = $doc->createElement('link');
	      $student->appendChild($stdLink);
	      $linkTxt = $doc->createTextNode($link);
	      $stdLink->appendChild($linkTxt);
   
	  }
	  
	    $password = $_POST['pwd_class'];
	    $flag = true;
	    $no = count($studentsNo);
	    if ($no<=10){
	      	
	         if($password === PASSWORD) { 
	             $doc->save($fileName);
		     
		     
		     $flag = false;
	         }          
                 else {
                     echo '<p style="color:#ff0000;"><b> INVALID PASSWORD. Try again </b></p>';
		     $flag = true;
	         }
	    }
	    else{  
	      echo '<p style="color:#ff0000;">you have to choose no more than 10 students</p>';
	    }

	   }
       }
       // call the function that display the selected classmates
      $array=array('student','first','last');
      selectedItems('selectedStudents.xml',$array);
    echo  '<hr/>' . "\n";
   }// end of classmatesList()
//**********************************************************************************************************************************    
   // this function displays the selected classmates on the admin page
   function selectedItems($fileName,$arr){
            $doc = new DOMDocument();
	    $doc->load($fileName);
	    $items = $doc->getElementsByTagName($arr[0]);
	    $counter = count($items);
	    echo '<br/>You selected the following '. $arr[0].'(s): <br/><br/>';
	    echo '<ul>';
	    foreach($items as $item){
	     $first = $item->getElementsByTagName($arr[1])->item(0)->nodeValue;
	     $last = $item->getElementsByTagName($arr[2])->item(0)->nodeValue;
	     echo '<li style="color:#0000ff;font-weight:bold;">'.$first." ".$last.'</li>';
	    }   
	    echo '</ul>';
   }
//********************************************************************************************************************************** 
   // This function displays the latest 2 news for selected students
   function display2News(){
     //reading the xml file of selected students
     echo '<a href="http://people.rit.edu/~amn5948/739/project2/project2.rss"> project2.rss </a>';
     $doc = new DOMDocument();
     $doc->load('selectedStudents.xml');
     $students = $doc->getElementsByTagName('student');
     foreach($students as $student){
        $firstName = $student->getElementsByTagName('first')->item(0)->nodeValue;
	$firstLast = $student->getElementsByTagName('last')->item(0)->nodeValue;
	$url = $student->getElementsByTagName('link')->item(0)->nodeValue;
	echo '<div><h2 style="text-align:center;">'.$firstName." ".$firstLast.'</h2></div>';
	//$url = " http://people.rit.edu/~amn5948/739/project2/project2.rss ";
	//echo $url;
	readRss($url,2);
     }
   }
//**********************************************************************************************************************************    
   function readRss($file,$noOfFeeds){
     $doc = new DOMDocument();
     $file=trim($file);
     
     $header_response = get_headers($file, 1);
     if ( strpos( $header_response[0], "404" ) !== false )
        {
         echo '<h3 style="color:#ff0000;"> There is a problem in the feed </h3> ';
	 echo '<hr/>';
        } 
     else 
        {
	   $doc->load($file);
	   $items = $doc->getElementsByTagName('item');
	   $count=1;
	   foreach($items as $item){
	      if($count<=$noOfFeeds){
	      $news = $item->getElementsByTagName('description')->item(0)->nodeValue;
	      $title = $item->getElementsByTagName('title')->item(0)->nodeValue;
	      $date = $item->getElementsByTagName('pubDate')->item(0)->nodeValue;
	      $link = $item->getElementsByTagName('link')->item(0)->nodeValue;
	      echo '<div class="headings"><a href="'.$link.'">' .$title.'</a></div><div class="date">' .$date .'</div><div class="paragraph">'.$news.'</div>';
	      $count++;
	      }	      
           }
	      echo '<hr/>';
        }
   }//end of readRss()
//**********************************************************************************************************************************    
   // This function builds and manipulate the website services list
   function webServicesList(){
      // reading the xml file      
       echo '<h3> Websites Services Control Panel </h3>';
       echo '<form method="post" name="f_websites" action="admin.php">'  . "\n";
       $doc = new DOMDocument();
       $doc->load('rss_class.xml');
       //the counter that increases the index of the array
       $count = 0;
       $websites = $doc->getElementsByTagName('choice');
       foreach($websites as $web){
	 $name = $web->getElementsByTagName('name')->item(0)->nodeValue;	
	 $url = $web->getElementsByTagName('url')->item(0)->nodeValue;	 
	 $arrWebsites[$count] = $name;
	 
	 echo '<input type="checkbox" name="webSites[]" value="'.$name."|".$url.'"/> '.$arrWebsites[$count] . '<br/>';
       }      
	 echo '<br/><label><b> Enter the password: </b></label>'  . "\n" .
              '<input type="password" name="pwd_web"/>'  . "<br/><br/>\n" .
              '<input type="submit" name="btn_submit_web" value="Save!"/>'  . "\n" .
              '<input type="reset" name="btn_reset_web" value="Reset form!"/>'  . "\n" .
              '</form>'  . "\n";
             
       if(isset($_POST['btn_submit_web'])){
        
	 if(empty($_POST['webSites'])){
	    echo '<p style="color:#ff0000;font-weight">ATTENTION: You have not selected any website.</p>';	   
	  } 
	  else {
	  $websiteNo = $_POST['webSites'];
	  //write the content of the array to xml file
	  $fileName = 'selectedWebsites.xml';
	  $doc=new DOMDocument();
	  
	  $web=$doc->createElement('web');
	  $doc->appendChild($web);         
	  //$content="";
	  foreach($websiteNo as $key => $value){
              list($name,$url)=explode('|',$value);
	      $choice = $doc->createElement('choice');
	      $web->appendChild($choice);
	      $choice->setAttribute('selected','yes');
	      $webName = $doc->createElement('first');
	      $choice->appendChild($webName);
	      $wNTxt = $doc->createTextNode($name);
	      $webName->appendChild($wNTxt);
	      $webURL = $doc->createElement('url');
	      $choice->appendChild($webURL);
	      $wUTxt = $doc->createTextNode($url);
	      $webURL->appendChild($wUTxt);
       	  }
	  
	    $password = $_POST['pwd_web'];
	    $flag = true;
	    $no = count($websiteNo);
	    if ($no<=3){
	      	
	         if($password === PASSWORD) { 
	             $doc->save($fileName);	     
		     $flag = false;
	         }          
                 else {
                     echo '<p style="color:#ff0000;"><b> INVALID PASSWORD. Try again </b></p>';
		     $flag = true;
	         }
	    }
	    else{  
	      echo '<p style="color:#ff0000;">you have to choose no more than 3 websites</p>';
	    }

	   }
       }
       // call the function that display the selected classmates
      $array=array('choice','first','url');
      selectedItems('selectedWebsites.xml',$array);
    
   } // end of the webServicesList()
//**********************************************************************************************************************************    
   // This function displays the latest news item from the selected webservises
   function displayWebServices(){
     //reading the xml file of selected students
     
     $doc = new DOMDocument();
     $doc->load('selectedWebsites.xml');
     $websites = $doc->getElementsByTagName('choice');
     foreach($websites as $website){
        $name = $website->getElementsByTagName('first')->item(0)->nodeValue;
	$url = $website->getElementsByTagName('url')->item(0)->nodeValue;
	echo '<div><h2 style="text-align:center;">'.$name.'</h2></div>';
	//$url = " http://people.rit.edu/~amn5948/739/project2/project2.rss ";
	//echo $url;
	readRss($url,1);
     }
   }
//**********************************************************************************************************************************    
   // This function reads and displays a specific no. of news
   function displayNews($lines){
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
?>

  
