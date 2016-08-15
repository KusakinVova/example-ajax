<?php
header('Content-type: application/json');
if($_POST){

	// $result = $_POST;
/*===================================================================================================*/
function mime_header_encode($str, $data_charset, $send_charset) 
{ // функция преобразования заголовков в верную кодировку 
        if($data_charset != $send_charset)
        $str=iconv($data_charset,$send_charset.'//IGNORE',$str);
        return ('=?'.$send_charset.'?B?'.base64_encode($str).'?=');
}

/* супер класс для отправки письма в нужной кодировке */
class TEmail 
{
        public $from_email;
        public $from_name;
        public $to_email;
        public $to_name;
        public $subject;
        public $data_charset='UTF-8';
        public $send_charset='windows-1251';
        public $body='';
        public $type='text/plain';

        function send()
        {
                $dc=$this->data_charset;
                $sc=$this->send_charset;
                $enc_to=mime_header_encode($this->to_name,$dc,$sc).' <'.$this->to_email.'>';
                $enc_subject=mime_header_encode($this->subject,$dc,$sc);
                $enc_from=mime_header_encode($this->from_name,$dc,$sc).' <'.$this->from_email.'>';
                $enc_body=$dc==$sc?$this->body:iconv($dc,$sc.'//IGNORE',$this->body);
                $headers='';
                $headers.="Mime-Version: 1.0\r\n";
                $headers.="Content-type: ".$this->type."; charset=".$sc."\r\n";
                $headers.="From: ".$enc_from."\r\n";
                return mail($enc_to,$enc_subject,$enc_body,$headers);
        }

}
/*===================================================================================================*/


$name = iconv("windows-1251", "windows-1251", htmlspecialchars($_POST["name"])); // пишем данные в переменные и экранируем спецсимволы
$phone = iconv("windows-1251", "windows-1251", htmlspecialchars($_POST["phone"]));

$emailgo= new TEmail; // инициализируем супер класс отправки
$emailgo->from_email= 'otkogo@mail.ru'; // от кого рабочий почтовый ящик должен быть указан
$emailgo->from_name= 'OtKogo'; // подпись письма
$emailgo->to_email= 'komy@mail.ru'; // кому
$emailgo->to_name= 'main'; // имя для кого
$emailgo->subject= 'Заголовок письма'; // тема
$emailgo->body= 'Тело письма заполнено
Имя: '.$name.'
Телефон: '.$phone;
        
$emailgo->send();


print json_encode("Письмо отправлено!\n\r".$emailgo->body); // отправляе ответное письмо 
}
else echo "WHAT?!";
?>
