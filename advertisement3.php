<?php

class Advt3
{
    protected $hostname;
    protected $dbname;
    protected $username;
    protected $passw;
    protected $charset;
    
    protected $CodeVerif;
    
    public function __construct()
    {
        $this->CodeVerif = $_POST['code3'];
        require_once "pdodata.php";
    }
  
    ///////////////////////////////////
    
    // Ввод кода активации для публикации объявления
    public function ResultFunction()
    {
        echo "<html> \n <head> \n <title> \n";
        echo "Публикация объявления";
        echo "</title> \n <meta charset = \"utf-8\">";
        echo "<link rel = \"stylesheet\" href = \"ourstyles.css\">";
        echo "</head> \n <body> \n";
        
        // Проверим код подтверждения
        session_start();
        
        if (($this->CodeVerif) != ($_SESSION['CodeConfirm'])) {
            echo "Вы ввели неправильный код подтверждения. Ваше объявление неопубликовано </br> Если желаете, можете вернуться назад и создать объявление снова";
            $_SESSION = array();
            session_destroy();
            exit;
        }
        
        // Зарегистрируем объявление в базе и опубликуем его
        $this->RegistrationInBase();
        
        echo "</body> \n </html> \n";
    }
   
     ///////////////////////////////////
   
    protected function RegistrationInBase()
    {
        $dsn = "mysql:host=$this->hostname;dbname=$this->dbname;charset=$this->charset";
        $pdoVar = new PDO($dsn, $this->username, $this->passw);
        
        $statement = $pdoVar->prepare("INSERT INTO Table1 (Name1, Phone1, Email1, Text1, Date1) VALUES (:Name1, :Phone1, :Email1, :Text1, :Date1)");
        $statement->bindValue(':Name1', $_SESSION['SName']);
        $statement->bindValue(':Phone1', $_SESSION['SPhone']);
        $statement->bindValue(':Email1', $_SESSION['SEmail']);
        $statement->bindValue(':Text1', $_SESSION['SText']);
        $statement->bindValue(':Date1', date("d-m-Y H:i:s"));
        $statement->execute();
        
        $_SESSION = array();
        session_destroy();
        
        // Опубликуем объявления
        // Число строк в таблице Table1
        $statement = $pdoVar -> query('SELECT COUNT(*) FROM Table1');
        $chislostrok = ($statement->fetchColumn());
        
        $statement = $pdoVar->query('SELECT Name1, Phone1, Email1, Text1, Date1 FROM Table1');
        for ($j = 1; $j <= $chislostrok; $j++) {
            $stroka = $statement->fetch();
            
            echo "<p id = \"advertname\">". $stroka['Name1'] ."</p>";
            echo "<p id = \"advertemailphone\">". $stroka['Phone1'] ."</p>";
            echo "<p id = \"advertemailphone\">". $stroka['Email1'] ."</p>";
            echo "<p id = \"advertemailphone\">". $stroka['Date1'] ."</p>";
            echo "<p id = \"adverttext\">". $stroka['Text1'] ."</p>";
            echo "<hr>";
        }
        $pdoVar = null;
    }
}

$var = new Advt3();
$var->ResultFunction();
