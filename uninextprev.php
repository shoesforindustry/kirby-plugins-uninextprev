<?php
/**
 * uniNextPrev plugin
 *
 * It provide variables with links and page titles for next/prev pages and can move through the site,
 * up and down, through parent/children/grandchildren.
 * Also seamlessly moves through pagination (e.g. articles index) if you set it.
 * So a sort of universal next / prev, hence 'uniNextPrev'
 *
 * WARNING: ONLY works at parent/child/grandchild menu levels - fails if you go any deeper!
 * Four levels is probably too deep for a menu anyway - but you have been warned.
 *
 * WARNING: You must set a comma seperated quoted list of page uid's
 * for which you want to ignore the children of in next/prev, in your config.php
 *  e.g.
 *    c::set('uniNextPrev_ignore_children',array('press','articles','about'));
 *  It can be empty
 *    c::set('uniNextPrev_ignore_children',array(''));
 *
 * Basic call uniNextPrev plugin in yoru template:
 *  $uniNextPrev = uninextprev();
 *
 * Call uniNextPrev plugin with pagination (call after setting pagination!)
 *  $uniNextPrev = uninextprev(array('pagination' => $articles->pagination()));
 *
 * See README for much more information!
 *
 * @author Russ Baldwin
 * @version v0.1.6
 * @copyright shoesforindustry.net, 06 Nov, 2014
 * @package Kirby Plugin
**/

function uninextprev($options = array()) {

  // default values
  $defaults = array(
    'pagination' => ''
  );

  // variables
  $nextPageURL='';
  $nextPageTitle='';
  $prevPageURL='';
  $prevPageTitle='';

  // merge defaults and options
  $options = array_merge($defaults, $options);
  $pagination = $options['pagination'];
  //uniNextPrev_ignore_children list is pulled from config.php so set it!
  $ignoreChildren = c::get('uniNextPrev_ignore_children',array());

  // ***************************************************

  // Next

  // We have pagination set when calling the plugin
  if($pagination AND $pagination->hasNextPage()){
   $nextPageURL=$pagination->nextPageURL();
   $nextPageTitle=page()->title().': '.$pagination->nextPage();
  }
  // We have visible children so jump down into them
  elseif (page()->hasVisibleChildren() AND !in_array(page()->uid(), $ignoreChildren) ) {
   $nextPageURL=page()->children()->first()->url();
   $nextPageTitle=page()->children()->first()->title();
  }

  // We are a parent & have run out of children - jump back up a level if we can
  elseif (!page()->hasNextVisible() and page()->parent()->uid()<>'' ) {
    if (!page()->parent()->nextVisible()){ // Jump up two levels if we are a grandchild
      $nextPageURL=page()->parent()->parent()->nextVisible()->url();
      $nextPageTitle=page()->parent()->parent()->nextVisible()->title();
    }else{ // Jump up one levels if we are a child
      $nextPageURL=page()->parent()->nextVisible()->url();
      $nextPageTitle=page()->parent()->nextVisible()->title();
    }
  }

  // Basic next page
  elseif (page()->hasNextVisible()){
   $nextPageURL=page()->nextVisible()->url();
   $nextPageTitle=page()->nextVisible()->title();
  }

// ***************************************************

// Previous
if (!page()->isHomePage()) {

// We have pagination set when calling the plugin
if ($pagination AND $pagination->hasPrevPage() ){
  $prevPageURL=$pagination->prevPageURL();
  $prevPageTitle=page()->title().': '.$pagination->prevPage();
}

// Jump to previous parents parent
elseif (page()->depth()==3 AND !page()->prevVisible()){
  $prevPageURL=page()->parent()->url();
  $prevPageTitle=page()->parent()->title();
}

// Jump to previous parent
elseif (page()->depth()==2 AND !page()->prevVisible()){
  $prevPageURL=page()->parent()->url();
  $prevPageTitle=page()->parent()->title();
}

// Previous page has children/perhaps grandchildren
elseif (page()->prevVisible()->hasVisibleChildren() AND !in_array(page()->prevVisible()->uid(), $ignoreChildren,false)){

  if (page()->prevVisible()->children()->last()->hasChildren()){
    // Page has grandchildren
    $prevPageURL=page()->PrevVisible()->grandchildren()->last()->url();
    $prevPageTitle=page()->PrevVisible()->grandchildren()->last()->title();
  }else{
    // Page has children
    $prevPageURL=page()->PrevVisible()->children()->last()->url();
    $prevPageTitle=page()->PrevVisible()->children()->last()->title();
  }
}

elseif (page()->prevVisible()) {
  // Jump to previous page
  $prevPageURL=page()->PrevVisible()->url();
  $prevPageTitle=page()->PrevVisible()->title();
}

}

  $results=array(
    "nextPageURL"=>$nextPageURL,
    "nextPageTitle"=>$nextPageTitle,
    "prevPageURL"=>$prevPageURL,
    "prevPageTitle"=>$prevPageTitle
  );

  return $results;
}
