<?php
$username = $params['username']??null;
$message = $params['message']??null;

//Check if form is null
$usernameErrorMessage = "";
$passwordErrorMessage = "";
if (isset($_POST['action'])) {
    if ($_POST['username'] != "") {
        $usernameErrorMessage = "";
    }
    else{
        $usernameErrorMessage = "Enter Username";
    }
    if ($_POST['password'] != "") {
        $passwordErrorMessage = "";
    }
    else{
        $passwordErrorMessage = "Enter Password";
    }
}
?>
<form method="post" action="index.php">
    <fieldset>
        <legend>Login form</legend>
        <label for="username">Username:</label>
        <input type="text" name="username" id ="username" placeholder="Enter username" value="<?php if (!is_null($username)) echo $username; ?>"></input>
        <label class="errors"><?php echo $usernameErrorMessage?></label>
        <label for="password">Password:</label>
        <input type="password" name="password" id ="password" placeholder="Enter password" value=""></input>
        <label class="errors"><?php echo $passwordErrorMessage?></label>
        <label>
        <button <?php if (isset($_SESSION['username'])){ ?> disabled <?php   } ?> name="action" value="user/login" type="submit">Submit</button>
        </label>
    </fieldset>
    <label class="errors"><?php echo $message?></label>
    <label class="errors"><?php if (isset($_SESSION['username'])){ echo "Can't login if user don't logout before";}?></label>
</form>