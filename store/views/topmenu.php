<nav>
    <ul>
        <li><a style="color: white">Store application</a></li>
        <li><a href="index.php?action=home">Home</a></li>
        <li><a href="index.php?action=product/listAll">List all products</a></li>
        <li><a href="index.php?action=product/form">Product form</a></li>
        <li <?php if (!isset($_SESSION['username'])){ ?> hidden <?php }?>><a href="index.php?action=user/listAll">List all users</a></li>
        <li><a href="index.php?action=user/form">User form</a></li>
        <li <?php if (isset($_SESSION['username'])){ ?> hidden <?php }?>><a href="index.php?action=login/form">Login</a></li>
        <li <?php if (!isset($_SESSION['username'])){ ?> hidden <?php }?>><a href="index.php?action=logout">Logout</a></li>
    </ul>
</nav>