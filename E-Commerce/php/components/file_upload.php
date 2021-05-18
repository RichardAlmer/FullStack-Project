<?php
function file_upload($image, $source = 'user')
{
    $result = new stdClass();//this object will carry status from file upload
    $result->fileName = 'default-image.jpg';
    $result->error = 1;//it could also be a boolean true/false
    //collect data from object $image
    $fileName = $image["name"];
    $fileType = $image["type"];
    $fileTmpName = $image["tmp_name"];
    $fileError = $image["error"];
    $fileSize = $image["size"];
    $fileExtension = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));    
    $filesAllowed = ["png", "jpg", "jpeg"];
    if ($fileError == 4) {       
        $result->ErrorMessage = "No image was chosen. It can always be updated later.";
        return $result;
    } else {
        if (in_array($fileExtension, $filesAllowed)) {
            if ($fileError === 0) {
                if ($fileSize < 1500000) { //1500kb this number is in bytes
                    //it gives a file name based microseconds
                    $fileNewName = uniqid('') . "." . $fileExtension; // 1233343434.jpg i.e
                    if($source == 'offer'){
                        $destination = "../../img/$fileNewName";
                    }elseif ($source == 'user'){
                        $destination = "img/$fileNewName";
                    }                    
                    if (move_uploaded_file($fileTmpName, $destination)) {
                        $result->error = 0;
                        $result->fileName = $fileNewName;
                        return $result;
                    } else {    
                        $result->ErrorMessage = "There was an error uploading this file.";
                        return $result;
                    }
                } else {                                      
                    $result->ErrorMessage = "This image is bigger than the allowed 500Kb. <br> Please choose a smaller one and Update your profile.";
                    return $result;
                }
            } else {                              
                $result->ErrorMessage = "There was an error uploading - $fileError code. Check php documentation.";
                return $result;
            }
        } else {                      
            $result->ErrorMessage = "This file type cant be uploaded.";
            return $result;
        }
    }
}