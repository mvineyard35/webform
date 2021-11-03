<?php
 // Tell server that you will be tracking session variables
 session_start( );
?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel = "stylesheet" href = "style.css">
            <!-- contacts.php - Keep track of contacts
            Michael Vineyard
            vineyarm@csp.edu
            prjwebform - Web Form for assignment 1
            11/3/2021
            -->
            <title>Contacts Form</title>
        </head>
        <body style = "background: linear-gradient(90deg, rgba(236,236,237,1) 0%, rgba(164,167,168,1) 100%) no-repeat; background-size: 100vw; height: 100vw; width: 100vw;">
            
            <?php 
            // The filename of the currently executing script to be used
            // as the action=" " attribute of the form element.
            $self = $_SERVER['PHP_SELF'];
            // Check to see if this is the first time viewing the page
            // The hidSubmitFlag will not exist if this is the first time
            if(array_key_exists('hidSubmitFlag', $_POST)) 
            {
                echo "<h2>Welcome back!</h2>";

                // Look at the hidden submitFlag variable to determine what to do
                $submitFlag = $_POST['hidSubmitFlag'];
                // echo "DEBUG: hidSubmitFlag is: $submitFlag<br />";
                // echo "DEBUG: hidSubmitFlag is type of: " . gettype($submitFlag) . "<br />";
                // Get the array that was stored as a session variable
                $contactsArray = unserialize(urldecode($_SESSION['serializedArray']));
                switch($submitFlag){
                    case "01": addContact();
                        break;
                    case "99": deleteContact();
                        break;
                    default: displayContacts($contactsArray);
                }
            }
            else
            {
                echo "<h2>Welcome to the Contacts Page</h2>";
                // First time visitor? If so, create the array
                // Create the contacts array
                $contactsArray = array();

                $contactsArray = array( );
                $contactsArray[0][0] ="Michael";
                $contactsArray[0][1] ="Vineyard";
                $contactsArray[0][2] ="555-555-5555";
                $contactsArray[0][3] ="name@csp.edu";

                $contactsArray[0][0] ="Joe";
                $contactsArray[0][1] ="Schmoe";
                $contactsArray[0][2] ="555-555-5555";
                $contactsArray[0][3] ="joe@shmoe.com";

                // Save this array as a serialized session variable
                $_SESSION['serializedArray'] = urlencode(serialize($contactsArray));
            }
            // echo("DEBUG: contactsArray:");
            // print_r($contactsArray);
            // echo("<br /><br />"); 
            
            /* =========================================
            Functions are alphabetical
            ========================================= */
            function addContact()
            {
                global $contactsArray;
                // Add the new information into the array
                // items stacked for readability
                $contactsArray[ ] = array($_POST['firstName'],
                $_POST['lastName'],
                $_POST['phone'],
                $_POST['email']);
                // The sort will be on the first column so use this to re-order the displays
                sort($contactsArray);
                // Save the updated array in its session variable
                $_SESSION['serializedArray'] = urlencode(serialize($contactsArray));
            } // end of addContact()
            function deleteContact()
            {
                global $contactsArray;
                global $deleteMe;
                // Get the selected index from the lstItem
                $deleteMe = $_POST['lstItem'];
                // echo "DEBUG: \$deleteMe is: " . $_POST['lstItem'];

                // Remove the selected index from the array
                unset($contactsArray[$deleteMe]);
                // echo "<h2>Record deleted</h2>";
                // Save the updated array in its session variable
                $_SESSION['serializedArray'] = urlencode(serialize($contactsArray)); 
                echo "<h2>Contact deleted</h2>";
            } // end of deleteContact()
            function displaycontacts()
            {
                global $contactsArray;
                echo("<table border='1'>");
                
                // display the header
                echo "<tr>";
                echo "<th>First Name</th>";
                echo "<th>Last Name</th>";
                echo "<th>Phone</th>";
                echo "<th>Email</th>";
                echo "</tr>";

                
                // Walk through each Contact or row
                foreach($contactsArray as $Contact)
                {
                echo "<tr>";
                // for each column in the row
                foreach($Contact as $value)
                {
                echo "<td>$value</td>";
                }
                echo "</tr>";
                }
                // stop the table
                echo "</table>";
            } // end of displaycontacts()
            ?>

            <h1>Contacts</h1>
                <p>
                    <?php displaycontacts(); ?>
                </p>
                <div>
                <form action="<?php $self ?>" method="POST" name="frmAdd">

                    <fieldset id="fieldsetAdd">
                        <legend>Add an item</legend>
                    
                        <label for="firstName">First Name:</label>
                        <input type="text" name="firstName" id="firstName" />
                        <br /><br />
                        
                        <label for="lastName">Last Name:</label>
                        <input type="text" name="lastName" id="lastName" />
                        <br /><br />
                        
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" />
                        <br /><br />
                        
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" />
                        <br /><br />
                        <!-- This field is used to determine if the page has been viewed already 
                        Code 01 = Add
                        -->
                        <input type='hidden' name='hidSubmitFlag' id='hidSubmitFlag' value='01' />
                        <input name="btnSubmit" type="submit" value="Add this information" />
                    </fieldset>
                </form>

                <form action="<?php $self ?>" method="POST" name="frmDelete">
                    <fieldset id="fieldsetDelete">
                        <legend>Select an item to delete:</legend>
                        
                        <select name="lstItem" size="1">
                        <?php
                        // Populate the list box using data from the array
                        foreach($contactsArray as $index => $lstContact)
                        {
                        // Make the value the index and the text displayed the description from the array
                        // The index will be used by deleteContact()
                        echo "<option value='" . $index . "'>" . $lstContact[1] . "</option>\n";
                        }
                        ?>
                        </select>
                        <!-- This field is used to determine if the page has been viewed already
                        Code 99 = Delete 
                        -->
                        <input type='hidden' name='hidSubmitFlag' id='hidSubmitFlag' value='99' />
                        <br /><br />
                        <input name="btnSubmit" type="submit" value="Delete" />
                    </fieldset>
                </form>
                </div>
        </body>
</html>