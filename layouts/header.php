<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?php if (!empty($page_title))
            echo remove_junk($page_title);
          elseif (!empty($user))
            echo ucfirst($user['name']);
          else echo "POLstore"; ?>
  </title>
  <link rel="stylesheet" href="libs/css/bootstrap.min.css" />
  <link rel="stylesheet" href="libs/css/bootstrap_v3.3.7.min.css" />
  <link rel="stylesheet" href="libs/css/datepicker3.min.css" />
  <link rel="stylesheet" href="libs/css/main.css" />
  <link href="libs/css/select2.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="libs/css/jquery.dataTables.css">
  <script src="https://kit.fontawesome.com/fc6579be9e.js" crossorigin="anonymous"></script>
 </head>
 
<body>
  <?php if ($session->isUserLoggedIn(true)) : ?>
    <header id="header">
      <div class="logo pull-left"> POLstore</div>
      <div class="header-content">
        <div class="header-date pull-left">
          <strong><?php echo date("F j, Y, g:i a"); ?></strong>
        </div>
        <div class="pull-right clearfix">
          <ul class="info-menu list-inline list-unstyled">
            <li class="profile">
              <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                <img src="uploads/users/<?php echo $user['image']; ?>" alt="user-image" class="img-circle img-inline">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="profile.php?id=<?php echo (int)$user['id']; ?>">
                  <i class="fa-regular fa-circle-user"></i>
                    Profile
                  </a>
                </li>
                <li>
                  <a href="edit_account.php" title="edit account">
                  <i class="fa-regular fa-sun"></i>
                    Settings
                  </a>
                </li>
                <li class="last">
                  <a href="logout.php">
                  <i class="fa-regular fa-circle-stop"></i>
                    Logout
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </header>
    <div class="sidebar" style="overflow-y: scroll;">
      <ul>
        <?php
        if ($user['user_level'] === '3') :
          include_once('user_menu.php');
        elseif ($user['user_level'] === '2') :
          include_once('special_menu.php');
        elseif ($user['user_level'] === '1') :
          include_once('admin_menu.php');
        endif; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="page">
    <div class="container-fluid">