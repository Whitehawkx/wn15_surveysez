<?php
/**
 * survey_view.php along with survey_list.php provides a list/view application
 *
 * The difference between demo_list.php and demo_list_pager.php is the reference to the
 * Pager class which processes a mysqli SQL statement and spans records across multiple
 * pages.
 *
 * The associated view page, demo_view_pager.php is virtually identical to demo_view.php.
 * The only difference is the pager version links to the list pager version to create a
 * separate application from the original list/view.
 *
 * @package SurveySez
 * @author Surabhi Agrawal <agrawal.surabhi16@gmail.com>
 * @version 1.0 2015/02/03
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see survey_list.php
 * @see survey_list.php
 * @see Pager_inc.php
 * @todo add class code
 * @todo create survey_view.php page
 */

# '../' works for a sub-folder.  use './' for the root
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

# check variable of item passed in - if invalid data, forcibly redirect back to demo_list_pager.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "surveys/index.php");
}

/*
If result, show result
else if survey, show survey
else show sorry, no survey

if (result) {

} else if(survey){

} else {
  echo "Sorry! No survey!";
}
*/

$myResult = new Result($myID);
if($myResult->isValid) {
  $PageTitle = "'Result to " . $myResult->Title . "' Survey!";
}else{
  $mySurvey = new Survey($myID);
  if($mySurvey->isValid){
    $config->titleTag = $mySurvey->Title . " survey!"; #overwrite PageTitle with Muffin info!
  }else{
    $config->titleTag = "No such survey!";
  }
}

//dumpDie($mySurvey);

# END CONFIG AREA ----------------------------------------------------------

get_header(); #defaults to theme header or header_inc.php

echo '
<h3 align="center">' . $config->titleTag . '</h3>
';

if($myResult->isValid) {# check to see if we have a valid SurveyID
  echo "Survey Title: <b>" . $myResult->Title . "</b><br />";  //show data on page
  echo "Survey Description: " . $myResult->Description . "<br />";
  $myResult->showGraph() . "<br />";  //showTallies method shows all questions, answers and tally totals!
  echo SurveyUtil::responseList($myID);
  unset($myResult);  //destroy object & release resources
}else{
  if($mySurvey->isValid) { #check to see if we have a valid SurveyID
    echo "<b>" . $mySurvey->SurveyID . ") </b>";
    echo "<b>" . $mySurvey->Title . "</b>-->";
    echo "<b>" . $mySurvey->Description . "</b><br />";
    echo $mySurvey->showQuestions();
    echo SurveyUtil::responseList($myID);
  }else{
    echo "Sorry, no such survey!";
  }
}



get_footer(); #defaults to theme footer or footer_inc.php
