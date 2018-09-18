<?php

//HEaders
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../models/post.php';

//Instace DB and Conn
$database = new Database() ;
$db = $database->connect();

//Intance blog post

$post = new Post($db);

// bloog post query

$result = $post->read();
//get row count
$num = $result->rowCount();

//check if any posts
if($num > 0) {
    //post array
    $post_arr = array();
    $posts_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        $post_item - array(
            'id' =>$id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name
            
        );

        //Push to data
        array_push($posts_arr['data'], $post_item);
    }

    //turn to json
    echo json_encode($posts_arr);

} else {
//no posts

echo json_encode(
    array('message' => 'no posts found' )
);
}

