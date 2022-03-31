<?php
   //get parameters passed in by controller.
   $user = $params['user']??null; 
   $action = $params['action']??"";
   $result = $params['result']??null;
   $message = $params['message']??null;
   $messageCorrect = $params['messageCorrect']??null;
   if (is_null($user)) {
       $user = new User(1, "");
   }
   //stablish button activation
   $disableAddButton = ($action == "user/search" ||  $action == "user/modify"  || !isset($_SESSION['role']) )?"disabled":"";
   $disableModifyButton = ($action == "user/add" || $action == "user/form" || $action == "user/remove" || !isset($_SESSION['role']))?"disabled":"";
   $disableRemoveButton = ($action == "user/add" || $action == "user/form" || !isset($_SESSION['role']))?"disabled":"";
   if(isset($_SESSION['role'])){
      if($_SESSION['role'] != "admin"){
        $disableAddButton = "disabled";
        $disableModifyButton = "disabled";
        $disableRemoveButton = "disabled";
      }
   }
   //show previous action information, if present
   if (!is_null($result)) {
       echo <<<EOT
       <div><p class="alert">$result</p></div>
  EOT;
   }   
   echo <<<EOT
   <form id="user-form" method="post" action="index.php">
    <fieldset><legend>User form</legend>
        <label for="id">Id: </label>
        <input type="text" name="id" id="id" placeholder="Enter id" value="{$user->getId()}"/>
        <label for="username">Username: </label>
        <input type="text" name="username" id="username" placeholder="Enter username" value="{$user->getUsername()}"/>
        <label for="password">Password: </label>
        <input type="text" name="password" id="password" placeholder="Enter password" value="{$user->getPassword()}"/>
        <label for="role">Role: </label>
        <input type="text" name="role" id="role" placeholder="Enter role" value="{$user->getRole()}"/>
        <label for="name">Name: </label>
        <input type="text" name="name" id="name" placeholder="Enter name" value="{$user->getName()}"/>
        <label for="surname">Surname: </label>
        <input type="text" name="surname" id="surname" placeholder="Enter surname" value="{$user->getSurname()}"/>
   </fieldset>

        <button type="submit" id="findItem" name="action" value="user/search">Search by User ID</button>
        <button type="submit" id="addItem" name="action" value="user/add" {$disableAddButton}>Add User</button>
        <button type="submit" id="modifyItem" name="action" value="user/modify" {$disableModifyButton}>Modify By User ID</button>
        <button type="submit" id="removeItem" name="action" value="user/remove" {$disableRemoveButton}>Remove by User ID</button>
        <button type="submit" id="Clear" name="action" value="user/form">Clear</button>
        <br><br>
        <label class="errors">$message</label>
        <label class="infoText">$messageCorrect</label>
</form>
EOT;
?>
