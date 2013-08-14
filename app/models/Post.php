<?php
class Post extends Eloquent {
	public static function getPosts($pageNumber) {
		$db = new PDO('sqlite:' . '../app/database/production.sqlite');
		$statement = $db->prepare('SELECT * FROM posts ORDER BY id DESC LIMIT 10');
     	if (!$statement->execute())
		{
			$err = print_r($statement->errorInfo(), true);
			throw new Exception($err);
		}
		$postrows = $statement->fetchAll(PDO::FETCH_ASSOC);
		return Post::getTagsForPost($postrows);
	}

	public static function getTagsForPost($postrows) {
		$db = new PDO('sqlite:' . '../app/database/production.sqlite');
		$count = 0;
		while ($count < count($postrows)) {
			$statement = $db->prepare('SELECT * FROM postTags WHERE postID=:postid');
			$statement->bindValue(':postid', $postrows[$count]['id'], PDO::PARAM_INT);
	     	if (!$statement->execute())
			{
				$err = print_r($statement->errorInfo(), true);
				throw new Exception($err);
			}
			$tagrows = $statement->fetchAll(PDO::FETCH_ASSOC);
			$count1 = 0;
			$tags = array();
			while($count1 < count($tagrows)) {
				$statement = $db->prepare('SELECT * FROM tags WHERE tagID=:tagid');
				$statement->bindValue(':tagid', $tagrows[$count1]['tagID'], PDO::PARAM_STR);
				if (!$statement->execute())
				{
					$err = print_r($statement->errorInfo(), true);
					throw new Exception($err);
				}
				$tags[] = $statement->fetchAll(PDO::FETCH_ASSOC);
				$count1++;
			}
			$postrows[$count]['tags'] = $tags;
			$count++;
		}
		return $postrows;

	}

	public static function getPostsFromSearch($query) {
		$db = new PDO('sqlite:' . '../app/database/production.sqlite');
		$statement = $db->prepare('SELECT * FROM posts WHERE title LIKE :query OR content LIKE :query ORDER BY id DESC LIMIT 10');
		$statement->bindValue(':query', '%'.$query.'%', PDO::PARAM_STR);
     	if (!$statement->execute())
		{
			$err = print_r($statement->errorInfo(), true);
			throw new Exception($err);
		}
		$postrows = $statement->fetchAll(PDO::FETCH_ASSOC);
		return Post::getTagsForPost($postrows);
	}
}