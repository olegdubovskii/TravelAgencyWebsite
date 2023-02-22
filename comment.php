<?php  
    if(count($_COOKIE) == 0)
        exit('Error!!!');
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);

    $message = htmlspecialchars($_POST['comments']);
    $message = trim($_POST['comments']);
    $message = preg_replace('(https?:\/\/(?:www\.)?((?:\b(?!bsuir)\w+\.[a-zA-Z]{2,4}\b))(?:\/.*)?)','#Внешние ссылки запрещены#',$message);

    $filecontents = file_get_contents('comments.txt');

    file_put_contents("comments.txt",$filecontents
                                    ."Name: " . $name 
                                    . "\nTelephone: " . $telephone
                                    . "\nEmail: " . $email
                                    . "\nComments: " . $message . "\n\n" 
                                    );
                                    
    //header ("Location: ".$_SERVER['HTTP_REFERER']);

    $subject  = 'Testing';
    $headers  = 'From: bsuirtest.ivanov@yandex.ru' . "\r\n" .
            'Reply-To: bsuirtest.ivanov@yandex.ru' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
    if(mail($email, $subject, $message, $headers))
        header ("Location: ".$_SERVER['HTTP_REFERER']);
    else
        echo "Email sending failed";
?>