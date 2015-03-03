<?php
//SurveyUtil_inc.php

class SurveyUtil {

  public static function responseList($myID) {
    $myReturn = '';

    $sql = "SELECT DateAdded,ResponseID FROM wn15_responses WHERE SurveyID=$myID";

    #reference images for pager
    $prev = '<img src="' . VIRTUAL_PATH . 'images/arrow_prev.gif" border="0" />';
    $next = '<img src="' . VIRTUAL_PATH . 'images/arrow_next.gif" border="0" />';

    # Create instance of new 'pager' class
    $myPager = new Pager(10,'',$prev,$next,'');
    $sql = $myPager->loadSQL($sql);  #load SQL, add offset

    # connection comes first in mysqli (improved) function
    $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

    if(mysqli_num_rows($result) > 0)
    {#records exist - process
      if($myPager->showTotal()==1){$itemz = "survey";}else{$itemz = "surveys";}  //deal with plural
        echo '<div align="center">We have ' . $myPager->showTotal() . ' ' . $itemz . '!</div>';
      while($row = mysqli_fetch_assoc($result))
      {# process each row
           $myReturn .= '<div align="center"><a href="' . VIRTUAL_PATH . 'surveys/response_view.php?id=' . (int)$row['ResponseID'] . '"> '  . dbOut($row['DateAdded']) . '</a>';
           $myReturn .= '</div>';
           /*
           echo "Date Added: " . dbOut($row['DateAdded']) . "<br />";
           echo "Survey Description: " . dbOut($row['Description']) . "<br />";*/
      }
      echo $myPager->showNAV(); # show paging nav, only if enough records  
    }else{#no records
      echo "<div align=center>What! No surveys?  There must be a mistake!!</div>";  
    }
    @mysqli_free_result($result);



    return $myReturn;
  }#end responseList

}#end SurveyUtil class