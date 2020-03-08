<?php
//Checking Uploaded file and creating variables
  if(isset($_POST['submit'])){
    //Initiating Variables
    $photo = $_POST['photoname'];
    $date = $_POST['date'];
    $photographer = $_POST['photographer'];
    $location = $_POST['location'];

    //File Initiation
    $file = $_FILES['file'];
    $fileError = $file["error"];
    $fileSize = $file["size"];
    $path = $_FILES['file']['name'];
    $fileExt = pathinfo($path, PATHINFO_EXTENSION);
    $allow = array("jpg", "JPEG", "jpeg", "png");

    //Checking if photo file uploaded correctly
    if(in_array($fileExt, $allow)){
      if($fileError == 0){
        if($fileSize < 5000000){
          $imgName = $path;
          $fileDest = "./uploads/" . $imgName; //USED TO UPLOAD PHOTOS TO FOLDER

          //Creating empty arrays
          $arrImg = [];
          $arrPhoto = [];
          $arrDate = [];
          $arrPhotographer = [];
          $arrLocation = [];


          move_uploaded_file($_FILES["file"]["tmp_name"], $fileDest);

          //Sends to meta data to data.txt
          $dataFile = 'data.txt';
          $metadata = $fileDest . ',' . $photo . ',' . $photographer . ',' . $location . ',' . $date . "\n";
          file_put_contents('data.txt', $metadata, FILE_APPEND);
        }
        else{
          echo "File size too BIG! Unable to upload";
        }
      }
      else{
        echo "ERROR!! Unable to upload photo!";
      }
    }
    else{
      echo "INCORRECT FILE TYPE!";
      exit();
    }
  }

//The function used to find the index of a value in an unsorted array
    function findIndex($original, $search){
      for($i = 0; $i<count($original); $i++){
        if($original[$i] == $search){
          return $i;
        }
      }
    }

