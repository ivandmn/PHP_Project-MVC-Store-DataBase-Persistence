<?php 
//Get the role of the user
$userrole = $_SESSION['role']??null;
//Get message error if exist
$message = $params['message']??null;  
//$userList contains all users array passed from the controller
$userList = $params['userList'];
?>

<?php 
//If role is admin or staff show list-users
if ($userrole == 'admin' or $userrole == 'staff' ): ?>
<table <?php if (empty($userList)){ ?> hidden <?php }?> >
    <h2>List all users</h2>
    <tr>
        <th>id</th>
        <th>username</th>
        <th>role</th>
    </tr>
    <?php
    //display list of items in a table.
    //Iterate $userList for print information
    foreach ($userList as $elem) {
        echo <<<EOT
        <tr>
            <td>{$elem->getId()}</td>
            <td>{$elem->getUsername()}</td>
            <td>{$elem->getRole()}</td>
        </tr>               
        EOT;
    }
    ?>
</table>
<?php else: ?>
<p class="alert">Permission denied</p>
<?php endif ?>
<p class="error"><?php echo $message ?></p>
