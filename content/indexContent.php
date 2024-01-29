<?php
$username = $_SESSION['username'] ?? '';
require_once 'error.php';
echo"
<div class='container px-4 px-lg-5'>
    <div class='row gx-4 gx-lg-5 justify-content-center text-center my-5'>

        <div class='col-lg-5'>
            <h1 class='display-4'>Home</h1>
            <p class='display-6' >Welcome to Toorizm {$username}!</p>
        </div>
        
    </div>
    
    <div class='row gx-4 gx-lg-5 justify-content-center text-center my-5'>
        <div class='col-lg-5'>
            <h2 class='display-4'>Attractions</h2>
            <p class='display-6'>Check out our <a href='attractions.php'>attractions</a>!</p>
        </div>
    </div>
    
    <div class='row gx-4 gx-lg-5 justify-content-center text-center my-5'>
        <div class='col-lg-5'>
            <h2 class='display-4'>Attractions by cities</h2>
            <p class='display-6'>Check out our <a href='city.php'>attractions by choosing a city</a>!</p>
        </div>
    </div>
    
    <div class='row gx-4 gx-lg-5 justify-content-center text-center my-5'>
        <div class='col-lg-5'>
            <h2 class='display-4'>Contact us!</h2>
            <p class='display-6'>Contact us on <a href='contacts.php'>contacts page</a>!</p>
        </div>
    </div>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
</div>


";
