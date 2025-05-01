<?php
class Article
{
    private $conn;
    public $id, $title, $content, $author, $category_id, $image_path;

    public function getTotalArticles($search_term = '', $category_id = 0)
    {
        if ($search_term !== '') {
            $keywords = preg_split('/\s+/', $search_term, -1, PREG_SPLIT_NO_EMPTY);

            $count_conditions = [];
            $count_params = [];
            $count_types = '';
            foreach ($keywords as $keyword) {
                $count_conditions[] = "(a.content LIKE ? OR a.author LIKE ? OR c.category_name LIKE ?)";
                $like_keyword = '%' . $keyword . '%';
                $count_params[] = $like_keyword;
                $count_params[] = $like_keyword;
                $count_params[] = $like_keyword;
                $count_types .= 'sss';
            }
            $count_where = implode(' AND ', $count_conditions);
            $count_query = "SELECT COUNT(*) as total FROM articles a LEFT JOIN category c ON a.category_id = c.category_id WHERE $count_where";
            $count_stmt = $this->conn->prepare($count_query);
            $count_stmt->bind_param($count_types, ...$count_params);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $total_articles = 0;
            if ($count_result && $row = $count_result->fetch_assoc()) {
                $total_articles = $row['total'];
            }
            $count_stmt->close();
            return $total_articles;
        } else {
            $count_query = "SELECT COUNT(*) as total FROM articles WHERE category_id = ? OR ? = 0";
            $count_stmt = $this->conn->prepare($count_query);
            $count_stmt->bind_param("ii", $category_id, $category_id);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $total_articles = 0;
            if ($count_result && $row = $count_result->fetch_assoc()) {
                $total_articles = $row['total'];
            }
            $count_stmt->close();
            return $total_articles;
        }
    }

    public function getArticles($search_term = '', $category_id = 0, $page = 1, $articles_per_page = 5)
    {
        $offset = ($page - 1) * $articles_per_page;
        if ($search_term !== '') {
            $keywords = preg_split('/\s+/', $search_term, -1, PREG_SPLIT_NO_EMPTY);

            $article_conditions = [];
            $article_params = [];
            $article_types = '';
            foreach ($keywords as $keyword) {
                $article_conditions[] = "(a.content LIKE ? OR a.author LIKE ? OR c.category_name LIKE ?)";
                $like_keyword = '%' . $keyword . '%';
                $article_params[] = $like_keyword;
                $article_params[] = $like_keyword;
                $article_params[] = $like_keyword;
                $article_types .= 'sss';
            }
            $article_where = implode(' AND ', $article_conditions);
            $articles_query = "
                SELECT a.id, a.title, a.author, SUBSTR(a.content, 1, 300) AS short_content, a.created_at,
                c.category_name
                FROM articles a
                LEFT JOIN category c ON a.category_id = c.category_id
                WHERE $article_where
                ORDER BY a.created_at DESC
                LIMIT ? OFFSET ?
            ";
            $stmt = $this->conn->prepare($articles_query);
            $article_types .= 'ii';
            $article_params[] = $articles_per_page;
            $article_params[] = $offset;
            $stmt->bind_param($article_types, ...$article_params);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            $articles_query = "
                SELECT a.id, a.title, a.author, SUBSTR(a.content, 1, 300) AS short_content, a.created_at,
                c.category_name
                FROM articles a
                LEFT JOIN category c ON a.category_id = c.category_id
                WHERE c.category_id = ? OR ? = 0
                ORDER BY a.created_at DESC
                LIMIT ? OFFSET ?
            ";
            $stmt = $this->conn->prepare($articles_query);
            $stmt->bind_param("iiii", $category_id, $category_id, $articles_per_page, $offset);
            $stmt->execute();
            return $stmt->get_result();
        }
    }

    public function getBreakingNews()
    {
        $BNQ = "
            SELECT content FROM breaking_news
            WHERE NOW() < DATE_ADD(creation_date, INTERVAL duration MINUTE)
            ORDER BY creation_date DESC
        ";
        return $this->conn->query($BNQ);
    }

    public function getBreakingNewsTitles($limit = 3)
    {
        $query = "SELECT id, title FROM articles ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPopularArticles($limit = 3)
    {
        $query = "SELECT id, title FROM articles ORDER BY views DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }


    public function getRandomArticleId()
    {
        $query = "SELECT id FROM articles ORDER BY RAND() LIMIT 1";
        $result = $this->conn->query($query);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['id'];
        }
        return null;
    }

    public function isFavorited($user_id, $article_id)
    {
        if (!$user_id)
            return false;

        $query = "SELECT 1 FROM favorites WHERE user_id = ? AND article_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $article_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function isBookmarked($user_id, $article_id)
    {
        if (!$user_id)
            return false;

        $query = "SELECT 1 FROM bookmarks WHERE user_id = ? AND article_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $article_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function incrementViews($article_id)
    {
        $stmt = $this->conn->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
        $stmt->bind_param("i", $article_id);
        return $stmt->execute();
    }

    public function getArticleById($article_id)
    {
        $query = "
            SELECT a.id, a.title, a.author, a.content, a.created_at, c.category_name, a.image_path
            FROM articles a
            LEFT JOIN category c ON a.category_id = c.category_id
            WHERE a.id = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getComments($article_id)
    {
        $query = "
            SELECT c.description, u.name
            FROM comment c
            JOIN users u ON c.user_id = u.user_id
            WHERE c.article_id = ?
            ORDER BY c.comment_id DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        return $stmt->get_result();
    }

}
?>