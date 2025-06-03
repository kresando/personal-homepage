# Blog Implementation Plan

## 1. Database Setup

First, create the database and tables:

```sql
CREATE DATABASE personal-homepage;
USE personal-homepage;

CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    author VARCHAR(100) NOT NULL
);

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE post_categories (
    post_id INT,
    category_id INT,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

## 2. PHP Files Implementation

### Database Connection (blog/db.php)
```php
<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'personal-homepage';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

### Blog API Endpoint (blog/api/posts.php)
```php
<?php
header('Content-Type: application/json');
require_once '../db.php';

// Get posts for homepage
$query = "SELECT id, title, content, image_url, created_at, author FROM posts ORDER BY created_at DESC LIMIT 4";
$result = mysqli_query($conn, $query);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Truncate content for preview
    $row['content'] = substr(strip_tags($row['content']), 0, 200) . '...';
    $posts[] = $row;
}

echo json_encode($posts);
mysqli_close($conn);
?>
```

### Single Post View (blog/post.php)
```php
<?php
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT * FROM posts WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    header("Location: /");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/blog.css">
</head>
<body>
    <!-- Similar structure to current blog post HTML -->
    <article class="blog-post">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <div class="blog-meta">
            <span><?php echo date('d F Y', strtotime($post['created_at'])); ?></span>
            <span><?php echo htmlspecialchars($post['author']); ?></span>
        </div>
        <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="">
        <div class="content">
            <?php echo $post['content']; ?>
        </div>
    </article>
</body>
</html>
```

## 3. JavaScript Implementation (js/blog.js)

```javascript
async function loadBlogPosts() {
    try {
        const response = await fetch('/blog/api/posts.php');
        const posts = await response.json();
        
        const blogGrid = document.querySelector('.blog-grid');
        blogGrid.innerHTML = ''; // Clear existing posts
        
        posts.forEach(post => {
            blogGrid.innerHTML += `
                <article class="blog-card">
                    <div class="blog-image">
                        <img src="${post.image_url}" alt="${post.title}">
                    </div>
                    <div class="blog-content">
                        <h3>${post.title}</h3>
                        <p>${post.content}</p>
                        <a href="/blog/post.php?id=${post.id}" class="read-more">Baca Selengkapnya</a>
                    </div>
                </article>
            `;
        });
    } catch (error) {
        console.error('Error loading blog posts:', error);
    }
}

// Load posts when page loads
document.addEventListener('DOMContentLoaded', loadBlogPosts);
```

## 4. Update index.html

Add the blog.js script to index.html:
```html
<!-- Add before closing body tag -->
<script src="js/blog.js"></script>
```

## 5. Implementation Steps

1. Create MySQL database and tables
2. Create PHP files in the blog directory
3. Add sample blog posts to the database
4. Create blog.js and update index.html
5. Test the integration

## Security Considerations

1. All database queries use prepared statements
2. Input validation on all parameters
3. Output escaping for HTML display
4. Error handling for database operations

## Testing Steps

1. Database connection
2. API endpoint returns correct JSON
3. Blog posts display in index.html
4. Single post view works
5. Error handling works as expected

Would you like to proceed with implementation?