<?php
include "admin_app_top.php";
include "checkUser.php";
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?=SITE_NAME?> - Admin Panel</title>

<link href="admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="main">
	<?php include "header.inc.php"; ?>
     
    <div id="adminNav"><div class="adminNavItem"><a href="admin_page.php">Return to Admin</a></div><div class="adminNavItem"><a href="logout.php" class="adminLink">Logout</a></div></div>
	<div id="adminLeft">    
    <?php if ($_SESSION['super']) {?>	
        <div class="adminSection">
            <div class="adminSectionHeading">Manage Administrators </div>
            <div class="adminEntry">
                <div class="adminEntryIcon"><a href="manageAdmins.php"><img src="images/User.png" width="24" height="24" border="0"></a></div>
                <div class="adminEntryText"><a href="manageAdmins.php">Manage Administrators</a></div>
            </div>
        </div>
     <?php } ?>
     <?php if ($_SESSION['content']) {?>   
        <div class="adminSection">
            <div class="adminSectionHeading">Manage Content</div>
            <div class="adminEntry">
                <div class="adminEntryIcon"><a href="managePages.php"><img src="images/Pencil yellow.png" width="24" height="24" border="0"></a></div>
                <div class="adminEntryText"><a href="managePages.php">Manage Pages</a></div>
            </div>
            <div class="adminEntry">
                <div class="adminEntryIcon"><a href="managePhotos.php"><img src="images/camera.png" width="24" height="24" border="0"></a></div>
                <div class="adminEntryText"><a href="managePhotos.php">Manage Photos</a></div>
            </div>
            <div class="adminEntry">
                <div class="adminEntryIcon"><a href="manageNews.php"><img src="images/News.png" width="24" height="24" border="0"></a></div>
                <div class="adminEntryText"><a href="manageNews.php">Manage News</a></div>
            </div>
            <div class="adminEntry">
                <div class="adminEntryIcon"><a href="manageFaqs.php"><img src="images/Orb help.png" width="24" height="24" border="0"></a></div>
                <div class="adminEntryText"><a href="manageFaqs.php">Manage FAQs</a></div>
            </div>
            
        </div>
    <?php } ?>
    </div>
    
    <div id="adminRight">
    	<?php if ($_SESSION['scores']) {?>
         <div class="adminSection">
            <div class="adminSectionHeading">Manage Scores and Schedules </div>
            <?php if ($_SESSION['super']) {?>
            <div class="adminEntry">
                <div class="adminEntryIcon"><a href="manageTeams.php"><img src="images/User.png" width="24" height="24" border="0"></a></div>
                <div class="adminEntryText"><a href="manageTeams.php">Manage Teams</a></div>
            </div>
            <div class="adminEntry">
                <div class="adminEntryIcon"><a href="manageGames.php"><img src="images/Basketball.png" width="24" height="24" border="0"></a></div>
                <div class="adminEntryText"><a href="manageGames.php">Manage Games</a></div>
            </div>
            <?php } ?>
            <div class="adminEntry">
                <div class="adminEntryIcon"><a href="manageScores.php"><img src="images/Doc.png" width="24" height="24" border="0"></a></div>
                <div class="adminEntryText"><a href="manageScores.php">Manage Scores</a></div>
            </div>
        </div>
        <?php } ?>
    </div>
                                          
    <?php include("down.inc.php"); ?>
</div>
</body>
</html>