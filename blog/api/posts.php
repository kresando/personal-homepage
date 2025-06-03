<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../db.php';

try {
    // Get query parameters
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 4;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Base query
    $query = "SELECT 
                p.id,
                p.title,
                p.content,
                p.image_url,
                p.created_at,
                p.author,
                p.slug,
                GROUP_CONCAT(c.name) as categories
            FROM posts p
            LEFT JOIN post_categories pc ON p.id = pc.post_id
            LEFT JOIN categories c ON pc.category_id = c.id
            GROUP BY p.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?";

    // Prepare and execute query
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch all posts
    $posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Truncate content for preview
        $row['content'] = substr(strip_tags($row['content']), 0, 200) . '...';
        
        // Convert categories string to array
        $row['categories'] = $row['categories'] ? explode(',', $row['categories']) : [];
        
        // Format date
        $row['created_at'] = format_date($row['created_at']);
        
        $posts[] = $row;
    }

    // Get total posts count for pagination
    $count_query = "SELECT COUNT(*) as total FROM posts";
    $count_result = mysqli_query($conn, $count_query);
    $total_posts = mysqli_fetch_assoc($count_result)['total'];

    // Prepare response
    $response = [
        'status' => 'success',
        'data' => [
            'posts' => $posts,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total_posts / $limit),
                'total_posts' => $total_posts,
                'per_page' => $limit
            ]
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred while fetching posts'
    ]);
}

mysqli_close($conn);