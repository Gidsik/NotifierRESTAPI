<?php
  if ($islogged){
    echo "hello! <br>";
    echo $login.'<br>';
    echo $pswd;
  }else{
    echo "u r not ";
    echo $login.'! <br>';
    echo "or try another password, not this ->".$pswd;
  }
 ?>
