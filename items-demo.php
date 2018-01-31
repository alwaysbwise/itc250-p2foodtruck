<?php
/**
 * item-demo2.php, based on demo_postback_nohtml.php is a single page web application that allows us to request and view 
 * a customer's name
 *
 * web applications.
 *
 * Any number of additional steps or processes can be added by adding keywords to the switch 
 * statement and identifying a hidden form field in the previous step's form:
 *
 *<code>
 * <input type="hidden" name="act" value="next" />
 *</code>
 * 
 * The above live of code shows the parameter "act" being loaded with the value "next" which would be the 
 * unique identifier for the next step of a multi-step process
 *
 * @package ITC281
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 1.1 2011/10/11
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @todo finish instruction sheet
 * @todo add more complicated checkbox & radio button examples
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
include 'items.php'; 
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

//END CONFIG AREA ----------------------------------------------------------

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

switch ($myAction) 
{//check 'act' for type of process
	case "display": # 2)Display user's name!
	 	showData();
	 	break;
	default: # 1)Ask user to enter their name 
	 	showForm();
}

function showForm()
{# shows form so user can enter their name.  Initial scenario
	global $config;
    get_header(); #defaults to header_inc.php	
	
	echo 
	'<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.YourName,"Place Order")){return false;}
			return true;//if all is passed, submit!
		}
	</script>
	<h3 align="center">' . smartTitle() . '</h3>
	<p align="center">Place Your Order</p> 
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
             ';
  
    
		foreach($config->items as $item)
          {
            //echo "<p>ID:$item->ID  Name:$item->Name</p>"; 
            //echo '<p>Taco <input type="text" name="item_1" /></p>';
              
              echo '<p>' . $item->Name . ' <input type="number" name="item_' . $item->ID . '" /></p>';
             
            //Loops through the Extras
            foreach($item->Extras as $extra)
              {
                  echo '<p>' . $extra . '<input type="checkbox" name="extra_' . $extra . '" /></p>';
              }
              
          }
        
          echo '
				<p>
					<input type="submit" value="Place Order">
				</p>
		<input type="hidden" name="act" value="display" /></form>';
	get_footer(); #defaults to footer_inc.php
}//end showForm()



function getItem($id)
{ 
    /*
    This returns the price but I'm still struggling with using the $id
    to get through the $config->items
    
        foreach($config->items as $item)
    {
        $price = $value * $item->Price;
      return $price;
    }
    */
    global $config;
    
    foreach($config->items as $item)
    {
       if($id == $item->ID)
       {
      return $item->Price;
       }
    }
}


function showData()
{#form submits here we show entered name
	
    //dumpDie($_POST);
     get_header(); #defaults to footer_inc.php
	
	
	echo '<h3 align="center">' . smartTitle() . '</h3>';
	
	foreach($_POST as $name => $value)
    {//loop the form elements
        
        //if form name attribute starts with 'item_', process it
        if(substr($name,0,5)=='item_')
        {
            //explode the string into an array on the "_"
            $name_array = explode('_',$name);

            //id is the second element of the array
			//forcibly cast to an int in the process
            $id = (int)$name_array[1];
            
            
            $thisItem = getItem($id);
            
            $subtotal = $thisItem * $value;
            $subtotal += $subtotal;
			
			
            echo "<p>You ordered $value of item number $id</p>";
            echo "<p>Your subtotal: $subtotal</p>";
            
            
        }
        
        

    }
/*    $tax = $subtotal*.1;
    $total = $subtotal;
	echo "<p>Subtotal: $subtotal</p>";
	echo "<p>Tax: $tax</p>";    
	echo "<p>Total: $total</p>";*/
	
	
	
	echo '<p align="center"><a href="' . THIS_PAGE . '">RESET</a></p>';
	get_footer(); #defaults to footer_inc.php
}
?>


