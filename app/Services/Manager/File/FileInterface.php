<?php
namespace Pulse\Services\Manager\File;

interface FileInterface
{
    /***********
     * Getters
     ***********/

    /**
     * Get File Id
     * @return string
     */
    public function getId();

    /**
     * Get File Title
     * @return string
     */
    public function getTitle();

    /**
     * Get File Path
     * @return string
     */
    public function getPath();
    /**
     * Get File Modified
     * @return string
     */
    public function getModified();
    /**
     * Get File Size
     * @return string
     */
    public function getSize();
    /**
     * isFolder
     * @return bool
     */
    public function isFolder();
    /**
     * Get File ThumbnailURL
     * @return string
     */
    public function getThumbnailURL();

    /**
     * Get URL
     * @return string
     */
    public function getURL();

    /**
     * Get File MimeType
     * @return string
     */
    public function getMimeType();
    /**
     * Get File DownloadURL
     * @return string
     */
    public function getDownloadURL();
    /**
     * Get File Icon
     * @return string
     */
    public function getIcon();

    /**
     * Get Owners
     * @return string Comma separated names of owners
     */
    public function getOwners();

    /***********
     * Setters
     ***********/

    /**
     * Set File Id
     * @param string $id
     */
    public function setId($id);

    /**
     * Set File Title
     * @return string
     */
    public function setTitle($title);

    /**
     * Set File Path
     * @param string $path
     */
    public function setPath($path);

    /**
     * Set File Modified
     * @param string $modified
     */
    public function setModified($modified);

    /**
     * Set File Size
     * @param string $size
     */
    public function setSize($size);

    /**
     * Set File IsFolder
     * @param boolean $isFolder
     */
    public function setIsFolder($isFolder);

    /**
     * Set File ThumbnailURL
     * @param string $thumbnail
     */
    public function setThumbnailURL($thumbnail);

    /**
     * Set URL
     * @param string $url
     */
    public function setURL($url);

    /**
     * Set File MimeType
     * @param string $type
     */
    public function setMimeType($type);

    /**
     * Set File DownloadURL
     * @param string $downloadURL
     */
    public function setDownloadURL($downloadURL);

    /**
     * Set File Icon
     * @param string $icon
     */
    public function setIcon($icon);

    /**
     * Set Owners
     * @param array $owners
     */
    public function setOwners($owners);
}
