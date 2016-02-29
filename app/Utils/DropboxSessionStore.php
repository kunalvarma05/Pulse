<?php
namespace App\Utils;

use \Dropbox\ArrayEntryStore;
use \Illuminate\Session\Store;

class DropboxSessionStore extends ArrayEntryStore{

    private $session;

    private $key;

    public function __construct(Store $session, $key = "Dropbox-oauth-key"){
        $this->key = $key;
        $this->session = $session;
    }

    public function get(){
        if($this->session->has($this->key)){
            return $this->session->get($this->key);
        }

        return null;
    }

    public function set($value){
        $this->session->set($this->key, $value);
        $this->session->save();
    }

    public function clear(){
        if($this->session->has($this->key)){
            $this->session->forget($this->key);
        }
    }

}