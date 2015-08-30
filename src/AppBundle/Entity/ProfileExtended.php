<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model as Model;

/**
 * Profile
 *
 * @ORM\Entity
 * @ORM\Table(name="profile_extended")
 */
class ProfileExtended
{
    use Model\IdClassTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="profile_id", type="integer", nullable=false)
     */
    private $profileId;

    /**
     * @ORM\OneToOne(targetEntity="Profile", inversedBy="profileExtended")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     **/
    private $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="description_extended", type="text", nullable=false)
     */
    protected $descriptionExtended;

    public function __toString()
    {
        return $this->descriptionExtended;
    }

    /**
     * Get profileId
     *
     * @return integer 
     */
    public function getProfileId()
    {
        return $this->profileId;
    }

    /**
     * Set profile
     *
     * @param Profile $profile
     * @return ProfileExtended
     */
    public function setProfile(Profile $profile)
    {
        $this->profile = $profile;
        $this->profileId = $profile->getId();
    
        return $this;
    }

    /**
     * Get profile
     *
     * @return Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set descriptionExtended
     *
     * @param string $descriptionExtended
     * @return ProfileExtended
     */
    public function setDescriptionExtended($descriptionExtended)
    {
        $this->descriptionExtended = $descriptionExtended;
    
        return $this;
    }

    /**
     * Get descriptionExtended
     *
     * @return string 
     */
    public function getDescriptionExtended()
    {
        return $this->descriptionExtended;
    }
}
