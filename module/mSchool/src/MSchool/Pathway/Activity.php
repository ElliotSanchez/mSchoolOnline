<?php

namespace MSchool\Pathway;

use Admin\Resource\Entity as Resource;

class Activity {

    protected $resource;
    protected $timer;
    protected $showPopup;

    public function __construct() {
        $this->timer = null;
        $this->showPopup = false;
    }

    /**
     * @return bool
     */
    public function isTimed()
    {
        return (bool) $this->timer;
    }

    /**
     * @param Resource $resource
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param int $timer
     */
    public function setTimer($timer)
    {
        $this->timer= $timer;
    }

    /**
     * @return int
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * @param int $showPopup
     */
    public function setShowPopup($showPopup)
    {
        $this->showPopup = $showPopup;
    }

    /**
     * @return int
     */
    public function getShowPopup()
    {
        return $this->showPopup;
    }

}