<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-newspaper"></i> The Global Herald
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                </li>
                <?php
                $categories_result->data_seek(0);
                while ($category = $categories_result->fetch_assoc()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $selected_category == $category['category_id'] ? 'active' : '' ?>"
                            href="index.php?category_id=<?= $category['category_id'] ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
                <li>
                    <a class="nav-link" href="index.php#games">Games</a>
                </li>

            </ul>
            <form class="d-flex" method="GET" action="index.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search"
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
            <ul class="navbar-nav ms-auto">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li>
                        <?php
                        $randomArticleId = $articleObj->getRandomArticleId();
                        ?>
                        <a class="nav-link" href="article.php?id=<?= $randomArticleId ?>">Discover</a>
                    </li>
                </ul>
                <?php if ($userObj->isLoggedIn() || $userObj->isSignedUp()): ?>
                    <li class="
                                                    nav-item">
                        <a class="nav-link position-relative" href="noti.php" title="Notifications">
                            <i class="fas fa-bell fa-lg"></i>
                            <?php if ($notifications_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $notifications_count ?>
                                    <span class="visually-hidden">unread
                                        notifications</span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item user-dropdown">
                        <a class="nav-link" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false" title="User Menu">
                            <i class="fas fa-user-circle fa-lg"></i>
                        </a>
                        <ul class="dropdown-menu user-dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>
                                    Profile</a></li>
                            <li><a class="dropdown-item" href="favorites.php"><i class="fas fa-heart me-2"></i>
                                    Favorites</a></li>
                            <li><a class="dropdown-item" href="bookmarks.php"><i class="fas fa-bookmark me-2"></i>
                                    Bookmarks</a></li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="admin.php"><i class="fas fa-user-shield me-2"></i>
                                        Admin Panel</a></li>
                            <?php endif; ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>
                                    Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html"><i class="fas fa-sign-in-alt"></i>
                            Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.html"><i class="fas fa-user-plus"></i>
                            Signup</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>