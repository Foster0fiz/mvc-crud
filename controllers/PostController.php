<?php
require_once 'models/Post.php';

class PostController
{
    private $model;

    public function __construct()
    {
        $this->model = new Post();
        session_start();
    }

    public function index()
    {
        $posts = $this->model->all();
        require 'views/posts/index.php';
    }

    public function create()
    {
        require 'views/posts/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            require 'views/utilities/404.php';
        }

        $title = $_POST['title'];
        $content = $_POST['content'];
        $author_name = $_SESSION['user']['name']; // Assume user is logged in
        $image = $_FILES['image']['name'];

        if (empty($title) || empty($content)) {
            $_SESSION['message'] = 'Title and Content cannot be empty';
            require 'views/posts/create.php';
            return;
        }

        // Store the image if uploaded
        if ($image) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($image);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }

        $data = [
            'title' => $title,
            'content' => $content,
            'image' => $image,
            'author_name' => $author_name
        ];

        if ($this->model->create($data)) {
            $_SESSION['message'] = 'Post created successfully';
            header('Location: /posts');
        }
    }
    
    public function show()
    {
        $id=$_GET['id'];
        $post = $this->model->find($id);
        if (!$post) {
            require 'views/utilities/404.php';
        }
        require 'views/posts/show.php';
    }

    // PostController.php
public function edit()
{
    $id = $_GET['id'];
    $post = $this->model->find($id);

    if (!$post) {
        require 'views/utilities/404.php'; // Show 404 if the post isn't found
        return;
    }

    require 'views/posts/edit.php'; // Load the edit view
}

public function update()
{
    $id = $_GET['id'];
    $post = $this->model->find($id);

    if (!$post) {
        require 'views/utilities/404.php';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        require 'views/utilities/404.php';
        return;
    }

    $title = $_POST['title'];
    $content = $_POST['content'];
    $newImage = $_FILES['image']['name']; // New image file

    if (empty($title) || empty($content)) {
        $_SESSION['message'] = 'Title and Content cannot be empty';
        require 'views/posts/edit.php';
        return;
    }

    // Handle image upload
    if ($newImage) {
        // Define the target directory and file
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($newImage);

        // Delete the old image file from the server if it exists
        if (!empty($post['image']) && file_exists($target_dir . $post['image'])) {
            unlink($target_dir . $post['image']); // Delete the old image
        }

        // Move the new image to the uploads directory
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = $newImage; // Set the new image name
    } else {
        // Retain the old image if no new image is uploaded
        $image = $post['image'];
    }

    // Prepare the updated data
    $data = [
        'title' => $title,
        'content' => $content,
        'image' => $image,
    ];

    // Update the post record
    if ($this->model->update($id, $data)) {
        $_SESSION['message'] = 'Post updated successfully';
        header('Location: /posts');
    }
}

    

  
        public function delete()
        {
            $id = $_GET['id'];
            $post = $this->model->find($id);
        
            if (!$post) {
                require 'views/utilities/404.php';
                return;
            }
        
            // Delete the image from the uploads folder
            $imagePath = 'uploads/' . $post['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath); // Deletes the image file
            }
        
            // Delete the post from the database
            if ($this->model->delete($id)) {
                $_SESSION['message'] = 'Post and associated image deleted successfully';
                header('Location: /posts');
                exit();
            } else {
                $_SESSION['message'] = 'Failed to delete the post';
                header('Location: /posts');
            }
        }
            
}
