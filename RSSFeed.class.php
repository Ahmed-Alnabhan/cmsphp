<?php
  class RSSFeed {
  
  // create a string that contains the rss tags
  var $xmlContents = "";
        // the function that creates the rss file
        function createRSS($myFile){
                // define the filename
                $fileName = 'project2.rss';
                // creating the header of the rss file
                $doc = new DOMDocument();
                $doc->formatOutput=true;
                $rss = $doc->createElement('rss');
                $rss->setAttribute("version",2.0);
                $rss->formatOutput=true;
                $doc->appendChild($rss);
                $channel= $doc->createElement("channel");
                $rss->appendChild($channel);
                $title=$doc->createElement('title');
                $channel->appendChild($title);
                $titleText = $doc->createTextNode('Ahmed Alnabhan Project2 RSS Feed');
                $title->appendChild($titleText);
                $link = $doc->createElement('link');
                $channel->appendChild($link);
                $linkText= $doc->createTextNode('http://people.rit.edu/~amn5948/739/project2/project2.rss');
                $link->appendChild($linkText);
                $desc = $doc->createElement('description');
                $channel->appendChild($desc);
                $descText= $doc->createTextNode("RSS Feed of Ahmed's websites fro project2");
                $desc->appendChild($descText);
                $lang = $doc->createElement('language');
                $channel->appendChild($lang);
                $langText = $doc->createTextNode('en-us');
                $lang->appendChild($langText);
                $date = $doc->createElement('LastUpdated');
                $channel->appendChild($date);
                $dateText = $doc->createTextNode('Thur. Mar, 3 2012 16:45');
                $date->appendChild($dateText);
                // creating the items of the rss file
                    $counter = 1;
                    $arrayOfNews = file($myFile);
                    $arrayOfNews = array_reverse($arrayOfNews);
                    foreach($arrayOfNews as $line){                   
                      if($counter<11){
                       list($first,$second,$third)=explode("|||",$line);
                       $item = $doc->createElement('item');
                       $channel->appendChild($item);
                       $title = $doc->createElement('title');
                       $item->appendChild($title);
                       $titlText = $doc->createTextNode($first);
                       $title->appendChild($titlText);
                       $link = $doc->createElement('link');
                       $item->appendChild($link);
                       $itemTxt = $doc->createTextNode('http://people.rit.edu/~amn5948/739/project2/news.php');
                       $link->appendChild($itemTxt);
                       $desc = $doc->createElement('description');
                       $item->appendChild($desc);
                       $descTxt = $doc->createTextNode($third);
                       $desc->appendChild($descTxt);
                       $date = $doc->createElement('pubDate');
                       $item->appendChild($date);
                       $dateTxt = $doc->createTextNode($second);
                       $date->appendChild($dateTxt);                       
                       $counter++;
                     }
                    }     
                // save the xml tree in project2.rss
                $doc->save($fileName);                
                
        } //end of function
  } //end of the class
?>