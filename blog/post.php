<?php
require_once 'db.php';

// Get post slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (empty($slug)) {
    header("Location: /");
    exit;
}

// Fetch post with categories
$query = "SELECT 
            p.*,
            GROUP_CONCAT(c.name) as categories
        FROM posts p
        LEFT JOIN post_categories pc ON p.id = pc.post_id
        LEFT JOIN categories c ON pc.category_id = c.id
        WHERE p.slug = ?
        GROUP BY p.id";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    header("Location: /");
    exit;
}

// Convert categories string to array
$categories = $post['categories'] ? explode(',', $post['categories']) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize_output($post['title']); ?> - Kresando Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand"><a href="../index.html">Portfolio</a></div>
        <div class="nav-toggle" id="navToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-menu">
            <li><a href="../index.html#home">Home</a></li>
            <li><a href="../index.html#work">Project Gallery</a></li>
            <li><a href="../index.html#blog" class="active">Blog</a></li>
            <li><a href="../index.html#contact">Contact</a></li>
        </ul>
    </nav>

    <div class="blog-header">
        <div class="container">
            <a href="../index.html#blog" class="back-button"><i class="fas fa-arrow-left"></i> Kembali ke Blog</a>
            <h1><?php echo sanitize_output($post['title']); ?></h1>
            <div class="blog-meta">
                <span><i class="far fa-calendar"></i> <?php echo format_date($post['created_at']); ?></span>
                <span><i class="far fa-user"></i> <?php echo sanitize_output($post['author']); ?></span>
                <span><i class="far fa-folder"></i> <?php echo sanitize_output(implode(', ', $categories)); ?></span>
            </div>
        </div>
    </div>

    <main class="blog-content">
        <div class="container">
            <?php if ($post['image_url']): ?>
            <img src="<?php echo sanitize_output($post['image_url']); ?>" alt="<?php echo sanitize_output($post['title']); ?>" class="featured-image">
            <?php endif; ?>
            
            <article>
                <?php echo $post['content']; ?>
            </article>

            <div class="blog-share">
                <h3>Bagikan Artikel</h3>
                <div class="social-share">
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank" aria-label="Share on Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>&title=<?php echo urlencode($post['title']); ?>" target="_blank" aria-label="Share on LinkedIn"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <?php
            // Fetch next and previous posts
            $prev_query = "SELECT title, slug FROM posts WHERE created_at < ? ORDER BY created_at DESC LIMIT 1";
            $next_query = "SELECT title, slug FROM posts WHERE created_at > ? ORDER BY created_at ASC LIMIT 1";
            
            $stmt = mysqli_prepare($conn, $prev_query);
            mysqli_stmt_bind_param($stmt, "s", $post['created_at']);
            mysqli_stmt_execute($stmt);
            $prev_post = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            $stmt = mysqli_prepare($conn, $next_query);
            mysqli_stmt_bind_param($stmt, "s", $post['created_at']);
            mysqli_stmt_execute($stmt);
            $next_post = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            ?>

            <div class="blog-navigation">
                <div class="blog-nav-prev">
                    <?php if ($prev_post): ?>
                    <a href="?slug=<?php echo urlencode($prev_post['slug']); ?>">
                        <i class="fas fa-angle-left"></i>
                        <span><?php echo sanitize_output($prev_post['title']); ?></span>
                    </a>
                    <?php else: ?>
                    <a class="disabled">
                        <i class="fas fa-angle-left"></i>
                        <span>Artikel Sebelumnya</span>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="blog-nav-next">
                    <?php if ($next_post): ?>
                    <a href="?slug=<?php echo urlencode($next_post['slug']); ?>">
                        <span><?php echo sanitize_output($next_post['title']); ?></span>
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <?php else: ?>
                    <a class="disabled">
                        <span>Artikel Berikutnya</span>
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Kresando Portfolio</p>
    </footer>

    <script src="../js/main.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>