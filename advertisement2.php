<?php

require_once "VerificationClass.php";

class Advt2 extends Verify
{
    protected $hostname;
    protected $dbname;
    protected $username;
    protected $passw;
    protected $charset;
    
    protected $ADname;
    protected $ADphone;
    protected $ADemail;
    protected $ADtext;
    
    protected $ourcode;
    
    public function __construct()
    {
        require_once "pdodata.php";
        
        $this->ADname = $_POST['name2'];
        $this->ADphone = $_POST['phone2'];
        $this->ADemail = $_POST['email2'];
        $this->ADtext = $_POST['text2'];
    }
  
    ///////////////////////////////////
    
    protected function Verification()
    {
        $this->RequiredFieldsVerification();
        $this->AdvertVerification(200, $this->ADtext);
        $this->PhoneVerification(16, $this->ADphone);
        $this->EmailVerification(50, $this->ADemail);
        $this->NameVerification(50, $this->ADname);
    }
    
    ///////////////////////////////////
    
    protected function ExistData()
    {
        $dsn = "mysql:host=$this->hostname;dbname=$this->dbname;charset=$this->charset";
        $pdoVar = new PDO($dsn, $this->username, $this->passw);
        
        // Число строк в таблице Table1
        $statement = $pdoVar->query('SELECT COUNT(*) FROM Table1');
        $chislostrok = ($statement->fetchColumn());
        
        // Проверяем в таблице Table1 существование совпадения по уникальному индексу Email1 и Text1
        $statement = $pdoVar->query('SELECT Email1, Text1 FROM Table1');
        for ($j = 1; $j <= $chislostrok; $j++) {
            $stroka = $statement->fetch();
            if (($stroka['Email1'] == ($this->ADemail)) && ($stroka['Text1'] == ($this->ADtext))) {
                echo "Вы ввели объявление, которое для данного электронного адреса уже существует </br> Пожалуйста, вернитесь назад и если желаете, создайте другое объявление";
                exit;
            }
        }
    }
    
    ///////////////////////////////////
    
    protected function Confirmation()
    {
        $this->ourcode = rand(10000, 99999);
        session_start();
        $_SESSION['CodeConfirm'] = $this->ourcode;
        
        // Сохраним в массиве $_SESSION также другие введённые данные
        $_SESSION['SName'] = $this->ADname;
        $_SESSION['SEmail'] = $this->ADemail;
        $_SESSION['SPhone'] = $this->ADphone;
        $_SESSION['SText'] = $this->ADtext;
        
        $message = "Ваш код подтверждения публикации объявления: ".($this->ourcode)."</br>";
        $resMail = mail($this->ADemail, 'Код подтверждения', $message);
        if ($resMail !== true) {
            echo "Почту отправить невозможно. Проверьте настройки почтового сервера </br>";
            echo "$message </br>";
            #exit;
        }
    }
    
    ///////////////////////////////////
    
    // Ввод кода активации для публикации объявления
    public function CodeFunction()
    {
        echo "<html> \n <head> \n <title> \n";
        echo "Код для публикации объявления";
        echo "</title> \n <meta charset = \"utf-8\">";
        echo "</head> \n <body> \n";
        
        // Проверим на ошибки введённые данные
        $this->Verification();
          
        // Проверим введённые данные на существование их в базе по уникальному индексу Email1 и Text1
        $this->ExistData();
        
        // Создадим код подтверждения публикации объявления и отправим его на e-mail
        $this->Confirmation();
        
        echo "Введите код подтверждения публикации объявления, который пришёл вам на e-mail </br>";
        echo "<form action=\"advertisement3.php\" method=\"post\">";
        echo "<input type = \"text\" name = \"code3\" size = \"20\"> </br>";
        echo "<p><input type=\"submit\" value=\"Ввести код подтверждения\"></p>";
        echo "</form>";
    }
}

$var = new Advt2();
$var->CodeFunction();
