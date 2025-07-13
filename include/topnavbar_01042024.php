<style>
    .heading {
    font-family: 'Bebas Neue', Arial, sans-serif;
}
</style>

<?php 
$sessionusertype=$_SESSION['type'];

$sqlusertype="SELECT `type` FROM `tbl_user_type` WHERE `idtbl_user_type`='$sessionusertype' AND `status`=1";
$resultusertype =$conn-> query($sqlusertype);
$rowusertype = $resultusertype-> fetch_assoc();
?>
<nav class="topnav navbar navbar-expand shadow navbar-light bg-laugfs" id="sidenavAccordion">
<button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#"><i class="text-dark" data-feather="menu"></i></button><a class="navbar-brand heading d-sm-block menu-logo" href="#">ANSEN Gas Distributor (Pvt) Ltd</a><div class="ml-5 justify-content-center"><img src="images/logo.png" class="img-fluid" alt="" style="width:22%;"></div>

<div class="dropdown-user-details-name text-dark" style="margin-left:60rem; font-size:1rem;"><span id="currentDateTime"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ucfirst($_SESSION['name']); ?></div>
    <ul class="navbar-nav align-items-center ml-auto">
        <li class="nav-item dropdown no-caret mr-3 dropdown-user">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-user text-dark"></i></a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="<?php if($_SESSION['image']!=''){echo $_SESSION['image'];}else{echo 'images/user.jpg';} ?>" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-email"><?php echo $rowusertype['type']; ?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="process/logoutprocess.php">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<script>
function updateDateTime() {
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth() + 1;
    var year = currentDate.getFullYear();
    var hours = currentDate.getHours();
    var minutes = currentDate.getMinutes();
    var seconds = currentDate.getSeconds();

    day = (day < 10 ? '0' : '') + day;
    month = (month < 10 ? '0' : '') + month;
    hours = (hours < 10 ? '0' : '') + hours;
    minutes = (minutes < 10 ? '0' : '') + minutes;
    seconds = (seconds < 10 ? '0' : '') + seconds;

    var dateTimeString = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

    document.getElementById('currentDateTime').textContent = dateTimeString;
}

updateDateTime();

setInterval(updateDateTime, 1000);
</script>