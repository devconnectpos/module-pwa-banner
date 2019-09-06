<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 3:39 PM
 */

namespace SM\PWABanner\Api\Data;


interface BannerInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const BANNER_ID             = 'banner_id';
    const BANNER_URL            = 'banner_url';
    const IS_ACTIVE             = 'is_active';
    const CREATED_AT            = 'created_at';
    const UPDATED_AT            = 'updated_at';
    /**#@-*/


    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getBannerUrl();

    /**
     * @return int
     */
    public function getIsActive();

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @param string  $url
     * @return $this
     */
    public function setBannerUrl($url);

    /**
     * @param int $is_active
     * @return $this
     */
    public function setIsActive($is_active);

    /**
     * @param string $date
     * @return $this
     */
    public function setCreatedAt($date);

    /**
     * @param string $date
     * @return $this
     */
    public function setUpdatedAt($date);


}