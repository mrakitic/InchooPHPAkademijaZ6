<?php

class IndexController
{
    public function index()
    {
        $view = new View();
        $posts = Post::all();
        $view->render('index', [
            "posts" => $posts
        ]);
    }
    public function view($id = 0)
    {
        $view = new View();
        $view->render('view', [
            "post" => Post::find($id),
            "comments" => Comment::all($id)
        ]);
    }
    public function newPost()
    {
        $data = $this->_validate($_POST);
        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO post (content) VALUES (:content)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            $stmt->execute();
            header('Location: ' . App::config('url'));
        }
    }

    public function deletePost($id){
        $connection = Db::connect();
        $sql = 'DELETE FROM comments WHERE postId = :id;
          DELETE FROM post WHERE id=:id';
        $stmt = $connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        header('Location: ' . App::config('url'));
    }

    public function newComment($postId){
        $data = $this->_validate($_POST);
        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO comments (content, postId) VALUES (:content, :postId)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            $stmt->bindValue('postId', $postId);
            $stmt->execute();
            header('Location: ' . App::config('url') . 'Index/view/' . $postId);
        }
    }

    public static function imageUpload($id){
        $target_dir="images/";
        $name= basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir .$name;

        if(move_uploaded_file($_FILES["fileToUpload"]["name"], $target_file)){
            $id = intval($id);
            $connection = Db::connect();
            $sql = 'UPDATE post SET image = :image WHERE id= :id';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('image', $name);
            $stmt->execute();
        }

        header('Location: ' . App::config('url'));
    }

    private function _validate($data)
    {
        $required = ['content'];
        //validate required keys
        foreach ($required as $key) {
            if (!isset($data[$key])) {
                return false;
            }
            $data[$key] = trim((string)$data[$key]);
            if (empty($data[$key])) {
                return false;
            }
        }
        return $data;
    }
}