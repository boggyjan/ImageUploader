<?
  
  #php sample

  $files = array();

  for($i = 0; $i < count($_FILES['images']['name']); $i++) {
    $target_path = "upload/";
    $ext = explode('.', basename( $_FILES['images']['name'][$i]));
    $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext)-1]; 

    if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_path)) {
      array_push($files, array(
        'filename' => $_FILES['images']['name'][$i],
        'path' => $target_path,
        'status' => 'done'
      ));
    } else{
      array_push($files, array(
        'filename' => $_FILES['images']['name'][$i],
        'path' => $target_path,
        'status' => 'fail'
      ));
    }
  }
  
  echo json_encode($files);


  // 以下是array的結構
  // [
  //   {
  //     filename: ''
  //     path: ''
  //     status: 'done' or 'fail'
  //   },
  //   {
  //     filename: ''
  //     path: ''
  //     status: 'done' or 'fail'
  //   }
  // ]
?>