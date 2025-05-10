<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Subscription Plans - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="/images/subs.png">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/subs.css">
    <script src="subs.js"></script>
    <?php if ($dark_mode): ?>
        <link rel="stylesheet" href="style/index-dark.css">
        <link rel="stylesheet" href="style/subs-dark.css">
    <?php endif; ?>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h1>Subscription Plans</h1>

        <form id="subscriptionForm" method="POST" action="subscription.php">
            <input type="hidden" name="plan" id="planInput" value="">
            <section class="plans">
                <?php foreach ($plans as $plan):
                    $plan_key = strtolower(str_replace(' ', '_', $plan['plan_name']));
                    $is_popular = $plan['popular'];
                    ?>
                    <div class="<?php echo $subscription->planClass($plan_key); ?> plan-card"
                        id="<?php echo htmlspecialchars($plan_key); ?>">
                        <h2><?php echo htmlspecialchars($plan['plan_name']); ?></h2>
                        <?php if ($is_popular): ?>
                            <label style="font-size: small; color: gray;">Popular</label>
                        <?php endif; ?>
                        <p class="price"><?php echo htmlspecialchars($plan['price']); ?> EGP</p>
                        <p><?php echo htmlspecialchars($plan['description']); ?></p>
                        <ul class="features">
                            <?php foreach ($plan['features'] as $feature): ?>
                                <li><?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" <?php echo $highlight_plan === $plan_key ? 'disabled' : ''; ?>
                            onclick="confirmChange('<?php echo htmlspecialchars($plan_key); ?>')" class="select-btn">
                            <?php echo $subscription->buttonLabel($plan_key); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </section>
        </form>

        <?php if ($highlight_plan === 'premium+' || $highlight_plan === 'premium'): ?>
            <div class="auto-renew-container">
                <h2>Auto-Renewal</h2>
                <form id="autoRenewForm" method="POST" action="subscription.php">
                    <input type="hidden" name="auto_renew" id="autoRenewInput"
                        value="<?php echo $auto_renew ? '1' : '0'; ?>">
                    <label class="auto-renew-label" for="autoRenewToggle">Enable Auto-Renewal:</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="autoRenewToggle" <?php echo $auto_renew ? 'checked' : ''; ?>
                            onchange="toggleAutoRenew(this)">
                        <span class="slider"></span>
                    </label>
                </form>
            </div>
            <?php if ($highlight_plan === 'premium+'): ?>

                <div id="Daily">
                    <h2>Daily</h2>
                    <?php if (count($articles) > 0): ?>
                        <?php foreach ($articles as $article): ?>
                            <article>
                                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                                <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No news articles for today.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div id="messageBox"></div>

    <footer>
        <p>&copy; <?= date("Y") ?> The Global Herald. <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryLinks = document.querySelectorAll('.navbar-nav .nav-link');
            const sidebarCategoryLinks = document.querySelectorAll('.category-list li a');
            const currentCategoryId = urlParams.get('category_id');

            sidebarCategoryLinks.forEach(link => {
                if ((currentCategoryId === null && link.getAttribute('href') === 'index.php') || link.getAttribute('href') === '?category_id=' + currentCategoryId) {
                    link.parentElement.classList.add('active');
                }
            });
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</body>

</html>