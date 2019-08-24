
<nav class="navbar navbar-inverse navbar fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a href="/ecom/admin/index.php" class="navbar-brand">Boutique Admin</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="brands.php">Brands</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="products.php">Products</a></li>
                <?php if(has_permission('admin')) : ?>
                    <li><a href="users.php">Users</a></li>
                <?php endif ; ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown"  href="#">Hello <?= $user_data['first'];?>!
                    <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="change_password.php">Change password</a></li>
                         <li><a href="logout.php">Log Out</a></li>
                    </ul>
                </li>
                    <!-- <li class="dopdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'] ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                                <li><a href="#"></a></li> 
                        </ul>
                    </li> -->
            </ul> 
        </div>
    </nav>