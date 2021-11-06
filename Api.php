<?php

class Api  {

    // specify your own api credentials
    private $url;
    private $password;
    private $username;

    // constructor with $db as database connection
    public function __construct($url, $username, $password)
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
    }

    public function getUrl()
    {
        return $this->url;

    }

    public function getUsername()
    {
        return $this->username;

    }

    public function getPassword()
    {
        return $this->password;

    }

    public function setUrl($url)
    {
        return $this->url = $url;

    }

    public function setUsername($username)
    {
        return $this->username = $username;

    }

    public function setPassword($password)
    {
        return $this->password = $password;

    }

    public function fetchData() {
        $ch = curl_init($this->getUrl());
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERPWD, $this->getUsername() . ":" . $this->getPassword());
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $data = json_decode($data, true);
        curl_close($ch);
        return $data;
    }

}