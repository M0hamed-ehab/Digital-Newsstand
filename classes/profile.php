<?php
class profile
{
    private $db;
    private $user_id;

    public function __construct($db)
    {
        $this->db = $db;

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.html");
            exit();
        }

        $this->user_id = $_SESSION['user_id'];
    }

    public function getUserInfo()
    {
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $user = $result->fetch_assoc()) {
            return $user;
        } else {
            throw new Exception("Error: Could not retrieve user information.");
        }
    }

    public function updateProfile($username, $email)
    {
        $errors = [];

        $new_username = trim($username);
        $new_email = trim($email);

        if (empty($new_username)) {
            $errors[] = "Username is required.";
        }

        if (empty($new_email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($errors)) {
            $update_query = "UPDATE users SET name = ?, email = ? WHERE user_id = ?";
            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bind_param("ssi", $new_username, $new_email, $this->user_id);

            if ($update_stmt->execute()) {
                return [
                    'success' => true,
                    'message' => "Profile updated successfully!"
                ];
            } else {
                return [
                    'success' => false,
                    'message' => "Error updating profile."
                ];
            }
        } else {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }
    }
}
?>