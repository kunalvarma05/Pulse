<?php
namespace Pulse\Services\Manager\File;

class File implements FileInterface
{

    protected $id;
    protected $title;
    protected $path;
    protected $modified;
    protected $size;
    protected $isFolder;
    protected $thumbnail;
    protected $type;
    protected $url;
    protected $downloadURL;
    protected $icon;
    protected $owners = [];


    /**
     * Get File Id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get File Title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get File Path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get File Modified
     * @return string
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Get File Size
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * isFolder
     * @return bool
     */
    public function isFolder()
    {
        return $this->isFolder;
    }

    /**
     * Get File ThumbnailURL
     * @return string
     */
    public function getThumbnailURL()
    {
        return $this->thumbnail;
    }

    /**
     * Get File MimeType
     * @return string
     */
    public function getMimeType()
    {
        return $this->type;
    }

    /**
     * Get URL
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * Get File DownloadURL
     * @return string
     */
    public function getDownloadURL()
    {
        return $this->downloadURL;
    }

    /**
     * Get File Icon
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get Owners
     * @return string Comma separated names of owners
     */
    public function getOwners()
    {
       $owners = $this->owners;

        if(!$owners || empty($owners))
        {
            return "";
        }

        return implode(", ", $owners);
    }


    /**
     * Set File Id
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set File Title
     * @return string
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set File Path
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Set File Modified
     * @param string $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    /**
     * Set File Size
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Set File IsFolder
     * @param boolean $isFolder
     */
    public function setIsFolder($isFolder)
    {
        $this->isFolder = $isFolder;
    }

    /**
     * Set URL
     * @param string $url
     */
    public function setURL($url)
    {
        $this->url = $url;
    }

    /**
     * Set File ThumbnailURL
     * @param string $thumbnail
     */
    public function setThumbnailURL($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * Set File MimeType
     * @param string $type
     */
    public function setMimeType($type)
    {
        $this->type = $type;
    }

    /**
     * Set File DownloadURL
     * @param string $downloadURL
     */
    public function setDownloadURL($downloadURL)
    {
        $this->downloadURL = $downloadURL;
    }

    /**
     * Set File Icon
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * Set Owners
     * @param array $owners
     */
    public function setOwners($owners)
    {
        $this->owners = $owners;
    }


}