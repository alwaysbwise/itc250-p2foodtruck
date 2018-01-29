<?php
require '../inc_0700/config_inc.php';
include 'items.php';
if(isset($_REQUEST['act']))
{
  $myAction = (trim($_REQUEST['act']));
}else
{
  $myAction = "";
}
switch ($myAction){
  case "display":
    showData();
    break;
  default:
    showForm();
}
function showForm(){
  global $config;
  get_header();
  echo
	'<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.YourName,"Please Enter Your Name")){return false;}
			return true;//if all is passed, submit!
		}
	</script>
	<h3 align="center">' . smartTitle() . '</h3>
	<p align="center">Please Enter Quantities of Each Item</p>
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
             ';
		foreach($config->items as $item)
        {
        echo '<p>' . $item->Name . ' <input type="text" name="item_' . $item->ID . '" /></p>';
        //$_COOKIE['Price'] = $item->Price;
        }
    echo '
      <p>
        <input type="submit" value="Submit Quantities"><em>(<font color="red"><b>*</b> required field</font>)</em>
      </p>
    <input type="hidden" name="act" value="display" />
    </form>
    ';
    get_footer();
}
function showData(){
  get_header();
  echo '<h3 align="center">' . smartTitle() . '</h3>';
    
    function calculateTotal($value, $Price)
{
    $subTotal = (int)$value * (float)$Price;
    $taxRate = $subTotal * .1;
    $total = $taxRate + $subTotal;
    return $total;
}
  

	foreach($_POST as $name => $value)
    {
      if(substr($name,0,5)=='item_')
      {
      $name_array = explode('_',$name);
      $id = (int)$name_array[1];
      $Price = $item->$Price;
      $total = calculateTotal($value, $Price);
      echo "<p>You ordered $value of item number $id</p>";
      }
        
    }

echo "<p>Your total is: $total</p>";
    


echo '<p align="center"><a href="' . THIS_PAGE . '">RESET</a></p>';
get_footer(); #defaults to footer_inc.php


}
?>
