<?php
$message = $params['message']??null;
?>
<form method="post" action="index.php">
    <p> You want to log out? <p>
    <button <?php if (!isset($_SESSION['username'])){ ?> disabled <?php   } ?> name="action" value="user/logout" type="submit">Yes</button>
    <button <?php if (!isset($_SESSION['username'])){ ?> disabled <?php   } ?> name="action" value="home" type="submit">No</button>
    <br><br><label class="errors"><?php echo $message?></label>
</form>