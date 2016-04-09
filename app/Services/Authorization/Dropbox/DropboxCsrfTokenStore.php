<?php
namespace Pulse\Services\Authorization\Dropbox;

use \Dropbox\ArrayEntryStore;
use \Illuminate\Session\Store;

class DropboxCsrfTokenStore implements DropboxCsrfTokenStoreInterface
{

    private $store;

    private $key;

    public function __construct(Store $store, $key = "Dropbox-oauth-key")
    {
        $this->key = $key;
        $this->store = $store;
    }

    public function get()
    {
        if($this->store->has($this->key))
        {
            return $this->store->get($this->key);
        }

        return null;
    }

    public function set($value)
    {
        $this->store->set($this->key, $value);
        $this->store->save();
    }

    public function clear()
    {
        if($this->store->has($this->key))
        {
            $this->store->forget($this->key);
        }
    }

}