//Used to compare by usort()
  function cmp($a,$b){
    if ($a==$b) return 0;
    return ($a<$b)?-1:1;
  }

 ?>

 <!DOCTYPE <!DOCTYPE html>
 <html>
   <head>
     <style>
     .gallery-container a div{
       width:100%;
       height: 235px;
       background-color: red;
       background-position: center;
       background-repeat: no-repeat;
       background-size: contain;
     }

     .imggallery {
       background-image: url(<?php $fileDest ?>);
       background-color: lightgray;
       border-style: solid;
       margin-left: auto;
       margin-right: auto;
       height: 200px;
       width:300px;
       background-position: center;
       background-repeat: no-repeat;
       background-size: contain;
       position: relative;
       padding: 7px;
     }

     .gallery {
       border: 0px solid #ccc;
     }

     .gallery:hover {
       border: 0.5px solid #77;
     }


     .desc {
       padding: 10px;
       font-size: 15px;
       text-align: left;
     }

     .responsive {
       padding: 0 6px;
       float: left;
       width: 25%;
     }
     </style>

     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta http-equiv="x-ua-compatible" content="ie=edge">
     <title></title>

     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
   </head>
   <body>
     <div class = "container">
       <div class = "page-header">
         <br />
         <h1>View All Photos</h1>
         <hr class = "my-4" />
       </div>

       <div class = "row">
         <!-- A button to upload more photos into the gallery -->
         <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
           <a class="btn btn-primary" href="Index.html" role="button">Upload</a>
         </div>

         <!-- The sorting dropdown mechanism for photos to be sorted in a certain way -->
          <div>
            <form method="POST">
             <label for= "order"><b>Sort By:</b></label>
               <select name="order" id="order" >
                 <option value = "PhotoName" selected>Photo Name</option>
                 <option value = "Date">Date</option>
                 <option value = "Location">Location</option>
                 <option value = "Photographer">Photographer</option>
               </select>
               <input type="submit" name="SUBMIT" />
             </form>
         </div> <!-- END OF SORTING DROPDOWN -->

       </div> <!-- END OF ROW -->
     </div>
       <br />

       <div class = "gallery-container">
         <?php
            $file = fopen("data.txt", "r") or die("UNABLE TO OPEN");
            fread($file,filesize("data.txt"));

            // $result = explode(",", file_get_contents($file));
            $fileArr = file("data.txt", FILE_IGNORE_NEW_LINES);

            //Sets up inserting metadata into arrays
           for($i=0; $i<count($fileArr); $i++){
             $result = [];
             $result = explode(",", $fileArr[$i]);
             $arrImg[$i] = $result[0];
             $arrPhoto[$i] = $result[1];
             $arrDate[$i] = $result[4];
             $arrPhotographer[$i] = $result[2];
             $arrLocation[$i] = $result[3];
           }
           $count = count($fileArr);
           fclose($file);

        if(isset($_POST)){
          $order = $_POST['order'];
        }

        //Switch function for sorting order of photos
          switch($order){
            case "PhotoName":
              $temp = $arrPhoto;
              usort($temp, "cmp");

              for($i=0; $i<$count; $i++){
                $index = findIndex($arrPhoto, $temp[$i]);
                echo '<div class = "responsive">
                      <div class = "gallery">
                        <div class="imggallery" style="background-image: url('.$arrImg[$index].')"></div>
                        <div class = "desc">
                          <b>'.$arrPhoto[$index].'</b><br />
                          Taken By: '.$arrPhotographer[$index].' <br />
                          Location: '.$arrLocation[$index].' <br />
                          '.$arrDate[$index].'<br />
                        </div>
                      </div>
                    </div>';

              }
              break;

            case "Date":
              $temp = $arrDate;
              usort($temp, "cmp");

              for($i=0; $i<$count; $i++){
                $index = findIndex($arrDate, $temp[$i]);
                echo '<div class = "responsive">
                        <div class = "gallery">
                          <div class="imggallery" style="background-image: url('.$arrImg[$index].')"></div>
                          <div class = "desc">
                            <b>'.$arrPhoto[$index].'</b><br />
                            Taken By: '.$arrPhotographer[$index].' <br />
                            Location: '.$arrLocation[$index].' <br />
                            '.$arrDate[$index].'<br />
                          </div>
                        </div>
                      </div>';
              }
              break;

            case "Location":
              $temp = $arrLocation;
              usort($temp, "cmp");

              for($i=0; $i<$count; $i++){
                $index = findIndex($arrLocation, $temp[$i]);
                echo '<div class = "responsive">
                        <div class = "gallery">
                          <div class="imggallery" style="background-image: url('.$arrImg[$index].')"></div>
                          <div class = "desc">
                            <b>'.$arrPhoto[$index].'</b><br />
                            Taken By: '.$arrPhotographer[$index].' <br />
                            Location: '.$arrLocation[$index].' <br />
                            '.$arrDate[$index].'<br />
                          </div>
                        </div>
                      </div>';
              }
              break;

            case "Photographer":
              $temp = $arrPhotographer;
              usort($temp, "cmp");

              for($i=0; $i<$count; $i++){
                $index = findIndex($arrPhotographer, $temp[$i]);
                echo '<div class = "responsive">
                        <div class = "gallery">
                          <div class="imggallery" style="background-image: url('.$arrImg[$index].')"></div>
                          <div class = "desc">
                            <b>'.$arrPhoto[$index].'</b><br />
                            Taken By: '.$arrPhotographer[$index].' <br />
                            Location: '.$arrLocation[$index].' <br />
                            '.$arrDate[$index].'<br />
                          </div>
                        </div>
                      </div>';
              }
              break;

            default:
               $temp = $arrPhoto;
               usort($temp, "cmp");

               for($i=0; $i<$count; $i++){
                 $index = findIndex($arrPhoto, $temp[$i]);
                 echo '<div class = "responsive">
                        <div class = "gallery">
                          <div class="imggallery" style="background-image: url('.$arrImg[$index].')"></div>
                          <div class = "desc">
                            <b>'.$arrPhoto[$index].'</b><br />
                            Taken By: '.$arrPhotographer[$index].' <br />
                            Location: '.$arrLocation[$index].' <br />
                            '.$arrDate[$index].'<br />
                           </div>
                        </div>
                      </div>';

              }

          }


        ?>
    </div> <!-- END OF THE CONTAINTER -->





     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
   </body>
 </html>
