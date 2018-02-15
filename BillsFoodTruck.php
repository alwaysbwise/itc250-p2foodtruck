<?php
/**
 * item-demo2.php, based on demo_postback_nohtml.php is a single page web application that allows us to take an order from a user and display the cost
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
 * @author Bria Wise <brian.wise@alwaysbwise.com>
 * @version 1.0 2018/01/31
 * @link http://www.brianwise.xyz
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @todo finish formatting
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
include 'items.php'; 

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
{# shows form so user can enter their order.  Initial scenario
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
              
              echo '<p>' . $item->Name . ' <input type="number" size="5" name="item_' . $item->ID . '" /></p>';
             
            //Loops through the Extras
            foreach($item->Extras as $extra)
              {
                  echo '<p>' . $extra . '<input type="checkbox" value="' . $extra . '" name="extra_[]" /></p>';
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

    global $config;
    
    //instantiate the Item class
    foreach($config->items as $item)
    {
        //parse through by id value to return price per item
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
  	
	//instantiate variables for use 
	 $subtotal = 0;
	 $itemSubtotal = 0;

	$count = count($_POST['extra_']);   

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
            
          if($value > 0)  {
            $thisItemPrice = getItem($id);
            $itemSubtotal = $thisItemPrice * $value;
            $subtotal += $itemSubtotal;
            
		    echo "<p>You ordered $value of item number $id at a price of $thisItemPrice</p>";	
            echo "<p>Your subtotal: $itemSubtotal</p>";
          }
            
        }//end if from form
        
       

    }//end foreach
    $countE = $count * .25;
    //echo "<p> $countE </p>";
    $tax = $subtotal*.1;
    $total = $subtotal + $tax;
    $subtotal = $subtotal + $countE;
    echo "<br><br><h3>Bill</h3>";
    echo "<p>Extras Subtotal: $countE</p>";
	echo "<p>Subtotal: $subtotal</p>";
	echo "<p>Tax: $tax</p>";    
	echo "<p>Total: $total</p>";
	
	
	
	echo '<p align="center"><a href="' . THIS_PAGE . '">RESET</a></p>';
	get_footer(); #defaults to footer_inc.php
}
?>


