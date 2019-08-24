<?php
$sql1       = "SELECT * FROM categories WHERE parent = 0 ";
$parentQry  = $db->query( $sql1 );
?>
<nav class="navbar navbar-inverse navbar fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand">Boutique</a>
            </div>
            <ul class="nav navbar-nav">
                <?php while( $parent = mysqli_fetch_assoc( $parentQry ) ) :
                    $parentId = $parent['id'];
                    $sql2     = "SELECT * FROM categories WHERE parent = '$parentId' ";
                    $childQry = $db->query( $sql2 );
                    ?>
                    <li class="dopdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'] ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php while( $child = mysqli_fetch_assoc( $childQry ) ): ?>
                                <li><a href="category.php?cat=<?=$child['id'];?>"><?php echo $child['category'] ?></a></li>
                            <?php endwhile ; ?>
                        </ul>
                    </li>
                 <?php endwhile ; ?> 
                 <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a></li> 
            </ul> 
        </div>
    </nav>