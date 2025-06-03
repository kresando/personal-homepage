document.addEventListener('DOMContentLoaded', loadBlogPosts);

async function loadBlogPosts() {
    try {
        const response = await fetch('blog/api/posts.php');
        const data = await response.json();
        
        if (data.status === 'success') {
            displayBlogPosts(data.data.posts);
        } else {
            console.error('Error loading blog posts:', data.message);
        }
    } catch (error) {
        console.error('Error fetching blog posts:', error);
    }
}

function displayBlogPosts(posts) {
    const blogGrid = document.querySelector('.blog-grid');
    if (!blogGrid) return;

    // Clear existing content
    blogGrid.innerHTML = '';

    // Add new blog posts
    posts.forEach(post => {
        const article = createBlogCard(post);
        blogGrid.appendChild(article);
    });
}

function createBlogCard(post) {
    const article = document.createElement('article');
    article.className = 'blog-card';

    article.innerHTML = `
        <div class="blog-image">
            <img src="${post.image_url}" alt="${escapeHtml(post.title)}">
        </div>
        <div class="blog-content">
            <h3>${escapeHtml(post.title)}</h3>
            <p>${escapeHtml(post.content)}</p>
            <a href="blog/post.php?slug=${encodeURIComponent(post.slug)}" class="read-more">Baca Selengkapnya</a>
        </div>
    `;

    return article;
}

// Helper function to escape HTML and prevent XSS
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}