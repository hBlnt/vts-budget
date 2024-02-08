<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top ">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link text-light" href="index.php">Home</a>
                </li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Cities
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="new_city.php">Create city</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="all_cities.php">All cities</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light" href="all_attractions.php">All attractions</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown2" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Organizations and users
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li><a class="dropdown-item" href="organizations.php">Create organizations</a></li>
                        <li><a class="dropdown-item" href="all_organizations.php">All organizations</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="user_status.php">Users</a></li>
                        <li><a class="dropdown-item" href="all_user_info.php">User details</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light" href="logout.php">Log out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

