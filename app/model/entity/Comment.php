<?php

class Comment
{
    private $id;
    private $content;
    private $postId;

    public function __construct($id, $content, $postId)
    {
        $this->setId($id);
        $this->setContent($content);
        $this->setpostId($postId);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    public function __call($name, $arguments)
    {
        $function = substr($name, 0, 3);
        if ($function === 'set') {
            $this->__set(strtolower(substr($name, 3)), $arguments[0]);
            return $this;
        } else if ($function === 'get') {
            return $this->__get(strtolower(substr($name, 3)));
        }
        return $this;
    }

    public static function all($id)
    {
        $list = [];
        $id=intval($id);
        $db = Db::connect();
        $statement = $db->prepare("select * from comments WHERE postId = :id order by id desc ");
        $statement->bindValue('id',$id);
        $statement->execute();
        foreach ($statement->fetchAll() as $comments) {
            $list[] = new Comment($comments->id, $comments->content, $comments->postId);
        }
        return $list;
    }

    public static function count($id){
        $id=intval($id);
        $db = Db::connect();
        $statement = $db->prepare("select count(*) as total from comments WHERE postId=:id");
        $statement->bindValue('id',$id);
        $statement->execute();
        $comments=$statement->fetch();
        return $comments->total;
    }

}