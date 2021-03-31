<?php
try{
    $upload_filename = $_FILES["fileupload"]['name'];
    $tmp = explode('.', $upload_filename);
    $ext = end($tmp);
    $fname = current(explode('.',$upload_filename));

    $n = 0;
    //checking if file already exist with same name
    $upload = 'upload/'.$fname.'.'.$ext;

    while(file_exists($upload)){
        $upload='upload/'.$fname.$n.'.'.$ext;
        $n++;
    }

    //$dest = 'upload/' . $fname . '.' . $ext;
    move_uploaded_file($_FILES['fileupload']['tmp_name'], $upload);
    $result = ["success",$upload];

    echo json_encode($result);

}catch(Exception $e){
    die("file couldn't be uploaded ".$e->getMessage());
}
