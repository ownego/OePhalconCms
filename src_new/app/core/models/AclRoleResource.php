<?php
        		        		
namespace App\Models;

use App\Models\Base as BaseModel;

class AclRoleResource extends BaseModel
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $id_acl_role;

    /**
     *
     * @var integer
     */
    protected $id_acl_resource;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $created_at;

    /**
     *
     * @var integer
     */
    protected $updated_at;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field id_acl_role
     *
     * @param integer $id_acl_role
     * @return $this
     */
    public function setIdAclRole($id_acl_role)
    {
        $this->id_acl_role = $id_acl_role;

        return $this;
    }

    /**
     * Method to set the value of field id_acl_resource
     *
     * @param integer $id_acl_resource
     * @return $this
     */
    public function setIdAclResource($id_acl_resource)
    {
        $this->id_acl_resource = $id_acl_resource;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param integer $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field updated_at
     *
     * @param integer $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_acl_role
     *
     * @return integer
     */
    public function getIdAclRole()
    {
        return $this->id_acl_role;
    }

    /**
     * Returns the value of field id_acl_resource
     *
     * @return integer
     */
    public function getIdAclResource()
    {
        return $this->id_acl_resource;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field created_at
     *
     * @return integer
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field updated_at
     *
     * @return integer
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function getSource()
    {
        return 'acl_role_resource';
    }

}
