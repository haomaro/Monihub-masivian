<?php

namespace Servicios\Masivian;

class correo
{

  public $endpoint = 'https://api.masiv.co/SmsHandlers/sendhandler.ashx';
  private $usuario;
  private $password;
  private $json;
  private $adj;


  public function __construct(){
    $this->setUsuario('Api_ZOO1M');
    $this->setPassword('TSSLZFO919');
  }

  public function parametros(
    $from,
    $from_name,
    $subject,
    $to,
    $html_content,    
    $reply = "soporte@monihub.com", 
    $copy = "soporte@monihub.com"){

    $json = '{
      "Subject":"'.$subject.'",
      "From": "'.$from_name.'<'.$from.'>",
      "Template" : {
          "Type" : "text/html", 
          "Value":"'.$html_content.'"
      },      
      "ReplyTo" : "'.$reply.'",
      ';

      $json .= $this->adjuntos();

      $json .='"Recipients" : [
          { 
              "To":"<'.$to.'>"
          },
          { 
              "To":"<'.$copy.'>"
          }
      ]
    }';
    //die($json);    
    $this->setJson($json);
  }

  public function envio(){             
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.masiv.masivian.com/email/v1/delivery',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_USERPWD => $this->getUsuario() . ":" . $this->getPassword(),
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => 1,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $this->getJson(),
      CURLOPT_HTTPHEADER => array(
          'Authorization: Basic QXBpX1pPTzFNOlRTU0xaRk85MTk=',
          'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    

  }

  public function adjuntos(){

    $adjuntos = $this->getAdj();
    if(is_array($adjuntos)){
      $json = '"Attachments" : [ ';
      foreach($adjuntos as $adjunto){
        $json .= '{"Path" : "'.$adjunto['url'].'", "Filename":"'.$adjunto['nombre'].'"},';
      }
      $json .= '],';
    }

    return $json;
  }

  public function setUsuario($usuario){
    $this->usuario = $usuario;
  }

  public function getUsuario(){
    return $this->usuario;
  }

  public function setPassword($password){
    $this->password = $password;
  }

  public function getPassword(){
    return $this->password;
  }

  public function setJson($json){
    $this->json = $json;
  }

  public function getJson(){
    return $this->json;
  } 

  public function setAdj($adj){
    $this->adj = $adj;
  }

  public function getAdj(){
    return $this->adj;
  }

}