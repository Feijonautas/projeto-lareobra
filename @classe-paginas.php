<?php

    if(!class_exists("Paginas")){
        class Paginas{
            public $titulo;
            public $descricao;
            public $empresa;
            public $logo;
            public $base_path;
            private $email_user;
            private $smtp_host;
            private $smtp_user;
            private $smtp_pass;
            private $smtp_port;
            
            function __construct(){
                $this->empresa     = "Lar e Obra";
                
                $this->logo        = "logo-lareobra.png";
                
                $this->base_path   = "www.lareobra.com.br/dev";
                
                $this->email_user  = "contato@lareobra.com.br";
                
                $this->smtp_host   = "smtps.f1.ultramail.com.br";
                
                $this->smtp_user   = "contato=lareobra.com.br";
                
                $this->smtp_pass   = "admlareobra@123";
                
                $this->smtp_port   = 587;
            }
            
            function set_titulo($titulo){
                $this->titulo = $titulo . " - " . $this->empresa;
            }
            
            function set_descricao($descricao){
                $this->descricao = $descricao;
            }
            
            function get_email_user(){
                return $this->email_user;
            }
            
            function get_email_pass(){
                return $this->email_pass;
            }
            
            function get_smtp_host(){
                return $this->smtp_host;
            }
            
            function get_smtp_user(){
                return $this->smtp_user;
            }
            
            function get_smtp_pass(){
                return $this->smtp_pass;
            }
            
            function get_smtp_port(){
                return $this->smtp_port;
            }
        }
    }

    $cls_paginas = new Paginas();